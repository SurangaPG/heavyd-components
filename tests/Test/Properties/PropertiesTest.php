<?php

/**
 * @file
 *  pro verbatim copy of the drupal class with the same name, this prevents having
 *  to boostrap a full drupal instance just for this one class. It also prevents
 *  a dependency on drupal in a workflow package.
 */
namespace workflow\Workflow\Test\Components\Properties;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use surangapg\HeavydComponents\Properties\Properties;
use surangapg\HeavydComponents\Scope\Scope;
use surangapg\HeavydComponents\Scope\ScopeInterface;

class PropertiesTest extends TestCase {

  /**
   * Test the creation of a set of properties.
   *
   * @covers Properties::__construct
   */
  function testCreate() {

    // Check condition without an additional subpath.
    $properties = new Properties();
    Assert::assertInstanceOf('surangapg\HeavydComponents\Properties\PropertiesInterface', $properties);
  }

  /**
   * Test the getting of the all the items in a data set.
   *
   * @covers Properties::get
   */
  function testGetWithoutParameter() {
    $properties = new Properties();
    $properties->addScope($this->generateDummyProjectScope());

    $data = $properties->get();

    Assert::assertArrayHasKey('project', $data);
    Assert::assertEquals(3, count($data['project']));
  }

  /**
   * Test the getting of a specific group of items in a data set.
   *
   * @covers Properties::get
   */
  function testGetWithParameter() {
    $properties = new Properties();
    $properties->addScope($this->generateDummyProjectScope());
    $data = $properties->get('project');
    Assert::assertArrayHasKey('name', $data);
    Assert::assertArrayHasKey('version', $data);
    Assert::assertEquals('d8', $data['version']);
  }

  /**
   * Assert the getting of a "combined" dataset. E.g both project and global.
   *
   * @covers Properties::get
   */
  function testGetCombined() {
    $properties = new Properties();
    $properties->addScope($this->generateDummyProjectScope());
    $properties->addScope($this->generateDummyGlobalScope());
    $data = $properties->get('project');
    Assert::assertArrayHasKey('name', $data);
    Assert::assertArrayHasKey('version', $data);
    Assert::assertEquals('d8', $data['version']);

    Assert::assertEquals('global', $data['nested']['variable']['label']);
    Assert::assertEquals('project', $data['nested']['variable']['data']);
  }

  /**
   * @covers Properties::refreshProperties
   */
  function testRefreshProperties() {
    $properties = new Properties();
    $properties->addScope($this->generateDummyProjectScope(), FALSE);

    $data = $properties->get();
    Assert::assertArrayNotHasKey('project', $data);

    $properties->refreshProperties();
    $data = $properties->get();
    Assert::assertArrayHasKey('project', $data);
    Assert::assertEquals(3, count($data['project']));
  }

  /**
   * @covers Properties::getBasePath
   */
  function testGetBasePath() {
    $properties = new Properties();
    $dummyProject = $this->generateDummyProjectScope();
    $dummyGlobal = $this->generateDummyGlobalScope();

    $properties->addScope($dummyProject);
    $properties->addScope($dummyGlobal);

    Assert::assertEquals($this->generateBasePath(), $properties->getBasePath());
    Assert::assertEquals($this->generateBasePath(), $properties->getBasePath(ScopeInterface::PROJECT));
    Assert::assertEquals($this->generateBasePath(), $properties->getBasePath(ScopeInterface::GLOBAL));
  }

  /**
   * @covers Properties::getBasePath
   */
  function testGetPropertiesPath() {
    $properties = new Properties();
    $dummyProject = $this->generateDummyProjectScope();
    $dummyGlobal = $this->generateDummyGlobalScope();

    $properties->addScope($dummyProject);
    $properties->addScope($dummyGlobal);

    Assert::assertEquals('project-scope', $properties->getPropertiesPath());
    Assert::assertEquals('project-scope', $properties->getPropertiesPath(FALSE, ScopeInterface::PROJECT));
    Assert::assertEquals($this->generateBasePath() . '/project-scope', $properties->getPropertiesPath(TRUE, ScopeInterface::PROJECT));

    Assert::assertEquals('global-scope', $properties->getPropertiesPath(FALSE, ScopeInterface::GLOBAL));
    Assert::assertEquals($this->generateBasePath() . '/global-scope', $properties->getPropertiesPath(TRUE, ScopeInterface::GLOBAL));
  }

  /**
   * @covers Properties::getScope
   * @covers Properties::addScope
   */
  function testGetScope() {
    $properties = new Properties();
    $dummyProject = $this->generateDummyProjectScope();
    $dummyGlobal = $this->generateDummyGlobalScope();

    $properties->addScope($dummyProject);
    $properties->addScope($dummyGlobal);

    Assert::assertEquals($dummyProject, $properties->getScope());
    Assert::assertEquals($dummyProject, $properties->getScope(ScopeInterface::PROJECT));
    Assert::assertEquals($dummyGlobal, $properties->getScope(ScopeInterface::GLOBAL));
  }

  /**
   * @covers Properties::getScopes
   * @covers Properties::addScope
   */
  function testGetScopes() {
    $properties = new Properties();
    $dummyProject = $this->generateDummyProjectScope();
    $dummyGlobal = $this->generateDummyGlobalScope();

    $properties->addScope($dummyProject);
    $properties->addScope($dummyGlobal);

    Assert::assertEquals($dummyProject, $properties->getScopes()[ScopeInterface::PROJECT]);
    Assert::assertEquals($dummyGlobal, $properties->getScopes()[ScopeInterface::GLOBAL]);

    Assert::assertEquals(2, count($properties->getScopes()));
  }

  /**
   * Test the refreshing of the properties.
   *
   * @covers Properties::setScopes
   */
  public function testSetScopes() {
    $properties = new Properties();

    $scopes = [
      'global' => $this->generateDummyGlobalScope(),
      'project' => $this->generateDummyGlobalScope(),
    ];

    $properties->setScopes($scopes);
    Assert::assertEquals($scopes, $properties->getScopes());

    // Checks that no data has been preloaded.
    $data = $properties->get();
    Assert::assertArrayHasKey('project', $data);
    Assert::assertEquals(3, count($data['project']));
  }

  /**
   * Test the refreshing of the properties.
   *
   * @covers Properties::setScopes
   */
  public function testSetScopesWithoutAutoload() {
    $properties = new Properties();

    $scopes = [
      'global' => $this->generateDummyGlobalScope(),
      'project' => $this->generateDummyGlobalScope(),
    ];

    $properties->setScopes($scopes, FALSE);
    Assert::assertEquals($scopes, $properties->getScopes());

    // Checks that no data has been preloaded.
    $data = $properties->get();
    Assert::assertArrayNotHasKey('project', $data);
  }

  /**
   * The project scope for testing.
   *
   * @return \surangapg\HeavydComponents\Scope\Scope
   *   Project scope with some data.
   */
  private function generateDummyProjectScope() {
    return new Scope($this->generateBasePath(), 'project-scope');
  }

  /**
   * The global scope for testing.
   *
   * @return \surangapg\HeavydComponents\Scope\Scope
   *   Global scope.
   */
  private function generateDummyGlobalScope() {
    return new Scope($this->generateBasePath(), 'global-scope', ScopeInterface::GLOBAL);
  }

  /**
   * Get the basepath for the fixtures.
   *
   * @return string
   *   The basepath for the fixtures.
   */
  private function generateBasePath() {
    return dirname(dirname(__DIR__)) . '/fixtures/properties-test';
  }
}