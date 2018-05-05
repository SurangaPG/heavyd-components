<?php
/**
 * @file Config baseclass.
 */

namespace surangapg\HeavydComponents\Properties;

use surangapg\HeavydComponents\Scope\ScopeInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

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
   * Get all the scopes for an item.
   *
   * @var array
   *   All the different scopes to be loaded. In the form key => basePath.
   */
  protected $scopes = [];

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
   * Ensures all the items in the property set are fully up to date.
   */
  public function refreshProperties() {

    // Flush the current properties.
    $this->properties = [];

    // Start by loading the global scope.
    $globalScope = $this->getScope(ScopeInterface::GLOBAL);
    if (isset($globalScope)) {
      $this->extractDataFromScope($globalScope);
    }

    // Merge over the project scope if applicable. Adding/overwriting the
    // global scope where relevant.
    $projectScope = $this->getScope(ScopeInterface::PROJECT);
    if (isset($projectScope)) {
      $this->extractDataFromScope($projectScope);
    }
  }

  /**
   * Gets the base path for the project.
   *
   * @return string
   *   The base path for the project.
   */
  public function getBasePath(string $scope = ScopeInterface::PROJECT) {
    return $this->getScope($scope)->getBasePath();
  }

  /**
   * Gets the properties path for the project.
   *
   * @param bool $absolute
   *   Should the path be absolute.
   * @param string $scope
   *   Id of the scope to use.
   *
   * @return string
   *   The properties path for the project.
   */
  public function getPropertiesPath(bool $absolute = FALSE, string $scope = ScopeInterface::PROJECT) {
    return $this->getScope($scope)->getPropertyDir($absolute);
  }

  /**
   * Get all the information about the loaded scopes.
   *
   * @param string $scope
   *   Id for the scope to get.
   *
   * @return ScopeInterface|NULL
   *   Array with all the current scope data.
   */
  public function getScope(string $scope = ScopeInterface::PROJECT) {
    return isset($this->scopes[$scope]) ? $this->scopes[$scope] : NULL;
  }

  /**
   * Get all the information about the loaded scopes.
   *
   * @return ScopeInterface[]
   *   Array with all the current scope data.
   */
  public function getScopes() {
    return $this->scopes;
  }

  /**
   * Add a scope.
   *
   * @param \surangapg\HeavydComponents\Scope\ScopeInterface $scope
   *   Scope interface to add.
   * @param bool $autoload
   *   Should the property files be autoloaded in.
   *
   * @throws \Exception
   *   When an unexpected key is added.
   */
  public function addScope(ScopeInterface $scope, bool $autoload = true) {

    if (!in_array($scope->getKey(), ['project', 'global'])) {
      throw new \Exception('only "project" and "global" scope key are currently supported.');
    }

    $this->scopes[$scope->getKey()] = $scope;

    if ($autoload) {
      $this->refreshProperties();
    }
  }

  /**
   * Set the scope for the data.
   *
   * @param ScopeInterface[] $scopes
   *   Array with all the scope data keyed in the form name => basepath.
   *   Currently only the "global" and the "project" scope are allowed.
   * @param bool $autoload
   *   Should the property files be autoloaded in.
   *
   * @throws \Exception
   *   When an unexpected key is added.
   */
  public function setScopes(array $scopes, bool $autoload = true) {

    // Check for unsupported scope levels.
    $addedKeys = array_keys($scopes);
    $detector = array_diff($addedKeys, ['global', 'project']);
    if (!empty($detector)) {
      throw new \Exception(sprintf('only "project" and "global" scope key are currently supported. Also found: %s', implode(', ', $detector)));
    }

    $this->scopes = $scopes;

    if ($autoload) {
      $this->refreshProperties();
    }
  }

  /**
   * Recursively merge two config arrays with a specific behavior:
   *
   * 1. scalar values are overridden
   * 2. array values are extended uniquely if all keys are numeric
   * 3. all other array values are merged
   *
   * @param array $original
   * @param array $override
   * @return array
   * @see http://stackoverflow.com/a/36366886/6812729
   */
  protected function arrayMergeAlternate(array $original, array $override)
  {
    foreach ($override as $key => $value) {
      if (isset($original[$key])) {
        if (!is_array($original[$key])) {
          if (is_numeric($key)) {
            // Append scalar value
            $original[] = $value;
          } else {
            // Override scalar value
            $original[$key] = $value;
          }
        } elseif (array_keys($original[$key]) === range(0, count($original[$key]) - 1)) {
          // Uniquely append to array with numeric keys
          $original[$key] = array_unique(array_merge($original[$key], $value));
        } else {
          // Merge all other arrays
          $original[$key] = $this->arrayMergeAlternate($original[$key], $value);
        }
      } else {
        // Simply add new key/value
        $original[$key] = $value;
      }
    }

    return $original;
  }

  /**
   * Gets all the data from a scope and adds it to the property set.
   *
   * @param \surangapg\HeavydComponents\Scope\ScopeInterface $scope
   *   Scope to extract the data from.
   */
  protected function extractDataFromScope(ScopeInterface $scope) {
    $dataFiles = glob($scope->getPropertyDir(TRUE) . '/*.yml');
    foreach ($dataFiles as $file) {
      $data = Yaml::parse(file_get_contents($file));
      $data = isset($data) ? $data : [];
      $dataKey = str_replace('.yml', '', basename($file));

      if (isset($this->properties[$dataKey])) {
        $this->properties[$dataKey] = $this->arrayMergeAlternate($this->properties[$dataKey], $data);
      }
      else {
        $this->properties[$dataKey] = $data;
      }
    }
  }
}