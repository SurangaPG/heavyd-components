<?php


/**
 * @file
 * Contains a basic helper to help run commands in a given folder.
 */
namespace surangapg\HeavydComponents\BinRunner;

use Symfony\Component\Console\Output\OutputInterface;

class PhingBinRunner extends BinRunner {

  /**
   * @inheritdoc
   */
  public function run($outputToCli = TRUE) {

    // Make the command silent if applicable (only applies for phing commands).
    if ($this->outputInterface->getVerbosity() < OutputInterface::VERBOSITY_VERBOSE && strpos($this->bin, 'phing') !== FALSE) {
      $this->addOption('-S');
    }

    return parent::run($outputToCli);
  }
}