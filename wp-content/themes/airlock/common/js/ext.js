
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
	
	
	function tz_likeInit() {
		jQuery(".likeThis").live('click', function() {
			var classes = jQuery(this).attr("class");
			classes = classes.split(" ");
			
			if(classes[1] == "active") {
				return false;
			}
			var classes = jQuery(this).addClass("active");
			var id = jQuery(this).attr("id");
			id = id.split("like-");
			jQuery.ajax({
			  type: "POST",
			  url: "index.php",
			  data: "likepost=" + id[1],
			  success: tz_reloadLikes("like-" + id[1])
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
	
	//πˆ∆¡º”‘ÿ
	jQuery.ias({
		//Enter the selector of the element containing your items that you want to paginate.
	    container : '#content',
	    //Enter the selector of the element that each item has. Make sure the elements are inside the container element.
	    item: '.portfolio-elastic',
	    //The page selector of the element. This element will be hidden when IAS loads.
	    pagination: '#posts-nav',
	    //Next page selector of the element. This element will be hidden when IAS loads.
	    next: '.nav-next a',
	    //Page number after which a °ÆLoad more items°Ø link is displayed. Users will manually trigger the loading of the next page by clicking this link.
	    triggerPageThreshold: 100,
	    //Load img src
        loader: '<img src="wp-content/themes/airlock/advance/plugins/infinite-ajax-scroll/images/loader.gif"/>'
    });
	
	});