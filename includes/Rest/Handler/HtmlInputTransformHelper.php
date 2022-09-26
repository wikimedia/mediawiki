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
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Parser\Parsoid\HTMLTransform;
use MediaWiki\Parser\Parsoid\HTMLTransformFactory;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\Revision\RevisionRecord;
use MWUnknownContentModelException;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Parsoid\Core\ClientError;
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
	 * @var array
	 */
	private $envOptions;

	/**
	 * @param IBufferingStatsdDataFactory $statsDataFactory
	 * @param HTMLTransformFactory $htmlTransformFactory
	 * @param array $envOptions
	 */
	public function __construct(
		IBufferingStatsdDataFactory $statsDataFactory,
		HTMLTransformFactory $htmlTransformFactory,
		array $envOptions = []
	) {
		$this->stats = $statsDataFactory;
		$this->htmlTransformFactory = $htmlTransformFactory;
		$this->envOptions = $envOptions + [
			'outputContentVersion' => Parsoid::defaultHTMLVersion(),
			'offsetType' => 'byte',
		];
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
				oldid:
					type: integer
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
			//      But revid should probably just be part of the info about the original data
			//      in the body.
			'revid' => [
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
			],
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
		if ( isset( $parameters['revid'] ) && $parameters['revid'] > 0 ) {
			$body['original']['oldid'] = (int)$parameters['revid'];
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

		if ( $revision ) {
			$this->transform->setOriginalRevision( $revision );
		} elseif ( isset( $this->parameters['oldid'] ) ) {
			$this->transform->setOriginalRevisionId( $this->parameters['oldid'] );
		}

		$original = $body['original'] ?? [];

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

		// TODO: load original data if 'use-stash' is set.
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

}
