<?php

namespace MediaWiki\Rest\Handler\Helper;

use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\TextContent;
use MediaWiki\Content\WikitextContent;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\Page\ExistingPageRecord;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageLookup;
use MediaWiki\Permissions\Authority;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SuppressedDataException;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use MediaWiki\Title\TitleFormatter;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * @internal for use by core REST infrastructure
 */
class PageContentHelper {

	/**
	 * The maximum cache duration for page content.
	 *
	 * If this is set to a value higher than about 60 seconds, active purging
	 * will have to be employed to make sure clients do not receive overly stale
	 * content. This is especially important to avoid distributing vandalized
	 * content for too long.
	 *
	 * Active purging can be enabled by adding the relevant URLs to
	 * HTMLCacheUpdater. See T365630 for more discussion.
	 */
	private const MAX_AGE_200 = 5;

	/**
	 * @internal
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::RightsUrl,
		MainConfigNames::RightsText,
	];

	protected ServiceOptions $options;
	protected RevisionLookup $revisionLookup;
	protected TitleFormatter $titleFormatter;
	protected PageLookup $pageLookup;
	private TitleFactory $titleFactory;
	private IConnectionProvider $dbProvider;
	private ChangeTagsStore $changeTagsStore;

	/** @var Authority|null */
	protected $authority = null;

	/** @var string[] */
	protected $parameters = null;

	/** @var RevisionRecord|false|null */
	protected $targetRevision = false;

	/** @var ExistingPageRecord|false|null */
	protected $pageRecord = false;

	/** @var PageIdentity|false|null */
	private $pageIdentity = false;

	public function __construct(
		ServiceOptions $options,
		RevisionLookup $revisionLookup,
		TitleFormatter $titleFormatter,
		PageLookup $pageLookup,
		TitleFactory $titleFactory,
		IConnectionProvider $dbProvider,
		ChangeTagsStore $changeTagsStore
	) {
		$this->options = $options;
		$this->revisionLookup = $revisionLookup;
		$this->titleFormatter = $titleFormatter;
		$this->pageLookup = $pageLookup;
		$this->titleFactory = $titleFactory;
		$this->dbProvider = $dbProvider;
		$this->changeTagsStore = $changeTagsStore;
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

	public function getPage(): ?ExistingPageRecord {
		if ( $this->pageRecord === false ) {
			$titleText = $this->getTitleText();
			if ( $titleText === null ) {
				return null;
			}
			$this->pageRecord = $this->pageLookup->getExistingPageByText( $titleText );
		}
		return $this->pageRecord;
	}

	public function getPageIdentity(): ?PageIdentity {
		if ( $this->pageIdentity === false ) {
			$this->pageIdentity = $this->getPage();
		}

		if ( $this->pageIdentity === null ) {
			$titleText = $this->getTitleText();
			if ( $titleText === null ) {
				return null;
			}
			$this->pageIdentity = $this->pageLookup->getPageByText( $titleText );
		}

		return $this->pageIdentity;
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
		} catch ( SuppressedDataException ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-permission-denied-revision' )->numParams( $revision->getId() ),
				403
			);
		} catch ( RevisionAccessException ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-nonexistent-revision' )->numParams( $revision->getId() ),
				404
			);
		}
		return $content;
	}

	public function isAccessible(): bool {
		$page = $this->getPageIdentity();
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
	 */
	public function hasContent(): bool {
		return $this->useDefaultSystemMessage() || (bool)$this->getPage();
	}

	public function constructMetadata(): array {
		$revision = $this->getRevisionRecordForMetadata();

		$page = $revision->getPage();
		return [
			'id' => $page->getId(),
			'key' => $this->titleFormatter->getPrefixedDBkey( $page ),
			'title' => $this->titleFormatter->getPrefixedText( $page ),
			'latest' => [
				'id' => $revision->getId(),
				'timestamp' => wfTimestampOrNull( TS_ISO_8601, $revision->getTimestamp() )
			],
			'content_model' => $revision->getMainContentModel(),
			'license' => [
				'url' => $this->options->get( MainConfigNames::RightsUrl ),
				'title' => $this->options->get( MainConfigNames::RightsText )
			],
		];
	}

	public function constructRestbaseCompatibleMetadata(): array {
		$revision = $this->getRevisionRecordForMetadata();

		$page = $revision->getPage();
		$title = $this->titleFactory->newFromPageIdentity( $page );

		$tags = $this->changeTagsStore->getTags(
			$this->dbProvider->getReplicaDatabase(),
			null, $revision->getId(), null
		);

		$restrictions = [];

		if ( $revision->isDeleted( RevisionRecord::DELETED_COMMENT ) ) {
			$restrictions[] = 'commenthidden';
		}

		if ( $revision->isDeleted( RevisionRecord::DELETED_USER ) ) {
			$restrictions[] = 'userhidden';
		}

		$publicUser = $revision->getUser();
		$publicComment = $revision->getComment();

		return [
			'title' => $title->getPrefixedDBkey(),
			'page_id' => $page->getId(),
			'rev' => $revision->getId(),

			// We could look up the tid from a ParserOutput, but it's expensive,
			// and the tid can't be used for anything anymore anyway.
			// Don't use an empty string though, that may break routing when the
			// value is used as a path parameter.
			'tid' => 'DUMMY',

			'namespace' => $page->getNamespace(),
			'user_id' => $revision->getUser( RevisionRecord::RAW )->getId(),
			'user_text' => $publicUser ? $publicUser->getName() : null,
			'comment' => $publicComment ? $publicComment->text : null,
			'timestamp' => wfTimestampOrNull( TS_ISO_8601, $revision->getTimestamp() ),
			'tags' => $tags,
			'restrictions' => $restrictions,
			'page_language' => $title->getPageLanguage()->getCode(),
			'redirect' => $title->isRedirect()
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
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-page-content-title' ),
			],
			'redirect' => [
				Handler::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'boolean',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_DEFAULT => true,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-page-content-redirect' ),
			]
		];
	}

	/**
	 * Whether the handler is allowed to follow redirects, according to the
	 * request parameters.
	 *
	 * Handlers that can follow wiki redirects can use this to give clients
	 * control over the redirect handling behavior.
	 */
	public function getRedirectsAllowed(): bool {
		return $this->parameters['redirect'] ?? true;
	}

	/**
	 * Sets the 'Cache-Control' header no more then provided $expiry.
	 * @param ResponseInterface $response
	 * @param int|null $expiry
	 */
	public function setCacheControl( ResponseInterface $response, ?int $expiry = null ) {
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
	 * If the page is a system message page. When the content gets
	 * overridden to create an actual page, this method returns false.
	 */
	public function useDefaultSystemMessage(): bool {
		return $this->getDefaultSystemMessage() !== null && $this->getPage() === null;
	}

	public function getDefaultSystemMessage(): ?Message {
		$title = Title::newFromText( $this->getTitleText() );

		return $title ? $title->getDefaultSystemMessage() : null;
	}

	/**
	 * @throws LocalizedHttpException if access is not allowed
	 */
	public function checkAccessPermission() {
		$titleText = $this->getTitleText() ?? '';

		// @phan-suppress-next-line PhanTypeMismatchArgumentNullable Validated by hasContent
		if ( !$this->isAccessible() || !$this->authority->authorizeRead( 'read', $this->getPageIdentity() ) ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-permission-denied-title' )->plaintextParams( $titleText ),
				403
			);
		}
	}

	/**
	 * @throws LocalizedHttpException if no content is available
	 */
	public function checkHasContent() {
		$titleText = $this->getTitleText() ?? '';

		$page = $this->getPageIdentity();
		if ( !$page ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-invalid-title' )->plaintextParams( $titleText ),
				404
			);
		}

		if ( !$this->hasContent() ) {
			// needs to check if it's possibly a variant title
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-nonexistent-title' )->plaintextParams( $titleText ),
				404
			);
		}

		$revision = $this->getTargetRevision();
		if ( !$revision && !$this->useDefaultSystemMessage() ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-no-revision' )->plaintextParams( $titleText ),
				404
			);
		}
	}

	/**
	 * @throws LocalizedHttpException if the content is not accessible
	 */
	public function checkAccess() {
		$this->checkHasContent(); // Status 404: Not Found
		$this->checkAccessPermission(); // Status 403: Forbidden
	}

	/**
	 * @return MutableRevisionRecord|RevisionRecord|null
	 */
	private function getRevisionRecordForMetadata() {
		if ( $this->useDefaultSystemMessage() ) {
			$title = Title::newFromText( $this->getTitleText() );
			$content = new WikitextContent( $title->getDefaultMessageText() );
			$revision = new MutableRevisionRecord( $title );
			$revision->setPageId( 0 );
			$revision->setId( 0 );
			$revision->setContent(
				SlotRecord::MAIN,
				$content
			);
		} else {
			$revision = $this->getTargetRevision();
		}

		return $revision;
	}

}
