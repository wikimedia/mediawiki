<?php
/**
 * The web entry point for all %Action API queries, handled by ApiMain
 * and ApiBase subclasses.
 *
 * @see ApiEntryPoint The corresponding entry point class
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup entrypoint
 * @ingroup API
 */

use MediaWiki\Api\ApiEntryPoint;
use MediaWiki\Context\RequestContext;
use MediaWiki\EntryPointEnvironment;
use MediaWiki\MediaWikiServices;

// So extensions (and other code) can check whether they're running in API mode
define( 'MW_API', true );
define( 'MW_ENTRY_POINT', 'api' );

require __DIR__ . '/includes/WebStart.php';

// Construct entry point object and call doRun() to handle the request.
( new ApiEntryPoint(
	RequestContext::getMain(),
	new EntryPointEnvironment(),
	MediaWikiServices::getInstance()
) )->run();
