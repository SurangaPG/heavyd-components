<?php
/**
 * @file Properties interface.
 */

namespace surangapg\HeavydComponents\Properties;

/**
 * Interface PropertiesInterface
 *
 * An interface aimed at the loading in of the various properties whatever their
 * source and making them accessible for different purposes.
 *
 * @package surangapg\HeavydComponents\Properties
 */
interface PropertiesInterface {

  /**
   * Create an instance of the properties handler for the project.
   *
   * @param string $basePath
   *   The basepath for the loading of the properties.
   * @param string $propertiesPath
   *   The properties subpath, relative to the base path.
   * @param bool $autoload
   *   Should the project files be autoloaded in.
   *
   * @return \surangapg\HeavydComponents\Properties\PropertiesInterface
   *   Fully formed and loaded properties handler.
   *
   */
  public static function create($basePath, $propertiesPath = 'properties', $autoload = true);

  /**
   * Get all the loaded properties for this project keyed by file.
   *
   * @param null|string $group
   *   The group (filename) where the property was loaded from.
   *
   * @return array
   *   Array of all the properties.
   */
  public function get($group = null);

  /**
   * Gets the base path for the project.
   *
   * @return string
   *   The base path for the project.
   */
  public function getBasePath();

  /**
   * Gets the properties path for the project.
   *
   * @return string
   *   The properties path for the project.
   */
  public function getPropertiesPath();

}