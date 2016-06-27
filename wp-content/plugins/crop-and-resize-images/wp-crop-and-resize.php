<?php
/*

  Plugin Name: Crop and Resize Images
  
  Description: Crop and resize images defined in WordPress
  Author: bo lipai
  Version: 1.2.4

 */

 include plugin_dir_path( __FILE__ ).'includes/library.php';

 class ultimate_image_editor{

	static function init(){
		
		
		add_action( 'init', array( __CLASS__, 'init_hook' ));
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue_scripts' ));

		add_action( 'admin_footer', array( __CLASS__, 'my_admin_footer_function' ));

		add_filter( 'attachment_fields_to_edit', array( __CLASS__, 'add_attachment_location_field' ), 10, 2 );

		add_action( 'wp_ajax_uie-get-editor', array( __CLASS__, 'uie_get_editor' ));
		add_action( 'wp_ajax_uie-crop-and-save', array( __CLASS__, 'uie_crop_and_save' ));
		
		add_action( 'wp_ajax_uie-scale-original-image', array( __CLASS__, 'uie_scale_original_image' ));
		add_action( 'wp_ajax_uie-restore-original-image', array( __CLASS__, 'uie_restore_original_image' ));
		
		add_action( 'wp_ajax_uie-crop-original-image', array( __CLASS__, 'uie_crop_original_image' ));

		add_filter( 'wp_generate_attachment_metadata', array( __CLASS__, 'create_original_image' ) ,10 ,2 );

		add_filter( 'image_size_names_choose', array( __CLASS__, 'image_size_names_choose' ));
	}

	/*
		Add backbone modal template to footer
	*/
	static function my_admin_footer_function(){
	 
	 ?>
		<script type="text/template" id="modal-template">
			
			<div class="bbm-modal__topbar">
				<h3 class="bbm-modal__title">
					WordPress Crop and Resize Image Editor
				</h3>
				<a href="#" class="bbm-button close">
					close
				</a>
			</div>

			<div class="bbm-modal__section">
				
			</div>
			
			<div class="bbm-modal__bottombar">
				
			</div>
		</script>
	  <div class="app"></div>
  <?php
	}

	/*
		Insert aditional image sizez to 'insert media into post' dropdown
	*/
	static function image_size_names_choose( $sizes ) {
		
		global $_wp_additional_image_sizes;
		
		if ( empty($_wp_additional_image_sizes )){
			return $sizes;
		}

		foreach ( $_wp_additional_image_sizes as $id => $data ){
			if( !isset($sizes[$id] )){
				$sizes[$id] = ucfirst( str_replace( '-', ' ', $id ) );
			}
		}
	
		return $sizes;
	
	}

	/*
		Init Hook
	*/
	static function init_hook(){

		self::_define_all_image_sizes();
	}
	
	/*
		Admin Enqueue Scripts	
	*/
	static function admin_enqueue_scripts(){

		$current_screen = get_current_screen();
		

		// get created cpt
		$cpt_array = array();
		$args = array(
		   'public'   => true,
		   '_builtin' => false
		);

		$output = 'names'; // names or objects, note names is the default
		$operator = 'and'; // 'and' or 'or'

		$post_types = get_post_types( $args, $output, $operator ); 

		foreach ( $post_types  as $post_type ) {

		   array_push(  $cpt_array, $post_type );
		}

		$screen_array = array_merge( array( 'attachment', 'edit-post', 'upload', 'post', 'page' ), $cpt_array );
		if ( ! isset( $current_screen->id ) || !( in_array( $current_screen->id, $screen_array ))){
			return false; 
		}

		wp_enqueue_media();

		$dep = array( 'jquery' );

		wp_register_script( 'uie-main-script', plugin_dir_url( __FILE__ ) . 'js/uie-main-script-min.js' );

		$translation_array = array(
			'loader' => '<img src="'.plugin_dir_url( __FILE__ ).'img/loader.gif" style="width: 5%;height: auto;display: block;position: absolute;left: 50%;top: 43%;">',
		);
		wp_localize_script( 'uie-main-script', 'js_vars', $translation_array );
		
		wp_enqueue_script( 'uie-main-script', plugin_dir_url( __FILE__ ) . 'js/uie-main-script-min.js', $dep, '', true );
		
		
		wp_enqueue_script( 'backbone-modal', plugin_dir_url( __FILE__ ) . 'js/backbone.modal.min.js', $dep, '', true );
		
		wp_enqueue_script( 'jcrop' );
		wp_enqueue_style( 'jcrop' );


		wp_enqueue_style( 'uie-css', plugin_dir_url( __FILE__ ) . 'css/style.min.css' );
		
		wp_enqueue_style( 'backbone-modal-css', plugin_dir_url( __FILE__ ) . 'css/backbone.modal.min.css' );

	}

	/*
		Add the open editor button to modal window
	*/
	static function add_attachment_location_field( $form_fields, $post ) {
		
		if( false === strpos( $post->post_mime_type, 'image' )){
			return $form_fields;
		}
		$form_fields['car-image'] = array(
			'value' => '',
			'label' => __( '' ),
			'html' => '<a id="open-crop-and-resize" data-post-id=" '.$post->ID.'" class="button open" title="Crop and resize button" href="#">' . __( 'Crop and resize', 'plugin' ) . '</a>',
			'input' => 'html'
		);
		return $form_fields;
	}
	
	static function uie_get_editor(){

		if( !is_ajax( )){
			return false;
		}

		$res = new stdClass();
		$res->action = 'uie-get-editor';
		$res->error = 0;
		$res->error_text = '';

		if( isset( $_POST['data'] )){
			parse_str( $_POST['data'], $vals );
			$res->data = $vals;
			extract( $vals );
		}

		// show the scale box for original image
		$wp_show_image_name = 'original';
		if( array_key_exists( 'wp_show_image_name', $_POST )){
			if( isset( $_POST['wp_show_image_name'] )){
				$wp_show_image_name = $_POST['wp_show_image_name'];
			}
		}

		// editor open for first time
		if( array_key_exists( 'wp_image_id', $_POST )){
			if( isset( $_POST['wp_image_id'] ) && is_numeric( $_POST['wp_image_id'] )){

				$res->img_id = $_POST['wp_image_id'];		
				$res->action .= ': first time';

				// check if original image exists
				// create if not
				$orig_img_path = get_post_meta( $res->img_id, 'original_image_path', true );
				$res->orig_img_path = $orig_img_path;
				if( empty( $orig_img_path )){
					$res->create_orig_image = self::create_original_image( false, $res->img_id );
					
				}
				else{
					// metadata is set
					if( !file_exists( $orig_img_path )){
						$res->original_image_not_exists = 1;
						$res->create_orig_image = self::create_original_image( false, $res->img_id );
					}
				}
			}
			// $wp_image_target_size = 'medium';
			$crop_from_original = 0;
		}
		// intermediate images
		else{
			$res->img_id = $wp_image_id;
			$res->action .= ': intermediate images';
			// $wp_image_target_size = 'medium';
		}				

		self::_add_original_to_all_image_sizes( $res->img_id );

		$res->img_size = 'original';
		if( isset( $crop_from_original )){
			if( isset( $wp_image_target_size ) && ( 0 == $crop_from_original )){
				$res->img_size = $wp_image_target_size;
			}
		}
		global $_wp_additional_image_sizes;
		$intermediate_sizes = get_intermediate_image_sizes();
		
		ob_start();
		
			include  'templates/uie-get-editor.php' ;
	
		$res->html = ob_get_clean();

		echo json_encode( $res );
		die();

	}

	static function uie_crop_and_save(){

		if( !is_ajax( )){
			return false;
		}

		$res = new stdClass();
		$res->action = 'uie_crop_and_save';
		$res->error = 0;
		$res->error_text = '';

		parse_str( $_POST['data'], $vals );
		$res->data = $vals;
		extract( $vals );

		$res->img_id = $wp_image_id;

		$res->img_size = 'full';
		if( isset( $wp_image_size )){
			$res->img_size = $wp_image_size;
		}
		
		// there is no image generated for this size or
		// crop the original
		if( 1 == $crop_from_original ){

			$sp = new stringProcess();
			
			// $file_url =  wp_get_attachment_image_src( $res->img_id, 'large' );
			// create get_original_image_url();
			$file_url = get_post_meta( $res->img_id, 'original_image_url', true );

			$res->orig_path = self::wp_url2path( $file_url );

			// delete the old intermediate image 
			$att_metadata = wp_get_attachment_metadata( $res->img_id );
			$t = $sp->before_last( '/', $res->orig_path );
			$orig_th_image = $att_metadata['sizes'][$wp_image_size]['file'];
			$res->orig_th_path = $t.'/'.$orig_th_image;
			
			unlink( $res->orig_th_path );


			// $res->file_nane = basename( get_attached_file( $res->img_id )); 
			$t = $sp->before_last( '.', $res->orig_path );
			$tt = $sp->after_last( '.', $res->orig_path );
			$res->dest_path = $t.'-'.$wp_image_target_width.'x'.$wp_image_target_height.'.'.$tt; 
			
			$res->coords = array( $image_x, $image_y, $image_x2, $image_y2, $wp_image_target_width, $wp_image_target_height );
			
			$res->copy_crop = self::copy_crop_and_resize_image( $res->orig_path, $res->dest_path, $res->coords );

			$image_name = $sp->after_last( '/', $res->dest_path );
			$res->image_name = $image_name;
			$res->ddata = array(
				'file' => $image_name,
				'width' => $wp_image_target_width,
				 'height' => $wp_image_target_height,
				 'mime-type' => 'image/jpeg'
			);
			
			$res->update_metadata = self::add_attachemt_metadata( $res->img_id, $wp_image_size, $res->ddata );

		}
		// crop the existing image
		else{

			$sp = new stringProcess();
			
			// create get_original_image_url();
			$file_url = get_post_meta( $res->img_id, 'original_image_url', true );

			$res->orig_path = self::wp_url2path( $file_url );

			// delete the old intermediate image 
			$att_metadata = wp_get_attachment_metadata( $res->img_id );
			$t = $sp->before_last( '/', $res->orig_path );
			$orig_th_image = $att_metadata['sizes'][$wp_image_size]['file'];
			$res->orig_th_path = $t.'/'.$orig_th_image;
			
			$res->orig_path = $res->orig_th_path;
			$res->dest_path = $res->orig_th_path;

			$res->coords = array( $image_x, $image_y, $image_x2, $image_y2, $wp_image_target_width, $wp_image_target_height );
			
			$res->copy_crop = self::copy_crop_and_resize_image( $res->orig_path, $res->dest_path, $res->coords );


		}

		global $_wp_additional_image_sizes;
		$intermediate_sizes = get_intermediate_image_sizes();
		unset( $wp_image_target_size );
		ob_start();
		
			include  'templates/uie-get-editor.php' ;
	
		$res->html = ob_get_clean();

		echo json_encode( $res );
		die();

	}

	/*
		crop-original-image
	*/
	static function uie_crop_original_image(){

		if( !is_ajax( )){
			return false;
		}

		$res = new stdClass();
		$res->action = 'uie_crop_original_image';
		$res->error = 0;
		$res->error_text = '';

		parse_str( $_POST['data'], $vals );
		$res->data = $vals;
		extract( $vals );

		$res->img_id = $wp_image_id;
		$file_url = get_post_meta( $res->img_id, 'original_image_url', true );
		$res->orig_path = self::wp_url2path( $file_url );
	
		$image = wp_get_image_editor( $res->orig_path );
		if ( ! is_wp_error( $image )){
			
			// make backup image from original
			self::create_original_backup_image( $res->img_id );
						
			$res->dest_path = $res->orig_path;

			$res->coords = array( $image_x, $image_y, $image_x2, $image_y2, $image_x2 - $image_x, $image_y2 - $image_y );
			
			$res->copy_crop = self::copy_crop_and_resize_image( $res->orig_path, $res->dest_path, $res->coords, true );

			if( $res->copy_crop ){

				// get image width and height
				// $data = $image->get_size();
				$width = $res->copy_crop['width'];
				$height = $res->copy_crop['height'];
				update_post_meta( $res->img_id, 'original_image_width', $width );
				update_post_meta( $res->img_id, 'original_image_height', $height );
			}
		}

		self::_add_original_to_all_image_sizes( $res->img_id );

		$res->img_size = 'original';
		$wp_show_image_name = 'original';
		global $_wp_additional_image_sizes;
		$intermediate_sizes = get_intermediate_image_sizes();

		ob_start();
		
		
			include  'templates/uie-get-editor.php' ;
	
		$res->html = ob_get_clean();

		echo json_encode( $res );
		die();
	}

	/*
		scale original image
	*/
	static function uie_scale_original_image(){

		if( !is_ajax( )){
			return false;
		}

		$res = new stdClass();
		$res->action = 'uie_scale_original_image';
		$res->error = 0;
		$res->error_text = '';

		parse_str( $_POST['data'], $vals );
		$res->data = $vals;
		extract( $vals );

		$res->img_id = $wp_image_id;
		$file_url = get_post_meta( $res->img_id, 'original_image_url', true );
		$res->orig_path = self::wp_url2path( $file_url );
	
		$image = wp_get_image_editor( $res->orig_path );
		if ( ! is_wp_error( $image )){
			
			// make backup image from original
			self::create_original_backup_image( $res->img_id );
			
			$res->image_resize = $image->crop( 0, 0, $wp_image_width, $wp_image_height, $imgedit_scale_width, $imgedit_scale_height, true );

			$image->save( $res->orig_path );
			if( true === $res->image_resize ){
				// get image width and height
				$data = $image->get_size();
				$width = $data['width'];
				$height = $data['height'];
				update_post_meta( $res->img_id, 'original_image_width', $imgedit_scale_width );
				update_post_meta( $res->img_id, 'original_image_height', $imgedit_scale_height );
			}
		}

		self::_add_original_to_all_image_sizes( $res->img_id );

		$res->img_size = 'original';
		$wp_show_image_name = 'original';
		global $_wp_additional_image_sizes;
		$intermediate_sizes = get_intermediate_image_sizes();

		ob_start();
		
		
			include  'templates/uie-get-editor.php' ;
	
		$res->html = ob_get_clean();

		echo json_encode( $res );
		die();

	}

	/*
		restore the original image after it has been resized
	*/
	static function uie_restore_original_image(){

		//TODO:is ajax
		$res = new stdClass();
		$res->action = 'uie_restore_original_image';
		$res->error = 0;
		$res->error_text = '';

		parse_str( $_POST['data'], $vals );
		$res->data = $vals;
		extract( $vals );

		
		$res->img_id = $wp_image_id;
		
		//get original image
		$image_original_url = get_post_meta( $res->img_id, 'original_image_url', true );
		$res->image_original_url = self::wp_url2path( $image_original_url );

		//get backup image
		$image_backup_url = get_post_meta( $res->img_id, 'original_bk_image_url', true );
		$res->image_backup_url = self::wp_url2path( $image_backup_url );
		
		//copy backup image over original image
		copy( $res->image_backup_url , $res->image_original_url );

		//update meta
		$image = wp_get_image_editor( $res->image_original_url );
		if( !is_wp_error( $image )){
	
	
			//get image width and height
			$data = $image->get_size();
			$width = $data['width'];
			$height = $data['height'];
			update_post_meta( $res->img_id, 'original_image_width', $width );
			update_post_meta( $res->img_id, 'original_image_height', $height );

		}


		self::_add_original_to_all_image_sizes( $res->img_id );

		$res->img_size = 'original';
		$wp_show_image_name = 'original';
		global $_wp_additional_image_sizes;
		$intermediate_sizes = get_intermediate_image_sizes();

		ob_start();
		
		
			include  'templates/uie-get-editor.php' ;
	
		$res->html = ob_get_clean();

		echo json_encode( $res );
		die();


	}
	/*
	* add attachment metadata for intermediate image
	*/
	static function add_attachemt_metadata( $att_id, $image_name, $data ){

		$img_data = wp_get_attachment_metadata( $att_id, true );
		// unset( $data['size-five'] ); 
		$img_data['sizes'][$image_name] = $data;
		
		return wp_update_attachment_metadata( $att_id, $img_data );

	}

	/* 
		copy an image over another image and crop and resize it
	*/
	static function copy_crop_and_resize_image( $orig_path, $dest_path, $coords = array(), $src_abs = true ){
		
		$empty = empty( $coords ) || empty( $orig_path ) || empty( $dest_path );
		$not_array = !is_array( $coords );
		if( $empty || $not_array ){
			return false;
		}
		copy( $orig_path , $dest_path );
		
		$image = wp_get_image_editor( $dest_path );
		if( is_wp_error( $image )){
			return false;
		}

		// crop and resize
		$res = $image->crop( $coords[0], $coords[1], $coords[2], $coords[3], $coords[4], $coords[5], $src_abs );
		//save
		$saved_img = $image->save( $dest_path );

		return $saved_img;
	}

	/*
		show all sizes of an attachment
	*/
	static function show_all_attached_images( $attachment_id ){


		$upload_dir = wp_upload_dir();

		$att_file = get_attached_file( $attachment_id );

		//get original atached image url
		// $attachment_id = get_post_thumbnail_id( $post_id  );
		$orig_img_url = wp_get_attachment_url( $attachment_id );

		//get base url
		$sp = new stringProcess();
		$base_url = trailingslashit( $sp->before_last( '/', $orig_img_url ));
		
		//get attachment metadata
		$att_metadata = wp_get_attachment_metadata( $attachment_id );
		
		//loop through sizes and show image
		echo 'original <br>';
		echo '<img src="'.$orig_img_url.'"><br><br>';
		foreach ( $att_metadata['sizes'] as $key => $value ){
			# code...
			echo $key.' - '.$value['width'].'x'.$value['height'].'<br>';
			echo '<img src="'.$base_url.$value['file'].'"><br><br>';
		}
	}

	/*
		returns a file path based on file url
	*/
	static function wp_url2path( $file_url ){

		if( empty( $file_url )){
			return false;
		}
		// url to path
		$upload_dir = wp_upload_dir();
		
		$file_path = trailingslashit( $upload_dir['basedir'] ).trim( str_replace( $upload_dir['baseurl'], '', $file_url ), '//' );
		
		return $file_path;
	}

	static function _define_all_image_sizes( ){

		global $_wp_additional_image_sizes, $_wp_all_image_sizes, $post;
		$_wp_all_image_sizes = $_wp_additional_image_sizes;


		$_wp_all_image_sizes['thumbnail']['width'] = get_option('thumbnail_size_w');
		$_wp_all_image_sizes['thumbnail']['height'] = get_option('thumbnail_size_h');
		$_wp_all_image_sizes['thumbnail']['crop'] = 1 ;
		
		$_wp_all_image_sizes['medium']['width'] = get_option('medium_size_w');
		$_wp_all_image_sizes['medium']['height'] = get_option('medium_size_h');
		$_wp_all_image_sizes['medium']['crop'] = 1 ;
		
		$_wp_all_image_sizes['large']['width'] = get_option('large_size_w');
		$_wp_all_image_sizes['large']['height'] = get_option('large_size_h');
		$_wp_all_image_sizes['large']['crop'] = 1 ;
		
	}

	static function _add_original_to_all_image_sizes( $post_id ){

		global $_wp_all_image_sizes;

		$_wp_all_image_sizes['original']['width'] = get_post_meta( $post_id, 'original_image_width', true );
		$_wp_all_image_sizes['original']['height'] = get_post_meta( $post_id, 'original_image_height', true );
		$_wp_all_image_sizes['original']['crop'] = 1 ;		

	}

	/*
	* save the image uploaded as original image. to be used for cropping intermediate images
	* add post_meta:
	* 	original_image_path 
	* 	original_image_url
	*	original_image_width
	*	original_image_height
	*/
	static function create_original_image( $metadata, $attachment_id ){


		$file_url = wp_get_attachment_image_src( $attachment_id, 'full' );
		$orig_path = self::wp_url2path( $file_url[0] );

		//dest path
		$sp = new stringProcess();
		$t = $sp->before_last( '.', $orig_path );
		$tt = $sp->after_last( '.', $orig_path );
		$dest_path = $t.'-original.'.$tt; 

		//dest url
		$sp = new stringProcess();
		$t = $sp->before_last( '.', $file_url[0] );
		$tt = $sp->after_last( '.', $file_url[0] );
		$dest_url = $t.'-original.'.$tt; 

		@copy( $orig_path , $dest_path );
		
		$image = wp_get_image_editor( $dest_path );
		if( is_wp_error( $image )){
			return $metadata;
		}
		
		update_post_meta( $attachment_id, 'original_image_path', wp_slash( $dest_path ));
		update_post_meta( $attachment_id, 'original_image_url', wp_slash( $dest_url ));
		
		//get image width and height
		$data = $image->get_size();
		$width = $data['width'];
		$height = $data['height'];
		update_post_meta( $attachment_id, 'original_image_width',  $width );
		update_post_meta( $attachment_id, 'original_image_height', $height );

		return $metadata;

	}

	/*
	* create backup of original image
	* add post_meta:
	* 	original_bk_image_path 
	* 	original_bk_image_url
	*	original_bk_image_width
	*	original_bk_image_height
	*/
	static function create_original_backup_image( $attachment_id ){


		$bk_img = get_post_meta( $attachment_id, 'original_bk_image_path', true );
		if( !empty( $bk_img )){
			if( file_exists( $bk_img )){
				return false;
			}
		}

		$file_url = get_post_meta( $attachment_id, 'original_image_url', true );
		$orig_path = self::wp_url2path( $file_url );

		// dest path
		$sp = new stringProcess();
		$t = $sp->before_last( '.', $orig_path );
		$tt = $sp->after_last( '.', $orig_path );
		$dest_path = $t.'-bk.'.$tt; 

		// dest url
		$sp = new stringProcess();
		$t = $sp->before_last( '.', $file_url );
		$tt = $sp->after_last( '.', $file_url );
		$dest_url = $t.'-bk.'.$tt; 

		copy( $orig_path , $dest_path );
		
		$image = wp_get_image_editor( $dest_path );
		if( is_wp_error( $image )){
			return false;
		}
		
		update_post_meta( $attachment_id, 'original_bk_image_path', wp_slash( $dest_path ));
		update_post_meta( $attachment_id, 'original_bk_image_url', wp_slash( $dest_url ));
		
		// get image width and height
		$data = $image->get_size();
		$width = $data['width'];
		$height = $data['height'];
		update_post_meta( $attachment_id, 'original_bk_image_width', $width );
		update_post_meta( $attachment_id, 'original_bk_image_height', $height );

		return true;

	}


}

 ultimate_image_editor::init();