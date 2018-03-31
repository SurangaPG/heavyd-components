<?php
/**
 * Test the detector class.
 */
namespace workflow\Workflow\Test\Components\Detector;

use PHPUnit\Framework\TestCase;
use surangapg\HeavydComponents\Detector\Detector;

class DetectorTest extends TestCase {

  /**
   * @covers Detector::findFiles()
   */
  public function testFindFiles() {
    $detector = new Detector($this->provideFixtureDir() . '/find-file-test', 'githook');
    $files = $detector->findFiles();

    $this->assertEquals(3, count($files), 'Should have detected 3 files.');
  }

  /**
   * @covers Detector::findFiles()
   */
  public function testListClasses() {
    $detector = new Detector($this->provideFixtureDir() . '/list-classes-test', 'githook');
    $classes = $detector->detect();

    $this->assertEquals(1, count($classes), 'Should have detected 1 class name.');
  }

  /**
   * @covers Detector::addFilter()
   */
  public function testListClassesFiltered() {
    $detector = new Detector($this->provideFixtureDir() . '/list-classes-test', 'githook');
    $detector->addInterfaceFilter('surangapg\HeavydComponents\Properties\PropertiesInterface');
    $classes = $detector->detect();

    $this->assertEquals(1, count($classes), 'Should have detected 1 class name.');
  }

  /**
   * Get the basepath for the fixtures.
   *
   * @return string
   *   The basepath for the fixtures.
   */
  private function provideFixtureDir() {
    return dirname(dirname(__DIR__)) . '/fixtures/detector-test';
  }
}
