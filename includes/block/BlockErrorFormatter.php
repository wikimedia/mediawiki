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

use CommentStoreComment;
use Language;
use Message;
use User;

/**
 * A service class for getting formatted information about a block.
 * To obtain an instance, use MediaWikiServices::getInstance()->getBlockErrorFormatter().
 *
 * @since 1.35
 */
class BlockErrorFormatter {
	/**
	 * Get a block error message. Different message keys are chosen depending on the
	 * block features. Message paramaters are formatted for the specified user and
	 * language.
	 *
	 * @param AbstractBlock $block
	 * @param User $user
	 * @param Language $language
	 * @param string $ip
	 * @return Message
	 */
	public function getMessage(
		AbstractBlock $block,
		User $user,
		Language $language,
		$ip
	) {
		$key = $this->getBlockErrorMessageKey( $block );
		$params = $this->getBlockErrorMessageParams( $block, $user, $language, $ip );
		return new Message( $key, $params );
	}

	/**
	 * Get a standard set of block details for building a block error message.
	 *
	 * @param AbstractBlock $block
	 * @return mixed[]
	 *  - identifier: Information for looking up the block
	 *  - targetName: The target, as a string
	 *  - blockerName: The blocker, as a string
	 *  - blockerId: ID of the blocker; 0 if a foreign user
	 *  - reason: Reason for the block
	 *  - expiry: Expiry time
	 *  - timestamp: Time the block was created
	 */
	private function getBlockErrorInfo( AbstractBlock $block ) {
		return [
			'identifier' => $block->getIdentifier(),
			'targetName' => (string)$block->getTarget(),
			'blockerName' => $block->getByName(),
			'blockerId' => $block->getBy(),
			'reason' => $block->getReasonComment(),
			'expiry' => $block->getExpiry(),
			'timestamp' => $block->getTimestamp(),
		];
	}

	/**
	 * Get a standard set of block details for building a block error message,
	 * formatted for a specified user and language.
	 *
	 * @since 1.35
	 * @param AbstractBlock $block
	 * @param User $user
	 * @param Language $language
	 * @return mixed[] See getBlockErrorInfo
	 */
	private function getFormattedBlockErrorInfo(
		AbstractBlock $block,
		User $user,
		Language $language
	) {
		$info = $this->getBlockErrorInfo( $block );

		$info['expiry'] = $language->formatExpiry( $info['expiry'] );
		$info['timestamp'] = $language->userTimeAndDate( $info['timestamp'], $user );
		$info['blockerName'] = $language->embedBidi( $info['blockerName'] );
		$info['targetName'] = $language->embedBidi( $info['targetName'] );

		$info['reason'] = $this->formatBlockReason( $info['reason'], $language );

		return $info;
	}

	/**
	 * Format the block reason as plain wikitext in the specified language.
	 *
	 * @param CommentStoreComment $reason
	 * @param Language $language
	 * @return string
	 */
	private function formatBlockReason( CommentStoreComment $reason, Language $language ) {
		if ( $reason->text === '' ) {
			$message = new Message( 'blockednoreason', [], $language );
			return $message->text();
		}
		return $reason->message->inLanguage( $language )->plain();
	}

	/**
	 * Create a link to the blocker's user page. This must be done here rather than in
	 * the message translation, because the blocker may not be a local user, in which
	 * case their page cannot be linked.
	 *
	 * @param string $blockerName Formatted blocker name
	 * @param int $blockerId
	 * @param Language $language
	 * @return string Link to the blocker's page; blocker's name if not a local user
	 */
	private function formatBlockerLink( $blockerName, $blockerId, Language $language ) {
		if ( $blockerId === 0 ) {
			// Foreign user
			return $blockerName;
		}

		$blocker = User::newFromId( $blockerId );
		$blockerUserpage = $blocker->getUserPage();
		$blockerText = $language->embedBidi( $blockerUserpage->getText() );
		return "[[{$blockerUserpage->getPrefixedText()}|{$blockerText}]]";
	}

	/**
	 * Determine the block error message key by examining the block.
	 *
	 * @param AbstractBlock $block
	 * @return string Message key
	 */
	private function getBlockErrorMessageKey( AbstractBlock $block ) {
		$key = 'blockedtext';
		if ( $block instanceof DatabaseBlock ) {
			if ( $block->getType() === AbstractBlock::TYPE_AUTO ) {
				$key = 'autoblockedtext';
			} elseif ( !$block->isSitewide() ) {
				$key = 'blockedtext-partial';
			}
		} elseif ( $block instanceof SystemBlock ) {
			$key = 'systemblockedtext';
		} elseif ( $block instanceof CompositeBlock ) {
			$key = 'blockedtext-composite';
		}
		return $key;
	}

	/**
	 * Get the formatted parameters needed to build the block error messages handled by
	 * getBlockErrorMessageKey.
	 *
	 * @param AbstractBlock $block
	 * @param User $user
	 * @param Language $language
	 * @param string $ip
	 * @return mixed[] Params used by standard block error messages, in order:
	 *  - blockerLink: Link to the blocker's user page, if any; otherwise same as blockerName
	 *  - reason: Reason for the block
	 *  - ip: IP address of the user attempting to perform an action
	 *  - blockerName: The blocker, as a bidi-embedded string
	 *  - identifier: Information for looking up the block
	 *  - expiry: Expiry time, in the specified language
	 *  - targetName: The target, as a bidi-embedded string
	 *  - timestamp: Time the block was created, in the specified language
	 */
	private function getBlockErrorMessageParams(
		AbstractBlock $block,
		User $user,
		Language $language,
		$ip
	) {
		$info = $this->getFormattedBlockErrorInfo( $block, $user, $language );

		// Add params that are specific to the standard block errors
		$info['ip'] = $ip;
		$info['blockerLink'] = $this->formatBlockerLink(
			$info['blockerName'],
			$info['blockerId'],
			$language
		);

		// Display the CompositeBlock identifier as a message containing relevant block IDs
		if ( $block instanceof CompositeBlock ) {
			$ids = $language->commaList( array_map(
				function ( $id ) {
					return '#' . $id;
				},
				array_filter( $info['identifier'], 'is_int' )
			) );
			if ( $ids === '' ) {
				$idsMsg = new Message( 'blockedtext-composite-no-ids', [], $language );
			} else {
				$idsMsg = new Message( 'blockedtext-composite-ids', [ $ids ], $language );
			}
			$info['identifier'] = $idsMsg->plain();
		}

		// Messages expect the params in this order
		$order = [
			'blockerLink',
			'reason',
			'ip',
			'blockerName',
			'identifier',
			'expiry',
			'targetName',
			'timestamp',
		];

		$params = [];
		foreach ( $order as $item ) {
			$params[] = $info[$item];
		}

		return $params;
	}
}
