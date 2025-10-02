<?php // For broken web servers: ><pre>

// If you are reading this in your web browser, your server is probably
// not configured correctly to run PHP applications!
//
// See the README, INSTALL, and UPGRADE files for basic setup instructions
// and pointers to the online documentation.
//
// https://www.mediawiki.org/wiki/Special:MyLanguage/MediaWiki
//
// -------------------------------------------------

/**
 * The.php entry point for web browser navigations, usually routed to
 * an Action or SpecialPage subclass.
 *
 * @see MediaWiki\Actions\ActionEntryPoint The implementation.
 *
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Actions\ActionEntryPoint;
use MediaWiki\Context\RequestContext;
use MediaWiki\EntryPointEnvironment;
use MediaWiki\MediaWikiServices;

define( 'MW_ENTRY_POINT', 'index' );

// Bail on old versions of PHP, or if composer has not been run yet to install
// dependencies. Using dirname( __FILE__ ) here because __DIR__ is PHP5.3+.
// phpcs:ignore MediaWiki.Usage.DirUsage.FunctionFound
require_once dirname( __FILE__ ) . '/includes/PHPVersionCheck.php';
wfEntryPointCheck( 'html', dirname( $_SERVER['SCRIPT_NAME'] ) );

require __DIR__ . '/includes/WebStart.php';

// Create the entry point object and call run() to handle the request.
( new ActionEntryPoint(
	RequestContext::getMain(),
	new EntryPointEnvironment(),
	// TODO: Maybe create a light-weight services container here instead.
	MediaWikiServices::getInstance()
) )->run();
