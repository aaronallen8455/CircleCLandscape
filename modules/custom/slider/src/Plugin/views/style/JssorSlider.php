<?php
/**
 * Created by PhpStorm.
 * User: Aaron Allen
 * Date: 5/20/2017
 * Time: 5:09 AM
 */

namespace Drupal\slider\Plugin\views\style;


use Drupal\views\Plugin\views\style\StylePluginBase;

/**
 * Unformatted style plugin to render rows one after another with no
 * decorations.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "jssor_slider",
 *   title = @Translation("Jssor slider"),
 *   help = @Translation("Formats rows for use with jssor."),
 *   theme = "slider_view_slider",
 *   display_types = {"normal"}
 * )
 */
class JssorSlider extends StylePluginBase {
  /**
   * Does the style plugin allows to use style plugins.
   *
   * @var bool
   */
  protected $usesRowPlugin = TRUE;

  /**
   * Does the style plugin support custom css class for the rows.
   *
   * @var bool
   */
  protected $usesRowClass = TRUE;
}