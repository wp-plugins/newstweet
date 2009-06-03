//making a global array to store the IDs
tweets = new Array();

String.prototype.linkify=function(){
	return this.replace(/[A-Za-z]+:\/\/[A-Za-z0-9-_]+\.[A-Za-z0-9-_:%&\?\/.=]+/g,
	function(m){
	return m.replace(m,'<a href="'+m+'" target="_blank">link&raquo;</a>'); // This line replaces links with "link>>" to keep the lines short.
	});}

String.prototype.linkuser=function(){
	return this.replace(/[@]+[A-Za-z0-9-_]+/g,
	function(u){
	var username=u.replace("@","")
	return u.link("http://twitter.com/"+username);});};

String.prototype.linktag=function(){
	return this.replace(/[#]+[A-Za-z0-9-_]+/,
	function(t){
	var tag=t.replace("#","%23")
	return t.link("http://search.twitter.com/search?q="+tag);});};


//function shows the poll screen
function gotoPoll(){
	var pars = 'searchterm=' + NewsTweetSearchterm+ '&rpp=' + NewsTweetRpp;
	jQuery.ajax({
	   type: "POST",
	   url: NewsTweetAjaxUrl,
	   data: pars,
	   success: function(msg){
				
				var myObj = eval( '(' + msg + ')' );
				
				var temp = jQuery("#twitterwrapper").html();
				
				//container innerHTML
				var html = '<div id="tweetholder">';
				
				
				
				//loop thru all results ----------------
				for(i=0;i<myObj.results.length;i++){
				
				//parse the date
				var thedate = new Date(Date.parse(myObj.results[i].created_at));
				var months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sept","Oct","Nov","Dec"];
				var hours = thedate.getHours();
				var minutes = thedate.getMinutes(); if (minutes < 10){ minutes = "0" + minutes; }
				var ampm = "am";  if (hours > 11){ ampm = "pm"; } if (hours > 12) { hours = hours - 12; } if (hours == 0) { hours = 12; }
				var thedatestr=months[thedate.getMonth()]+' '+thedate.getDate()+' @ '+hours+':'+minutes+' '+ampm;
				
				
				
					if(tweets[myObj.results[i].id]){
					
					html += '<div id="'+myObj.results[i].id+'" class="tweet x">';
					html += '<p class="posttext"><wbr>&ldquo;'+myObj.results[i].text.linkify().linkuser()+'&rdquo;</wbr></p>';
					html += '<img class="avatar" src="'+myObj.results[i].profile_image_url+'" alt="'+myObj.results[i].from_user+'" />';
					html += '<p class="posted"><a href="http://www.twitter.com/'+myObj.results[i].from_user+'" target="_blank">'+myObj.results[i].from_user+'</a><br/><span class="date">'+thedatestr+'</span></p>';
					html += '</div>';
					
					} else {
		
					html += '<div id="'+myObj.results[i].id+'" class="tweet '+i+'">';
					html += '<p class="posttext"><em>&ldquo;'+myObj.results[i].text.linkify().linkuser()+'&rdquo;</em></p>';
					html += '<img class="avatar" src="'+myObj.results[i].profile_image_url+'" alt="'+myObj.results[i].from_user+'" />';
					html += '<p class="posted"><a href="http://www.twitter.com/'+myObj.results[i].from_user+'" target="_blank">'+myObj.results[i].from_user+'</a><br/><span class="date">'+thedatestr+'</span></p>';
					html += '</div>';
				
					}
					
					//push the IDs
					tweets[myObj.results[i].id] = true;
				}
				
				html += '<div class="clear"></div></div>';
				
				jQuery("#twitterwrapper").html(html);
				
				
				
				
				jQuery(document).ready(function(){
      			
      			jQuery(".x").show();
      			
      			jQuery(".0").fadeIn(2000);
      			jQuery(".1").fadeIn(1250);
      			jQuery(".2").fadeIn(1500);
                jQuery(".3").fadeIn(1250);
                jQuery(".4").fadeIn(1000);
                jQuery(".5").fadeIn(750);
                jQuery(".6").fadeIn(500);
                jQuery(".7").fadeIn(250);
                jQuery(".8").fadeIn(100);
                jQuery(".9").fadeIn(50);
                        
      			
      			
    			}); // end init
    			
    			
				
			}
	 });
}
