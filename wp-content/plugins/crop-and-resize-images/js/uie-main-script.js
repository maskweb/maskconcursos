var loader = js_vars.loader;

jQuery( document ).ready( function(){
	
    
    // Create a modal view class
    var Modal = Backbone.Modal.extend({
    	template: _.template( jQuery( '#modal-template' ).html()),
    	cancelEl: '.bbm-button'
    });

	// open editor
    jQuery( document ).on('click', '#open-crop-and-resize', function( e ){

		var modalView = new Modal();

		jQuery('.app').html( modalView.render().el );

		uie_load_editor( e );

    });

	var orig_image_id;
	var uie_get_editor_url;
	var jcrop_api;
	var bk_modal;
	var frame;
	
	jQuery( document ).on( 'click', '.media-modal-close', function( e ){
		
		jQuery( '.media-modal-content' ).html( bk_modal );
		
	})

	// load intermediat image size
	jQuery( document ).on( 'click', '.uie-change-image-size', function( e ){

		uie_load_intermediate( e );
	})
	
	// load original image size to be cropped for intermediate images
	jQuery( document ).on( 'click', '.uie-crop-from-original', function( e ){

		uie_load_crop_from_original( e );
	})

	// crop original image
	jQuery( document ).on( 'click', '#uie-crop-original-image', function( e ){

		uie_crop_original_image( e );
	})
	
	// crop and save button
	jQuery( document ).on( 'click', '#uie-crop-button', function( e ){

		uie_crop_and_save( e );
	})

	// scale original image
	jQuery( document ).on( 'click', '#uie-scale-original-image', function( e ){

		uie_scale_original_image( e );
	})

	// restore original image
	jQuery( document ).on( 'click', '#uie-restore-original-image', function( e ){

		uie_restore_original_image( e );
	})

	// reset scale input
	jQuery( document ).on( 'click', '#uie-reset-scale-image', function( e ){

		uie_reset_scale_original_image( e );
	})
	
	// resize image input boxes
	jQuery( document ).on( 'blur', '#imgedit-scale-width', function( e ){

		var img_width = jQuery( '#wp-image-target-width' ).val( );
		var img_height = jQuery( '#wp-image-target-height' ).val( );
		var bounds = [ img_width, img_height ];
		
		var ar = uie_get_aspect_ratio( bounds );

		var width = jQuery( e.target ).val();
		jQuery( '#imgedit-scale-height' ).val( parseInt(  width / ar ));

	})

	jQuery( document ).on( 'blur', '#imgedit-scale-height', function( e ){

		var img_width = jQuery( '#wp-image-target-width' ).val( );
		var img_height = jQuery( '#wp-image-target-height' ).val( );
		var bounds = [ img_width, img_height ];
		
		var ar = uie_get_aspect_ratio( bounds );

		var width = jQuery( e.target ).val();
		jQuery( '#imgedit-scale-width' ).val( parseInt( ar * width ));
	})

})

/*
	functions
*/

uie_scale_original_image = function( e ){


	var commentform = jQuery( '#uie-form' );
	var formdata = commentform.serialize();
	
	data = {

		action 				: 'uie-scale-original-image',
		data 				: formdata,
		wp_show_image_name 	: jQuery( e.target ).data( 'image-name' )
	}
	
	_uie_editor_ajax( e, data );
	

}

uie_crop_original_image = function( e ){

	var modal_box = jQuery( '.bbm-modal__section' );

	var commentform = jQuery( '#uie-form' );
	var formdata = commentform.serialize();

	data = {

		action 	: 'uie-crop-original-image',
		data 	: formdata
	}

	modal_box.html( loader );
	var jqxhr = jQuery.ajax({
		type	: "POST",
		url		: ajaxurl,
		data 	: data,
		dataType: 'json',
		cache	: false
		
	})
	.done( function( response ){
		
		
		if( 0 == response.error ){

			modal_box.html( response.html );
			
			var options = {

				bgColor: '#000',
				aspectRatio: uie_jcrop_ar(),
				allowMove: true,
				onSelect: showCoords,
				onChange: showCoords,
				boxWidth: uie_jcrop_box_width(), 
				// boxHeight: 0,
			}
			jQuery( '#jcrop_target' ).Jcrop( options, function(){
				jcrop_api = this; 
			})
		}
	
	})
	
}

uie_restore_original_image = function( e ){

	var commentform = jQuery( '#uie-form' );

	var formdata = commentform.serialize();
	
	data = {

		action 				: 'uie-restore-original-image',
		data 				: formdata,
		wp_show_image_name 	: jQuery( e.target ).data( 'image-name' )
	}
	
	_uie_editor_ajax( e, data );
	

}

uie_reset_scale_original_image = function( e ){

	jQuery( '#imgedit-scale-width' ).val( jQuery( '#wp-image-width' ).val( ));
	jQuery( '#imgedit-scale-height' ).val( jQuery( '#wp-image-height' ).val( ));

}

// load editor for the first time
uie_load_editor = function( e ){

	e.preventDefault();

	var image_id = jQuery( '#open-crop-and-resize' ).data( 'post-id' );

	data = {

		action 	: 'uie-get-editor',
		wp_image_id : image_id,
		wp_image_target_size : 'medium'
	}

	_uie_editor_ajax( e, data );
	
}

// uie-change-image-size
// load intermediate images into editor
uie_load_intermediate = function( e ){

	var modal_box = jQuery( '.bbm-modal__section' );


	var target_image_size = jQuery( e.target ).data( 'image-name' );

	
	//change image-target-size based on which link was pressed: intermadiate image or crop form original
	jQuery( '#wp-image-target-size' ).val( target_image_size );
	
	//when requesting intermediate image set crop-to-original to 0
	//the large image may not have been croped yet so crop-to-original is 1
	jQuery( '#crop_from_original' ).val( 0 );
	var commentform = jQuery( '#uie-form' );
	var formdata = commentform.serialize();
	data = {

		action 				: 'uie-get-editor',
		data 				: formdata,
		wp_show_image_name 	: target_image_size
	}
	_uie_editor_ajax( e, data );

}

// load original image to be croped 
uie_load_crop_from_original = function( e ){


	var modal_box = jQuery( '.bbm-modal__section' );

	
	jQuery( '#wp-image-target-size' ).val( jQuery( e.target ).data( 'image-name' ));
	
	// when requesting intermediate image set crop-to-original to 0
	// the large image may not have been croped yet so crop-to-original is 1
	jQuery( '#crop_from_original' ).val( 1 );

	var commentform = jQuery( '#uie-form' );
	var formdata = commentform.serialize();
	data = {

		action 	: 'uie-get-editor',
		data 	: formdata,
		wp_show_image_name 	: jQuery( e.target ).data( 'image-name' )

	}

	_uie_editor_ajax( e, data );


}

// load editor ajax call
_uie_editor_ajax = function( e, data ){

	var modal_box = jQuery( '.bbm-modal__section' );

	var context = jQuery( e.target ).hasClass( 'uie-crop-from-original' );
	
	modal_box.html( loader );

	var jqxhr = jQuery.ajax({
		type	: "POST",
		url		: ajaxurl,
		data 	: data,
		dataType: 'json',
		cache	: false
		
	})
	.done( function( response ){
		
		
		if( 0 == response.error ){

			modal_box.html( response.html );
			
			var options = {

				bgColor: '#000',
				aspectRatio: uie_jcrop_ar(),
				allowMove: true,
				onSelect: showCoords,
				onChange: showCoords,
				boxWidth: uie_jcrop_box_width(), 
				// boxHeight: 0,
			}
			jQuery( '#jcrop_target' ).Jcrop( options, function(){

				jcrop_api = this;
				orig_image_id = response.img_id;
				
				//set crop to original to 1 if the event comes from that link
				if( true == context ){

					jQuery( '#crop_from_original' ).val( 1 );

				}

			})

		}
		
	})
}

// crop and save action button
uie_crop_and_save = function( e ){


	var modal_box = jQuery( '.bbm-modal__section' );
	

	var commentform = jQuery( '#uie-form' );
	var formdata = commentform.serialize();
	data = {

			action 	: 'uie-crop-and-save',
			data 	: formdata
		}

	modal_box.html( loader );
	var jqxhr = jQuery.ajax({
		type	: "POST",
		url		: ajaxurl,
		data 	: data,
		dataType: 'json',
		cache	: false
		
	})
	.done( function( response ){
				
		if( 0 == response.error ){

			modal_box.html( response.html );
			
			var options = {

				bgColor: '#000',
				aspectRatio: uie_jcrop_ar(),
				allowMove: true,
				onSelect: showCoords,
				onChange: showCoords,
				boxWidth: uie_jcrop_box_width(), 
				// boxHeight: 0,
			}
			jQuery( '#jcrop_target' ).Jcrop( options, function(){
				jcrop_api = this; 
			})
		}
		
	})
}

// calculate and return aspect ratio
uie_get_aspect_ratio = function( size ){

	return size[0]/size[1];

}

// get aspect ratio for image crop area based on the values set by add_image_size
uie_jcrop_ar = function(){


	var img_width = jQuery( '#wp-image-target-width' ).val( );
	var img_height = jQuery( '#wp-image-target-height' ).val( );

	var res = uie_get_aspect_ratio( [img_width, img_height] );

	// for original image do not set crop ar restrictions
	if( 'original' == jQuery( '#wp-image-size' ).val()){
		res = 0;
	}

	return res;

}
uie_jcrop_box_width = function(){


	var window_width =  jQuery( '#uie-left-wrapper' ).width( );
	var window_height =  jQuery( '#uie-left-wrapper' ).height( )
	
	var img_width = jQuery( '#wp-image-width' ).val( );
	var img_height = jQuery( '#wp-image-height' ).val( );

	var ar = uie_get_aspect_ratio( [ img_height, img_width] );

	var width = 0;

	// protrait
	if( 1 > ar ){

		width =  parseInt( window_width * ( 3/4 ) );
	}
	// landscape
	else{

		width = parseInt( window_width / 2 );

	}


	
	return width;
	
}

/*
* set cropping values to hidden input fields
*/
function showCoords ( c ){
	
	// variables can be accessed here as
	// c.x, c.y, c.x2, c.y2, c.w, c.h
	
	jQuery( '#image_x' ).val( c.x );
	jQuery( '#image_y' ).val( c.y );
	jQuery( '#image_x2' ).val( c.x2 );
	jQuery( '#image_y2' ).val( c.y2 );
	jQuery( '#image_crop' ).val( 1 );

	
};
