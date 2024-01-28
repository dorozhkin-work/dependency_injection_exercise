<?php

namespace Drupal\dependency_injection_exercise\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\dependency_injection_exercise\PhotosProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'RestOutputBlock' block.
 *
 * @Block(
 *  id = "rest_output_block",
 *  admin_label = @Translation("Rest output block"),
 * )
 */
class RestOutputBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Dependency injection exercise photos provider service.
   *
   * @var \Drupal\dependency_injection_exercise\PhotosProviderInterface
   */
  protected $provider;

  /**
   * Constructs a new RestOutputBlock.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\dependency_injection_exercise\PhotosProviderInterface $provider
   *   Photos provider.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, PhotosProviderInterface $provider) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->provider = $provider;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('dependency_injection_exercise.photos_provider'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
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
    $data = $this->provider->fetch();
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
