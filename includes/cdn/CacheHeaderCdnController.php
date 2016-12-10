<?php

namespace MediaWiki\Cdn;

use Hooks;
use MediaWiki\Session\Session;
use MediaWiki\Session\SessionManager;
use OutputPage;
use Title;
use WebRequest;
use Wikimedia\Assert\Assert;

/**
 * ActiveCdnController
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class CacheHeaderCdnController implements CdnController {

	/**
	 * @var int
	 */
	private $maxAge;

	/**
	 * @var boolean
	 */
	private $useESI = false;

	/**
	 * @var boolean
	 */
	private $useKeyHeader = false;

	/**
	 * CacheHeaderCdnController constructor.
	 *
	 * @param int $maxAge fronm the SquidMaxAge setting
	 */
	public function __construct( $maxAge ) {
		Assert::parameterType( 'int', $maxAge, '$maxAge' );
		$this->maxAge = $maxAge;
	}

	/**
	 * @param string[] $resourceUris
	 *
	 * @throws \Exception if supportsBuckets() returns false.
	 * @return void
	 */
	public function purgeURLs( array $resourceUris ) {
		// noop
	}

	/**
	 * @param Title $title
	 *
	 * @return void
	 */
	public function purgeDependentResources( Title $title ) {
		$urls = $this->getDependentResources( $title );
		$this->purgeURLs( $urls );
	}

	/**
	 * @param Title $title
	 *
	 * @return string[]
	 */
	protected function getPageUrls( Title $title ) {
		$urls = [
			$title->getInternalURL(),
			$title->getInternalURL( 'action=history' )
		];

		$pageLang = $title->getPageLanguage();
		if ( $pageLang->hasVariants() ) {
			$variants = $pageLang->getVariants();
			foreach ( $variants as $vCode ) {
				$urls[] = $title->getInternalURL( $vCode );
			}
		}

		// If we are looking at a css/js user subpage, purge the action=raw.
		if ( $title->isJsSubpage() ) {
			$urls[] = $title->getInternalURL( 'action=raw&ctype=text/javascript' );
		} elseif ( $title->isCssSubpage() ) {
			$urls[] = $title->getInternalURL( 'action=raw&ctype=text/css' );
		}

		return $urls;
	}

	/**
	 * @param Title $requestTitle
	 * @param WebRequest $request
	 * @param OutputPage $output
	 */
	public function applyCacheControl( Title $requestTitle, WebRequest $request, OutputPage $output ) {
		$response = $request->response();

		# don't serve compressed data to clients who can't handle it
		# maintain different caches for logged-in users and non-logged in ones
		$response->header( $output->getVaryHeader() );

		if ( $this->useKeyHeader ) {
			$response->header( $output->getKeyHeader() );
		}

		$lastModified = $output->getLastModified();
		if ( !$output->isCacheable() ) {
			# In general, the absence of a last modified header should be enough to prevent
			# the client from using its cache. We send a few other things just to make sure.
			$response->header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', 0 ) . ' GMT' );
			$response->header( 'Cache-Control: no-cache, no-store, max-age=0, must-revalidate' );
			$response->header( 'Pragma: no-cache' );

			return;
		}

		if (
			!$response->hasCookies() &&
			!$this->getSession()->isPersistent() && // XXX: can't we just use $request->getSession()?
			!$output->isPrintable() &&
			$this->maxAge != 0 &&
			!$output->haveCacheVaryCookies()
		) {
			$effectiveMaxAge =  $output->getCdnMaxage();

			if ( $this->useESI ) {
				# We'll purge the proxy cache explicitly, but require end user agents
				# to revalidate against the proxy on each visit.
				# Surrogate-Control controls our CDN, Cache-Control downstream caches
				wfDebug( __METHOD__ .
					": proxy caching with ESI; {$lastModified} **", 'private' );
				# start with a shorter timeout for initial testing
				# header( 'Surrogate-Control: max-age=2678400+2678400, content="ESI/1.0"');
				$response->header(
					"Surrogate-Control: max-age={$this->maxAge}" .
					"+{$effectiveMaxAge}, content=\"ESI/1.0\""
				);
				$response->header( 'Cache-Control: s-maxage=0, must-revalidate, max-age=0' );
			} else {
				# We'll purge the proxy cache for anons explicitly, but require end user agents
				# to revalidate against the proxy on each visit.
				# IMPORTANT! The CDN needs to replace the Cache-Control header with
				# Cache-Control: s-maxage=0, must-revalidate, max-age=0
				wfDebug( __METHOD__ .
					": local proxy caching; {$lastModified} **", 'private' );
				# start with a shorter timeout for initial testing
				# header( "Cache-Control: s-maxage=2678400, must-revalidate, max-age=0" );
				$response->header( "Cache-Control: " .
					"s-maxage={$effectiveMaxAge}, must-revalidate, max-age=0" );
			}
		} else {
			# We do want clients to cache if they can, but they *must* check for updates
			# on revisiting the page.
			wfDebug( __METHOD__ . ": private caching; {$lastModified} **", 'private' );
			$response->header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', 0 ) . ' GMT' );
			$response->header( "Cache-Control: private, must-revalidate, max-age=0" );
		}
		if ( $lastModified ) {
			$response->header( "Last-Modified: {$lastModified}" );
		}

	}

	/**
	 * @param Title $title
	 *
	 * @return string[]
	 */
	public function getDependentResources( Title $title ) {
		$urls = $this->getDependentResources( $title );

		Hooks::run( 'TitleSquidURLs', [ $title, &$urls, $this->isUsingBuckets() ] );
		return $urls;
	}

	protected function isUsingBuckets() {
		return false;
	}

	/**
	 * @param boolean $useESI
	 */
	public function setUseESI( $useESI ) {
		Assert::parameterType( 'boolean', $useESI, '$useESI' );
		$this->useESI = $useESI;
	}

	/**
	 * @param boolean $useKeyHeader
	 */
	public function setUseKeyHeader( $useKeyHeader ) {
		Assert::parameterType( 'boolean', $useKeyHeader, '$useKeyHeader' );
		$this->useKeyHeader = $useKeyHeader;
	}

	/**
	 * @return Session
	 */
	private function getSession() {
		// TODO: Inject some kind of session provider; injecting the Session directly probably won't work.
		return SessionManager::getGlobalSession();
	}
}
