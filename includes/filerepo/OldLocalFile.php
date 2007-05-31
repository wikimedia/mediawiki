<?php

/**
 * Class to represent a file in the oldimage table
 *
 * @addtogroup FileRepo
 */
class OldLocalFile extends LocalFile {
	var $requestedTime, $archive_name;

	const CACHE_VERSION = 1;
	const MAX_CACHE_ROWS = 20;

	function newFromTitle( $title, $repo, $time ) {
		return new self( $title, $repo, $time, null );
	}

	function newFromArchiveName( $title, $repo, $archiveName ) {
		return new self( $title, $repo, null, $archiveName );
	}

	function newFromRow( $row, $repo ) {
		$title = Title::makeTitle( NS_IMAGE, $row->oi_name );
		$file = new self( $title, $repo, null, $row->oi_archive_name );
		$file->loadFromRow( $row, 'oi_' );
		return $file;
	}

	/**
	 * @param Title $title
	 * @param FileRepo $repo
	 * @param string $time Timestamp or null to load by archive name
	 * @param string $archiveName Archive name or null to load by timestamp
	 */
	function __construct( $title, $repo, $time, $archiveName ) {
		parent::__construct( $title, $repo );
		$this->requestedTime = $time;
		$this->archive_name = $archiveName;
		if ( is_null( $time ) && is_null( $archiveName ) ) {
			throw new MWException( __METHOD__.': must specify at least one of $time or $archiveName' );
		}
	}

	function getCacheKey() {
		$hashedName = md5($this->getName());
		return wfMemcKey( 'oldfile', $hashedName );
	}

	function getArchiveName() {
		if ( !isset( $this->archive_name ) ) {
			$this->load();
		}
		return $this->archive_name;
	}

	function isOld() {
		return true;
	}

	/**
	 * Try to load file metadata from memcached. Returns true on success.
	 */
	function loadFromCache() {
		global $wgMemc;
		wfProfileIn( __METHOD__ );
		$this->dataLoaded = false;
		$key = $this->getCacheKey();
		if ( !$key ) {
			return false;
		}
		$oldImages = $wgMemc->get( $key );

		if ( isset( $oldImages['version'] ) && $oldImages['version'] == MW_OLDFILE_VERSION ) {
			unset( $oldImages['version'] );
			$more = isset( $oldImages['more'] );
			unset( $oldImages['more'] );
			$found = false;
			if ( is_null( $this->requestedTime ) ) {
				foreach ( $oldImages as $timestamp => $info ) {
					if ( $info['archive_name'] == $this->archive_name ) {
						$found = true;
						break;
					}
				}
			} else {
				krsort( $oldImages );
				foreach ( $oldImages as $timestamp => $info ) {
					if ( $timestamp <= $this->requestedTime ) {
						$found = true;
						break;
					}
				}
			}
			if ( $found ) {
				wfDebug( "Pulling file metadata from cache key {$key}[{$timestamp}]\n" );
				$this->dataLoaded = true;
				foreach ( $cachedValues as $name => $value ) {
					$this->$name = $value;
				}
			} elseif ( $more ) {
				wfDebug( "Cache key was truncated, oldimage row might be found in the database\n" );
			} else {
				wfDebug( "Image did not exist at the specified time.\n" );
				$this->fileExists = false;
				$this->dataLoaded = true;
			}
		}

		if ( $this->dataLoaded ) {
			wfIncrStats( 'image_cache_hit' );
		} else {
			wfIncrStats( 'image_cache_miss' );
		}

		wfProfileOut( __METHOD__ );
		return $this->dataLoaded;
	}

	function saveToCache() {
		// Cache the entire history of the image (up to MAX_CACHE_ROWS).
		// This is expensive, so we only do it if $wgMemc is real
		global $wgMemc;
		if ( $wgMemc instanceof FakeMemcachedClient ) {
			return;
		}
		$key = $this->getCacheKey();
		if ( !$key ) { 
			return;
		}
		wfProfileIn( __METHOD__ );

		$dbr = $this->repo->getSlaveDB();
		$res = $dbr->select( 'oldimage', $this->getCacheFields(),
			array( 'oi_name' => $this->getName() ), __METHOD__, 
			array( 
				'LIMIT' => self::MAX_CACHE_ROWS + 1,
				'ORDER BY' => 'oi_timestamp DESC',
			));
		$cache = array( 'version' => self::CACHE_VERSION );
		$numRows = $dbr->numRows( $res );
		if ( $numRows > self::MAX_CACHE_ROWS ) {
			$cache['more'] = true;
			$numRows--;
		}
		for ( $i = 0; $i < $numRows; $i++ ) {
			$row = $dbr->fetchObject( $res );
			$this->decodeRow( $row, 'oi_' );
			$cache[$row->oi_timestamp] = $row;
		}
		$dbr->freeResult( $res );
		$wgMemc->set( $key, $cache, 7*86400 /* 1 week */ );
		wfProfileOut( __METHOD__ );
	}

	function loadFromDB() {
		wfProfileIn( __METHOD__ );
		$dbr = $this->repo->getSlaveDB();
		$conds = array( 'oi_name' => $this->getName() );
		if ( is_null( $this->requestedTime ) ) {
			$conds['oi_archive_name'] = $this->archive_name;
		} else {
			$conds[] = 'oi_timestamp <= ' . $dbr->addQuotes( $this->requestedTime );
		}
		$row = $dbr->selectRow( 'oldimage', $this->getCacheFields( 'oi_' ),
			$conds, __METHOD__, array( 'ORDER BY' => 'oi_timestamp DESC' ) );
		if ( $row ) {
			$this->loadFromRow( $row, 'oi_' );
		} else {
			$this->fileExists = false;
		}
		$this->dataLoaded = true;
	}

	function getCacheFields( $prefix = 'img_' ) {
		$fields = parent::getCacheFields( $prefix );
		$fields[] = $prefix . 'archive_name';

		// XXX: Temporary hack before schema update
		$fields = array_diff( $fields, array( 
			'oi_media_type', 'oi_major_mime', 'oi_minor_mime', 'oi_metadata' ) );
		return $fields;
	}

	function getRel() {
		return 'archive/' . $this->getHashPath() . $this->getArchiveName();
	}

	function getUrlRel() {
		return 'archive/' . $this->getHashPath() . urlencode( $this->getArchiveName() );
	}
	
	function upgradeRow() {
		wfProfileIn( __METHOD__ );

		$this->loadFromFile();

		$dbw = $this->repo->getMasterDB();
		list( $major, $minor ) = self::splitMime( $this->mime );

		wfDebug(__METHOD__.': upgrading '.$this->archive_name." to the current schema\n");
		$dbw->update( 'oldimage',
			array(
				'oi_width' => $this->width,
				'oi_height' => $this->height,
				'oi_bits' => $this->bits,
				#'oi_media_type' => $this->media_type,
				#'oi_major_mime' => $major,
				#'oi_minor_mime' => $minor,
				#'oi_metadata' => $this->metadata,
			), array( 'oi_name' => $this->getName(), 'oi_timestamp' => $this->requestedTime ),
			__METHOD__
		);
		wfProfileOut( __METHOD__ );
	}

	// XXX: Temporary hack before schema update
	function maybeUpgradeRow() {}

}


?>
