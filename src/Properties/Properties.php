<?php
/**
 * @file Config baseclass.
 */

namespace surangapg\HeavydComponents\Properties;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use surangapg\HeavydComponents\Exception\MissingPropertyException;

/**
 * Class Properties
 *
 * Basic implementation that globs a number of folders and reads then into
 * active configuration. By default these are the files from the /properties
 * dir.
 */
class Properties implements PropertiesInterface {

  /**
   * All the properties for this project. Loaded from the properties directory.
   *
   * @var array
   */
  protected $properties = [];

  /**
   * The base path for the project.
   *
   * Contains the docroot for the entire project.
   *
   * @var string
   */
  protected $basePath = '';

  /**
   * The properties path for the project.
   *
   * This contains all the properties.yml files. Which are normally compacted by
   * phing.
   *
   * @var string
   */
  protected $propertiesPath = '';

  /**
   * @inheritdoc
   */
  public static function create($basePath, $propertiesPath = 'properties', $autoload = true) {

    $properties = new self($basePath, $propertiesPath);

    if ($autoload) {
      $properties->loadProjectProperties();
    }

    return $properties;
  }

  /**
   * @inheritdoc
   */
  public function get($group = null) {

    $properties = [];

    if (isset($group) && isset($this->properties[$group])) {
      $properties = $this->properties[$group];
    }
    elseif (!isset($group)) {
      $properties = $this->properties;
    }

    return $properties;
  }

  /**
   * Properties constructor.
   *
   * @param string $basePath
   *   The basepath for the installation or null to autodetect it.
   * @param string $propertiesPath
   *   The $properties path for the project properties files.
   */
  protected function __construct($basePath, $propertiesPath) {
    $this->setBasePath($basePath);
    $this->setPropertiesPath($propertiesPath);
  }

  /**
   * Loads in all the project property yaml files.
   */
  public function loadProjectProperties() {

    $fullPath = $this->getBasePath() . '/' . $this->getPropertiesPath();

    $dataFiles = glob($fullPath . '/*.yml');

    foreach ($dataFiles as $file) {
      $data = Yaml::parse(file_get_contents($file));

      $data = isset($data) ? $data : [];

      $dataKey = str_replace('.yml', '', basename($file));

      $this->properties[$dataKey] = $data;
    }
  }

  /**
   * All the loaded properties for this item.
   *
   * @param array $loadedProperties
   *   Array of data to set.
   */
  protected function setProperties($loadedProperties) {
    $this->properties = $loadedProperties;
  }

  /**
   * Gets the base path for the project.
   *
   * @return string
   *   The base path for the project.
   */
  public function getBasePath() {
    return $this->basePath;
  }

  /**
   * Gets the properties path for the project.
   *
   * @return string
   *   The properties path for the project.
   */
  public function getPropertiesPath() {
    return $this->propertiesPath;
  }

  /**
   * Base path for the project.
   *
   * @param $basePath
   *   Base path for the project.
   */
  protected function setBasePath($basePath) {
    $this->basePath = rtrim($basePath, '/');
  }

  /**
   * Properties path for the project.
   *
   * @param $propertiesPath
   *   Properties path for the project.
   */
  protected function setPropertiesPath($propertiesPath) {
    $this->propertiesPath = trim($propertiesPath, '/');
  }
}