<?php

require_once 'menus.php';

function my_sub_menu($class_names = "nav"){
  if (!$post = get_post()) {
    return;
  }
  
  $top_ancestor = $post->ID;
  if ($post->post_parent) {
    $ancestors = array_reverse(get_post_ancestors($post->ID));
    $top_ancestor = $ancestors[0];
  }

  ?>
    <ul class="<?= $classes ?>">
      <?php wp_list_pages(array('title_li' => '', 'sort_column' => 'menu_order', 'child_of' => $top_ancestor)); ?>
    </ul>
  <?php
}