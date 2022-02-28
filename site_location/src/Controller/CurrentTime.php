<?php

namespace Drupal\site_location\Controller;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Datetime\DateFormatter;

/**
 * Class CurrentTime return the current time with timestamp.
 */
class CurrentTime {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The datetime.time service.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $timeService;

  /**
   * Date Formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * Injecting the dependency of the config factory to this object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   * @param \Drupal\Component\Datetime\TimeInterface $time_service
   *   Custom service to get the time.
   * @param \Drupal\Core\Datetime\DateFormatter $date_formatter
   *   Custom service to get the time.
   */
  public function __construct(ConfigFactoryInterface $config_factory, TimeInterface $time_service, DateFormatter $date_formatter) {
    $this->configFactory = $config_factory;
    $this->timeService = $time_service;
    $this->dateFormatter = $date_formatter;
  }

  /**
   * Function to get the current time based on the timezone.
   */
  public function currentTimeWithTimezone() {
    $config = $this->configFactory->get('site_location.settings');
    $config_timezone = $config->get('timezone');
    $current_time_stamp = $this->timeService->getCurrentTime();
    $current_time = $this->dateFormatter->format(
          $current_time_stamp, 'custom', 'jS F Y - h:i A', $config_timezone
      );
    return $current_time;
  }

}
