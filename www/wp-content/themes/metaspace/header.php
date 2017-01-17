<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon-16x16.png">
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="/apple-touch-icon-152x152.png">
    <link rel="stylesheet" href="/styles/app.css">
    <title><?php wp_title(); ?></title>
    <meta name="description" content="<?php bloginfo( 'description' ); ?>">
    
    <meta property="og:title" content="​*METASPACE*​" />
    <meta property="og:description" content="​Project of developing high-performance algorithms for high-resolution imaging mass spectrometry." />    
    <meta property="og:url" content="<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?>" />
    <meta property="og:image" content="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/images/EMBL_open_graph.jpg'; ?>" />
  </head>
  <body>
    <div class="l-wrapper">
      <header class="b-header">
        <div class="l-inner"><a href="/" class="b-logo"><img src="/files/logo.png" alt="Metaspace" title="Metaspace" class="b-logo__img"></a>
        <?php
                $menu = wp_nav_menu( array( 'container' => 'nav',
                                    'container_class' => 'b-nav',
                                    'items_wrap' => '%3$s',
                                    'depth' => 1,
                                    'echo' => 0,
                                    'walker' => new ThemeTopRight_Walker_Nav_Menu
                                  )
                           );
                echo strip_tags($menu, '<nav><a>');
        ?>
          
          <!--nav class="b-nav"><a href="#consortium" class="b-nav__item js-scroll-link">Consortium</a><a href="#advisory" class="b-nav__item js-scroll-link">Advisory Board</a><a href="#publications" class="b-nav__item js-scroll-link">Publications</a><a href="#events" class="b-nav__item js-scroll-link">Events</a><a href="#contacts" class="b-nav__item js-scroll-link">Contacts</a></nav-->
        </div>
      </header>
