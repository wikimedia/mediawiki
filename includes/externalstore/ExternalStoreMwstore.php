<?php
/**
 * External storage in a file backend.
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
 */

/**
 * File backend accessable external objects.
 *
 * In this system, each store "location" maps to the name of a file backend.
 * The file backends must be defined in $wgFileBackends and must be global
 * and fully qualified with a global "wikiId" prefix in the configuration.
 *
 * @ingroup ExternalStorage
 * @since 1.21
 */
class ExternalStoreMwstore extends ExternalStoreMedium {
	/**
	 * The URL returned is of the form of the form mwstore://backend/container/wiki/id
	 *
	 * @see ExternalStoreMedium::fetchFromURL()
	 */
	public function fetchFromURL( $url ) {
		$be = FileBackendGroup::singleton()->backendFromPath( $url );
		if ( $be instanceof FileBackend ) {
			// We don't need "latest" since objects are immutable and
			// backends should at least have "read-after-create" consistency.
			return $be->getFileContents( array( 'src' => $url ) );
		}

		return false;
	}

	/**
	 * Fetch data from given external store URLs.
	 * The URL returned is of the form of the form mwstore://backend/container/wiki/id
	 *
	 * @param array $urls An array of external store URLs
	 * @return array A map from url to stored content. Failed results are not represented.
	 */
	public function batchFetchFromURLs( array $urls ) {
		$pathsByBackend = array();
		foreach ( $urls as $url ) {
			$be = FileBackendGroup::singleton()->backendFromPath( $url );
			if ( $be instanceof FileBackend ) {
				$pathsByBackend[$be->getName()][] = $url;
			}
		}
		$blobs = array();
		foreach ( $pathsByBackend as $backendName => $paths ) {
			$be = FileBackendGroup::singleton()->get( $backendName );
			$blobs = $blobs + $be->getFileContentsMulti( array( 'srcs' => $paths ) );
		}

		return $blobs;
	}

	/**
	 * @see ExternalStoreMedium::store()
	 */
	public function store( $backend, $data ) {
		$be = FileBackendGroup::singleton()->get( $backend );
		if ( $be instanceof FileBackend ) {
			// Get three random base 36 characters to act as shard directories
			$rand = wfBaseConvert( mt_rand( 0, 46655 ), 10, 36, 3 );
			// Make sure ID is roughly lexicographically increasing for performance
			$id = str_pad( UIDGenerator::newTimestampedUID128( 32 ), 26, '0', STR_PAD_LEFT );
			// Segregate items by wiki ID for the sake of bookkeeping
			$wiki = isset( $this->params['wiki'] ) ? $this->params['wiki'] : wfWikiID();

			$url = $be->getContainerStoragePath( 'data' ) . '/' . rawurlencode( $wiki );
			$url .= ( $be instanceof FSFileBackend )
				? "/{$rand[0]}/{$rand[1]}/{$rand[2]}/{$id}" // keep directories small
				: "/{$rand[0]}/{$rand[1]}/{$id}"; // container sharding is only 2-levels

			$be->prepare( array( 'dir' => dirname( $url ), 'noAccess' => 1, 'noListing' => 1 ) );
			if ( $be->create( array( 'dst' => $url, 'content' => $data ) )->isOK() ) {
				return $url;
			}
		}

		return false;
	}
}
