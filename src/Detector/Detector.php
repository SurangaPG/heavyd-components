<?php

/**
 * @file
 * Contains a basic helper to detect listed workflow classes.
 */
namespace surangapg\HeavydComponents\Detector;

use Symfony\Component\Yaml\Yaml;

/**
 * Class EnvFile
 *
 * Helper class to set up an env file. These can then be pushed to a server
 * and be used to handle the db connections from there.
 *
 * @package surangapg\HeavydComponents\EnvFile
 */
class Detector implements DetectorInterface {

  /**
   * The type of item to load (corresponds to the key in the info.workflow.yml).
   *
   * @var string
   */
  protected $type;

  /**
   * Vendor dir to scan.
   *
   * @var string
   */
  protected $vendorPath;

  /**
   * All the interface filters to verify.
   *
   * @var array
   */
  protected $interfaceFilters = [];

  /**
   * @inheritdoc
   */
  public function __construct($vendorPath, $type) {
    $this->type = $type;
    $this->vendorPath = $vendorPath;
  }

  /**
   * Find all the info files.
   *
   * @return string[]
   */
  public function findFiles() {

    // @todo fix globbing pattern ...
    $infoFiles = array_merge(
      glob($this->vendorPath . '/info.workflow.yml'),
      glob($this->vendorPath . '/**/info.workflow.yml'),
      glob($this->vendorPath . '/**/**/info.workflow.yml')
    );
    return $infoFiles;
  }

  /**
   * Get a list of fully qualified class names.
   *
   * @return string[]
   *   Array of fully qualified classnames.
   */
  public function detect() {

    $infoFiles = $this->findFiles();

    $foundClassNames = [];
    foreach ($infoFiles as $infoFile) {
      $data = Yaml::parse(file_get_contents($infoFile));

      if (isset($data[$this->type])) {
        foreach ($data[$this->type] as $class) {

          if (!class_exists($class)) {
            continue;
          }

          foreach ($this->interfaceFilters as $filter) {
            if( !in_array($filter, class_implements($class))) {
              continue 2;
            }
          }

          $foundClassNames[$class] = $class;
        }
      }
    }

    return $foundClassNames;
  }

  /**
   * @param string $filter
   *   Fully qualified interface name.
   */
  public function addInterfaceFilter($filter) {
    $this->interfaceFilters[] = $filter;
  }

}