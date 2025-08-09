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
use InvalidArgumentException;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use LogicException;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Context\RequestContext;
use MediaWiki\Language\LanguageCode;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\Config\SiteConfig;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\Handler\Helper\HtmlInputTransformHelper;
use MediaWiki\Rest\Handler\Helper\HtmlOutputRendererHelper;
use MediaWiki\Rest\Handler\Helper\ParsoidFormatHelper;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\Response;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SuppressedDataException;
use MediaWiki\Title\MalformedTitleException;
use MediaWiki\Title\Title;
use MediaWiki\WikiMap\WikiMap;
use MobileContext;
use Wikimedia\Http\HttpAcceptParser;
use Wikimedia\Message\DataMessageValue;
use Wikimedia\Message\MessageValue;
use Wikimedia\Parsoid\Config\DataAccess;
use Wikimedia\Parsoid\Config\PageConfig;
use Wikimedia\Parsoid\Config\PageConfigFactory;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\HtmlPageBundle;
use Wikimedia\Parsoid\Core\ResourceLimitExceededException;
use Wikimedia\Parsoid\DOM\Document;
use Wikimedia\Parsoid\Parsoid;
use Wikimedia\Parsoid\Utils\ContentUtils;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMUtils;
use Wikimedia\Parsoid\Utils\Timing;

// TODO logging, timeouts(?), CORS
// TODO content negotiation (routes.js routes.acceptable)
// TODO handle MaxConcurrentCallsError (pool counter?)

/**
 * Base class for Parsoid handlers.
 * @internal For use by the Parsoid extension
 */
abstract class ParsoidHandler extends Handler {

	private RevisionLookup $revisionLookup;
	protected SiteConfig $siteConfig;
	protected PageConfigFactory $pageConfigFactory;
	protected DataAccess $dataAccess;

	/** @var ExtensionRegistry */
	protected $extensionRegistry;

	/** @var ?StatsdDataFactoryInterface A statistics aggregator */
	protected $metrics;

	/** @var array */
	private $requestAttributes;

	public static function factory(): static {
		$services = MediaWikiServices::getInstance();
		// @phan-suppress-next-line PhanTypeInstantiateAbstractStatic
		return new static(
			$services->getRevisionLookup(),
			$services->getParsoidSiteConfig(),
			$services->getParsoidPageConfigFactory(),
			$services->getParsoidDataAccess()
		);
	}

	public function __construct(
		RevisionLookup $revisionLookup,
		SiteConfig $siteConfig,
		PageConfigFactory $pageConfigFactory,
		DataAccess $dataAccess
	) {
		$this->revisionLookup = $revisionLookup;
		$this->siteConfig = $siteConfig;
		$this->pageConfigFactory = $pageConfigFactory;
		$this->dataAccess = $dataAccess;
		$this->extensionRegistry = ExtensionRegistry::getInstance();
		$this->metrics = $siteConfig->metrics();
	}

	public function getSupportedRequestTypes(): array {
		return array_merge( parent::getSupportedRequestTypes(), [
			'application/x-www-form-urlencoded',
			'multipart/form-data'
		] );
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
		$server = RequestContext::getMain()->getConfig()->get( MainConfigNames::Server );
		$expectedDomain = parse_url( $server, PHP_URL_HOST );
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
			$expectedMobileDomain = parse_url( $mobileServer, PHP_URL_HOST );
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
					throw new LocalizedHttpException(
						new MessageValue( "rest-json-body-parse-error", [ 'not a valid JSON object' ] ), 400 );
				}
				return $json;
			default:
				throw new LocalizedHttpException(
					new MessageValue( "rest-unsupported-content-type", [ $contentType ?? '(null)' ] ),
					415
				);
		}
	}

	protected function getOpts( array $body, RequestInterface $request ): array {
		return array_merge(
			$body,
			array_intersect_key( $request->getPathParams(), [ 'from' => true, 'format' => true ] )
		);
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
		$opts = $this->getOpts( $body, $request );
		'@phan-var array<string,array|bool|string> $opts'; // @var array<string,array|bool|string> $opts
		$contentLanguage = $request->getHeaderLine( 'Content-Language' ) ?: null;
		if ( $contentLanguage ) {
			$contentLanguage = LanguageCode::normalizeNonstandardCodeAndWarn(
				$contentLanguage
			);
		}
		$attribs = [
			'pageName' => $request->getPathParam( 'title' ) ?? '',
			'oldid' => $request->getPathParam( 'revision' ),
			// "body_only" flag to return just the body (instead of the entire HTML doc)
			// We would like to deprecate use of this flag: T181657
			'body_only' => $request->getQueryParams()['body_only'] ?? $body['body_only'] ?? null,
			'errorEnc' => ParsoidFormatHelper::ERROR_ENCODING[$opts['format']] ?? 'plain',
			'iwp' => WikiMap::getCurrentWikiId(), // PORT-FIXME verify
			'offsetType' => $body['offsetType']
				?? $request->getQueryParams()['offsetType']
				// Lint requests should return UCS2 offsets by default
				?? ( $opts['format'] === ParsoidFormatHelper::FORMAT_LINT ? 'ucs2' : 'byte' ),
			'pagelanguage' => $contentLanguage,
		];

		// For use in getHtmlOutputRendererHelper
		$opts['stash'] = $request->getQueryParams()['stash'] ?? false;

		if ( $request->getMethod() === 'POST' ) {
			if ( isset( $opts['original']['revid'] ) ) {
				$attribs['oldid'] = $opts['original']['revid'];
			}
			if ( isset( $opts['original']['title'] ) ) {
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

		// For use in getHtmlOutputRendererHelper
		$opts['accept-language'] = $request->getHeaderLine( 'Accept-Language' ) ?: null;

		$acceptLanguage = null;
		if ( $opts['accept-language'] !== null ) {
			$acceptLanguage = LanguageCode::normalizeNonstandardCodeAndWarn(
				$opts['accept-language']
			);
		}

		// Init pageName if oldid is provided and is a valid revision
		if ( ( $attribs['pageName'] === '' ) && $attribs['oldid'] ) {
			$rev = $this->revisionLookup->getRevisionById( $attribs['oldid'] );
			if ( $rev ) {
				$attribs['pageName'] = $rev->getPage()->getDBkey();
			}
		}

		$attribs['envOptions'] = [
			// We use `prefix` but ought to use `domain` (T206764)
			'prefix' => $attribs['iwp'],
			// For the legacy "domain" path parameter used by the endpoints exposed
			// by the parsoid extension. Will be null for core endpoints.
			'domain' => $request->getPathParam( 'domain' ),
			'pageName' => $attribs['pageName'],
			'cookie' => $request->getHeaderLine( 'Cookie' ),
			'reqId' => $request->getHeaderLine( 'X-Request-Id' ),
			'userAgent' => $request->getHeaderLine( 'User-Agent' ),
			'htmlVariantLanguage' => $acceptLanguage,
			// Semver::satisfies checks below expect a valid outputContentVersion value.
			// Better to set it here instead of adding the default value at every check.
			'outputContentVersion' => Parsoid::defaultHTMLVersion(),
		];

		# Convert language codes in $opts['updates']['variant'] if present
		$sourceVariant = $opts['updates']['variant']['source'] ?? null;
		if ( $sourceVariant ) {
			$sourceVariant = LanguageCode::normalizeNonstandardCodeAndWarn(
				$sourceVariant
			);
			$opts['updates']['variant']['source'] = $sourceVariant;
		}
		$targetVariant = $opts['updates']['variant']['target'] ?? null;
		if ( $targetVariant ) {
			$targetVariant = LanguageCode::normalizeNonstandardCodeAndWarn(
				$targetVariant
			);
			$opts['updates']['variant']['target'] = $targetVariant;
		}
		if ( isset( $opts['wikitext']['headers']['content-language'] ) ) {
			$contentLanguage = $opts['wikitext']['headers']['content-language'];
			$contentLanguage = LanguageCode::normalizeNonstandardCodeAndWarn(
				$contentLanguage
			);
			$opts['wikitext']['headers']['content-language'] = $contentLanguage;
		}
		if ( isset( $opts['original']['wikitext']['headers']['content-language'] ) ) {
			$contentLanguage = $opts['original']['wikitext']['headers']['content-language'];
			$contentLanguage = LanguageCode::normalizeNonstandardCodeAndWarn(
				$contentLanguage
			);
			$opts['original']['wikitext']['headers']['content-language'] = $contentLanguage;
		}

		$attribs['opts'] = $opts;

		// TODO: Remove assertDomainIsCorrect() once we no longer need to support the {domain}
		//       parameter for the endpoints exposed by the parsoid extension.
		if ( $attribs['envOptions']['domain'] !== null ) {
			$this->assertDomainIsCorrect( $attribs['envOptions']['domain'] );
		}

		$this->requestAttributes = $attribs;
		return $this->requestAttributes;
	}

	/**
	 * @param array $attribs
	 * @param ?string $source
	 * @param PageIdentity $page
	 * @param ?int $revId
	 *
	 * @return HtmlOutputRendererHelper
	 */
	private function getHtmlOutputRendererHelper(
		array $attribs,
		?string $source,
		PageIdentity $page,
		?int $revId
	): HtmlOutputRendererHelper {
		$services = MediaWikiServices::getInstance();

		// Request lenient rev handling
		$lenientRevHandling = true;

		$authority = $this->getAuthority();

		$params = [];
		$helper = $services->getPageRestHelperFactory()->newHtmlOutputRendererHelper(
			$page, $params, $authority, $revId, $lenientRevHandling
		);

		// XXX: should default to the page's content model?
		$model = $attribs['opts']['contentmodel']
			?? ( $attribs['envOptions']['contentmodel'] ?? CONTENT_MODEL_WIKITEXT );

		if ( $source !== null ) {
			$helper->setContentSource( $source, $model );
		}

		if ( isset( $attribs['opts']['stash'] ) ) {
			$helper->setStashingEnabled( $attribs['opts']['stash'] );
		}

		if ( isset( $attribs['envOptions']['outputContentVersion'] ) ) {
			$helper->setOutputProfileVersion( $attribs['envOptions']['outputContentVersion'] );
		}

		if ( isset( $attribs['pagelanguage'] ) ) {
			$helper->setPageLanguage( $attribs['pagelanguage'] );
		}

		if ( isset( $attribs['opts']['accept-language'] ) ) {
			$helper->setVariantConversionLanguage( $attribs['opts']['accept-language'] );
		}

		return $helper;
	}

	/**
	 * @param array $attribs
	 * @param string $html
	 * @param PageIdentity $page
	 *
	 * @return HtmlInputTransformHelper
	 */
	protected function getHtmlInputTransformHelper(
		array $attribs,
		string $html,
		PageIdentity $page
	): HtmlInputTransformHelper {
		$services = MediaWikiServices::getInstance();

		$parameters = $attribs['opts'] + $attribs;
		$body = $attribs['opts'];

		$body['html'] = $html;

		$helper = $services->getPageRestHelperFactory()->newHtmlInputTransformHelper(
			$attribs['envOptions'] + [
				'offsetType' => $attribs['offsetType'],
			],
			$page,
			$body,
			$parameters
		);

		$helper->setMetrics( $this->siteConfig->prefixedStatsFactory() );

		return $helper;
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
	 * Try to create a PageConfig object. If we get an exception (because content
	 * may be missing or inaccessible), throw an appropriate HTTP response object
	 * for callers to handle.
	 *
	 * @param array $attribs
	 * @param ?string $wikitextOverride
	 *   Custom wikitext to use instead of the real content of the page.
	 * @param bool $html2WtMode
	 * @return PageConfig
	 * @throws HttpException
	 */
	protected function tryToCreatePageConfig(
		array $attribs, ?string $wikitextOverride = null, bool $html2WtMode = false
	): PageConfig {
		$revId = $attribs['oldid'];
		$pagelanguageOverride = $attribs['pagelanguage'];
		$title = $attribs['pageName'];

		$title = ( $title !== '' ) ? Title::newFromText( $title ) : Title::newMainPage();
		if ( !$title ) {
			throw new LocalizedHttpException(
				new MessageValue( "rest-invalid-title", [ 'pageName' ] ), 400
			);
		}
		$user = RequestContext::getMain()->getUser();

		if ( $wikitextOverride === null ) {
			$revisionRecord = null;
		} else {
			// Create a mutable revision record point to the same revision
			// and set to the desired wikitext.
			$revisionRecord = new MutableRevisionRecord( $title );
			// Don't set id to $revId if we have $wikitextOverride
			// A revision corresponds to specific wikitext, which $wikitextOverride
			// might not be.
			$revisionRecord->setId( 0 );
			$revisionRecord->setSlot(
				SlotRecord::newUnsaved(
					SlotRecord::MAIN,
					new WikitextContent( $wikitextOverride )
				)
			);
		}

		$hasOldId = ( $revId !== null );
		$ensureAccessibleContent = !$html2WtMode || $hasOldId;

		try {
			// Note: Parsoid by design isn't supposed to use the user
			// context right now, and all user state is expected to be
			// introduced as a post-parse transform.  So although we pass a
			// User here, it only currently affects the output in obscure
			// corner cases; see PageConfigFactory::create() for more.
			// @phan-suppress-next-line PhanUndeclaredMethod method defined in subtype
			$pageConfig = $this->pageConfigFactory->createFromParserOptions(
				ParserOptions::newFromUser( $user ),
				$title,
				$revisionRecord ?? $revId,
				$pagelanguageOverride,
				$ensureAccessibleContent
			);
		} catch ( SuppressedDataException $e ) {
			throw new LocalizedHttpException(
				new MessageValue( "rest-permission-denied-revision", [ $e->getMessage() ] ), 403
			);
		} catch ( RevisionAccessException $e ) {
			throw new LocalizedHttpException(
				new MessageValue( "rest-specified-revision-unavailable", [ $e->getMessage() ] ), 404
			);
		}

		// All good!
		return $pageConfig;
	}

	/**
	 * Try to create a PageIdentity object.
	 * If no page is specified in the request, this will return the wiki's main page.
	 * If an invalid page is requested, this throws an appropriate HTTPException.
	 *
	 * @param array $attribs
	 * @return PageIdentity
	 * @throws HttpException
	 */
	protected function tryToCreatePageIdentity( array $attribs ): PageIdentity {
		if ( $attribs['pageName'] === '' ) {
			return Title::newMainPage();
		}

		// XXX: Should be injected, but the Parsoid extension relies on the
		//      constructor signature. Also, ParsoidHandler should go away soon anyway.
		$pageStore = MediaWikiServices::getInstance()->getPageStore();

		$page = $pageStore->getPageByText( $attribs['pageName'] );

		if ( !$page ) {
			throw new LocalizedHttpException(
				new MessageValue( "rest-invalid-title", [ 'pageName' ] ), 400
			);
		}

		return $page;
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

	private function wtLint(
		PageConfig $pageConfig, array $attribs, ?array $linterOverrides = []
	): array {
		$envOptions = $attribs['envOptions'] + [
			'linterOverrides' => $linterOverrides,
			'offsetType' => $attribs['offsetType'],
		];
		try {
			$parsoid = $this->newParsoid();
			$parserOutput = new ParserOutput();
			return $parsoid->wikitext2lint( $pageConfig, $envOptions, $parserOutput );
		} catch ( ClientError $e ) {
			throw new LocalizedHttpException( new MessageValue( "rest-parsoid-error", [ $e->getMessage() ] ), 400 );
		} catch ( ResourceLimitExceededException $e ) {
			throw new LocalizedHttpException(
				new MessageValue( "rest-parsoid-resource-exceeded", [ $e->getMessage() ] ), 413
			);
		}
	}

	/**
	 * Wikitext -> HTML helper.
	 * Spec'd in https://phabricator.wikimedia.org/T75955 and the API tests.
	 *
	 * @param PageConfig $pageConfig
	 * @param array $attribs Request attributes from getRequestAttributes()
	 * @param ?string $wikitext Wikitext to transform (or null to use the
	 *   page specified in the request attributes).
	 *
	 * @return Response
	 */
	protected function wt2html(
		PageConfig $pageConfig, array $attribs, ?string $wikitext = null
	) {
		$request = $this->getRequest();
		$opts = $attribs['opts'];
		$format = $opts['format'];
		$oldid = $attribs['oldid'];
		$stash = $opts['stash'] ?? false;

		if ( $format === ParsoidFormatHelper::FORMAT_LINT ) {
			$linterOverrides = [];
			if ( $this->extensionRegistry->isLoaded( 'Linter' ) ) { // T360809
				$disabled = [];
				$services = MediaWikiServices::getInstance();
				$linterCategories = $services->getMainConfig()->get( 'LinterCategories' );
				foreach ( $linterCategories as $name => $cat ) {
					if ( $cat['priority'] === 'none' ) {
						$disabled[] = $name;
					}
				}
				$linterOverrides['disabled'] = $disabled;
			}
			$lints = $this->wtLint( $pageConfig, $attribs, $linterOverrides );
			$response = $this->getResponseFactory()->createJson( $lints );
			return $response;
		}

		// TODO: This method should take a PageIdentity + revId,
		//       to reduce the usage of PageConfig in MW core.
		$helper = $this->getHtmlOutputRendererHelper(
			$attribs,
			$wikitext,
			$this->pageConfigToPageIdentity( $pageConfig ),
			// Id will be 0 if we have $wikitext but that isn't valid
			// to call $helper->setRevision with.  In any case, the revision
			// will be reset when $helper->setContent is called with $wikitext.
			// Ideally, the revision would be pass through here instead of
			// the id and wikitext.
			$pageConfig->getRevisionId() ?: null
		);

		$needsPageBundle = ( $format === ParsoidFormatHelper::FORMAT_PAGEBUNDLE );

		if ( $attribs['body_only'] ) {
			$helper->setFlavor( 'fragment' );
		} elseif ( !$needsPageBundle ) {
			// Inline data-parsoid. This will happen when no special params are set.
			$helper->setFlavor( 'edit' );
		}

		if ( $wikitext === null && $oldid !== null ) {
			$mstr = 'pageWithOldid';
		} else {
			$mstr = 'wt';
		}

		$parseTiming = Timing::start();

		if ( $needsPageBundle ) {
			$pb = $helper->getPageBundle();

			// Handle custom offset requests as a pb2pb transform
			if (
				$helper->isParsoidContent() &&
				( $attribs['offsetType'] !== 'byte' )
			) {
				$parsoid = $this->newParsoid();
				$pb = $parsoid->pb2pb(
					$pageConfig,
					'convertoffsets',
					$pb,
					[
						'inputOffsetType' => 'byte',
						'outputOffsetType' => $attribs['offsetType']
					]
				);
			}

			$response = $this->getResponseFactory()->createJson( $pb->responseData() );
			$helper->putHeaders( $response, false );

			ParsoidFormatHelper::setContentType(
				$response,
				ParsoidFormatHelper::FORMAT_PAGEBUNDLE,
				$pb->version
			);
		} else {
			$out = $helper->getHtml();

			// TODO: offsetType conversion isn't supported right now for non-pagebundle endpoints
			// Once the OutputTransform framework lands, we might revisit this.

			$response = $this->getResponseFactory()->create();
			$response->getBody()->write( $out->getRawText() );

			$helper->putHeaders( $response, true );

			// Emit an ETag only if stashing is enabled. It's not reliably useful otherwise.
			if ( $stash ) {
				$eTag = $helper->getETag();
				if ( $eTag ) {
					$response->setHeader( 'ETag', $eTag );
				}
			}
		}

		// XXX: For pagebundle requests, this can be somewhat inflated
		// because of pagebundle json-encoding overheads
		$outSize = $response->getBody()->getSize();
		$parseTime = $parseTiming->end();

		// Ignore slow parse metrics for non-oldid parses
		if ( $mstr === 'pageWithOldid' ) {
			if ( $parseTime > 3000 ) {
				LoggerFactory::getInstance( 'slow-parsoid' )
					->info( 'Parsing {title} was slow, took {time} seconds', [
						'time' => number_format( $parseTime / 1000, 2 ),
						'title' => Title::newFromLinkTarget( $pageConfig->getLinkTarget() )->getPrefixedText(),
					] );
			}

			if ( $parseTime > 10 && $outSize > 100 ) {
				// * Don't bother with this metric for really small parse times
				//   p99 for initialization time is ~7ms according to grafana.
				//   So, 10ms ensures that startup overheads don't skew the metrics
				// * For body_only=false requests, <head> section isn't generated
				//   and if the output is small, per-request overheads can skew
				//   the timePerKB metrics.

				// NOTE: This is slightly misleading since there are fixed costs
				// for generating output like the <head> section and should be factored in,
				// but this is good enough for now as a useful first degree of approxmation.
				$timePerKB = $parseTime * 1024 / $outSize;
				if ( $timePerKB > 500 ) {
					// At 100ms/KB, even a 100KB page which isn't that large will take 10s.
					// So, we probably want to shoot for a threshold under 100ms.
					// But, let's start with 500ms+ outliers first and see what we uncover.
					LoggerFactory::getInstance( 'slow-parsoid' )
						->info( 'Parsing {title} was slow, timePerKB took {timePerKB} ms, total: {time} seconds', [
							'time' => number_format( $parseTime / 1000, 2 ),
							'timePerKB' => number_format( $timePerKB, 1 ),
							'title' => Title::newFromLinkTarget( $pageConfig->getLinkTarget() )->getPrefixedText(),
						] );
				}
			}
		}

		if ( $wikitext !== null ) {
			// Don't cache requests when wt is set in case somebody uses
			// GET for wikitext parsing
			// XXX: can we just refuse to do wikitext parsing in a GET request?
			$response->setHeader( 'Cache-Control', 'private,no-cache,s-maxage=0' );
		} elseif ( $oldid !== null ) {
			// XXX: can this go away? Parsoid's PageContent class doesn't expose supressed revision content.
			if ( $request->getHeaderLine( 'Cookie' ) ||
				$request->getHeaderLine( 'Authorization' ) ) {
				// Don't cache requests with a session.
				$response->setHeader( 'Cache-Control', 'private,no-cache,s-maxage=0' );
			}
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
	 * @param PageConfig|PageIdentity $page
	 * @param array $attribs Attributes gotten from requests
	 * @param string $html Original HTML
	 *
	 * @return Response
	 * @throws HttpException
	 */
	protected function html2wt(
		$page, array $attribs, string $html
	) {
		if ( $page instanceof PageConfig ) {
			// TODO: Deprecate passing a PageConfig.
			//       Ideally, callers would use HtmlToContentTransform directly.
			$page = Title::newFromLinkTarget( $page->getLinkTarget() );
		}

		try {
			$transform = $this->getHtmlInputTransformHelper( $attribs, $html, $page );

			$response = $this->getResponseFactory()->create();
			$transform->putContent( $response );

			return $response;
		} catch ( ClientError $e ) {
			throw new LocalizedHttpException( new MessageValue( "rest-parsoid-error", [ $e->getMessage() ] ), 400 );
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
		$opts = $attribs['opts'];

		$revision = $opts['previous'] ?? $opts['original'] ?? null;
		if ( !isset( $revision['html'] ) ) {
			throw new LocalizedHttpException( new MessageValue( "rest-missing-revision-html" ), 400 );
		}

		$vOriginal = ParsoidFormatHelper::parseContentTypeHeader(
			$revision['html']['headers']['content-type'] ?? '' );
		if ( $vOriginal === null ) {
			throw new LocalizedHttpException( new MessageValue( "rest-missing-revision-html-content-type" ), 400 );
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
				throw new LocalizedHttpException( new MessageValue( "rest-unknown-parsoid-transformation" ), 400 );
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
			$pb = new HtmlPageBundle(
				$revision['html']['body'],
				$revision['data-parsoid']['body'] ?? null,
				$revision['data-mw']['body'] ?? null
			);
			$this->validatePb( $pb, $attribs['envOptions']['inputContentVersion'] );
			Parsoid::downgrade( $downgrade, $pb );

			if ( !empty( $attribs['body_only'] ) ) {
				$doc = $this->parseHTML( $pb->html );
				$body = DOMCompat::getBody( $doc );
				$pb->html = ContentUtils::toXML( $body, [ 'innerXML' => true ] );
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
			throw new LocalizedHttpException( new MessageValue( "rest-unsupported-profile-conversion" ), 415 );
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

		$pb = new HtmlPageBundle(
			$revision['html']['body'],
			$revision['data-parsoid']['body'] ?? null,
			$revision['data-mw']['body'] ?? null,
			$attribs['envOptions']['inputContentVersion'],
			$revision['html']['headers'] ?? null,
			$revision['contentmodel'] ?? null
		);

		$out = $parsoid->pb2pb( $pageConfig, 'redlinks', $pb, [] );

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
		$target = $opts['updates']['variant']['target'] ??
			$attribs['envOptions']['htmlVariantLanguage'];
		$source = $opts['updates']['variant']['source'] ?? null;

		if ( !$target ) {
			throw new LocalizedHttpException( new MessageValue( "rest-target-variant-required" ), 400 );
		}

		$pageIdentity = $this->tryToCreatePageIdentity( $attribs );

		$pb = new HtmlPageBundle(
			$revision['html']['body'],
			$revision['data-parsoid']['body'] ?? null,
			$revision['data-mw']['body'] ?? null,
			$attribs['envOptions']['inputContentVersion'],
			$revision['html']['headers'] ?? null,
			$revision['contentmodel'] ?? null
		);

		// XXX: DI should inject HtmlTransformFactory
		$languageVariantConverter = MediaWikiServices::getInstance()
			->getHtmlTransformFactory()
			->getLanguageVariantConverter( $pageIdentity );
		$languageVariantConverter->setPageConfig( $pageConfig );
		$httpContentLanguage = $attribs['pagelanguage' ] ?? null;
		if ( $httpContentLanguage ) {
			$languageVariantConverter->setPageLanguageOverride( $httpContentLanguage );
		}

		try {
			$out = $languageVariantConverter->convertPageBundleVariant( $pb, $target, $source );
		} catch ( InvalidArgumentException $e ) {
			throw new LocalizedHttpException(
				new MessageValue( "rest-unsupported-language-conversion", [ $source ?? '(unspecified)', $target ] ),
				400,
				[ 'reason' => $e->getMessage() ]
			);
		}

		$response = $this->getResponseFactory()->createJson( $out->responseData() );
		ParsoidFormatHelper::setContentType(
			$response, ParsoidFormatHelper::FORMAT_PAGEBUNDLE, $out->version
		);
		return $response;
	}

	/** @inheritDoc */
	abstract public function execute(): Response;

	/**
	 * Validate a HtmlPageBundle against the given contentVersion, and throw
	 * an HttpException if it does not match.
	 * @param HtmlPageBundle $pb
	 * @param string $contentVersion
	 * @throws HttpException
	 */
	private function validatePb( HtmlPageBundle $pb, string $contentVersion ): void {
		$errorMessage = '';
		if ( !$pb->validate( $contentVersion, $errorMessage ) ) {
			throw new LocalizedHttpException(
				new MessageValue( "rest-page-bundle-validation-error", [ $errorMessage ] ),
				400
			);
		}
	}

	/**
	 * @param PageConfig $page
	 *
	 * @return ProperPageIdentity
	 * @throws HttpException
	 */
	private function pageConfigToPageIdentity( PageConfig $page ): ProperPageIdentity {
		$services = MediaWikiServices::getInstance();

		$title = $page->getLinkTarget();
		try {
			$page = $services->getPageStore()->getPageForLink( $title );
		} catch ( MalformedTitleException | InvalidArgumentException ) {
			// Note that even some well-formed links are still invalid
			// parameters for getPageForLink(), e.g. interwiki links or special pages.
			throw new HttpException(
				"Bad title: $title", # uses LinkTarget::__toString()
				400
			);
		}

		return $page;
	}

}
