<?php
/**
 * @file
 * Contains the theme's functions to manipulate Drupal's default markup.
 *
 * Complete documentation for this file is available online.
 * @see https://drupal.org/node/1728096
 */

/**
 * Alter CSS files before they are output on the page.
 */
function rsc2014_css_alter(&$css) {
  // Remove css files which are hard to override. Use our own.
  unset($css[drupal_get_path('module', 'nice_menus') . '/css/nice_menus_default.css']);
  unset($css[drupal_get_path('module', 'nice_menus') . '/css/nice_menus.css']);
  unset($css[drupal_get_path('module', 'addtoany') . '/addtoany.css']);
}


function rsc2014_preprocess_node(&$vars) {
  $c = &$vars['content'];

  /*
   * For all view modes:
   */
  
  // For all content types:
  
  $l = &$c['links'];
  if (empty($l['#links'])) {
    $l['#links'] = array();
  }
  
  if (!empty($l['comment']['#links'])) {
    $l['#links'] += $l['comment']['#links'];
    unset($l['comment']);
  }
  if (!empty($l['node']['#links'])) {
    $l['#links'] += $l['node']['#links'];
    unset($l['node']);
  }
  
  // because theme_links does not support a weight variable, the only way to order these here is to order the array
  if (!empty($l['#links']['node-readmore'])) {
    // put the read more link first
    $l['#links'] = array('node-readmore' => $l['#links']['node-readmore']) + $l['#links'];
  }

  // For content types rsc-library-article and rsc-library-audio:
  if (in_array($vars['type'],array('rsc_library_article','rsc_library_audio'))) {

    // find and render difficulty and author fields and merge them into a list:
    $article_infos = array();
    foreach ($c as $fieldname => &$field) {
      if (substr($fieldname, 0, 12) === 'rscl_author_') {
        $article_infos[1] = render($field);
        unset($c[$fieldname]);
      }
      else {
        if (substr($fieldname, 0, 16) === 'rscl_difficulty_') {
          $article_infos[0] = render($field);
          unset($c[$fieldname]);
        }
      }
    }
    ksort($article_infos);
    if (!empty($article_infos)) {
      $c['article_info'] = array(
        '#weight' => -20,
        '#markup' => '<ul class="article-info"><li class="first">' . implode('</li> <li>', $article_infos) . '</li></ul>',
      );
    }

  }

  /*
   * For specific view modes:
   */

  switch($vars['view_mode']) {

    case 'full':

      // For content types rsc-library-article and rsc-library-audio:
      if (in_array($vars['type'],array('rsc_library_article','rsc_library_audio'))) {

        // create compound field from source, date and pages fields
        $src = array();
        $pages = "";
        // find and render source field
        foreach($c as $fieldname => &$field) {
          if (substr($fieldname,0,12) === 'rscl_source_') {
            if (!empty($field[0])) {
              $src[] = t('Source: !src',array('!src'=>trim(render($field))));
              unset($c[$fieldname]);
            }
            break; // there is only one
          }
        }
        if (!empty($c['rscl_date'][0])) {
          $src[] = trim(render($c['rscl_date']));
          unset($c['rscl_date']);
        }
        if (!empty($c['rscl_pages'][0])) {
          $pages = t('!pages&nbsp;pages.',array('!pages'=>trim(render($c['rscl_pages'][0]))));
          unset($c['rscl_pages']);
        }
        if ($src && $pages) {
          $c['source_info'] = array(
            '#weight' => -10,
            '#markup' => implode(', ',$src).". ".$pages,
          );
        } else if ($src) {
          $c['source_info'] = array(
            '#weight' => -10,
            '#markup' => implode(', ',$src),
          );
        } else if ($pages) {
          $c['source_info'] = array(
            '#weight' => -10,
            '#markup' => $pages,
          );
        }

        // make a list of fields (better semantics than a lot of divs)
        $exclude = array('links','comments','rscl_body','article_info','rscl_link','rscl_attachment','field_image');
        $items = array();
        foreach(array_diff(array_keys($c),$exclude) as $key) {
          $field = &$c[$key];
          $items[$field['#weight']] = render($field);
          unset($field);
        }
        ksort($items);
        if (!empty($items))
          $c['fields-list'] = array(
            '#weight' => 4,
            '#markup' => '<ul class="fields-list"><li class="first">'.implode('</li><li>',$items).'</li></ul>',
          );
      }

      break;

    case 'rscl_block':

      // use template node--rsc-block.tpl.php
      $vars['theme_hook_suggestions'][] = "node__{$vars['view_mode']}";

      break;

  }
  
}


/**
 * implements template_preprocess_user_profile(&$variables)
 */
function rsc2014_preprocess_user_profile(&$variables) {
  if (!empty($variables['user_profile']['profile_preacher']['view'])) {
    $v = &$variables['user_profile']['profile_preacher']['view'];
    reset($v);
    $k1 = key($v);
    reset($v[$k1]);
    $k2 = key($v[$k1]);
    $p = &$v[$k1][$k2];
    
    $languages = array();
    if (!empty($p['field_i_can_read']))
      foreach ($p['field_i_can_read'] as $key => $item)
        if (substr($key,0,1) != '#') {
          $languages[] = render($item);
          unset($p['field_i_can_read'][$key]);
        }
    
    $p['field_i_can_read'][0] = array('#markup' => implode($languages,',&nbsp;'));
  }
  
  if (!empty($variables['user_profile']['summary'])) {
    $variables['user_profile']['summary']['#attributes']['class'][] = 'inline';
  }
}


/**
 * Hacky implementation of hook_init().
 * https://api.drupal.org/api/drupal/modules!system!system.api.php/function/hook_init/7
 *
 * This runs as soon as template.php is included.
 */
rsc2014_init(); // This line is required because Drupal 7 does not invoke hook_init for themes.
function rsc2014_init() {

  /*
   * Set the theme flavour
   *
   * This allows the site builder to use the php filter on blocks ("Pages on
   * which this PHP code returns TRUE (experts only)") to detect the theme. For
   * example, to hide a block on PL pages, use:
   *     return $GLOBALS['rsc2014']['flavour'] != 'pl';
   *
   * To make it work for drupal sites that live in a subfolder, don't use
   * request_uri(). Rather use arg() and/or request_path(). Note that arg will
   * return the internal argument of the page rather than the aliased URL seen
   * by the browser. Sometimes useful, other times a pain.
   */

  $GLOBALS['rsc2014'] = array();
  $GLOBALS['rsc2014']['theme_path'] = drupal_get_path('theme', 'rsc2014'); 
  $GLOBALS['rsc2014']['flavour'] = 'default';
  
  $p = &$GLOBALS['rsc2014']['theme_path'];

  if (rsc2014_preaching_library()) {

    // Notify everyone (blocks, preprocess functions, etc.) that the PL flavour should be used
    $GLOBALS['rsc2014']['flavour'] = 'pl';

    // Add CSS and JS for the PL theme
    drupal_add_css($p.'/css/pl.css', array('group' => CSS_THEME));
    drupal_add_js($p.'/js/pl.js', array('group' => JS_THEME));

  } else {

    // Add CSS for the default (library) theme
    // TODO: I wonder whether 'every_page' should really be TRUE here (Dolf, 20160416)
    drupal_add_css($p.'/css/lib.css', array('group' => CSS_THEME, 'every_page' => TRUE));

  }
  
  // Add js for front page
  if (drupal_is_front_page()) {
    drupal_add_js($p.'/js/front.js', array('group' => JS_THEME));
  }

}


/**
 * Override or insert variables into the page templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("page" in this case.)
 */
function rsc2014_preprocess_page(&$variables) {
  // check if the user is logged in
  global $user;
  if ($user->uid) {
    // Logged in
  } else {
    // Not logged in
    $a0 = arg(0);
    $a1 = arg(1);
    if ($a0 == 'pl' && $a1 == 'user') {
      $a0 = $a1;
      $a1 = arg(2);
    }
    if ($a0 == 'user') {
      switch (arg(1)) {
        case 'password':
          drupal_set_title(t('Request new password'));
          break;
        case 'register':
          drupal_set_title(t('Create new account'));
          break;
        default:
          drupal_set_title(t('Login'));
          break;
      }
    }
  }
  // FIXME: this does not work for the <title> tag in <head>. For some reason, "Home | site-name" is used when coming from <front> to user/login, etc.

  // set the correct page.tpl.php for the theme flavour
  if ($GLOBALS['rsc2014']['flavour'] == 'pl') {
    $variables['theme_hook_suggestions'][] = 'page__preaching_library';
  }
}


function rsc2014_preprocess_html(&$variables) {
  // add body classes for user roles
  global $user;
  foreach($user->roles as $role){
    $variables['classes_array'][] = 'role-' . drupal_html_class($role);
  }
}


/**
 * Return a themed breadcrumb trail.
 *
 * @param $variables
 *   - title: An optional string to be used as a navigational heading to give
 *     context for breadcrumb links to screen-reader users.
 *   - title_attributes_array: Array of HTML attributes for the title. It is
 *     flattened into a string within the theme function.
 *   - breadcrumb: An array containing the breadcrumb links.
 * @return
 *   A string containing the breadcrumb output.
 */
function rsc2014_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];
  $output = '';

  // Return the breadcrumb with separators.
  if (!empty($breadcrumb)) {
    $breadcrumb_before = '';
    $breadcrumb_after = '&nbsp;&gt;&nbsp;';

    // Provide a navigational heading to give context for breadcrumb links to screen-reader users.
    if (empty($variables['title'])) {
      $variables['title'] = t('You are here');
    }
    // Unless overridden by a preprocess function, make the heading invisible.
    if (!isset($variables['title_attributes_array']['class'])) {
      $variables['title_attributes_array']['class'][] = 'element-invisible';
    }

    // Build the breadcrumb trail.
    $output = '<nav class="breadcrumb" role="navigation">';
    $output .= '<h2' . drupal_attributes($variables['title_attributes_array']) . '>' . $variables['title'] . '</h2>';
    $output .= '<ol><li>' . implode($breadcrumb_after . '</li><li>' . $breadcrumb_before, $breadcrumb) . '</li></ol>';
    $output .= '</nav>';
  }

  return $output;
}


/**
 * Override comment.module comment_forbidden link
 */
function rsc2014_comment_post_forbidden($variables) {
  $node = $variables['node'];
  global $user;

  // Since this is expensive to compute, we cache it so that a page with many
  // comments only has to query the database once for all the links.
  $authenticated_post_comments = &drupal_static(__FUNCTION__, NULL);

  if (!$user->uid) {
    if (!isset($authenticated_post_comments)) {
      // We only output a link if we are certain that users will get permission
      // to post comments by logging in.
      $comment_roles = user_roles(TRUE, 'post comments');
      $authenticated_post_comments = isset($comment_roles[DRUPAL_AUTHENTICATED_RID]);
    }

    if ($authenticated_post_comments) {
      // We cannot use drupal_get_destination() because these links
      // sometimes appear on /node and taxonomy listing pages.
      if (variable_get('comment_form_location_' . $node->type, COMMENT_FORM_BELOW) == COMMENT_FORM_SEPARATE_PAGE) {
        $destination = array('destination' => "comment/reply/$node->nid#comment-form");
      }
      else {
        $destination = array('destination' => "node/$node->nid#comment-form");
      }

      return l(t('Comment'),'user/login', array('query' => $destination));
    }
  }
}


/**
 * Determine if the current page request has something to do with the Preaching
 * Library. Since a theme and a module may not depend on one another, there is
 * a copy of this function in rsc_cl. Keep them in sync please!
 */
function rsc2014_preaching_library() {

  return

    // URL starts with /pl or /preacher
    in_array(arg(0), array('pl', 'preacher',)) ||

    // A file is being accessed from the PL directory (needed to make 404 pages look like the PL theme)
    substr(request_path(), 0, 15) == 'system/files/pl';

}
