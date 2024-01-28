<?php

namespace Drupal\dependency_injection_exercise\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\dependency_injection_exercise\PhotosProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the rest output.
 */
class RestOutputController extends ControllerBase {

  /**
   * Dependency injection exercise photos provider service.
   *
   * @var \Drupal\dependency_injection_exercise\PhotosProviderInterface
   */
  protected $provider;

  /**
   * RestOutputController constructor.
   *
   * @param \Drupal\dependency_injection_exercise\PhotosProviderInterface $provider
   *   Photos provider.
   */
  public function __construct(PhotosProviderInterface $provider) {
    $this->provider = $provider;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('dependency_injection_exercise.photos_provider')
    );
  }

  /**
   * Displays the photos.
   *
   * @return array[]
   *   A renderable array representing the photos.
   */
  public function showPhotos(): array {
    // Setup build caching.
    $build = [
      '#cache' => [
        'max-age' => 60,
        'contexts' => [
          'url',
        ],
      ],
    ];

    // Try to obtain the photo data via the external API.
    $data = $this->provider->album(5)->fetch();
    if (empty($data)) {
      $build['error'] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => $this->t('No photos available.'),
      ];
      return $build;
    }

    // Build a listing of photos from the photo data.
    $build['photos'] = array_map(static function ($item) {
      return [
        '#theme' => 'image',
        '#uri' => $item['thumbnailUrl'],
        '#alt' => $item['title'],
        '#title' => $item['title'],
      ];
    }, $data);

    return $build;
  }

}
