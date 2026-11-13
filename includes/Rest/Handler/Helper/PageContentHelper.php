<?php

namespace MediaWiki\Rest\Handler\Helper;

use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\TextContent;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\ExistingPageRecord;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageLookup;
use MediaWiki\ParamValidator\TypeDef\TitleDef;
use MediaWiki\ParamValidator\TypeDef\TitleParam;
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
use MediaWiki\ShadowPage\ShadowPage;
use MediaWiki\ShadowPage\ShadowPageLoader;
use MediaWiki\Title\TitleFactory;
use MediaWiki\Title\TitleFormatter;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Timestamp\TimestampFormat as TS;

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

	/** @var Authority|null */
	protected $authority = null;

	/** @var array */
	protected $parameters = null;

	/** @var RevisionRecord|false|null */
	protected $targetRevision = false;

	/** @var ExistingPageRecord|false|null */
	protected $pageRecord = false;

	/** @var PageIdentity|false|null */
	private $pageIdentity = false;

	/** @var ShadowPage|false|null */
	private $shadowPage = false;

	public function __construct(
		protected ServiceOptions $options,
		protected RevisionLookup $revisionLookup,
		protected TitleFormatter $titleFormatter,
		protected PageLookup $pageLookup,
		private TitleFactory $titleFactory,
		private IConnectionProvider $dbProvider,
		private ChangeTagsStore $changeTagsStore,
		private ShadowPageLoader $shadowPageLoader,
	) {
	}

	/**
	 * @param Authority $authority
	 * @param array $parameters validated parameters
	 */
	public function init( Authority $authority, array $parameters ) {
		$this->authority = $authority;
		$this->parameters = $parameters;
	}

	private function getTitleParam(): ?TitleParam {
		$title = $this->parameters['title'] ?? null;
		// Callers may pass a raw title, e.g. WikimediaCustomizations' DiscordPreviewRestHandler
		return is_string( $title ) ? new TitleParam( $title, $this->pageLookup->getPageByText( $title ) ) : $title;
	}

	/**
	 * @return string|null title text or null if unable to retrieve title. For a page
	 *   requested by ID ("#123"), this is the title of that page.
	 */
	public function getTitleText(): ?string {
		$titleParam = $this->getTitleParam();
		if ( $titleParam && $titleParam->getRequestedPageId() !== null ) {
			$page = $titleParam->getPage();
			return $page ? $this->titleFormatter->getPrefixedText( $page ) : null;
		}
		return $titleParam?->getText();
	}

	/**
	 * Title as given in the request, for detecting normalization redirects.
	 *
	 * @return string|null null if the page was requested by ID ("#123")
	 */
	public function getTitleAsRequested(): ?string {
		$titleParam = $this->getTitleParam();
		return $titleParam && $titleParam->getRequestedPageId() === null ? $titleParam->getText() : null;
	}

	/**
	 * @return string The title for error messages: as requested if it does not address a page
	 */
	private function getTitleTextForMessage(): string {
		return $this->getTitleText() ?? $this->getTitleParam()?->getText() ?? '';
	}

	/**
	 * @return ExistingPageRecord|null The page's PageRecord, or null if the page was not found.
	 */
	public function getPage(): ?ExistingPageRecord {
		if ( $this->pageRecord === false ) {
			$page = $this->getPageIdentity();
			// PageLookup already returns an ExistingPageRecord for pages that exist
			$this->pageRecord = $page instanceof ExistingPageRecord ? $page : null;
		}
		return $this->pageRecord;
	}

	/**
	 * @return PageIdentity|null The page from the title parameter, or null if the title
	 *   is missing or does not address a page (e.g. invalid title, special page)
	 */
	public function getPageIdentity(): ?PageIdentity {
		if ( $this->pageIdentity === false ) {
			$page = $this->getTitleParam()?->getPage();
			// Prefer the ExistingPageRecord, getPage() relies on it
			$this->pageIdentity = $page ? ( $this->pageLookup->getPageByReference( $page ) ?? $page ) : null;
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
			$titleText = $this->getTitleTextForMessage();
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
	 * Returns an ETag representing a page's source. The ETag assumes a page's source has changed if:
	 * - The latest revision of a page has been made private or un-readable for another reason
	 * - A newer revision exists
	 * - The visible tags on the revision have changed
	 *
	 * @param string $suffix A suffix to attach to the etag.
	 *        Must consist of characters that are legal in ETags.
	 */
	public function getETag( string $suffix = '' ): ?string {
		$revision = $this->getTargetRevision();
		$revId = $revision ? $revision->getId() : 'e0';

		$isAccessible = $this->isAccessible();
		$accessibleTag = $isAccessible ? 'a1' : 'a0';

		$tagsOnRevision = [];
		if ( $revision ) {
			$tagsOnRevision = $this->changeTagsStore->getViewableTags(
				$this->dbProvider->getReplicaDatabase(),
				$this->authority,
				null,
				$revision->getId()
			);
		}

		$revisionTag = $revId . $accessibleTag . implode( ', ', $tagsOnRevision );
		$etag = sha1( $revisionTag );

		if ( $suffix !== '' ) {
			$etag .= '/' . $suffix;
		}

		return '"' . $etag . '"';
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
		return $this->useShadowContent() || (bool)$this->getPage();
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
				'timestamp' => wfTimestampOrNull( TS::ISO_8601, $revision->getTimestamp() )
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

		$tags = $this->changeTagsStore->getViewableTags(
			$this->dbProvider->getReplicaDatabase(),
			$this->authority,
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
			'user_id' => $publicUser?->getId(),
			'user_text' => $publicUser?->getName(),
			'comment' => $publicComment ? $publicComment->text : null,
			'timestamp' => wfTimestampOrNull( TS::ISO_8601, $revision->getTimestamp() ),
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
				ParamValidator::PARAM_TYPE => 'title',
				ParamValidator::PARAM_REQUIRED => true,
				TitleDef::PARAM_RETURN_OBJECT => TitleDef::TITLE_PARAM_VALUE,
				TitleDef::PARAM_ALLOW_ID => true,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-page-content-title' ),
				Handler::PARAM_EXAMPLE => 'Earth',
			],
			'redirect' => [
				Handler::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'boolean',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_DEFAULT => true,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-page-content-redirect' ),
				Handler::PARAM_EXAMPLE => true,
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
	 * If the page is a shadow page. When the content gets
	 * overridden to create an actual page, this method returns false.
	 */
	public function useShadowContent(): bool {
		return $this->getShadowPage()?->hasPreloadContent() && $this->getPage() === null;
	}

	/**
	 * Get the shadow page, if any
	 * @return ShadowPage|null
	 */
	public function getShadowPage(): ?ShadowPage {
		if ( $this->shadowPage === false ) {
			$page = $this->getPageIdentity();
			if ( $page ) {
				$this->shadowPage = $this->shadowPageLoader->get( $page );
			} else {
				$this->shadowPage = null;
			}
		}
		return $this->shadowPage;
	}

	/**
	 * @throws LocalizedHttpException 404 if the requested title does not address a
	 *         page, 403 if that page may not be read
	 */
	public function checkAccessPermission() {
		$titleText = $this->getTitleTextForMessage();
		$page = $this->getPageIdentity();

		// A title that does not address a page at all (an unparseable title, or one
		// of a special page) leaves nothing to authorize against, so report it as
		// missing rather than as forbidden. Checked here and not just in
		// checkHasContent(), so that callers may run the two checks in either order.
		if ( !$page ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-invalid-title' )->plaintextParams( $titleText ),
				404
			);
		}

		if ( !$this->isAccessible() || !$this->authority->authorizeRead( 'read', $page ) ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-permission-denied-title' )->plaintextParams( $titleText ),
				403
			);
		}
	}

	/**
	 * @param bool $allowShadowContent Whether the content of a "shadow page" (a
	 *        page that is known but has no stored content, such as a system
	 *        message page) satisfies this check. Routes that do not serve shadow
	 *        content treat such a page as missing.
	 *
	 * @throws LocalizedHttpException if no content is available
	 */
	public function checkHasContent( bool $allowShadowContent = true ) {
		$titleText = $this->getTitleTextForMessage();

		$page = $this->getPageIdentity();
		if ( !$page ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-invalid-title' )->plaintextParams( $titleText ),
				404
			);
		}

		$useShadowContent = $allowShadowContent && $this->useShadowContent();

		if ( !$useShadowContent && !$this->getPage() ) {
			// needs to check if it's possibly a variant title
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-nonexistent-title' )->plaintextParams( $titleText ),
				404
			);
		}

		$revision = $this->getTargetRevision();
		if ( !$revision && !$useShadowContent ) {
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
		$pageIdentity = $this->getPageIdentity();
		$content = $this->getShadowPage()?->getPreloadContent();
		if ( $pageIdentity && $content ) {
			$revision = new MutableRevisionRecord( $pageIdentity );
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
