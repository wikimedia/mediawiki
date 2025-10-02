<?php

/**
 * This is the entry point for the REST API.
 *
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Context\RequestContext;
use MediaWiki\EntryPointEnvironment;
use MediaWiki\MediaWikiServices;
use MediaWiki\Rest\EntryPoint;

define( 'MW_REST_API', true );
define( 'MW_ENTRY_POINT', 'rest' );

require __DIR__ . '/includes/WebStart.php';

( new EntryPoint(
	EntryPoint::getMainRequest(),
	RequestContext::getMain(),
	new EntryPointEnvironment(),
	MediaWikiServices::getInstance()
) )->run();
