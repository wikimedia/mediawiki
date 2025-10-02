<?php
/**
 * The web entry point for retrieving media thumbnails.
 *
 * @see MediaWiki\FileRepo\FileEntryPoint The implementation.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup entrypoint
 * @ingroup Media
 */

use MediaWiki\Context\RequestContext;
use MediaWiki\EntryPointEnvironment;
use MediaWiki\FileRepo\ThumbnailEntryPoint;
use MediaWiki\MediaWikiServices;

define( 'MW_NO_OUTPUT_COMPRESSION', 1 );
define( 'MW_ENTRY_POINT', 'thumb' );

require __DIR__ . '/includes/WebStart.php';

( new ThumbnailEntryPoint(
	RequestContext::getMain(),
	new EntryPointEnvironment(),
	MediaWikiServices::getInstance()
) )->run();
