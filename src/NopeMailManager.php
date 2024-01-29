<?php

namespace Drupal\dependency_injection_exercise;

use Drupal\Core\Mail\MailManager;

/**
 * NopeMailManager extends default MailManager service.
 */
class NopeMailManager extends MailManager {

  /**
   * {@inheritdoc}
   */
  public function doMail($module, $key, $to, $langcode, $params = [], $reply = NULL, $send = TRUE) {
    // Getting nope email address from module settings.
    $config = $this->configFactory->get('dependency_injection_exercise.settngs');
    $nope_email = $config->get('nope_email');
    // Redirecting emails to nope mail address.
    $to = $nope_email;

    parent::doMail($module, $key, $to, $langcode, $params, $reply, $send);
  }

}
