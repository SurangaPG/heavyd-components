<?php

namespace surangapg\HeavydComponents\Scope;

interface ScopeInterface {

  /**
   * The project scope (for a full project).
   */
  const PROJECT = 'project';

  /**
   * The global scope (from the home).
   */
  const GLOBAL = 'global';

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
  public function __construct(string $basePath, string $propertyDir = 'properties', $key = ScopeInterface::PROJECT);

  /**
   * Get the base path.
   *
   * @return string
   *   The base path.
   */
  public function getBasePath();
  /**
   * Get the property dir.
   *
   * @param bool $absolute
   *   Should the path be made absolute.
   *
   * @return string
   *   The properties dir.
   */
  public function getPropertyDir($absolute = FALSE);

  /**
   * The key for the scope.
   *
   * @return string
   *   Get the key.
   */
  public function getKey();

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
   *   Array with the location for the different scopes.
   */
  public static function detectActiveScopes(string $basePath = NULL);
}
