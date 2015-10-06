<?php



/* COMMENTS */

function mytheme_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   <li>
    <article <?php comment_class('media'); ?> id="comment-<?php comment_ID(); ?>">
      <a class="pull-left" href="<?php echo htmlspecialchars( get_comment_link($comment->comment_ID )) ?>">
        <?php echo get_avatar($comment, $size='64'); ?>
      </a>
      <div class="media-body">
        <header class="comment-author vcard">
            
          <?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
          <time><?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></time> 
          <span class="sharing-tools">
            <a href="<?php echo htmlspecialchars( get_comment_link($comment->comment_ID )) ?>"><i class="icon-link"></i></a>
            <span class='st_facebook_custom' st_url="<?php echo htmlspecialchars( get_comment_link($comment->comment_ID )) ?>"><i class="icon-facebook-sign"></i></span>
            <span class='st_twitter_custom' st_url="<?php echo htmlspecialchars( get_comment_link($comment->comment_ID )) ?>"><i class="icon-twitter-sign"></i></span>
            <span class='st_linkedin_custom' st_url="<?php echo htmlspecialchars( get_comment_link($comment->comment_ID )) ?>"><i class="icon-linkedin-sign"></i></span>
            <span class='st_email_custom' st_url="<?php echo htmlspecialchars( get_comment_link($comment->comment_ID )) ?>"><i class="icon-envelope"></i></span>   
          </span>
          <?php my_edit_link(get_edit_comment_link(), __('Edit comment')) ?>
        </header>
        <?php if ($comment->comment_approved == '0') : ?>
          <em><?php _e('Your comment is awaiting moderation.') ?></em>
          <br />
        <?php endif; ?>

        <?php comment_text() ?>

        <nav>
          <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => 'Reply <i class="icon-reply"></i>'))) ?>
        </nav>
      </div>
    </article>
  <!-- </li> is added by wordpress automatically -->
<?php
}