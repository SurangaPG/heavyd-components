<?php
/**
 * @contains
 * Class to handle bin commands that couldn't be run.
 */
namespace surangapg\HeavydComponents\BinRunner\Exception;

use surangapg\HeavydComponents\BinRunner\BinRunnerInterface;

/**
 * Class BinRunFailedException
 *
 * Exception to throw when a bin command couldn't be run.
 *
 * @package surangapg\HeavydComponents\Exception
 */
class BinRunFailedException extends \Exception {

  /**
   * BinRunFailedException constructor.
   *
   * @param \surangapg\HeavydComponents\BinRunner\BinRunnerInterface $binRunner
   *   Runner that failed.
   * @param int $code
   *   The code to return
   * @param \Exception $previous
   *   Previous exception.
   */
  public function __construct(BinRunnerInterface $binRunner, $code = 0, \Exception $previous = null) {

    $message = 'Failed running bin command.' . PHP_EOL;
    $message .= 'Full command: ' . PHP_EOL;
    $message .= $binRunner->getFullCommand() . PHP_EOL;
    $message .= 'Full output: ' . PHP_EOL;
    $message .= $binRunner->getOutput();

    parent::__construct($message, $code, $previous);
  }

}