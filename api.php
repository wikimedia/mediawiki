<?php
/**
 * The web entry point for all %Action API queries, handled by ApiMain
 * and ApiBase subclasses.
 *
 * @see ApiEntryPoint The corresponding entry point class
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
