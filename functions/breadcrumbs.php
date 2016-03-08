<?php
function breadcrumbs($text_before){
  global $post;
  ?>
    <ol class="breadcrumb">
      <?php
        $ids = array(get_option('page_on_front'));

        if(is_home()){
            $page_for_posts = get_post(get_option('page_for_posts'));
          
            foreach($page_for_posts->ancestors as $id){
              array_push($ids, $id);
            }
                      
            array_push($ids, $page_for_posts->ID);
        } else {
          if($post->post_type == 'page'){
            foreach(array_reverse($post->ancestors) as $id){
              array_push($ids, $id);
            }
          }

          if($post->post_type == 'post'){
            $page_for_posts = get_post(get_option('page_for_posts'));
          
            foreach($page_for_posts->ancestors as $id){
              array_push($ids, $id);
            }
                      
            array_push($ids, $page_for_posts->ID);
          }

          array_push($ids, $post->ID);
        }

        foreach($ids as $i => $id){
          ?>
            <li<?= $id == array_slice($ids, -1)[0] ? ' class="active"' : '' ?>>
              <?php if($i == 0){ echo $text_before; } ?>
              <a href="<?php echo get_permalink($id); ?>"><?php echo get_the_title($id); ?></a>
            </li>
          <?php
        }
      ?>
    </ol>
  <?php
}