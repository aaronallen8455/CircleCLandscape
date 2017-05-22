<?php
/**
 * Created by PhpStorm.
 * User: Aaron Allen
 * Date: 5/20/2017
 * Time: 9:09 PM
 */

namespace Drupal\responsive_menu\Plugin\Block;


use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Render\Markup;
use Kint;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a responsive menu block.
 *
 * @Block(
 *   id = "responsive_menu",
 *   admin_label = @Translation("Responsive menu block"),
 * )
 */
class ResponsiveMenu extends BlockBase implements BlockPluginInterface, ContainerFactoryPluginInterface {

  /** @var  \Drupal\Core\Menu\MenuLinkTreeInterface */
  protected $menuTree;

  /** @var  \Drupal\Core\Path\AliasManagerInterface */
  protected $aliasManager;

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    $menu_tree,
    $alias_manager
  ) {
    $this->menuTree = $menu_tree;
    $this->aliasManager = $alias_manager;

    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * Used to get the menu link tree service
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   * @param array                                                     $configuration
   * @param string                                                    $plugin_id
   * @param mixed                                                     $plugin_definition
   *
   * @return static
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ) {
    $menu_tree = $container->get('menu.link_tree');
    $alias_manager = $container->get('path.alias_manager');

    return new static($configuration, $plugin_id, $plugin_definition, $menu_tree, $alias_manager);
  }

  public function build() {
    $menu = $this->getMenuItems();

    return array(
      '#theme' => 'responsive_menu',
      '#menu_items' => $menu,
      '#attached' => [
        'library' => [
          'responsive_menu/menu'
        ]
      ]
    );
  }

  /**
   * Get the menu items
   *
   * @return array
   */
  protected function getMenuItems() {

    $tree = $this->menuTree
      ->load('main', $this->menuTree->getCurrentRouteMenuTreeParameters('main'));

    $manipulators = array(
      // Only show links that are accessible for the current user.
      array('callable' => 'menu.default_tree_manipulators:checkAccess'),
      array('callable' => 'menu.default_tree_manipulators:generateIndexAndSort'),
    );
    $tree = $this->menuTree->transform($tree, $manipulators);

    // Build a renderable array from the tree.
    $menuTmp = $this->menuTree->build($tree);

    $menu = array();
    // build array of title => paths
    foreach ($menuTmp['#items'] as $item) {
      $menu[$item['title']] =
          $item['url']->getRouteName() == 'front'
            ? $item['url']->getRouteName()
            : $this->aliasManager->getAliasByPath('/' . $item['url']->getInternalPath());
    }

    return $menu;
  }
}