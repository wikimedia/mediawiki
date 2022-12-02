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
namespace MediaWiki\Rest\Handler;

use Content;
use IBufferingStatsdDataFactory;
use Language;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use LogicException;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Edit\ParsoidOutputStash;
use MediaWiki\Edit\SelserContext;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageRecord;
use MediaWiki\Parser\Parsoid\HtmlTransformFactory;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWiki\Parser\Parsoid\ParsoidOutputAccess;
use MediaWiki\Parser\Parsoid\ParsoidRenderID;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MWUnknownContentModelException;
use ParserOptions;
use ParserOutput;
use User;
use Wikimedia\Assert\Assert;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\Parsoid;
use Wikimedia\Parsoid\Utils\ContentUtils;
use Wikimedia\Parsoid\Utils\DOMUtils;

/**
 * Helper for getting output of a given wikitext page rendered by parsoid.
 *
 * @since 1.36
 *
 * @unstable Pending consolidation of the Parsoid extension with core code.
 */
class HtmlOutputRendererHelper {
	/**
	 * @internal
	 * @var string[]
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::ParsoidCacheConfig
	];

	/** @var string[] */
	private const OUTPUT_FLAVORS = [ 'view', 'stash', 'fragment', 'edit' ];

	/** @var ParsoidOutputStash */
	private $parsoidOutputStash;

	/** @var PageIdentity|null */
	private $page = null;

	/** @var RevisionRecord|int|null */
	private $revisionOrId = null;

	/** @var Language|null */
	private $pageLanguage = null;

	/** @var ?string One of the flavors from OUTPUT_FLAVORS */
	private $flavor = null;

	/** @var bool */
	private $stash = false;

	/** @var IBufferingStatsdDataFactory */
	private $stats;

	/** @var User */
	private $user;

	/** @var ParsoidOutputAccess */
	private $parsoidOutputAccess;

	/** @var ParserOutput */
	private $parserOutput;

	/** @var ParserOutput */
	private $processedParserOutput;

	/** @var HtmlTransformFactory */
	private $htmlTransformFactory;

	/** @var IContentHandlerFactory */
	private $contentHandlerFactory;

	/** @var LanguageFactory */
	private $languageFactory;

	/** @var string|null */
	private $sourceLanguageCode = null;

	/** @var string|null */
	private $targetLanguageCode = null;

	/**
	 * Flags to be passed as $options to ParsoidOutputAccess::getParserOutput,
	 * to control parser cache access.
	 *
	 * @var int Use ParsoidOutputAccess::OPT_*
	 */
	private $parsoidOutputAccessOptions = 0;

	/**
	 * @see the $options parameter on Parsoid::wikitext2html
	 * @var array
	 */
	private $parsoidOptions = [];

	/**
	 * Whether the result can be cached in the parser cache and the web cache.
	 * Set to false when bespoke options are set.
	 *
	 * @var bool
	 */
	private $isCacheable = true;

	/**
	 * @param ParsoidOutputStash $parsoidOutputStash
	 * @param StatsdDataFactoryInterface $statsDataFactory
	 * @param ParsoidOutputAccess $parsoidOutputAccess
	 * @param HtmlTransformFactory $htmlTransformFactory
	 * @param IContentHandlerFactory $contentHandlerFactory
	 * @param LanguageFactory $languageFactory
	 */
	public function __construct(
		ParsoidOutputStash $parsoidOutputStash,
		StatsdDataFactoryInterface $statsDataFactory,
		ParsoidOutputAccess $parsoidOutputAccess,
		HtmlTransformFactory $htmlTransformFactory,
		IContentHandlerFactory $contentHandlerFactory,
		LanguageFactory $languageFactory
	) {
		$this->parsoidOutputStash = $parsoidOutputStash;
		$this->stats = $statsDataFactory;
		$this->parsoidOutputAccess = $parsoidOutputAccess;
		$this->htmlTransformFactory = $htmlTransformFactory;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->languageFactory = $languageFactory;
	}

	/**
	 * Sets the given flavor to use for Wikitext -> HTML transformations.
	 *
	 * Flavors may influence parser options, parsoid options, and DOM transformations.
	 * They will be reflected by the ETag returned by getETag().
	 *
	 * @param string $flavor
	 *
	 * @return void
	 */
	public function setFlavor( string $flavor ): void {
		if ( !in_array( $flavor, self::OUTPUT_FLAVORS ) ) {
			throw new LogicException( 'Invalid flavor supplied' );
		}

		$this->flavor = $flavor;
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
			throw new HttpException( "Unsupported profile version: $version", 406 );
		}

		// Only set the option if the value isn't the default!
		if ( $outputContentVersion !== Parsoid::defaultHTMLVersion() ) {
			// See Parsoid::wikitext2html
			$this->parsoidOptions['outputContentVersion'] = $outputContentVersion;
			$this->isCacheable = false;
		}
	}

	/**
	 * Set the desired offset type for data-parsoid attributes.
	 *
	 * @note Will disable caching if the given offset type is different from the default.
	 *
	 * @param string $offsetType One of the offset types accepted by Parsoid::wikitext2html.
	 */
	public function setOffsetType( $offsetType ) {
		// Only set the option if the value isn't the default (see Wikimedia\Parsoid\Config\Env)!
		// See Parsoid::wikitext2html for possible values.
		if ( $offsetType !== 'byte' ) {
			$this->parsoidOptions['offsetType'] = $offsetType;
			$this->isCacheable = false;
		}
	}

	/**
	 * Controls how the parser cache is used.
	 *
	 * @param bool $read Whether we should look for cached output before parsing
	 * @param bool $write Whether we should cache output after parsing
	 */
	public function setUseParserCache( bool $read, bool $write ) {
		$this->parsoidOutputAccessOptions =
			( $read ? 0 : ParsoidOutputAccess::OPT_FORCE_PARSE ) |
			( $write ? 0 : ParsoidOutputAccess::OPT_NO_UPDATE_CACHE );
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
		$this->revisionOrId = $revisionOrId;

		if ( !$this->getRevisionId() ) {
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
	 * This will create a fake revision for rendering, the revision ID will be 0.
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
			throw new HttpException( 'Bad content model: ' . $model, 400 );
		}
	}

	/**
	 * @param Language|string $pageLanguage
	 */
	public function setPageLanguage( $pageLanguage ): void {
		if ( is_string( $pageLanguage ) ) {
			$pageLanguage = $this->languageFactory->getLanguage( $pageLanguage );
		}

		$this->pageLanguage = $pageLanguage;
	}

	/**
	 * @param PageIdentity $page
	 * @param array $parameters
	 * @param User $user
	 * @param RevisionRecord|int|null $revision DEPRECATED, use setRevision()
	 * @param Language|null $pageLanguage DEPRECATED, use setPageLanguage()
	 */
	public function init(
		PageIdentity $page,
		array $parameters,
		User $user,
		$revision = null,
		?Language $pageLanguage = null
	) {
		$this->page = $page;
		$this->user = $user;
		$this->revisionOrId = $revision;
		$this->pageLanguage = $pageLanguage;
		$this->stash = $parameters['stash'] ?? false;

		if ( $this->stash ) {
			$this->setFlavor( 'stash' );
		} else {
			$this->setFlavor( $parameters['flavor'] ?? 'view' );
		}
	}

	/**
	 * Set the language to be used for variant conversion
	 * @param string $targetLanguageCode
	 * @param null|string $sourceLanguageCode
	 */
	public function setVariantConversionLanguage( string $targetLanguageCode, ?string $sourceLanguageCode = null ) {
		$this->targetLanguageCode = $targetLanguageCode;
		$this->sourceLanguageCode = $sourceLanguageCode;
	}

	/**
	 * @return ParserOutput a tuple with html and content-type
	 * @throws LocalizedHttpException
	 * @throws ClientError
	 */
	public function getHtml(): ParserOutput {
		if ( $this->processedParserOutput ) {
			return $this->processedParserOutput;
		}

		$parserOutput = $this->getParserOutput();

		if ( $this->stash ) {
			if ( $this->user->pingLimiter( 'stashbasehtml' ) ) {
				throw new LocalizedHttpException(
					MessageValue::new( 'parsoid-stash-rate-limit-error' ),
					// See https://www.rfc-editor.org/rfc/rfc6585#section-4
					429,
					[ 'reason' => 'Rate limiter tripped, wait for a few minutes and try again' ]
				);
			}

			$fakeRevision = !$this->getRevisionId() && $this->revisionOrId !== null;
			$parsoidStashKey = ParsoidRenderID::newFromKey(
				$this->parsoidOutputAccess->getParsoidRenderID( $parserOutput )
			);
			$stashSuccess = $this->parsoidOutputStash->set(
				$parsoidStashKey,
				new SelserContext(
					PageBundleParserOutputConverter::pageBundleFromParserOutput( $parserOutput ),
					$parsoidStashKey->getRevisionID(),
					$fakeRevision ? $this->revisionOrId->getContent( SlotRecord::MAIN ) : null
				)
			);
			if ( !$stashSuccess ) {
				$this->stats->increment( 'htmloutputrendererhelper.stash.fail' );
				throw new LocalizedHttpException(
					MessageValue::new( 'rest-html-backend-error' ),
					500,
					[ 'reason' => 'Failed to stash parser output' ]
				);
			}
			$this->stats->increment( 'htmloutputrendererhelper.stash.save' );
		}

		if ( $this->flavor === 'edit' ) {
			$pb = $this->getPageBundle();

			// Inject data-parsoid and data-mw attributes.
			// XXX: Would be nice if we had a DOM handy.
			$doc = DOMUtils::parseHTML( $parserOutput->getRawText() );
			PageBundle::apply( $doc, $pb );
			$parserOutput->setText( ContentUtils::toXML( $doc ) );
		}

		// Check if variant conversion has to be performed
		// NOTE: Variant conversion is performed on the fly, and kept outside the stash.
		if ( $this->targetLanguageCode ) {
			$languageVariantConverter = $this->htmlTransformFactory->getLanguageVariantConverter( $this->page );
			$parserOutput = $languageVariantConverter->convertParserOutputVariant(
				$parserOutput,
				$this->targetLanguageCode,
				$this->sourceLanguageCode
			);
		}

		$this->processedParserOutput = $parserOutput;
		return $parserOutput;
	}

	/**
	 * Returns an ETag uniquely identifying the HTML output.
	 *
	 * @param string $suffix A suffix to attach to the etag.
	 *
	 * @return string|null
	 */
	public function getETag( string $suffix = '' ): ?string {
		$parserOutput = $this->getParserOutput();

		$renderID = $this->parsoidOutputAccess->getParsoidRenderID( $parserOutput )->getKey();

		if ( $suffix !== '' ) {
			$eTag = "$renderID/{$this->flavor}/$suffix";
		} else {
			$eTag = "$renderID/{$this->flavor}";
		}

		if ( $this->targetLanguageCode ) {
			$eTag .= "+lang:{$this->targetLanguageCode}";
		}

		return "\"{$eTag}\"";
	}

	/**
	 * Returns the time at which the HTML was rendered.
	 *
	 * @return string|null
	 */
	public function getLastModified(): ?string {
		return $this->getParserOutput()->getCacheTime();
	}

	/**
	 * @return array
	 */
	public function getParamSettings(): array {
		return [
			'stash' => [
				Handler::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'boolean',
				ParamValidator::PARAM_DEFAULT => false,
				ParamValidator::PARAM_REQUIRED => false,
			],
			'flavor' => [
				Handler::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => self::OUTPUT_FLAVORS,
				ParamValidator::PARAM_DEFAULT => 'view',
				ParamValidator::PARAM_REQUIRED => false,
			],
		];
	}

	/**
	 * @return ParserOutput
	 */
	private function getParserOutput(): ParserOutput {
		if ( !$this->parserOutput ) {
			$parserOptions = ParserOptions::newFromAnon();

			if ( $this->pageLanguage ) {
				$parserOptions->setTargetLanguage( $this->pageLanguage );
			}

			// XXX: $parsoidOptions are really parser options, and they should be integrated with
			//      the ParserOptions class. That would allow us to use the ParserCache with
			//      various flavors.
			$parsoidOptions = $this->parsoidOptions;

			// NOTE: VisualEditor would set this flavor when transforming from Wikitext to HTML
			//       for the purpose of editing when doing parsefragment (in body only mode).
			if ( $this->flavor === 'fragment' ) {
				$parsoidOptions += [
					'body_only' => true,
					'wrapSections' => false
				];
			}

			// NOTE: ParsoidOutputAccess::getParserOutput() should be used for revisions
			//       that comes from the database. Either this revision is null to indicate
			//       the current revision or the revision must have an ID.
			// If we have a revision and the ID is 0 or null, then it's a fake revision
			// representing a preview.
			$fakeRevision = !$this->getRevisionId() && $this->revisionOrId !== null;
			$pageRecordAvailable = $this->page instanceof PageRecord;

			if ( $pageRecordAvailable && !$fakeRevision && !$parsoidOptions && $this->isCacheable ) {
				$status = $this->parsoidOutputAccess->getParserOutput(
					$this->page,
					$parserOptions,
					$this->revisionOrId,
					$this->parsoidOutputAccessOptions
				);
			} else {
				$status = $this->parsoidOutputAccess->parse(
					$this->page,
					$parserOptions,
					$parsoidOptions,
					$this->revisionOrId
				);
			}

			if ( !$status->isOK() ) {
				if ( $status->hasMessage( 'parsoid-client-error' ) ) {
					throw new LocalizedHttpException(
						MessageValue::new( 'rest-html-backend-error' ),
						400,
						[ 'reason' => $status->getErrors() ]
					);
				} elseif ( $status->hasMessage( 'parsoid-resource-limit-exceeded' ) ) {
					throw new LocalizedHttpException(
						MessageValue::new( 'rest-resource-limit-exceeded' ),
						413,
						[ 'reason' => $status->getErrors() ]
					);
				} else {
					throw new LocalizedHttpException(
						MessageValue::new( 'rest-html-backend-error' ),
						500,
						[ 'reason' => $status->getErrors() ]
					);
				}
			}

			$this->parserOutput = $status->getValue();
		}

		return $this->parserOutput;
	}

	/**
	 * The content language of the HTML output after parsing.
	 *
	 * @return string
	 */
	public function getHtmlOutputContentLanguage(): string {
		$pageBundleData = $this->getHtml()->getExtensionData(
			PageBundleParserOutputConverter::PARSOID_PAGE_BUNDLE_KEY
		);

		// XXX: We need a canonical way of getting the output language from
		//      ParserOutput since we may not be getting parser outputs from
		//		Parsoid always in the future.
		if ( !isset( $pageBundleData['headers']['content-language'] ) ) {
			throw new LogicException( 'Failed to find content language in page bundle data' );
		}

		return $pageBundleData['headers']['content-language'];
	}

	/**
	 * Set the HTTP headers based on the response generated
	 *
	 * @param ResponseInterface $response
	 * @param bool $forHtml Whether the response will be HTML (rather than JSON)
	 *
	 * @return void
	 */
	public function putHeaders( ResponseInterface $response, bool $forHtml = true ) {
		if ( $forHtml ) {
			// For HTML we want to set the Content-Language. For JSON, we probably don't.
			$response->setHeader( 'Content-Language', $this->getHtmlOutputContentLanguage() );

			$pb = $this->getPageBundle();
			ParsoidFormatHelper::setContentType( $response, ParsoidFormatHelper::FORMAT_HTML, $pb->version );
		}

		if ( $this->targetLanguageCode ) {
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
	 *
	 * @return PageBundle
	 */
	public function getPageBundle(): PageBundle {
		// XXX: converting between PageBundle and ParserOutput is inefficient!
		$parserOutput = $this->getParserOutput();
		return PageBundleParserOutputConverter::pageBundleFromParserOutput( $parserOutput );
	}

	/**
	 * Returns the ID of the revision that is being rendered.
	 * If this is not 0, the rendering is for a revision present in the database.
	 * If it is 0, the revision is a fake revision representing e.g. a preview.
	 *
	 * @return int
	 */
	public function getRevisionId(): int {
		return is_object( $this->revisionOrId ) ? (int)$this->revisionOrId->getId() : (int)$this->revisionOrId;
	}

}
