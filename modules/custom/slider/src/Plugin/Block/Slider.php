<?php
/**
 * Created by PhpStorm.
 * User: Aaron Allen
 * Date: 5/16/2017
 * Time: 10:42 PM
 */

namespace Drupal\slider\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\image\Entity\ImageStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Provides a Jssor slider Block.
 *
 * @Block(
 *   id = "slider_block",
 *   admin_label = @Translation("Jssor slider block"),
 * )
 */
class Slider extends BlockBase implements BlockPluginInterface, ContainerFactoryPluginInterface {

  protected $queryFactory;

  protected $manager;

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    $queryFactory,
    $manager
  ) {
    $this->queryFactory = $queryFactory;
    $this->manager = $manager;

    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ) {
    $queryFactory = $container->get('entity.query');
    $manager = $container->get('entity_type.manager');

    return new static($configuration, $plugin_id, $plugin_definition, $queryFactory, $manager);
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // get all image nodes
    $query = $this->queryFactory->get('node');
    $query
      ->condition('status', 1)
      ->condition('type', 'image');
    $ids = $query->execute();

    $storage = $this->manager->getStorage('node');
    $entities = $storage->loadMultiple($ids);

    $images = [];

    // get the image urls
    foreach ($entities as $id => $entity) {
      $images[] = file_create_url($entity->field_image->entity->getFileUri());
    }

    // send the urls to the template
    return array(
      '#theme' => 'slider',
      '#images' => $images,
      '#attached' => [
        'library' => [
          'slider/slider'
        ]
      ]
    );
  }
}