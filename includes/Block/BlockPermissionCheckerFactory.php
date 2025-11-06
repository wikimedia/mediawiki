<?php

/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Block;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Permissions\Authority;
use MediaWiki\User\UserIdentity;

/**
 * Factory class for BlockPermissionChecker
 *
 * @since 1.35
 */
class BlockPermissionCheckerFactory {
	/**
	 * @internal only to be used by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = BlockPermissionChecker::CONSTRUCTOR_OPTIONS;

	private ServiceOptions $options;
	private BlockTargetFactory $blockTargetFactory;

	public function __construct(
		ServiceOptions $options,
		BlockTargetFactory $blockTargetFactory
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->blockTargetFactory = $blockTargetFactory;
	}

	/**
	 * @param UserIdentity|string|null $target Target of the validated block; may be null if unknown
	 * @param Authority $performer Performer of the validated block
	 * @return BlockPermissionChecker
	 *
	 * @deprecated since 1.44 use newChecker, which does not require $target
	 */
	public function newBlockPermissionChecker(
		$target,
		Authority $performer
	) {
		$checker = $this->newChecker( $performer );
		if ( $target !== null ) {
			$checker->setTarget( $target );
		}
		return $checker;
	}

	/**
	 * @param Authority $performer Performer of the block
	 * @return BlockPermissionChecker
	 */
	public function newChecker( Authority $performer ) {
		return new BlockPermissionChecker(
			$this->options,
			$this->blockTargetFactory,
			$performer
		);
	}
}
