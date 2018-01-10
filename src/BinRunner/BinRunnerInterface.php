<?php

namespace surangapg\HeavydComponents\BinRunner;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interface BinRunnerInterface
 *
 * Helper run various binary commands etc.
 */
interface BinRunnerInterface {

  /**
   * Indicates a global bin. E.g one that can be run without any extra help.
   * Such as "composer".
   */
  const GLOBAL_BIN = 'global';

  /**
   * Indicates a relative bin that should be run from the root of the project.
   * E.g: phing/vendor/bin => this will auto convert to ./phing/vendor/bin.
   */
  const RELATIVE_BIN = 'relative';

  /**
   * Get the full binary command.
   *
   * @param bool $outputToCli
   *   Should the output be returned straight to the cli or written to temp file.
   *
   * @return string
   *   Full command string.
   */
  public function getFullCommand($outputToCli = TRUE);

  /**
   * Basic constructor.
   *
   * @param $bin
   *   Binary file to run from.
   * @param $type $makeAbsolute
   *   Should the binary be made absolute based on the project route. E.g
   * @param string $dir
   *   The directory to run the command in. Defaults to the root project dir.
   * @param \Symfony\Component\Console\Output\OutputInterface|NULL $outputInterface
   *   An output interface.
   *  @param string $outputFile
   *   The outputfile to write to.
   */
  public function __construct(string $bin, string $dir, OutputInterface $outputInterface = NULL, string $outputFile = NULL, $type = BinRunnerInterface::RELATIVE_BIN);

  /**
   * Add an option to the command to run.
   *
   * @param $name
   *   Name for the argument (with needed preceeding -- marks)
   * @param $value
   *   Value to add for the marker. Will be added with a = behind the name.
   */
  public function addOption($name, $value = null);

  /**
   * Extra argument for the command.
   *
   * @param string $value
   *   Add an argument to the command.
   */
  public function addArg($value);

  /**
   * Since some command have a lot of output (not always passed via the $1)
   * We'll write it to a file and return the data afterwards.
   *
   * @return string
   *   The full output that was printed to the console.
   */
  public function getOutput();

  /**
   * The location of the output file.
   *
   * @return string
   *   The location of the output file.
   */
  public function getOutputFile();

  /**
   *  Start running the actual command.
   *
   * @param bool $outputToCli
   *   Should the output be returned straight to the cli.
   *
   * @return int
   *   Return code for the command.
   */
  public function run($outputToCli = TRUE);

}