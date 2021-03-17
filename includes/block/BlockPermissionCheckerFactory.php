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
 */

namespace MediaWiki\Block;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Permissions\Authority;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;

/**
 * Factory class for BlockPermissionChecker
 *
 * @since 1.35
 */
class BlockPermissionCheckerFactory {

	/** @var ServiceOptions */
	private $options;

	/**
	 * @internal only to be used by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = BlockPermissionChecker::CONSTRUCTOR_OPTIONS;

	/** @var BlockUtils */
	private $blockUtils;

	/** @var UserFactory */
	private $userFactory;

	/**
	 * @param ServiceOptions $options
	 * @param BlockUtils $blockUtils
	 * @param UserFactory $userFactory
	 */
	public function __construct(
		ServiceOptions $options,
		BlockUtils $blockUtils,
		UserFactory $userFactory
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->blockUtils = $blockUtils;
		$this->userFactory = $userFactory;
	}

	/**
	 * @param UserIdentity|string|null $target Target of the validated block; may be null if unknown
	 * @param Authority $performer Performer of the validated block
	 *
	 * @return BlockPermissionChecker
	 */
	public function newBlockPermissionChecker(
		$target,
		Authority $performer
	) {
		return new BlockPermissionChecker(
			$this->options,
			$this->blockUtils,
			$this->userFactory,
			$target,
			$performer
		);
	}
}
