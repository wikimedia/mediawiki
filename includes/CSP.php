<?php
/**
 * Handle sending Content-Security-Policy headers
 *
 * @see http://www.w3.org/TR/CSP2/
 *
 * Copyright © 2015 Brian Wolff
 *
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
class CSP {
	const REPORT_ONLY_MODE = 1;
	const FULL_MODE = 2;
	const FULL_MODE_RESTRICTED = 3;

	/** @var string The nonce to use for inline scripts (from OutputPage) */
	private $nonce;
	/** @var Config The site configuration object */
	private $mwConfig;
	/** @var WebResponse */
	private $response;

	/**
	 * @param $nonce string
	 * @param $response WebResponse
	 * @param $mwConfig Config
	 */
	public function __construct( $nonce, WebResponse $response, Config $mwConfig ) {
		$this->nonce = $nonce;
		$this->response = $response;
		$this->mwConfig = $mwConfig;
	}

	/**
	 * Send a single CSP header based on a given policy config.
	 *
	 * @note Most callers will probably want CSP::sendHeaders() instead.
	 * @param $csp Array CSP configuration
	 * @param $reportOnly int self::*_MODE constant
	 */
	public function sendCSPHeader( $csp, $reportOnly ) {
		$policy = $this->makeCSPDirectives( $csp, $reportOnly );
		$headerName = $this->getHeaderName( $reportOnly );
		if ( $policy ) {
			$this->response->header(
				"$headerName: $policy"
			);
		}
	}

	/**
	 * Return the meta header to use for after load restricted mode
	 *
	 * @param $csp Array CSP configuration
	 */
	public function getMetaHeader( $csp ) {
		return $this->makeCSPDirectives( $csp, self::FULL_MODE_RESTRICTED );
	}

	/**
	 * Send CSP headers based on wiki config
	 *
	 * Main method that callers are expected to use
	 * @param $context IContextSource - A context object, the associated OutputPage
	 *  object must be the one that the page in question was generated with.
	 */
	public static function sendHeaders( IContextSource $context ) {
		$out = $context->getOutput();
		$csp = new CSP(
			$out->getCSPNonce(),
			$context->getRequest()->response(),
			$context->getConfig()
		);

		$cspConfig = $context->getConfig()->get( 'CSPHeader' );
		$cspConfigReportOnly = $context->getConfig()->get( 'CSPReportOnlyHeader' );

		$csp->sendCSPHeader( $cspConfig, self::FULL_MODE );
		$csp->sendCSPHeader( $cspConfigReportOnly, self::REPORT_ONLY_MODE );

		// Include <meta> header which increases security level after initial load.
		// This helps mitigate attacks on browsers not supporting CSP2. It also
		// helps mitigate attacks due to the shared nonce that non-logged in users
		// get due to varnish cache.
		// Unclear if this is the best place to insert the meta tag, or if
		// it should be in a RL module. I figure its best to do this as early
		// as possible.
		// FIXME: Needs testing to see if this actually works properly
		$metaHeader = $csp->getMetaHeader( $cspConfig );
		if ( $metaHeader ) {
			$context->getOutput()->addScript(
				ResourceLoader::makeInlineScript(
					$csp->makeMetaInsertScript(
						$metaHeader
					),
					$out->getCSPNonce()
				)
			);
		}
	}

	private function makeMetaInsertScript( $metaContents ) {
		return "$('\\x3Cmeta http-equiv=\"Content-Security-Policy\"\\x3E')" .
			'.attr("content",' .
			Xml::encodeJsVar( $metaContents ) .
			').prependTo($("head"))';
	}

	/**
	 * Get the name of the HTTP header to use.
	 *
	 * @param $reportOnly int Either self::REPORT_ONLY_MODE or self::FULL_MODE
	 * @return String name of http header
	 * @throws UnexpectedValueException if you feed it self::FULL_MODE_RESTRICTED.
	 */
	private function getHeaderName( $reportOnly ) {
		if ( $reportOnly === self::REPORT_ONLY_MODE ) {
			return 'Content-Security-Policy-Report-Only';
		} elseif ( $reportOnly === self::FULL_MODE ) {
			return 'Content-Security-Policy';
		}
		throw UnexpectedValueException( $reportOnly );
	}

	/**
	 * Determine what CSP policies to set for this page
	 *
	 * @param $config array|bool Policy configuration (Either $wgCSPHeader or $wgCSPReportOnlyHeader)
	 * @param $mode int self::REPORT_ONLY_MODE, self::FULL_MODE or Self::FULL_MODE_RESTRICTED
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

		$additionalSelfUrls = $this->getAdditionalSelfUrls();
		$additionalSelfUrlsScript = $this->getAdditionalSelfUrlsScript();
		$nonceSrc = "'nonce-" . $this->nonce . "'";

		// If no default-src is sent at all, it
		// seems browsers (or at least some), interpret
		// that as allow anything, but the spec seems
		// to imply that data: and blob: should be
		// blocked.
		$defaultSrc = [ '*', 'data:', 'blob:' ];

		$cssSrc = false;
		$imgSrc = false;
		$scriptSrc = [ "'unsafe-eval'", "'self'" ];
		if ( $mode !== self::FULL_MODE_RESTRICTED ) {
			$scriptSrc[] = $nonceSrc;
		}
		$scriptSrc = array_merge( $scriptSrc, $additionalSelfUrlsScript );
		if ( isset( $policyConfig['script-src'] )
			&& is_array( $policyConfig['script-src'] )
		) {
			foreach ( $policyConfig['script-src'] as $src ) {
				$scriptSrc[] = $this->escapeUrlForCSP( $src );
			}
		}
		// Note: default on if unspecified.
		if ( ( !isset( $policyConfig['unsafeFallback'] )
			|| $policyConfig['unsafeFallback'] )
			&& $mode !== self::FULL_MODE_RESTRICTED
		) {
			// unsafe-inline should be ignored on browsers
			// that support 'nonce-foo' sources.
			// Some older versions of firefox don't follow this
			// rule, but new browsers do. (Should be for at least
			// firefox 40+).
			$scriptSrc[] = "'unsafe-inline'";
		}
		// If default source option set to true or
		// an array of urls, set a restrictive default-src.
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

		if ( !isset( $policyConfig['includeCORS'] ) || $policyConfig['includeCORS'] ) {
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

		Hooks::run( 'CSPDefaultSource', [ &$defaultSrc, $policyConfig, $mode ] );
		Hooks::run( 'CSPScriptSource', [ &$scriptSrc, $policyConfig, $mode ] );

		// Check if array just in case the hook made it false
		if ( is_array( $defaultSrc ) ) {
			$cssSrc = array_merge( $defaultSrc, [ "'unsafe-inline'" ] );
		}

		if ( $mode === self::FULL_MODE_RESTRICTED ) {
			// report-uri disallowed in <meta> tags.
			$reportUri = false;
		} elseif ( isset( $policyConfig['report-uri'] ) && $policyConfig['report-uri'] !== true ) {
			if ( $policyConfig['report-uri'] === false ) {
				$reportUri = false;
			} else {
				$reportUri = $this->escapeUrlForCSP( $policyConfig['report-uri'] );
			}
		} else {
			$reportUri = $this->getReportUri( $mode );
		}

		// Only send an img-src, if we're sending a restricitve default.
		if ( !is_array( $defaultSrc )
			|| !in_array( '*', $defaultSrc )
			|| !in_array( 'data:', $defaultSrc )
			|| !in_array( 'blob:', $defaultSrc )
		) {
			// A future todo might be to make the whitelist options only
			// add all the whitelisted sites to the header, instead of
			// allowing all (Assuming there is a small number of sites).
			// For now, the external image feature disables the limits
			// CSP puts on external images.
			if ( $mwConfig->get( 'AllowExternalImages' )
				|| $mwConfig->get( 'AllowExternalImagesFrom' )
				|| $mwConfig->get( 'AllowImageTag' )
			) {
				$imgSrc = [ '*', 'data:', 'blob:' ];
			} elseif ( $mwConfig->get( 'EnableImageWhitelist' ) ) {
				$whitelist = wfMessage( 'external_image_whitelist' )
					->inContentLanguage()
					->plain();
				if ( preg_match( '/^\s*[^\s#]/m', $whitelist ) ) {
					$imgSrc = [ '*', 'data:', 'blob:' ];
				}
			}
		}

		$directives = [];
		if ( $scriptSrc ) {
			$directives[] = 'script-src ' . implode( $scriptSrc, ' ' );
		}
		if ( $defaultSrc ) {
			$directives[] = 'default-src ' . implode( $defaultSrc, ' ' );
		}
		if ( $cssSrc ) {
			$directives[] = 'style-src ' . implode( $cssSrc, ' ' );
		}
		if ( $imgSrc ) {
			$directives[] = 'img-src ' . implode( $imgSrc, ' ' );
		}
		if ( $reportUri ) {
			$directives[] = 'report-uri ' . $reportUri;
		}

		Hooks::run( 'CSPDirectives', [ &$directives, $policyConfig, $mode ] );

		return implode( $directives, '; ' );
	}

	/**
	 * Get the default report uri.
	 *
	 * @param $mode int self::*_MODE constant. Do not use with self::FULL_MODE_RESTRICTED
	 * @return String The URI to send reports to.
	 * @throws UnexpectedValueException if given invalid mode.
	 */
	private function getReportUri( $mode ) {
		if ( $mode === self::FULL_MODE_RESTRICTED ) {
			throw UnexpectedValueException( $mode );
		}
		$apiArguments = [
			'action' => 'cspreport',
			'format' => 'json'
		];
		if ( $mode === self::REPORT_ONLY_MODE ) {
			$apiArguments['reportonly'] = '1';
		}
		$reportUri = wfAppendQuery( wfScript( 'api' ), $apiArguments );

		// Per spec, ';' and ',' must be hex-escaped in report uri
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
	 * @param $url String
	 * @return String|bool Converted url or false on failure
	 */
	private function prepareUrlForCSP( $url ) {
		$result = false;
		if ( preg_match( '/^[a-z][a-z0-9+.-]*:$/i', $url ) ) {
			// A schema source (e.g. blob: or data:)
			return $url;
		}
		$bits = wfParseUrl( $url );
		if ( !$bits && strpos( $url, '/' ) === false ) {
			// probably something like example.com.
			// try again protocol-relative.
			$url = '//' . $url;
			$bits = wfParseUrl( $url );
		}
		if ( $bits && isset( $bits['host'] )
			&& $bits['host'] !== $this->mwConfig->get( 'ServerName' )
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
	 * Get additional script sources
	 *
	 * @return Array Additional sources for loading scripts from
	 */
	private function getAdditionalSelfUrlsScript() {
		$additionalUrls = [];
		// wgExtensionAssetsPath for ?debug=true mode
		$pathVars = [ 'LoadScript', 'ExtensionAssetsPath', 'ResourceBasePath' ];

		foreach ( $pathVars as $path ) {
			$url = $this->mwConfig->get( $path );
			$preparedUrl = $this->prepareUrlForCSP( $url );
			if ( $preparedUrl ) {
				$additionalUrls[] = $preparedUrl;
			}
		}
		$RLSources = $this->mwConfig->get( 'ResourceLoaderSources' );
		foreach ( $RLSources as $id => $value ) {
			$url = $this->prepareUrlForCSP( $value );
			if ( $url ) {
				$additionalUrls[] = $url;
			}
		}

		return array_unique( $additionalUrls );
	}

	/**
	 * Get additional host names for the wiki (e.g. if static content loaded elsewhere)
	 *
	 * @note These are general load sources, not script sources
	 * @return array Array of other urls for wiki (for use in default-src)
	 */
	private function getAdditionalSelfUrls() {
		// XXX on a foreign repo, the included description page can have anything on it,
		// including inline scripts. But nobody sane does that.

		// In principle, you can have even more complex configs... (e.g. The urlsByExt option)
		$pathUrls = [];
		$additionalSelfUrls = [];

		$callback = function ( $repo, &$urls ) {
			$urls[] = $repo->getZoneUrl( 'public' );
			$urls[] = $repo->getZoneUrl( 'transcoded' );
			$urls[] = $repo->getZoneUrl( 'thumb' );
			$urls[] = $repo->getDescriptionStylesheetUrl();
		};
		$localRepo = RepoGroup::singleton()->getRepo( 'local' );
		$callback( $localRepo, $pathUrls );
		RepoGroup::singleton()->forEachForeignRepo( $callback, [ &$pathUrls ] );

		// Globals that might point to a different domain
		$pathGlobals = [ 'LoadScript', 'ExtensionAssetsPath', 'StylePath', 'ResourceBasePath' ];
		foreach ( $pathGlobals as $path ) {
			$pathUrls[] = $this->mwConfig->get( $path );
		}
		foreach ( $pathUrls as $path ) {
			$preparedUrl = $this->prepareUrlForCSP( $path );
			if ( $preparedUrl !== false ) {
				$additionalSelfUrls[] = $preparedUrl;
			}
		}
		$RLSources = $this->mwConfig->get( 'ResourceLoaderSources' );
		foreach ( $RLSources as $id => $value ) {
			$url = $this->prepareUrlForCSP( $value );
			if ( $url ) {
				$additionalSelfUrls[] = $url;
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
	 * @return Array Additional hosts
	 */
	private function getCORSSources() {
		$additionalUrls = [];
		$CORSSources = $this->mwConfig->get( 'CrossSiteAJAXdomains' );
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
	 * @param $url URL (or possibly just part of one)
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
	 * Some versions of firefox (40-42) incorrectly report a csp
	 * violation for nonce sources, despite allowing them.
	 *
	 * @see https://bugzilla.mozilla.org/show_bug.cgi?id=1026520
	 * @param $ua String User-agent header
	 * @return boolean
	 */
	public static function falsePositiveBrowser( $ua ) {
		return (boolean)preg_match( '!Firefox/4[0-2]\.!', $ua );
	}

	/**
	 * Is CSP currently enabled (i.e. Should we set nonce attribute)
	 *
	 * @param Config $config Configuration object
	 * @return boolean
	 */
	public static function isEnabled( Config $config ) {
		return $config->get( 'CSPHeader' ) !== false
			|| $config->get( 'CSPReportOnlyHeader' ) !== false;
	}
}
