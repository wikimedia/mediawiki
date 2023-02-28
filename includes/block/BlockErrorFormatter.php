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

use Language;
use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserNameUtils;
use Message;
use TitleFormatter;

/**
 * A service class for getting formatted information about a block.
 * To obtain an instance, use MediaWikiServices::getInstance()->getBlockErrorFormatter().
 *
 * @since 1.35
 */
class BlockErrorFormatter {

	/** @var TitleFormatter */
	private $titleFormatter;

	/** @var HookRunner */
	private $hookRunner;

	/**
	 * @var UserNameUtils
	 */
	private $userNameUtils;

	/**
	 * @param TitleFormatter $titleFormatter
	 * @param HookContainer $hookContainer
	 * @param UserNameUtils $userNameUtils
	 */
	public function __construct(
		TitleFormatter $titleFormatter,
		HookContainer $hookContainer,
		UserNameUtils $userNameUtils
	) {
		$this->titleFormatter = $titleFormatter;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->userNameUtils = $userNameUtils;
	}

	/**
	 * Get a block error message. Different message keys are chosen depending on the
	 * block features. Message parameters are formatted for the specified user and
	 * language.
	 *
	 * @param Block $block
	 * @param UserIdentity $user
	 * @param Language $language
	 * @param string $ip
	 * @return Message
	 */
	public function getMessage(
		Block $block,
		UserIdentity $user,
		Language $language,
		$ip
	) {
		$key = $this->getBlockErrorMessageKey( $block, $user );
		$params = $this->getBlockErrorMessageParams( $block, $user, $language, $ip );
		return new Message( $key, $params );
	}

	/**
	 * Get a standard set of block details for building a block error message.
	 *
	 * @param Block $block
	 * @return mixed[]
	 *  - identifier: Information for looking up the block
	 *  - targetName: The target, as a string
	 *  - blockerName: The blocker, as a string
	 *  - reason: Reason for the block
	 *  - expiry: Expiry time
	 *  - timestamp: Time the block was created
	 */
	private function getBlockErrorInfo( Block $block ) {
		$blocker = $block->getBlocker();
		return [
			'identifier' => $block->getIdentifier(),
			'targetName' => $block->getTargetName(),
			'blockerName' => $blocker ? $blocker->getName() : '',
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
	 * @param Block $block
	 * @param UserIdentity $user
	 * @param Language $language
	 * @return mixed[] See getBlockErrorInfo
	 */
	private function getFormattedBlockErrorInfo(
		Block $block,
		UserIdentity $user,
		Language $language
	) {
		$info = $this->getBlockErrorInfo( $block );

		$info['expiry'] = $language->formatExpiry( $info['expiry'], true, 'infinity', $user );
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
			return $message->plain();
		}
		return $reason->message->inLanguage( $language )->plain();
	}

	/**
	 * Create a link to the blocker's user page. This must be done here rather than in
	 * the message translation, because the blocker may not be a local user, in which
	 * case their page cannot be linked.
	 *
	 * @param ?UserIdentity $blocker
	 * @param Language $language
	 * @return string Link to the blocker's page; blocker's name if not a local user
	 */
	private function formatBlockerLink( ?UserIdentity $blocker, Language $language ) {
		if ( !$blocker ) {
			// TODO should we say something? This is just matching the code before
			// the refactoring in late July 2021
			return '';
		}

		if ( $blocker->getId() === 0 ) {
			// Foreign user
			// TODO what about blocks placed by IPs? Shouldn't we check based on
			// $blocker's wiki instead? This is just matching the code before the
			// refactoring in late July 2021.
			return $language->embedBidi( $blocker->getName() );
		}

		$blockerUserpage = PageReferenceValue::localReference( NS_USER, $blocker->getName() );
		$blockerText = $language->embedBidi(
			$this->titleFormatter->getText( $blockerUserpage )
		);
		$prefixedText = $this->titleFormatter->getPrefixedText( $blockerUserpage );
		return "[[{$prefixedText}|{$blockerText}]]";
	}

	/**
	 * Determine the block error message key by examining the block.
	 *
	 * @param Block $block
	 * @param UserIdentity $user
	 * @return string Message key
	 */
	private function getBlockErrorMessageKey( Block $block, UserIdentity $user ) {
		$isTempUser = $this->userNameUtils->isTemp( $user->getName() );
		$key = $isTempUser ? 'blockedtext-tempuser' : 'blockedtext';
		if ( $block instanceof DatabaseBlock ) {
			if ( $block->getType() === Block::TYPE_AUTO ) {
				$key = $isTempUser ? 'autoblockedtext-tempuser' : 'autoblockedtext';
			} elseif ( !$block->isSitewide() ) {
				$key = 'blockedtext-partial';
			}
		} elseif ( $block instanceof SystemBlock ) {
			$key = 'systemblockedtext';
		} elseif ( $block instanceof CompositeBlock ) {
			$key = 'blockedtext-composite';
		}

		// Allow extensions to modify the block error message
		$this->hookRunner->onGetBlockErrorMessageKey( $block, $key );

		return $key;
	}

	/**
	 * Get the formatted parameters needed to build the block error messages handled by
	 * getBlockErrorMessageKey.
	 *
	 * @param Block $block
	 * @param UserIdentity $user
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
		Block $block,
		UserIdentity $user,
		Language $language,
		$ip
	) {
		$info = $this->getFormattedBlockErrorInfo( $block, $user, $language );

		// Add params that are specific to the standard block errors
		$info['ip'] = $ip;
		$info['blockerLink'] = $this->formatBlockerLink(
			$block->getBlocker(),
			$language
		);

		// Display the CompositeBlock identifier as a message containing relevant block IDs
		if ( $block instanceof CompositeBlock ) {
			$ids = $language->commaList( array_map(
				static function ( $id ) {
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
