<?php

/**
 * @file
 *  pro verbatim copy of the drupal class with the same name, this prevents having
 *  to boostrap a full drupal instance just for this one class. It also prevents
 *  a dependency on drupal in a workflow package.
 */
namespace workflow\Workflow\Test\Components\Crypt;

use PHPUnit\Framework\TestCase;
use surangapg\HeavydComponents\Crypt\Php;

class PhpTest extends TestCase {

  /**
   * Very minimal sanity check.
   *
   * This class has been copied from the drupal core and should be pretty
   * stable/straightforward. So we do the bare minimum.
   *
   * @covers Php::generate
   */
  function testGenerate() {
    $php = new Php();
    $random = $php->generate();
    $this->assertEquals(TRUE, is_string($random));
  }

}