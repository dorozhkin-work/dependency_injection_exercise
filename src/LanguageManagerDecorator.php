<?php

namespace Drupal\dependency_injection_exercise;

use Drupal\Core\Language\LanguageDefault;
use Drupal\Core\Language\LanguageManager;

/**
 * Dependency Injection Exercise language manager decorator class.
 */
class LanguageManagerDecorator extends LanguageManager {

  /**
   * Original language manager.
   *
   * @var \Drupal\Core\Language\LanguageManager
   */
  protected $originalLanguageManager;

  /**
   * Constructs a LanguageManager object.
   */
  public function __construct(LanguageManager $originalLanguageManager, LanguageDefault $languageDefault) {
    $this->originalLanguageManager = $originalLanguageManager;
    parent::__construct($languageDefault);
  }

  /**
   * Alternative method.
   */
  public function getCurrentLanguageId(): string {
    return $this->originalLanguageManager->getCurrentLanguage()->getId() . '-foo';
  }

}
