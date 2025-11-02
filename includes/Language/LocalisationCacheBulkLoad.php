<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

/**
 * LocalisationCache optimised for loading many languages at once.
 *
 * Used by maintenance/rebuildLocalisationCache.php.
 *
 * @ingroup Language
 */
class LocalisationCacheBulkLoad extends LocalisationCache {

	/**
	 * A cache for the contents of the data files.
	 * Core files are serialized to avoid using ~1GB of RAM during a re-cache.
	 * @var string[][]
	 */
	private $fileCache = [];

	/**
	 * Most recently used languages. Uses the linked-list aspect of PHP hashtables
	 * to keep the most recently used language codes at the end of the array, and
	 * the language codes that are ready to be deleted at the beginning.
	 * @var array<string,true>
	 */
	private $mruLangs = [];

	/**
	 * Maximum number of languages that may be loaded into $this->data
	 * @var int
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
			$code = array_key_first( $this->mruLangs );
			wfDebug( __METHOD__ . ": unloading $code" );
			$this->unload( $code );
		}
	}

}
