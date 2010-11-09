<?php

class CPT_Filter_Widget extends WP_Widget {
	function CPT_Filter_Widget() {
        parent::WP_Widget(false, $name = 'Custom Post Type Filter');	
	}

	function form($instance) {
		$title = esc_attr($instance['title']);
		$selected_post_types = $instance['selected_post_types'];
		
		$post_types = CPT_Filter::get_post_types();
               
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
            
            <label for="<?php echo $this->get_field_name('selected_post_types') ?>" /><p><?php _e( 'Select the post types filterable by this widget: ', 'cptf' ) ?></p>
            <ul>
            <?php foreach( $post_types as $post_type => $name ) : ?>
            	<li>
            		<input type="checkbox" value="<?php echo $post_type ?>" name="<?php echo $this->get_field_name('selected_post_types') ?>[<?php echo $post_type ?>]" <?php if ( isset( $selected_post_types[$post_type] ) ) : ?>checked="checked" <?php endif ?>/> <?php echo $name ?>
            	</li>
            <?php endforeach ?>
            </ul>
            </label>           
            
        <?php 
	}

	function update($new_instance, $old_instance) {
		
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
       	$instance['selected_post_types'] = $new_instance['selected_post_types'];
        
        return $instance;
	}

	function widget($args, $instance) {
		
		extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        $selected_post_types = apply_filters( 'cptf_widget_selected_post_types', $instance['selected_post_types'] ); 
        
        $taxonomies = CPT_Filter::get_taxonomies_for_post_types( $selected_post_types );
        
        print_r($taxonomies);
        ?>
        	<?php echo $before_widget; ?>
        	<?php if ( $title )
            	echo $before_title . $title . $after_title; ?>
            
            <form method="post" action="">
            
            <?php foreach( $taxonomies as $tax_name => $tax_array ) : ?>
            	<label for="<?php echo $tax_name ?>">
            		<h3><?php echo $tax_array['label'] ?></h3>
            	
					<select name="<?php echo $tax_name ?>">
					<option value=""></option>
					<?php foreach( $tax_array['terms'] as $term ) : ?>
						<option value="<?php echo $term->term_id ?>"><?php echo apply_filters( 'cptf_tax_term_label', $term->name, $term->id, $tax_name ) ?></option>
					<?php endforeach ?>
					</select>            	
            	</label>
            <?php endforeach ?>
            
            <div id="cptf-submit">
				<input type="submit" value="<?php _e( 'Submit', 'cptf' ) ?>" name="cptf_submit" />
			</div>
			
			</form>
                  
    		<?php echo $after_widget; ?>
        <?php
	}

}
add_action('widgets_init', create_function('', 'return register_widget("CPT_Filter_Widget");'));

?>