<?php

/**
 * @file
 *  pro verbatim copy of the drupal class with the same name, this prevents having
 *  to boostrap a full drupal instance just for this one class. It also prevents
 *  a dependency on drupal in a workflow package.
 */
namespace workflow\Workflow\Test\Components\Crypt;

use PHPUnit\Framework\TestCase;
use surangapg\HeavydComponents\Crypt\Crypt;

class CryptTest extends TestCase {

  /**
   * Very minimal sanity check.
   *
   * This class has been copied from the drupal core and should be pretty
   * stable/straightforward. So we do the bare minimum.
   *
   * @covers Crypt::randomBytes
   */
  function testRandomBytes() {
    $random = Crypt::randomBytes(16);
    $this->assertEquals(TRUE, is_string($random));
  }

  /**
   * Very minimal sanity check.
   *
   * This class has been copied from the drupal core and should be pretty
   * stable/straightforward. So we do the bare minimum.
   *
   * @covers Crypt::hashBase64
   */
  function testHashBase64() {
    $random = Crypt::hashBase64('superman');
    $this->assertEquals(TRUE, is_string($random));
  }

  /**
   * Very minimal sanity check.
   *
   * This class has been copied from the drupal core and should be pretty
   * stable/straightforward. So we do the bare minimum.
   *
   * @covers Crypt::hmacBase64
   */
  function testHmacBase64() {
    $random = Crypt::hmacBase64('superman', 'batman');
    $this->assertEquals(TRUE, is_string($random));
  }

  /**
   * Very minimal sanity check.
   *
   * This class has been copied from the drupal core and should be pretty
   * stable/straightforward. So we do the bare minimum.
   *
   * @covers Crypt::hashEquals
   */
  function testHashEquals() {
    $equals = Crypt::hashEquals('superman', 'batman');
    $this->assertEquals(FALSE, $equals);

    $equals = Crypt::hashEquals('batman', 'batman');
    $this->assertEquals(TRUE, $equals);
  }

  /**
   * Very minimal sanity check.
   *
   * This class has been copied from the drupal core and should be pretty
   * stable/straightforward. So we do the bare minimum.
   *
   * @covers Crypt::randomBytesBase64
   */
  function testRandomBytesBase64() {
    $random = Crypt::randomBytesBase64();
    $this->assertEquals(TRUE, is_string($random));
  }
}