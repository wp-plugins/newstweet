<?php
/*
 * Plugin Name: NewsTweet
 * Plugin URI: http://www.thisistim.biz/newstweet
 * Description: Set a keyword or phrase and NewsTweet constantly pulls twitter posts containing that word or phrase. Useful for bands or anyone with a specific focus for their blog. NewsTweet uses AJAX and the Twitter API so your content stays fresh without the need to refresh the page.
 * Version: 1.0
 * License: GPL
 * Author: Tim Resudek & Todd Resudek
 * Author URI: http://www.thisistim.biz
 * Min WP Version: 2.0.4
 * Max WP Version: 2.5.0+
 */

//-------------------------------------------------------------

	// Plugin class
	if (!class_exists("NewsTweetClass")) {
		class NewsTweetClass {

			//-----------------------------------------
			// Options
			//-----------------------------------------
			var $optionsName = 'NewsTweetOptions';

			var $optionsDefaults = array(
				'searchterm' => '',
				'rpp' => '',
				'title' => '',
				'tweetit' => ''
			);

			//-----------------------------------------
			// Paths
			//-----------------------------------------
			var $pluginPath = '';

			//-----------------------------------------
			function NewsTweetClass() {
				$this->pluginPath = get_option('siteurl') . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__));
			}

			function activate() {
				$this->getOptions();
			}

			//-----------------------------------------

			function getOptions() {
				$options = $this->optionsDefaults;

				$o = get_option($this->optionsName);

				if (!empty($o)) {
					foreach($o as $key => $value) {
						$options[$key] = $value;
					}
				}

				update_option($this->optionsName, $options);

				return $options;
			}

			//-----------------------------------------

			function widget_init() {

				wp_enqueue_script('jquery_cycle', $this->pluginPath.'/js/jquery.cycle.js',array('jquery'));
				wp_enqueue_script('news-tweet-utils', $this->pluginPath.'/js/news-tweet-utils.js');


				if (!function_exists('register_sidebar_widget'))
					return;


				function widget_NewsTweet_output($args = array()) {
					global $NewsTweetInstance;

					if (is_array($args)) 
						extract($args);

					$options = $NewsTweetInstance->getOptions();
					$_searchterm = $options['searchterm'];
					$_rpp = $options['rpp'];
					$_ttl = $options['title'];
					$_tweetit = $options['tweetit'];

					echo $before_widget;
					echo $before_title.$_ttl.$after_title;
					

?>

<script type="text/javascript">
			jQuery(document).ready(function(){
				
				NewsTweetAjaxUrl = '<?php echo($NewsTweetInstance->pluginPath).'/newstweet-ajax.php';?>';
				NewsTweetSearchterm = '<?php echo($_searchterm)?>';
				NewsTweetRpp = '<?php echo($_rpp)?>';

				gotoPoll();
				if(si){clearInterval(si);}
				var si = setInterval('gotoPoll()',60000);

			});

</script>

<div id="newstweet-container">
	<!--<div id="newstweet-header">Realtime <em>"<?php echo(stripslashes($_searchterm));?>"</em> on Twitter:</div>-->
	<div id="twitterwrapper">
	</div>
	<div class="newstweet-clear"></div>
	<p class="newstweet-viewall"><a href='http://search.twitter.com/search?q=<?php echo(urlencode(stripslashes($_searchterm)));?>' target="_blank">View All</a>
		<?php if($options['tweetit'] == 'yes'){ echo '<a href="http://twitter.com/home?status='.$_searchterm.'" target="_blank">Tweet It</a>'; } ?>
	</p>
	<div class="newstweet-clear"></div>
</div>

<?php
					echo $after_widget;
				}

				function widget_NewsTweet_control($args = array()) {
					global $NewsTweetInstance;

					if (is_array($args)) 
						extract($args);

					$options = $NewsTweetInstance->getOptions();

					if ( $_POST['NewsTweet-widget-submit'] ) {
						if (get_magic_quotes_gpc()) {
						}
						$options['searchterm'] = $_POST['NewsTweet-searchterm'];
						$options['rpp'] = $_POST['NewsTweet-rpp'];
						$options['title'] = stripslashes($_POST['NewsTweet-title']);
						$options['tweetit'] = $_POST['NewsTweet-tweetit'];

						update_option($NewsTweetInstance->optionsName, $options);

					}
?>
			<p style="text-align:left;">
			<label for="NewsTweet-title">Widget Title<br/>
			<input style="width: 280px;" id="NewsTweet-title" name="NewsTweet-title" type="text" value="<?php echo $options['title'];?>" />
			</label>
			<label for="NewsTweet-searchterm">Search Term<br/>
			<textarea style="width: 280px;" id="NewsTweet-searchterm" name="NewsTweet-searchterm"><?php echo stripslashes($options['searchterm']);?></textarea>
			</label>
			<label for="NewsTweet-rpp" style="line-height:26px;">Results 
			<select style="width:50px;margin-right:20px;" id="NewsTweet-rpp" name="NewsTweet-rpp"><option value="1" <?php if($options['rpp'] == '1'){ echo 'selected'; } ?> >1</option><option value="2" <?php if($options['rpp'] == '2'){ echo 'selected'; } ?>>2</option><option value="3" <?php if($options['rpp'] == '3'){ echo 'selected'; } ?>>3</option><option value="4" <?php if($options['rpp'] == '4'){ echo 'selected'; } ?> >4</option><option value="5" <?php if($options['rpp'] == '5'){ echo 'selected'; } ?> >5</option><option value="6" <?php if($options['rpp'] == '6'){ echo 'selected'; } ?> >6</option><option value="7" <?php if($options['rpp'] == '7'){ echo 'selected'; } ?> >7</option><option value="8" <?php if($options['rpp'] == '8'){ echo 'selected'; } ?> >8</option><option value="9" <?php if($options['rpp'] == '9'){ echo 'selected'; } ?> >9</option><option value="10" <?php if($options['rpp'] == '10'){ echo 'selected'; } ?> >10</option></select>
			</label>
			<label for="NewsTweet-tweetit" style="line-height:26px;">Show "Tweet It" 
			<select style="width:50px;margin-right:20px;" id="NewsTweet-tweetit" name="NewsTweet-tweetit"><option value="yes" <?php if($options['tweetit'] == 'yes'){ echo 'selected'; } ?> >Yes</option><option value="no" <?php if($options['tweetit'] == 'no'){ echo 'selected'; } ?>>No</option></select>
			</label>
			</p>

			<input type="hidden" id="NewsTweet-widget-submit" name="NewsTweet-widget-submit" value="1" />

<?php
				}

				// Register widget for use
				register_sidebar_widget(array('NewsTweet', 'widgets'), 'widget_NewsTweet_output');

				// Register settings for use, 300x200 pixel form
				register_widget_control(array('NewsTweet', 'widgets'), 'widget_NewsTweet_control', 300, 200);
			}

			//-----------------------------------------


			//-----------------------------------------

			function blogHead() {
		?>
	<link rel="stylesheet" type="text/css" href="<?php echo $this->pluginPath.'/css/style.css'; ?>" />
		<?php
			}
		}
	}

//-------------------------------------------------------------

	// Class initialization
	if (class_exists("NewsTweetClass")) {

		$NewsTweetInstance = new NewsTweetClass();

	}

//-------------------------------------------------------------

	if (isset($NewsTweetInstance)) {


		//Actions
		register_activation_hook(__FILE__,array(&$NewsTweetInstance, 'activate'));


		add_action('wp_head', array(&$NewsTweetInstance, 'blogHead'), 1);

		add_action('widgets_init', array(&$NewsTweetInstance, 'widget_init'));
	}
?>