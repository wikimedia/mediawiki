<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Block;

use MediaWiki\Api\ApiBlockInfoHelper;
use MediaWiki\Api\ApiMessage;
use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Language\Language;
use MediaWiki\Language\LocalizationContext;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Message\Message;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityUtils;

/**
 * A service class for getting formatted information about a block.
 * To obtain an instance, use MediaWikiServices::getInstance()->getBlockErrorFormatter().
 *
 * @since 1.35
 */
class BlockErrorFormatter {

	private TitleFormatter $titleFormatter;
	private HookRunner $hookRunner;
	private UserIdentityUtils $userIdentityUtils;
	private LocalizationContext $uiContext;
	private LanguageFactory $languageFactory;

	public function __construct(
		TitleFormatter $titleFormatter,
		HookContainer $hookContainer,
		UserIdentityUtils $userIdentityUtils,
		LanguageFactory $languageFactory,
		LocalizationContext $uiContext
	) {
		$this->titleFormatter = $titleFormatter;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->userIdentityUtils = $userIdentityUtils;

		$this->languageFactory = $languageFactory;
		$this->uiContext = $uiContext;
	}

	private function getLanguage(): Language {
		return $this->languageFactory->getLanguage( $this->uiContext->getLanguageCode() );
	}

	/**
	 * Get a block error message. Different message keys are chosen depending on the
	 * block features. Message parameters are formatted for the specified user and
	 * language. The message includes machine-readable data for API error responses.
	 *
	 * If passed a CompositeBlock, will get a generic message stating that there are
	 * multiple blocks. To get all the block messages, use getMessages instead.
	 *
	 * @param Block $block
	 * @param UserIdentity $user
	 * @param mixed $language Unused since 1.42
	 * @param string $ip
	 * @return ApiMessage
	 */
	public function getMessage(
		Block $block,
		UserIdentity $user,
		$language,
		string $ip
	): Message {
		$key = $this->getBlockErrorMessageKey( $block, $user );
		$params = $this->getBlockErrorMessageParams( $block, $user, $ip );
		$apiHelper = new ApiBlockInfoHelper;

		// @phan-suppress-next-line PhanTypeMismatchReturnSuperType
		return ApiMessage::create(
			$this->uiContext->msg( $key, $params ),
			$apiHelper->getBlockCode( $block ),
			[ 'blockinfo' => $apiHelper->getBlockDetails( $block, $this->getLanguage(), $user ) ]
		);
	}

	/**
	 * Get block error messages for all of the blocks that apply to a user.
	 *
	 * @since 1.42
	 * @param Block $block
	 * @param UserIdentity $user
	 * @param string $ip
	 * @return Message[]
	 */
	public function getMessages(
		Block $block,
		UserIdentity $user,
		string $ip
	): array {
		$messages = [];
		foreach ( $block->toArray() as $singleBlock ) {
			$messages[] = $this->getMessage( $singleBlock, $user, null, $ip );
		}

		return $messages;
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
	 *  - talkPageDisabled: True if talk page access is prevented by the block
	 *  - emailDisabled: True if email access is prevented by the block
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
			'talkPageDisabled' => $block->getTargetUserIdentity() ? $block->appliesToUsertalk() : false,
			'emailDisabled' => $block->isEmailBlocked(),
		];
	}

	/**
	 * Get a standard set of block details for building a block error message,
	 * formatted for a specified user and language.
	 *
	 * @since 1.35
	 * @param Block $block
	 * @param UserIdentity $user
	 * @return mixed[] See getBlockErrorInfo
	 */
	private function getFormattedBlockErrorInfo(
		Block $block,
		UserIdentity $user
	) {
		$info = $this->getBlockErrorInfo( $block );

		$language = $this->getLanguage();

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
	 * @return string Link to the blocker's page; blocker's name if not a local user
	 */
	private function formatBlockerLink( ?UserIdentity $blocker ) {
		if ( !$blocker ) {
			// TODO should we say something? This is just matching the code before
			// the refactoring in late July 2021
			return '';
		}

		$language = $this->getLanguage();

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
		$isTempUser = $this->userIdentityUtils->isTemp( $user );
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
	 *  - talkPageDisabled: True if talk page access is prevented by the block
	 *  - emailDisabled: True if email access is prevented by the block
	 */
	private function getBlockErrorMessageParams(
		Block $block,
		UserIdentity $user,
		string $ip
	) {
		$info = $this->getFormattedBlockErrorInfo( $block, $user );

		// Add params that are specific to the standard block errors
		$info['ip'] = $ip;
		$info['blockerLink'] = $this->formatBlockerLink( $block->getBlocker() );

		// Display the CompositeBlock identifier as a message containing relevant block IDs
		if ( $block instanceof CompositeBlock ) {
			$ids = $this->getLanguage()->commaList( array_map(
				static function ( $id ) {
					return '#' . $id;
				},
				array_filter( $info['identifier'], 'is_int' )
			) );
			if ( $ids === '' ) {
				$idsMsg = $this->uiContext->msg( 'blockedtext-composite-no-ids', [] );
			} else {
				$idsMsg = $this->uiContext->msg( 'blockedtext-composite-ids', [ $ids ] );
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
			'talkPageDisabled',
			'emailDisabled',
		];

		$params = [];
		foreach ( $order as $item ) {
			$params[] = $info[$item];
		}

		return $params;
	}

}
