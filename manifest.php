<?php

$invalid = "invalid manifest params";

// Bail if PHP is too low
if ( !function_exists( 'version_compare' ) || version_compare( phpversion(), '5.3.2' ) < 0 ) {
	// We need to use dirname( __FILE__ ) here cause __DIR__ is PHP5.3+
	require dirname( __FILE__ ) . '/includes/PHPVersionError.php';
	wfPHPVersionError( 'manifest.php' );
}

// Initialise common code.
require __DIR__ . '/includes/WebStart.php';
$ctx = RequestContext::getMain();
$skin = new SkinMobileAlpha( $ctx );
$request = $ctx->getRequest();
$response = $request->response();

$manifestPlatform = $request->getVal( 'platform' );
$manifestType = $request->getVal( 'type' );

if ( !$manifestPlatform && !$manifestType ) {
	genAppCache( $request, $response );
	exit;
}

if ( empty( $manifestPlatform ) || empty( $manifestType ) ) {
	echo $invalid;
	exit;
}

$tuple = "$manifestPlatform.$manifestType";

switch ( $tuple ) {
	case "chrome.app":
		genChromeApp();
		break;

	case "chrome.cache":
		genChromeCache( $request, $response );
		break;

	case "firefox.app":
		genFirefoxApp();
		break;

	case "firefox.cache":
		genFirefoxCache( $request, $response );;
		break;

	default:
		echo $invalid;
		break;
}


function genChromeApp() {
	// TODO: use mod_rewrite to fake it
	header( 'Content-Type:  application/webapp-manifest+json' );
	echo <<<EOD
{
    "name": "Wikipedia",
    "description": "Wikipedia for Chrome",
    "version": "0.4",
    "manifest_version": 3,
    "icons": {
        "128": "apps/W_launchicon_128_1101.png"
    },
    "app": {
        "urls": [
            "http://localhost:8080/wiki/"
        ],
        "launch": {
            "web_url": "http://localhost:8080/wiki/Special:MobileWebApp"
        }
    }
}
EOD;
}

function genChromeCache( WebRequest $req, WebResponse $resp ) {
	genAppCache( $req, $resp );
}

function genFirefoxApp() {
	header( "Content-Type: application/x-web-app-manifest+json" );
	echo <<<EOD
{
  "version": "0.4",
  "name": "Wikipedia",
  "description": "Wikipedia for Firefox OS",
  "launch_path": "/wiki/Special:MobileWebApp",
  "appcache_path": "/w/manifest.php?platform=firefox&type=cache",
  "icons": {
    "16": "/w/apps/W_launchicon_16_1101.png",
    "48": "/w/apps/W_launchicon_48_1101.png",
    "128": "/w/apps/W_launchicon_128_1101.png"
  },
  "developer": {
    "name": "Wikimedia Foundation",
    "url": "https://www.wikimediafoundation.org/"
  },
  "installs_allowed_from": ["*"],
  "locales": {},
  "default_locale": "en"
}
EOD;
}

function genFirefoxCache( WebRequest $req, WebResponse $resp ) {
	genAppCache( $req, $resp );
}

function genAppCache( WebRequest $req, WebResponse $resp ) {

	$scriptModules = array( 'startup' );
	$style = $req->getText( 'style' );
	$styleModules = $style ? explode( '|', $style ) : array( 'mobile.pagelist.styles', 'mobile.styles', 'mobile.styles.page' );
	$lang = $req->getText( 'lang' );
	$lang = $lang ? $lang : 'en';
	$skinName = $req->getText( 'skin' );
	$skinName = $skinName ? $skinName : 'mobile';
	$user = null;
	$version = null;
	$debug = false;
	$printable = false;
	$handheld = false;
	$target = $req->getText( 'target' );
	$target = $target ? $target : 'mobile';
	$extraQuery = array( 'target' => $target );

	$jsUrl = ResourceLoader::makeLoaderURL( $scriptModules, $lang, $skinName,
		$user, $version, $debug, 'scripts', $printable, $handheld, $extraQuery );
	$jsUrl = getRelativePath( $jsUrl );

	$styleUrl = ResourceLoader::makeLoaderURL( $styleModules, $lang, $skinName,
		$user, $version, $debug, 'styles', $printable, $handheld, $extraQuery );
	$styleUrl = getRelativePath( $styleUrl );


	$fr = new FauxRequest( array(
		'debug' => $debug,
		'lang' => $lang,
		'modules' => implode( ',', $scriptModules),
		'only' => 'scripts',
		'skin' => $skinName,
		'target' => $target,
	));

	$resourceLoader = new ResourceLoader();
	$rlcCtx = new ResourceLoaderContext( $resourceLoader, $fr );
	$startupUrl = ResourceLoaderStartUpModule::getStartupVersionUrl( $rlcCtx );

	$urls = "$jsUrl\n$styleUrl\n$startupUrl";
	$checksum = sha1( $urls );

	$resp->header( 'Content-type: text/cache-manifest; charset=UTF-8' );
	$resp->header( 'Cache-Control: public, must-revalidate, max-age=300, s-max-age=300' );
	$resp->header( "ETag: $checksum" );

	echo <<<HTML
CACHE MANIFEST

NETWORK:
*


HTML;

	echo <<<HTML
CACHE:
{$urls}

# {$checksum}

HTML;
}

function getRelativePath( $url ) {
	$parts = wfParseUrl( $url );
	return $parts['path'] && $parts['query'] ?
		$parts['path'] . '?' . $parts['query'] :
		$parts['path'];
}
