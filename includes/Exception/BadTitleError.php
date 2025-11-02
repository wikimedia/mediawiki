<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Exception;

use MediaWiki\Message\Message;
use MediaWiki\Title\MalformedTitleException;

/**
 * Show an error page on a badtitle.
 *
 * We always emit a HTTP 404 error code since pages with an invalid title will
 * never have any content. In the past this emitted a 400 error code to ensure
 * caching proxies and mobile browsers don't cache it as valid content (T35646),
 * but that had the disadvantage of telling caches in front of MediaWiki
 * (Varnish, etc.), not to cache it either.
 *
 * @newable
 * @since 1.19
 * @ingroup Exception
 */
class BadTitleError extends ErrorPageError {
	/**
	 * @stable to call
	 *
	 * @param string|Message|MalformedTitleException $msg A message key (default: 'badtitletext'), or
	 *     a MalformedTitleException to figure out things from
	 * @param array $params Parameter to wfMessage()
	 */
	public function __construct( $msg = 'badtitletext', $params = [] ) {
		if ( $msg instanceof MalformedTitleException ) {
			$errorMessage = $msg->getErrorMessage();
			if ( !$errorMessage ) {
				parent::__construct( 'badtitle', 'badtitletext', [] );
			} else {
				$errorMessageParams = $msg->getErrorMessageParameters();
				parent::__construct( 'badtitle', $errorMessage, $errorMessageParams );
			}
		} else {
			parent::__construct( 'badtitle', $msg, $params );
		}
	}

	/**
	 * @inheritDoc
	 */
	public function report( $action = self::SEND_OUTPUT ) {
		global $wgOut;

		$wgOut->setStatusCode( 404 );

		parent::report( self::STAGE_OUTPUT );

		// Unconditionally cache the error for an hour, see T316932
		$wgOut->enableClientCache();
		$wgOut->setCdnMaxage( 3600 );

		if ( $action === self::SEND_OUTPUT ) {
			$wgOut->output();
		}
	}
}

/** @deprecated class alias since 1.44 */
class_alias( BadTitleError::class, 'BadTitleError' );
