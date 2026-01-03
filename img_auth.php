<?php
/**
 * The web entry point for serving non-public images to logged-in users.
 *
 * To use this, see https://www.mediawiki.org/wiki/Special:MyLanguage/Manual:Image_authorization
 *
 * - Set $wgUploadDirectory to a non-public directory (not web accessible)
 * - Set $wgUploadPath to point to this file
 *
 * Optional Parameters
 *
 * - Set $wgImgAuthDetails = true if you want the reason the access was denied messages to
 *       be displayed instead of just the 403 error (doesn't work on IE anyway),
 *       otherwise it will only appear in error logs
 *
 *  For security reasons, you usually don't want your user to know *why* access was denied,
 *  just that it was. If you want to change this, you can set $wgImgAuthDetails to 'true'
 *  in localsettings.php and it will give the user the reason why access was denied.
 *
 * Your server needs to support REQUEST_URI or PATH_INFO; CGI-based
 * configurations sometimes don't.
 *
 * @see MediaWiki\FileRepo\AuthenticatedFileEntryPoint The implementation.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup entrypoint
 */

use MediaWiki\Context\RequestContext;
use MediaWiki\EntryPointEnvironment;
use MediaWiki\FileRepo\AuthenticatedFileEntryPoint;
use MediaWiki\MediaWikiServices;

define( 'MW_NO_OUTPUT_COMPRESSION', 1 );
define( 'MW_ENTRY_POINT', 'img_auth' );
require __DIR__ . '/includes/WebStart.php';

( new AuthenticatedFileEntryPoint(
	RequestContext::getMain(),
	new EntryPointEnvironment(),
	MediaWikiServices::getInstance()
) )->run();
