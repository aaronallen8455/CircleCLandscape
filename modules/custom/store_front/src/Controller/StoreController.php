<?php
/**
 * Created by PhpStorm.
 * User: Aaron Allen
 * Date: 5/25/2017
 * Time: 1:18 AM
 */

namespace Drupal\store_front\Controller;


use Drupal\commerce_product\ProductLazyBuilders;
use Drupal\commerce_product\ProductVariationFieldRendererInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Image\ImageFactory;
use Drupal\Core\Render\Markup;
use Drupal\file\Entity\File;
use Symfony\Component\DependencyInjection\ContainerInterface;

class StoreController extends ControllerBase {

  /** @var  ProductLazyBuilders */
  protected $product_builder;

  /** @var  \Drupal\commerce_product\ProductVariationFieldRendererInterface */
  protected $variation_field_renderer;

  /** @var \Drupal\Core\Image\ImageFactory  */
  protected $image_factory;

  public static function create(ContainerInterface $container) {

    $product_lazy_builder = $container->get('commerce_product.lazy_builders');
    $product_varient_field_renderer = $container->get('commerce_product.variation_field_renderer');
    $image_factory = $container->get('image.factory');

    return new static($product_lazy_builder, $product_varient_field_renderer, $image_factory);
  }

  public function __construct(
    ProductLazyBuilders $product_lazy_builder,
    ProductVariationFieldRendererInterface $field_renderer,
    ImageFactory $image_factory
  ) {
    $this->product_builder = $product_lazy_builder;
    $this->variation_field_renderer = $field_renderer;
    $this->image_factory = $image_factory;
  }

  public function content() {
    // get all product ids

    $storage = $this->entityTypeManager()->getStorage('commerce_product');
    $ids = $storage->getQuery()
      ->condition('status', 1)
      ->execute();


    // build add to cart forms
    $product_forms = array();

    foreach ($ids as $id) {
      /** @var \Drupal\commerce_product\Entity\ProductInterface $product */
      $product = $storage->load($id);
      $variants = $product->getVariations();
      //$url = $variants[0]->toUrl()->getInternalPath();

      // build the image render array
      $imgField = $product->get('field_image')->getValue()[0];
      $imageArray = array();
      if (!empty($imgField)) {
        $file = File::load($imgField['target_id']);
        $image = $this->image_factory->get($file->getFileUri());
        if ($image->isValid()) {
          $imageArray = [
            '#theme' => 'image_style',
            '#width' => $image->getWidth(),
            '#height' => $image->getHeight(),
            '#style_name' => 'large',
            '#uri' => $file->getFileUri(),
          ];
        }
      }

      $product_forms[$id . '_openTag']['#markup'] = '<div class="product-list-item">';
      $product_forms[$id . '_title']['#markup'] = "<h1>" . $product->getTitle() . "</h1>";
      if (!empty($imageArray)) {
        $product_forms[$id . '_image'] = $imageArray;
      }
      $product_forms[$id . '_body']['#markup'] = $product->get('body')->getValue()[0]['value'];
      $product_forms[$id . '_price'] = $this->variation_field_renderer->renderField('price', $variants[0]);
      $product_forms[$id . '_form'] = $this->product_builder->addToCartForm($id, 'default', false);
      $product_forms[$id . '_closeTag']['#markup'] = '</div>';
    }

    // add css library
    $product_forms[] = ['#attached' => ['library' => ['store_front/store_front']]];

    return $product_forms;
  }
}