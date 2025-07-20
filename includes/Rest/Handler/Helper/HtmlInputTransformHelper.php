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
use MediaWiki\Edit\ParsoidOutputStash;
use MediaWiki\Edit\ParsoidRenderID;
use MediaWiki\Edit\SelserContext;
use MediaWiki\Exception\MWUnknownContentModelException;
use MediaWiki\Language\LanguageCode;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageLookup;
use MediaWiki\Page\PageRecord;
use MediaWiki\Page\ParserOutputAccess;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\HtmlToContentTransform;
use MediaWiki\Parser\Parsoid\HtmlTransformFactory;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Status\Status;
use Wikimedia\Bcp47Code\Bcp47Code;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\HtmlPageBundle;
use Wikimedia\Parsoid\Core\ResourceLimitExceededException;
use Wikimedia\Parsoid\Parsoid;
use Wikimedia\Stats\StatsFactory;

/**
 * REST helper for converting HTML to page content source (e.g. wikitext).
 *
 * @since 1.40
 *
 * @unstable Pending consolidation of the Parsoid extension with core code.
 */
class HtmlInputTransformHelper {
	/**
	 * @internal
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::ParsoidCacheConfig
	];

	/** @var PageIdentity|null */
	private $page = null;

	/**
	 * @var HtmlToContentTransform
	 */
	private $transform;

	/**
	 * @var array
	 */
	private $envOptions;

	private StatsFactory $statsFactory;
	private HtmlTransformFactory $htmlTransformFactory;
	private ParsoidOutputStash $parsoidOutputStash;
	private ParserOutputAccess $parserOutputAccess;
	private PageLookup $pageLookup;
	private RevisionLookup $revisionLookup;

	/**
	 * @param StatsFactory $statsFactory
	 * @param HtmlTransformFactory $htmlTransformFactory
	 * @param ParsoidOutputStash $parsoidOutputStash
	 * @param ParserOutputAccess $parserOutputAccess
	 * @param PageLookup $pageLookup
	 * @param RevisionLookup $revisionLookup
	 * @param array $envOptions
	 * @param ?PageIdentity $page
	 * @param array|string $body Body structure, or an HTML string
	 * @param array $parameters
	 * @param RevisionRecord|null $originalRevision
	 * @param Bcp47Code|null $pageLanguage
	 */
	public function __construct(
		StatsFactory $statsFactory,
		HtmlTransformFactory $htmlTransformFactory,
		ParsoidOutputStash $parsoidOutputStash,
		ParserOutputAccess $parserOutputAccess,
		PageLookup $pageLookup,
		RevisionLookup $revisionLookup,
		array $envOptions = [],
		?PageIdentity $page = null,
		$body = '',
		array $parameters = [],
		?RevisionRecord $originalRevision = null,
		?Bcp47Code $pageLanguage = null
	) {
		$this->statsFactory = $statsFactory;
		$this->htmlTransformFactory = $htmlTransformFactory;
		$this->parsoidOutputStash = $parsoidOutputStash;
		$this->envOptions = $envOptions + [
			'outputContentVersion' => Parsoid::defaultHTMLVersion(),
			'offsetType' => 'byte',
		];
		$this->parserOutputAccess = $parserOutputAccess;
		$this->pageLookup = $pageLookup;
		$this->revisionLookup = $revisionLookup;
		if ( $page === null ) {
			wfDeprecated( __METHOD__ . ' without $page', '1.43' );
		} else {
			$this->initInternal( $page, $body, $parameters, $originalRevision, $pageLanguage );
		}
	}

	public function getParamSettings(): array {
		// JSON body schema:
		/*
		doc:
			properties:
				headers:
					type: array
					items:
						type: string
				body:
					type: [ string, object ]
			required: [ body ]

		body:
			properties:
				offsetType:
					type: string
				revid:
					type: integer
				renderid:
					type: string
				etag:
					type: string
				html:
					type: [ doc, string ]
				data-mw:
					type: doc
				original:
					properties:
						html:
							type: doc
						source:
							type: doc
						data-mw:
							type: doc
						data-parsoid:
							type: doc
			required: [ html ]
		 */

		// FUTURE: more params
		// - slot (for loading the base content)

		return [
			// XXX: should we really declare this here? Or should end endpoint do this?
			//      We are not reading this property...
			'title' => [
				Handler::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_DEFAULT => '',
				ParamValidator::PARAM_REQUIRED => false,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-html-input-title' )
			],
			// XXX: Needed for compatibility with the parsoid transform endpoint.
			//      But revid should just be part of the info about the original data
			//      in the body.
			'oldid' => [
				Handler::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'int',
				ParamValidator::PARAM_DEFAULT => 0,
				ParamValidator::PARAM_REQUIRED => false,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-html-input-oldid' )
			],
			// XXX: Supported for compatibility with the parsoid transform endpoint.
			//      If given, it should be 'html' or 'pagebundle'.
			'from' => [
				Handler::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_DEFAULT => '',
				ParamValidator::PARAM_REQUIRED => false,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-html-input-from' )
			],
			// XXX: Supported for compatibility with the parsoid transform endpoint.
			//      Ignored.
			'format' => [
				Handler::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_DEFAULT => '',
				ParamValidator::PARAM_REQUIRED => false,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-html-input-format' )
			],
			'contentmodel' => [ // XXX: get this from the Accept header?
				Handler::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_DEFAULT => '',
				ParamValidator::PARAM_REQUIRED => false,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-html-input-contentmodel' )
			],
			'language' => [ // TODO: get this from Accept-Language header?!
				Handler::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_DEFAULT => '',
				ParamValidator::PARAM_REQUIRED => false,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-html-input-language' )
			]
		];
	}

	/**
	 * Modify body and parameters to provide compatibility with legacy endpoints.
	 *
	 * @see ParsoidHandler::getRequestAttributes
	 *
	 * @param array<string,mixed> &$body
	 * @param array<string,mixed> &$parameters
	 *
	 * @throws HttpException
	 *
	 * @return void
	 */
	private static function normalizeParameters( array &$body, array &$parameters ) {
		// If the revision ID is given in the path, pretend it was given in the body.
		if ( isset( $parameters['oldid'] ) && (int)$parameters['oldid'] > 0 ) {
			$body['original']['revid'] = (int)$parameters['oldid'];
		}

		// If an etag is given in the body, use it as the render ID.
		// Note that we support ETag format in the renderid field.
		if ( !empty( $body['original']['etag'] ) ) {
			// @phan-suppress-next-line PhanTypeInvalidDimOffset false positive
			$body['original']['renderid'] = $body['original']['etag'];
		}

		// Accept 'wikitext' as an alias for 'source'.
		if ( isset( $body['original']['wikitext'] ) ) {
			// @phan-suppress-next-line PhanTypeInvalidDimOffset false positive
			$body['original']['source'] = $body['original']['wikitext'];
			unset( $body['original']['wikitext'] );
		}

		// If 'from' is not set, we accept page bundle style input as well as full HTML.
		// If 'from' is set, we only accept page bundle style input if it is set to FORMAT_PAGEBUNDLE.
		if (
			isset( $parameters['from'] ) && $parameters['from'] !== '' &&
			$parameters['from'] !== ParsoidFormatHelper::FORMAT_PAGEBUNDLE
		) {
			unset( $body['original']['data-parsoid']['body'] );
			unset( $body['original']['data-mw']['body'] );
			unset( $body['data-mw']['body'] );
		}

		// If 'from' is given, it must be html or pagebundle.
		if (
			isset( $parameters['from'] ) && $parameters['from'] !== '' &&
			$parameters['from'] !== ParsoidFormatHelper::FORMAT_HTML &&
			$parameters['from'] !== ParsoidFormatHelper::FORMAT_PAGEBUNDLE
		) {
			throw new LocalizedHttpException(
				new MessageValue( "rest-unsupported-transform-input", [ $parameters['from'] ] ), 400
			);
		}

		if ( isset( $body['contentmodel'] ) && $body['contentmodel'] !== '' ) {
			$parameters['contentmodel'] = $body['contentmodel'];
		} elseif ( isset( $parameters['format'] ) && $parameters['format'] !== '' ) {
			$parameters['contentmodel'] = $parameters['format'];
		}
	}

	/**
	 * @param PageIdentity $page
	 * @param array|string $body Body structure, or an HTML string
	 * @param array $parameters
	 * @param RevisionRecord|null $originalRevision
	 * @param Bcp47Code|null $pageLanguage
	 *
	 * @throws HttpException
	 * @deprecated since 1.43; pass arguments to constructor instead
	 */
	public function init(
		PageIdentity $page,
		$body,
		array $parameters,
		?RevisionRecord $originalRevision = null,
		?Bcp47Code $pageLanguage = null
	) {
		wfDeprecated( __METHOD__, '1.43' );
		$this->initInternal( $page, $body, $parameters, $originalRevision, $pageLanguage );
	}

	/**
	 * @param PageIdentity $page
	 * @param array|string $body Body structure, or an HTML string
	 * @param array $parameters
	 * @param RevisionRecord|null $originalRevision
	 * @param Bcp47Code|null $pageLanguage
	 *
	 * @throws HttpException
	 */
	private function initInternal(
		PageIdentity $page,
		$body,
		array $parameters,
		?RevisionRecord $originalRevision = null,
		?Bcp47Code $pageLanguage = null
	) {
		if ( is_string( $body ) ) {
			$body = [ 'html' => $body ];
		}

		self::normalizeParameters( $body, $parameters );

		$this->page = $page;

		if ( !isset( $body['html'] ) ) {
			throw new LocalizedHttpException( new MessageValue( "rest-missing-body-field", [ 'html' ] ) );
		}

		$html = is_array( $body['html'] ) ? $body['html']['body'] : $body['html'];

		// TODO: validate $body against a proper schema.
		$this->transform = $this->htmlTransformFactory->getHtmlToContentTransform(
			$html,
			$this->page
		);

		$this->transform->setMetrics( $this->statsFactory );

		// NOTE: Env::getContentModel will fall back to the page's recorded content model
		//       if none is set here.
		$this->transform->setOptions( [
			'contentmodel' => $parameters['contentmodel'] ?? null,
			'offsetType' => $body['offsetType'] ?? $this->envOptions['offsetType'],
		] );

		$original = $body['original'] ?? [];
		$originalRendering = null;

		if ( !isset( $original['html'] ) && !empty( $original['renderid'] ) ) {
			$key = $original['renderid'];
			if ( preg_match( '!^(W/)?".*"$!', $key ) ) {
				$originalRendering = ParsoidRenderID::newFromETag( $key );

				if ( !$originalRendering ) {
					throw new LocalizedHttpException( new MessageValue( "rest-bad-etag", [ $key ] ), 400 );
				}
			} else {
				try {
					$originalRendering = ParsoidRenderID::newFromKey( $key );
				} catch ( InvalidArgumentException ) {
					throw new LocalizedHttpException(
						new MessageValue( 'rest-parsoid-bad-render-id', [ $key ] ),
						400
					);
				}
			}
		} elseif ( !empty( $original['html'] ) || !empty( $original['data-parsoid'] ) ) {
			// NOTE: We might have an incomplete HtmlPageBundle here, with no HTML but with data-parsoid!
			// XXX: Do we need to support that, or can that just be a 400?
			$originalRendering = new HtmlPageBundle(
				$original['html']['body'] ?? '',
				$original['data-parsoid']['body'] ?? null,
				$original['data-mw']['body'] ?? null,
				null, // will be derived from $original['html']['headers']['content-type']
				$original['html']['headers'] ?? []
			);
		}

		if ( !$originalRevision && !empty( $original['revid'] ) ) {
			$originalRevision = (int)$original['revid'];
		}

		if ( $originalRevision || $originalRendering ) {
			$this->setOriginal( $originalRevision, $originalRendering );
		} else {
			if ( $this->page->exists() ) {
				$this->statsFactory
					->getCounter( 'html_input_transform_total' )
					->setLabel( 'original_html_given', 'false' )
					->setLabel( 'page_exists', 'true' )
					->setLabel( 'status', 'unknown' )
					->copyToStatsdAt( 'html_input_transform.original_html.not_given.page_exists' )
					->increment();
			} else {
				$this->statsFactory
					->getCounter( 'html_input_transform_total' )
					->setLabel( 'original_html_given', 'false' )
					->setLabel( 'page_exists', 'false' )
					->setLabel( 'status', 'unknown' )
					->copyToStatsdAt( 'html_input_transform.original_html.not_given.page_not_exist' )
					->increment();
			}
		}

		if ( isset( $body['data-mw']['body'] ) ) {
			$this->transform->setModifiedDataMW( $body['data-mw']['body'] );
		}

		if ( $pageLanguage ) {
			$this->transform->setContentLanguage( $pageLanguage );
		} elseif ( isset( $parameters['language'] ) && $parameters['language'] !== '' ) {
			$pageLanguage = LanguageCode::normalizeNonstandardCodeAndWarn(
				$parameters['language']
			);
			$this->transform->setContentLanguage( $pageLanguage );
		}

		if ( isset( $original['source']['body'] ) ) {
			// XXX: do we really have to support wikitext overrides?
			$this->transform->setOriginalText( $original['source']['body'] );
		}
	}

	/**
	 * Return HTMLTransform object, so additional context can be provided by calling setters on it.
	 */
	public function getTransform(): HtmlToContentTransform {
		return $this->transform;
	}

	/**
	 * Set metrics sink.
	 */
	public function setMetrics( StatsFactory $statsFactory ) {
		$this->statsFactory = $statsFactory;

		if ( $this->transform ) {
			$this->transform->setMetrics( $statsFactory );
		}
	}

	/**
	 * Supply information about the revision and rendering that was the original basis of
	 * the input HTML. This is used to apply selective serialization (selser), if possible.
	 *
	 * @param RevisionRecord|int|null $rev
	 * @param ParsoidRenderID|HtmlPageBundle|ParserOutput|null $originalRendering
	 */
	public function setOriginal( $rev, $originalRendering ) {
		if ( $originalRendering instanceof ParsoidRenderID ) {
			$renderId = $originalRendering;

			// If the client asked for a render ID, load original data from stash
			try {
				$selserContext = $this->fetchSelserContextFromStash( $renderId );
			} catch ( InvalidArgumentException $ex ) {
				$this->statsFactory
					->getCounter( 'html_input_transform_total' )
					->setLabel( 'original_html_given', 'as_renderid' )
					->setLabel( 'page_exists', 'unknown' )
					->setLabel( 'status', 'bad_renderid' )
					->copyToStatsdAt( 'html_input_transform.original_html.given.as_renderid.bad' )
					->increment();
				throw new LocalizedHttpException( new MessageValue( "rest-bad-stash-key" ),
					400,
					[
						'reason' => $ex->getMessage(),
						'key' => "$renderId"
					]
				);
			}

			if ( !$selserContext ) {
				// NOTE: When the client asked for a specific stash key (resp. etag),
				//       we should fail with a 412 if we don't have the specific rendering.
				//       On the other hand, of the client only provided a base revision ID,
				//       we can re-parse and hope for the best.

				throw new LocalizedHttpException(
					new MessageValue( "rest-no-stashed-content", [ $renderId->getKey() ] ), 412
				);

				// TODO: This class should provide getETag and getLastModified methods for use by
				//       the REST endpoint, to provide proper support for conditionals.
				//       However, that requires some refactoring of how HTTP conditional checks
				//       work in the Handler base class.
			}

			if ( !$rev ) {
				$rev = $renderId->getRevisionID();
			}

			$originalRendering = $selserContext->getPageBundle();
			$content = $selserContext->getContent();

			if ( $content ) {
				$this->transform->setOriginalContent( $content );
			}
		} elseif ( !$originalRendering && $rev ) {
			// The client provided a revision ID, but not stash key.
			// Try to get a rendering for the given revision, and use it as the basis for selser.
			// Chances are good that the resulting diff will be reasonably clean.
			// NOTE: If we don't have a revision ID, we should not attempt selser!
			$originalRendering = $this->fetchParserOutputFromParsoid( $this->page, $rev, true );

			if ( $originalRendering ) {
				$this->statsFactory->getCounter( 'html_input_transform_total' )
					->setLabel( 'original_html_given', 'as_revid' )
					->setLabel( 'page_exists', 'unknown' )
					->setLabel( 'status', 'found' )
					->copyToStatsdAt( 'html_input_transform.original_html.given.as_revid.found' )
					->increment();
			} else {
				$this->statsFactory->getCounter( 'html_input_transform_total' )
					->setLabel( 'original_html_given', 'as_revid' )
					->setLabel( 'page_exists', 'unknown' )
					->setLabel( 'status', 'not_found' )
					->copyToStatsdAt( 'html_input_transform.original_html.given.as_revid.not_found' )
					->increment();
			}
		} elseif ( $originalRendering ) {
			$this->statsFactory->getCounter( 'html_input_transform_total' )
				->setLabel( 'original_html_given', 'true' )
				->setLabel( 'page_exists', 'unknown' )
				->setLabel( 'status', 'verbatim' )
				->copyToStatsdAt( 'html_input_transform.original_html.given.verbatim' )
				->increment();
		}

		if ( $originalRendering instanceof ParserOutput ) {
			$originalRendering = PageBundleParserOutputConverter::pageBundleFromParserOutput( $originalRendering );

			// NOTE: Use the default if we got a ParserOutput object.
			//       Don't apply the default if we got passed a HtmlPageBundle,
			//       in that case, we want to require the version to be explicit.
			if ( $originalRendering->version === null && !isset( $originalRendering->headers['content-type'] ) ) {
				$originalRendering->version = Parsoid::defaultHTMLVersion();
			}
		}

		if ( !$originalRendering instanceof HtmlPageBundle ) {
			return;
		}

		if ( $originalRendering->version !== null ) {
			$this->transform->setOriginalSchemaVersion( $originalRendering->version );
		} elseif ( !empty( $originalRendering->headers['content-type'] ) ) {
			$vOriginal = ParsoidFormatHelper::parseContentTypeHeader(
				// @phan-suppress-next-line PhanTypeArraySuspiciousNullable Silly Phan, we just checked.
				$originalRendering->headers['content-type']
			);

			if ( $vOriginal ) {
				$this->transform->setOriginalSchemaVersion( $vOriginal );
			}
		}

		if ( $rev instanceof RevisionRecord ) {
			$this->transform->setOriginalRevision( $rev );
		} elseif ( $rev && is_int( $rev ) ) {
			$this->transform->setOriginalRevisionId( $rev );
		}

		// NOTE: We might have an incomplete HtmlPageBundle here, with no HTML.
		//       HtmlPageBundle::$html is declared to not be nullable, so it would be set to the empty
		//       string if not given.
		if ( $originalRendering->html !== '' ) {
			$this->transform->setOriginalHtml( $originalRendering->html );
		}

		$originalDataParsoid = $originalRendering->parsoid;
		if ( $originalDataParsoid !== null ) {
			$this->transform->setOriginalDataParsoid( $originalDataParsoid );
		}

		$originalDataMW = $originalRendering->mw;
		if ( $originalDataMW !== null ) {
			$this->transform->setOriginalDataMW( $originalDataMW );
		}
	}

	/**
	 * @return Content the content derived from the input HTML.
	 * @throws HttpException
	 */
	public function getContent(): Content {
		try {
			return $this->transform->htmlToContent();
		} catch ( ClientError $e ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-html-backend-error', [ $e->getMessage() ] ),
				400,
				[ 'reason' => $e->getMessage() ]
			);
		} catch ( ResourceLimitExceededException $e ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-resource-limit-exceeded' ),
				413,
				[ 'reason' => $e->getMessage() ]
			);
		} catch ( MWUnknownContentModelException $e ) {
			throw new LocalizedHttpException(
				new MessageValue( "rest-unknown-content-model", [ $e->getModelId() ] ),
				400
			);
		}
	}

	/**
	 * Creates a response containing the content derived from the input HTML.
	 * This will set the appropriate Content-Type header.
	 */
	public function putContent( ResponseInterface $response ) {
		$content = $this->getContent();
		$data = $content->serialize();

		try {
			$contentType = ParsoidFormatHelper::getContentType(
				$content->getModel(),
				$this->envOptions['outputContentVersion']
			);
		} catch ( InvalidArgumentException ) {
			// If Parsoid doesn't know the content type,
			// ask the ContentHandler!
			$contentType = $content->getDefaultFormat();
		}

		$response->setHeader( 'Content-Type', $contentType );
		$response->getBody()->write( $data );
	}

	/**
	 * @param PageIdentity $page
	 * @param RevisionRecord|int $revision
	 * @param bool $mayParse
	 *
	 * @return ParserOutput|null
	 * @throws HttpException
	 */
	private function fetchParserOutputFromParsoid( PageIdentity $page, $revision, bool $mayParse ): ?ParserOutput {
		$parserOptions = ParserOptions::newFromAnon();
		$parserOptions->setUseParsoid();

		try {
			if ( !$page instanceof PageRecord ) {
				$name = "$page";
				$page = $this->pageLookup->getPageByReference( $page );
				if ( !$page ) {
					throw new RevisionAccessException( 'Page {name} not found',
						[ 'name' => $name ] );
				}
			}

			if ( is_int( $revision ) ) {
				$revId = $revision;
				$revision = $this->revisionLookup->getRevisionById( $revId, 0, $page );

				if ( !$revision ) {
					throw new RevisionAccessException( 'Revision {revId} not found',
						[ 'revId' => $revId ] );
				}
			}

			if ( $page->getId() !== $revision->getPageId() ) {
				throw new RevisionAccessException( 'Revision {revId} does not belong to page {name}',
					[ 'name' => $page->getDBkey(),
						'revId' => $revision->getId() ] );
			}

			if ( $mayParse ) {
				try {
					$status = $this->parserOutputAccess->getParserOutput(
						$page, $parserOptions, $revision
					);
				} catch ( ClientError $e ) {
					$status = Status::newFatal( 'parsoid-client-error', $e->getMessage() );
				} catch ( ResourceLimitExceededException $e ) {
					$status = Status::newFatal( 'parsoid-resource-limit-exceeded', $e->getMessage() );
				}

				if ( !$status->isOK() ) {
					$this->throwHttpExceptionForStatus( $status );
				}

				$parserOutput = $status->getValue();
			} else {
				$parserOutput = $this->parserOutputAccess->getCachedParserOutput(
					$page, $parserOptions, $revision
				);
			}
		} catch ( RevisionAccessException $e ) {
			// The client supplied bad revision ID, or the revision was deleted or suppressed.
			throw new LocalizedHttpException( new MessageValue( "rest-specified-revision-unavailable" ),
				404,
				[ 'reason' => $e->getMessage() ]
			);
		}

		return $parserOutput;
	}

	/**
	 * @param ParsoidRenderID $renderID
	 *
	 * @return SelserContext|null
	 */
	private function fetchSelserContextFromStash( $renderID ): ?SelserContext {
		$selserContext = $this->parsoidOutputStash->get( $renderID );
		$labels = [
			'original_html_given' => 'as_renderid',
			'page_exists' => 'unknown',
			'status' => 'hit-stashed'
		];
		$counter = $this->statsFactory->getCounter( 'html_input_transform_total' );
		if ( $selserContext ) {
			$counter->setLabels( $labels )
				->copyToStatsdAt( 'html_input_transform.original_html.given.as_renderid.stash_hit.found.hit' )
				->increment();
			return $selserContext;
		} else {
			// Looks like the rendering is gone from stash (or the client send us a bogus key).
			// Try to load it from the parser cache instead.
			// On a wiki with low edit frequency, there is a good chance that it's still there.
			try {
				$parserOutput = $this->fetchParserOutputFromParsoid( $this->page, $renderID->getRevisionID(), false );

				if ( !$parserOutput ) {
					$labels[ 'status' ] = 'miss-fallback_not_found';
					$counter->setLabels( $labels )->copyToStatsdAt(
						'html_input_transform.original_html.given.as_renderid.stash_miss_pc_fallback.not_found.miss'
					)->increment();
					return null;
				}

				$cachedRenderID = ParsoidRenderID::newFromParserOutput( $parserOutput );
				if ( $cachedRenderID->getKey() !== $renderID->getKey() ) {
					$labels[ 'status' ] = 'mismatch-fallback_not_found';
					$counter->setLabels( $labels )
						->copyToStatsdAt(
							'html_input_transform.original_html.given.as_renderid.' .
							'stash_miss_pc_fallback.not_found.mismatch'
						)
						->increment();

					// It's not the correct rendering.
					return null;
				}
				$labels[ 'status' ] = 'hit-fallback_found';
				$counter->setLabels( $labels )
					->copyToStatsdAt(
						'html_input_transform.original_html.given.as_renderid.' .
						'stash_miss_pc_fallback.found.hit'
					)
					->increment();

				$pb = PageBundleParserOutputConverter::pageBundleFromParserOutput( $parserOutput );
				return new SelserContext( $pb, $renderID->getRevisionID() );
			} catch ( HttpException ) {
				$labels[ 'status' ] = 'failed-fallback_not_found';
				$counter->setLabels( $labels )
					->copyToStatsdAt(
						'html_input_transform.original_html.given.as_renderid.' .
						'stash_miss_pc_fallback.not_found.failed'
					)
					->increment();

				// If the revision isn't found, don't trigger a 404. Return null to trigger a 412.
				return null;
			}
		}
	}

	/**
	 * @param Status $status
	 *
	 * @return never
	 * @throws HttpException
	 */
	private function throwHttpExceptionForStatus( Status $status ) {
		// TODO: make this nicer.
		if ( $status->hasMessage( 'parsoid-resource-limit-exceeded' ) ) {
			throw new LocalizedHttpException( new MessageValue( "rest-parsoid-resource-exceeded" ),
				413,
				[ 'reason' => $status->getHTML() ]
			);
		} else {
			throw new LocalizedHttpException( new MessageValue( "rest-parsoid-error" ),
				400,
				[ 'reason' => $status->getHTML() ]
			);
		}
	}

}
