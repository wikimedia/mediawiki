<?php
/**
 * Copyright (C) 2011-2020 Wikimedia Foundation and others.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

namespace MediaWiki\Rest\Handler;

use Composer\Semver\Semver;
use ExtensionRegistry;
use InvalidArgumentException;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use LogicException;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\Parsoid\HTMLTransform;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\ResponseException;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\SlotRecord;
use MobileContext;
use ParserOutput;
use RequestContext;
use Title;
use WikiMap;
use Wikimedia\Http\HttpAcceptParser;
use Wikimedia\Message\DataMessageValue;
use Wikimedia\Parsoid\Config\DataAccess;
use Wikimedia\Parsoid\Config\PageConfig;
use Wikimedia\Parsoid\Config\PageConfigFactory;
use Wikimedia\Parsoid\Config\SiteConfig;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\Core\ResourceLimitExceededException;
use Wikimedia\Parsoid\DOM\Document;
use Wikimedia\Parsoid\Parsoid;
use Wikimedia\Parsoid\Utils\ContentUtils;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMUtils;
use Wikimedia\Parsoid\Utils\Timing;
use WikitextContent;

/**
 * Base class for Parsoid handlers.
 */
abstract class ParsoidHandler extends Handler {
	// TODO logging, timeouts(?), CORS
	// TODO content negotiation (routes.js routes.acceptable)
	// TODO handle MaxConcurrentCallsError (pool counter?)

	/** @var array Parsoid-specific settings array from $config */
	private $parsoidSettings;

	/** @var SiteConfig */
	protected $siteConfig;

	/** @var PageConfigFactory */
	protected $pageConfigFactory;

	/** @var DataAccess */
	protected $dataAccess;

	/** @var ExtensionRegistry */
	protected $extensionRegistry;

	/** @var ?StatsdDataFactoryInterface A statistics aggregator */
	protected $metrics;

	/** @var array */
	private $requestAttributes;

	/**
	 * @return static
	 */
	public static function factory(): ParsoidHandler {
		$services = MediaWikiServices::getInstance();
		// @phan-suppress-next-line PhanTypeInstantiateAbstractStatic
		return new static(
			$services->getMainConfig()->get( MainConfigNames::ParsoidSettings ),
			$services->getParsoidSiteConfig(),
			$services->getParsoidPageConfigFactory(),
			$services->getParsoidDataAccess()
		);
	}

	/**
	 * @param array $parsoidSettings
	 * @param SiteConfig $siteConfig
	 * @param PageConfigFactory $pageConfigFactory
	 * @param DataAccess $dataAccess
	 */
	public function __construct(
		array $parsoidSettings,
		SiteConfig $siteConfig,
		PageConfigFactory $pageConfigFactory,
		DataAccess $dataAccess
	) {
		$this->parsoidSettings = $parsoidSettings;
		$this->siteConfig = $siteConfig;
		$this->pageConfigFactory = $pageConfigFactory;
		$this->dataAccess = $dataAccess;
		$this->extensionRegistry = ExtensionRegistry::getInstance();
		$this->metrics = $siteConfig->metrics();
	}

	/**
	 * Verify that the {domain} path parameter matches the actual domain.
	 * @todo Remove this when we no longer need to support the {domain}
	 *       parameter with backwards compatibility with the parsoid
	 *       extension.
	 * @param string $domain Domain name parameter to validate
	 */
	protected function assertDomainIsCorrect( $domain ): void {
		// We are cutting some corners here (IDN, non-ASCII casing)
		// since domain name support is provisional.
		// TODO use a proper validator instead
		$server = \RequestContext::getMain()->getConfig()->get( MainConfigNames::Server );
		$expectedDomain = wfParseUrl( $server )['host'] ?? null;
		if ( !$expectedDomain ) {
			throw new LogicException( 'Cannot parse $wgServer' );
		}
		if ( strcasecmp( $expectedDomain, $domain ) === 0 ) {
			return;
		}

		// TODO: This should really go away! It's only acceptable because
		//       this entire method is going to be removed once we no longer
		//       need the parsoid extension endpoints with the {domain} parameter.
		if ( $this->extensionRegistry->isLoaded( 'MobileFrontend' ) ) {
			// @phan-suppress-next-line PhanUndeclaredClassMethod
			$mobileServer = MobileContext::singleton()->getMobileUrl( $server );
			$expectedMobileDomain = wfParseUrl( $mobileServer )['host'] ?? null;
			if ( $expectedMobileDomain && strcasecmp( $expectedMobileDomain, $domain ) === 0 ) {
				return;
			}
		}

		$msg = new DataMessageValue(
			'mwparsoid-invalid-domain',
			[],
			'invalid-domain',
			[ 'expected' => $expectedDomain, 'actual' => $domain, ]
		);

		throw new LocalizedHttpException( $msg, 400, [
			'error' => 'parameter-validation-failed',
			'name' => 'domain',
			'value' => $domain,
			'failureCode' => $msg->getCode(),
			'failureData' => $msg->getData(),
		] );
	}

	/**
	 * Get the parsed body by content-type
	 *
	 * @return array
	 */
	protected function getParsedBody(): array {
		$request = $this->getRequest();
		[ $contentType ] = explode( ';', $request->getHeader( 'Content-Type' )[0] ?? '', 2 );
		switch ( $contentType ) {
			case 'application/x-www-form-urlencoded':
			case 'multipart/form-data':
				return $request->getPostParams();
			case 'application/json':
				$json = json_decode( $request->getBody()->getContents(), true );
				if ( !is_array( $json ) ) {
					throw new HttpException( 'Payload does not JSON decode to an array.', 400 );
				}
				return $json;
			default:
				throw new HttpException( 'Unsupported Media Type', 415 );
		}
	}

	/**
	 * Rough equivalent of req.local from Parsoid-JS.
	 * FIXME most of these should be replaced with more native ways of handling the request.
	 * @return array
	 */
	protected function &getRequestAttributes(): array {
		if ( $this->requestAttributes ) {
			return $this->requestAttributes;
		}

		$request = $this->getRequest();
		$body = ( $request->getMethod() === 'POST' ) ? $this->getParsedBody() : [];
		$opts = array_merge( $body, array_intersect_key( $request->getPathParams(),
			[ 'from' => true, 'format' => true ] ) );
		'@phan-var array<string,array|bool|string> $opts'; // @var array<string,array|bool|string> $opts
		$attribs = [
			'titleMissing' => empty( $request->getPathParams()['title'] ),
			'pageName' => $request->getPathParam( 'title' ) ?? '',
			'oldid' => $request->getPathParam( 'revision' ),
			// "body_only" flag to return just the body (instead of the entire HTML doc)
			// We would like to deprecate use of this flag: T181657
			'body_only' => $request->getQueryParams()['body_only'] ?? $body['body_only'] ?? null,
			'errorEnc' => ParsoidFormatHelper::ERROR_ENCODING[$opts['format']] ?? 'plain',
			'iwp' => WikiMap::getCurrentWikiId(), // PORT-FIXME verify
			'subst' => (bool)( $request->getQueryParams()['subst'] ?? $body['subst'] ?? null ),
			'offsetType' => $body['offsetType']
				?? $request->getQueryParams()['offsetType']
				// Lint requests should return UCS2 offsets by default
				?? ( $opts['format'] === ParsoidFormatHelper::FORMAT_LINT ? 'ucs2' : 'byte' ),
			'pagelanguage' => $request->getHeaderLine( 'Content-Language' ) ?: null,
		];

		if ( $request->getMethod() === 'POST' ) {
			if ( isset( $opts['original']['revid'] ) ) {
				$attribs['oldid'] = $opts['original']['revid'];
			}
			if ( isset( $opts['original']['title'] ) ) {
				$attribs['titleMissing'] = false;
				$attribs['pageName'] = $opts['original']['title'];
			}
		}
		if ( $attribs['oldid'] !== null ) {
			if ( $attribs['oldid'] === '' ) {
				$attribs['oldid'] = null;
			} else {
				$attribs['oldid'] = (int)$attribs['oldid'];
			}
		}

		$attribs['envOptions'] = [
			// We use `prefix` but ought to use `domain` (T206764)
			'prefix' => $attribs['iwp'],
			// For the legacy "domain" path parameter used by the endpoints exposed
			// by the parsoid extension. Will be null for core endpoints.
			'domain' => $request->getPathParam( 'domain' ),
			'pageName' => $attribs['pageName'],
			'offsetType' => $attribs['offsetType'],
			'cookie' => $request->getHeaderLine( 'Cookie' ),
			'reqId' => $request->getHeaderLine( 'X-Request-Id' ),
			'userAgent' => $request->getHeaderLine( 'User-Agent' ),
			'htmlVariantLanguage' => $request->getHeaderLine( 'Accept-Language' ) ?: null,
			// Semver::satisfies checks below expect a valid outputContentVersion value.
			// Better to set it here instead of adding the default value at every check.
			'outputContentVersion' => Parsoid::defaultHTMLVersion(),
		];
		$attribs['opts'] = $opts;

		// TODO: Remove assertDomainIsCorrect() once we no longer need to support the {domain}
		//       parameter for the endpoints exposed by the parsoid extension.
		if ( empty( $this->parsoidSettings['debugApi'] ) && $attribs['envOptions']['domain'] !== null ) {
			$this->assertDomainIsCorrect( $attribs['envOptions']['domain'] );
		}

		$this->requestAttributes = $attribs;
		return $this->requestAttributes;
	}

	protected function getHTMLTransform(
		array $attribs,
		string $html,
		PageConfig $pageConfig,
		array $parsoidSettings
	): HTMLTransform {
		$transform = new HTMLTransform( $html, $pageConfig, $this->newParsoid(), $parsoidSettings );

		if ( $this->metrics ) {
			$transform->setMetrics( $this->metrics );
		}

		$transform->setOptions( [
			'contentmodel' => $attribs['opts']['contentmodel'] ?? null,
			'offsetType' => $attribs['offsetType'] ?? 'byte',
		] );

		if ( isset( $attribs['oldid'] ) ) {
			$transform->setOriginalRevisionId( $attribs['oldid'] );
		}

		$original = $attribs['opts']['original'] ?? [];

		// NOTE: We may have an 'original' key that contains no html, but
		//       just wikitext. We can ignore that here, we only care if there is HTML.
		if ( !empty( $original['html']['headers']['content-type'] ) ) {
			$vOriginal = ParsoidFormatHelper::parseContentTypeHeader(
				$original['html']['headers']['content-type']
			);

			if ( $vOriginal ) {
				$transform->setOriginalSchemaVersion( $vOriginal );
			}
		}

		if ( isset( $original['html']['body'] ) ) {
			$transform->setOriginalHtml( $original['html']['body'] );
		}

		if ( $attribs['opts']['from'] === ParsoidFormatHelper::FORMAT_PAGEBUNDLE ) {
			if ( isset( $original['data-parsoid']['body'] ) ) {
				$transform->setOriginalDataParsoid( $original['data-parsoid']['body'] );
			}

			if ( isset( $original['data-mw']['body'] ) ) {
				$transform->setOriginalDataMW( $original['data-mw']['body'] );
			}
		}

		if ( isset( $attribs['opts']['data-mw']['body'] ) ) {
			$transform->setModifiedDataMW( $attribs['opts']['data-mw']['body'] );
		}

		return $transform;
	}

	/**
	 * FIXME: Combine with ParsoidFormatHelper::parseContentTypeHeader
	 */
	private const NEW_SPEC =
		'#^https://www.mediawiki.org/wiki/Specs/(HTML|pagebundle)/(\d+\.\d+\.\d+)$#D';

	/**
	 * This method checks if we support the requested content formats
	 * As a side-effect, it updates $attribs to set outputContentVersion
	 * that Parsoid should generate based on request headers.
	 *
	 * @param array &$attribs Request attributes from getRequestAttributes()
	 * @return bool
	 */
	protected function acceptable( array &$attribs ): bool {
		$request = $this->getRequest();
		$format = $attribs['opts']['format'];

		if ( $format === ParsoidFormatHelper::FORMAT_WIKITEXT ) {
			return true;
		}

		$acceptHeader = $request->getHeader( 'Accept' );
		if ( !$acceptHeader ) {
			return true;
		}

		$parser = new HttpAcceptParser();
		$acceptableTypes = $parser->parseAccept( $acceptHeader[0] );  // FIXME: Multiple headers valid?
		if ( !$acceptableTypes ) {
			return true;
		}

		// `acceptableTypes` is already sorted by quality.
		foreach ( $acceptableTypes as $t ) {
			$type = "{$t['type']}/{$t['subtype']}";
			$profile = $t['params']['profile'] ?? null;
			if (
				( $format === ParsoidFormatHelper::FORMAT_HTML && $type === 'text/html' ) ||
				( $format === ParsoidFormatHelper::FORMAT_PAGEBUNDLE && $type === 'application/json' )
			) {
				if ( $profile ) {
					preg_match( self::NEW_SPEC, $profile, $matches );
					if ( $matches && strtolower( $matches[1] ) === $format ) {
						$contentVersion = Parsoid::resolveContentVersion( $matches[2] );
						if ( $contentVersion ) {
							// $attribs mutated here!
							$attribs['envOptions']['outputContentVersion'] = $contentVersion;
							return true;
						} else {
							continue;
						}
					} else {
						continue;
					}
				} else {
					return true;
				}
			} elseif (
				( $type === '*/*' ) ||
				( $format === ParsoidFormatHelper::FORMAT_HTML && $type === 'text/*' )
			) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param string $title The page to be transformed
	 * @param ?int $revision The revision to be transformed
	 * @param ?string $wikitextOverride
	 *   Custom wikitext to use instead of the real content of the page.
	 * @param ?string $pagelanguageOverride
	 * @return PageConfig
	 */
	protected function createPageConfig(
		string $title, ?int $revision, ?string $wikitextOverride = null,
		?string $pagelanguageOverride = null
	): PageConfig {
		$title = $title ? Title::newFromText( $title ) : Title::newMainPage();
		if ( !$title ) {
			// TODO use proper validation
			throw new LogicException( 'Title not found!' );
		}
		$user = RequestContext::getMain()->getUser();

		if ( $wikitextOverride === null ) {
			$revisionRecord = null;
		} else {
			// Create a mutable revision record point to the same revision
			// and set to the desired wikitext.
			$revisionRecord = new MutableRevisionRecord( $title );
			if ( $revision !== null ) {
				$revisionRecord->setId( $revision );
			}
			$revisionRecord->setSlot(
				SlotRecord::newUnsaved(
					SlotRecord::MAIN,
					new WikitextContent( $wikitextOverride )
				)
			);
		}

		// Note: Parsoid by design isn't supposed to use the user
		// context right now, and all user state is expected to be
		// introduced as a post-parse transform.  So although we pass a
		// User here, it only currently affects the output in obscure
		// corner cases; see PageConfigFactory::create() for more.
		// @phan-suppress-next-line PhanUndeclaredMethod method defined in subtype
		return $this->pageConfigFactory->create(
			$title, $user, $revisionRecord ?? $revision, null, $pagelanguageOverride,
			$this->parsoidSettings
		);
	}

	/**
	 * Redirect to another Parsoid URL (e.g. canonization)
	 *
	 * @stable to override
	 *
	 * @param string $path Target URL
	 * @param array $pathParams Path parameters to inject into path
	 * @param array $queryParams Query parameters
	 *
	 * @return Response
	 */
	protected function createRedirectResponse(
		string $path, array $pathParams = [], array $queryParams = []
	): Response {
		// FIXME this should not be necessary in the REST entry point
		unset( $queryParams['title'] );

		$url = $this->getRedirectRouteUrl( $path, $pathParams, $queryParams );

		if ( $this->getRequest()->getMethod() === 'POST' ) {
			// 307 response
			$response = $this->getResponseFactory()->createTemporaryRedirect( $url );
		} else {
			// 302 response
			$response = $this->getResponseFactory()->createLegacyTemporaryRedirect( $url );
		}
		$response->setHeader( 'Cache-Control', 'private,no-cache,s-maxage=0' );
		return $response;
	}

	/**
	 * Returns the target URL for a redirect to the given path and parameters.
	 * This exists so subclasses can override it to control whether the redirect
	 * should go to a private or to a public URL.
	 *
	 * @see Router::getRouteUrl()
	 *
	 * @stable to override
	 *
	 * @param string $path
	 * @param array $pathParams
	 * @param array $queryParams
	 *
	 * @return string
	 */
	protected function getRedirectRouteUrl(
		string $path, array $pathParams = [], array $queryParams = []
	) {
		return $this->getRouter()->getRouteUrl( $path, $pathParams, $queryParams );
	}

	/**
	 * Try to create a PageConfig object. If we get an exception (because content
	 * may be missing or inaccessible), throw an appropriate HTTP response object
	 * for callers to handle.
	 *
	 * @param array $attribs
	 * @param ?string $wikitext
	 * @param bool $html2WtMode
	 * @return PageConfig
	 * @throws HttpException
	 */
	protected function tryToCreatePageConfig(
		array $attribs, ?string $wikitext = null, bool $html2WtMode = false
	): PageConfig {
		$oldid = $attribs['oldid'];

		try {
			$pageConfig = $this->createPageConfig(
				$attribs['pageName'], $oldid, $wikitext,
				$attribs['pagelanguage']
			);
		} catch ( RevisionAccessException $exception ) {
			throw new HttpException( 'The specified revision is deleted or suppressed.', 404 );
		}

		$hasOldId = ( $attribs['oldid'] !== null );
		if ( ( !$html2WtMode || $hasOldId ) && $pageConfig->getRevisionContent() === null ) {
			// T234549
			throw new HttpException(
				'The specified revision does not exist.', 404
			);
		}

		if ( !$html2WtMode && $wikitext === null && !$hasOldId ) {
			// Redirect to the latest revid
			throw new ResponseException(
				$this->createRedirectToOldidResponse( $pageConfig, $attribs )
			);
		}

		// All good!
		return $pageConfig;
	}

	/**
	 * Get the path for the transform endpoint. May be overwritten to override the path.
	 *
	 * This is done in the parsoid extension, for backwards compatibility
	 * with the old endpoint URLs.
	 *
	 * @stable to override
	 *
	 * @param string $format The format the endpoint is expected to return.
	 *
	 * @return string
	 */
	protected function getTransformEndpoint( string $format = ParsoidFormatHelper::FORMAT_HTML ): string {
		return '/coredev/v0/transform/{from}/to/{format}/{title}/{revision}';
	}

	/**
	 * Get the path for the page content endpoint. May be overwritten to override the path.
	 *
	 * This is done in the parsoid extension, for backwards compatibility
	 * with the old endpoint URLs.
	 *
	 * @stable to override
	 *
	 * @param string $format The format the endpoint is expected to return.
	 *
	 * @return string
	 */
	protected function getPageContentEndpoint( string $format = ParsoidFormatHelper::FORMAT_HTML ): string {
		if ( $format !== ParsoidFormatHelper::FORMAT_HTML ) {
			throw new InvalidArgumentException( 'Unsupported page content format: ' . $format );
		}
		return '/v1/page/{title}/html';
	}

	/**
	 * Get the path for the page content endpoint. May be overwritten to override the path.
	 *
	 * This is done in the parsoid extension, for backwards compatibility
	 * with the old endpoint URLs.
	 *
	 * @stable to override
	 *
	 * @param string $format The format the endpoint is expected to return.
	 *
	 * @return string
	 */
	protected function getRevisionContentEndpoint( string $format = ParsoidFormatHelper::FORMAT_HTML ): string {
		if ( $format !== ParsoidFormatHelper::FORMAT_HTML ) {
			throw new InvalidArgumentException( 'Unsupported revision content format: ' . $format );
		}
		return '/v1/revision/{revision}/html';
	}

	/**
	 * Expand the current URL with the latest revision number and redirect there.
	 *
	 * @param PageConfig $pageConfig
	 * @param array $attribs Request attributes from getRequestAttributes()
	 * @return Response
	 */
	protected function createRedirectToOldidResponse(
		PageConfig $pageConfig, array $attribs
	): Response {
		$format = $this->getRequest()->getPathParam( 'format' );
		$target = $pageConfig->getTitle();
		$revid = $pageConfig->getRevisionId();

		if ( $revid === null ) {
			throw new LogicException( 'Expected page to have a revision id.' );
		}

		$this->metrics->increment( 'redirectToOldid.' . $format );

		$pathParams = [
			'domain' => $attribs['envOptions']['domain'],
			'format' => $format,
			'title' => $target,
			'revision' => $revid
		];

		if ( $this->getRequest()->getMethod() === 'POST' ) {
			$pathParams['from'] = $this->getRequest()->getPathParam( 'from' );
			$newPath = $this->getTransformEndpoint( $format );
		} else {
			$newPath = $this->getRevisionContentEndpoint( $format );

		}
		return $this->createRedirectResponse( $newPath, $pathParams, $this->getRequest()->getQueryParams() );
	}

	/**
	 * Wikitext -> HTML helper.
	 * Spec'd in https://phabricator.wikimedia.org/T75955 and the API tests.
	 *
	 * @param PageConfig $pageConfig
	 * @param array $attribs Request attributes from getRequestAttributes()
	 * @param ?string $wikitext Wikitext to transform (or null to use the
	 *   page specified in the request attributes).
	 * @return Response
	 */
	protected function wt2html(
		PageConfig $pageConfig, array $attribs, ?string $wikitext = null
	) {
		$request = $this->getRequest();
		$opts = $attribs['opts'];
		$format = $opts['format'];
		$oldid = $attribs['oldid'];

		$needsPageBundle = ( $format === ParsoidFormatHelper::FORMAT_PAGEBUNDLE );
		$doSubst = ( $wikitext !== null && $attribs['subst'] );

		// Performance Timing options
		// init refers to time elapsed before parsing begins
		$metrics = $this->metrics;
		$timing = Timing::start( $metrics );

		if ( Semver::satisfies( $attribs['envOptions']['outputContentVersion'],
			'!=' . Parsoid::defaultHTMLVersion() ) ) {
			$metrics->increment( 'wt2html.parse.version.notdefault' );
		}

		$parsoid = $this->newParsoid();

		if ( $doSubst ) {
			if ( $format !== ParsoidFormatHelper::FORMAT_HTML ) {
				throw new HttpException(
					'Substitution is only supported for the HTML format.', 501
				);
			}
			$wikitext = $parsoid->substTopLevelTemplates(
				$pageConfig, (string)$wikitext
			);
			$pageConfig = $this->createPageConfig(
				$attribs['pageName'], $attribs['oldid'], $wikitext
			);
		}

		if (
			!empty( $this->parsoidSettings['devAPI'] ) &&
			( $request->getQueryParams()['follow_redirects'] ?? false )
		) {
			$content = $pageConfig->getRevisionContent();
			$redirectTarget = $content ? $content->getRedirectTarget() : null;
			if ( $redirectTarget ) {
				$redirectInfo = $this->dataAccess->getPageInfo(
					$pageConfig, [ $redirectTarget ]
				);
				$pathParams = [
					'domain' => $attribs['envOptions']['domain'],
					'format' => $format,
					'title' => $redirectTarget,
					'revision' => $redirectInfo['revId']
				];

				// NOTE: Core doesn't have REST endpoints that return raw wikitext,
				//       so the below will fail unless the methods are overwritten.
				if ( $redirectInfo['revId'] ) {
					$redirectPath = $this->getRevisionContentEndpoint( ParsoidFormatHelper::FORMAT_WIKITEXT );
				} else {
					$redirectPath = $this->getPageContentEndpoint( ParsoidFormatHelper::FORMAT_WIKITEXT );
				}
				throw new ResponseException(
					$this->createRedirectResponse( $redirectPath, $pathParams, $request->getQueryParams() )
				);
			}
		}

		$reqOpts = $attribs['envOptions'] + [
				'pageBundle' => $needsPageBundle,
				// When substing, set data-parsoid to be discarded, so that the subst'ed
				// content is considered new when it comes back.
				'discardDataParsoid' => $doSubst,
				'contentmodel' => $opts['contentmodel'] ?? null,
			];

		// VE, the only client using body_only property,
		// doesn't want section tags when this flag is set.
		// (T181226)
		if ( $attribs['body_only'] ) {
			$reqOpts['wrapSections'] = false;
			$reqOpts['body_only'] = true;
		}

		if ( $wikitext === null && $oldid !== null ) {
			$reqOpts['logLinterData'] = true;
			$mstr = 'pageWithOldid';
		} else {
			$mstr = 'wt';
		}

		// XXX: Not necessary, since it's in the pageConfig
		// if ( isset( $attribs['pagelanguage'] ) ) {
		// 	$reqOpts['pagelanguage'] = $attribs['pagelanguage'];
		// }

		$timing->end( "wt2html.$mstr.init" );
		$metrics->timing(
			"wt2html.$mstr.size.input",
			strlen( $pageConfig->getPageMainContent() )
		);
		$parseTiming = Timing::start( $metrics );

		if ( $format === ParsoidFormatHelper::FORMAT_LINT ) {
			try {
				$lints = $parsoid->wikitext2lint( $pageConfig, $reqOpts );
			} catch ( ClientError $e ) {
				throw new HttpException( $e->getMessage(), 400 );
			} catch ( ResourceLimitExceededException $e ) {
				throw new HttpException( $e->getMessage(), 413 );
			}
			$response = $this->getResponseFactory()->createJson( $lints );
		} else {
			$parserOutput = new ParserOutput();
			try {
				$out = $parsoid->wikitext2html(
					$pageConfig, $reqOpts, $headers, $parserOutput
				);
			} catch ( ClientError $e ) {
				throw new HttpException( $e->getMessage(), 400 );
			} catch ( ResourceLimitExceededException $e ) {
				throw new HttpException( $e->getMessage(), 413 );
			}
			if ( $needsPageBundle ) {
				$response = $this->getResponseFactory()->createJson( $out->responseData() );
				ParsoidFormatHelper::setContentType( $response, ParsoidFormatHelper::FORMAT_PAGEBUNDLE,
					$out->version );
			} else {
				$response = $this->getResponseFactory()->create();
				ParsoidFormatHelper::setContentType( $response, ParsoidFormatHelper::FORMAT_HTML,
					$attribs['envOptions']['outputContentVersion'] );
				$response->getBody()->write( $out );
				$response->setHeader( 'Content-Language', $headers['content-language'] );
				$response->addHeader( 'Vary', $headers['vary'] );
			}

			// NOTE: We used to generate an ETag here, but since it was random every time and the
			//       output wasn't stored anywhere, it could not possibly match anything, ever.

			// FIXME: For pagebundle requests, this can be somewhat inflated
			// because of pagebundle json-encoding overheads
			$outSize = $response->getBody()->getSize();
			$parseTime = $parseTiming->end( "wt2html.$mstr.parse" );
			$timing->end( 'wt2html.total' );
			$metrics->timing( "wt2html.$mstr.size.output", $outSize );

			// Ignore slow parse metrics for non-oldid parses
			if ( $mstr === 'pageWithOldid' ) {
				if ( $parseTime > 3000 ) {
					LoggerFactory::getInstance( 'slow-parsoid' )
						->info( 'Parsing {title} was slow, took {time} seconds', [
							'time' => number_format( $parseTime / 1000, 2 ),
							'title' => $pageConfig->getTitle(),
						] );
				}

				if ( $parseTime > 10 && $outSize > 100 ) {
					// * Don't bother with this metric for really small parse times
					//   p99 for initialization time is ~7ms according to grafana.
					//   So, 10ms ensures that startup overheads don't skew the metrics
					// * For body_only=false requests, <head> section isn't generated
					//   and if the output is small, per-request overheads can skew
					//   the timePerKB metrics.

					// FIXME: This is slightly misleading since there are fixed costs
					// for generating output like the <head> section and should be factored in,
					// but this is good enough for now as a useful first degree of approxmation.
					$timePerKB = $parseTime * 1024 / $outSize;
					$metrics->timing( 'wt2html.timePerKB', $timePerKB );

					if ( $timePerKB > 500 ) {
						// At 100ms/KB, even a 100KB page which isn't that large will take 10s.
						// So, we probably want to shoot for a threshold under 100ms.
						// But, let's start with 500ms+ outliers first and see what we uncover.
						LoggerFactory::getInstance( 'slow-parsoid' )
							->info( 'Parsing {title} was slow, timePerKB took {timePerKB} ms, total: {time} seconds', [
								'time' => number_format( $parseTime / 1000, 2 ),
								'timePerKB' => number_format( $timePerKB, 1 ),
								'title' => $pageConfig->getTitle(),
							] );
					}
				}
			}
		}

		if ( $wikitext !== null ) {
			// Don't cache requests when wt is set in case somebody uses
			// GET for wikitext parsing
			$response->setHeader( 'Cache-Control', 'private,no-cache,s-maxage=0' );
		} elseif ( $oldid !== null ) {
			// FIXME this should be handled in core (cf OutputPage::sendCacheControl)
			if ( $request->getHeaderLine( 'Cookie' ) ||
				$request->getHeaderLine( 'Authorization' ) ) {
				// Don't cache requests with a session.
				$response->setHeader( 'Cache-Control', 'private,no-cache,s-maxage=0' );
			}
			// Indicate the MediaWiki revision in a header as well for
			// ease of extraction in clients.
			$response->setHeader( 'Content-Revision-Id', $oldid );
		} else {
			throw new LogicException( 'Should be unreachable' );
		}
		return $response;
	}

	protected function newParsoid(): Parsoid {
		return new Parsoid( $this->siteConfig, $this->dataAccess );
	}

	protected function parseHTML( string $html, bool $validateXMLNames = false ): Document {
		return DOMUtils::parseHTML( $html, $validateXMLNames );
	}

	/**
	 * @param PageConfig $pageConfig
	 * @param array $attribs Attributes gotten from requests
	 * @param string $html Original HTML
	 *
	 * @return Response
	 * @throws HttpException
	 */
	protected function html2wt(
		PageConfig $pageConfig, array $attribs, string $html
	) {
		try {
			$transform = $this->getHTMLTransform( $attribs, $html, $pageConfig, $this->parsoidSettings );
			$wikitext = $transform->htmlToWikitext();

			$response = $this->getResponseFactory()->create();
			ParsoidFormatHelper::setContentType( $response, ParsoidFormatHelper::FORMAT_WIKITEXT );
			$response->getBody()->write( $wikitext );

			return $response;
		} catch ( ClientError $e ) {
			throw new HttpException( $e->getMessage(), 400 );
		}
	}

	/**
	 * Pagebundle -> pagebundle helper.
	 *
	 * @param array<string,array|string> $attribs
	 * @return Response
	 * @throws HttpException
	 */
	protected function pb2pb( array $attribs ) {
		$request = $this->getRequest();
		$opts = $attribs['opts'];

		$revision = $opts['previous'] ?? $opts['original'] ?? null;
		if ( !isset( $revision['html'] ) ) {
			throw new HttpException(
				'Missing revision html.', 400
			);
		}

		$vOriginal = ParsoidFormatHelper::parseContentTypeHeader(
			$revision['html']['headers']['content-type'] ?? '' );
		if ( $vOriginal === null ) {
			throw new HttpException(
				'Content-type of revision html is missing.', 400
			);
		}
		$attribs['envOptions']['inputContentVersion'] = $vOriginal;
		'@phan-var array<string,array|string> $attribs'; // @var array<string,array|string> $attribs

		$this->metrics->increment(
			'pb2pb.original.version.' . $attribs['envOptions']['inputContentVersion']
		);

		if ( !empty( $opts['updates'] ) ) {
			// FIXME: Handling missing revisions uniformly for all update types
			// is not probably the right thing to do but probably okay for now.
			// This might need revisiting as we add newer types.
			$pageConfig = $this->tryToCreatePageConfig( $attribs, null, true );
			// If we're only updating parts of the original version, it should
			// satisfy the requested content version, since we'll be returning
			// that same one.
			// FIXME: Since this endpoint applies the acceptable middleware,
			// `getOutputContentVersion` is not what's been passed in, but what
			// can be produced.  Maybe that should be selectively applied so
			// that we can update older versions where it makes sense?
			// Uncommenting below implies that we can only update the latest
			// version, since carrot semantics is applied in both directions.
			// if ( !Semver::satisfies(
			// 	$attribs['envOptions']['inputContentVersion'],
			// 	"^{$attribs['envOptions']['outputContentVersion']}"
			// ) ) {
			//  throw new HttpException(
			// 		'We do not know how to do this conversion.', 415
			// 	);
			// }
			if ( !empty( $opts['updates']['redlinks'] ) ) {
				// Q(arlolra): Should redlinks be more complex than a bool?
				// See gwicke's proposal at T114413#2240381
				return $this->updateRedLinks( $pageConfig, $attribs, $revision );
			} elseif ( isset( $opts['updates']['variant'] ) ) {
				return $this->languageConversion( $pageConfig, $attribs, $revision );
			} else {
				throw new HttpException(
					'Unknown transformation.', 400
				);
			}
		}

		// TODO(arlolra): subbu has some sage advice in T114413#2365456 that
		// we should probably be more explicit about the pb2pb conversion
		// requested rather than this increasingly complex fallback logic.
		$downgrade = Parsoid::findDowngrade(
			$attribs['envOptions']['inputContentVersion'],
			$attribs['envOptions']['outputContentVersion']
		);
		if ( $downgrade ) {
			$pb = new PageBundle(
				$revision['html']['body'],
				$revision['data-parsoid']['body'] ?? null,
				$revision['data-mw']['body'] ?? null
			);
			$this->validatePb( $pb, $attribs['envOptions']['inputContentVersion'] );
			Parsoid::downgrade( $downgrade, $pb );

			if ( !empty( $attribs['body_only'] ) ) {
				$doc = $this->parseHTML( $pb->html );
				$body = DOMCompat::getBody( $doc );
				$pb->html = ContentUtils::toXML( $body, [
					'innerXML' => true,
				] );
			}

			$response = $this->getResponseFactory()->createJson( $pb->responseData() );
			ParsoidFormatHelper::setContentType(
				$response, ParsoidFormatHelper::FORMAT_PAGEBUNDLE, $pb->version
			);
			return $response;
			// Ensure we only reuse from semantically similar content versions.
		} elseif ( Semver::satisfies( $attribs['envOptions']['outputContentVersion'],
			'^' . $attribs['envOptions']['inputContentVersion'] ) ) {
			$pageConfig = $this->tryToCreatePageConfig( $attribs );
			return $this->wt2html( $pageConfig, $attribs );
		} else {
			throw new HttpException(
				'We do not know how to do this conversion.', 415
			);
		}
	}

	/**
	 * Update red links on a document.
	 *
	 * @param PageConfig $pageConfig
	 * @param array $attribs
	 * @param array $revision
	 * @return Response
	 */
	protected function updateRedLinks(
		PageConfig $pageConfig, array $attribs, array $revision
	) {
		$parsoid = $this->newParsoid();

		$pb = new PageBundle(
			$revision['html']['body'],
			$revision['data-parsoid']['body'] ?? null,
			$revision['data-mw']['body'] ?? null,
			$attribs['envOptions']['inputContentVersion'],
			$revision['html']['headers'] ?? null,
			$revision['contentmodel'] ?? null
		);

		$out = $parsoid->pb2pb(
			$pageConfig, 'redlinks', $pb, []
		);

		$this->validatePb( $out, $attribs['envOptions']['inputContentVersion'] );

		$response = $this->getResponseFactory()->createJson( $out->responseData() );
		ParsoidFormatHelper::setContentType(
			$response, ParsoidFormatHelper::FORMAT_PAGEBUNDLE, $out->version
		);
		return $response;
	}

	/**
	 * Do variant conversion on a document.
	 *
	 * @param PageConfig $pageConfig
	 * @param array $attribs
	 * @param array $revision
	 * @return Response
	 * @throws HttpException
	 */
	protected function languageConversion(
		PageConfig $pageConfig, array $attribs, array $revision
	) {
		$opts = $attribs['opts'];
		$source = $opts['updates']['variant']['source'] ?? null;
		$target = $opts['updates']['variant']['target'] ??
			$attribs['envOptions']['htmlVariantLanguage'];

		if ( !$target ) {
			throw new HttpException(
				'Target variant is required.', 400
			);
		}

		if ( !$this->siteConfig->langConverterEnabledForLanguage(
			$pageConfig->getPageLanguage()
		) ) {
			throw new HttpException(
				'LanguageConversion is not enabled on this article.', 400
			);
		}

		$parsoid = $this->newParsoid();

		$pb = new PageBundle(
			$revision['html']['body'],
			$revision['data-parsoid']['body'] ?? null,
			$revision['data-mw']['body'] ?? null,
			$attribs['envOptions']['inputContentVersion'],
			$revision['html']['headers'] ?? null,
			$revision['contentmodel'] ?? null
		);
		$out = $parsoid->pb2pb(
			$pageConfig, 'variant', $pb,
			[
				'variant' => [
					'source' => $source,
					'target' => $target,
				]
			]
		);

		$response = $this->getResponseFactory()->createJson( $out->responseData() );
		ParsoidFormatHelper::setContentType(
			$response, ParsoidFormatHelper::FORMAT_PAGEBUNDLE, $out->version
		);
		return $response;
	}

	/** @inheritDoc */
	abstract public function execute(): Response;

	/**
	 * Validate a PageBundle against the given contentVersion, and throw
	 * an HttpException if it does not match.
	 * @param PageBundle $pb
	 * @param string $contentVersion
	 * @throws HttpException
	 */
	private function validatePb( PageBundle $pb, string $contentVersion ): void {
		$errorMessage = '';
		if ( !$pb->validate( $contentVersion, $errorMessage ) ) {
			throw new HttpException( $errorMessage, 400 );
		}
	}

}
