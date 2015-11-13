<?php
/**
 * Handle sending Content-Security-Policy headers
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
	const REPORT_ONLY_MODE = true;
	const FULL_MODE = false;

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
	 * @param $reportOnly boolean Either self::REPORT_ONLY_MODE or self::FULL_MODE
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
	 * Send CSP headers based on wiki config
	 *
	 * Main method that callers are expected to use
	 * @param $context IContextSource - A context object, the associated OutputPage
	 *  object must be the one that the page in question was generated with.
	 */
	public static function sendHeaders( IContextSource $context ) {
		$csp = new CSP(
			$context->getOutput()->getCSPNonce(),
			$context->getRequest()->response(),
			$context->getConfig()
		);
		$cspConfig = $context->getConfig()->get( 'CSPHeader' );
		$cspConfigReportOnly = $context->getConfig()->get( 'CSPReportOnlyHeader' );

		$csp->sendCSPHeader( $cspConfig, self::REPORT_ONLY_MODE );
		$csp->sendCSPHeader( $cspConfigReportOnly, self::FULL_MODE );
	}

	private function getHeaderName( $reportOnly ) {
		if ( $reportOnly === self::REPORT_ONLY_MODE ) {
			return 'Content-Security-Policy-Report-Only';
		} else {
			return 'Content-Security-Policy';
		}
	}

	/**
	 * Determine what CSP policies to set for this page
	 *
	 * @param $config array|bool Policy configuration (Either $wgCSPHeader or $wgCSPReportOnlyHeader)
	 * @param $mode boolean self::REPORT_ONLY_MODE or self::FULL_MODE
	 * @return string Policy directives, or empty string for no policy.
	 */
	private function makeCSPDirectives( $policyConfig, $mode ) {
		if ( !$policyConfig ) {
			return '';
		}

		$mwConfig = $this->mwConfig;

		$apiArguments = array(
			'action' => 'cspreport',
			'format' => 'json'
		);
		if ( $mode === self::REPORT_ONLY_MODE ) {
			$apiArguments['reportonly'] = '1';
		}
		$reportUri = wfAppendQuery( wfScript( 'api' ), $apiArguments ) ;

		// Per spec, ';' and ',' must be hex-escaped in report uri
		$reportUri = str_replace( array( ';', ',' ), array( '%3B', '%2C' ), $reportUri );


		// XXX on a foreign repo, the included description page can have anything on it,
		// including inline scripts. But nobody sane does that.

		// In principle, you can have even more complex configs... (e.g. The urlsByExt option)

		$pathUrls = array();
		$callback = function ( $repo, &$urls ) {
			$urls[] = $repo->getZoneUrl( 'public' );
			$urls[] = $repo->getZoneUrl( 'transcoded' );
			$urls[] = $repo->getZoneUrl( 'thumb' );
			$urls[] = $repo->getDescriptionStylesheetUrl();
		};
		$localRepo = RepoGroup::singleton()->getRepo( 'local' );
		$callback( $localRepo, $pathUrls );
		RepoGroup::singleton()->forEachForeignRepo( $callback, array( &$pathUrls ) );

		$additionalSelfUrls = array();

		// Globals that might point to a different domain
		$pathGlobals = array( 'LoadScript', 'ExtensionAssetsPath', 'StylePath', 'ResourceBasePath' );
		foreach ( $pathGlobals as $path ) {
			$pathUrls[] = $mwConfig->get( $path );
		}
		foreach ( $pathUrls as $path ) {
			$bits = wfParseUrl( $path );
			if ( $bits && isset( $bits['host'] ) && $bits['host'] !== $mwConfig->get( 'ServerName' ) ) {
				// XXX is it better to include full path in whitelisted url or just hostname?
				$host = $bits['scheme'] === '' ? $bits['host']	: $bits['scheme'] . $bits['delimiter'] . $bits['host'];
				$additionalSelfUrls[] = str_replace( array( ';', ',' ), array( '%3B', '%2C' ), $host );
			}
		}
		$additionalSelfUrls = array_unique( $additionalSelfUrls );

		$additionalSelfUrlsScript = array();
		$bits = wfParseUrl( $mwConfig->get( 'LoadScript' ) );
		if ( $bits && isset( $bits['host'] ) && $bits['host'] !== $mwConfig->get( 'ServerName' ) ) {
			$host = $bits['scheme'] === '' ? $bits['host']	: $bits['scheme'] . $bits['delimiter'] . $bits['host'];
			$additionalSelfUrlsScript[] = str_replace( array( ';', ',' ), array( '%3B', '%2C' ), $host );
		}

		$defaultSrc = false;
		$cssSrc = false;
		$scriptSrc = array( "'unsafe-eval'", "'self'", "'nonce-" . $this->nonce . "'");
		$scriptSrc = array_merge( $scriptSrc, $additionalSelfUrlsScript );
		if ( is_array( $policyConfig ) ) {
			if ( isset( $policyConfig['script-src'] ) && is_array( $policyConfig['script-src'] ) ) {
				foreach ( $policyConfig['script-src'] as $src ) {
					$src = str_replace( array( ';', ',' ), array( '%3B', '%2C' ), $src );
					$scriptSrc[] = $src;
				}
			}
			if ( isset( $policyConfig['default-src'] ) && $policyConfig['default-src'] ) {
// FIXME, blob: for UpWiz?
				$defaultSrc = array_merge( array( "'self'", 'data:' ), $additionalSelfUrls );
				if ( is_array( $policyConfig['default-src'] ) ) {
					foreach( $policyConfig['default-src'] as $src ) {
						$src = str_replace( array( ';', ',' ), array( '%3B', '%2C' ), $src );
						$defaultSrc[] = $src;
					}
				}
			}
		}

		Hooks::run( 'CSPDefaultSource', array( &$defaultSrc, $policyConfig, $mode ) );
		Hooks::run( 'CSPScriptSource', array( &$scriptSrc, $policyConfig, $mode ) );

		if ( is_array( $defaultSrc ) ) {
			$cssSrc = array_merge( $defaultSrc, array( "'unsafe-inline'" ) );
		}

		if ( isset( $policyConfig['report-uri'] ) && $policyConfig['report-uri'] !== true ) {
			$reportUri = $policyConfig['report-uri'];
		}

		$directives = array();
		if ( $scriptSrc ) {
			$directives[] = 'script-src ' . implode( $scriptSrc, ' ' );
		}
		if ( $defaultSrc ) {
			$directives[] = 'default-src ' . implode( $defaultSrc, ' ' );
		}
		if ( $cssSrc ) {
			$directives[] = 'style-src ' . implode( $cssSrc, ' ' );
		}
		if ( $reportUri ) {
			$directives[] = 'report-uri ' . $reportUri;
		}

		Hooks::run( 'CSPDirectives', array( &$directives, $policyConfig, $mode ) );

		return implode( $directives, '; ' );
	}
}
