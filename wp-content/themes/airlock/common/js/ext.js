
/**
 *      The airlock's extended js
 *
 *      $Id: ext.js
 *      
 *      $date: 2013-02-28
 *      
 *      @author cyy
 */

jQuery(document).ready(function($) {
	
	/*-----------------------------------------------------------------------------------*/
	/*	Top
	/*-----------------------------------------------------------------------------------*/
	(new GoTop()).init({
		pageWidth : 980,
		nodeId : 'go-top',
		nodeWidth : 50,
		distanceToBottom : 125,
		hideRegionHeight : 368,
		text : 'Top'
	});

	/*-----------------------------------------------------------------------------------*/
	/*	Like Script start
	/*-----------------------------------------------------------------------------------*/
	function tz_reloadLikes(who) {
		var text = jQuery("#" + who).html();
		var patt= /(\d)+/;
		
		var num = patt.exec(text);
		num[0]++;
		text = text.replace(patt,num[0]);
		jQuery("#" + who).html('<span class="count">' + text + '</span>');
	} //reloadLikes
	
	/**
	 * minus likes
	 */
	function tz_minusLikes(who) {
		var text = jQuery("#" + who).html();
		var patt= /(\d)+/;
		
		var num = patt.exec(text);
		var likeNum = num[0];
		if (likeNum > 0) {
			--likeNum;
		}
		text = text.replace(patt, likeNum);
		jQuery("#" + who).html('<span class="count">' + text + '</span>');
	}
	
	//取消like，清理cookie，页面减少like数，后台不减少
	function tz_likeInit() {
		jQuery(".likeThis").live('click', function() {
			
			var classes = jQuery(this).attr("class");
			classes = classes.split(" ");
			
			var id = jQuery(this).attr("id");
			id = id.split("like-");
			
			var likeStatus = classes[1] == "active";
			
			if(likeStatus) {
				jQuery(this).removeClass('active');
				tz_minusLikes("like-" + id[1]);
			} else {
				jQuery(this).addClass("active");
				tz_reloadLikes("like-" + id[1]);
			}
			
			jQuery.ajax({
			  type: "POST",
			  url: "index.php",
			  data: "likepost=" + id[1],
			  success: function (msg) {
				
			  }
			}); 
			
			
			return false;
		});
	}
	
	tz_likeInit();
	/*-----------------------------------------------------------------------------------*/
	/*	Like Script end
	/*-----------------------------------------------------------------------------------*/
		
	/*-----------------------------------------------------------------------------------*/
	/*	infinite ajax scroll Script start
	/*-----------------------------------------------------------------------------------*/
	
	//��������
	jQuery.ias({
		//Enter the selector of the element containing your items that you want to paginate.
	    container : '#content',
	    //Enter the selector of the element that each item has. Make sure the elements are inside the container element.
	    item: '.item',
	    //The page selector of the element. This element will be hidden when IAS loads.
	    pagination: '#posts-nav',
	    //Next page selector of the element. This element will be hidden when IAS loads.
	    next: '.nav-next a',
	    //Page number after which a ��Load more items�� link is displayed. Users will manually trigger the loading of the next page by clicking this link.
	    triggerPageThreshold: 100,
	    //Load img src
        loader: '<img src="wp-content/themes/airlock/advance/plugins/infinite-ajax-scroll/images/loader.gif"/>',
        
        onLoadItems: function(items) {
            // hide new items while they are loading
            var $newElems = $(items).show().css({ opacity: 0 });
            // ensure that images load before adding to masonry layout
            $newElems.imagesLoaded(function(){
              // show elems now they're ready
              $newElems.animate({ opacity: 1 });
              $('.portfolio-elastic').masonry().masonry( 'appended', $newElems, true );
            });
            return true;
        }
        
	});
	
	

	$.fn.smartFloat = function() {
		var position = function(element) {
			var top = element.position().top, pos = element
					.css("position");
			$(window).scroll(function() {
				var scrolls = $(this).scrollTop();
				if (scrolls > top) {
					if (window.XMLHttpRequest) {
						element.css({
							position : "fixed",
							top : 0
						});
					} else {
						element.css({
							top : scrolls
						});
					}
				} else {
					element.css({
						position : "absolute",
						top : 368
					//这里根据右侧栏长度自定义
					});
				}
			});
		};
		return $(this).each(function() {
			position($(this));
		});
	};
	
	$("#top-link").smartFloat();  
	
	});

