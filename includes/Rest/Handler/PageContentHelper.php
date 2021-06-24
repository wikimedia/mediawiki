<?php

namespace MediaWiki\Rest\Handler;

use Config;
use MediaWiki\Page\ExistingPageRecord;
use MediaWiki\Page\PageLookup;
use MediaWiki\Permissions\Authority;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SuppressedDataException;
use TextContent;
use TitleFormatter;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * @internal for use by core REST infrastructure
 */
class PageContentHelper {
	private const MAX_AGE_200 = 5;

	/** @var Config */
	protected $config;

	/** @var RevisionLookup */
	protected $revisionLookup;

	/** @var TitleFormatter */
	protected $titleFormatter;

	/** @var PageLookup */
	protected $pageLookup;

	/** @var Authority|null */
	protected $authority = null;

	/** @var string[] */
	protected $parameters = null;

	/** @var RevisionRecord|false|null */
	protected $targetRevision = false;

	/** @var ExistingPageRecord|false|null */
	protected $pageRecord = false;

	/**
	 * @param Config $config
	 * @param RevisionLookup $revisionLookup
	 * @param TitleFormatter $titleFormatter
	 * @param PageLookup $pageLookup
	 */
	public function __construct(
		Config $config,
		RevisionLookup $revisionLookup,
		TitleFormatter $titleFormatter,
		PageLookup $pageLookup
	) {
		$this->config = $config;
		$this->revisionLookup = $revisionLookup;
		$this->titleFormatter = $titleFormatter;
		$this->pageLookup = $pageLookup;
	}

	/**
	 * @param Authority $authority
	 * @param string[] $parameters validated parameters
	 */
	public function init( Authority $authority, array $parameters ) {
		$this->authority = $authority;
		$this->parameters = $parameters;
	}

	/**
	 * @return string|null title text or null if unable to retrieve title
	 */
	public function getTitleText(): ?string {
		return $this->parameters['title'] ?? null;
	}

	/**
	 * @return ExistingPageRecord|null
	 */
	public function getPage(): ?ExistingPageRecord {
		if ( $this->pageRecord === false ) {
			$titleText = $this->getTitleText();
			if ( !$titleText ) {
				return null;
			}
			$this->pageRecord = $this->pageLookup->getExistingPageByText( $titleText );
		}
		return $this->pageRecord;
	}

	/**
	 * Returns the target revision. No permission checks are applied.
	 *
	 * @return RevisionRecord|null latest revision or null if unable to retrieve revision
	 */
	public function getTargetRevision(): ?RevisionRecord {
		if ( $this->targetRevision === false ) {
			$page = $this->getPage();
			if ( $page ) {
				$this->targetRevision = $this->revisionLookup->getRevisionByTitle( $page );
			} else {
				$this->targetRevision = null;
			}
		}
		return $this->targetRevision;
	}

	// Default to main slot
	public function getRole(): string {
		return SlotRecord::MAIN;
	}

	/**
	 * @return TextContent
	 * @throws LocalizedHttpException slot content is not TextContent or RevisionRecord/Slot is inaccessible
	 */
	public function getContent(): TextContent {
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
				->getSlot( $slotRole, RevisionRecord::FOR_THIS_USER, $this->authority )
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
		$page = $this->getPage();
		return $page && $this->authority->probablyCan( 'read', $page );
	}

	/**
	 * Returns an ETag representing a page's source. The ETag assumes a page's source has changed
	 * if the latest revision of a page has been made private, un-readable for another reason,
	 * or a newer revision exists.
	 * @return string|null
	 */
	public function getETag(): ?string {
		$revision = $this->getTargetRevision();
		$revId = $revision ? $revision->getId() : 'e0';

		$isAccessible = $this->isAccessible();
		$accessibleTag = $isAccessible ? 'a1' : 'a0';

		$revisionTag = $revId . $accessibleTag;
		return '"' . sha1( $revisionTag ) . '"';
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
	 * Checks whether content exists. Permission checks are not considered.
	 *
	 * @return bool
	 */
	public function hasContent(): bool {
		return (bool)$this->getPage();
	}

	/**
	 * @return array
	 */
	public function constructMetadata(): array {
		$page = $this->getPage();
		$revision = $this->getTargetRevision();
		return [
			'id' => $page->getId(),
			'key' => $this->titleFormatter->getPrefixedDBkey( $page ),
			'title' => $this->titleFormatter->getPrefixedText( $page ),
			'latest' => [
				'id' => $revision->getId(),
				'timestamp' => wfTimestampOrNull( TS_ISO_8601, $revision->getTimestamp() )
			],
			'content_model' => $this->getTargetRevision()
				->getSlot( SlotRecord::MAIN, RevisionRecord::RAW )
				->getModel(),
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

		if ( !$this->authority->authorizeRead( 'read', $this->getPage() ) ) {
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
