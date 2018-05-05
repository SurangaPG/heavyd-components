<?php

namespace surangapg\HeavydComponents\Scope;

/**
 * Class Scope
 *
 * Scope for an application. Defines a base path, properties dir an key.
 * This is a small abstraction to define where extra data can be found.
 */
class Scope implements ScopeInterface {

  /**
   * Absolute base path for this scope.
   *
   * @var string
   *   The base path for this scope.
   */
  protected $basePath;

  /**
   * Properties dir relative to the base path.
   *
   * @var string
   *   The property dir for this scope, defaults to "properties".
   */
  protected $propertyDir;

  /**
   * Key for this scope. Currently only project and global are supported.
   *
   * @var string
   *   The base path for this scope.
   */
  protected $key;

  /**
   * Scope constructor.
   *
   * @param string $basePath
   *   The base path for this scope.
   * @param string $propertyDir
   *   The property dir for this scope, defaults to "properties".
   * @param string $key
   *   The key for this scope, defaults to "ScopeInterface::PROJECT".
   */
  public function __construct(string $basePath, string $propertyDir = 'properties', $key = ScopeInterface::PROJECT) {
    $this->basePath = $basePath;
    $this->propertyDir = $propertyDir;
    $this->key = $key;
  }

  /**
   * Get the base path.
   *
   * @return string
   *   The base path.
   */
  public function getBasePath() {
    return $this->basePath;
  }

  /**
   * Get the property dir.
   *
   * @param bool $absolute
   *   Should the path be made absolute.
   *
   * @return string
   *   The properties dir.
   */
  public function getPropertyDir($absolute = FALSE) {
    if ($absolute) {
      return rtrim($this->basePath, '/') . '/' . $this->propertyDir;
    }
    else {
      return $this->propertyDir;
    }
  }

  /**
   * The key for the scope.
   *
   * @return string
   *   Get the key.
   */
  public function getKey() {
    return $this->key;
  }

  /**
   * Detects all the information for all the levels in the application.
   *
   * Currently this is project and global. It detects the global application
   * home dir (if it exists and the project application home dir).
   *
   * @param string|NULL $basePath
   *   The base path for the item.
   *
   * @return ScopeInterface[]
   *   Array with the location for the different parts of the application.
   */
  public static function detectActiveScopes(string $basePath = NULL) {

    $scopes = [
      'global' => static::detectGlobalScope(),
      'project' => static::detectProjectScope($basePath),
    ];

    return $scopes;
  }

  /**
   * Detect the location for the global application.
   *
   * @return null|ScopeInterface
   *   The global scope interface.
   */
  public static function detectGlobalScope() {
    $globalBasePath = '';
    exec('cd && pwd', $globalBasePath);

    if (file_exists($globalBasePath[0] . '/.heavyd')) {
      return new Scope($globalBasePath[0] . '/.heavyd', 'properties', ScopeInterface::GLOBAL);
    }
    return NULL;
  }

  /**
   * Define the project path for this command.
   *
   * @param string $basePath
   *   The Basepath to start searching from.
   *
   * @return ScopeInterface|null
   *   The location that was found.
   */
  public static function detectProjectScope($basePath = null) {
    $basePath = isset($basePath) ? $basePath : getcwd();
    $marker = static::findHeavydMarkerInTree($basePath);

    if (!isset($marker)) {
      return $marker;
    }

    return new Scope(dirname($marker));
  }

  /**
   * Find the heavyD marker recursively a the directory tree.
   *
   * @param string $dir
   *   The directory to search in.
   *
   * @return string
   *   The heavyd file detected.
   *
   * @throws \Exception
   *   If no file could be found in the current tree.
   */
  private static function findHeavydMarkerInTree(string $dir) {
    $path = rtrim($dir, '/') . '/.heavyd.yml';
    if (!file_exists($path)) {

      if ($dir == '/') {
        return NULL;
      }

      return static::findHeavydMarkerInTree(dirname($dir));
    }

    return $path;
  }
}
