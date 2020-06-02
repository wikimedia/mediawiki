<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Rest\Handler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\Revision\ContributionsLookup;
use RequestContext;
use Wikimedia\Message\MessageValue;

/**
 * @since 1.35
 */
class UserContributionsHandler extends Handler {

	/**
	 * @var ContributionsLookup
	 */
	private $contributionsLookup;

	public function __construct( ContributionsLookup $contributionsLookup ) {
		$this->contributionsLookup = $contributionsLookup;
	}

	/**
	 * @return array|ResponseInterface
	 * @throws LocalizedHttpException
	 */
	public function execute() {
		// TODO: Implement execute() method.
		$user = RequestContext::getMain()->getUser();
		if ( $user->isAnon() ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-permission-denied-anon' ), 401
			);
		}
		return []; // TODO: Remove this, put in just for CI to pass
	}
}
