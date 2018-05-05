<?php
/**
 * @contains
 * Class to handle properties that are missing.
 */
namespace surangapg\HeavydComponents\Server\Exception;

/**
 * Class MissingPropertyException
 *
 * Exception to throw when the server can't be reached.
 *
 * @package workflow\Workflow\Components\Exception
 */
class CouldNotPollServerException extends \Exception {

  /**
   * MissingPropertyException constructor.
   *
   * @param string $sshCredentials
   *   The property that was missing.
   * @param array $sshCommandOutput
   *   Output from the ssh command.
   * @param int $code
   *   The code to return
   * @param \Exception $previous
   *   Previous exception.
   */
  public function __construct($sshCredentials, array $sshCommandOutput = [], $code = 0, \Exception $previous = null) {
    $message = sprintf("Couldn't poll server at %s", $sshCredentials);
    $message .= sprintf("Error message: \n %s", implode(PHP_EOL, $sshCommandOutput));
    parent::__construct($message, $code, $previous);
  }

}