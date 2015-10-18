<?php



/* CUSTOM PROFILE */

function add_category_field_to_profile($name, $title){
  add_taxonomy_field_to_profile($name, $title, 'category');
}

function add_post_field_to_profile($name, $title, $post_type){
  add_field_to_profile($name, $title, 'post_' . $post_type);
}

function add_taxonomy_field_to_profile($name, $title, $taxonomy_name){
  add_field_to_profile($name, $title, 'taxonomy_' . $taxonomy_name);
}

function add_text_field_to_profile($name, $title){
  add_field_to_profile($name, $title, 'text');
}

function add_long_text_field_to_profile($name, $title){
  add_field_to_profile($name, $title, 'long_text');
}

function add_number_field_to_profile($name, $title){
  add_field_to_profile($name, $title, 'number');
}

function add_boolean_field_to_profile($name, $title){
  add_field_to_profile($name, $title, 'boolean');
}

function add_field_to_profile($name, $title, $type){
  if(!isset($GLOBALS['my_profile_fields'])){
    $GLOBALS['my_profile_fields'] = array();
  }

  $GLOBALS['my_profile_fields'][] = (object)array(
    'title' => $title,
    'name' => $name,
    'type' => $type,
  );
}



/* SHOW FORM FIELDS */ // http://justintadlock.com/archives/2009/09/10/adding-and-using-custom-user-profile-fields

//add_action('show_user_profile', 'my_show_extra_profile_fields');
//add_action('edit_user_profile', 'my_show_extra_profile_fields');
add_action('personal_options', 'my_show_extra_profile_fields');

function my_show_extra_profile_fields($profileuser) {
  if(!isset($GLOBALS['my_profile_fields'])){
    return;
  }
  
  ?>
	  <table class="form-table" style="margin-top: 0;">
      <?php
        foreach($GLOBALS['my_profile_fields'] as $field){
          ?>
		        <tr>
			        <th><label for="profile_<?php echo $field->name; ?>"><?php echo $field->title; ?></label></th>
			        <td>
                <?php
                  if($field->type == 'text'){
                    ?>
                      <input type="text" class="regular-text" id="profile_<?php echo $field->name; ?>" name="profile_<?php echo $field->name; ?>" value="<?php echo esc_attr(get_profile_value($profileuser->ID, $field->name)); ?>">
                    <?php
                  }
                ?>
			        </td>
		        </tr>
          <?php
        }
      ?>
	  </table>
  <?php
}



/* SAVING FORM FIELDS */

add_action('personal_options_update', 'my_save_extra_profile_fields');
add_action('edit_user_profile_update', 'my_save_extra_profile_fields');

function my_save_extra_profile_fields($user_id) {
  if(!isset($GLOBALS['my_profile_fields'])){
    return;
  }
  
  foreach($GLOBALS['my_profile_fields'] as $field){
	  update_usermeta($user_id, $field->name, $_POST['profile_' . $field->name]);
  }
}



/* GET PROFILE VALUE */

function get_profile_value($arg1, $arg2 = NULL){
  $user_id = NULL;
  $meta_key = NULL;

  if($arg2 == NULL){
    $user_id = get_current_user_id();
    $meta_key = $arg1;
  } else {
    $user_id = $arg1;
    $meta_key = $arg2;
  }

  return get_the_author_meta($meta_key, $user_id);
}