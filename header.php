<!DOCTYPE html>
<html ng-app="app">
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame (remove this if you use the .htaccess) -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php wp_title('&raquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <!--<link rel="icon" href="<?php bloginfo("url") ?>/favicon.ico">-->

    <!-- Wordpress Head Items -->
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

    <style>
      [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak],
      .ng-cloak, .x-ng-cloak,
      .ng-hide:not(.ng-hide-animate) {
        display: none !important;
      }
    </style>
    
    <?php output_javascript_variable('siteUrl', get_bloginfo('url') . '/'); ?>

    <?php wp_head(); ?>
  </head>
<body ng-controller="Main" <?php body_class('no-js'); ?>><script>document.body.className = document.body.className.replace(" no-js", " js");</script>
  <!--[if lt IE 9]>
    <div class="alert alert-warning"><div class="container">You are using an outdated browser. <a href="http://browsehappy.com/" class="alert-link">Upgrade your browser today</a></div></div>
  <![endif]-->
    
  <nav class="navbar" role="navigation">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only"><?php _e('Toggle navigation'); ?></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        
        <a class="navbar-brand" href="<?php echo site_url(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/logo.png"></a>
      </div>

      <div class="collapse navbar-collapse">
        <?php my_menu('main', "nav navbar-nav") ?>
      </div>
    </nav>
  </div>