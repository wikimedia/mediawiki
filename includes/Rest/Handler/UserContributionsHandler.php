<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Rest\Handler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\Revision\ContributionsLookup;
use MediaWiki\Revision\ContributionsSegment;
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

		// can make it segment_size per ticket, but wanted to be consistent with search endpoint.
		$limit = 2;
		$segment = '';
		$contributionsSegment = $this->contributionsLookup->getRevisionsByUser( $user, $limit, $segment );

		$revisions = $this->getRevisionsList( $contributionsSegment );

		$response = [ 'revisions' => $revisions ];

		return $response;
	}

	/**
	 * Returns list of revisions
	 *
	 * @param ContributionsSegment $segment
	 *
	 * @return array[]
	 */
	private function getRevisionsList( ContributionsSegment $segment ) : array {
		$revisionsData = [];

		foreach ( $segment->getRevisions() as $revision ) {
			$revisionsData[] = [
				"id" => $revision->getId(),
				"comment" => $revision->getComment()->text,
				"timestamp" => wfTimestamp( TS_ISO_8601, $revision->getTimestamp() ),
				"size" => $revision->getSize(),
				"page" => [
					"id" => $revision->getPageId(),
					"key" => $revision->getPageAsLinkTarget()->getDBkey(),
					"title" => $revision->getPageAsLinkTarget()->getText()
				]
			];
		}
		return $revisionsData;
	}

}
