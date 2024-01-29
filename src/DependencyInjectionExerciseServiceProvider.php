<?php

namespace Drupal\dependency_injection_exercise;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceModifierInterface;

/**
 * Defines a service provider for the Dependecy injection exercise module.
 */
final class DependencyInjectionExerciseServiceProvider implements ServiceModifierInterface {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container): void {
    $definition = $container->getDefinition('plugin.manager.mail');
    $definition->setClass('Drupal\dependency_injection_exercise\NopeMailManager');
  }

}
