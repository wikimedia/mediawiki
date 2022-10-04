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
 * @ingroup Page
 */
namespace MediaWiki\Rest\Handler;

use Content;
use IBufferingStatsdDataFactory;
use InvalidArgumentException;
use Language;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use MediaWiki\Edit\ParsoidOutputStash;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Parser\Parsoid\HTMLTransform;
use MediaWiki\Parser\Parsoid\HTMLTransformFactory;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWiki\Parser\Parsoid\ParsoidOutputAccess;
use MediaWiki\Parser\Parsoid\ParsoidRenderID;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionRecord;
use MWUnknownContentModelException;
use ParserOptions;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\Core\ResourceLimitExceededException;
use Wikimedia\Parsoid\Parsoid;

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
	 * @var string[]
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::ParsoidCacheConfig
	];

	/** @var HTMLTransformFactory */
	private $htmlTransformFactory;

	/** @var PageIdentity|null */
	private $page = null;

	/** @var IBufferingStatsdDataFactory */
	private $stats;

	/** @var array|null */
	private $parameters = null;

	/**
	 * @var HTMLTransform
	 */
	private $transform;

	/**
	 * @var ParsoidOutputStash
	 */
	private $parsoidOutputStash;

	/**
	 * @var ParsoidOutputAccess
	 */
	private $parsoidOutputAccess;

	/**
	 * @var array
	 */
	private $envOptions;

	/**
	 * @param StatsdDataFactoryInterface $statsDataFactory
	 * @param HTMLTransformFactory $htmlTransformFactory
	 * @param ParsoidOutputStash $parsoidOutputStash
	 * @param ParsoidOutputAccess $parsoidOutputAccess
	 * @param array $envOptions
	 */
	public function __construct(
		StatsdDataFactoryInterface $statsDataFactory,
		HTMLTransformFactory $htmlTransformFactory,
		ParsoidOutputStash $parsoidOutputStash,
		ParsoidOutputAccess $parsoidOutputAccess,
		array $envOptions = []
	) {
		$this->stats = $statsDataFactory;
		$this->htmlTransformFactory = $htmlTransformFactory;
		$this->parsoidOutputStash = $parsoidOutputStash;
		$this->envOptions = $envOptions + [
			'outputContentVersion' => Parsoid::defaultHTMLVersion(),
			'offsetType' => 'byte',
		];
		$this->parsoidOutputAccess = $parsoidOutputAccess;
	}

	/**
	 * @return array
	 */
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
			],
			// XXX: Needed for compatibility with the parsoid transform endpoint.
			//      But revid should just be part of the info about the original data
			//      in the body.
			'oldid' => [
				Handler::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'int',
				ParamValidator::PARAM_DEFAULT => 0,
				ParamValidator::PARAM_REQUIRED => false,
			],
			// XXX: Supported for compatibility with the parsoid transform endpoint.
			//      If given, it should be 'html' or 'pagebundle'.
			'from' => [
				Handler::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_DEFAULT => '',
				ParamValidator::PARAM_REQUIRED => false,
			],
			// XXX: Supported for compatibility with the parsoid transform endpoint.
			//      Ignored.
			'format' => [
				Handler::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_DEFAULT => '',
				ParamValidator::PARAM_REQUIRED => false,
			],
			'contentmodel' => [ // XXX: get this from the Accept header?
				Handler::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_DEFAULT => '',
				ParamValidator::PARAM_REQUIRED => false,
			],
			'language' => [ // TODO: get this from Accept-Language header?!
				Handler::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_DEFAULT => '',
				ParamValidator::PARAM_REQUIRED => false,
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
		// fetchOriginalDataFromStash() will detect the Etag format.
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
			throw new HttpException( 'Unsupported input: ' . $parameters['from'], 400 );
		}

		if ( isset( $body['contentmodel'] ) && $body['contentmodel'] !== '' ) {
			$parameters['contentmodel'] = $body['contentmodel'];
		} elseif ( isset( $parameters['format'] ) && $parameters['format'] !== '' ) {
			$parameters['contentmodel'] = $parameters['format'];
		}
	}

	/**
	 * @param PageIdentity $page
	 * @param array $body
	 * @param array $parameters
	 * @param RevisionRecord|null $revision
	 * @param Language|null $pageLanguage
	 *
	 * @throws HttpException
	 */
	public function init(
		PageIdentity $page,
		array $body,
		array $parameters,
		?RevisionRecord $revision = null,
		?Language $pageLanguage = null
	) {
		self::normalizeParameters( $body, $parameters );

		$this->page = $page;
		$this->parameters = $parameters;

		if ( !isset( $body['html'] ) ) {
			throw new HttpException( 'Expected `html` key in body' );
		}

		$html = is_array( $body['html'] ) ? $body['html']['body'] : $body['html'];

		// TODO: validate $body against a proper schema.
		$this->transform = $this->htmlTransformFactory->getHTMLTransform(
			$html,
			$this->page
		);

		$this->transform->setMetrics( $this->stats );

		// NOTE: Env::getContentModel will fall back to the page's recorded content model
		//       if none is set here.
		$this->transform->setOptions( [
			'contentmodel' => $this->parameters['contentmodel'] ?? null,
			'offsetType' => $body['offsetType'] ?? $this->envOptions['offsetType'],
		] );

		if ( !isset( $body['original']['html'] ) && !empty( $body['original']['renderid'] ) ) {
			// If the client asked for a render ID, load original data from stash
			try {
				$original = $this->fetchOriginalDataFromStash( $page, $body['original']['renderid'] );
			} catch ( InvalidArgumentException $ex ) {
				throw new HttpException(
					'Bad stash key',
					400,
					[
						'reason' => $ex->getMessage(),
						'key' => $body['original']['renderid']
					]
				);
			}

			if ( !$original ) {
				// NOTE: When the client asked for a specific stash key (resp. etag),
				//       we should fail with a 412 if we don't have the specific rendering.
				//       On the other hand, of the client only provided a base revision ID,
				//       we can re-parse and hope for the best.

				throw new HttpException(
					'No stashed content found for ' . $body['original']['renderid'], 412
				);

				// TODO: This class should provide getETag and getLastModified methods for use by
				//       the REST endpoint, to provide proper support for conditionals.
				//       However, that requires some refactoring of how HTTP conditional checks
				//       work in the Handler base class.
			}
		} else {
			$original = $body['original'] ?? [];
		}

		// NOTE: We may have an 'original' key that contains no html, but
		//       just wikitext. We can ignore that here, we only care if there is HTML.
		if ( !empty( $original['html']['headers']['content-type'] ) ) {
			$vOriginal = ParsoidFormatHelper::parseContentTypeHeader(
				$original['html']['headers']['content-type']
			);

			if ( $vOriginal ) {
				$this->transform->setOriginalSchemaVersion( $vOriginal );
			}
		}

		if ( $revision ) {
			$this->transform->setOriginalRevision( $revision );
		} elseif ( isset( $original['revid'] ) ) {
			$this->transform->setOriginalRevisionId( $original['revid'] );
		}

		if ( isset( $original['html']['body'] ) ) {
			$this->transform->setOriginalHtml( $original['html']['body'] );
		}

		if ( isset( $original['data-parsoid']['body'] ) ) {
			$this->transform->setOriginalDataParsoid( $original['data-parsoid']['body'] );
		}

		if ( isset( $original['data-mw']['body'] ) ) {
			$this->transform->setOriginalDataMW( $original['data-mw']['body'] );
		}

		if ( isset( $body['data-mw']['body'] ) ) {
			$this->transform->setModifiedDataMW( $body['data-mw']['body'] );
		}

		if ( $pageLanguage ) {
			$this->transform->setContentLanguage( $pageLanguage->getCode() );
		} elseif ( isset( $parameters['language'] ) && $parameters['language'] !== '' ) {
			$this->transform->setContentLanguage( $parameters['language'] );
		}

		if ( isset( $original['source']['body'] ) ) {
			// XXX: do we really have to support wikitext overrides?
			$this->transform->setOriginalText( $original['source']['body'] );
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
				new MessageValue( 'rest-html-backend-error' ),
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
			throw new HttpException( $e->getMessage(), 400 );
		}
	}

	/**
	 * Creates a response containing the content derived from the input HTML.
	 * This will set the appropriate Content-Type header.
	 *
	 * @param ResponseInterface $response
	 */
	public function putContent( ResponseInterface $response ) {
		$content = $this->getContent();
		$data = $content->serialize();

		try {
			$contentType = ParsoidFormatHelper::getContentType(
				$content->getModel(),
				$this->envOptions['outputContentVersion']
			);
		} catch ( InvalidArgumentException $e ) {
			// If Parsoid doesn't know the content type,
			// ask the ContentHandler!
			$contentType = $content->getDefaultFormat();
		}

		$response->setHeader( 'Content-Type', $contentType );
		$response->getBody()->write( $data );
	}

	private function fetchOriginalDataFromCache( PageIdentity $page, ?int $revId ): ?array {
		$parserOptions = ParserOptions::newFromAnon();

		try {
			$parserOutput = $this->parsoidOutputAccess->getCachedParserOutput(
				$page,
				$parserOptions,
				$revId
			);
		} catch ( RevisionAccessException $e ) {
			// The client supplied bad revision ID, or the revision was deleted or suppressed.
			throw new HttpException(
				'The specified revision does not exist.',
				404,
				[ 'reason' => $e->getMessage() ]
			);
		}

		if ( !$parserOutput ) {
			return null;
		}

		$pb = PageBundleParserOutputConverter::pageBundleFromParserOutput( $parserOutput );
		$renderID = $this->parsoidOutputAccess->getParsoidRenderID( $parserOutput );

		return $this->makeOriginalDataArrayFromPageBundle( $pb, $renderID );
	}

	private function fetchOriginalDataFromStash( PageIdentity $page, string $key ): ?array {
		if ( preg_match( '!^(W/)?".*"$!', $key ) ) {
			$renderID = ParsoidRenderID::newFromETag( $key );
		} else {
			$renderID = ParsoidRenderID::newFromKey( $key );
		}

		$pb = $this->parsoidOutputStash->get( $renderID );

		if ( !$pb ) {
			// Looks like the rendering is gone from stash (or the client send us a bogus key).
			// Try to load it from the parser cache instead.
			// On a wiki with low edit frequency, there is a good chance that it's still there.
			try {
				$original = $this->fetchOriginalDataFromCache( $page, $renderID->getRevisionID() );
			} catch ( HttpException $e ) {
				// If the revision isn't found, don't trigger a 404. Return null to trigger a 412.
				return null;
			}

			if ( !$original || $original['html']['key'] !== $renderID->getKey() ) {
				// Nothing found in the parser cache, or it's not the correct rendering.
				return null;
			}

			return $original;
		}

		return $this->makeOriginalDataArrayFromPageBundle( $pb, $renderID );
	}

	/**
	 * @param PageBundle $pb
	 * @param ParsoidRenderID $renderID
	 *
	 * @return array
	 */
	private function makeOriginalDataArrayFromPageBundle(
		PageBundle $pb,
		ParsoidRenderID $renderID
	): array {
		$original = [
			'contentmodel' => $pb->contentmodel,
			'revid' => $renderID->getRevisionID(),
			'html' => [
				'version' => $pb->version,
				'headers' => $pb->headers ?: [],
				'body' => $pb->html,
				'key' => $renderID->getKey(),
			],
			'data-parsoid' => [
				'body' => $pb->parsoid,
			],
			'data-mw' => [
				'body' => $pb->mw,
			],
		];

		if ( $pb->version ) {
			$original['html']['headers']['content-type'] =
				ParsoidFormatHelper::getContentType( 'html', $pb->version );
		}

		return $original;
	}

}
