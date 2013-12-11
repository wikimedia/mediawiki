<?php


// Initialise common code.
// require __DIR__ . '/includes/WebStart.php';



$invalid = "invalid manifest params";

$manifestPlatform = isset($_GET['platform']) ? $_GET['platform'] : null;
$manifestType = isset($_GET['type']) ? $_GET['type'] : null;

if (empty($manifestPlatform) || empty($manifestType)) {
	echo $invalid;
	exit;
}

$tuple = "$manifestPlatform.$manifestType";

switch ($tuple) {
	case "chrome.app":
		genChromeApp();
		break;

	case "chrome.cache":
		genChromeCache();
		break;

	case "firefox.app":
		genFirefoxApp();
		break;

	case "firefox.cache":
		genFirefoxCache();
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
            "web_url": "http://localhost:8080/wiki/Main_Page?mobileaction=app"
        }
    }
}
EOD;
}

function genChromeCache() {
	genAppCache();
}

function genFirefoxApp() {
	header("Content-Type: application/x-web-app-manifest+json");
	// TODO: change launch_path to /wiki/Special:App
	echo <<<EOD
{
  "version": "0.4",
  "name": "Wikipedia",
  "description": "Wikipedia for Firefox OS",
  "launch_path": "/wiki/Special:App",
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

function genFirefoxCache() {
	genAppCache();
}

function genAppCache() {
	header( 'Content-Type: text/cache-manifest' );
	echo <<<EOD
CACHE MANIFEST
# Tue Nov 19 20:45:59 UTC 2013
# replace the following with /wiki/Special:App
/wiki/Special:App

NETWORK:
*
EOD;
}
