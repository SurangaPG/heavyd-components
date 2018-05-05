<?php
/**
 * @file Contains a server class which can be used to display the more abstract
 *  server config as a more usable server object.
 */
namespace surangapg\HeavydComponents\Server;

interface ServerInterface {

  /**
   * Generates the server object from all the data in an array of properties.
   *
   * @param array $serverProperties
   *   Array of basic config. Basically a pro verbatim set from the config in
   *   the server.yml file. Should have the following keys.
   *   - label: The label for the server (e.g Production).
   *   - hostedBy: The hosting company for the server (e.g level27).
   *   - host: The host for the server (e.g o-a-web1.level27.be).
   *   - user: The server user (e.g vd1000).
   *   - root: The root dir for the server (e.g /var/web/vd1000/project).
   *
   * @return \surangapg\HeavydComponents\Server\Server
   *   Fully populated server object.
   */
  public static function create(array $serverProperties);

  /**
   * Get the user used to connect to the server via ssh.
   *
   * @return string
   */
  public function getUser();

  /**
   * Pulls a single file from the server via scp.
   *
   * @param string $source
   *   Full path for the source file without any of the server credentials.
   * @param string $target
   *   Full path for the target file.
   */
  public function pullFile($source, $target);

  /**
   * Pushes a single file from the server via scp.
   *
   * @param string $source
   *   Full path for the target file.
   * @param string $target
   *   Full path for the target file without any of the server credentials.
   */
  public function pushFile($source, $target);

  /**
   * Get the host information for the server, either the IP or domain resolver.
   *
   * @return string
   */
  public function getHost();

  /**
   * Company that hosts the server.
   *
   * @return string
   */
  public function getHostedBy();

  /**
   * @param string $command
   *   The command to run.
   * @param string|NULL $dir
   *   The directory to run the command in. Defaults to the drush root.
   *
   * @return array
   *    Array with the following keys:
   *      return: Return code for the command.
   *      output: Array with all the string output.
   */
  public function runRemote(string $command, string $dir = NULL);

  /**
   * Human readable label.
   *
   * @return string
   */
  public function getLabel();

  /**
   * Root dir for the server.
   * @return string
   */
  public function getRoot();

  /**
   * The ssh info or the uri.
   *
   * @return string
   *   Link uri or ssh info if not set.
   */
  public function getUriOrSshInfo();

  /**
   * Get the raw data.
   *
   * @return array
   *   All the raw data.
   */
  public function getRawData();

}