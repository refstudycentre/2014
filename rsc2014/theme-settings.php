<?php
/**
 * Implements hook_form_system_theme_settings_alter().
 *
 * @param $form
 *   Nested array of form elements that comprise the form.
 * @param $form_state
 *   A keyed array containing the current state of the form.
 */
function rsc2014_form_system_theme_settings_alter(&$form, &$form_state, $form_id = NULL)  {
  // Work-around for a core bug affecting admin themes. See issue #943212.
  if (isset($form_id)) {
    return;
  }
  
  unset($form['breadcrumb']); // We have our own breadcrumb solution
  $form['theme_settings']['toggle_logo'] = array(
    '#type'  => 'value',
    '#value' => '1',
  );
  $form['theme_settings']['toggle_name'] = array(
    '#type'  => 'value',
    '#value' => '1',
  );
  $form['theme_settings']['toggle_favicon'] = array(
    '#type'  => 'value',
    '#value' => '1',
  );
  $form['favicon'] = array('default_favicon' => array(
    '#type'  => 'value',
    '#value' => '1',
    '#tree'  => FALSE,
  ));

  // Create the form using Forms API: http://api.drupal.org/api/7

  /* -- Delete this line if you want to use this setting
  $form['rsc2014_example'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('rsc2014 sample setting'),
    '#default_value' => theme_get_setting('rsc2014_example'),
    '#description'   => t("This option doesn't do anything; it's just an example."),
  );
  // */

  // Remove some of the base theme's settings.
  /* -- Delete this line if you want to turn off this setting.
  unset($form['themedev']['zen_wireframes']); // We don't need to toggle wireframes on this site.
  // */

  // We are editing the $form in place, so we don't need to return anything.
}
