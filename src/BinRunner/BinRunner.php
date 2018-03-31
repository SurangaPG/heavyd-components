<?php

/**
 * @file
 * Contains a basic helper to help run commands in a given folder.
 */
namespace surangapg\HeavydComponents\BinRunner;

use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use surangapg\HeavydComponents\BinRunner\Exception\BinRunFailedException;

/**
 * Class BinRunner
 *
 * Run a command in a given folder with some extra helpers for the text output.
 * (this is written to a temp file in the filesystem).
 */
class BinRunner implements BinRunnerInterface {

  /**
   * Dir where the command will be executed.
   *
   * @var string
   */
  protected $dir;

  /**
   * The type of the command bin.
   *
   * @var string
   */
  protected $type;

  /**
   * The bin for the command.
   *
   * @var string
   */
  protected $bin;

  /**
   * Output interface.
   *
   * @var \Symfony\Component\Console\Output\OutputInterface
   */
  protected $outputInterface;

  /**
   * File where the temp output will be stored.
   *
   * @var string
   */
  protected $outputFile;

  /**
   * Array of arrays with all the options in with a key/value pair.
   *
   * @var string[]
   */
  protected $options = [];

  /**
   * Array of all the args.
   *
   * @var string[]
   */
  protected $args = [];

  /**
   * Should a fail throw an exception.
   *
   * @var bool
   */
  protected $throwException = true;

  /**
   * @inheritdoc
   */
  public function getFullCommand($outputToCli = TRUE) {

    if ($outputToCli) {
      $command = sprintf('cd %s && %s %s %s',
        $this->dir,
        $this->generateBin(),
        $this->generateArgs(),
        $this->generateOptions()
      );
    }
    else {
      $command = sprintf('cd %s && %s %s %s &> %s',
        $this->dir,
        $this->generateBin(),
        $this->generateArgs(),
        $this->generateOptions(),
        $this->outputFile
      );
    }

    return preg_replace('/\s+/', ' ', $command);
  }

  /**
   * @inheritdoc
   */
  public function __construct(string $bin, string $dir, OutputInterface $outputInterface = NULL, string $outputFile = NULL, $type = BinRunnerInterface::RELATIVE_BIN) {
    $this->dir = $dir;
    $this->type = $type;
    $this->bin = $bin;
    if (!isset($outputInterface)) {
      $outputInterface = new BufferedOutput();
    }
    $this->outputInterface = $outputInterface;
    $this->outputFile = isset($outputFile) ? $outputFile : $dir . '/temp/heavyd-command-output--' . str_replace(' ', '-', microtime());
  }

  /**
   * @inheritdoc
   */
  public function addOption($name, $value = null) {
    $option = $name;
    if (isset($value)) {
      $option .= '="' . $value . '"';
    }
    $this->options[] = $option;
  }

  /**
   * @inheritdoc
   */
  public function addArg($value) {
    $this->args[] = $value;
  }

  /**
   * @inheritdoc
   */
  public function getOutput() {
    return file_get_contents($this->outputFile);
  }

  /**
   * @inheritdoc
   */
  public function getOutputFile() {
    return $this->outputFile;
  }

  /**
   * @inheritdoc
   */
  public function run($outputToCli = TRUE) {

    $command = $this->getFullCommand($outputToCli);

    if ($this->outputInterface->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
      $this->outputInterface->writeln('Running: ' . $command, OutputInterface::VERBOSITY_VERBOSE);
    }
    else {
      $bin = basename($this->bin);
      $args = implode(' ', $this->args);
      $options = implode(' ', $this->options);
      $this->outputInterface->writeln('Running ' . $bin . ' ' . $args . ' ' . $options . ' (use -v to see full command)');
    }

    if ($this->outputInterface->getVerbosity() >= OutputInterface::VERBOSITY_VERY_VERBOSE) {
      $this->outputInterface->writeln(' Bin: ' . $this->bin);
      $this->outputInterface->writeln(' Dir: ' . $this->dir);
      $this->outputInterface->writeln(' Options: ' . implode(', ', $this->options));
      $this->outputInterface->writeln(' Args: ' . implode(', ', $this->args));
      $this->outputInterface->writeln(' Type: ' . $this->type);
      $this->outputInterface->writeln(' Outputfile: ' . $this->outputFile);
      $this->outputInterface->writeln('');
    }

    $output = [];
    if ($outputToCli) {
      passthru($command, $return);

      if ($return !== 0 && $this->throwException) {
        throw new BinRunFailedException($this);
      }

      if ($this->outputInterface->getVerbosity() >= OutputInterface::VERBOSITY_VERY_VERBOSE) {
        $this->outputInterface->writeln(' Command output: ' . $this->bin);
        $this->outputInterface->writeln(file_get_contents($this->outputFile));
        $this->outputInterface->writeln('');
      }

      return $return;
    }

    else {
      exec($command, $output, $return);

      if ($return !== 0 && $this->throwException) {
        throw new BinRunFailedException($this);
      }

      if ($this->outputInterface->getVerbosity() >= OutputInterface::VERBOSITY_VERY_VERBOSE) {
        $this->outputInterface->writeln(' Command output: ' . $this->bin);
        $this->outputInterface->writeln(file_get_contents($this->outputFile));
        $this->outputInterface->writeln('');
      }

      return $return;
    }
  }

  /**
   * Generate the full binary path.
   *
   * @return string
   *   The full binary path.
   */
  protected function generateBin() {
    $bin = '';
    if ($this->type == BinRunnerInterface::RELATIVE_BIN) {
      ltrim($this->bin, './');
      $bin = './';
    }
    $bin .= $this->bin;
    return $bin;
  }

  /**
   * Generate a full string of the args.
   *
   * @return string
   *   All the args for the command.
   */
  protected function generateArgs() {
    return implode(' ', $this->args);
  }

  /**
   * Generate a full string of all the options for the command.
   *
   * @return string
   *   All the args for the command.
   */
  protected function generateOptions() {
    return implode(' ', $this->options);
  }

  /**
   * Get the output interface.
   *
   * @return \Symfony\Component\Console\Output\BufferedOutput|\Symfony\Component\Console\Output\OutputInterface
   *   The output interface for the command runner.
   */
  public function getOutputInterface() {
    return $this->outputInterface;
  }
}