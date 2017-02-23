<?php

namespace MediaWiki\Cdn;

use CdnCacheUpdate;
use DeferredUpdates;
use Hooks;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Session\Session;
use MediaWiki\Session\SessionManager;
use OutputPage;
use Title;
use WebRequest;
use Wikimedia\Assert\Assert;

/**
 * Controller for caching and puring resources in reverse proxies or a content delivery network.
 * This class expects the proxy hosts to follow the Squid/Varnish protocol with active purging
 * enabled. Active purging requires special configuration of the proxies.
 *
 * @todo Provide a link to the appropriate Squid/Varnish configuration.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class CdnController implements CdnUpdateController, CdnHeaderController {

	/**
	 * @var int
	 */
	private $maxAge;

	/**
	 * @var string[]
	 */
	private $cdnHosts;

	/**
	 * @var boolean
	 */
	private $useESI = false;

	/**
	 * @var boolean
	 */
	private $useKeyHeader = false;

	/**
	 * CdnController constructor.
	 *
	 * @param int $maxAge max CDN cache duration, fronm the SquidMaxAge setting
	 * @param string[] $cdnHosts Addresses of CDN servers to actively purge
	 */
	public function __construct( $maxAge, array $cdnHosts = [] ) {
		Assert::parameterType( 'int', $maxAge, '$maxAge' );
		$this->maxAge = $maxAge;

		Assert::parameterElementType( 'string', $cdnHosts, '$cdnHosts' );
		$this->cdnHosts = $cdnHosts;
	}

	/**
	 * @param string[] $resourceUris
	 *
	 * @throws \Exception if supportsBuckets() returns false.
	 * @return void
	 */
	protected function purgeURLs( array $resourceUris ) {
		// FIXME: pass $this->cdnHosts to CdnCacheUpdate;
		// TODO: move the functionality of CdnCacheUpdate::doUpdate here;
		// TODO: implement a DeferredCdnPurgeController that schedules a deferred CdnCacheUpdate.
		if ( !empty( $this->cdnHosts ) ) {
			DeferredUpdates::addUpdate(
				new CdnCacheUpdate( $resourceUris ),
				DeferredUpdates::PRESEND
			);
		}
	}

	/**
	 * @param Title $title
	 * @param bool|int $deferred
	 */
	public function purgeDependentResources( Title $title, $deferred = DeferredUpdates::PRESEND ) {
		$urls = $this->getDependentResources( $title );

		// FIXME: use $deferred !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
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
	 * @param WebRequest $request
	 * @param OutputPage $output
	 * @param LinkTarget[] $dependsOn
	 */
	public function applyCacheControl( WebRequest $request, OutputPage $output, array $dependsOn = [] ) {
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
