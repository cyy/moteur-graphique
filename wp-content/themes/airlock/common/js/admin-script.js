var add_more_fields_meta = function(element){
	mark = jQuery(element).parent();
	//clone last element
	insert = mark.prev('.additive').clone(true, true);
	
	current_number = parseInt(insert.attr('title'));
	new_number = current_number + 1;
	
	//alter current number
	insert.attr('title', new_number);
	//alter 'name', 'for', 'id', 'value' of children
	to_change = {
		'name': '*[name]',
		'for': '*[for]',
		'id': '*[id]',
		'value': '.switch input[type="radio"]'
	};
	jQuery.each(to_change, function(index, selector){
		insert.find(selector).each(function(){
			name = jQuery(this).attr(index).replace('_' + current_number, '_' + new_number);
			jQuery(this).attr(index, name);
		});
	});
	//alter mover
	insert.find('.mover .position').val(new_number);
	insert.insertBefore(mark);
	insert.siblings('.counter-input').val(current_number + 1);
	return insert;
}

var add_remove_button = function(element){
	new_remover = jQuery('<span class="button remove-fieldset">Remove fieldset</span>').appendTo(element);
	new_remover.click(function(){
		main = jQuery(this).parent();
		successors = main.nextAll('.fieldset.additive');
		predecessors = main.prevAll('.fieldset.additive');
		//if only one left 
		if(!successors.length && !predecessors.length ){
//			alert('clear!');
		}
		//last one
		else if(!successors.length && predecessors.length){
			main.fadeOut(250,function(){ jQuery(this).remove(); });
			counter = parseInt(main.prevAll('input.counter-input').val());
			main.prevAll('input.counter-input').val(counter - 1);
		}
		//have successors
		else if(successors.length){
			main.fadeOut(250,function(){ jQuery(this).remove(); });
			//rewrite 'name', 'for', 'id', 'value' of children
			to_change = {
				'name': '*[name]',
				'for': '*[for]',
				'id': '*[id]',
				'value': '.switch input[type="radio"]'
			};

			successors.each(function(){
				_this = jQuery(this);
				current_number = _this.find('input.position').val();
				new_number = current_number - 1;
				jQuery.each(to_change, function(index, selector){
					_this.find(selector).each(function(){
						name = jQuery(this).attr(index).replace('_' + current_number, '_' + new_number);
						jQuery(this).attr(index, name);
					});
				});
				_this.attr('title', new_number);
				_this.find('input.position').val(new_number)
			});
			counter = parseInt(main.prevAll('input.counter-input').val());
			main.prevAll('input.counter-input').val(counter - 1);
		}
	});
}

jQuery(document).ready(function($) {
/*** UPLOAD FUNCTIONS ***/
    
	var field_for_uploaded_file, 
	    $image_input,
		tbframe_interval;
	
	function air_upload_image(){
		//parent of upload input
        var $cont = jQuery(this).parent();
		
		//find text input to write in
        $image_input = jQuery('input[type=text]', $cont);
		
		//remeber in which input we want to write
        field_for_uploaded_file = $image_input.attr('name');
		
		//show wordpress thickbox
        tb_show('Upload or select image to use', 'media-upload.php?&amp;post_id=0&amp;type=image&amp;TB_iframe=true');
		
		//set insert image button to nice name
        tbframe_interval = setInterval(function() {
            jQuery('#TB_iframeContent').contents()
			.find('.savesend .button, #go_button')
			.val('Use this image');
        }, 2000);
		
		//prevent default actions
        return false;
    }
	
	//replace default action of inserting into editor
	//copy original fucntion
    window.original_send_to_editor = window.send_to_editor;
	
	//our new function
    window.send_to_editor = function(html){
		//if there is some field waiting for input
        if (field_for_uploaded_file) {
			//end inserting nice name
	        clearInterval(tbframe_interval);
			
			//for searching if only <img> is returned in html
			html = $('<div></div>').append(html);
			
			//search for img in returned code
			var $img = jQuery('img',html);
            var fileurl = $img.attr('src');

            //insert its src to waiting field
            $image_input.val(fileurl);
			
			//collect attributes
			var file_attr = 'alt="' + $img.attr('alt') + '"';
			if($img.attr('title')){
				file_attr += ' title="' + $img.attr('title') + '"'
			}
			
			//insert to attribute filed if available
			var $attr_inp = $image_input.parents('.input-parent').next('.input-parent');
			if($attr_inp.is('.for-image-attributes')){
				$attr_inp.find('input[type="text"]').val(file_attr);
			}
            
			//close thickbox
            tb_remove();
            
			//clean waiting variable
            field_for_uploaded_file = null;
        }
		//else deafult action
        else {
            window.original_send_to_editor(html);
        }
    };
	
	if( $('.upload-image-button').length ){
		//bind click event on upload button
		$('.upload-image-button').click( air_upload_image );
		
		//clean if thickbox was closed too early
		$('html').live('tb_unload',function(){
            //clean waiting variable
            field_for_uploaded_file = null;
			
			//end inserting nice name
            clearInterval(tbframe_interval);
        });
	}
	

/*** color picker ***/
	$('input.with-color').wheelColorPicker({
		dir: AdminParams.colorDir,
		format: 'css',
		color: null
	});
	
/*** mover sort in theme options***/
	if ($('#apollo13-settings div.mover').length) {
		//sort
		$('div.mover input.position').each(function(){
			vall = $(this).val();
			$(this).parents('.postbox.block').addClass('sorted-position-' + vall);
		});
		for( iter = 2; iter <= $('div.mover input.position').length; iter++){
			//if iter == 1 we dont do anything
			
			$('.sorted-position-' + iter).insertAfter('.sorted-position-' + (iter - 1));
		}
		
		$('div.mover .move-up, div.mover .move-down').click(function(){
/*** !!!HAVE TO FIX!!!! **/			last_one = $('div.mover').length; //here cause of shortcodes support(variable number of movers)
			_this = $(this).parents('.postbox.block');//main affected block
			that = '';//also affected element while moving
			this_pos = $(this).siblings('input.position').val();
			that_pos = '';
			move_up = $(this).hasClass('move-up') ? true : false;
			in_shortcodes = $(this).parent().hasClass('sc') ? true : false;
			
//			alert(this_pos + ' ' + move_up + ' ' + in_shortcodes);
			
			//if already first or last
			if( move_up && this_pos == 1 )
				return;
			else if( !move_up && this_pos == last_one )
				return;
				
			//get other affected element
			if( move_up ){
				if(in_shortcodes){
					
				}
				else{
					that = _this.prev('.postbox.block');
					that_pos = that.find('input.position').val();
				}
			}
			else{
				if(in_shortcodes){
					
				}
				else{
					that = _this.next('.postbox.block');
					that_pos = that.find('input.position').val();
				}
			}
			temp = this_pos;
			this_pos = that_pos; //new position!
			that_pos = temp; //new position!
			
			//move things
			$(_this).fadeOut(300,function(){
				if( move_up )
					_this.insertBefore(that);
				else
					_this.insertAfter(that);
				
				_this.find('input.position').val(this_pos);
				that.find('input.position').val(that_pos);
					
				$(_this).fadeIn(300);
			});
		});
	}
	
	
	if ($('.apollo13-metas').length) {
		$('.apollo13-metas .add-more-fields').click(function(){
			add_more_fields_meta(this);
		});
		
		//hide unused options like image vs video
		$('.apollo13-metas .switch input[type="radio"]').change(function(){
			parent = $(this).parents('.switch')
			var to_show = $(this).val();
			//return string without number at end
			to_show = to_show.substring(0,to_show.lastIndexOf('_'));
//			console.log(to_show.substring(0,to_show.lastIndexOf('_')));
			
			$(this).parents('.input-desc').find('input[type="radio"]').each(function(){
				var _this = this;
				var _this_name = $(this).val().substring( 0, $(this).val().lastIndexOf('_'));
				elem = parent.find('[id^="' + _this_name + '"]');
				elem.each(function(){
					if (to_show == _this_name) 
	                    $(this).parents('.input-parent').show();
	                else 
	                    $(this).parents('.input-parent').hide();
				});
			});
		});
		
		add_remove_button($('.fieldset.additive'));
		
		
		$('div.mover .move-up, div.mover .move-down').click(function(){
			last_one = $(this).parents('.apollo13-metas').children('.fieldset.additive').length; //here cause of shortcodes support(variable number of movers)
			_this = $(this).parents('.fieldset.additive');//main affected block
			that = '';//also affected element while moving
			this_pos = $(this).siblings('input.position').val();
			that_pos = '';
			move_up = $(this).hasClass('move-up') ? true : false;
			
			//if already first or last
			if( move_up && this_pos == 1 )
				return;
			else if( !move_up && this_pos == last_one )
				return;
				
			//get other affected element
			if( move_up ){
				that = _this.prev('.fieldset.additive');
				that_pos = that.find('input.position').val();
			}
			else{
				that = _this.next('.fieldset.additive');
				that_pos = that.find('input.position').val();
			}
			
			//rewrite 'name', 'for', 'id', 'value' of children
			to_change = {
				'name': '*[name]',
				'for': '*[for]',
				'id': '*[id]',
				'value': '.switch input[type="radio"]'
			};
			//set first to temp
			current_number = this_pos;
			new_number = 'temp123';
			$.each(to_change, function(index, selector){
				_this.find(selector).each(function(){
					name = $(this).attr(index).replace('_' + current_number, '_' + new_number);
					$(this).attr(index, name);
				});
			});
			//set second to first
			current_number = that_pos;
			new_number = this_pos;
			$.each(to_change, function(index, selector){
				that.find(selector).each(function(){
					name = $(this).attr(index).replace('_' + current_number, '_' + new_number);
					$(this).attr(index, name);
				});
			});
			//set first to second
			current_number = 'temp123';
			new_number = that_pos;
			$.each(to_change, function(index, selector){
				_this.find(selector).each(function(){
					name = $(this).attr(index).replace('_' + current_number, '_' + new_number);
					$(this).attr(index, name);
				});
			});
			_this.attr('title', that_pos);
			that.attr('title', this_pos);
			
			temp = this_pos;
			this_pos = that_pos; //new position!
			that_pos = temp; //new position!
			
			
			//move things
			$(_this).fadeOut(300,function(){
				if( move_up )
					_this.insertBefore(that);
				else
					_this.insertAfter(that);
				
				_this.find('input.position').val(this_pos);
				that.find('input.position').val(that_pos);
					
				$(_this).fadeIn(300);
			});
		});
		
	}
	

});