<?php

namespace Drupal\dependency_injection_exercise;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Dependency injection exercise breadcrumb builder class.
 */
final class DependencyInjectionExerciseBreadcrumbBuilder implements BreadcrumbBuilderInterface {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match): bool {
    return $route_match->getRouteName() === 'dependency_injection_exercise.rest_output_controller_photos';
  }

  /**
   * {@inheritdoc}
   */
  public function build(RouteMatchInterface $route_match): Breadcrumb {
    $breadcrumb = new Breadcrumb();

    $links[] = Link::createFromRoute($this->t('Home'), '<front>');
    $links[] = Link::createFromRoute($this->t('Dropsolid'), '<none>');
    $links[] = Link::createFromRoute($this->t('Example'), '<none>');
    $links[] = Link::createFromRoute($this->t('Photos'), '<none>');

    $breadcrumb->addCacheContexts(['route']);

    return $breadcrumb->setLinks($links);
  }

}
