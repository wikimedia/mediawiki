<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Actions;

/**
 * Provides information about an action that can be used to determine whether the action
 * can be executed in a given context.
 *
 * @since 1.41
 * @author Daniel Kinzler
 */
class ActionInfo {

	private string $name;
	private ?string $restriction;
	private bool $needsReadRights;
	private bool $requiresWrite;
	private bool $requiresUnblock;

	/**
	 * Constructs ActionInfo based on an action object spec which is expected to
	 * include the following keys:
	 * - name: the canonical name of the action (string)
	 * - requiresUnblock: Whether this action can still be executed by a blocked user (bool)
	 * - requiresWrite: Whether this action requires the wiki not to be locked (bool)
	 * - needsReadRights: whether this action requires read rights (bool)
	 * - restriction: the permission required to perform this action (string, or null for none)
	 *
	 * @param array $spec The action spec
	 */
	public function __construct( array $spec ) {
		$this->name = $spec['name'];
		$this->restriction = $spec['restriction'];
		$this->needsReadRights = $spec['needsReadRights'];
		$this->requiresWrite = $spec['requiresWrite'];
		$this->requiresUnblock = $spec['requiresUnblock'];
	}

	/**
	 * @return string The action's name
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return ?string A permission required to execute the action
	 */
	public function getRestriction(): ?string {
		return $this->restriction;
	}

	/**
	 * @return bool Whether the action requires the user to have read access
	 */
	public function needsReadRights(): bool {
		return $this->needsReadRights;
	}

	/**
	 * @return bool Whether the action requires the database to be writable
	 */
	public function requiresWrite(): bool {
		return $this->requiresWrite;
	}

	/**
	 * @return bool Whether the action requires the user to not be blocked
	 */
	public function requiresUnblock(): bool {
		return $this->requiresUnblock;
	}

}
