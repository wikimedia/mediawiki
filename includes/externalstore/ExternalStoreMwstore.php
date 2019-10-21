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
 * File backend accessible external objects.
 *
 * In this system, each store "location" maps to the name of a file backend.
 * The file backends must be defined in $wgFileBackends and must be global
 * and fully qualified with a global "wikiId" prefix in the configuration.
 *
 * @ingroup ExternalStorage
 * @since 1.21
 */
class ExternalStoreMwstore extends ExternalStoreMedium {
	/** @var FileBackendGroup */
	private $fbGroup;

	/**
	 * @see ExternalStoreMedium::__construct()
	 * @param array $params Additional parameters include:
	 *   - fbGroup: a FileBackendGroup instance
	 */
	public function __construct( array $params ) {
		parent::__construct( $params );
		if ( !isset( $params['fbGroup'] ) || !( $params['fbGroup'] instanceof FileBackendGroup ) ) {
			throw new InvalidArgumentException( "FileBackendGroup required in 'fbGroup' field." );
		}
		$this->fbGroup = $params['fbGroup'];
	}

	/**
	 * The URL returned is of the form of the form mwstore://backend/container/wiki/id
	 *
	 * @see ExternalStoreMedium::fetchFromURL()
	 * @param string $url
	 * @return bool
	 */
	public function fetchFromURL( $url ) {
		$be = $this->fbGroup->backendFromPath( $url );
		if ( $be instanceof FileBackend ) {
			// We don't need "latest" since objects are immutable and
			// backends should at least have "read-after-create" consistency.
			return $be->getFileContents( [ 'src' => $url ] );
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
		$pathsByBackend = [];
		foreach ( $urls as $url ) {
			$be = $this->fbGroup->backendFromPath( $url );
			if ( $be instanceof FileBackend ) {
				$pathsByBackend[$be->getName()][] = $url;
			}
		}
		$blobs = [];
		foreach ( $pathsByBackend as $backendName => $paths ) {
			$be = $this->fbGroup->get( $backendName );
			$blobs += $be->getFileContentsMulti( [ 'srcs' => $paths ] );
		}

		return $blobs;
	}

	/**
	 * @inheritDoc
	 */
	public function store( $backend, $data ) {
		$be = $this->fbGroup->get( $backend );
		// Get three random base 36 characters to act as shard directories
		$rand = Wikimedia\base_convert( mt_rand( 0, 46655 ), 10, 36, 3 );
		// Make sure ID is roughly lexicographically increasing for performance
		$id = str_pad( UIDGenerator::newTimestampedUID128( 32 ), 26, '0', STR_PAD_LEFT );
		// Segregate items by DB domain ID for the sake of bookkeeping
		$domain = $this->isDbDomainExplicit
			? $this->dbDomain
			// @FIXME: this does not include the schema for b/c but it ideally should
			: WikiMap::getWikiIdFromDbDomain( $this->dbDomain );
		$url = $be->getContainerStoragePath( 'data' ) . '/' . rawurlencode( $domain );
		// Use directory/container sharding
		$url .= ( $be instanceof FSFileBackend )
			? "/{$rand[0]}/{$rand[1]}/{$rand[2]}/{$id}" // keep directories small
			: "/{$rand[0]}/{$rand[1]}/{$id}"; // container sharding is only 2-levels

		$be->prepare( [ 'dir' => dirname( $url ), 'noAccess' => 1, 'noListing' => 1 ] );
		$status = $be->create( [ 'dst' => $url, 'content' => $data ] );

		if ( $status->isOK() ) {
			return $url;
		}

		throw new MWException( __METHOD__ . ": operation failed: $status" );
	}

	public function isReadOnly( $backend ) {
		if ( parent::isReadOnly( $backend ) ) {
			return true;
		}

		$be = $this->fbGroup->get( $backend );

		return $be ? $be->isReadOnly() : false;
	}
}
