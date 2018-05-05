<?php
/**
 * @file
 * Contains a server class which can be used to display the more abstract
 * server config as a more usable server object.
 *
 * Contains only some very basic methods.
 */
namespace surangapg\HeavydComponents\Server;

use Symfony\Component\Console\Output\ConsoleOutput;
use surangapg\HeavydComponents\Server\Exception\CouldNotPollServerException;


class Server implements ServerInterface {

  /**
   * Human readable label for the server.
   * @var string $label
   */
  protected $label;

  /**
   * Company that hosts the server.
   * @var string $hostedBy
   */
  protected $hostedBy;

  /**
   * Host url for the server.
   * @var string $host
   */
  protected $host;

  /**
   * User for the server.
   * @var string $user
   */
  protected $user;

  /**
   * Root dir for the server.
   * @var string $root
   */
  protected $root;

  /**
   * Get the raw data array.
   *
   * @var array
   *   Raw data array.
   */
  protected $rawData;

  /**
   * @inheritdoc
   */
  public static function create(array $serverProperties) {
    return new self($serverProperties);
  }

  /**
   * Server constructor.
   *
   * @param array $serverProperties
   *   Properties as passed from create.
   *
   * @internal Use the Server::Create().
   */
  public function __construct(array $serverProperties) {
    $this->setHost($serverProperties['host']);
    $this->setHostedBy($serverProperties['hostedBy']);
    $this->setUser($serverProperties['user']);
    $this->setRoot($serverProperties['root']);

    $this->rawData = $serverProperties;
  }

  /**
   * Poll the connection to the remote server.
   *
   * @TODO Should this support more than only ssh.
   * @TODO Don't send all the output to /dev/null
   *
   * @return bool
   *   Indicates or the server was polled correctly.
   *
   * @throws \workflow\Workflow\Components\Exception\CouldNotPollServerException
   *   Exception to indicate that the server couldn't be reached.
   */
  public function pollSshConnection() {
    $output = [];
    $return = 0;
    exec(
      sprintf("ssh -oBatchMode=yes -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no %s@%s &>/dev/null echo Server accessed successfully",
        $this->getUser(),
        $this->getHost()
      ),
      $output,
      $return
    );

    if ($return != 0) {
      throw new CouldNotPollServerException($this->getUser() . '@' . $this->getHost(), $output);
    }

    return TRUE;
  }

  /**
   * @inheritdoc
   */
  public function runRemote(string $command, string $dir = NULL) {
    $remote = $this->getUser() . '@' . $this->getHost();
    $rootDir = isset($dir) ? $dir : $this->getRoot();
    $output = [];
    $return = 0;

    exec(sprintf('ssh %s -oBatchMode=yes -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no \'cd %s && %s\'', $remote, $rootDir, $command), $output, $return);

    return [
      'return' => $return,
      'output' => $output,
    ];
  }

  /**
   * @inheritdoc
   */
  public function pullFile($source, $target) {
    $outputInterface = new ConsoleOutput();
    $binRunner = new BinRunner('scp', $outputInterface, getcwd(), BinRunnerInterface::GLOBAL_BIN);
    $binRunner->addArg($this->getUser() . '@' . $this->getHost() . ':' . $source);
    $binRunner->addArg($target);
    $binRunner->run(false);
  }

  /**
   * @inheritdoc
   */
  public function pushFile($source, $target) {
    $outputInterface = new ConsoleOutput();
    $binRunner = new BinRunner('scp', $outputInterface, getcwd(), BinRunnerInterface::GLOBAL_BIN);
    $binRunner->addArg($source);
    $binRunner->addArg($this->getUser() . '@' . $this->getHost() . ':' . $target);
    $binRunner->run(false);
  }

  /**
   * Get the unique identifier for the server.
   *
   * @return string
   *   Unique identifier for the server.
   */
  public function getLabel() {
    return $this->getHost() . ':' . $this->getRoot();
  }

  /**
   * The ssh info or the uri.
   *
   * @return string
   *   Link uri or ssh info if not set.
   */
  public function getUriOrSshInfo() {
    if (isset($this->getRawData()['uri'])) {
      return $this->getRawData()['uri'];
    }

    return $this->getUser() . '@' . $this->getLabel();
  }

  /**
   * Get the raw data.
   *
   * @return array
   *   All the raw data.
   */
  public function getRawData() {
    return $this->rawData;
  }

  /**
   * @return string
   */
  public function getHost() {
    return $this->host;
  }

  /**
   * @return string
   */
  public function getHostedBy() {
    return $this->hostedBy;
  }

  /**
   * @return string
   */
  public function getRoot() {
    return $this->root;
  }

  /**
   * @return string
   */
  public function getUser() {
    return $this->user;
  }

  /**
   * @param string $host
   */
  public function setHost($host) {
    $this->host = $host;
  }

  /**
   * @param string $hostedBy
   */
  public function setHostedBy($hostedBy) {
    $this->hostedBy = $hostedBy;
  }

  /**
   * @param string $label
   */
  public function setLabel($label) {
    $this->label = $label;
  }

  /**
   * @param string $root
   */
  public function setRoot($root) {
    $this->root = $root;
  }

  /**
   * @param string $user
   */
  public function setUser($user) {
    $this->user = $user;
  }
}