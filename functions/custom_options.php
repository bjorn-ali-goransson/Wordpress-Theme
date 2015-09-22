<?php



/* CUSTOM OPTIONS */

function add_category_field_to_settings($title, $name){
  add_taxonomy_field_to_settings($title, $name, 'category');
}

function add_post_field_to_settings($title, $name, $post_type){
  add_field_to_settings($title, $name, 'post_' . $post_type);
}

function add_taxonomy_field_to_settings($title, $name, $taxonomy_name){
  add_field_to_settings($title, $name, 'taxonomy_' . $taxonomy_name);
}

function add_text_field_to_settings($title, $name){
  add_field_to_settings($title, $name, 'text');
}

function add_long_text_field_to_settings($title, $name){
  add_field_to_settings($title, $name, 'long_text');
}

function add_number_field_to_settings($title, $name){
  add_field_to_settings($title, $name, 'number');
}

function add_boolean_field_to_settings($title, $name){
  add_field_to_settings($title, $name, 'boolean');
}

function add_field_to_settings($title, $name, $type){
  if(!isset($GLOBALS['my_settings_fields'])){
    $GLOBALS['my_settings_fields'] = array();
  }

  $GLOBALS['my_settings_fields'][] = (object)array(
    'title' => $title,
    'name' => $name,
    'type' => $type,
  );
}

add_action('admin_init', function(){
  if(!isset($GLOBALS['my_settings_fields'])){
    return;
  }

  register_setting( 'my_settings', 'my_settings', function($input){ return $input; });
  add_settings_section('my_settings_main', 'Skräddarsydda inställningar', function() { }, 'my_settings');

  foreach($GLOBALS['my_settings_fields'] as $field){
    if($field->type == 'text'){
      add_settings_field(
        $field->name,
        $field->title,
        create_function('', '
          $options = get_option(\'my_settings\');
          echo \'<input type="text" id="' . $field->name . '" name="my_settings[' . $field->name . ']" value="\' . $options[\'' . $field->name . '\'] . \'">\';
        '),
        'my_settings',
        'my_settings_main'
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
        'my_settings_main'
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
        'my_settings_main'
      );
    }
    if($field->type == 'boolean'){
      add_settings_field(
        $field->name,
        $field->title,
        create_function('', '
          $options = get_option(\'my_settings\');
          echo \'<input type="checkbox" id="' . $field->name . '" name="my_settings[' . $field->name . ']" value="true" \' . ($options[\'' . $field->name . '\'] == \'true\' ? \'checked\' : \'\') . \'>\';
        '),
        'my_settings',
        'my_settings_main'
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
        'my_settings_main'
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
        'my_settings_main'
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



/* GET OPTION */

function get_my_option($key){
  $options = get_option('my_settings');

  if(!array_key_exists($key, $options)){
    return NULL;
  }

  return $options[$key];
}



/* OTHER */

