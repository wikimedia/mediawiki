<?php

namespace MediaWiki\Api\Hook;

use MediaWiki\Api\ApiBlock;
use MediaWiki\Permissions\Authority;
use MediaWiki\User\UserIdentity;

/**
 * @unstable
 * @ingroup Hooks
 */
interface ApiBlockSucceededHook {
	/**
	 * This hook is called from ApiBlock.
	 * It allows callers to respond to a successful block with support
	 * for passing back the results of subsequent blocks in the response.
	 *
	 * @since 1.46
	 *
	 * @param ApiBlock $module
	 * @param Authority $performer
	 * @param UserIdentity $mainTarget
	 * @param array $params
	 * @param array &$additionalBlocksStatuses
	 * @return void
	 */
	public function onApiBlockSucceeded( $module, $performer, $mainTarget, $params, &$additionalBlocksStatuses );
}
