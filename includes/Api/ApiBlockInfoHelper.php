<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Block\AbstractBlock;
use MediaWiki\Block\Block;
use MediaWiki\Block\CompositeBlock;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\SystemBlock;
use MediaWiki\Language\Language;
use MediaWiki\User\UserIdentity;
use MediaWiki\Utils\MWTimestamp;

/**
 * Helper class for API modules that display block information. Intended for use via
 * composition.
 *
 * @ingroup API
 * @since 1.44
 */
class ApiBlockInfoHelper {

	/**
	 * Get basic info about a given block
	 *
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
	public function getBlockDetails(
		Block $block,
		Language $language,
		UserIdentity $user
	) {
		$blocker = $block->getBlocker();

		$vals = [];
		$vals['blockid'] = $block->getId();
		$vals['blockedby'] = $blocker ? $blocker->getName() : '';
		$vals['blockedbyid'] = $blocker ? $blocker->getId() : 0;
		$vals['blockreason'] = $block->getReasonComment()
			->message->inLanguage( $language )->plain();
		$vals['blockedtimestamp'] = wfTimestamp( TS_ISO_8601, $block->getTimestamp() );
		$expiry = ApiResult::formatExpiry( $block->getExpiry(), 'infinite' );
		$vals['blockexpiry'] = $expiry;
		$vals['blockpartial'] = !$block->isSitewide();
		$vals['blocknocreate'] = $block->isCreateAccountBlocked();
		$vals['blockanononly'] = !$block->isHardblock();
		if ( $block instanceof AbstractBlock ) {
			$vals['blockemail'] = $block->isEmailBlocked();
			$vals['blockowntalk'] = !$block->isUsertalkEditAllowed();
		}

		// Formatted timestamps
		$vals['blockedtimestampformatted'] = $language->formatExpiry(
			$block->getTimestamp(), true, 'infinity', $user
		);
		if ( $expiry !== 'infinite' ) {
			$vals['blockexpiryformatted'] = $language->formatExpiry(
				$expiry, true, 'infinity', $user
			);
			$vals['blockexpiryrelative'] = $language->getHumanTimestamp(
				new MWTimestamp( $expiry ), new MWTimestamp(), $user
			);
		}

		if ( $block instanceof SystemBlock ) {
			$vals['systemblocktype'] = $block->getSystemBlockType();
		}

		if ( $block instanceof CompositeBlock ) {
			$components = [];
			foreach ( $block->getOriginalBlocks() as $singleBlock ) {
				$components[] = $this->getBlockDetails( $singleBlock, $language, $user );
			}
			$vals['blockcomponents'] = $components;
		}

		return $vals;
	}

	/**
	 * Get the API error code, to be used in ApiMessage::create or ApiBase::dieWithError
	 * @param Block $block
	 * @return string
	 */
	public function getBlockCode( Block $block ): string {
		if ( $block instanceof DatabaseBlock && $block->getType() === Block::TYPE_AUTO ) {
			return 'autoblocked';
		}
		return 'blocked';
	}

}
