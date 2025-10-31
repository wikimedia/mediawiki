<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Block\Block;
use MediaWiki\Language\Language;
use MediaWiki\User\UserIdentity;

/**
 * @ingroup API
 */
trait ApiBlockInfoTrait {

	/**
	 * Get basic info about a given block
	 * @param Block $block
	 * @param Language|null $language
	 * @return array Array containing several keys:
	 *  - blockid - ID of the block
	 *  - blockedby - username of the blocker
	 *  - blockedbyid - user ID of the blocker
	 *  - blockreason - reason provided for the block
	 *  - blockedtimestamp - timestamp for when the block was placed/modified
	 *  - blockedtimestampformatted - blockedtimestamp, formatted for the current locale
	 *  - blockexpiry - expiry time of the block
	 *  - blockexpiryformatted - blockexpiry formatted for the current locale, omitted if infinite
	 *  - blockexpiryrelative - relative time to blockexpiry (e.g. 'in 5 days'), omitted if infinite
	 *  - blockpartial - block only applies to certain pages, namespaces and/or actions
	 *  - systemblocktype - system block type, if any
	 *  - blockcomponents - If the block is a composite block, this will be an array of block
	 *    info arrays
	 */
	private function getBlockDetails(
		Block $block,
		$language = null
	) {
		return ( new ApiBlockInfoHelper )->getBlockDetails(
			$block, $language ?? $this->getLanguage(), $this->getUser() );
	}

	/**
	 * Get the API error code, to be used in ApiMessage::create or ApiBase::dieWithError
	 * @param Block $block
	 * @return string
	 */
	private function getBlockCode( Block $block ): string {
		return ( new ApiBlockInfoHelper )->getBlockCode( $block );
	}

	// region   Methods required from ApiBase
	/** @name   Methods required from ApiBase
	 * @{
	 */

	/**
	 * @see IContextSource::getLanguage
	 * @return Language
	 */
	abstract public function getLanguage();

	/**
	 * @see IContextSource::getUser
	 * @return UserIdentity
	 */
	abstract public function getUser();

	/** @} */
	// endregion -- end of methods required from ApiBase

}

/** @deprecated class alias since 1.43 */
class_alias( ApiBlockInfoTrait::class, 'ApiBlockInfoTrait' );
