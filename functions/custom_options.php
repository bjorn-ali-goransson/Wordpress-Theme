<?php



/* CUSTOM OPTIONS */

function add_category_field_to_settings($name, $title){
  add_taxonomy_field_to_settings($name, $title, 'category');
}

function add_post_field_to_settings($name, $title, $post_type){
  add_field_to_settings($name, $title, 'post_' . $post_type);
}

function add_taxonomy_field_to_settings($name, $title, $taxonomy_name){
  add_field_to_settings($name, $title, 'taxonomy_' . $taxonomy_name);
}

function add_text_field_to_settings($name, $title){
  add_field_to_settings($name, $title, 'text');
}

function add_long_text_field_to_settings($name, $title){
  add_field_to_settings($name, $title, 'long_text');
}

function add_number_field_to_settings($name, $title){
  add_field_to_settings($name, $title, 'number');
}

function add_boolean_field_to_settings($name, $title){
  add_field_to_settings($name, $title, 'boolean');
}

function add_field_to_settings($name, $title, $type){
  if(!isset($GLOBALS['my_settings_fields'])){
    $GLOBALS['my_settings_fields'] = array();
  }

  if(!isset($GLOBALS['my_settings_sections'])){
    add_section_to_settings('Skräddarsydda inställningar');
  }

  $GLOBALS['my_settings_fields'][] = (object)array(
    'title' => $title,
    'name' => $name,
    'type' => $type,
    'section' => $GLOBALS['my_settings_sections'][count($GLOBALS['my_settings_sections']) - 1]->name,
  );
}

function add_section_to_settings($title){
  if(!isset($GLOBALS['my_settings_sections'])){
    $GLOBALS['my_settings_sections'] = array();
  }

  $GLOBALS['my_settings_sections'][] = (object)array(
    'title' => $title,
    'name' => $title,
  );
}

add_action('admin_init', function(){
  if(!isset($GLOBALS['my_settings_fields'])){
    return;
  }

  register_setting( 'my_settings', 'my_settings', function($input){ return $input; });

  
  foreach($GLOBALS['my_settings_sections'] as $section){
    add_settings_section($section->title, $section->title, function() { }, 'my_settings');
  }

  foreach($GLOBALS['my_settings_fields'] as $field){
    if($field->type == 'text'){
      add_settings_field(
        $field->name,
        $field->title,
        function() use ($field){
          ?>
            <input type="text" class="regular-text" id="<?php echo $field->name; ?>" name="my_settings[<?php echo $field->name; ?>]" value="<?php echo esc_attr(get_option_value($field->name)); ?>">
          <?php
        },
        'my_settings',
        $field->section
      );
    }
    if($field->type == 'long_text'){
      add_settings_field(
        $field->name,
        $field->title,
        create_function('', '
          $options = get_option(\'my_settings\');
          echo \'<textarea id="' . $field->name . '" name="my_settings[' . $field->name . ']">\' . $options[\'' . $field->name . '\'] . \'</textarea>\';
        '),
        'my_settings',
        $field->section
      );
    }
    if($field->type == 'number'){
      add_settings_field(
        $field->name,
        $field->title,
        create_function('', '
          $options = get_option(\'my_settings\');
          echo \'<input type="number" id="' . $field->name . '" name="my_settings[' . $field->name . ']" value="\' . $options[\'' . $field->name . '\'] . \'">\';
        '),
        'my_settings',
        $field->section
      );
    }
    if($field->type == 'boolean'){
      add_settings_field(
        $field->name,
        $field->title,
        function() use ($field){
          ?>
            <input type="checkbox" id="<?php echo $field->name; ?>" name="my_settings[<?php echo $field->name; ?>]" value="true" <?php if(get_option_value($field->name) == 'true'){ echo 'checked'; } ?>>
          <?php
        },
        'my_settings',
        $field->section
      );
    }
    if(strpos($field->type, 'taxonomy_') === 0){
      $taxonomy_type = substr($field->type, strlen('taxonomy_'));

      add_settings_field(
        $field->name,
        $field->title,
        create_function('', '
          $options = get_option(\'my_settings\');
          wp_dropdown_categories(array(
            \'taxonomy\' => \'' . $taxonomy_type . '\',
            \'id\' => \'' . $field->name . '\',
            \'name\' => \'my_settings[' . $field->name . ']\',
            \'selected\' => $options[\'' . $field->name . '\'],
            \'hide_empty\' => 0,
            \'hierarchical\' => 1,
          ));
        '),
        'my_settings',
        $field->section
      );
    }
    if(strpos($field->type, 'post_') === 0){
      $post_type = substr($field->type, strlen('post_'));
      
      add_settings_field(
        $field->name,
        $field->title,
        create_function('', '
          $options = get_option(\'my_settings\');

          echo \'<select id="' . $field->name . '" name="my_settings[' . $field->name . ']">\';

          foreach(get_posts(array(\'post_type\' => \'' . $post_type . '\', \'nopaging\' => TRUE)) as $post){
            echo \'<option value="\' . $post->ID . \'"\' . ($options[\'' . $field->name . '\'] == $post->ID ? \' selected="selected"\' : \'\') . \'>\' . $post->post_title . \'</option>\';
          }

          echo \'</select>\';
        '),
        'my_settings',
        $field->section
      );
    }
  }
});

add_action('admin_menu', function() {
  if(!isset($GLOBALS['my_settings_fields'])){
    return;
  }
  
  add_options_page('Custom options', 'Custom options', 'manage_options', 'my_settings', function() {
    ?>
      <div>
        <form action="options.php" method="post">
          <?php settings_fields('my_settings'); ?>
          <?php do_settings_sections('my_settings'); ?>
 
          <input type="submit" class="button button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
        </form>
      </div>
    <?php
  });
});



/* GET OPTION VALUE */

function get_option_value($key){
  $options = get_option('my_settings');

  if(!array_key_exists($key, $options)){
    return NULL;
  }

  return $options[$key];
}