<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */
namespace MediaWiki\Rest\Handler\Helper;

use InvalidArgumentException;
use MediaWiki\Content\Content;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Edit\ParsoidOutputStash;
use MediaWiki\Edit\ParsoidRenderID;
use MediaWiki\Edit\SelserContext;
use MediaWiki\Exception\HttpError;
use MediaWiki\Exception\MWUnknownContentModelException;
use MediaWiki\Language\LanguageCode;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageLookup;
use MediaWiki\Page\PageRecord;
use MediaWiki\Page\ParserOutputAccess;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\Config\SiteConfig as ParsoidSiteConfig;
use MediaWiki\Parser\Parsoid\HtmlTransformFactory;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWiki\Permissions\Authority;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use Wikimedia\Assert\Assert;
use Wikimedia\Bcp47Code\Bcp47Code;
use Wikimedia\Bcp47Code\Bcp47CodeValue;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\Core\ResourceLimitExceededException;
use Wikimedia\Parsoid\DOM\Element;
use Wikimedia\Parsoid\Parsoid;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMUtils;
use Wikimedia\Parsoid\Utils\WTUtils;
use Wikimedia\Stats\StatsFactory;

/**
 * Helper for getting output of a given wikitext page rendered by parsoid.
 *
 * @since 1.36
 *
 * @unstable Pending consolidation of the Parsoid extension with core code.
 */
class HtmlOutputRendererHelper implements HtmlOutputHelper {
	use RestAuthorizeTrait;
	use RestStatusTrait;

	/**
	 * @internal
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::ParsoidCacheConfig
	];

	private const OUTPUT_FLAVORS = [ 'view', 'stash', 'fragment', 'edit' ];

	/** @var PageIdentity|null */
	private $page = null;

	/** @var RevisionRecord|int|null */
	private $revisionOrId = null;

	/** @var Bcp47Code|null */
	private $pageLanguage = null;

	/** @var ?string One of the flavors from OUTPUT_FLAVORS */
	private $flavor = null;

	/** @var bool */
	private $stash = false;

	/** @var Authority */
	private $authority;

	/** @var ParserOutput */
	private $parserOutput;

	/** @var ParserOutput */
	private $processedParserOutput;

	/** @var ?Bcp47Code */
	private $sourceLanguage = null;

	/** @var ?Bcp47Code */
	private $targetLanguage = null;

	/**
	 * Should we ignore mismatches between $page and the page that $revision belongs to?
	 * Usually happens because of page moves. This should be set to true only for internal API calls.
	 */
	private bool $lenientRevHandling = false;

	/**
	 * Flags to be passed as $options to ParserOutputAccess::getParserOutput,
	 * to control parser cache access.
	 *
	 * @var int Use ParserOutputAccess::OPT_*
	 */
	private $parserOutputAccessOptions = 0;

	/**
	 * @see the $options parameter on Parsoid::wikitext2html
	 * @var array
	 */
	private $parsoidOptions = [];

	private ?ParserOptions $parserOptions = null;

	/**
	 * Whether the result can be cached in the parser cache and the web cache.
	 * Set to false when bespoke options are set.
	 *
	 * @var bool
	 */
	private $isCacheable = true;

	private ParsoidOutputStash $parsoidOutputStash;
	private StatsFactory $statsFactory;
	private ParserOutputAccess $parserOutputAccess;
	private PageLookup $pageLookup;
	private RevisionLookup $revisionLookup;
	private RevisionRenderer $revisionRenderer;
	private ParsoidSiteConfig $parsoidSiteConfig;
	private HtmlTransformFactory $htmlTransformFactory;
	private IContentHandlerFactory $contentHandlerFactory;
	private LanguageFactory $languageFactory;

	/**
	 * @param ParsoidOutputStash $parsoidOutputStash
	 * @param StatsFactory $statsFactory
	 * @param ParserOutputAccess $parserOutputAccess
	 * @param PageLookup $pageLookup
	 * @param RevisionLookup $revisionLookup
	 * @param RevisionRenderer $revisionRenderer
	 * @param ParsoidSiteConfig $parsoidSiteConfig
	 * @param HtmlTransformFactory $htmlTransformFactory
	 * @param IContentHandlerFactory $contentHandlerFactory
	 * @param LanguageFactory $languageFactory
	 * @param PageIdentity|null $page
	 * @param array $parameters
	 * @param Authority|null $authority
	 * @param RevisionRecord|int|null $revision
	 * @param bool $lenientRevHandling Should we ignore mismatches between
	 *    $page and the page that $revision belongs to? Usually happens
	 *    because of page moves. This should be set to true only for
	 *    internal API calls.
	 * @param ParserOptions|null $parserOptions
	 * @note Since 1.43, setting $page and $authority arguments to null
	 *    has been deprecated.
	 */
	public function __construct(
		ParsoidOutputStash $parsoidOutputStash,
		StatsFactory $statsFactory,
		ParserOutputAccess $parserOutputAccess,
		PageLookup $pageLookup,
		RevisionLookup $revisionLookup,
		RevisionRenderer $revisionRenderer,
		ParsoidSiteConfig $parsoidSiteConfig,
		HtmlTransformFactory $htmlTransformFactory,
		IContentHandlerFactory $contentHandlerFactory,
		LanguageFactory $languageFactory,
		?PageIdentity $page = null,
		array $parameters = [],
		?Authority $authority = null,
		$revision = null,
		bool $lenientRevHandling = false,
		?ParserOptions $parserOptions = null
	) {
		$this->parsoidOutputStash = $parsoidOutputStash;
		$this->statsFactory = $statsFactory;
		$this->parserOutputAccess = $parserOutputAccess;
		$this->pageLookup = $pageLookup;
		$this->revisionLookup = $revisionLookup;
		$this->revisionRenderer = $revisionRenderer;
		$this->parsoidSiteConfig = $parsoidSiteConfig;
		$this->htmlTransformFactory = $htmlTransformFactory;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->languageFactory = $languageFactory;
		$this->lenientRevHandling = $lenientRevHandling;
		$this->parserOptions = $parserOptions;
		if ( $page === null || $authority === null ) {
			// Constructing without $page and $authority parameters
			// is deprecated since 1.43.
			wfDeprecated( __METHOD__ . ' without $page or $authority', '1.43' );
		} else {
			$this->initInternal( $page, $parameters, $authority, $revision );
		}
	}

	/**
	 * Sets the given flavor to use for Wikitext -> HTML transformations.
	 *
	 * Flavors may influence parser options, parsoid options, and DOM transformations.
	 * They will be reflected by the ETag returned by getETag().
	 *
	 * @note This method should not be called if stashing mode is enabled.
	 * @see setStashingEnabled
	 * @see getFlavor()
	 *
	 * @param string $flavor
	 *
	 * @return void
	 */
	public function setFlavor( string $flavor ): void {
		if ( !in_array( $flavor, self::OUTPUT_FLAVORS ) ) {
			throw new InvalidArgumentException( 'Invalid flavor supplied' );
		}

		if ( $this->stash ) {
			// XXX: throw?
			$flavor = 'stash';
		}

		$this->flavor = $flavor;
	}

	/**
	 * Returns the flavor of HTML that will be generated.
	 * @see setFlavor()
	 * @return string
	 */
	public function getFlavor(): string {
		return $this->flavor;
	}

	/**
	 * Set the desired Parsoid profile version for the output.
	 * The actual output version is selected to be compatible with the one given here,
	 * per the rules of semantic versioning.
	 *
	 * @note Will disable caching if the effective output version is different from the default.
	 *
	 * @param string $version
	 *
	 * @throws HttpException If the given version is not supported (status 406)
	 */
	public function setOutputProfileVersion( $version ) {
		$outputContentVersion = Parsoid::resolveContentVersion( $version );

		if ( !$outputContentVersion ) {
			throw new LocalizedHttpException(
				new MessageValue( "rest-unsupported-profile-version", [ $version ] ), 406
			);
		}

		// Only set the option if the value isn't the default!
		if ( $outputContentVersion !== Parsoid::defaultHTMLVersion() ) {
			throw new LocalizedHttpException(
				new MessageValue( "rest-unsupported-profile-version", [ $version ] ), 406
			);

			// TODO: (T347426) At some later point, we may reintroduce support for
			// non-default content versions as part of work on the content
			// negotiation protocol.
			//
			// // See Parsoid::wikitext2html
			// $this->parsoidOptions['outputContentVersion'] = $outputContentVersion;
			// $this->isCacheable = false;
		}
	}

	/**
	 * Determine whether stashing should be applied.
	 *
	 * @param bool $stash
	 *
	 * @return void
	 */
	public function setStashingEnabled( bool $stash ): void {
		$this->stash = $stash;

		if ( $stash ) {
			$this->setFlavor( 'stash' );
		} elseif ( $this->flavor === 'stash' ) {
			$this->setFlavor( 'view' );
		}
	}

	/**
	 * Set the revision to render.
	 *
	 * This can take a fake RevisionRecord when rendering for previews
	 * or when switching the editor from source mode to visual mode.
	 *
	 * In that case, $revisionOrId->getId() must return 0 to indicate
	 * that the ParserCache should be bypassed. Stashing may still apply.
	 *
	 * @param RevisionRecord|int $revisionOrId
	 */
	public function setRevision( $revisionOrId ): void {
		Assert::parameterType( [ RevisionRecord::class, 'integer' ], $revisionOrId, '$revision' );

		if ( is_int( $revisionOrId ) && $revisionOrId <= 0 ) {
			throw new HttpError( 400, "Bad revision ID: $revisionOrId" );
		}

		$this->revisionOrId = $revisionOrId;

		if ( $this->getRevisionId() === null ) {
			// If we have a RevisionRecord but no revision ID, we are dealing with a fake
			// revision used for editor previews or mode switches. The wikitext is coming
			// from the request, not the database, so the result is not cacheable for re-use
			// by others (though it can be stashed for use by the same client).
			$this->isCacheable = false;
		}
	}

	/**
	 * Set the content to render. Useful when rendering for previews
	 * or when switching the editor from source mode to visual mode.
	 *
	 * This will create a fake revision for rendering, the revision ID will be 0.
	 *
	 * @see setRevision
	 * @see setContentSource
	 *
	 * @param Content $content
	 */
	public function setContent( Content $content ): void {
		$rev = new MutableRevisionRecord( $this->page );
		$rev->setId( 0 );
		$rev->setPageId( $this->page->getId() );
		$rev->setContent( SlotRecord::MAIN, $content );
		$this->setRevision( $rev );
	}

	/**
	 * Set the content to render. Useful when rendering for previews
	 * or when switching the editor from source mode to visual mode.
	 *
	 * This will create a fake revision for rendering. The revision ID will be 0.
	 *
	 * @param string $source The source data, e.g. wikitext
	 * @param string $model The content model indicating how to interpret $source, e.g. CONTENT_MODEL_WIKITEXT
	 *
	 * @see setRevision
	 * @see setContent
	 */
	public function setContentSource( string $source, string $model ): void {
		try {
			$handler = $this->contentHandlerFactory->getContentHandler( $model );
			$content = $handler->unserializeContent( $source );
			$this->setContent( $content );
		} catch ( MWUnknownContentModelException $ex ) {
			throw new LocalizedHttpException( new MessageValue( "rest-bad-content-model", [ $model ] ), 400 );
		}
	}

	/**
	 * This is equivalent to 'pageLanguageOverride' in PageConfigFactory
	 * For example, when clients call the REST API with the 'content-language'
	 * header to affect language variant conversion.
	 *
	 * @param Bcp47Code|string $pageLanguage the page language, as a Bcp47Code
	 *   or a BCP-47 string.
	 */
	public function setPageLanguage( $pageLanguage ): void {
		if ( is_string( $pageLanguage ) ) {
			$pageLanguage = new Bcp47CodeValue( $pageLanguage );
		}
		$this->pageLanguage = $pageLanguage;
	}

	/**
	 * Initializes the helper with the given parameters like the page
	 * we're dealing with, parameters gotten from the request inputs,
	 * and the revision if any is available.
	 *
	 * @param PageIdentity $page
	 * @param array $parameters
	 * @param Authority $authority
	 * @param RevisionRecord|int|null $revision
	 * @deprecated since 1.43, use parameters in constructor instead
	 */
	public function init(
		PageIdentity $page,
		array $parameters,
		Authority $authority,
		$revision = null
	) {
		wfDeprecated( __METHOD__, '1.43' );
		$this->initInternal( $page, $parameters, $authority, $revision );
	}

	/**
	 * @param PageIdentity $page
	 * @param array $parameters
	 * @param Authority $authority
	 * @param int|RevisionRecord|null $revision
	 */
	private function initInternal(
		PageIdentity $page,
		array $parameters,
		Authority $authority,
		$revision = null
	) {
		$this->page = $page;
		$this->authority = $authority;
		$this->stash = $parameters['stash'] ?? false;

		if ( $revision !== null ) {
			$this->setRevision( $revision );
		}

		if ( $this->stash ) {
			$this->setFlavor( 'stash' );
		} else {
			$this->setFlavor( $parameters['flavor'] ?? 'view' );
		}
		$this->parserOptions ??= ParserOptions::newFromAnon();
	}

	/**
	 * @inheritDoc
	 */
	public function setVariantConversionLanguage(
		$targetLanguage,
		$sourceLanguage = null
	): void {
		if ( is_string( $targetLanguage ) ) {
			$targetLanguage = $this->getAcceptedTargetLanguage( $targetLanguage );
			$targetLanguage = LanguageCode::normalizeNonstandardCodeAndWarn(
				$targetLanguage
			);
		}
		if ( is_string( $sourceLanguage ) ) {
			$sourceLanguage = LanguageCode::normalizeNonstandardCodeAndWarn(
				$sourceLanguage
			);
		}
		$this->targetLanguage = $targetLanguage;
		$this->sourceLanguage = $sourceLanguage;
	}

	/**
	 * Get a target language from an accept header
	 */
	private function getAcceptedTargetLanguage( string $targetLanguage ): string {
		// We could try to identify the most desirable language here,
		// following the rules for Accept-Language headers in RFC9100.
		// For now, just take the first language code.

		if ( preg_match( '/^\s*([-\w]+)/', $targetLanguage, $m ) ) {
			return $m[1];
		} else {
			// "undetermined" per RFC5646
			return 'und';
		}
	}

	/**
	 * @inheritDoc
	 */
	public function getHtml(): ParserOutput {
		if ( $this->processedParserOutput ) {
			return $this->processedParserOutput;
		}

		$parserOutput = $this->getParserOutput();

		if ( $this->stash ) {
			$this->authorizeWriteOrThrow( $this->authority, 'stashbasehtml', $this->page );

			$isFakeRevision = $this->getRevisionId() === null;
			$parsoidStashKey = ParsoidRenderID::newFromParserOutput( $parserOutput );
			$stashSuccess = $this->parsoidOutputStash->set(
				$parsoidStashKey,
				new SelserContext(
					PageBundleParserOutputConverter::pageBundleFromParserOutput( $parserOutput ),
					$parsoidStashKey->getRevisionID(),
					$isFakeRevision ? $this->revisionOrId->getContent( SlotRecord::MAIN ) : null
				)
			);
			if ( !$stashSuccess ) {
				$this->statsFactory->getCounter( 'htmloutputrendererhelper_stash_total' )
					->setLabel( 'status', 'fail' )
					->copyToStatsdAt( 'htmloutputrendererhelper.stash.fail' )
					->increment();

				$errorData = [ 'parsoid-stash-key' => $parsoidStashKey ];
				LoggerFactory::getInstance( 'HtmlOutputRendererHelper' )->error(
					"Parsoid stash failure",
					$errorData
				);
				throw new LocalizedHttpException(
					MessageValue::new( 'rest-html-stash-failure' ),
					500,
					$errorData
				);
			}
			$this->statsFactory->getCounter( 'htmloutputrendererhelper_stash_total' )
				->setLabel( 'status', 'save' )
				->copyToStatsdAt( 'htmloutputrendererhelper.stash.save' )
				->increment();
		}

		if ( $this->flavor === 'edit' ) {
			$pb = $this->getPageBundle();

			// Inject data-parsoid and data-mw attributes.
			$parserOutput->setRawText( $pb->toInlineAttributeHtml() );
		}

		// Check if variant conversion has to be performed
		// NOTE: Variant conversion is performed on the fly, and kept outside the stash.
		if ( $this->targetLanguage ) {
			$languageVariantConverter = $this->htmlTransformFactory->getLanguageVariantConverter( $this->page );
			$parserOutput = $languageVariantConverter->convertParserOutputVariant(
				$parserOutput,
				$this->targetLanguage,
				$this->sourceLanguage
			);
		}

		$this->processedParserOutput = $parserOutput;
		return $parserOutput;
	}

	/**
	 * @inheritDoc
	 */
	public function getETag( string $suffix = '' ): ?string {
		$parserOutput = $this->getParserOutput();

		$renderID = ParsoidRenderID::newFromParserOutput( $parserOutput )->getKey();

		if ( $suffix !== '' ) {
			$eTag = "$renderID/{$this->flavor}/$suffix";
		} else {
			$eTag = "$renderID/{$this->flavor}";
		}

		if ( $this->targetLanguage ) {
			$eTag .= "+lang:{$this->targetLanguage->toBcp47Code()}";
		}

		return "\"{$eTag}\"";
	}

	private function isLatest(): bool {
		$revId = $this->getRevisionId();

		if ( $revId === null ) {
			return false; // un-saved revision
		}

		if ( $revId === 0 ) {
			return true; // latest revision
		}

		$page = $this->getPageRecord();

		if ( !$page ) {
			return false; // page doesn't exist. shouldn't happen.
		}

		return $revId === $page->getLatest();
	}

	/**
	 * @inheritDoc
	 */
	public function getLastModified(): ?string {
		if ( $this->isLatest() ) {
			$page = $this->getPageRecord();

			// $page should never be null here.
			// If it's null, getParserOutput() will fail nicely below.
			if ( $page ) {
				// Using the touch timestamp for this purpose is in line with
				// the behavior of ViewAction::show(). However,
				// OutputPage::checkLastModified() applies a lot of additional
				// limitations.
				return $page->getTouched();
			}
		}

		return $this->getParserOutput()->getCacheTime();
	}

	/**
	 * @inheritDoc
	 */
	public static function getParamSettings(): array {
		return [
			'stash' => [
				Handler::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'boolean',
				ParamValidator::PARAM_DEFAULT => false,
				ParamValidator::PARAM_REQUIRED => false,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-html-output-stash' )
			],
			'flavor' => [
				Handler::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => self::OUTPUT_FLAVORS,
				ParamValidator::PARAM_DEFAULT => 'view',
				ParamValidator::PARAM_REQUIRED => false,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-html-output-flavor' )
			],
		];
	}

	private function getDefaultPageLanguage(): Bcp47Code {
		// NOTE: keep in sync with Parser::getTargetLanguage!

		// XXX: Inject a TitleFactory just for this?! We need a better way to determine the page language...
		$title = Title::castFromPageIdentity( $this->page );

		if ( $this->parserOptions->getInterfaceMessage() ) {
			return $this->parserOptions->getUserLangObj();
		}

		return $title->getPageLanguage();
	}

	private function getParserOutput(): ParserOutput {
		if ( !$this->parserOutput ) {
			$this->parserOptions->setRenderReason( __METHOD__ );

			$defaultLanguage = $this->getDefaultPageLanguage();

			if ( $this->pageLanguage
				&& $this->pageLanguage->toBcp47Code() !== $defaultLanguage->toBcp47Code()
			) {
				$languageObj = $this->languageFactory->getLanguage( $this->pageLanguage );
				$this->parserOptions->setTargetLanguage( $languageObj );
				// Ensure target language splits the parser cache, when
				// non-default; targetLangauge is not in
				// ParserOptions::$cacheVaryingOptionsHash for the legacy
				// parser.
				$this->parserOptions->addExtraKey( 'target=' . $languageObj->getCode() );
			}

			try {
				$status = $this->getParserOutputInternal();
			} catch ( RevisionAccessException $e ) {
				throw new LocalizedHttpException(
					MessageValue::new( 'rest-nonexistent-title' ),
					404,
					[ 'reason' => $e->getMessage() ]
				);
			}

			if ( !$status->isOK() ) {
				if ( $status->hasMessage( 'parsoid-client-error' ) ) {
					$this->throwExceptionForStatus( $status, 'rest-html-backend-error', 400 );
				} elseif ( $status->hasMessage( 'parsoid-resource-limit-exceeded' ) ) {
					$this->throwExceptionForStatus( $status, 'rest-resource-limit-exceeded', 413 );
				} elseif ( $status->hasMessage( 'missing-revision-permission' ) ) {
					$this->throwExceptionForStatus( $status, 'rest-permission-denied-revision', 403 );
				} elseif ( $status->hasMessage( 'parsoid-revision-access' ) ) {
					$this->throwExceptionForStatus( $status, 'rest-specified-revision-unavailable', 404 );
				} else {
					$this->logStatusError( $status, 'Parsoid backend error', 'HtmlOutputRendererHelper' );
					$this->throwExceptionForStatus( $status, 'rest-html-backend-error', 500 );
				}
			}

			$this->parserOutput = $status->getValue();
		}

		Assert::invariant( $this->parserOutput->getRenderId() !== null, "no render id" );
		return $this->parserOutput;
	}

	/**
	 * The content language of the HTML output after parsing.
	 *
	 * @return Bcp47Code The language, as a BCP-47 code
	 */
	public function getHtmlOutputContentLanguage(): Bcp47Code {
		$contentLanguage = $this->getHtml()->getLanguage();

		// This shouldn't happen, but don't crash if it does:
		if ( !$contentLanguage ) {
			if ( $this->pageLanguage ) {
				LoggerFactory::getInstance( 'HtmlOutputRendererHelper' )->warning(
					"ParserOutput does not specify a language"
				);

				$contentLanguage = $this->pageLanguage;
			} else {
				LoggerFactory::getInstance( 'HtmlOutputRendererHelper' )->warning(
					"ParserOutput does not specify a language and no page language set in helper."
				);

				$title = Title::newFromPageIdentity( $this->page );
				$contentLanguage = $title->getPageLanguage();
			}
		}

		return $contentLanguage;
	}

	/**
	 * @inheritDoc
	 */
	public function putHeaders( ResponseInterface $response, bool $forHtml = true ): void {
		if ( $forHtml ) {
			// For HTML, we want to set the Content-Language. For JSON, we probably don't.
			$response->setHeader( 'Content-Language', $this->getHtmlOutputContentLanguage()->toBcp47Code() );

			$pb = $this->getPageBundle();
			ParsoidFormatHelper::setContentType( $response, ParsoidFormatHelper::FORMAT_HTML, $pb->version );
		}

		if ( $this->targetLanguage ) {
			$response->addHeader( 'Vary', 'Accept-Language' );
		}

		// XXX: if Parsoid returns Vary headers, set them here?!

		if ( !$this->isCacheable ) {
			$response->setHeader( 'Cache-Control', 'private,no-cache,s-maxage=0' );
		}

		// TODO: cache control for stable HTML? See ContentHelper::setCacheControl

		if ( $this->getRevisionId() ) {
			$response->setHeader( 'Content-Revision-Id', (string)$this->getRevisionId() );
		}
	}

	/**
	 * Returns the rendered HTML as a PageBundle object.
	 */
	public function getPageBundle(): PageBundle {
		// XXX: converting between PageBundle and ParserOutput is inefficient!
		$parserOutput = $this->getParserOutput();
		$pb = PageBundleParserOutputConverter::pageBundleFromParserOutput( $parserOutput );

		// Check if variant conversion has to be performed
		// NOTE: Variant conversion is performed on the fly, and kept outside the stash.
		if ( $this->targetLanguage ) {
			$languageVariantConverter = $this->htmlTransformFactory->getLanguageVariantConverter( $this->page );
			$pb = $languageVariantConverter->convertPageBundleVariant(
				$pb,
				$this->targetLanguage,
				$this->sourceLanguage
			);
		}

		return $pb;
	}

	/**
	 * Returns the ID of the revision that is being rendered.
	 *
	 * This will return 0 if no revision has been specified, so the current revision
	 * will be rendered.
	 *
	 * This wil return null if RevisionRecord has been set but that RevisionRecord
	 * does not have a revision ID, e.g. when rendering a preview.
	 */
	public function getRevisionId(): ?int {
		if ( !$this->revisionOrId ) {
			// If we don't have a revision set, or it's 0, we are rendering the current revision.
			return 0;
		}

		if ( $this->revisionOrId instanceof RevisionRecord ) {
			// NOTE: return null even if getId() gave us 0
			return $this->revisionOrId->getId() ?: null;
		}

		// It's a revision ID, just return it
		return (int)$this->revisionOrId;
	}

	/**
	 * Strip Parsoid's section wrappers
	 *
	 * TODO: Should we move this to Parsoid's ContentUtils class?
	 * There already is a stripUnnecessaryWrappersAndSyntheticNodes but
	 * it targets html2wt and does a lot more than just section unwrapping.
	 */
	private function stripParsoidSectionTags( Element $elt ): void {
		$n = $elt->firstChild;
		while ( $n ) {
			$next = $n->nextSibling;
			if ( $n instanceof Element ) {
				// Recurse into subtree before stripping this
				$this->stripParsoidSectionTags( $n );
				// Strip <section> tags and synthetic extended-annotation-region wrappers
				if ( WTUtils::isParsoidSectionTag( $n ) ) {
					$parent = $n->parentNode;
					// Help out phan
					'@phan-var Element $parent';
					DOMUtils::migrateChildren( $n, $parent, $n );
					$parent->removeChild( $n );
				}
			}
			$n = $next;
		}
	}

	/**
	 * Returns the page record, or null if no page is known or the page does not exist.
	 *
	 * @return PageRecord|null
	 */
	private function getPageRecord(): ?PageRecord {
		if ( $this->page === null ) {
			return null;
		}

		if ( !$this->page instanceof PageRecord ) {
			$page = $this->pageLookup->getPageByReference( $this->page );
			if ( !$page ) {
				return null;
			}

			$this->page = $page;
		}

		return $this->page;
	}

	private function getParserOutputInternal(): Status {
		// NOTE: ParserOutputAccess::getParserOutput() should be used for revisions
		//       that come from the database. Either this revision is null to indicate
		//       the current revision or the revision must have an ID.
		// If we have a revision and the ID is 0 or null, then it's a fake revision
		// representing a preview.
		$parsoidOptions = $this->parsoidOptions;
		// NOTE: VisualEditor would set this flavor when transforming from Wikitext to HTML
		//       for the purpose of editing when doing parsefragment (in body only mode).
		if ( $this->flavor === 'fragment' || $this->getRevisionId() === null ) {
			$this->isCacheable = false;
		}

		// TODO: Decide whether we want to allow stale content for speed for the
		// 'view' flavor. In that case, we would want to use PoolCounterWork,
		// either directly or through ParserOutputAccess.

		$flags = $this->parserOutputAccessOptions;

		// Find page
		$pageRecord = $this->getPageRecord();
		$revision = $this->revisionOrId;

		// NOTE: If we have a RevisionRecord already and this is
		//       not cacheable, just use it, there is no need to
		//       resolve $page to a PageRecord (and it may not be
		//       possible if the page doesn't exist).
		if ( $this->isCacheable ) {
			if ( !$pageRecord ) {
				if ( $this->page ) {
					throw new RevisionAccessException(
						'Page {name} not found',
						[ 'name' => "{$this->page}" ]
					);
				} else {
					throw new RevisionAccessException( "No page" );
				}
			}

			$revision ??= $pageRecord->getLatest();

			if ( is_int( $revision ) ) {
				$revId = $revision;
				$revision = $this->revisionLookup->getRevisionById( $revId );

				if ( !$revision ) {
					throw new RevisionAccessException(
						'Revision {revId} not found',
						[ 'revId' => $revId ]
					);
				}
			}

			if ( $pageRecord->getId() !== $revision->getPageId() ) {
				if ( $this->lenientRevHandling ) {
					$pageRecord = $this->pageLookup->getPageById( $revision->getPageId() );
					if ( !$pageRecord ) {
						// This should ideally never trigger!
						throw new \RuntimeException(
							"Unexpected NULL page for pageid " . $revision->getPageId() .
							" from revision " . $revision->getId()
						);
					}
					// Don't cache this!
					$flags |= ParserOutputAccess::OPT_NO_UPDATE_CACHE;
				} else {
					throw new RevisionAccessException(
						'Revision {revId} does not belong to page {name}',
						[ 'name' => $pageRecord->getDBkey(), 'revId' => $revision->getId() ]
					);
				}
			}
		}

		$contentModel = $revision->getMainContentModel();
		if ( $this->parsoidSiteConfig->supportsContentModel( $contentModel ) ) {
			$this->parserOptions->setUseParsoid();
		}
		if ( $this->isCacheable ) {
			// phan can't tell that we must have used the block above to
			// resolve $pageRecord to a PageRecord if we've made it to this block.
			'@phan-var PageRecord $pageRecord';
			try {
				$status = $this->parserOutputAccess->getParserOutput(
					$pageRecord, $this->parserOptions, $revision, $flags
				);
			} catch ( ClientError $e ) {
				$status = Status::newFatal( 'parsoid-client-error', $e->getMessage() );
			} catch ( ResourceLimitExceededException $e ) {
				$status = Status::newFatal( 'parsoid-resource-limit-exceeded', $e->getMessage() );
			}
			Assert::invariant( $status->isOK() ? $status->getValue()->getRenderId() !== null : true, "no render id" );
		} else {
			'@phan-var RevisionRecord $revision';
			$status = $this->parseUncacheable(
				$this->page,
				$revision,
				$this->lenientRevHandling
			);

			// @phan-suppress-next-line PhanSuspiciousValueComparison
			if ( $status->isOK() && $this->flavor === 'fragment' ) {
				// Unwrap sections and return body_only content
				// NOTE: This introduces an extra html -> dom -> html roundtrip
				// This will get addressed once HtmlHolder work is complete
				$parserOutput = $status->getValue();
				$body = DOMCompat::getBody( DOMUtils::parseHTML( $parserOutput->getRawText() ) );
				if ( $body ) {
					$this->stripParsoidSectionTags( $body );
					$parserOutput->setText( DOMCompat::getInnerHTML( $body ) );
				}
			}
			Assert::invariant( $status->isOK() ? $status->getValue()->getRenderId() !== null : true, "no render id" );
		}

		return $status;
	}

	// See ParserOutputAccess::renderRevision() -- but of course this method
	// bypasses any caching.
	private function parseUncacheable(
		PageIdentity $page,
		RevisionRecord $revision,
		bool $lenientRevHandling = false
	): Status {
		// Enforce caller expectation
		$revId = $revision->getId();
		if ( $revId !== 0 && $revId !== null ) {
			return Status::newFatal( 'parsoid-revision-access',
				"parseUncacheable should not be called for a real revision" );
		}
		try {
			$renderedRev = $this->revisionRenderer->getRenderedRevision(
				$revision,
				$this->parserOptions,
				// ParserOutputAccess uses 'null' for the authority and
				// 'audience' => RevisionRecord::RAW, presumably because
				// the access checks are already handled by the
				// RestAuthorizeTrait
				$this->authority,
				[ 'audience' => RevisionRecord::RAW ]
			);
			if ( $renderedRev === null ) {
				return Status::newFatal( 'parsoid-revision-access' );
			}
			$parserOutput = $renderedRev->getRevisionParserOutput();
			// Ensure this isn't accidentally cached
			$parserOutput->updateCacheExpiry( 0 );
			return Status::newGood( $parserOutput );
		} catch ( ClientError $e ) {
			return Status::newFatal( 'parsoid-client-error', $e->getMessage() );
		} catch ( ResourceLimitExceededException $e ) {
			return Status::newFatal( 'parsoid-resource-limit-exceeded', $e->getMessage() );
		}
	}

	public function isParsoidContent(): bool {
		return PageBundleParserOutputConverter::hasPageBundle(
			$this->getParserOutput()
		);
	}

}
