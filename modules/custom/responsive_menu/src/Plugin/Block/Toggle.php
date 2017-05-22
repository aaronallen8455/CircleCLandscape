<?php
/**
 * Created by PhpStorm.
 * User: Aaron Allen
 * Date: 5/22/2017
 * Time: 1:03 AM
 */

namespace Drupal\responsive_menu\Plugin\Block;


use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;

/**
 * Provides a menu toggle element.
 *
 * @Block(
 *   id = "menu_toggle",
 *   admin_label = @Translation("Responsive menu toggle element"),
 * )
 */
class Toggle extends BlockBase implements BlockPluginInterface {

  public function build() {
    return array (
      '#markup' => <<<HTML
<span class="action nav-toggle" data-action="toggle-nav">
    <span>Menu</span>
</span>
HTML
    );
  }
}