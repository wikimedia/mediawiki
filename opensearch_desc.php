<?php
/**
 * The web entry point for generating an OpenSearch description document.
 *
 * See <http://www.opensearch.org/> for the specification of the OpenSearch
 * "description" document. In a nut shell, this tells browsers how and where
 * to submit submit search queries to get a search results page back,
 * as well as how to get typeahead suggestions (see ApiOpenSearch).
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
 */

// This endpoint is supposed to be independent of request cookies and other
// details of the session. Enforce this constraint with respect to session use.
define( 'MW_NO_SESSION', 1 );

define( 'MW_ENTRY_POINT', 'opensearch_desc' );

require_once __DIR__ . '/includes/WebStart.php';

$url = $wgRestPath . '/v1/search';
$ctype = $wgRequest->getVal( 'ctype' );

if ( $ctype !== null ) {
	$url = wfAppendQuery( $url, [ 'ctype' => $ctype ] );
}

$wgRequest->response()->header( 'Location: ' . $url, true, 308 );
$wgRequest->response()->header( 'Cache-control: max-age=600' );
