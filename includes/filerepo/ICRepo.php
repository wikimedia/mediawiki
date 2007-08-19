<?php

/**
 * A repository for files accessible via InstantCommons. 
 */

class ICRepo extends LocalRepo {
	var $directory, $url, $hashLevels, $cache;	
	var $fileFactory = array( 'ICFile', 'newFromTitle' );
	var $oldFileFactory = false;

	function __construct( $info ) {
		parent::__construct( $info );		
		// Required settings
		$this->directory = $info['directory'];
		$this->url = $info['url'];
		$this->hashLevels = $info['hashLevels'];
		if(isset($info['cache'])){
			$this->cache = getcwd().'/images/'.$info['cache'];
		}		
	}		
}

/**
 * A file loaded from InstantCommons
 */
class ICFile extends LocalFile{
	static function newFromTitle($title,$repo){
		return new self($title, $repo);		
	}
	
	/**
	 * Returns true if the file comes from the local file repository.
	 *
	 * @return bool
	 */
	function isLocal() { 
		return true; 
	}
		
	function load(){
		if (!$this->dataLoaded ) {
			if ( !$this->loadFromCache() ) {
				if(!$this->loadFromDB()){
					$this->loadFromIC();
				}				
				$this->saveToCache(); 
			}
			$this->dataLoaded = true;
		}		
	}
	
	/**
	 * Load file metadata from the DB
	 */
	function loadFromDB() {
		wfProfileIn( __METHOD__ );

		# Unconditionally set loaded=true, we don't want the accessors constantly rechecking
		$this->dataLoaded = true;

		$dbr = $this->repo->getSlaveDB();

		$row = $dbr->selectRow( 'ic_image', $this->getCacheFields( 'img_' ),
			array( 'img_name' => $this->getName() ), __METHOD__ ); 
		if ( $row ) {
			if (trim($row->img_media_type)==NULL) {
				$this->upgradeRow();
				$this->upgraded = true;
			} 
			$this->loadFromRow( $row );
			//This means that these files are local so the repository locations are local
			$this->setUrlPathLocal();			
			$this->fileExists = true;
			//var_dump($this); exit;
		} else {
			$this->fileExists = false;
		}

		wfProfileOut( __METHOD__ );
		
		return $this->fileExists;
	}
	
		/**
	 * Fix assorted version-related problems with the image row by reloading it from the file
	 */
	function upgradeRow() {
		wfProfileIn( __METHOD__ );

		$this->loadFromIC();

		$dbw = $this->repo->getMasterDB();
		list( $major, $minor ) = self::splitMime( $this->mime );

		wfDebug(__METHOD__.': upgrading '.$this->getName()." to the current schema\n");

		$dbw->update( 'ic_image',
			array(
				'img_width' => $this->width,
				'img_height' => $this->height,
				'img_bits' => $this->bits,
				'img_media_type' => $this->type,
				'img_major_mime' => $major,
				'img_minor_mime' => $minor,
				'img_metadata' => $this->metadata,
			), array( 'img_name' => $this->getName() ),
			__METHOD__
		);
		$this->saveToCache();
		wfProfileOut( __METHOD__ );
	}
	
	function exists(){
		$this->load();
		return $this->fileExists;
		
	}
	
	/**
	 * Fetch the file from the repository. Check local ic_images table first. If not available, check remote server
	 */	 
	 function loadFromIC(){
	 	# Unconditionally set loaded=true, we don't want the accessors constantly rechecking
		$this->dataLoaded = true;
		$icUrl = $this->repo->directory.'&media='.$this->title->mDbkeyform; 
		if($h = @fopen($icUrl, 'rb')){		 	
		 	$contents = fread($h, 3000);
		 	$image = $this->api_xml_to_array($contents);
		 	if($image['fileExists']){
		 		foreach($image as $property=>$value){
		 			if($property=="url"){$value=$this->repo->url.$value; }	 			
		 			$this->$property = $value;
		 		}  
				 if($this->curl_file_get_contents($this->repo->url.$image['url'], $this->repo->cache.'/'.$image['name'])){
				 	//Record the image	
				 	$this->recordDownload("Downloaded with InstantCommons");
	            	
				 	//Then cache it			 			 	
				 }else{//set fileExists back to false			 	
				 	$this->fileExists = false;
				 }			 
		 	}
		}
	 }
	 
	
	 function setUrlPathLocal(){
	 	global $wgScriptPath;
	 	$path = $wgScriptPath.'/'.substr($this->repo->cache, strlen($wgScriptPath));
	 	$this->repo->url = $path;//.'/'.rawurlencode($this->title->mDbkeyform);		
		$this->repo->directory = $this->repo->cache;//.'/'.rawurlencode($this->title->mDbkeyform);
	 	
	 }
	
	 function getThumbPath( $suffix=false ){
	 	$path = $this->repo->cache;
	 	if ( $suffix !== false ) {
			$path .= '/thumb/' . rawurlencode( $suffix );
		}
		return $path;
	 }
	 function getThumbUrl( $suffix=false ){
	 	global $wgScriptPath;
	 	$path = $wgScriptPath.'/'.substr($this->repo->cache, strlen($wgScriptPath));
	 	if ( $suffix !== false ) {
			$path .= '/thumb/' . rawurlencode( $suffix );
		}
		return $path;
	 }
	
	 /**
	  * Convert the InstantCommons Server API XML Response to an associative array
	  */
	  function api_xml_to_array($xml){
		 preg_match("/<instantcommons><image(.*?)<\/instantcommons>/",$xml,$match);
		 preg_match_all("/(.*?=\".*?\")/",$match[1], $matches);
		 foreach($matches[1] as $match){
		 	list($key,$value) = split("=",$match);
		 	$image[trim($key,'<" ')]=trim($value,' "');
		 }	
		 return $image;
	  }
	  
	/**
     * Use cURL to read the content of a URL into a string
     * ref: http://groups-beta.google.com/group/comp.lang.php/browse_thread/thread/8efbbaced3c45e3c/d63c7891cf8e380b?lnk=raot
     * @param string $url - the URL to fetch
     * @param resource $fp - filename to write file contents to
     * @param boolean $bg - call cURL in the background (don't hang page until complete)
     * @param int $timeout - cURL connect timeout
     */
    function curl_file_get_contents($url, $fp, $bg=TRUE, $timeout = 1) {
        {
        	# Call curl in the background to download the file
	        $cmd = 'curl '.wfEscapeShellArg($url).' -o '.$fp.' &';
	        wfDebug('Curl download initiated='.$cmd );
	        $success = false;  
        	$file_contents = array();        	         
            $file_contents['err'] = wfShellExec($cmd, $file_contents['return']);
            if($file_contents['err']==0){//Success
            	$success = true;
            }                
        }
       return $success;                
    }
	
	function getMasterDB() {
		if ( !isset( $this->dbConn ) ) {
			$class = 'Database' . ucfirst( $this->dbType );
			$this->dbConn = new $class( $this->dbServer, $this->dbUser, 
				$this->dbPassword, $this->dbName, false, $this->dbFlags, 
				$this->tablePrefix );
		}
		return $this->dbConn;
	}

	/**
	 * Record a file upload in the upload log and the image table
	 */
	private function recordDownload($comment='', $timestamp = false ){
		global $wgUser; 

		$dbw = $this->repo->getMasterDB();
		
		if ( $timestamp === false ) {
			$timestamp = $dbw->timestamp();
		}
		list( $major, $minor ) = self::splitMime( $this->mime );

		# Test to see if the row exists using INSERT IGNORE
		# This avoids race conditions by locking the row until the commit, and also
		# doesn't deadlock. SELECT FOR UPDATE causes a deadlock for every race condition.
		$dbw->insert( 'ic_image',
			array(
				'img_name' => $this->getName(),
				'img_size'=> $this->size,
				'img_width' => intval( $this->width ),
				'img_height' => intval( $this->height ),
				'img_bits' => $this->bits,
				'img_media_type' => $this->type,
				'img_major_mime' => $major,
				'img_minor_mime' => $minor,
				'img_timestamp' => $timestamp,
				'img_description' => $comment,
				'img_user' => $wgUser->getID(),
				'img_user_text' => $wgUser->getName(),
				'img_metadata' => $this->metadata,
			),
			__METHOD__,
			'IGNORE'
		);

		if( $dbw->affectedRows() == 0 ) {
			# Collision, this is an update of a file			
			# Update the current image row
			$dbw->update( 'ic_image',
				array( /* SET */
					'img_size' => $this->size,
					'img_width' => intval( $this->width ),
					'img_height' => intval( $this->height ),
					'img_bits' => $this->bits,
					'img_media_type' => $this->media_type,
					'img_major_mime' => $this->major_mime,
					'img_minor_mime' => $this->minor_mime,
					'img_timestamp' => $timestamp,
					'img_description' => $comment,
					'img_user' => $wgUser->getID(),
					'img_user_text' => $wgUser->getName(),
					'img_metadata' => $this->metadata,
				), array( /* WHERE */
					'img_name' => $this->getName()
				), __METHOD__
			);
		} else {
			# This is a new file
			# Update the image count
			$site_stats = $dbw->tableName( 'site_stats' );
			$dbw->query( "UPDATE $site_stats SET ss_images=ss_images+1", __METHOD__ );
		}

		$descTitle = $this->getTitle();
		$article = new Article( $descTitle );

		# Add the log entry
		$log = new LogPage( 'icdownload' );
		$log->addEntry( 'InstantCommons download', $descTitle, $comment );

		if( $descTitle->exists() ) {
			# Create a null revision
			$nullRevision = Revision::newNullRevision( $dbw, $descTitle->getArticleId(), $log->getRcComment(), false );
			$nullRevision->insertOn( $dbw );
			$article->updateRevisionOn( $dbw, $nullRevision );

			# Invalidate the cache for the description page
			$descTitle->invalidateCache();
			$descTitle->purgeSquid();
		}

		
		# Commit the transaction now, in case something goes wrong later
		# The most important thing is that files don't get lost, especially archives
		$dbw->immediateCommit();

		# Invalidate cache for all pages using this file
		$update = new HTMLCacheUpdate( $this->getTitle(), 'imagelinks' );
		$update->doUpdate();

		return true;
	}
	
}

