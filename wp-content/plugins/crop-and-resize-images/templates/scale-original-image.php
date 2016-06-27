<div class="imgedit-group">
	<div class="imgedit-group-top">
		
		<h3>
			Scale and crop original image 
			<a href="#" class="dashicons dashicons-editor-help imgedit-help-toggle" onclick="imageEdit.toggleHelp(this);return false;">
			</a>
		</h3>
		
		<div class="imgedit-help">
			<p>
				You can crop or scale the original image. For best results, scaling should be done before you crop. Images can be scaled up or down.
			</p>
		</div>
	
		<p>
			Original dimensions <?php echo $orig_img_data[1] ?> × <?php echo $orig_img_data[2] ?>
			
			<?php $image_bk = get_post_meta( $res->img_id, 'original_bk_image_url', true ); ?>
			
			<?php if( !empty( $image_bk )): ?>
				
				<?php //var_dump_pre( $image_bk ); ?>
				<a href="javascript:void()" id="uie-restore-original-image" class="button button-primary" >
					Restore original image
				</a>
			
			<?php endif; ?>
		</p>
		
		<div class="imgedit-submit">
			<span class="nowrap">

				<input type="text" id="imgedit-scale-width" name="imgedit_scale_width" style="width:4em;" value="<?php echo $orig_img_data[1] ?>"> 
				× 
				<input type="text" id="imgedit-scale-height" name="imgedit_scale_height" style="width:4em;" value="<?php echo $orig_img_data[2] ?>">
				
				<a href="javascript:void()" id="uie-reset-scale-image" class="button button-primary" >
					Reset dimensions
				</a>
			
			</span>
			<br>
			<br>
			<input type="button" id="uie-scale-original-image" class="button button-primary" value="Scale Image">
			or
			<input type="button" id="uie-crop-original-image" class="button button-primary" value="Crop Original Image">

		</div>
	</div>
</div>
