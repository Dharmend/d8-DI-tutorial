<?php

namespace Drupal\site_location\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\site_location\Controller\CurrentTime;

/**
 * Provides a 'location' block.
 *
 * @Block(
 *   id = "Current_time",
 *   admin_label = @Translation("Current Time (Timezone)"),
 *   category = @Translation("Current Time (Timezone)")
 * )
 */
class SiteLocation extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The Current Time Service.
   *
   * @var \Drupal\site_location\Controller\CurrentTime
   */
  protected $currentTime;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory, CurrentTime $current_time) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->configFactory = $config_factory;
    $this->currentTime = $current_time;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
          $configuration,
          $plugin_id,
          $plugin_definition,
          $container->get('config.factory'),
          $container->get('site_location.timezone'),
        );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Get the timezone Values.
    $config = $this->configFactory->get('site_location.settings');
    $current_time = $this->currentTime->currentTimeWithTimezone();
    $time_data = [
      'Country' => $config->get('country'),
      'City' => $config->get('city'),
      'Time' => $current_time,
    ];
    return [
      '#theme' => 'location_data',
      '#time_data' => $time_data,
      '#cache' => [
        'tags' => ['config:site_location.settings'],
      ],
      '#title' => $this->t('Current Time Based on the Config'),
    ];

  }

}
