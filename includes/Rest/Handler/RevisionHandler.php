<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use RequestContext;
use User;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Handler class for Core REST API endpoints that perform operations on revisions
 */
class RevisionHandler extends SimpleHandler {
	/** @var RevisionLookup */
	private $revisionLookup;

	/** @var PermissionManager */
	private $permissionManager;

	/** @var User */
	private $user;

	/**
	 * @var RevisionRecord|bool|null
	 */
	private $revision = null;

	/**
	 * @param RevisionLookup $revisionLookup
	 * @param PermissionManager $permissionManager
	 */
	public function __construct(
		RevisionLookup $revisionLookup,
		PermissionManager $permissionManager
	) {
		$this->revisionLookup = $revisionLookup;
		$this->permissionManager = $permissionManager;

		// @todo Inject this, when there is a good way to do that
		$this->user = RequestContext::getMain()->getUser();
	}

	/**
	 * @return RevisionRecord|bool
	 */
	private function getRevision() {
		$id = $this->getValidatedParams()['id'];

		if ( $this->revision === null ) {
			$rev = $this->revisionLookup->getRevisionById( $id );

			// If null was returned, remember it as false, since null means uninitialized.
			$this->revision = $rev ?: false;
		}

		return $this->revision;
	}

	/**
	 * @param int $id
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run( $id ) {
		$rev = $this->getRevision();
		if ( !$rev ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-nonexistent-revision', [ $id ] ), 404 );
		}
		if ( !$this->permissionManager->userCan( 'read', $this->user, $rev->getPageAsLinkTarget() ) ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-permission-denied-revision', [ $id ] ), 403 );
		}

		$responseData = [
			'id' => $rev->getId(),
			'page' => [
				'id' => $rev->getPageId(),
				'title' => $rev->getPageAsLinkTarget()->getText(),
			],
			'size' => $rev->getSize(),
			'minor' => $rev->isMinor(),
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
		$responseData['comment'] = $comment ? $comment->text : null;

		$parent = $this->revisionLookup->getPreviousRevision( $rev );
		if ( $parent ) {
			$responseData['delta'] = $rev->getSize() - $parent->getSize();
		} else {
			$responseData['delta'] = null;
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

	/**
	 * Returns an ETag representing the requested revision.
	 * If access to the revision is restricted, do not return an etag.
	 *
	 * @return string|null
	 * @throws LocalizedHttpException
	 */
	protected function getETag(): ?string {
		$rev = $this->getRevision();
		if ( !$rev || $rev->getVisibility() !== 0 ) {
			return null;
		}

		$tag = $rev->getId();

		return '"' . $tag . '"';
	}

	/**
	 * Returns the requested revision's timestamp.
	 * If access to the revision is restricted, do not return a timestamp.
	 *
	 * @return string|null
	 * @throws LocalizedHttpException
	 */
	protected function getLastModified(): ?string {
		$rev = $this->getRevision();
		if ( !$rev || $rev->getVisibility() !== 0 ) {
			return null;
		}

		return $rev->getTimestamp();
	}

	/**
	 * @return bool
	 */
	protected function hasRepresentation() {
		$rev = $this->getRevision();
		return $rev ? true : false;
	}
}
