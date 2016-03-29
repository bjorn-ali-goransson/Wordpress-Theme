<?php

abstract class My_Widget extends WP_Widget {
	protected $identifier = null;
	protected $label = null;
	protected $description = null;
	protected $has_title = true;
	
	function My_Widget() {
		if($this->identifier == null){
		  $this->identifier = strtolower(get_class($this));
		}
    
    if($this->label == null){
			$this->label = preg_replace('/([a-z])([A-Z])/', '$1 $2', str_replace('_', ' ', get_class($this)));
		}
		
		parent::__construct($this->identifier, $this->label, array('description'=>$this->description));
	}
  
	function form( $instance ) {
    if(!$this->has_title){
      return;
    }

		$id = $this->get_field_id('title');
		$name = $this->get_field_name('title');
		$value = !empty($instance['title']) ? esc_attr($instance['title']) : '';
		
    ?>
      <p>
		    <label for="<?= $id ?>">Title</label>
		    <input class="widefat" id="<?= $id ?>" name="<?= $name ?>" type="text" value="<?= $value ?>" />
      </p>
    <?php
	}
	
	function widget($args, $instance){
		$instance = (object) $instance;

    foreach(acf_get_field_groups(array('widget' => $this->identifier)) as $field_group){
      foreach(acf_get_fields($field_group) as $field){
        $name = $field['name'];
        $instance->$name = get_field($name, "widget_{$this->id}");
      }
    }
		
		if(!empty($this->has_title)){
			$instance->title = apply_filters('widget_title', empty($instance->title)?'':$instance->title, $instance, $this->id_base);
		}
		
		$this->render_widget($instance, $args);
	}

	abstract function render_widget($instance, $args);
}