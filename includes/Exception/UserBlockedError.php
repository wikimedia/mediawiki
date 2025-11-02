<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Exception;

use MediaWiki\Block\Block;
use MediaWiki\Context\RequestContext;
use MediaWiki\Language\RawMessage;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserIdentity;

/**
 * Show an error when the user tries to do something whilst blocked.
 *
 * @newable
 * @since 1.18
 * @ingroup Exception
 */
class UserBlockedError extends ErrorPageError {
	/**
	 * @stable to call
	 * @param Block $block
	 * @param UserIdentity|null $user
	 * @param mixed $language Unused since 1.42
	 * @param string|null $ip
	 */
	public function __construct(
		Block $block,
		?UserIdentity $user = null,
		$language = null,
		$ip = null
	) {
		$context = RequestContext::getMain();
		if ( $user === null || $ip === null ) {
			// If any of these are not passed in, use the global context
			$user = $context->getUser();
			$ip = $context->getRequest()->getIP();
		}

		// @todo This should be passed in via the constructor
		$messages = MediaWikiServices::getInstance()->getFormatterFactory()
			->getBlockErrorFormatter( $context )
			->getMessages( $block, $user, $ip );

		if ( count( $messages ) === 1 ) {
			$message = $messages[0];
		} else {
			$message = new RawMessage( '* $' . implode( "\n* \$", range( 1, count( $messages ) ) ) );
			$message->params( $messages )->parse();
		}

		parent::__construct( 'blockedtitle', $message );
	}
}

/** @deprecated class alias since 1.44 */
class_alias( UserBlockedError::class, 'UserBlockedError' );
