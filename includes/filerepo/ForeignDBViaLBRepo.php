<?php
/**
 * A foreign repository with a MediaWiki database accessible via the configured LBFactory.
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
 * @ingroup FileRepo
 */

/**
 * A foreign repository with a MediaWiki database accessible via the configured LBFactory
 *
 * @ingroup FileRepo
 */
class ForeignDBViaLBRepo extends LocalRepo {
	var $wiki, $dbName, $tablePrefix;
	var $fileFactory = array( 'ForeignDBFile', 'newFromTitle' );
	var $fileFromRowFactory = array( 'ForeignDBFile', 'newFromRow' );

	/**
	 * @param $info array|null
	 */
	function __construct( $info ) {
		parent::__construct( $info );
		$this->wiki = $info['wiki'];
		list( $this->dbName, $this->tablePrefix ) = wfSplitWikiID( $this->wiki );
		$this->hasSharedCache = $info['hasSharedCache'];
	}

	/**
	 * @return DatabaseBase
	 */
	function getMasterDB() {
		return wfGetDB( DB_MASTER, array(), $this->wiki );
	}

	/**
	 * @return DatabaseBase
	 */
	function getSlaveDB() {
		return wfGetDB( DB_SLAVE, array(), $this->wiki );
	}

	function hasSharedCache() {
		return $this->hasSharedCache;
	}

	/**
	 * Get a key on the primary cache for this repository.
	 * Returns false if the repository's cache is not accessible at this site.
	 * The parameters are the parts of the key, as for wfMemcKey().
	 * @return bool|string
	 */
	function getSharedCacheKey( /*...*/ ) {
		if ( $this->hasSharedCache() ) {
			$args = func_get_args();
			array_unshift( $args, $this->wiki );
			return implode( ':', $args );
		} else {
			return false;
		}
	}

	protected function assertWritableRepo() {
		throw new MWException( get_class( $this ) . ': write operations are not supported.' );
	}
}
