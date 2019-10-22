<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\StringStream;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SuppressedDataException;
use RequestContext;
use TextContent;
use User;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;

class CompareHandler extends Handler {
	/** @var RevisionLookup */
	private $revisionLookup;

	/** @var PermissionManager */
	private $permissionManager;

	/** @var User */
	private $user;

	/** @var RevisionRecord[] */
	private $revisions = [];

	public function __construct(
		RevisionLookup $revisionLookup,
		PermissionManager $permissionManager
	) {
		$this->revisionLookup = $revisionLookup;
		$this->permissionManager = $permissionManager;

		// @todo Inject this, when there is a good way to do that
		$this->user = RequestContext::getMain()->getUser();
	}

	public function execute() {
		$fromRev = $this->getRevisionOrThrow( 'from' );
		$toRev = $this->getRevisionOrThrow( 'to' );

		if ( $fromRev->getPageId() !== $toRev->getPageId() ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-compare-page-mismatch' ), 400 );
		}

		if ( !$this->permissionManager->userCan( 'read', $this->user,
			$toRev->getPageAsLinkTarget() )
		) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-compare-permission-denied' ), 403 );
		}

		$data = [
			'response' => [
				'from' => [
					'id' => $fromRev->getId(),
					'slot_role' => $this->getRole(),
				],
				'to' => [
					'id' => $toRev->getId(),
					'slot_role' => $this->getRole()
				],
				'diff' => [ 'PLACEHOLDER' => null ]
			]
		];
		$rf = $this->getResponseFactory();
		$wrapperJson = $rf->encodeJson( $data );
		$diff = $this->getJsonDiff();
		$response = $rf->create();
		$response->setHeader( 'Content-Type', 'application/json' );

		$response->setBody( new StringStream(
			str_replace( '{"PLACEHOLDER":null}', $diff, $wrapperJson ) ) );
		return $response;
	}

	/**
	 * @param string $paramName
	 * @return RevisionRecord|null
	 */
	private function getRevision( $paramName ) {
		if ( !isset( $this->revisions[$paramName] ) ) {
			$this->revisions[$paramName] =
				// @phan-suppress-next-line PhanTypeArraySuspiciousNullable T235355
				$this->revisionLookup->getRevisionById( $this->getValidatedParams()[$paramName] );
		}
		return $this->revisions[$paramName];
	}

	/**
	 * @param string $paramName
	 * @return RevisionRecord
	 * @throws LocalizedHttpException
	 */
	private function getRevisionOrThrow( $paramName ) {
		$rev = $this->getRevision( $paramName );
		if ( !$rev ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-compare-nonexistent', [ $paramName ] ), 404 );
		}

		if ( !$this->isAccessible( $rev ) ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-compare-inaccessible', [ $paramName ] ), 403 );
		}
		return $rev;
	}

	/**
	 * @param RevisionRecord $rev
	 * @return bool
	 */
	private function isAccessible( $rev ) {
		return $rev->audienceCan(
			RevisionRecord::DELETED_TEXT,
			RevisionRecord::FOR_THIS_USER,
			$this->user
		);
	}

	private function getRole() {
		return SlotRecord::MAIN;
	}

	private function getRevisionText( $paramName ) {
		$revision = $this->getRevision( $paramName );
		try {
			$content = $revision
				->getSlot( $this->getRole(), RevisionRecord::FOR_THIS_USER, $this->user )
				->getContent()
				->convert( CONTENT_MODEL_TEXT );
			if ( $content instanceof TextContent ) {
				return $content->getText();
			} else {
				throw new LocalizedHttpException(
					new MessageValue(
						'rest-compare-wrong-content',
						[ $this->getRole(), $paramName ]
					),
					400 );
			}
		} catch ( SuppressedDataException $e ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-compare-inaccessible', [ $paramName ] ), 403 );
		} catch ( RevisionAccessException $e ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-compare-nonexistent', [ $paramName ] ), 404 );
		}
	}

	/**
	 * @return string
	 */
	private function getJsonDiff() {
		// TODO: properly implement
		// This is a prototype only. SlotDiffRenderer should be extended to support this use case.
		$fromText = $this->getRevisionText( 'from' );
		$toText = $this->getRevisionText( 'to' );
		if ( !function_exists( 'wikidiff2_inline_json_diff' ) ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-compare-wikidiff2' ), 500 );
		}
		return wikidiff2_inline_json_diff( $fromText, $toText, 2 );
	}

	public function getParamSettings() {
		return [
			'from' => [
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_REQUIRED => true,
				Handler::PARAM_SOURCE => 'path',
			],
			'to' => [
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_REQUIRED => true,
				Handler::PARAM_SOURCE => 'path',
			],
		];
	}
}
