<?php

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @since 1.35
 */

namespace MediaWiki\Skins\Vector\FeatureManagement\Requirements;

use Config;
use MediaWiki\Skins\Vector\FeatureManagement\Requirement;
use User;

/**
 * @package MediaWiki\Skins\Vector\FeatureManagement\Requirements
 * @internal
 */
class ABRequirement implements Requirement {
	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @var User
	 */
	private $user;

	/**
	 * @var string The name of the experiment
	 */
	private $experimentName;

	/**
	 * @var string The name of the requirement
	 */
	private $name;

	/**
	 * @param Config $config
	 * @param User $user
	 * @param string $experimentName The name of the experiment
	 * @param string|null $name The name of the requirement
	 */
	public function __construct(
		Config $config,
		User $user,
		string $experimentName,
		?string $name = null
	) {
		$this->config = $config;
		$this->user = $user;
		$this->experimentName = $experimentName;
		$this->name = $name ?? '';
	}

	/**
	 * @inheritDoc
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * Returns true if the user is logged-in and false otherwise.
	 *
	 * @inheritDoc
	 */
	public function isMet(): bool {
		// Get the experiment configuration from the config object.
		$experiment = $this->config->get( 'VectorWebABTestEnrollment' );

		// Use the local user ID directly
		$id = $this->user->getId();

		// Check if the experiment is not enabled or does not match the specified name.
		if ( !$experiment['enabled'] || $experiment['name'] !== $this->experimentName ) {
			// If the experiment is not enabled or does not match the specified name,
			// return true, indicating that the metric is "met"
			return true;
		} else {
			// If the experiment is enabled and matches the specified name,
			// calculate the user's variant based on their user ID
			$variant = $id % 2;

			// Cast the variant value to a boolean and return it, indicating whether
			// the user is in the "control" or "test" group.
			return (bool)$variant;
		}
	}
}
