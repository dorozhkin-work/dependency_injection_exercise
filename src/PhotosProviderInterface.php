<?php

namespace Drupal\dependency_injection_exercise;

/**
 * Interface for photo_provider.
 */
interface PhotosProviderInterface {

  /**
   * Sets photos provider request album.
   *
   * @param int $album
   *   Album index to fetch from.
   */
  public function album(int $album): PhotosProviderInterface;

  /**
   * Loads list of photos from specific album.
   *
   * @param string $request_url
   *   Provider request url (optional)
   *
   * @return array
   *   List of photos
   */
  public function fetch(string $request_url = ''): array;

}
