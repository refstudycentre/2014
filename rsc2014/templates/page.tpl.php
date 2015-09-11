<?php
/**
 * @file
 * Returns the HTML for a single Drupal page.
 *
 * Complete documentation for this file is available online.
 * @see https://drupal.org/node/1728148
 */
?>

<div id="page">

  <div id="midcol">
  
    <header class="header" id="header" role="banner">

      <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" class="header__logo" id="logo"><img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" class="header__logo-image" /></a>
      <h1 class="header__site-name" id="site-name">
        <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" class="header__site-link" rel="home"><span><?php print $site_name; ?></span></a>
      </h1>
  
      <?php print render($page['header']); ?>
  
    </header>
    
    <div id="page-title-area">
      <?php print $breadcrumb; ?>
      <?php print $feed_icons; ?>
      <a id="main-content"></a>
      <?php print render($title_prefix); ?>
      <?php if ($title): ?>
        <h1 class="page__title title" id="page-title"><?php print $title; ?></h1>
      <?php endif; ?>
      <?php print render($title_suffix); ?>
    </div>
    
    <div id="content" class="column" role="main">
    
      <?php print $messages; ?>
      <?php print render($tabs); ?>
      <?php print render($page['help']); ?>
      <?php if ($action_links): ?>
        <ul class="action-links"><?php print render($action_links); ?></ul>
      <?php endif; ?>
      <?php print render($page['content']); ?>
      
    </div>
    
    <?php
      // Render regions to see if there's anything in them:
      $content_mid_left = render($page['content_mid_left']);
      $content_mid_right = render($page['content_mid_right']);
      $content_lower = render($page['content_lower']);
      $sidebar_second = render($page['sidebar_second']);
      $sidebar_first  = render($page['sidebar_first']);
    ?>
    
    <?php if ($content_mid_left || $content_mid_right): ?>
      <div id="content_mids_wrapper">
        <div id="content_mid_left" class="column" role="main"><?php print $content_mid_left; ?></div>
        <div id="content_mid_right" class="column" role="main"><?php print $content_mid_right; ?></div>
      </div>
    <?php endif; ?>
    
    <?php if ($content_lower): ?>
      <div id="content_lower" class="column" role="main"><?php print $content_lower; ?></div>
    <?php endif; ?>
    
  </div>

  <?php if ($sidebar_first || $sidebar_second): ?>
    <aside class="sidebars">
      <?php print $sidebar_second; ?>
      <?php print $sidebar_first; ?>
    </aside>
  <?php endif; ?>

</div>

<?php print render($page['footer']); ?>

<?php print render($page['bottom']); ?>
