<?php

namespace MediaWiki\Rest\Handler;

use Config;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SuppressedDataException;
use TextContent;
use Title;
use TitleFactory;
use TitleFormatter;
use User;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * @since 1.36
 */
class PageContentHelper {
	private const MAX_AGE_200 = 5;

	/** @var Config */
	protected $config;

	/** @var PermissionManager */
	protected $permissionManager;

	/** @var RevisionLookup */
	protected $revisionLookup;

	/** @var TitleFormatter */
	protected $titleFormatter;

	/** @var TitleFactory */
	private $titleFactory;

	/** @var User|null */
	protected $user = null;

	/** @var string[] */
	private $parameters = null;

	/** @var RevisionRecord|bool|null */
	private $latestRevision = null;

	/** @var Title|bool|null */
	private $titleObject = null;

	/**
	 * @param Config $config
	 * @param PermissionManager $permissionManager
	 * @param RevisionLookup $revisionLookup
	 * @param TitleFormatter $titleFormatter
	 * @param TitleFactory $titleFactory
	 */
	public function __construct(
		Config $config,
		PermissionManager $permissionManager,
		RevisionLookup $revisionLookup,
		TitleFormatter $titleFormatter,
		TitleFactory $titleFactory
	) {
		$this->config = $config;
		$this->permissionManager = $permissionManager;
		$this->revisionLookup = $revisionLookup;
		$this->titleFormatter = $titleFormatter;
		$this->titleFactory = $titleFactory;
	}

	/**
	 * @param User $user
	 * @param string[] $parameters validated parameters
	 */
	public function init( User $user, array $parameters ) {
		$this->user = $user;
		$this->parameters = $parameters;
	}

	/**
	 * @return string|null Title text or null if unable to retrieve title
	 */
	public function getTitleText(): ?string {
		return $this->parameters['title'] ?? null;
	}

	/**
	 * @return Title|bool Title or false if unable to retrieve title
	 */
	public function getTitle() {
		if ( $this->titleObject === null ) {
			$titleText = $this->getTitleText();
			$this->titleObject =
				$this->titleFactory->newFromText( $titleText ) ?? false;
		}
		return $this->titleObject;
	}

	/**
	 * @return RevisionRecord|bool latest revision or false if unable to retrieve revision
	 */
	public function getTargetRevision() {
		if ( $this->latestRevision === null ) {
			$title = $this->getTitle();
			if ( $title && $title->getArticleID() ) {
				$this->latestRevision = $this->revisionLookup->getRevisionByTitle( $title );
			} else {
				$this->latestRevision = false;
			}
		}
		return $this->latestRevision;
	}

	// Default to main slot
	public function getRole(): string {
		return SlotRecord::MAIN;
	}

	/**
	 * @return TextContent
	 * @throws LocalizedHttpException slot content is not TextContent or Revision/Slot is inaccessible
	 */
	public function getPageContent(): TextContent {
		$revision = $this->getTargetRevision();

		if ( !$revision ) {
			$titleText = $this->getTitleText() ?? '';
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-no-revision' )->plaintextParams( $titleText ),
				404
			);
		}

		$slotRole = $this->getRole();

		try {
			$content = $revision
				->getSlot( $slotRole, RevisionRecord::FOR_THIS_USER, $this->user )
				->getContent()
				->convert( CONTENT_MODEL_TEXT );
			if ( !( $content instanceof TextContent ) ) {
				throw new LocalizedHttpException( MessageValue::new( 'rest-page-source-type-error' ), 400 );
			}
		} catch ( SuppressedDataException $e ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-permission-denied-revision' )->numParams( $revision->getId() ),
				403
			);
		} catch ( RevisionAccessException $e ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-nonexistent-revision' )->numParams( $revision->getId() ),
				404
			);
		}
		return $content;
	}

	/**
	 * @return bool
	 */
	public function isAccessible(): bool {
		$title = $this->getTitle();
		return $title && $title->getArticleID()
			&& $this->permissionManager->userCan( 'read', $this->user, $title );
	}

	/**
	 * Returns an ETag representing a page's source. The ETag assumes a page's source has changed
	 * if the latest revision of a page has been made private, un-readable for another reason,
	 * or a newer revision exists.
	 * @return string|null
	 */
	public function getETag(): ?string {
		$revision = $this->getTargetRevision();
		$latestRevision = $revision ? $revision->getId() : 'e0';

		$isAccessible = $this->isAccessible();
		$accessibleTag = $isAccessible ? 'a1' : 'a0';

		$revisionTag = $latestRevision . $accessibleTag;
		return '"' . sha1( "$revisionTag" ) . '"';
	}

	/**
	 * @return string|null
	 */
	public function getLastModified(): ?string {
		if ( !$this->isAccessible() ) {
			return null;
		}

		$revision = $this->getTargetRevision();
		if ( $revision ) {
			return $revision->getTimestamp();
		}
		return null;
	}

	/**
	 * @return bool
	 */
	public function hasContent(): bool {
		$title = $this->getTitle();
		return $title && $title->getArticleID();
	}

	/**
	 * @return array
	 */
	public function constructMetadata(): array {
		$titleObject = $this->getTitle();
		$revision = $this->getTargetRevision();
		return [
			'id' => $titleObject->getArticleID(),
			'key' => $this->titleFormatter->getPrefixedDBkey( $titleObject ),
			'title' => $this->titleFormatter->getPrefixedText( $titleObject ),
			'latest' => [
				'id' => $revision->getId(),
				'timestamp' => wfTimestampOrNull( TS_ISO_8601, $revision->getTimestamp() )
			],
			'content_model' => $titleObject->getContentModel(),
			'license' => [
				'url' => $this->config->get( 'RightsUrl' ),
				'title' => $this->config->get( 'RightsText' )
			],
		];
	}

	/**
	 * @return array[]
	 */
	public function getParamSettings(): array {
		return [
			'title' => [
				Handler::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
			],
		];
	}

	/**
	 * Sets the 'Cache-Control' header no more then provided $expiry.
	 * @param ResponseInterface $response
	 * @param int|null $expiry
	 */
	public function setCacheControl( ResponseInterface $response, int $expiry = null ) {
		if ( $expiry === null ) {
			$maxAge = self::MAX_AGE_200;
		} else {
			$maxAge = min( self::MAX_AGE_200, $expiry );
		}
		$response->setHeader(
			'Cache-Control',
			'max-age=' . $maxAge
		);
	}

	/**
	 * @throws LocalizedHttpException if the content is not accessible
	 */
	public function checkAccess() {
		$titleText = $this->getTitleText() ?? '';

		if ( !$this->hasContent() ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-nonexistent-title' )->plaintextParams( $titleText ),
				404
			);
		}

		if ( !$this->isAccessible() ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-permission-denied-title' )->plaintextParams( $titleText ),
				403
			);
		}

		$revision = $this->getTargetRevision();
		if ( !$revision ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-no-revision' )->plaintextParams( $titleText ),
				404
			);
		}
	}

}
