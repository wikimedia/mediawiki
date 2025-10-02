<?php
/**
 * @see MediaWiki\ResourceLoader\ResourceLoaderEntryPoint
 *
 * @license GPL-2.0-or-later
 * @file
 * @author Roan Kattouw
 * @author Trevor Parscal
 */

use MediaWiki\Context\RequestContext;
use MediaWiki\EntryPointEnvironment;
use MediaWiki\MediaWikiServices;
use MediaWiki\ResourceLoader\ResourceLoaderEntryPoint;

// This endpoint is supposed to be independent of request cookies and other
// details of the session. Enforce this constraint with respect to session use.
define( 'MW_NO_SESSION', 1 );

define( 'MW_ENTRY_POINT', 'load' );

require __DIR__ . '/includes/WebStart.php';

( new ResourceLoaderEntryPoint(
	RequestContext::getMain(),
	new EntryPointEnvironment(),
	MediaWikiServices::getInstance()
) )->run();
