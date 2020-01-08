<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\SuppressedDataException;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Rest\Response;
use RequestContext;
use User;
use Config;
use TextContent;
use TitleFormatter;
use Title;
use Wikimedia\Message\ScalarParam;
use Wikimedia\Message\ParamType;

/**
 * Handler class for Core REST API Page Source endpoint
 */
class PageSourceHandler extends SimpleHandler {
	private const MAX_AGE_200 = 5;

	/** @var RevisionLookup */
	private $revisionLookup;

	/** @var TitleFormatter */
	private $titleFormatter;

	/** @var PermissionManager */
	private $permissionManager;

	/** @var User */
	private $user;

	/** @var Config */
	private $config;

	/** @var RevisionRecord|bool */
	private $revision;

	/** @var Title */
	private $titleObject;

	/**
	 * @param RevisionLookup $revisionLookup
	 * @param TitleFormatter $titleFormatter
	 * @param PermissionManager $permissionManager
	 * @param Config $config
	 */
	public function __construct(
		RevisionLookup $revisionLookup,
		TitleFormatter $titleFormatter,
		PermissionManager $permissionManager,
		Config $config
	) {
		$this->revisionLookup = $revisionLookup;
		$this->titleFormatter = $titleFormatter;
		$this->permissionManager = $permissionManager;
		$this->config = $config;

		// @todo Inject this, when there is a good way to do that
		$this->user = RequestContext::getMain()->getUser();
	}

	// Default to main slot
	private function getRole() {
		return SlotRecord::MAIN;
	}

	/**
	 * @param RevisionRecord $revision
	 * @return TextContent $content
	 * @throws LocalizedHttpException slot content is not TextContent or Revision/Slot is inaccessible
	 */
	private function getPageContent( RevisionRecord $revision ) {
		try {
			$content = $revision
				->getSlot( $this->getRole(), RevisionRecord::FOR_THIS_USER, $this->user )
				->getContent()
				->convert( CONTENT_MODEL_TEXT );
			if ( !( $content instanceof TextContent ) ) {
				throw new LocalizedHttpException( new MessageValue( 'rest-page-source-type-error' ), 400 );
			}
		} catch ( SuppressedDataException $e ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-permission-denied-revision',
					[ new ScalarParam( ParamType::NUM, $revision->getId() ) ]
				),
				403
			);
		} catch ( RevisionAccessException $e ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-nonexistent-revision',
					[ new ScalarParam( ParamType::NUM, $revision->getId() ) ]
				),
				404
			);
		}
		return $content;
	}

	private function isAccessible( $titleObject ) {
		return $this->permissionManager->userCan( 'read', $this->user, $titleObject );
	}

	/**
	 * @param string $title
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run( $title ) {
		$titleObject = $this->getTitle();
		if ( !$titleObject || !$titleObject->getArticleID() ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-nonexistent-title',
					[ new ScalarParam( ParamType::TEXT, $title ) ]
				),
				404
			);
		}
		if ( !$this->isAccessible( $titleObject ) ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-permission-denied-title',
					[ new ScalarParam( ParamType::TEXT, $title ) ]
				),
				403
			);
		}
		$revision = $this->getRevision();
		if ( !$revision ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-no-revision',
					[ new ScalarParam( ParamType::TEXT, $title ) ]
				),
				404
			);
		}
		$content = $this->getPageContent( $this->revision );
		$body = [
			'id' => $titleObject->getArticleID(),
			'key' => $this->titleFormatter->getPrefixedDbKey( $this->titleObject ),
			'title' => $this->titleFormatter->getPrefixedText( $this->titleObject ),
			'latest' => [
				'id' => $this->revision->getId(),
				'timestamp' => wfTimestampOrNull( TS_ISO_8601, $this->revision->getTimestamp() )
			],
			'content_model' => $content->getModel(),
			'license' => [
				'url' => $this->config->get( 'RightsUrl' ),
				'title' => $this->config->get( 'RightsText' )
			],
			'source' => $content->getText()
		];

		$response = $this->getResponseFactory()->createJson( $body );
		$response->setHeader( 'Cache-Control', 'maxage=' . self::MAX_AGE_200 );
		return $response;
	}

	public function needsWriteAccess() {
		return false;
	}

	/**
	 * @return RevisionRecord|bool latest revision or false if unable to retrieve revision
	 */
	private function getRevision() {
		if ( is_null( $this->revision ) ) {
			$title = $this->getTitle();
			if ( $title && $title->getArticleID() ) {
				$this->revision = $this->revisionLookup->getKnownCurrentRevision( $title );
			} else {
				$this->revision = false;
			}
		}
		return $this->revision;
	}

	/**
	 * @return Title|bool Title or false if unable to retrieve title
	 */
	private function getTitle() {
		if ( is_null( $this->titleObject ) ) {
			$this->titleObject = Title::newFromText( $this->getValidatedParams()['title'] ) ?? false;
		}
		return $this->titleObject;
	}

	/**
	 * Returns an ETag representing a page's source. The ETag assumes a page's source has changed
	 * if the latest revision of a page has been made private, un-readable for another reason,
	 * or a newer revision exists.
	 * @return string
	 */
	protected function getETag() {
		$revision = $this->getRevision();
		$latestRevision = $revision ? $revision->getID() : 'e0';

		$isAccessible = $this->isAccessible( $this->getTitle() );
		$accessibleTag = $isAccessible ? 'a1' : 'a0';

		$revisionTag = $latestRevision . $accessibleTag;
		return '"' . sha1( "$revisionTag" ) . '"';
	}

	/**
	 * @return string|null
	 */
	protected function getLastModified() {
		$revision = $this->getRevision();
		if ( $revision ) {
			return $this->revision->getTimestamp();
		}
	}

	public function getParamSettings() {
		return [
			'title' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
			],
		];
	}
}
