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

class PropertiesTest extends TestCase {

  /**
   * Test the creation of a set of properties.
   *
   * @covers Properties::create
   */
  function testCreate() {

    $basePath = $this->generateBasePath();

    // Check condition without an additional subpath.
    $properties = Properties::create($basePath);
    Assert::assertInstanceOf('surangapg\HeavydComponents\Properties\PropertiesInterface', $properties);

    // Check condition with an additional subpath.
    $properties = Properties::create($basePath, 'alternative');
    Assert::assertInstanceOf('surangapg\HeavydComponents\Properties\PropertiesInterface', $properties);
  }

  /**
   * Test the getting of the all the items in a data set.
   *
   * @covers Properties::get
   */
  function testGetWithoutParameter() {
    $properties = Properties::create($this->generateBasePath());
    $properties = $properties->get();
    Assert::assertArrayHasKey('project', $properties);
    Assert::assertEquals(2, count($properties['project']));
  }

  /**
   * Test the getting of a specific group of items in a data set.
   *
   * @covers Properties::get
   */
  function testGetWithParameter() {
    $properties = Properties::create($this->generateBasePath());
    $properties = $properties->get('project');
    Assert::assertArrayHasKey('name', $properties);
    Assert::assertArrayHasKey('version', $properties);
  }

  /**
   * Test the loading of an alternate location.
   *
   * @covers Properties::create
   */
  function testCreateFromAlternatePath() {
    $basePath = $this->generateBasePath();

    // Check condition without an additional subpath.
    $properties = Properties::create($basePath);
    Assert::assertEquals('Example', $properties->get('project')['name']);

    // Check condition with an additional subpath.
    $properties = Properties::create($basePath, 'alternative');
    Assert::assertEquals('Alternate', $properties->get('project')['name']);
  }

  /**
   * Test the lazy loading of project properties.
   *
   * @covers Properties::loadProjectProperties
   */
  function testLoadProjectProperties() {
    // Generate a new item without properties preloaded. This should be empty.
    $properties = Properties::create($this->generateBasePath(), 'properties', FALSE);
    Assert::assertEmpty($properties->get());

    // Load in all the items.
    $properties->loadProjectProperties();
    Assert::assertNotEmpty($properties->get());
  }

  /**
   * Test the getting and setting of the basePath.
   *
   * @covers Properties::getBasePath
   * @covers Properties::setBasePath
   */
  function testGetSetBasePath() {
    $basePath = $this->generateBasePath();

    // Generate a new item without properties preloaded. This should be empty.
    $properties = Properties::create($basePath, 'properties', FALSE);
    Assert::assertEquals($basePath, $properties->getBasePath());

    // Check setting of an item with a trailing slash.
    $properties = Properties::create($basePath . '/', 'properties', FALSE);
    Assert::assertEquals($basePath, $properties->getBasePath());
  }

  /**
   * Test the getting and setting of the basePath.
   *
   * @covers Properties::getPropertiesPath
   * @covers Properties::setPropertiesPath
   */
  function testGetSetPropertiesPath() {
    $basePath = $this->generateBasePath();

    // Generate a new item without properties preloaded. This should be empty.
    $properties = Properties::create($basePath, 'properties', FALSE);
    Assert::assertEquals('properties', $properties->getPropertiesPath());

    // Check setting of an item with a trailing slash.
    $properties = Properties::create($basePath, 'properties/', FALSE);
    Assert::assertEquals('properties', $properties->getPropertiesPath());

    // Check setting of an item with a leading slash.
    $properties = Properties::create($basePath, '/properties', FALSE);
    Assert::assertEquals('properties', $properties->getPropertiesPath());

    // Check setting of an item with both a leading/trailing slash.
    $properties = Properties::create($basePath, '/properties/', FALSE);
    Assert::assertEquals('properties', $properties->getPropertiesPath());
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