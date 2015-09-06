<?php
  
add_number_field_to_settings('Maximalt antal beställningar som får göras på en och samma förfrågning', 'maximum_order_count_per_inquiry');
add_number_field_to_settings('Minimum antal mäklarkontor att kontakta (st)', 'minimum_broker_office_count');
add_number_field_to_settings('Minimum antal enskilda mäklare att kontakta (st)', 'minimum_broker_email_count');
add_number_field_to_settings('Maximal distans mellan objektet och mäklarkontor (km)', 'maximum_broker_office_distance');
add_number_field_to_settings('Pris (kr)', 'price');

add_text_field_to_settings('Skicka e-post om nya förfrågningar till', 'send_email_about_new_inquiries_to');

add_text_field_to_settings('Text innan mäklar-listan på landningssidor', 'broker_showroom_before');
add_text_field_to_settings('Text efter mäklar-listan på landningssidor', 'broker_showroom_after');

add_text_field_to_settings('Sidfot rad 1', 'page_footer_row_1');
add_text_field_to_settings('Sidfot rad 2', 'page_footer_row_2');
add_text_field_to_settings('Sidfot rad 3', 'page_footer_row_3');
add_text_field_to_settings('Länk till Facebook', 'facebook_link');
add_text_field_to_settings('Länk till Instagram', 'instagram_link');
add_text_field_to_settings('Länk till LinkedIn', 'linkedin_link');
add_text_field_to_settings('Copyright-notis', 'page_footer_copyright_notice');

add_boolean_field_to_settings('Skicka ingen e-post utan spara den i systemet', 'send_no_emails_but_save_them_in_the_system');

//

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
  }
});

add_action('admin_menu', function() {
  add_options_page('Skräddarsydda inställningar', 'Skräddarsydda inställningar', 'manage_options', 'my_settings', function() {
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