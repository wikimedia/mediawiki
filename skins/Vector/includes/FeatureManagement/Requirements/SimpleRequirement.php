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

use MediaWiki\Skins\Vector\FeatureManagement\Requirement;

/**
 * NOTE: This API hasn't settled. It may change at any time without warning. Please don't bind to
 * it unless you absolutely need to
 *
 * @unstable
 *
 * @package MediaWiki\Skins\Vector\FeatureManagement\Requirements
 * @internal
 */
class SimpleRequirement implements Requirement {

	/**
	 * @var string The name of the requirement
	 */
	private $name;

	/**
	 * @var bool Whether the requirement is met
	 */
	private $isMet;

	/**
	 * @param string $name The name of the requirement
	 * @param bool $isMet Whether the requirement is met
	 */
	public function __construct( string $name, bool $isMet ) {
		$this->name = $name;
		$this->isMet = $isMet;
	}

	/**
	 * @inheritDoc
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @inheritDoc
	 */
	public function isMet(): bool {
		return $this->isMet;
	}
}
