<?php
/**
 * @file
 * Contains a helper class for projects. A project is one piece of data
 * representing a project within the company structure.
 */

namespace surangapg\HeavydComponents\Project;

use surangapg\HeavydComponents\Server\ServerInterface;

interface ProjectInterface {

  /**
   * Check or the project allows for the autopolling of the security status.
   *
   * @return bool
   *   Should the project be polled automatically.
   */
  public function pollSecurityAutomatically();

  /**
   * Get the full unique identifier for this project.
   *
   * This is in essence a combination of the client, project and type data.
   *
   * @return string
   *   Get the full unique identifier for this project.
   */
  public function getFullIdentifier();

  /**
   * Get all the servers that should be checked for security purposes.
   *
   * @return ServerInterface[]
   *   All the servers to check.
   */
  public function getSecurityPollableServers();

  /**
   * Get the project type. Either d7 or d8.
   *
   * @return string
   *   The project type.
   */
  public function getProjectType();

  /**
   * Get the name for the project.
   *
   * @return string
   *   The project name.
   */
  public function getProjectName();

  /**
   * Group the project belongs to (usually the client)
   *
   * @return string
   *   The project group.
   */
  public function getProjectGroup();

  /**
   * Get the machine name for the team that handles this project.
   *
   * @return string
   *   The team name.
   */
  public function getProjectTeam();

  /**
   * The code repository.
   *
   * @return string
   *   Get the code repository
   */
  public function getCodeRepository();

  /**
   * Get the main branch for the project.
   *
   * @return string
   *    The main branch for the project.
   */
  public function getCodeMainBranch();

  /**
   * Get the raw data.
   *
   * @return array
   *   The raw data.
   */
  public function getRawData();

  /**
   * Get all the servers connected to this project.
   *
   * @return \workflow\Workflow\Components\Server\ServerInterface[]
   *   All the servers connected to this project.
   */
  public function getConnectedServers();

}