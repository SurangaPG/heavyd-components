<?php

namespace surangapg\HeavydComponents\Test\Properties;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use surangapg\HeavydComponents\BinRunner\BinRunnerInterface;
use surangapg\HeavydComponents\BinRunner\PhingBinRunner;

class PhingBinRunnerTest extends TestCase {

  /**
   * Test the getting of a full command to be outputted to the cli.
   *
   * A full command that is not based of a phing binary should not add a silent
   * option.
   *
   * @covers PhingBinRunner::getFullCommand
   */
  public function testGetFullNonPhingCommandToCLI() {
    $outputInterface = new ConsoleOutput();

    $binRunner = new PhingBinRunner(
      'composer',
      '/dir/which/is/not/real',
      $outputInterface,
      '/outputfile/hkjlkq',
      BinRunnerInterface::GLOBAL_BIN
    );

    Assert::assertEquals('cd /dir/which/is/not/real && composer ',
      $binRunner->getFullCommand());
  }

  /**
   * Test the getting of a full command to be outputted to the cli.
   *
   * A full command from phing should be silent by default.
   *
   * @covers PhingBinRunner::getFullCommand
   */
  public function testGetFullCommandToCLI() {
    $outputInterface = new ConsoleOutput();

    $binRunner = new PhingBinRunner(
      'phing',
      '/dir/which/is/not/real',
      $outputInterface,
      '/outputfile/hkjlkq',
      BinRunnerInterface::GLOBAL_BIN
    );

    Assert::assertEquals('cd /dir/which/is/not/real && phing -S',
      $binRunner->getFullCommand());
  }

  /**
   * Test the getting of a full command to be outputted to the cli.
   *
   * A full command from phing should be silent by default.
   *
   * @covers PhingBinRunner::getFullCommand
   */
  public function testGetFullCommandToFile() {
    $outputInterface = new ConsoleOutput();

    $binRunner = new PhingBinRunner(
      'phing',
      '/dir/which/is/not/real',
      $outputInterface,
      '/outputfile/hkjlkq',
      BinRunnerInterface::GLOBAL_BIN
    );

    Assert::assertEquals('cd /dir/which/is/not/real && phing -S &> /outputfile/hkjlkq',
      $binRunner->getFullCommand(FALSE));
  }

  /**
   * Test the getting of a verbose command to be outputted to the cli.
   *
   * A full command from phing should not be silent if the output marks it as
   * verbose.
   *
   * @covers PhingBinRunner::getFullCommand
   */
  public function testGetFullVerboseCommand() {
    $outputInterface = new ConsoleOutput();
    $outputInterface->setVerbosity(OutputInterface::VERBOSITY_VERBOSE);

    $binRunner = new PhingBinRunner(
      'phing',
      '/dir/which/is/not/real',
      $outputInterface,
      '/outputfile/hkjlkq',
      BinRunnerInterface::GLOBAL_BIN
    );

    Assert::assertEquals('cd /dir/which/is/not/real && phing ',
      $binRunner->getFullCommand());
  }

}
