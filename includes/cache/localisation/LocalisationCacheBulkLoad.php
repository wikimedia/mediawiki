<?php
/**
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
 * A localisation cache optimised for loading large amounts of data for many
 * languages. Used by rebuildLocalisationCache.php.
 */
class LocalisationCacheBulkLoad extends LocalisationCache {

	/**
	 * A cache of the contents of data files.
	 * Core files are serialized to avoid using ~1GB of RAM during a recache.
	 */
	private $fileCache = [];

	/**
	 * Most recently used languages. Uses the linked-list aspect of PHP hashtables
	 * to keep the most recently used language codes at the end of the array, and
	 * the language codes that are ready to be deleted at the beginning.
	 */
	private $mruLangs = [];

	/**
	 * Maximum number of languages that may be loaded into $this->data
	 */
	private $maxLoadedLangs = 10;

	/**
	 * @param string $fileName
	 * @param string $fileType
	 * @return array|mixed
	 */
	protected function readPHPFile( $fileName, $fileType ) {
		$serialize = $fileType === 'core';
		if ( !isset( $this->fileCache[$fileName][$fileType] ) ) {
			$data = parent::readPHPFile( $fileName, $fileType );

			if ( $serialize ) {
				$encData = serialize( $data );
			} else {
				$encData = $data;
			}

			$this->fileCache[$fileName][$fileType] = $encData;

			return $data;
		} elseif ( $serialize ) {
			return unserialize( $this->fileCache[$fileName][$fileType] );
		} else {
			return $this->fileCache[$fileName][$fileType];
		}
	}

	/**
	 * @param string $code
	 * @param string $key
	 * @return mixed
	 */
	public function getItem( $code, $key ) {
		unset( $this->mruLangs[$code] );
		$this->mruLangs[$code] = true;

		return parent::getItem( $code, $key );
	}

	/**
	 * @param string $code
	 * @param string $key
	 * @param string $subkey
	 * @return mixed
	 */
	public function getSubitem( $code, $key, $subkey ) {
		unset( $this->mruLangs[$code] );
		$this->mruLangs[$code] = true;

		return parent::getSubitem( $code, $key, $subkey );
	}

	/**
	 * @param string $code
	 */
	public function recache( $code ) {
		parent::recache( $code );
		unset( $this->mruLangs[$code] );
		$this->mruLangs[$code] = true;
		$this->trimCache();
	}

	/**
	 * @param string $code
	 */
	public function unload( $code ) {
		unset( $this->mruLangs[$code] );
		parent::unload( $code );
	}

	/**
	 * Unload cached languages until there are less than $this->maxLoadedLangs
	 */
	protected function trimCache() {
		while ( count( $this->data ) > $this->maxLoadedLangs && count( $this->mruLangs ) ) {
			reset( $this->mruLangs );
			$code = key( $this->mruLangs );
			wfDebug( __METHOD__ . ": unloading $code\n" );
			$this->unload( $code );
		}
	}

}
