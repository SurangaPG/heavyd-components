<?php
/**
 * @file Properties interface.
 */

namespace surangapg\HeavydComponents\Properties;

use surangapg\HeavydComponents\Scope\ScopeInterface;

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
   * Loads in all the property yaml files.
   */
  public function refreshProperties();

  /**
   * Gets the base path for the project.
   *
   * @return string
   *   The base path for the project.
   */
  public function getBasePath(string $scope = ScopeInterface::PROJECT);
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
  public function getPropertiesPath(bool $absolute = FALSE, string $scope = ScopeInterface::PROJECT);

  /**
   * Get all the information about the loaded scopes.
   *
   * @param string $scope
   *   Id for the scope to get.
   *
   * @return ScopeInterface|NULL
   *   Array with all the current scope data.
   */
  public function getScope(string $scope = ScopeInterface::PROJECT);

  /**
   * Get all the information about the loaded scopes.
   *
   * @return ScopeInterface[]
   *   Array with all the current scope data.
   */
  public function getScopes();

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
  public function addScope(ScopeInterface $scope, bool $autoload = true);

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
  public function setScopes(array $scopes, bool $autoload = true);

}