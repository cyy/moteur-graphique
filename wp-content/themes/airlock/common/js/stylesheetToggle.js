/**
* Stylesheet toggle variation on styleswitch stylesheet switcher.
* Built on jQuery.
* Under an CC Attribution, Share Alike License.
* By Kelvin Luck ( http://www.kelvinluck.com/ )
**/

var CSSload = function(link, callback) {
    var cssLoaded = false;
    try{
        if ( link.sheet && link.sheet.cssRules.length > 0 ){
            cssLoaded = true;
        }else if ( link.styleSheet && link.styleSheet.cssText.length > 0 ){
            cssLoaded = true;
        }else if ( link.innerHTML && link.innerHTML.length > 0 ){
            cssLoaded = true;
        }
    }
    catch(ex){ }
    if ( cssLoaded ){
		if (callback && typeof(callback) === "function") {
			callback();
		}
    }else{
        setTimeout(function(){
            CSSload(link, callback);
        }, 100);
    }
};


(function($)
	{
		// Local vars for toggle
		var availableStylesheets = [];
		var activeStylesheetIndex = 0;
		
		// To loop through available stylesheets
		$.stylesheetToggle = function()
		{
			activeStylesheetIndex ++;
			activeStylesheetIndex %= availableStylesheets.length;
			$.stylesheetSwitch(availableStylesheets[activeStylesheetIndex]);
		};
		
		// To switch to a specific named stylesheet
		$.stylesheetSwitch = function(styleName)
		{
			switch_style = $('link[rel*=style][title="switch"]')[0];
			$('link[rel*=style][title]').not('link[title="switch"]').each(
				function(i) 
				{
//					this.disabled = true;
					$('body').removeClass('theme-color-' + this.title);
					if (this.getAttribute('title') == styleName) {
//						this.disabled = false;
						CSSload($(switch_style).attr('href', this.href)[0], function(){
							if( ApolloParams.cufon == 'on' ){
								Cufon.refresh('.mm', { fontFamily: ApolloParams.cufon_name, hover: true, letterSpacing: '-1px' });
							}
						});
						$('body').addClass('theme-color-' + styleName);
						activeStylesheetIndex = i;
					}
				}
			);
			createCookie('style', styleName, 365);
			
			//Refreshing theme specific settings
			if (styleName == 'dark') {
				$('.socials img').each(function(){
					str = $(this).attr('src');
					$(this).attr('src', str.replace(social_skins['light'], social_skins['dark']));
				});
			}
			else if(styleName == 'light') {
				$('.socials img').each(function(){
					str = $(this).attr('src');
					$(this).attr('src', str.replace(social_skins['dark'], social_skins['light']));
				});
			}
			
			$('.posts-elastic .item .post-categories,\
			.posts-elastic .item .post-info,\
			.portfolio-elastic .item .post-categories')
			.css('border-color','');
			
			$('.posts-elastic .item,\
			.portfolio-elastic .item,\
			#primary .item')
			.css('background-color','');
	
			if( ApolloParams.cufon == 'on' ){
				Cufon.refresh('.mm', { fontFamily: ApolloParams.cufon_name, hover: true, letterSpacing: '-1px' });
			}
		};
		
		// To initialise the stylesheet with it's 
		$.stylesheetInit = function()
		{
			switch_style = $('link[rel*=style][title="switch"]')[0];
			namee = ''
			$('link[rel*=style][title]').not('link[title="switch"]').each(
				function(i) 
				{
					availableStylesheets.push(this.getAttribute('title'));
					if( $(switch_style).attr('href') == $(this).attr('href') )
						namee = $(this).attr('title');
				}
			);
			var c = readCookie('style');
			if (c) {
				$.stylesheetSwitch(c);
			}
			else{
				createCookie('style', namee, 365);
			}
		};
	}
)(jQuery);

// cookie functions http://www.quirksmode.org/js/cookies.html
function createCookie(name,value,days)
{
	if (days)
	{
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}
function readCookie(name)
{
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++)
	{
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}
function eraseCookie(name)
{
	createCookie(name,"",-1);
}
// /cookie functions