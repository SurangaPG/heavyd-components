<?php

/**
 * @file
 * Contains a basic helper to help generate an env file.
 */
namespace surangapg\HeavydComponents\Detector;

/**
 * Interface DetectorInterface
 *
 * Provide a uniform way to detect the different info.workflow.yml files in
 * the vendor dirs and get their data.
 *
 * @package surangapg\HeavydComponents\Detector
 */
interface DetectorInterface {

  /**
   * DetectorInterface constructor.
   *
   * @param string $vendorPath
   *   Path to a vendor dir to detect the items from.
   * @param string $type
   *   Type of items to detect.
   */
  public function __construct($vendorPath, $type);

  /**
   * Get a list of fully qualified class names.
   *
   * @return string[]
   *   Array of fully qualified classnames.
   */
  public function detect();

  /**
   * @param string $filter
   *   Fully qualified interface name.
   */
  public function addInterfaceFilter($filter);

}