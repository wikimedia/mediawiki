<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Rest\Handler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\Revision\ContributionsLookup;
use MediaWiki\Revision\ContributionsSegment;
use RequestContext;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * @since 1.35
 */
class UserContributionsHandler extends Handler {

	/**
	 * @var ContributionsLookup
	 */
	private $contributionsLookup;

	/** Hard limit results to 20 revisions */
	private const MAX_LIMIT = 20;

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
		$limit = $this->getValidatedParams()['limit'];
		$segment = $this->getValidatedParams()['segment'];
		$contributionsSegment =
			$this->contributionsLookup->getContributions( $user, $limit, $user, $segment );

		$revisions = $this->getRevisionsList( $contributionsSegment );
		$urls = $this->constructURLs( $contributionsSegment );

		$response = $urls + [ 'revisions' => $revisions ];

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

	/**
	 * @param ContributionsSegment $segment
	 *
	 * @return string[]
	 */
	private function constructURLs( ContributionsSegment $segment ) {
		$limit = $this->getValidatedParams()['limit'];
		$urls = [];

		if ( $segment->isOldest() ) {
			$urls['older'] = null;
		} else {
			$query = [ 'limit' => $limit, 'segment' => $segment->getBefore() ];
			$urls['older'] = $this->getRouteUrl( [], $query );
		}

		// FIXME: Without this if/else Router::getRouteUrl() performs differently in the tests
		// FIXME: than the actual implementation. Need to fix HandlerTestTrait::getRouteUrl first.
		if ( !count( $segment->getRevisions() ) ) {
			$query = [ 'limit' => $limit ];
		} else {
			$query = [ 'limit' => $limit, 'segment' => $segment->getAfter() ];
		}
		$urls['newer'] = $this->getRouteUrl( [], $query );

		$query = [ 'limit' => $limit ];
		$urls['latest'] = $this->getRouteUrl( [], $query );
		return $urls;
	}

	public function getParamSettings() {
		return [
			'limit' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_DEFAULT => self::MAX_LIMIT,
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => self::MAX_LIMIT,
			],
			'segment' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_DEFAULT => ''
			],
		];
	}

}
