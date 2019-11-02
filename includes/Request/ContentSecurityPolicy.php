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

namespace MediaWiki\Request;

use MediaWiki\Config\Config;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use UnexpectedValueException;

/**
 * Handle sending Content-Security-Policy headers
 *
 * @author Copyright 2015–2018 Brian Wolff
 * @see https://www.w3.org/TR/CSP2/
 * @since 1.32
 */
class ContentSecurityPolicy {
	public const REPORT_ONLY_MODE = 1;
	public const FULL_MODE = 2;

	// Used for uploaded files. Keep in sync with images/.htaccess
	private const UPLOAD_CSP = "default-src 'none'; style-src 'unsafe-inline' data:;" .
		"font-src data:; img-src data: 'self'; media-src data: 'self'; sandbox";
	private const UPLOAD_CSP_PDF = "default-src 'none'; style-src 'unsafe-inline' data:; object-src 'self';" .
		"font-src data:; img-src data: 'self'; media-src data: 'self';";

	/** @var Config The site configuration object */
	private $mwConfig;
	/** @var WebResponse */
	private $response;

	/** @var string[] */
	private $extraDefaultSrc = [];
	/** @var string[] */
	private $extraScriptSrc = [];
	/** @var string[] */
	private $extraStyleSrc = [];

	/** @var HookRunner */
	private $hookRunner;

	/**
	 * @note As a general rule, you would not construct this class directly
	 *  but use the instance from OutputPage::getCSP()
	 * @internal
	 * @param WebResponse $response
	 * @param Config $mwConfig
	 * @param HookContainer $hookContainer
	 * @since 1.35 Method signature changed
	 */
	public function __construct(
		WebResponse $response,
		Config $mwConfig,
		HookContainer $hookContainer
	) {
		$this->response = $response;
		$this->mwConfig = $mwConfig;
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	/**
	 * Get the CSP directives for the wiki.
	 * @return string[] Array of CSP directives (header name => header value). The array keys will be
	 *    ContentSecurityPolicy::FULL_MODE and ContentSecurityPolicy::REPORT_ONLY_MODE; they might not
	 *    be present if the wiki is configured no to use the given type of CSP.
	 * @phan-return array{Content-Security-Policy?:string,Content-Security-Policy-Report-Only?:string}
	 * @since 1.42
	 */
	public function getDirectives() {
		$cspConfig = $this->mwConfig->get( MainConfigNames::CSPHeader );
		$cspConfigReportOnly = $this->mwConfig->get( MainConfigNames::CSPReportOnlyHeader );

		$cspPolicy = $this->makeCSPDirectives( $cspConfig, self::FULL_MODE );
		$cspReportOnlyPolicy = $this->makeCSPDirectives( $cspConfigReportOnly, self::REPORT_ONLY_MODE );

		return array_filter( [
			$this->getHeaderName( self::FULL_MODE ) => $cspPolicy,
			$this->getHeaderName( self::REPORT_ONLY_MODE ) => $cspReportOnlyPolicy,
		] );
	}

	/**
	 * Send CSP headers based on wiki config
	 *
	 * Main method that callers (OutputPage) are expected to use.
	 * As a general rule, you would never call this in an extension unless
	 * you have disabled OutputPage and are fully controlling the output.
	 *
	 * @since 1.35
	 */
	public function sendHeaders() {
		$directives = $this->getDirectives();
		foreach ( $directives as $headerName => $policy ) {
			$this->response->header( "$headerName: $policy" );
		}

		// This used to insert a <meta> tag here, per advice at
		// https://blogs.dropbox.com/tech/2015/09/unsafe-inline-and-nonce-deployment/
		// The goal was to prevent nonce from working after the page hit onready,
		// This would help in old browsers that didn't support nonces, and
		// also assist for Varnish-cached pages which repeat nonces.
		// However, this is incompatible with how ResourceLoader runs code
		// from mw.loader.store, so it was removed.
	}

	/**
	 * @param int $reportOnly Either self::REPORT_ONLY_MODE or self::FULL_MODE
	 * @return string Name of http header
	 * @throws UnexpectedValueException
	 */
	private function getHeaderName( $reportOnly ) {
		if ( $reportOnly === self::REPORT_ONLY_MODE ) {
			return 'Content-Security-Policy-Report-Only';
		}

		if ( $reportOnly === self::FULL_MODE ) {
			return 'Content-Security-Policy';
		}
		throw new UnexpectedValueException( "Mode '$reportOnly' not recognised" );
	}

	/**
	 * Determine what CSP policies to set for this page
	 *
	 * @param array|bool $policyConfig Policy configuration
	 *   (Either $wgCSPHeader or $wgCSPReportOnlyHeader)
	 * @param int $mode self::REPORT_ONLY_MODE, self::FULL_MODE
	 * @return string Policy directives, or empty string for no policy.
	 */
	private function makeCSPDirectives( $policyConfig, $mode ) {
		if ( $policyConfig === false ) {
			// CSP is disabled
			return '';
		}
		if ( $policyConfig === true ) {
			$policyConfig = [];
		}

		$mwConfig = $this->mwConfig;

		if (
			self::isNonceRequired( $mwConfig ) ||
			self::isNonceRequiredArray( [ $policyConfig ] )
		) {
			wfDeprecated( 'wgCSPHeader "useNonces" option', '1.41' );
		}

		$additionalSelfUrls = $this->getAdditionalSelfUrls();
		$additionalSelfUrlsScript = $this->getAdditionalSelfUrlsScript();

		// If no default-src is sent at all, it seems browsers (or at least some),
		// interpret that as allow anything, but the spec seems to imply that
		// "data:" and "blob:" should be blocked.
		$defaultSrc = [ '*', 'data:', 'blob:' ];

		$imgSrc = false;
		$scriptSrc = [ "'unsafe-eval'", "blob:", "'self'" ];

		$scriptSrc = array_merge( $scriptSrc, $additionalSelfUrlsScript );
		if ( isset( $policyConfig['script-src'] )
			&& is_array( $policyConfig['script-src'] )
		) {
			foreach ( $policyConfig['script-src'] as $src ) {
				$scriptSrc[] = $this->escapeUrlForCSP( $src );
			}
		}
		// Note: default on if unspecified.
		if ( $policyConfig['unsafeFallback'] ?? true ) {
			// unsafe-inline should be ignored on browsers that support 'nonce-foo' sources.
			// Some older versions of firefox don't follow this rule, but new browsers do.
			// (Should be for at least Firefox 40+).
			$scriptSrc[] = "'unsafe-inline'";
		}
		// If default source option set to true or an array of urls,
		// set a restrictive default-src.
		// If set to false, we send a lenient default-src,
		// see the code above where $defaultSrc is set initially.
		if ( isset( $policyConfig['default-src'] )
			&& $policyConfig['default-src'] !== false
		) {
			$defaultSrc = array_merge(
				[ "'self'", 'data:', 'blob:' ],
				$additionalSelfUrls
			);
			if ( is_array( $policyConfig['default-src'] ) ) {
				foreach ( $policyConfig['default-src'] as $src ) {
					$defaultSrc[] = $this->escapeUrlForCSP( $src );
				}
			}
		}

		if ( $policyConfig['includeCORS'] ?? true ) {
			$CORSUrls = $this->getCORSSources();
			if ( !in_array( '*', $defaultSrc ) ) {
				$defaultSrc = array_merge( $defaultSrc, $CORSUrls );
			}
			// Unlikely to have * in scriptSrc, but doesn't
			// hurt to check.
			if ( !in_array( '*', $scriptSrc ) ) {
				$scriptSrc = array_merge( $scriptSrc, $CORSUrls );
			}
		}

		$defaultSrc = array_merge( $defaultSrc, $this->extraDefaultSrc );
		$scriptSrc = array_merge( $scriptSrc, $this->extraScriptSrc );

		$cssSrc = array_merge( $defaultSrc, $this->extraStyleSrc, [ "'unsafe-inline'" ] );

		$this->hookRunner->onContentSecurityPolicyDefaultSource( $defaultSrc, $policyConfig, $mode );
		$this->hookRunner->onContentSecurityPolicyScriptSource( $scriptSrc, $policyConfig, $mode );

		if ( isset( $policyConfig['report-uri'] ) && $policyConfig['report-uri'] !== true ) {
			if ( $policyConfig['report-uri'] === false ) {
				$reportUri = false;
			} else {
				$reportUri = $this->escapeUrlForCSP( $policyConfig['report-uri'] );
			}
		} else {
			$reportUri = $this->getReportUri( $mode );
		}

		// Only send an img-src, if we're sending a restrictive default.
		if ( !is_array( $defaultSrc )
			|| !in_array( '*', $defaultSrc )
			|| !in_array( 'data:', $defaultSrc )
			|| !in_array( 'blob:', $defaultSrc )
		) {
			// A future todo might be to make the allow options only
			// add all the allowed sites to the header, instead of
			// allowing all (Assuming there is a small number of sites).
			// For now, the external image feature disables the limits
			// CSP puts on external images.
			if ( $mwConfig->get( MainConfigNames::AllowExternalImages )
				|| $mwConfig->get( MainConfigNames::AllowExternalImagesFrom )
			) {
				$imgSrc = [ '*', 'data:', 'blob:' ];
			} elseif ( $mwConfig->get( MainConfigNames::EnableImageWhitelist ) ) {
				$whitelist = wfMessage( 'external_image_whitelist' )
					->inContentLanguage()
					->plain();
				if ( preg_match( '/^\s*[^\s#]/m', $whitelist ) ) {
					$imgSrc = [ '*', 'data:', 'blob:' ];
				}
			}
		}
		// Default value 'none'. true is none, false is nothing, string is single directive,
		// array is list.
		if ( !isset( $policyConfig['object-src'] ) || $policyConfig['object-src'] === true ) {
			$objectSrc = [ "'none'" ];
		} else {
			$objectSrc = (array)( $policyConfig['object-src'] ?: [] );
		}
		$objectSrc = array_map( $this->escapeUrlForCSP( ... ), $objectSrc );

		$directives = [];
		if ( $scriptSrc ) {
			$directives[] = 'script-src ' . implode( ' ', array_unique( $scriptSrc ) );
		}
		if ( $defaultSrc ) {
			$directives[] = 'default-src ' . implode( ' ', array_unique( $defaultSrc ) );
		}
		if ( $cssSrc ) {
			$directives[] = 'style-src ' . implode( ' ', array_unique( $cssSrc ) );
		}
		if ( $imgSrc ) {
			$directives[] = 'img-src ' . implode( ' ', array_unique( $imgSrc ) );
		}
		if ( $objectSrc ) {
			$directives[] = 'object-src ' . implode( ' ', $objectSrc );
		}
		if ( $reportUri ) {
			$directives[] = 'report-uri ' . $reportUri;
		}

		$this->hookRunner->onContentSecurityPolicyDirectives( $directives, $policyConfig, $mode );

		return implode( '; ', $directives );
	}

	/**
	 * Get the default report uri.
	 *
	 * @param int $mode self::*_MODE constant.
	 * @return string The URI to send reports to.
	 * @throws UnexpectedValueException if given invalid mode.
	 */
	private function getReportUri( $mode ) {
		$apiArguments = [
			'action' => 'cspreport',
			'format' => 'json'
		];
		if ( $mode === self::REPORT_ONLY_MODE ) {
			$apiArguments['reportonly'] = '1';
		}
		$reportUri = wfAppendQuery( wfScript( 'api' ), $apiArguments );

		// Per spec, ';' and ',' must be hex-escaped in report URI
		$reportUri = $this->escapeUrlForCSP( $reportUri );
		return $reportUri;
	}

	/**
	 * Given a url, convert to form needed for CSP.
	 *
	 * Currently this does either scheme + host, or
	 * if protocol relative, just the host. Future versions
	 * could potentially preserve some of the path, if its determined
	 * that that would be a good idea.
	 *
	 * @note This does the extra escaping for CSP, but assumes the url
	 *   has already had normal url escaping applied.
	 * @note This discards urls same as server name, as 'self' directive
	 *   takes care of that.
	 * @param string $url
	 * @return string|bool Converted url or false on failure
	 */
	private function prepareUrlForCSP( $url ) {
		$result = false;
		if ( preg_match( '/^[a-z][a-z0-9+.-]*:$/i', $url ) ) {
			// A schema source (e.g. blob: or data:)
			return $url;
		}
		$bits = wfGetUrlUtils()->parse( $url );
		if ( !$bits && strpos( $url, '/' ) === false ) {
			// probably something like example.com.
			// try again protocol-relative.
			$url = '//' . $url;
			$bits = wfGetUrlUtils()->parse( $url );
		}
		if ( $bits && isset( $bits['host'] )
			&& $bits['host'] !== $this->mwConfig->get( MainConfigNames::ServerName )
		) {
			$result = $bits['host'];
			if ( $bits['scheme'] !== '' ) {
				$result = $bits['scheme'] . $bits['delimiter'] . $result;
			}
			if ( isset( $bits['port'] ) ) {
				$result .= ':' . $bits['port'];
			}
			$result = $this->escapeUrlForCSP( $result );
		}
		return $result;
	}

	/**
	 * @return string[] Additional sources for loading scripts from
	 */
	private function getAdditionalSelfUrlsScript() {
		$additionalUrls = [];
		// wgExtensionAssetsPath for ?debug=true mode
		$pathVars = [
			MainConfigNames::LoadScript,
			MainConfigNames::ExtensionAssetsPath,
			MainConfigNames::ResourceBasePath,
		];

		foreach ( $pathVars as $path ) {
			$url = $this->mwConfig->get( $path );
			$preparedUrl = $this->prepareUrlForCSP( $url );
			if ( $preparedUrl ) {
				$additionalUrls[] = $preparedUrl;
			}
		}
		$RLSources = $this->mwConfig->get( MainConfigNames::ResourceLoaderSources );
		foreach ( $RLSources as $sources ) {
			foreach ( $sources as $value ) {
				$url = $this->prepareUrlForCSP( $value );
				if ( $url ) {
					$additionalUrls[] = $url;
				}
			}
		}

		return array_unique( $additionalUrls );
	}

	/**
	 * Get additional host names for the wiki (e.g. if static content loaded elsewhere)
	 *
	 * @note These are general load sources, not script sources
	 * @return string[] Array of other urls for wiki (for use in default-src)
	 */
	private function getAdditionalSelfUrls() {
		// XXX on a foreign repo, the included description page can have anything on it,
		// including inline scripts. But nobody does that.

		// In principle, you can have even more complex configs... (e.g. The urlsByExt option)
		$pathUrls = [];
		$additionalSelfUrls = [];

		// Future todo: The zone urls should never go into
		// style-src. They should either be only in img-src, or if
		// img-src unspecified they should be in default-src. Similarly,
		// the DescriptionStylesheetUrl only needs to be in style-src
		// (or default-src if style-src unspecified).
		$callback = static function ( $repo, &$urls ) {
			$urls[] = $repo->getZoneUrl( 'public' );
			$urls[] = $repo->getZoneUrl( 'transcoded' );
			$urls[] = $repo->getZoneUrl( 'thumb' );
			$urls[] = $repo->getDescriptionStylesheetUrl();
		};
		$repoGroup = MediaWikiServices::getInstance()->getRepoGroup();
		$localRepo = $repoGroup->getRepo( 'local' );
		$callback( $localRepo, $pathUrls );
		$repoGroup->forEachForeignRepo( $callback, [ &$pathUrls ] );

		// Globals that might point to a different domain
		$pathGlobals = [
			MainConfigNames::LoadScript,
			MainConfigNames::ExtensionAssetsPath,
			MainConfigNames::StylePath,
			MainConfigNames::ResourceBasePath,
		];
		foreach ( $pathGlobals as $path ) {
			$pathUrls[] = $this->mwConfig->get( $path );
		}
		foreach ( $pathUrls as $path ) {
			$preparedUrl = $this->prepareUrlForCSP( $path );
			if ( $preparedUrl !== false ) {
				$additionalSelfUrls[] = $preparedUrl;
			}
		}
		$RLSources = $this->mwConfig->get( MainConfigNames::ResourceLoaderSources );

		foreach ( $RLSources as $sources ) {
			foreach ( $sources as $value ) {
				$url = $this->prepareUrlForCSP( $value );
				if ( $url ) {
					$additionalSelfUrls[] = $url;
				}
			}
		}

		return array_unique( $additionalSelfUrls );
	}

	/**
	 * include domains that are allowed to send us CORS requests.
	 *
	 * Technically, $wgCrossSiteAJAXdomains lists things that are allowed to talk to us
	 * not things that we are allowed to talk to - but if something is allowed to talk to us,
	 * then there is a good chance that we should probably be allowed to talk to it.
	 *
	 * This is configurable with the 'includeCORS' key in the CSP config, and enabled
	 * by default.
	 * @note CORS domains with single character ('?') wildcards, are not included.
	 * @return array Additional hosts
	 */
	private function getCORSSources() {
		$additionalUrls = [];
		$CORSSources = $this->mwConfig->get( MainConfigNames::CrossSiteAJAXdomains );
		foreach ( $CORSSources as $source ) {
			if ( strpos( $source, '?' ) !== false ) {
				// CSP doesn't support single char wildcard
				continue;
			}
			$url = $this->prepareUrlForCSP( $source );
			if ( $url ) {
				$additionalUrls[] = $url;
			}
		}
		return $additionalUrls;
	}

	/**
	 * CSP spec says ',' and ';' are not allowed to appear in urls.
	 *
	 * @note This assumes that normal escaping has been applied to the url
	 * @param string $url URL (or possibly just part of one)
	 * @return string
	 */
	private function escapeUrlForCSP( $url ) {
		return str_replace(
			[ ';', ',' ],
			[ '%3B', '%2C' ],
			$url
		);
	}

	/**
	 * Does this browser give false positive reports?
	 *
	 * Some versions of firefox (40-42) incorrectly report a CSP
	 * violation for nonce sources, despite allowing them.
	 *
	 * @see https://bugzilla.mozilla.org/show_bug.cgi?id=1026520
	 * @param string $ua User-agent header
	 * @return bool
	 */
	public static function falsePositiveBrowser( $ua ) {
		return (bool)preg_match( '!Firefox/4[0-2]\.!', $ua );
	}

	/**
	 * Should we set nonce attribute
	 *
	 * @param Config $config
	 * @return bool
	 */
	public static function isNonceRequired( Config $config ) {
		$configs = [
			$config->get( MainConfigNames::CSPHeader ),
			$config->get( MainConfigNames::CSPReportOnlyHeader ),
		];
		return self::isNonceRequiredArray( $configs );
	}

	/**
	 * Does a specific config require a nonce
	 *
	 * @param array $configs An array of CSP config arrays
	 * @return bool
	 */
	private static function isNonceRequiredArray( array $configs ) {
		foreach ( $configs as $headerConfig ) {
			if (
				is_array( $headerConfig ) &&
				isset( $headerConfig['useNonces'] ) &&
				$headerConfig['useNonces']
			) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Get the nonce if nonce is in use
	 *
	 * Not currently supported or implemented.
	 *
	 * @since 1.35
	 * @return false
	 */
	public function getNonce() {
		return false;
	}

	/**
	 * If possible you should use a more specific source type then default.
	 *
	 * So for example, if an extension added a special page that loaded something
	 * it might call $this->getOutput()->getCSP()->addDefaultSrc( '*.example.com' );
	 *
	 * @since 1.35
	 * @param string $source Source to add.
	 *   e.g. blob:, *.example.com, %https://example.com, example.com/foo
	 */
	public function addDefaultSrc( $source ) {
		$this->extraDefaultSrc[] = $this->prepareUrlForCSP( $source );
	}

	/**
	 * So for example, if an extension added a special page that loaded external CSS
	 * it might call $this->getOutput()->getCSP()->addStyleSrc( '*.example.com' );
	 *
	 * @since 1.35
	 * @param string $source Source to add.
	 *   e.g. blob:, *.example.com, %https://example.com, example.com/foo
	 */
	public function addStyleSrc( $source ) {
		$this->extraStyleSrc[] = $this->prepareUrlForCSP( $source );
	}

	/**
	 * So for example, if an extension added a special page that loaded something
	 * it might call $this->getOutput()->getCSP()->addScriptSrc( '*.example.com' );
	 *
	 * @since 1.35
	 * @warning Be careful including external scripts, as they can take over accounts.
	 * @param string $source Source to add.
	 *   e.g. blob:, *.example.com, %https://example.com, example.com/foo
	 */
	public function addScriptSrc( $source ) {
		$this->extraScriptSrc[] = $this->prepareUrlForCSP( $source );
	}

	/**
	 * Get the CSP header for a specific file
	 *
	 * @note Only used in img_auth.php & thumb.php. Normal image serving
	 * handled by a .htaccess file
	 *
	 * @param string $filename
	 * @return string|null CSP header (Without header name prefix)
	 */
	public static function getMediaHeader( string $filename ) {
		$config = MediaWikiServices::getInstance()->getMainConfig();
		if ( !$config->get( MainConfigNames::CSPUploadEntryPoint ) ) {
			return null;
		}
		// Some browsers (Chrome) require allowing objects to
		// render PDFs. Generally plugins are slightly higher-risk
		// so only allow it on pdf files.
		if ( strtolower( substr( $filename, -4 ) ) === '.pdf' ) {
			return self::UPLOAD_CSP_PDF;
		}
		return self::UPLOAD_CSP;
	}
}
