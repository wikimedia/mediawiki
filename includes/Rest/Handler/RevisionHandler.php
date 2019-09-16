<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use RequestContext;
use User;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Rest\Response;

/**
 * Handler class for Core REST API endpoints that perform operations on revisions
 */
class RevisionHandler extends SimpleHandler {
	/** @var RevisionLookup */
	private $revisionLookup;

	/** @var User */
	private $user;

	/**
	 * @param RevisionLookup $revisionLookup
	 */
	public function __construct( RevisionLookup $revisionLookup ) {
		$this->revisionLookup = $revisionLookup;

		// @todo Inject this, when there is a good way to do that
		$this->user = RequestContext::getMain()->getUser();
	}

	/**
	 * @param int $id
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run( $id ) {
		$rev = $this->revisionLookup->getRevisionById( $id );
		if ( !$rev ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-revision-nonexistent', [ $id ] ), 404 );
		}

		$responseData = [
			'id' => $rev->getId(),
			'page' => [
				'id' => $rev->getPageId(),
				'title' => $rev->getPageAsLinkTarget()->getText(),
			],
			'timestamp' => wfTimestamp( TS_ISO_8601, $rev->getTimestamp() ),
		];

		$revUser = $rev->getUser( RevisionRecord::FOR_THIS_USER, $this->user );
		if ( $revUser ) {
			$responseData['user'] = [
				'id' => $revUser->isRegistered() ? $revUser->getId() : null,
				'name' => $revUser->getName()
			];
		} else {
			$responseData['user'] = null;
		}

		$comment = $rev->getComment( RevisionRecord::FOR_THIS_USER, $this->user );
		if ( $comment && $comment->text !== '' ) {
			$responseData['comment'] = $comment->text;
		} else {
			$responseData['comment'] = null;
		}

		return $this->getResponseFactory()->createJson( $responseData );
	}

	public function needsWriteAccess() {
		return false;
	}

	public function getParamSettings() {
		return [
			'id' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_REQUIRED => true,
			],
		];
	}
}
