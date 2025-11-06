<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Status\Status;

class MailingListSubscribeTask extends Task {
	/** @var HttpRequestFactory */
	private $http;

	/**
	 * URL to mediawiki-announce list summary page
	 */
	private const MEDIAWIKI_ANNOUNCE_URL =
		'https://lists.wikimedia.org/postorius/lists/mediawiki-announce.lists.wikimedia.org/';

	/** @inheritDoc */
	public function getName() {
		return 'subscribe';
	}

	/** @inheritDoc */
	public function getDependencies() {
		return [ 'services', 'tables' ];
	}

	public function isSkipped(): bool {
		return !$this->getOption( 'Subscribe' ) || !$this->getOption( 'AdminEmail' );
	}

	public function execute(): Status {
		$this->initServices( $this->getServices() );
		$status = Status::newGood();
		if ( !$this->http->canMakeRequests() ) {
			$status->warning( 'config-install-subscribe-fail',
				wfMessage( 'config-install-subscribe-notpossible' ) );
			return $status;
		}

		// Create subscription request
		$params = [ 'email' => $this->getOption( 'AdminEmail' ) ];
		$req = $this->http->create( self::MEDIAWIKI_ANNOUNCE_URL . 'anonymous_subscribe',
			[ 'method' => 'POST', 'postData' => $params ], __METHOD__ );

		// Add headers needed to pass Django's CSRF checks
		$token = str_repeat( 'a', 64 );
		$req->setHeader( 'Referer', self::MEDIAWIKI_ANNOUNCE_URL );
		$req->setHeader( 'Cookie', "csrftoken=$token" );
		$req->setHeader( 'X-CSRFToken', $token );

		// Send subscription request
		$reqStatus = $req->execute();
		if ( !$reqStatus->isOK() ) {
			$status->warning( 'config-install-subscribe-fail',
				Status::wrap( $reqStatus )->getMessage() );
			return $status;
		}

		// Was the request submitted successfully?
		// The status message is displayed after a redirect, using Django's messages
		// framework, so load the list summary page and look for the expected text.
		// (Though parsing the cookie set by the framework may be possible, it isn't
		// simple, since the format of the cookie has changed between versions.)
		$checkReq = $this->http->create( self::MEDIAWIKI_ANNOUNCE_URL, [], __METHOD__ );
		$checkReq->setCookieJar( $req->getCookieJar() );
		if ( !$checkReq->execute()->isOK() ) {
			$status->warning( 'config-install-subscribe-possiblefail' );
			return $status;
		}
		$html = $checkReq->getContent();
		if ( str_contains( $html, 'Please check your inbox for further instructions' ) ) {
			// Success
		} elseif ( str_contains( $html, 'Member already subscribed' ) ) {
			$status->warning( 'config-install-subscribe-alreadysubscribed' );
		} elseif ( str_contains( $html, 'Subscription request already pending' ) ) {
			$status->warning( 'config-install-subscribe-alreadypending' );
		} else {
			$status->warning( 'config-install-subscribe-possiblefail' );
		}
		return $status;
	}

	private function initServices( MediaWikiServices $services ) {
		$this->http = $services->getHttpRequestFactory();
	}
}
