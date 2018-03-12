<!DOCTYPE html>
<html>
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php wp_title('&raquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <!--<link rel="icon" href="<?php bloginfo("url") ?>/favicon.ico">-->

    <?php wp_head(); ?>
  </head>
<body ng-controller="Main" <?php body_class('no-js'); ?>><script>document.body.className = document.body.className.replace(" no-js", " js");</script>
  <!--[if lt IE 9]>
    <p>You are using an outdated browser. <a href="http://browsehappy.com/" class="alert-link">Upgrade your browser today</a></p>
  <![endif]-->