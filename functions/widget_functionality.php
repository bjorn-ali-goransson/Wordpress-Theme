<?php

require_once(dirname(__FILE__) . '/widget_class.php');

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

add_action('widgets_init', function() {
  foreach (get_declared_classes() as $class) {
    if (is_subclass_of($class, "My_Widget"))
      register_widget( $class );
  }
});