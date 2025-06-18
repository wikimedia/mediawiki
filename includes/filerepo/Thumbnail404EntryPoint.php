<?php
/**
 * Entry point implementation for automatically generating missing media thumbnails
 * on the fly.
 *
 * @see \MediaWiki\FileRepo\ThumbnailEntryPoint
 * @see /thumb.php The web entry point.
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
 * @ingroup Media
 */

namespace MediaWiki\FileRepo;

use MediaWiki\MainConfigNames;

class Thumbnail404EntryPoint extends ThumbnailEntryPoint {

	protected function handleRequest() {
		$thumbPath = $this->getConfig( MainConfigNames::ThumbPath );

		if ( $thumbPath ) {
			$relPath = $this->getRequestPathSuffix( $thumbPath );
		} else {
			// Determine the request path relative to the thumbnail zone base
			$repo = $this->getServiceContainer()->getRepoGroup()->getLocalRepo();
			$baseUrl = $repo->getZoneUrl( 'thumb' );
			if ( str_starts_with( $baseUrl, '/' ) ) {
				$basePath = $baseUrl;
			} else {
				$basePath = parse_url( $baseUrl, PHP_URL_PATH );
			}
			$relPath = $this->getRequestPathSuffix( "$basePath" );
		}

		$params = $this->extractThumbRequestInfo( $relPath ); // basic wiki URL param extracting
		if ( $params == null ) {
			$this->thumbError( 400, 'The specified thumbnail parameters are not recognized.' );
			return;
		}

		$this->streamThumb( $params ); // stream the thumbnail
	}

	/**
	 * Convert pathinfo type parameter, into normal request parameters
	 *
	 * So for example, if the request was redirected from
	 * /w/images/thumb/a/ab/Foo.png/120px-Foo.png. The $thumbRel parameter
	 * of this function would be set to "a/ab/Foo.png/120px-Foo.png".
	 * This method is responsible for turning that into an array
	 * with the following keys:
	 *  * f => the filename (Foo.png)
	 *  * rel404 => the whole thing (a/ab/Foo.png/120px-Foo.png)
	 *  * archived => 1 (If the request is for an archived thumb)
	 *  * temp => 1 (If the file is in the "temporary" zone)
	 *  * thumbName => the thumbnail name, including parameters (120px-Foo.png)
	 *
	 * Transform specific parameters are set later via extractThumbParams().
	 *
	 * @param string $thumbRel Thumbnail path relative to the thumb zone
	 *
	 * @return array|null Associative params array or null
	 */
	protected function extractThumbRequestInfo( $thumbRel ) {
		$repo = $this->getServiceContainer()->getRepoGroup()->getLocalRepo();

		$hashDirReg = $subdirReg = '';
		$hashLevels = $repo->getHashLevels();
		for ( $i = 0; $i < $hashLevels; $i++ ) {
			$subdirReg .= '[0-9a-f]';
			$hashDirReg .= "$subdirReg/";
		}

		// Check if this is a thumbnail of an original in the local file repo
		if ( preg_match( "!^((archive/)?$hashDirReg([^/]*)/([^/]*))$!", $thumbRel, $m ) ) {
			[ /*all*/, $rel, $archOrTemp, $filename, $thumbname ] = $m;
			// Check if this is a thumbnail of a temp file in the local file repo
		} elseif ( preg_match( "!^(temp/)($hashDirReg([^/]*)/([^/]*))$!", $thumbRel, $m ) ) {
			[ /*all*/, $archOrTemp, $rel, $filename, $thumbname ] = $m;
		} else {
			return null; // not a valid looking thumbnail request
		}

		$params = [ 'f' => $filename, 'rel404' => $rel ];
		if ( $archOrTemp === 'archive/' ) {
			$params['archived'] = 1;
		} elseif ( $archOrTemp === 'temp/' ) {
			$params['temp'] = 1;
		}

		$params['thumbName'] = $thumbname;
		return $params;
	}

}
