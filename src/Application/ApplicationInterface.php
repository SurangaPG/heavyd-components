<?php
/**
 * @file Properties interface.
 */

namespace surangapg\HeavydComponents\Application;

use surangapg\HeavydComponents\Properties\PropertiesInterface;

interface ApplicationInterface {

  /**
   * @return PropertiesInterface
   */
  public function getProperties();

}