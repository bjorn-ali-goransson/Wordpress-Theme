<?php



/* CUSTOM OPTIONS */

function add_category_field_to_settings($name, $title, $default_value = NULL){
  add_taxonomy_field_to_settings($name, $title, 'category', $default_value);
}

function add_post_field_to_settings($name, $title, $post_type, $default_value = NULL){
  add_field_to_settings($name, $title, 'post_' . $post_type, $default_value);
}

function add_taxonomy_field_to_settings($name, $title, $taxonomy_name, $default_value = NULL){
  add_field_to_settings($name, $title, 'taxonomy_' . $taxonomy_name, $default_value);
}

function add_text_field_to_settings($name, $title, $default_value = NULL){
  add_field_to_settings($name, $title, 'text', $default_value);
}

function add_long_text_field_to_settings($name, $title, $default_value = NULL){
  add_field_to_settings($name, $title, 'long_text', $default_value);
}

function add_number_field_to_settings($name, $title, $default_value = NULL){
  add_field_to_settings($name, $title, 'number', $default_value);
}

function add_boolean_field_to_settings($name, $title, $default_value = NULL){
  add_field_to_settings($name, $title, 'boolean', $default_value);
}

function add_field_to_settings($name, $title, $type, $default_value = NULL){
  if(!isset($GLOBALS['my_settings_fields'])){
    $GLOBALS['my_settings_fields'] = array();
  }

  if(!isset($GLOBALS['my_settings_sections'])){
    add_section_to_settings('Skräddarsydda inställningar');
  }

  $GLOBALS['my_settings_fields'][$name] = (object)array(
    'title' => $title,
    'name' => $name,
    'type' => $type,
    'default_value' => $default_value,
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
    add_settings_section($section->name, '<span style="color:#999;">' . $section->title . '</span>', function() { }, 'my_settings');
  }

  foreach($GLOBALS['my_settings_fields'] as $field){
    if($field->type == 'text'){
      add_settings_field(
        $field->name,
        $field->title,
        function() use ($field){
          $options = get_option('my_settings');
  
          $value = '';

          if(array_key_exists($field->name, $options) && $options[$field->name] !== '' && (!isset($GLOBALS['my_settings_fields'][$field->name]->default_value) || $options[$field->name] != $GLOBALS['my_settings_fields'][$field->name]->default_value)){
            $value = $options[$field->name];
          }

          ?>
            <input type="text" class="regular-text" id="<?php echo $field->name; ?>" name="my_settings[<?php echo $field->name; ?>]" value="<?php echo esc_attr($value); ?>">
          <?php
            
          if(isset($GLOBALS['my_settings_fields'][$field->name]->default_value)){
            ?>
              <p class="description">"<?php echo $GLOBALS['my_settings_fields'][$field->name]->default_value; ?>"</p>
            <?php
          }
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

function get_option_value($name){
  $options = get_option('my_settings');
  
  if(!array_key_exists($name, $options) || $options[$name] === ''){
    if(!isset($GLOBALS['my_settings_fields'][$name]->default_value)){
      return NULL;
    }
    return $GLOBALS['my_settings_fields'][$name]->default_value;
  }

  return $options[$name];
}