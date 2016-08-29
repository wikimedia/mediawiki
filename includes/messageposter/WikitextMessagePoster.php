<?php

namespace MediaWiki\MessagePoster;

use ApiMain;
use DerivativeRequest;
use RequestContext;
use Status;
use User;

/**
 * This is an implementation of IMessagePoster for wikitext talk pages.
 */
class WikitextMessagePoster implements IMessagePoster {
	/**
	 * @var Title Title to post to
	 */
	protected $title;

	/**
	 * @param Title $title Wikitext page in a talk namespace, to post to
	 */
	public function __construct( $title ) {
		$this->title = $title;
	}

	public function post( User $user, $subject, $body, $bot = false ) {
		$status = Status::newGood();

		// Add signature if needed
		if ( strpos( $body, '~~~' ) === false ) {
			$body .= "\n\n~~~~";
		}

		// Based on code from MassMessage (which was based on
		// TranslationNotifications)

		global $wgUser, $wgRequest;

		$oldRequest = $wgRequest;
		$oldUser = $wgUser;

		$params = [
			'action' => 'edit',
			'title' => $this->title->getPrefixedText(),
			'section' => 'new',
			'summary' => $subject,
			'text' => $body,
			'notminor' => true,
			'token' => $user->getEditToken(),
			'redirect' => true,
		];

		if ( $bot ) {
			$params['bot'] = true;
		}

		$wgRequest = new DerivativeRequest(
			$wgRequest,
			$params,
			true // was posted?
		);
		// New user objects will use $wgRequest, so we set that
		// to our DerivativeRequest, so we don't run into any issues
		$wgUser = $user;
		$wgUser->clearInstanceCache(); // Force rights reload (for IP block exemption)

		$context = RequestContext::getMain();
		// All further internal API requests will use the main
		// RequestContext, so setting it here will fix it for
		// all other internal uses, like how LQT does
		$oldCUser = $context->getUser();
		$oldCRequest = $context->getRequest();
		$context->setUser( $wgUser );
		$context->setRequest( $wgRequest );

		$api = new ApiMain(
			$wgRequest,
			true // enable write?
		);

		try {
			$api->execute();
		} finally {
			// Cleanup all the stuff we polluted
			$context->setUser( $oldCUser );
			$context->setRequest( $oldCRequest );
			$wgUser = $oldUser;
			$wgRequest = $oldRequest;
		}
	}
}
