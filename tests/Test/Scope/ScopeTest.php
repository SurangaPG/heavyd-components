<?php

/**
 * @file
 *  pro verbatim copy of the drupal class with the same name, this prevents having
 *  to boostrap a full drupal instance just for this one class. It also prevents
 *  a dependency on drupal in a workflow package.
 */
namespace workflow\Workflow\Test\Components\Scope;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use surangapg\HeavydComponents\Scope\Scope;

class ScopeTest extends TestCase {

  /**
   * @covers Scope::__construct
   */
  public function testConstruct() {
    $scope = new Scope('/this/is/the/basepath');
    Assert::assertInstanceOf('surangapg\HeavydComponents\Scope\ScopeInterface', $scope);
  }

  /**
   * @covers Scope::getBasePath
   */
  public function testGetBasePath() {
    $scope = new Scope('/this/is/the/basepath');
    Assert::assertEquals('/this/is/the/basepath', $scope->getBasePath());
  }

  /**
   * @covers Scope::getPropertyDir
   */
  public function testGetPropertyDir() {
    $scope = new Scope('/this/is/the/basepath');
    Assert::assertEquals('properties', $scope->getPropertyDir());
    Assert::assertEquals('/this/is/the/basepath/properties', $scope->getPropertyDir(TRUE));

    // Test the case with a trailing slash.
    $scope = new Scope('/this/is/the/basepath/');
    Assert::assertEquals('properties', $scope->getPropertyDir());
    Assert::assertEquals('/this/is/the/basepath/properties', $scope->getPropertyDir(TRUE));
  }

  /**
   * @covers Scope::getKey
   */
  public function testGetKey() {
    $scope = new Scope('this/is/the/basepath');
    Assert::assertEquals('project', $scope->getKey());

    $scope = new Scope('this/is/the/basepath', 'properties', 'sample');
    Assert::assertEquals('sample', $scope->getKey());
  }

  /**
   * @covers Scope::detectProjectScope
   */
  public function testDetectProjectScope() {

    // Check detection from the root of the project.
    $fixtureDir = $this->generateBasePath() . '/project-root';
    $detectedScope = Scope::detectProjectScope($fixtureDir);
    Assert::assertInstanceOf('surangapg\HeavydComponents\Scope\ScopeInterface', $detectedScope);

    // Check detection from a child dir.
    $detectedScope = Scope::detectProjectScope($fixtureDir . '/web/site/');
    Assert::assertInstanceOf('surangapg\HeavydComponents\Scope\ScopeInterface', $detectedScope);

    // Test detection where no valid project scope exists.
    $detectedScope = Scope::detectProjectScope(__DIR__);
    Assert::assertEquals(NULL, $detectedScope);
  }

  /**
   * Get the basepath for the fixtures.
   *
   * @return string
   *   The basepath for the fixtures.
   */
  private function generateBasePath() {
    return dirname(dirname(__DIR__)) . '/fixtures/scope-test';
  }
}