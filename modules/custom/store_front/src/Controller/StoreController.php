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
use Drupal\Core\Render\Markup;
use Kint;
use Symfony\Component\DependencyInjection\ContainerInterface;

class StoreController extends ControllerBase {

  /** @var  ProductLazyBuilders */
  protected $product_builder;

  /** @var  \Drupal\commerce_product\ProductVariationFieldRendererInterface */
  protected $variation_field_renderer;

  public static function create(ContainerInterface $container) {

    $product_lazy_builder = $container->get('commerce_product.lazy_builders');

    $product_varient_field_renderer = $container->get('commerce_product.variation_field_renderer');

    return new static($product_lazy_builder, $product_varient_field_renderer);
  }

  public function __construct(ProductLazyBuilders $product_lazy_builder, ProductVariationFieldRendererInterface $field_renderer) {
    $this->product_builder = $product_lazy_builder;
    $this->variation_field_renderer = $field_renderer;
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
      $url = $variants[0]->toUrl()->getInternalPath();
      //$this->ksm($product->get('body')->getValue());
      $product_forms[$id . '_openTag']['#markup'] = '<div class="product-list-item">';
      $product_forms[$id . '_title']['#markup'] = "<a href='$url'>" . $product->getTitle() . "</a>";
      $product_forms[$id . '_price'] = $this->variation_field_renderer->renderField('price', $variants[0]);
      $product_forms[$id . '_body']['#markup'] = $product->get('body')->getValue()[0]['value'];
      $product_forms[$id . '_form'] = $this->product_builder->addToCartForm($id, 'default', false);
      $product_forms[$id . '_closeTag']['#markup'] = '</div>';
    }

    //$product_forms['test'] = ['#markup' => 'TEST'];
    //$this->ksm($product_forms);

    return $product_forms;
  }

  public function ksm() {
    $this->kint_require();
    if (\Drupal::currentUser()->hasPermission('access kint')) {
      $args = func_get_args();
      $msg = @Kint::dump($args);
      drupal_set_message(Markup::create($msg));
    }
  }

  public function kint_require() {
    return require_once DRUPAL_ROOT . '/' . drupal_get_path('module', 'kint') . '/kint/Kint.class.php';
  }
}