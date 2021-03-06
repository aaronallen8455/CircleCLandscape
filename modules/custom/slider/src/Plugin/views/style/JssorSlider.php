<?php
/**
 * Created by PhpStorm.
 * User: Aaron Allen
 * Date: 5/20/2017
 * Time: 5:09 AM
 */

namespace Drupal\slider\Plugin\views\style;


use Drupal\Core\Form\FormStateInterface;
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

  /**
   * {@inheritdoc}
   */
  public function defineOptions() {
    $options = parent::defineOptions();
    $options['width'] = ['default' => '1300'];
    $options['height'] = ['default' => '900'];

    return $options;
  }

  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    $form['width'] = [
      '#type' => 'number',
      '#title' => $this->t('Width of images'),
      '#default_value' => $this->options['width'],
      '#required' => TRUE,
      '#min' => 10,
    ];

    $form['height'] = [
      '#type' => 'number',
      '#title' => $this->t('Height of images'),
      '#default_value' => $this->options['height'],
      '#required' => TRUE,
      '#min' => 10,
    ];
  }
}