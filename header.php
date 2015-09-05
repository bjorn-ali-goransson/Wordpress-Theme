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

    <link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>

    <!-- Wordpress Head Items -->
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

    <style>
      [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak],
      .ng-cloak, .x-ng-cloak,
      .ng-hide:not(.ng-hide-animate) {
        display: none !important;
      }
    </style>
    
    <?php echo_header_variable('siteUrl', get_bloginfo('url') . '/'); ?>
    <?php echo_header_variable('currentUserId', get_current_user_id()); ?>

    <?php wp_head(); ?>
  </head>
<body ng-controller="Main" <?php body_class('no-js'); ?>"><script>document.body.className = document.body.className.replace(" no-js", "").replace("no-js ", "");</script>
  <!--[if lt IE 7]>
    <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a></p>
  <![endif]-->