<?php

namespace Drupal\dependency_injection_exercise;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Main class for photos_provider.
 */
class PhotosProvider implements PhotosProviderInterface {


  /**
   * The client used to send HTTP requests.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $client;

  /**
   * Config.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $config;

  /**
   * The logger channel.
   *
   * @var \Drupal\Core\Logger\LoggerChannelInterface
   */
  protected $logger;

  /**
   * Provider request url pattern.
   *
   * @var string
   */
  protected $pattern;

  /**
   * Provider request album.
   *
   * @var int
   */
  protected $album;

  /**
   * Provider request method.
   *
   * @var string
   */
  protected $method;

  /**
   * Constructs a PhotosProvider object.
   *
   * @param \GuzzleHttp\ClientInterface $client
   *   Http client.
   * @param \Drupal\Core\Config\ConfigFactory $config_factory
   *   Configuration factory.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger
   *   Logger factory.
   */
  public function __construct(ClientInterface $client, ConfigFactory $config_factory, LoggerChannelFactoryInterface $logger) {
    $this->client = $client;
    $this->config = $config_factory->get('dependency_injection_exercise.settngs');
    $this->logger = $logger->get('dependency_injection_exercise');
    // Setting default provider request method.
    $this->method = 'GET';
    // Setting default provider request album.
    $this->album = random_int(1, 20);
    // Setting default provider request url pattern.
    $this->pattern = $this->config->get('provider_url_pattern');
  }

  /**
   * {@inheritdoc}
   */
  public function album(int $album): PhotosProviderInterface {
    $this->album = $album;
    return $this;
  }

  /**
   * Builds request url.
   */
  protected function buildUrl(): string {
    return sprintf($this->pattern, $this->album);
  }

  /**
   * {@inheritdoc}
   */
  public function fetch(string $request_url = ''): array {
    $data = [];
    $url = ($request_url) ?: $this->buildUrl();
    try {
      $response = $this->client->request($this->method, $url);
      $raw_data = $response->getBody()->getContents();
      $data = Json::decode($raw_data);
    }
    catch (GuzzleException $e) {
      $this->logger->error($e->getMessage());
    }
    return $data;
  }

}
