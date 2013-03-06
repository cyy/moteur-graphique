// On window load. This waits until images have loaded which is essential
(function($) {
	$(window).load(function(){
	speed = 500;	
		
		// clone image
		$('.elastic .item img, .widget-container div.item .item-image img').each(function(index){
			var el = $(this);
			var $temp_img = $(new Image());
			
			el.css({"position":"absolute"}).wrap("<div class='img_wrapper' style='display: block'>").clone().addClass('img_grayscale').css({"position":"absolute","z-index":"998","opacity":"0"}).insertBefore(el).queue(function(){
				var el = $(this);
				$temp_img.attr('src', el.attr('src')); //for measure hidden images
				el.parent().css({"width":$temp_img.get(0).width,"height":$temp_img.get(0).height});
				if($.browser.msie){
					this.style.filter = 'progid:DXImageTransform.Microsoft.BasicImage(grayScale=1) alpha(opacity=0)';
				}
				else{
					this.src = grayscale(this.src);
				}
				el.dequeue();
			});
		});
		
		// Fade image 
		 $('.elastic .item, .widget-container div.item').live({
            mouseenter : function(){
            if($('body').hasClass('theme-color-light')){
                $(this).addClass('hovered').stop().animate( { backgroundColor: '#464646' }, speed);
                $(this).find('.post-categories, .post-info').stop().animate( { borderColor: '#4f4f4f' }, speed);
                if( $(this).children('.item-image').length )
                    $(this).children('.item-image').find('img:first').stop().animate({opacity:1}, speed);
            }
            else if($('body').hasClass('theme-color-dark')){
                $(this).addClass('hovered').stop().animate( { backgroundColor: '#fff' }, speed);
                $(this).find('.post-categories, .post-info').stop().animate( { borderColor: '#f2f2f2' }, speed);
                if( $(this).children('.item-image').length )
                    $(this).children('.item-image').find('img:first').stop().animate({opacity:1}, speed);
            }
        },
        mouseleave : function(){
            if($('body').hasClass('theme-color-light')){
                $(this).removeClass('hovered').stop().animate( { backgroundColor: '#fff' }, speed);
                $(this).find('.post-categories, .post-info').stop().animate( { borderColor: '#F3F3F3' }, speed);
                if( $(this).children('.item-image').length )
                    $(this).children('.item-image').find('.img_grayscale').stop().animate({opacity:0}, speed);
            }
            else if($('body').hasClass('theme-color-dark')){
                $(this).removeClass('hovered').stop().animate( { backgroundColor: '#212121' }, speed);
                $(this).find('.post-categories, .post-info').stop().animate( { borderColor: '#2a2a2a' }, speed);
                if( $(this).children('.item-image').length )
                    $(this).children('.item-image').find('.img_grayscale').stop().animate({opacity:0}, speed);
            }
        }});
	});
})(jQuery);




	
	// Grayscale w canvas method
	function grayscale(src){
		var canvas = document.createElement('canvas');
		var ctx = canvas.getContext('2d');
		var imgObj = new Image();
		imgObj.src = src;
		canvas.width = imgObj.width;
		canvas.height = imgObj.height; 
		ctx.drawImage(imgObj, 0, 0); 
		var imgPixels = ctx.getImageData(0, 0, canvas.width, canvas.height);
		for(var y = 0; y < imgPixels.height; y++){
			for(var x = 0; x < imgPixels.width; x++){
				var i = (y * 4) * imgPixels.width + x * 4;
				var avg = (imgPixels.data[i] + imgPixels.data[i + 1] + imgPixels.data[i + 2]) / 3;
				imgPixels.data[i] = avg; 
				imgPixels.data[i + 1] = avg; 
				imgPixels.data[i + 2] = avg;
			}
		}
		ctx.putImageData(imgPixels, 0, 0, 0, 0, imgPixels.width, imgPixels.height);
		return canvas.toDataURL();
    }
	