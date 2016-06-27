<?php 
	global $_wp_all_image_sizes;

	$show_image_medium = array( 
	
			'medium' => array( 
				'width' => $_wp_all_image_sizes['medium']['width'],
	            'height' => $_wp_all_image_sizes['medium']['height'],
	            'crop' => 1
			)
	
	);
?>

<form method="post" action="" id="uie-form">
	
	<!--BEGIN: title -->
	<div class="bb-frame-title">

		<?php
			//cropping from original or not  
			$crop_from_orig_info = false;
			$image_size_title = $res->img_size;
			if( isset( $wp_image_target_size ) && ( $wp_image_target_size != $res->img_size )){
				$crop_from_orig_info = true;
				$image_size_title = $wp_image_target_size;
			}

			$title_width 	= $_wp_all_image_sizes[$image_size_title]['width'];
			$title_height	= $_wp_all_image_sizes[$image_size_title]['height'];
		?>
		<h1>
			Crop and resize - editing <i style="font-weight:400;color:#2ea2cc"><?php echo $image_size_title; ?> ( <?php echo $title_width ?>x<?php echo $title_height ?> )</i> image
			
			<?php if( $crop_from_orig_info ): ?>
				( croping from original )
			<?php endif; ?>
		
		</h1>
	</div>
	<!--END: title -->

	<!--BEGIN: frame content -->
	<div class="bb-frame-content">
		
		<!-- BEGIN: left -->
		<div id="uie-left-wrapper" >

			<?php 
				
				
				if( 'original' == $res->img_size ){
					$orig_img_data = array();
					$orig_img_data[0] = get_post_meta( $res->img_id, 'original_image_url', true );
					$orig_img_data[1] = get_post_meta( $res->img_id, 'original_image_width', true );
					$orig_img_data[2] = get_post_meta( $res->img_id, 'original_image_height', true );
				}
				else{
					$orig_img_data = wp_get_attachment_image_src( $res->img_id, $res->img_size );
				}
				
				//this is true only if the image does not have an image cropped
				//and wp delivers the default full image
				$crop_from_original = 0;
				if( isset( $orig_img_data[3] )){
					$crop_from_original = false === $orig_img_data[3] ? 1 : 0;
				}
			?>

			<img src="<?php echo $orig_img_data[0].'?'.time(); ?>" id="jcrop_target">

			<input type="hidden" name="wp_image_id" id="wp-image-id" value="<?php echo $res->img_id; ?>">
			<input type="hidden" name="wp_image_size" id="wp-image-size" value="<?php echo $image_size_title; ?>">
			
			<input type="hidden" name="wp_image_target_size" id="wp-image-target-size" value="<?php echo $res->img_size; ?>">
			<input type="hidden" name="wp_image_target_width" id="wp-image-target-width" value="<?php echo $_wp_all_image_sizes[$image_size_title]['width']; ?>"> 
			<input type="hidden" name="wp_image_target_height" id="wp-image-target-height" value="<?php echo $_wp_all_image_sizes[$image_size_title]['height'];?>"> 
			
			<input type="hidden" name="wp_image_width" id="wp-image-width" value="<?php echo $_wp_all_image_sizes[$res->img_size]['width']; ?>"> 
			<input type="hidden" name="wp_image_height" id="wp-image-height" value="<?php echo $_wp_all_image_sizes[$res->img_size]['height'];?>"> 

			<input type="hidden" name="image_crop" id="image_crop" value="0" />
			<input type="hidden" name="image_x" id="image_x" value="false" >
			<input type="hidden" name="image_y" id="image_y" value="false" >
			<input type="hidden" name="image_x2" id="image_x2" value="false" >
			<input type="hidden" name="image_y2" id="image_y2" value="false" >
			
			<input type="hidden" name="crop_from_original" id="crop_from_original" value="<?php echo $crop_from_original ?>" >
		
		</div>
		<!-- END: left -->
		

		<!-- BEGIN: right -->
		<div id="uie-right-wrapper" >
			
			<strong>
				Image info:
			</strong>
			<br>
			Image path: 
			<input type="text" value="<?php echo $orig_img_data[0]; ?>" readonly>
			<a href="<?php echo $orig_img_data[0]; ?>" target="_blank">
				view image
			</a>
			<br>
			<br>
			Image size: <?php  echo $orig_img_data[1].'x'.$orig_img_data[2] ?>
			<br>

			<?php $backup_sizes = get_post_meta( $res->img_id, '_wp_attachment_backup_sizes', true ); ?>
			
			<?php if( 1 == $crop_from_original ): ?>
			<p style="border-left:4px solid #dd3d36;padding-left:5px">
				An error has occured. Please crop image form original.
			</p>
			<?php endif ?>
			<br>
			<strong>
				Image sizes defined by Wordpress:
			</strong>
			<br>
			<?php 
				$classes='';
				if( 'original' == $image_size_title ){
					$classes = 'c-green c-gray';
				}
			?>
			<a class="uie-change-image-size <?php echo $classes ?>" data-image-size="<?php echo $orig_img_data[1].'x'.$orig_img_data[2] ?>" data-image-name="original" href="javascript:void(0)">
				<?php echo 'original image'; ?>
			
			</a>
			
			<?php 
				//show only if original image is selected
				if( isset( $wp_show_image_name )){
					if( 'original' == $wp_show_image_name ){
						include 'scale-original-image.php';
					}
				}
			?>

			<br>
			
			<ul class="wp_all_image_sizes">
				<?php foreach ( $show_image_medium as $key => $value ): ?>
					
				
					<?php 
					
						if( 'original' == $key ) { 
							continue;
						} 

						$class = '';
						$li_class = '';
						if( $image_size_title == $key ){
							$class = 'c-green';
							$li_class = 'c-gray';
						}
					?>
					<li class="<?php echo $li_class ?>">	
						<a class="uie-change-image-size <?php echo $class; ?>" data-id = "<?php echo $res->img_id ?>" data-image-size="<?php echo $value['width'].'x'.$value['height'] ?>" data-image-name="<?php echo $key ?>" href="javascript:void(0)">
							<?php echo $key; ?>
							
							<span data-id = "<?php echo $res->img_id ?>" data-image-size="<?php echo $value['width'].'x'.$value['height'] ?>" data-image-name="<?php echo $key ?>" href="javascript:void(0)">
								( <?php echo $value['width']; ?>
								x
								<?php echo $value['height']; ?> )
							</span>
							
						</a>
						|
						<a class="uie-crop-from-original" data-image-size="<?php echo $value['width'].'x'.$value['height'] ?>" data-image-name="<?php echo $key ?>" href="javascript:void(0)">
							crop from original
						</a>
						
					</li>							
				<?php endforeach?>
			
			</ul>

			<?php if( 'original' != $wp_show_image_name ): ?>
				<input type="button" id="uie-crop-button" class="button button-primary" value="Crop and save">
			<?php endif; ?>
			
			<br>
			<br>

			<div class="get-pro-version">

				Get the Pro version where you can crop and resize all WordPress defined images.
				<br>
				<br>
				<a href="http://wpconvertsite.com/wordpress-plugin-crop-and-resize-images/" class="download-pro-button">
					<span>
						Get Pro Version
					</span>
					<span>

						<img src="<?php echo plugin_dir_url( __FILE__ ).'../img/dw.png'; ?>">
					</span>

				</a> 

			</div>
		</div>
		<!--END: right-->
	
	</div>
	<!--END: frame content -->

</form>