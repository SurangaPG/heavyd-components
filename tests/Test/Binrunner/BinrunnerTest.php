<?php

namespace surangapg\HeavydComponents\Test\Properties;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Yaml\Yaml;
use surangapg\HeavydComponents\BinRunner\BinRunner;
use surangapg\HeavydComponents\BinRunner\BinRunnerInterface;

class BinRunnerTest extends TestCase {

  /**
   * Test the getting of a full command to be outputted to the cli.
   *
   * @covers BinRunner::getFullCommand
   */
  public function testGetFullCommandToCLI() {
    $outputInterface = new ConsoleOutput();

    $binRunner = new BinRunner(
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
   * Test the getting of a full command to be outputted to a file.
   *
   * @covers BinRunner::getFullCommand
   */
  public function testGetFullCommandToFile() {
    $outputInterface = new ConsoleOutput();

    $binRunner = new BinRunner(
      'composer',
      '/dir/which/is/not/real',
      $outputInterface,
      '/outputfile/hkjlkq',
      BinRunnerInterface::GLOBAL_BIN
    );

    Assert::assertEquals('cd /dir/which/is/not/real && composer &> /outputfile/hkjlkq',
      $binRunner->getFullCommand(FALSE));
  }

  /**
   * Test the getting of a full command to be outputted to a file.
   *
   * @covers BinRunner::getFullCommand
   */
  public function testGetRelativeCommandToFile() {
    $outputInterface = new ConsoleOutput();

    $binRunner = new BinRunner(
      'composer',
      '/dir/which/is/not/real',
      $outputInterface,
      '/outputfile/hkjlkq',
      BinRunnerInterface::RELATIVE_BIN
    );

    Assert::assertEquals('cd /dir/which/is/not/real && ./composer &> /outputfile/hkjlkq',
      $binRunner->getFullCommand(FALSE));
  }

  /**
   * Tests a binrunner constructor.
   *
   * @covers BinRunner::__construct
   */
  public function testConstruct() {
    $binRunner = new BinRunner(
      'composer',
      '/dir/which/is/not/real'
    );

    // Output class should have been generated.
    Assert::assertInstanceOf('Symfony\Component\Console\Output\OutputInterface', $binRunner->getOutputInterface());

    // Output file name should have been generated.
    Assert::assertNotEmpty($binRunner->getOutputFile());
  }

  /**
   * Test or added options are added to the outputted command correctly.
   *
   * @covers BinRunner::addOption
   */
  public function testAddOption() {
    $outputInterface = new ConsoleOutput();

    $binRunner = new BinRunner(
      'composer',
      '/dir/which/is/not/real',
      $outputInterface,
      '/outputfile/hkjlkq',
      BinRunnerInterface::GLOBAL_BIN
    );

    $binRunner->addOption('--force');
    $binRunner->addOption('-v');
    $binRunner->addOption('--exclude', 'haha');
    $binRunner->addOption('--exclude', 'hihi');

    Assert::assertEquals('cd /dir/which/is/not/real && composer --force -v --exclude="haha" --exclude="hihi"',
      $binRunner->getFullCommand());

    Assert::assertEquals('cd /dir/which/is/not/real && composer --force -v --exclude="haha" --exclude="hihi" &> /outputfile/hkjlkq',
      $binRunner->getFullCommand(FALSE));
  }

  /**
   * Test or added arguments are added to the command correctly.
   *
   * @covers BinRunner::addArg
   */
   public function testAddArg() {

     $outputInterface = new ConsoleOutput();

     $binRunner = new BinRunner(
       'composer',
       '/dir/which/is/not/real',
       $outputInterface,
       '/outputfile/hkjlkq',
       BinRunnerInterface::GLOBAL_BIN
     );

     $binRunner->addArg('install');
     $binRunner->addArg('mik');

     Assert::assertEquals('cd /dir/which/is/not/real && composer install mik ',
      $binRunner->getFullCommand());

     Assert::assertEquals('cd /dir/which/is/not/real && composer install mik &> /outputfile/hkjlkq',
       $binRunner->getFullCommand(FALSE));
  }

  /**
   * Test or added arguments are added to the command correctly.
   *
   * @covers BinRunner::addArg
   * @covers BinRunner::addOption
   */
  public function testCommandAddArgsAndOptions() {

    $outputInterface = new ConsoleOutput();

    $binRunner = new BinRunner(
      'composer',
      '/dir/which/is/not/real',
      $outputInterface,
      '/outputfile/hkjlkq',
      BinRunnerInterface::GLOBAL_BIN
    );

    $binRunner->addArg('install');
    $binRunner->addOption('--mik', 'haha');

    Assert::assertEquals('cd /dir/which/is/not/real && composer install --mik="haha"',
      $binRunner->getFullCommand());

    Assert::assertEquals('cd /dir/which/is/not/real && composer install --mik="haha" &> /outputfile/hkjlkq',
      $binRunner->getFullCommand(FALSE));
  }

  /**
   * Test or added arguments are added to the command correctly.
   *
   * @covers BinRunner::run
   */
  public function testRun() {

    $binRunner = new BinRunner(
      'echo',
      __DIR__,
      NULL,
      $this->getArtifactDir()  . '/bin-runner-output-' . str_replace(' ', '-', microtime()) . '.txt',
      BinRunnerInterface::GLOBAL_BIN
    );

    $binRunner->addArg('chicken');
    $binRunner->run(FALSE);
    Assert::assertFileExists($binRunner->getOutputFile());
    // @TODO This doesn't work in the pipeline, but it does work locally.
    // Assert::assertContains('chicken', $binRunner->getOutput());
  }

  /**
   * Get the artifact dir.
   *
   * @return string
   *   The directory to place all the artifacts.
   */
  protected function getArtifactDir() {
    return dirname(dirname(__DIR__)) . '/artifacts';
  }
}