<?php
/**
 * The web entry point to be used as 404 handler behind a web server rewrite
 * rule for media thumbnails, internally handled via thumb.php.
 *
 * This script will interpret a request URL like
 * `/w/images/thumb/a/a9/Example.jpg/50px-Example.jpg` and treat it as
 * if it was a request to thumb.php with the relevant query parameters filled
 * out. See also $wgGenerateThumbnailOnParse.
 *
 * @see thumb.php
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup entrypoint
 * @ingroup Media
 */

use MediaWiki\Context\RequestContext;
use MediaWiki\EntryPointEnvironment;
use MediaWiki\FileRepo\Thumbnail404EntryPoint;
use MediaWiki\MediaWikiServices;

define( 'MW_NO_OUTPUT_COMPRESSION', 1 );
define( 'MW_ENTRY_POINT', 'thumb_handler' );

require __DIR__ . '/includes/WebStart.php';

( new Thumbnail404EntryPoint(
	RequestContext::getMain(),
	new EntryPointEnvironment(),
	MediaWikiServices::getInstance()
) )->run();
