<?php
/**
 * Created by PhpStorm.
 * User: Aaron Allen
 * Date: 5/25/2017
 * Time: 5:38 AM
 */

namespace Drupal\copyright\Plugin\Block;


use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a responsive menu block.
 *
 * @Block(
 *   id = "copyright",
 *   admin_label = @Translation("Copyright block"),
 * )
 */
class Copyright extends BlockBase implements BlockPluginInterface {

  /**
   * @return array
   */
  public function build() {
    $config = $this->getConfiguration();
    $copyrightName = isset($config['copyright_name']) ? $config['copyright_name'] : '';

    return [
      '#theme' => 'copyright',
      '#copyright_name' => $this->t($copyrightName)
    ];
  }

  /**
   * use this form to get the copyright holder's name
   *
   * @param array                                $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();

    $form['copyright_name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Copyright name'),
      '#description' => $this->t('The name of entity to copyright for.'),
      '#default_value' => isset($config['copyright_name']) ? $config['copyright_name'] : '',
    );

    return $form;
  }

  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $values = $form_state->getValues();
    $this->configuration['copyright_name'] = $values['copyright_name'];
  }
}