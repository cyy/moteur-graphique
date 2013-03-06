/***** On DOM load *********/
jQuery(document).ready(function($) {
	
	/******** CUFON ********/
	if( ApolloParams.cufon == 'on' ){
		Cufon.replace('.mm', { fontFamily: ApolloParams.cufon_name, hover: true, letterSpacing: '-1px' });
	}
		
	/******* TOP MENU *********/
	//Cut menu in blocks (3 items in block)
	$('#menu ul.menu > li').each(function(index){
		if( index%3 == 0 && index > 2 ){
			$('<ul class="menu"></ul>').appendTo('#menu');
		}
		if (index > 2) {
			$(this).appendTo('#menu ul.menu:last');
		}
	});
	
	//compute position and width and hide after
	$('#menu ul.sub-menu').each(function(){
		parent_padding = parseInt( $(this).parent().parent().css('paddingLeft') );
		pos_vert = parseInt( $(this).parent().innerWidth() ) + parent_padding + 25;
//		alert(parent_padding + ' ' + parseInt( $(this).parent().innerWidth() ) + ' ' + $(this).parent().width())
		$(this).css({
			'right': pos_vert,
			'width': $(this).width()
		});
	});
	$('#menu ul.sub-menu').hide().append('<span class="arr"></span>');
	
	
	//Hover action
	$('#menu ul.sub-menu').parent().hoverIntent({
		over: function(){
			//hide every menu that is open on same level
			$(this).siblings('li').has('ul').css('position', 'static').children('ul').hide();
			
			$(this).css('position', 'relative').children('ul.sub-menu').fadeIn(300);
		},
		out: function(){
			_this = this
			$(this).children('ul.sub-menu').fadeOut(200, function(){
				$(_this).css('position', 'static');
			});
		},
		interval: 100,
		timeout: 500
	});
	
	/******* SOCIAL *********/
	social_quantity = $('#header .socials a').length;
	social_height = Math.ceil( social_quantity / ( parseInt( $('#header .socials').width() ) /39 ) ) * 39;
	if (social_height > 39) {
		$('#header .socials .slide').hover(function(){
			$(this).animate({
				height: social_height
			}, 300, 'swing');
		}, function(){
			$(this).animate({
				height: '32'
			}, 200, 'swing');
		});
	}
	if ($.browser.msie) {
		//no opacity on png!
	}
	else{
		$('#header .socials a').css('opacity', '0.6').hover(function(){
			$(this).fadeTo(300,1);
		},
		function(){
			$(this).fadeTo(300,0.6);
		})
	}
	
	/******* SEARCH *********/
	$('form.search-form .search-right div').hide();
	
	$('form.search-form label').click(function(){
		$('form.search-form').addClass('active');
		$('form.search-form .search-right div').animate({
			'width': 'show'
		},250, 'swing');
		$('form.search-form .search-right').animate({
			paddingLeft: '16',
			paddingRight: '12'
		},250, 'swing');
	});
	
	$('form.search-form .close').click(function(){
		$('form.search-form').removeClass('active');
		$('form.search-form .search-right div').animate({
			'width': 'hide'
		},100, 'swing');
		$('form.search-form .search-right').animate({
			paddingLeft: '0',
			paddingRight: '16'
		},100, 'swing');
	});
	
	/******* PLACEHOLDERS *********/
	if ($('input.placeholder').length) {
		$('input.placeholder').focus(function(){
			if( $(this).val() == $(this).attr('title') )
				$(this).val('');
		})
		.blur(function(){
			if( $(this).val() == '' )
				$(this).val($(this).attr('title'));
		});
	}
	
	/******* NIGHT DAY SWITCH *********/
	if ($('#night-toggle').length) {
		$('#night-toggle').hover(function(){
			$('#night-toggle .norm').fadeOut(500);
			$('#night-toggle .hov').fadeIn(500);
		}, function(){
			$('#night-toggle .hov').fadeOut(500);
			$('#night-toggle .norm').fadeIn(500);
		});
		
		// Call stylesheet init so that all stylesheet changing functions will work.
		$.stylesheetInit();
		
		$('#night-toggle').click(function(e){
		createCookie('style-user-switch', '1', 0);
			if( ! $('#night-cover').length )
				$('body').append('<div style="display: none;" id="night-cover" />');
			$('#night-cover').fadeIn(700, function(){
				$.stylesheetToggle();
				$(this).fadeOut(700);
			});
			
			return false;
		});
		
		if( $('#night-toggle').hasClass('detector') ){
			currentTime = new Date()
	  		hours = currentTime.getHours();
			if(hours >= 18 || hours < 6){
				if(readCookie('style')=='light' && !readCookie('style-user-switch') ){
					$.stylesheetSwitch('dark');
				}
			}
			else{
				if(readCookie('style')=='dark' && !readCookie('style-user-switch') )
					$.stylesheetSwitch('light');
			}
		}
	}
	
	/******* Height of CONTENT *********/
	if ($('#primary').length) {
		$('#primary').resize(function(e){
			content_pad = parseInt( $('#content').css('padding-top') );
			$('#content').css('min-height', $('#primary').height() - content_pad);
		});
		$('#primary').resize();
	}
	
			
	/******* TABS *********/
	//Default Action
	$(".tab_content").hide(); //Hide all content
	$("ul.tabs li:first").addClass("active").show(); //Activate first tab
	$(".tab_content:first").show(); //Show first tab content
	
	//On Click Event
	$("ul.tabs li").click(function() {
		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content").hide(); //Hide all tab content
		var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active content
		return false;
	});
	
	/******* ALPHA SCOPE *********/
	/**if ($('.alpha-scope').length) {
		$('.alpha-scope').each(function(){
			if($(this).hasClass('alpha-scope-double-icon') ||  $(this).hasClass('alpha-scope-single-icon')){
				$('<span class="alpha-scope-all"><span class="alpha-scope-bg"></span></span>').appendTo(this).hide();
				$(this).find('a').appendTo($(this).children('.alpha-scope-all'));
			}
			else{
				$('<span class="alpha-scope-all"><span class="alpha-scope-bg"></span><span class="alpha-scope-icon"></span></span>').appendTo(this).hide();
			}
			$('.alpha-scope-bg').css('opacity', '0.5');
			$('.alpha-scope').hover(function(){
				$(this).children('span').fadeIn(300);
			},function(){
				$(this).children('span').fadeOut(300);
			});
		});
	}**/
	
	$('.alpha-scope').live({
        mouseenter : function(){
            $(this).children('span').fadeIn(300);
        },
         mouseleave : function(){
            $(this).children('span').fadeOut(300);
       }
    });
	/******* SCROLL TOP *********/
	$('#top-link').click(function(e) {
		e.preventDefault();
		$.scrollTo(0,300);
	});
	
	/******* BLOG ELASTIC **********/
	if($('.posts-elastic .item').length){
		$container = $('.posts-elastic');
		$container.imagesLoaded(function(){
 			$container.masonry({
	    		itemSelector : '.item',
				columnWidth : 315,
				isAnimated: !Modernizr.csstransitions,
				animationOptions: {
					duration: 750,
					easing: 'linear',
					queue: false
				}
			});
		});
		$('.posts-elastic .item').eq(0).resize(function(e){
			$('.posts-elastic').masonry({ itemSelector: '.item:visible' }).masonry( 'reload' );
		});
	}
	
	/******* PORTFOLIO ELASTIC **********/
	if($('.portfolio-elastic .item').length){
		$container_1 = $('.portfolio-elastic');
		$container_1.imagesLoaded(function(){
 			$container_1.masonry({
	    		itemSelector: '.item:visible',
				columnWidth: 240,
				isAnimated: !Modernizr.csstransitions,
				animationOptions: {
					duration: 750,
					easing: 'linear',
					queue: false
				}
			});
		});
	}
	if($('#variant-liquid').length){
		$container_2 = $('#variant-liquid');
		$container_2.imagesLoaded(function(){
 			$container_2.masonry({
	    		itemSelector: '.liquid-item',
				columnWidth: 470,
				isAnimated: !Modernizr.csstransitions,
				animationOptions: {
					duration: 750,
					easing: 'linear',
					queue: false
				}
			});
		});
		
	}
	
	/******* POST SLIDER *******/
	if ($('#image-video-slider').length) {
		$(window).load(function(){
			$('#image-video-slider').anythingSlider({
				width: '640',
				theme : 'minimalist-round',
				resizeContents : false,
				buildArrows : false,
				buildStartStop : false ,
				resumeOnVideoEnd : false,
				autoPlay : false,
				startStopped :  false,
				resumeOnVisible: false,
				infiniteSlides : false 
			});
		});
	}
	
	
	/******* PORTFOLIO DYNAMIC SORTING *******/
	if ($('#portfolioList.dynamic').length) {
		
		$('#portfolioList a').click(function(event){
			event.preventDefault();
			$('#portfolioList a.selected').removeClass('selected');
			items_class = $(this).attr('class');
			$(this).addClass('selected');
			if(items_class == 'portfolio_cat-all'){
				$('.portfolio-elastic > div.item').fadeIn(300);
				$('.portfolio-elastic > div.item').promise().done(function() {
					$('.portfolio-elastic').masonry({ itemSelector: '.item:visible' }).masonry( 'reload' );
				});
			}
			else{
				$('.portfolio-elastic > div.item').not('.' + items_class ).fadeOut(300);
				$('.portfolio-elastic > div.' + items_class).fadeIn(300);
				$('.portfolio-elastic > div.item').promise().done(function() {
					$('.portfolio-elastic').masonry({ itemSelector: '.item:visible' }).masonry( 'reload' );
				});
			}
			
		})
	}
	
	/** Comment validation ***/
	if ($('#commentform').length) {
		//fix for not submiting form after check http://jibbering.com/faq/names/
		$('#comment-submit').attr('name','othername');
		
		$('#commentform').addClass('styled-form').removeClass('validated').submit(function(e){
			if($(this).hasClass('validated')){
				return true;
			}
			else{
				e.preventDefault();
				error_number = 0;

				$(this).find('input.required, textarea').each(function(){
					if( $.trim( $(this).val() ) == '' ){
						$(this).parent().addClass('error');
						error_number++;
						return;
					}
					
					if( $(this).hasClass('email') ){
						var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
     					if ($(this).val().search(emailRegEx) == -1) {
							$(this).parent().addClass('error');
							error_number++;
							return;
						}
					}
					
					// everythin ok
					$(this).parent().removeClass('error');
				});
				
				if( error_number == 0 ){
					$(this).addClass('validated').submit();
				}
				
			}
		});
	}
	
	/** Contact validation ***/
	if ($('.contact-form').length) {
		$('.contact-form').removeClass('validated').submit(function(e){
			if($(this).hasClass('validated')){
				return true;
			}
			else{
				e.preventDefault();
				error_number = 0;

				$(this).find('input[type="text"], textarea').each(function(){
					if( $.trim( $(this).val() ) == '' ){
						$(this).parent().addClass('error');
						error_number++;
						return;
					}
					
					if( $(this).hasClass('email') ){
						var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
     					if ($(this).val().search(emailRegEx) == -1) {
							$(this).parent().addClass('error');
							error_number++;
							return;
						}
					}
					
					// everythin ok
					$(this).parent().removeClass('error');
				});
				
				if( error_number == 0 ){
					$(this).addClass('validated').submit();
				}
				
			}
		});
	}
	
});