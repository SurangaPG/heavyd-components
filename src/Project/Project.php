<?php
/**
 * @file
 * Contains a helper class for projects. A project is one piece of data
 * representing a project within the company structure.
 *
 * @TODO Unit tests
 * @TODO Clean up overly array based logic (once test coverage is complete).
 */

namespace surangapg\HeavydComponents\Project;

use Symfony\Component\Yaml\Yaml;
use surangapg\HeavydComponents\Server\Server;
use surangapg\HeavydComponents\Server\ServerInterface;

class Project implements ProjectInterface {

  /**
   * All the raw config.
   *
   * @var array
   *   All the raw data in this project.
   */
  protected $rawData;

  /**
   * Get all the connected servers.
   *
   * @var ServerInterface[]
   *   All the connected servers keyed by their name.
   */
  protected $connectedServers;

  /**
   * Parse a folder with yaml files to project classes.
   *
   * @untested
   *
   * @param string $folder
   *   Parse all the yaml files in a folder to project objects.
   *
   * @return ProjectInterface[]
   *   All the parsed items.
   */
  public static function parseFolder(string $folder) {
    $yamlFiles = glob($folder . '/*.yml');

    $projects = [];

    foreach ($yamlFiles as $yamlFile) {
      $projects[basename($yamlFile)] = new static(Yaml::parseFile($yamlFile));
    }

    return $projects;
  }

  /**
   * Project constructor.
   *
   * @param array $data
   *   Array with all the data to generate project from.
   */
  public function __construct(array $data) {

    // @TODO Add validation.
    $this->rawData = $data;

    // Generate server objects for all the servers.
    foreach ($data['servers'] as $key => $serverConfig) {
      $this->connectedServers[$key] = Server::create($serverConfig);
    }
  }

  /**
   * Check or the project allows for the autopolling of the security status.
   *
   * @return bool
   *   Should the project be polled automatically.
   */
  public function pollSecurityAutomatically() {
    return !(!isset($this->rawData['security']['autopoll']) || !$this->rawData['security']['autopoll']);
  }

  /**
   * Get all the servers that should be checked for security purposes.
   *
   * @return ServerInterface[]
   *   All the servers to check.
   */
  public function getSecurityPollableServers() {
    $pollableServers = array_flip($this->rawData['security']['polled_servers']);

    if (count($pollableServers) == 0) {
      return [];
    }

    return array_intersect_key($this->getConnectedServers(), $pollableServers);
  }

  /**
   * Get the project type. Either d7 or d8.
   *
   * @return string
   *   The project type.
   */
  public function getProjectType() {
    return $this->rawData['project']['type'];
  }

  /**
   * Get the machine name for the team that handles this project.
   *
   * @return string
   *   The team name.
   */
  public function getProjectTeam() {
    return $this->rawData['project']['team'];
  }

  /**
   * Get the name for the project.
   *
   * @return string
   *   The project name.
   */
  public function getProjectName() {
    return $this->rawData['project']['name'];
  }

  /**
   * Group the project belongs to (usually the client)
   *
   * @return string
   *   The project group.
   */
  public function getProjectGroup() {
    return $this->rawData['project']['group'];
  }

  /**
   * Get the full unique identifier for this project.
   *
   * This is in essence a combination of the client, project and type data.
   *
   * @return string
   *   Get the full unique identifier for this project.
   */
  public function getFullIdentifier() {
    return $this->rawData['project']['group'] . '_' . $this->rawData['project']['type'] . '_' . $this->rawData['project']['name'];
  }

  /**
   * Get all the servers connected to this project.
   *
   * @return \surangapg\HeavydComponents\Server\ServerInterface[]
   *   All the servers connected to this project.
   */
  public function getConnectedServers() {
    return $this->connectedServers;
  }

  /**
   * The code repository.
   *
   * @return string
   *   Get the code repository
   */
  public function getCodeRepository() {
    return empty($this->rawData['code']['repository']) ? NULL : $this->rawData['code']['repository'];
  }

  /**
   * Get the main branch for the project.
   *
   * @return string
   *    The main branch for the project.
   */
  public function getCodeMainBranch() {
    return empty($this->rawData['code']['main_branch']) ? NULL : $this->rawData['code']['main_branch'];
  }

  /**
   * Get the raw data.
   *
   * @return array
   *   The raw data.
   */
  public function getRawData() {
    return $this->rawData;
  }
}