<?php

abstract class HH_Widget extends WP_Widget {
	protected $identifier = null;
	protected $label = null;
	protected $description = null;
	protected $has_title = false;
	
	protected $fields = array();
	protected $defaults = array();
	
	function HH_Widget() {
		if($this->identifier == null){
		  $this->identifier = strtolower(get_class($this));
		}
    
    if($this->label == null){
			$this->label = preg_replace('/([a-z])([A-Z])/', '$1 $2', str_replace('_', ' ', get_class($this)));
		}
		
		$options = array();
		
		if($this->description != null){
			$options["description"] = $this->description;
		}
		
		$this->WP_Widget($this->identifier, $this->label, $options);
		
		if($this->has_title){
			$this->register_title();
		}
    
    foreach($this->fields as $key => &$field){
      $field = (object) $field;

      if(empty($field->name)){
        $field->name = $key;
      }
      
      if(empty($field->label)){
        $field->label = ucfirst(str_replace("_", " ", $field->name));
      }
      
      if(empty($field->type)){
        $field->type = 'text';
      }
    }
		
		$this->init();
	}
	
	function init(){}
	
	function register_title(){
		$field = new stdClass();
		
		$field->name = "title";
		$field->type = "text";
		$field->label = "Title";
		$field->required = true;
		
		array_unshift($this->fields, $field);
		
		$this->has_title = true;
	}
	
	function update( $new_instance, $old_instance ) {
		foreach($this->fields as $field){
			$field = (object) $field;
			
			$old_instance[$field->name] = $new_instance[$field->name];
		}
		
		return $old_instance;
	}
	
	function render_label($field, $id){
		if(isset($field->required) && $field->required == true){
			$required = "<span style=\"color:red;\" title=\"This field is required.\">*</span>";
		} else {
			$required = "";
		}
		
		echo "<label for=\"{$id}\">{$field->label}{$required}</label>";
	}
	
	function form( $instance ) {
		$instance = wp_parse_args(
			(array) $instance, 
			$this->defaults
		);
		
		foreach($this->fields as $key => $field){
			$field = (object) $field;
			
			$method_name = "render_{$field->type}_field";
			
			$this->$method_name($field, $instance);
		}
	}
	
	function widget($args, $instance){
		$instance = (object) $instance;
		
		if(!empty($this->has_title)){
			$instance->title = $this->get_title($instance);
		}
		
		$this->render_widget($instance, $args);
	}
	
	function get_title($instance){
		return apply_filters('widget_title', empty($instance->title)?'':$instance->title, $instance, $this->id_base);
	}
	
	abstract function render_widget($instance, $args);

  // field type functions ...
	
	function render_text_field($field, $instance){
		$id = $this->get_field_id($field->name);
		$name = $this->get_field_name($field->name);
		$value = !empty($instance[$field->name]) ? esc_attr($instance[$field->name]) : '';
		
		echo "<p>";
		$this->render_label($field, $id);
		echo "<input class=\"widefat\" id=\"{$id}\" name=\"{$name}\" type=\"text\" value=\"{$value}\" />";
		echo "</p>";
	}
	
	function render_hidden_field($field, $instance){
		$id = $this->get_field_id($field->name);
		$name = $this->get_field_name($field->name);
		$value = !empty($instance[$field->name]) ? esc_attr($instance[$field->name]) : '';
		
		echo "<input id=\"{$id}\" name=\"{$name}\" type=\"hidden\" value=\"{$value}\" />";
	}
	
	function render_number_field($field, $instance){
		$id = $this->get_field_id($field->name);
		$name = $this->get_field_name($field->name);
		$value = !empty($instance[$field->name]) ? intval($instance[$field->name]) : 0;
		
		echo "<p>";
		$this->render_label($field, $id);
		echo "<input id=\"{$id}\" name=\"{$name}\" type=\"number\" value=\"{$value}\" style=\"margin-left:20px;\" />";
		echo "</p>";
	}
	
	function render_html_field($field, $instance){
		$id = $this->get_field_id($field->name);
		$name = $this->get_field_name($field->name);
		$value = !empty($instance[$field->name]) ? esc_attr($instance[$field->name]) : '';
		
		echo "<p>";
		$this->render_label($field, $id);
		echo "<textarea id=\"{$id}\" name=\"{$name}\" style=\"display:block;width:100%;\" class=\"html-field\">{$value}</textarea>";
		echo "</p>";
	}
	
	function render_dropdown_field($field, $instance){
		$id = $this->get_field_id($field->name);
		$name = $this->get_field_name($field->name);
		$value = !empty($instance[$field->name]) ? esc_attr($instance[$field->name]) : '';
		
		echo "<p>";
		$this->render_label($field, $id);
		echo "<select class=\"widefat\" id=\"{$id}\" name=\"{$name}\" style=\"width:150px;margin-left:20px;\">";
		
		if($value == ""){
			$selected = " selected=\"selected\"";
		} else {
			$selected = "";
		}
			
		echo "<option value=\"\"{$selected}></option>";

		foreach($field->options as $key => $label){
			if(is_numeric($key)){
				$key = $label;
			}
			
			if($key == $value){
				$selected = " selected=\"selected\"";
			} else {
				$selected = "";
			}
			
			echo "<option value=\"{$key}\"{$selected}>{$label}</option>";
		}
		
		echo "</select>";
		echo "</p>";
	}
	
	function render_long_text_field($field, $instance){
		$id = $this->get_field_id($field->name);
		$name = $this->get_field_name($field->name);
		$value = !empty($instance[$field->name]) ? esc_attr($instance[$field->name]) : '';
		
		echo "<p>";
		$this->render_label($field, $id);
		echo "<textarea id=\"{$id}\" name=\"{$name}\" style=\"display:block;width:100%;\">{$value}</textarea>";
		echo "</p>";
	}
	
	function render_image_field($field, $instance){
		$id = $this->get_field_id($field->name);
		$name = $this->get_field_name($field->name);
		$value = !empty($instance[$field->name]) ? intval($instance[$field->name]) : 0;
		
		echo "<p>";
		$this->render_label($field, $id);
		echo "<input id=\"{$id}\" name=\"{$name}\" type=\"hidden\" value=\"{$value}\" />";

		echo "<span style=\"display:inline-block;width:150px;margin-left:20px;\">";
		echo "<input class=\"choose-image button\" type=\"button\" value=\"&hellip;\" title=\"" . __('Choose...') . "\" />";
		echo "</span>";

		echo "<span class=\"my-image\" style=\"display:block;text-align:center;margin-top:10px;\">";
		if($value > 0){
			echo wp_get_attachment_image($value, "thumbnail");
		}
		echo "</span>";
		echo "</p>";
	}
	
	function render_link_field($field, $instance){
		$id = $this->get_field_id($field->name);
		$name = $this->get_field_name($field->name);
		$value = !empty($instance[$field->name]) ? $instance[$field->name] : '';
		
		echo "<p>";
		$this->render_label($field, $id);
		echo "<input class=\"widefat\" id=\"{$id}\" name=\"{$name}\" type=\"text\" value=\"{$value}\" />";
		echo "</p>";
	}
	
	function render_menu_field($field, $instance){
		$id = $this->get_field_id($field->name);
		$name = $this->get_field_name($field->name);
		$value = !empty($instance[$field->name]) ? $instance[$field->name] : 0;
		
		echo "<p>";
		$this->render_label($field, $id);

		echo "<select class=\"widefat\" id=\"{$id}\" name=\"{$name}\" style=\"width:150px;margin-left:20px;\">";
		echo "<option></option>";

		foreach(get_terms('nav_menu', array('hide_empty' => false)) as $menu){
			if($menu->term_id == $value){
				$selected = " selected=\"selected\"";
			} else {
				$selected = "";
			}
			
			echo "<option value=\"{$menu->term_id}\"{$selected}>{$menu->name}</option>";
		}
		
		echo "</select>";

		echo "</p>";
	}
	
	function render_post_field($field, $instance){
		$id = $this->get_field_id($field->name);
		$name = $this->get_field_name($field->name);
		$value = !empty($instance[$field->name]) ? $instance[$field->name] : 0;
		
		echo "<p>";
		$this->render_label($field, $id);

		echo "<select class=\"widefat\" id=\"{$id}\" name=\"{$name}\" style=\"width:150px;margin-left:20px;\">";
		echo "<option></option>";

		foreach(get_posts(array("post_type" => isset($field->post_type) ? $field->post_type : "post", "numberposts" => -1)) as $post){
			if($post->ID == $value){
				$selected = " selected=\"selected\"";
			} else {
				$selected = "";
			}
			
			echo "<option value=\"{$post->ID}\"{$selected}>{$post->post_title}</option>";
		}
		
		echo "</select>";

		echo "</p>";
	}
	
	function render_page_field($field, $instance){
		$id = $this->get_field_id($field->name);
		$name = $this->get_field_name($field->name);
		$value = !empty($instance[$field->name]) ? $instance[$field->name] : 0;
		
		echo "<p>";
		$this->render_label($field, $id);

		echo "<span style=\"display:inline-block;width:150px;margin-left:20px;\">";

    wp_dropdown_pages(array(
      'show_option_none' => ' ',
      'name' => $name,
      'id' => $id,
      'selected' => $value,
    ));

		echo "</span>";

		echo "</p>";
	}
	
	function render_category_field($field, $instance){
		$id = $this->get_field_id($field->name);
		$name = $this->get_field_name($field->name);
		$value = !empty($instance[$field->name]) ? $instance[$field->name] : '';
    $taxonomy = !empty($field->taxonomy) ? $field->taxonomy : "category";
		
		echo "<p>";
		$this->render_label($field, $id);

		echo "<span style=\"display:inline-block;width:150px;margin-left:20px;\">";
    
    wp_dropdown_categories(array(
      'show_count' => 1,
      'hierarchical' => 1,
      'class' => 'widefat',
      'id' => $id,
      'name' => $name,
      'selected' => $value,
      'show_option_none' => ' ',
    ));

		echo "</span>";

		echo "</p>";
	}

	function render_checkbox_field($field, $instance){
		$id = $this->get_field_id($field->name);
		$name = $this->get_field_name($field->name);
		$value = !empty($instance[$field->name]) ? esc_attr(strip_tags($instance[$field->name])) : '';
  
		$checked = "";
		if ($value=="1")
			$checked = "checked = \"checked\"";
		
		echo "<p>";
		echo "<input id=\"{$id}\" name=\"{$name}\" type=\"checkbox\" value=\"1\" {$checked}/>";
		$this->render_label($field, $id);
		echo "</p>";
	}

	function render_hr_field($field, $instance){
		echo "<hr/>";
	}
}

add_action('admin_head', function() {
  ?>
  <script>
    (function ($) {
      $(document).on("click", 'input.choose-image', function (event) {
        var button = $(this);

        wp.media.editor.send.attachment = function (props, attachment) {
          button.parent().siblings("input[type=\"hidden\"]").val(attachment.id);
          button.parent().siblings("span.my-image").html('<img src="' + attachment.sizes.thumbnail.url + '" />');
        }

        wp.media.editor.open(this);
        event.preventDefault();
      });

      // $('div.widgets-sortables').bind('sortstop', activate);

    })(jQuery);
  </script>
  <?php
});

add_action('admin_enqueue_scripts', function($hook) {
  if($hook == "widgets.php"){
    wp_enqueue_media();
  }
});

function myplugin_register_widgets() {
  foreach (get_declared_classes() as $class) {
    if (is_subclass_of($class, "HH_Widget"))
      register_widget( $class );
  }
}

add_action( 'widgets_init', 'myplugin_register_widgets' );
