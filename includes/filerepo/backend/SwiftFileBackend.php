<?php
/**
 * @file
 * @ingroup FileBackend
 * @author Russ Nelson
 * @author Aaron Schulz
 */

/**
 * Class for an OpenStack Swift based file backend.
 *
 * This requires that the php-cloudfiles library is present,
 * which is available at https://github.com/rackspace/php-cloudfiles.
 * All of the library classes must be registed in $wgAutoloadClasses.
 *
 * Status messages should avoid mentioning the Swift account name
 * Likewise, error suppression should be used to avoid path disclosure.
 *
 * @ingroup FileBackend
 * @since 1.19
 */
class SwiftFileBackend extends FileBackend {
	/** @var CF_Authentication */
	protected $auth; // Swift authentication handler

	/** @var CF_Connection */
	protected $conn; // Swift connection handle
	protected $connStarted = 0; // integer UNIX timestamp
	protected $connContainers = array(); // container object cache
	protected $connTTL = 120; // integer seconds

	protected $swiftProxyUser; // string

	/**
	 * @see FileBackend::__construct()
	 * Additional $config params include:
	 *    swiftAuthUrl       : Swift authentication server URL
	 *    swiftUser          : Swift user used by MediaWiki
	 *    swiftKey           : Swift authentication key for the above user
	 *    swiftProxyUser     : Swift user used for end-user hits to proxy server
	 *    shardViaHashLevels : Map of container names to the number of hash levels
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );
		// Required settings
		$this->auth = new CF_Authentication(
			$config['swiftUser'], $config['swiftKey'], null, $config['swiftAuthUrl'] );
		// Optional settings
		$this->connTTL = isset( $config['connTTL'] )
			? $config['connTTL']
			: 60; // some sane number
		$this->swiftProxyUser = isset( $config['swiftProxyUser'] )
			? $config['swiftProxyUser']
			: '';
		$this->shardViaHashLevels = isset( $config['shardViaHashLevels'] )
			? $config['shardViaHashLevels']
			: '';
	}

	/**
	 * @see FileBackend::resolveContainerPath()
	 */
	protected function resolveContainerPath( $container, $relStoragePath ) {
		if ( strlen( urlencode( $relStoragePath ) ) > 1024 ) {
			return null; // too long for Swift
		}
		return $relStoragePath;
	}

	/**
	 * @see FileBackend::doCopyInternal()
	 */
	protected function doCreateInternal( array $params ) {
		$status = Status::newGood();

		list( $dstCont, $destRel ) = $this->resolveStoragePathReal( $params['dst'] );
		if ( $destRel === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );
			return $status;
		}

		// (a) Check the destination container
		try {
			$dContObj = $this->getContainer( $dstCont );
		} catch ( NoSuchContainerException $e ) {
			$status->fatal( 'backend-fail-create', $params['dst'] );
			return $status;
		} catch ( InvalidResponseException $e ) {
			$status->fatal( 'backend-fail-connect', $this->name );
			return $status;
		} catch ( Exception $e ) { // some other exception?
			$status->fatal( 'backend-fail-internal', $this->name );
			$this->logException( $e, __METHOD__, $params );
			return $status;
		}

		// (b) Check if the destination object already exists
		try {
			$dContObj->get_object( $destRel ); // throws NoSuchObjectException
			// NoSuchObjectException not thrown: file must exist
			if ( empty( $params['overwriteDest'] ) ) {
				$status->fatal( 'backend-fail-alreadyexists', $params['dst'] );
				return $status;
			}
		} catch ( NoSuchObjectException $e ) {
			// NoSuchObjectException thrown: file does not exist
		} catch ( InvalidResponseException $e ) {
			$status->fatal( 'backend-fail-connect', $this->name );
			return $status;
		} catch ( Exception $e ) { // some other exception?
			$status->fatal( 'backend-fail-internal', $this->name );
			$this->logException( $e, __METHOD__, $params );
			return $status;
		}

		// (c) Get a SHA-1 hash of the object
		$sha1Hash = wfBaseConvert( sha1( $params['content'] ), 16, 36, 31 );

		// (d) Actually create the object
		try {
			$obj = $dContObj->create_object( $destRel );
			// Note: metadata keys stored as [Upper case char][[Lower case char]...]
			$obj->metadata = array( 'Sha1base36' => $sha1Hash );
			$obj->write( $params['content'] );
		} catch ( BadContentTypeException $e ) {
			$status->fatal( 'backend-fail-contenttype', $params['dst'] );
		} catch ( InvalidResponseException $e ) {
			$status->fatal( 'backend-fail-connect', $this->name );
		} catch ( Exception $e ) { // some other exception?
			$status->fatal( 'backend-fail-internal', $this->name );
			$this->logException( $e, __METHOD__, $params );
		}

		return $status;
	}

	/**
	 * @see FileBackend::doStoreInternal()
	 */
	protected function doStoreInternal( array $params ) {
		$status = Status::newGood();

		list( $dstCont, $destRel ) = $this->resolveStoragePathReal( $params['dst'] );
		if ( $destRel === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );
			return $status;
		}

		// (a) Check the destination container
		try {
			$dContObj = $this->getContainer( $dstCont );
		} catch ( NoSuchContainerException $e ) {
			$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
			return $status;
		} catch ( InvalidResponseException $e ) {
			$status->fatal( 'backend-fail-connect', $this->name );
			return $status;
		} catch ( Exception $e ) { // some other exception?
			$status->fatal( 'backend-fail-internal', $this->name );
			$this->logException( $e, __METHOD__, $params );
			return $status;
		}

		// (b) Check if the destination object already exists
		try {
			$dContObj->get_object( $destRel ); // throws NoSuchObjectException
			// NoSuchObjectException not thrown: file must exist
			if ( empty( $params['overwriteDest'] ) ) {
				$status->fatal( 'backend-fail-alreadyexists', $params['dst'] );
				return $status;
			}
		} catch ( NoSuchObjectException $e ) {
			// NoSuchObjectException thrown: file does not exist
		} catch ( InvalidResponseException $e ) {
			$status->fatal( 'backend-fail-connect', $this->name );
			return $status;
		} catch ( Exception $e ) { // some other exception?
			$status->fatal( 'backend-fail-internal', $this->name );
			$this->logException( $e, __METHOD__, $params );
			return $status;
		}

		// (c) Get a SHA-1 hash of the object
		$sha1Hash = sha1_file( $params['src'] );
		if ( $sha1Hash === false ) { // source doesn't exist?
			$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
			return $status;
		}
		$sha1Hash = wfBaseConvert( $sha1Hash, 16, 36, 31 );

		// (d) Actually store the object
		try {
			$obj = $dContObj->create_object( $destRel );
			// Note: metadata keys stored as [Upper case char][[Lower case char]...]
			$obj->metadata = array( 'Sha1base36' => $sha1Hash );
			$obj->load_from_filename( $params['src'], True ); // calls $obj->write()
		} catch ( BadContentTypeException $e ) {
			$status->fatal( 'backend-fail-contenttype', $params['dst'] );
		} catch ( IOException $e ) {
			$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
		} catch ( InvalidResponseException $e ) {
			$status->fatal( 'backend-fail-connect', $this->name );
		} catch ( Exception $e ) { // some other exception?
			$status->fatal( 'backend-fail-internal', $this->name );
			$this->logException( $e, __METHOD__, $params );
		}

		return $status;
	}

	/**
	 * @see FileBackend::doCopyInternal()
	 */
	protected function doCopyInternal( array $params ) {
		$status = Status::newGood();

		list( $srcCont, $srcRel ) = $this->resolveStoragePathReal( $params['src'] );
		if ( $srcRel === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['src'] );
			return $status;
		}

		list( $dstCont, $destRel ) = $this->resolveStoragePathReal( $params['dst'] );
		if ( $destRel === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );
			return $status;
		}

		// (a) Check the source and destination containers
		try {
			$sContObj = $this->getContainer( $srcCont );
			$dContObj = $this->getContainer( $dstCont );
		} catch ( NoSuchContainerException $e ) {
			$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
			return $status;
		} catch ( InvalidResponseException $e ) {
			$status->fatal( 'backend-fail-connect', $this->name );
			return $status;
		} catch ( Exception $e ) { // some other exception?
			$status->fatal( 'backend-fail-internal', $this->name );
			$this->logException( $e, __METHOD__, $params );
			return $status;
		}

		// (b) Check if the destination object already exists
		try {
			$dContObj->get_object( $destRel ); // throws NoSuchObjectException
			// NoSuchObjectException not thrown: file must exist
			if ( empty( $params['overwriteDest'] ) ) {
				$status->fatal( 'backend-fail-alreadyexists', $params['dst'] );
				return $status;
			}
		} catch ( NoSuchObjectException $e ) {
			// NoSuchObjectException thrown: file does not exist
		} catch ( InvalidResponseException $e ) {
			$status->fatal( 'backend-fail-connect', $this->name );
			return $status;
		} catch ( Exception $e ) { // some other exception?
			$status->fatal( 'backend-fail-internal', $this->name );
			$this->logException( $e, __METHOD__, $params );
			return $status;
		}

		// (c) Actually copy the file to the destination
		try {
			$sContObj->copy_object_to( $srcRel, $dContObj, $destRel );
		} catch ( NoSuchObjectException $e ) { // source object does not exist
			$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
		} catch ( InvalidResponseException $e ) {
			$status->fatal( 'backend-fail-connect', $this->name );
		} catch ( Exception $e ) { // some other exception?
			$status->fatal( 'backend-fail-internal', $this->name );
			$this->logException( $e, __METHOD__, $params );
		}

		return $status;
	}

	/**
	 * @see FileBackend::doDeleteInternal()
	 */
	protected function doDeleteInternal( array $params ) {
		$status = Status::newGood();

		list( $srcCont, $srcRel ) = $this->resolveStoragePathReal( $params['src'] );
		if ( $srcRel === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['src'] );
			return $status;
		}

		// (a) Check the source container
		try {
			$sContObj = $this->getContainer( $srcCont );
		} catch ( NoSuchContainerException $e ) {
			$status->fatal( 'backend-fail-delete', $params['src'] );
			return $status;
		} catch ( InvalidResponseException $e ) {
			$status->fatal( 'backend-fail-connect', $this->name );
			return $status;
		} catch ( Exception $e ) { // some other exception?
			$status->fatal( 'backend-fail-internal', $this->name );
			$this->logException( $e, __METHOD__, $params );
			return $status;
		}

		// (b) Actually delete the object
		try {
			$sContObj->delete_object( $srcRel );
		} catch ( NoSuchObjectException $e ) {
			if ( empty( $params['ignoreMissingSource'] ) ) {
				$status->fatal( 'backend-fail-delete', $params['src'] );
			}
		} catch ( InvalidResponseException $e ) {
			$status->fatal( 'backend-fail-connect', $this->name );
		} catch ( Exception $e ) { // some other exception?
			$status->fatal( 'backend-fail-internal', $this->name );
			$this->logException( $e, __METHOD__, $params );
		}

		return $status;
	}

	/**
	 * @see FileBackend::doPrepareInternal()
	 */
	protected function doPrepareInternal( $fullCont, $dir, array $params ) {
		$status = Status::newGood();

		try {
			$this->createContainer( $fullCont );
		} catch ( InvalidResponseException $e ) {
			$status->fatal( 'backend-fail-connect', $this->name );
		} catch ( Exception $e ) { // some other exception?
			$status->fatal( 'backend-fail-internal', $this->name );
			$this->logException( $e, __METHOD__, $params );
		}

		return $status;
	}

	/**
	 * @see FileBackend::doSecureInternal()
	 */
	protected function doSecureInternal( $fullCont, $dir, array $params ) {
		$status = Status::newGood();
		// @TODO: restrict container from $this->swiftProxyUser
		return $status;
	}

	/**
	 * @see FileBackend::doCleanInternal()
	 */
	protected function doCleanInternal( $fullCont, $dir, array $params ) {
		$status = Status::newGood();

		// (a) Check the container
		try {
			$contObj = $this->getContainer( $fullCont, true );
		} catch ( NoSuchContainerException $e ) {
			return $status; // ok, nothing to do
		} catch ( InvalidResponseException $e ) {
			$status->fatal( 'backend-fail-connect', $this->name );
			return $status;
		} catch ( Exception $e ) { // some other exception?
			$status->fatal( 'backend-fail-internal', $this->name );
			$this->logException( $e, __METHOD__, $params );
			return $status;
		}

		// (c) Delete the container if empty
		if ( $contObj->count == 0 ) {
			try {
				$this->deleteContainer( $fullCont );
			} catch ( NoSuchContainerException $e ) {
				return $status; // race?
			} catch ( InvalidResponseException $e ) {
				$status->fatal( 'backend-fail-connect', $this->name );
				return $status;
			} catch ( Exception $e ) { // some other exception?
				$status->fatal( 'backend-fail-internal', $this->name );
				$this->logException( $e, __METHOD__, $params );
				return $status;
			}
		}

		return $status;
	}

	/**
	 * @see FileBackend::doFileExists()
	 */
	protected function doGetFileStat( array $params ) {
		list( $srcCont, $srcRel ) = $this->resolveStoragePathReal( $params['src'] );
		if ( $srcRel === null ) {
			return false; // invalid storage path
		}

		$stat = false;
		try {
			$container = $this->getContainer( $srcCont );
			// @TODO: handle 'latest' param as "X-Newest: true"
			$obj = $container->get_object( $srcRel );
			// Convert dates like "Tue, 03 Jan 2012 22:01:04 GMT" to TS_MW
			$date = DateTime::createFromFormat( 'D, d F Y G:i:s e', $obj->last_modified );
			if ( $date ) {
				$stat = array(
					'mtime' => $date->format( 'YmdHis' ),
					'size'  => $obj->content_length,
					'sha1'  => $obj->metadata['Sha1base36']
				);
			} else { // exception will be caught below
				throw new Exception( "Could not parse date for object {$srcRel}" );
			}
		} catch ( NoSuchContainerException $e ) {
		} catch ( NoSuchObjectException $e ) {
		} catch ( InvalidResponseException $e ) {
			$stat = null;
		} catch ( Exception $e ) { // some other exception?
			$stat = null;
			$this->logException( $e, __METHOD__, $params );
		}

		return $stat;
	}

	/**
	 * @see FileBackendBase::getFileContents()
	 */
	public function getFileContents( array $params ) {
		list( $srcCont, $srcRel ) = $this->resolveStoragePathReal( $params['src'] );
		if ( $srcRel === null ) {
			return false; // invalid storage path
		}

		$data = false;
		try {
			$container = $this->getContainer( $srcCont );
			$obj = $container->get_object( $srcRel );
			$data = $obj->read( $this->headersFromParams( $params ) );
		} catch ( NoSuchContainerException $e ) {
		} catch ( NoSuchObjectException $e ) {
		} catch ( InvalidResponseException $e ) {
		} catch ( Exception $e ) { // some other exception?
			$this->logException( $e, __METHOD__, $params );
		}

		return $data;
	}

	/**
	 * @see FileBackend::getFileListInternal()
	 */
	public function getFileListInternal( $fullCont, $dir, array $params ) {
		return new SwiftFileBackendFileList( $this, $fullCont, $dir );
	}

	/**
	 * Do not call this function outside of SwiftFileBackendFileList
	 * 
	 * @param $fullCont string Resolved container name
	 * @param $dir string Resolved storage directory with no trailing slash
	 * @param $after string Storage path of file to list items after
	 * @param $limit integer Max number of items to list
	 * @return Array
	 */
	public function getFileListPageInternal( $fullCont, $dir, $after, $limit ) {
		$files = array();
		try {
			$container = $this->getContainer( $fullCont );
			$files = $container->list_objects( $limit, $after, "{$dir}/" );
		} catch ( NoSuchContainerException $e ) {
		} catch ( NoSuchObjectException $e ) {
		} catch ( InvalidResponseException $e ) {
		} catch ( Exception $e ) { // some other exception?
			$this->logException( $e, __METHOD__, $params );
		}

		return $files;
	}

	/**
	 * @see FileBackend::doGetFileSha1base36()
	 */
	public function doGetFileSha1base36( array $params ) {
		$stat = $this->getFileStat( $params );
		if ( $stat ) {
			return $stat['sha1'];
		} else {
			return false;
		}
	}

	/**
	 * @see FileBackend::doStreamFile()
	 */
	protected function doStreamFile( array $params ) {
		$status = Status::newGood();

		list( $srcCont, $srcRel ) = $this->resolveStoragePathReal( $params['src'] );
		if ( $srcRel === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['src'] );
		}

		try {
			$cont = $this->getContainer( $srcCont );
			$obj = $cont->get_object( $srcRel );
		} catch ( NoSuchContainerException $e ) {
			$status->fatal( 'backend-fail-stream', $params['src'] );
			return $status;
		} catch ( NoSuchObjectException $e ) {
			$status->fatal( 'backend-fail-stream', $params['src'] );
			return $status;
		} catch ( IOException $e ) {
			$status->fatal( 'backend-fail-stream', $params['src'] );
			return $status;
		} catch ( Exception $e ) { // some other exception?
			$status->fatal( 'backend-fail-stream', $params['src'] );
			$this->logException( $e, __METHOD__, $params );
			return $status;
		}

		try {
			$output = fopen( 'php://output', 'w' );
			$obj->stream( $output, $this->headersFromParams( $params ) );
		} catch ( InvalidResponseException $e ) {
			$status->fatal( 'backend-fail-connect', $this->name );
		} catch ( Exception $e ) { // some other exception?
			$status->fatal( 'backend-fail-stream', $params['src'] );
			$this->logException( $e, __METHOD__, $params );
		}

		return $status;
	}

	/**
	 * @see FileBackend::getLocalCopy()
	 */
	public function getLocalCopy( array $params ) {
		list( $srcCont, $srcRel ) = $this->resolveStoragePathReal( $params['src'] );
		if ( $srcRel === null ) {
			return null;
		}

		// Get source file extension
		$ext = FileBackend::extensionFromPath( $srcRel );
		// Create a new temporary file...
		$tmpFile = TempFSFile::factory( wfBaseName( $srcRel ) . '_', $ext );
		if ( !$tmpFile ) {
			return null;
		}

		try {
			$cont = $this->getContainer( $srcCont );
			$obj = $cont->get_object( $srcRel );
			$handle = fopen( $tmpFile->getPath(), 'w' );
			if ( $handle ) {
				$obj->stream( $handle, $this->headersFromParams( $params ) );
				fclose( $handle );
			} else {
				$tmpFile = null; // couldn't open temp file
			}
		} catch ( NoSuchContainerException $e ) {
			$tmpFile = null;
		} catch ( NoSuchObjectException $e ) {
			$tmpFile = null;
		} catch ( InvalidResponseException $e ) {
			$tmpFile = null;
		} catch ( Exception $e ) { // some other exception?
			$tmpFile = null;
			$this->logException( $e, __METHOD__, $params );
		}

		return $tmpFile;
	}

	/**
	 * Get headers to send to Swift when reading a file based
	 * on a FileBackend params array, e.g. that of getLocalCopy(). 
	 * $params is currently only checked for a 'latest' flag.
	 * 
	 * @param $params Array
	 * @return Array 
	 */
	protected function headersFromParams( array $params ) {
		$hdrs = array();
		if ( !empty( $params['latest'] ) ) {
			$hdrs[] = 'X-Newest: true';
		}
		return $hdrs;
	}

	/**
	 * Get a connection to the Swift proxy
	 *
	 * @return CF_Connection|false
	 * @throws InvalidResponseException
	 */
	protected function getConnection() {
		if ( $this->conn === false ) {
			return false; // failed last attempt
		}
		// Authenticate with proxy and get a session key.
		// Session keys expire after a while, so we renew them periodically.
		if ( $this->conn === null || ( time() - $this->connStarted ) > $this->connTTL ) {
			$this->connContainers = array();
			try {
				$this->auth->authenticate();
				$this->conn = new CF_Connection( $this->auth );
				$this->connStarted = time();
			} catch ( AuthenticationException $e ) {
				$this->conn = false; // don't keep re-trying
			} catch ( InvalidResponseException $e ) {
				$this->conn = false; // don't keep re-trying
			}
		}
		if ( !$this->conn ) {
			throw new InvalidResponseException; // auth/connection problem
		}
		return $this->conn;
	}

	/**
	 * Get a Swift container object, possibly from process cache.
	 * Use $reCache if the file count or byte count is needed.
	 *
	 * @param $container string Container name
	 * @param $reCache bool Refresh the process cache
	 * @return CF_Container
	 */
	protected function getContainer( $container, $reCache = false ) {
		$conn = $this->getConnection(); // Swift proxy connection
		if ( $reCache ) {
			unset( $this->connContainers[$container] ); // purge cache
		}
		if ( !isset( $this->connContainers[$container] ) ) {
			$contObj = $conn->get_container( $container );
			// Exception not thrown: container must exist
			$this->connContainers[$container] = $contObj; // cache it
		}
		return $this->connContainers[$container];
	}

	/**
	 * Create a Swift container
	 *
	 * @param $container string Container name
	 * @return CF_Container
	 */
	protected function createContainer( $container ) {
		$conn = $this->getConnection(); // Swift proxy connection
		$contObj = $conn->create_container( $container );
		$this->connContainers[$container] = $contObj; // cache it
		return $contObj;
	}

	/**
	 * Delete a Swift container
	 *
	 * @param $container string Container name
	 * @return void
	 */
	protected function deleteContainer( $container ) {
		$conn = $this->getConnection(); // Swift proxy connection
		$conn->delete_container( $container );
		unset( $this->connContainers[$container] ); // purge cache
	}

	/**
	 * Log an unexpected exception for this backend
	 * 
	 * @param $e Exception
	 * @param $func string
	 * @param $params Array
	 * @return void
	 */
	protected function logException( Exception $e, $func, array $params ) {
		wfDebugLog( 'SwiftBackend',
			get_class( $e ) . " in '{$this->name}': '{$func}' with " . serialize( $params ) 
		);
	}
}

/**
 * SwiftFileBackend helper class to page through object listings.
 * Swift also has a listing limit of 10,000 objects for sanity.
 * Do not use this class from places outside SwiftFileBackend.
 *
 * @ingroup FileBackend
 */
class SwiftFileBackendFileList implements Iterator {
	/** @var Array */
	protected $bufferIter = array();
	protected $bufferAfter = null; // string; list items *after* this path
	protected $pos = 0; // integer

	/** @var SwiftFileBackend */
	protected $backend; 
	protected $container; //
	protected $dir; // string storage directory
	protected $suffixStart; // integer

	const PAGE_SIZE = 5000; // file listing buffer size

	/**
	 * @param $backend SwiftFileBackend
	 * @param $fullCont string Resolved container name
	 * @param $dir string Resolved directory relative to container
	 */
	public function __construct( SwiftFileBackend $backend, $fullCont, $dir ) {
		$this->backend = $backend;
		$this->container = $fullCont;
		$this->dir = $dir;
		if ( substr( $this->dir, -1 ) === '/' ) {
			$this->dir = substr( $this->dir, 0, -1 ); // remove trailing slash
		}
		$this->suffixStart = strlen( $dir ) + 1; // size of "path/to/dir/"
	}

	public function current() {
		return substr( current( $this->bufferIter ), $this->suffixStart );
	}

	public function key() {
		return $this->pos;
	}

	public function next() {
		// Advance to the next file in the page
		next( $this->bufferIter );
		++$this->pos;
		// Check if there are no files left in this page and
		// advance to the next page if this page was not empty.
		if ( !$this->valid() && count( $this->bufferIter ) ) {
			$this->bufferAfter = end( $this->bufferIter );
			$this->bufferIter = $this->backend->getFileListPageInternal(
				$this->container, $this->dir, $this->bufferAfter, self::PAGE_SIZE
			);
		}
	}

	public function rewind() {
		$this->pos = 0;
		$this->bufferAfter = null;
		$this->bufferIter = $this->backend->getFileListPageInternal(
			$this->container, $this->dir, $this->bufferAfter, self::PAGE_SIZE
		);
	}

	public function valid() {
		return ( current( $this->bufferIter ) !== false ); // no paths can have this value
	}
}
