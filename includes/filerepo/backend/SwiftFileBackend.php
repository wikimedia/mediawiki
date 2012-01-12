<?php
/**
 * @file
 * @ingroup FileBackend
 * @author Russ Nelson
 * @author Aaron Schulz
 */

/**
 * Class for a Swift based file backend.
 * Status messages should avoid mentioning the Swift account name
 * Likewise, error suppression should be used to avoid path disclosure.
 *
 * This requires the php-cloudfiles library is present,
 * which is available at https://github.com/rackspace/php-cloudfiles.
 * All of the library classes must be registed in $wgAutoloadClasses.
 *
 * @TODO: update MessagesEn for status errors.
 * @TODO: handle 'latest' param as "X-Newest: true".
 *
 * @ingroup FileBackend
 */
class SwiftFileBackend extends FileBackend {
	/** @var CF_Authentication */
	protected $auth; // swift authentication handler
	/** @var CF_Connection */
	protected $conn; // swift connection handle
	protected $connStarted = 0; // integer UNIX timestamp

	protected $swiftProxyUser; // string
	protected $connTTL = 60; // integer seconds

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
			return null; // too long for swift
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

		// (a) Get a swift proxy connection
		$conn = $this->getConnection();
		if ( !$conn ) {
			$status->fatal( 'backend-fail-connect', $this->name );
			return $status;
		}

		// (b) Check the destination container
		try {
			$dContObj = $conn->get_container( $dstCont );
		} catch ( NoSuchContainerException $e ) {
			$status->fatal( 'backend-fail-create', $params['dst'] );
			return $status;
		} catch ( InvalidResponseException $e ) {
			$status->fatal( 'backend-fail-connect', $this->name );
			return $status;
		} catch ( Exception $e ) { // some other exception?
			$status->fatal( 'backend-fail-internal' );
			$this->logException( $e, __METHOD__, $params );
			return $status;
		}

		// (c) Check if the destination object already exists
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
			$status->fatal( 'backend-fail-internal' );
			$this->logException( $e, __METHOD__, $params );
			return $status;
		}

		// (d) Get a SHA-1 hash of the object
		$sha1Hash = wfBaseConvert( sha1( $params['content'] ), 16, 36, 31 );

		// (e) Actually create the object
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
			$status->fatal( 'backend-fail-internal' );
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

		// (a) Get a swift proxy connection
		$conn = $this->getConnection();
		if ( !$conn ) {
			$status->fatal( 'backend-fail-connect', $this->name );
			return $status;
		}

		// (b) Check the destination container
		try {
			$dContObj = $conn->get_container( $dstCont );
		} catch ( NoSuchContainerException $e ) {
			$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
			return $status;
		} catch ( InvalidResponseException $e ) {
			$status->fatal( 'backend-fail-connect', $this->name );
			return $status;
		} catch ( Exception $e ) { // some other exception?
			$status->fatal( 'backend-fail-internal' );
			$this->logException( $e, __METHOD__, $params );
			return $status;
		}

		// (c) Check if the destination object already exists
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
			$status->fatal( 'backend-fail-internal' );
			$this->logException( $e, __METHOD__, $params );
			return $status;
		}

		// (d) Get a SHA-1 hash of the object
		$sha1Hash = sha1_file( $params['src'] );
		if ( $sha1Hash === false ) { // source doesn't exist?
			$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
			return $status;
		}
		$sha1Hash = wfBaseConvert( $sha1Hash, 16, 36, 31 );

		// (e) Actually store the object
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
			$status->fatal( 'backend-fail-internal' );
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

		// (a) Get a swift proxy connection
		$conn = $this->getConnection();
		if ( !$conn ) {
			$status->fatal( 'backend-fail-connect', $this->name );
			return $status;
		}

		// (b) Check the source and destination containers
		try {
			$sContObj = $conn->get_container( $srcCont );
			$dContObj = $conn->get_container( $dstCont );
		} catch ( NoSuchContainerException $e ) {
			$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
			return $status;
		} catch ( InvalidResponseException $e ) {
			$status->fatal( 'backend-fail-connect', $this->name );
			return $status;
		} catch ( Exception $e ) { // some other exception?
			$status->fatal( 'backend-fail-internal' );
			$this->logException( $e, __METHOD__, $params );
			return $status;
		}

		// (c) Check if the destination object already exists
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
			$status->fatal( 'backend-fail-internal' );
			$this->logException( $e, __METHOD__, $params );
			return $status;
		}

		// (d) Actually copy the file to the destination
		try {
			$sContObj->copy_object_to( $srcRel, $dContObj, $destRel );
		} catch ( NoSuchObjectException $e ) { // source object does not exist
			$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
		} catch ( InvalidResponseException $e ) {
			$status->fatal( 'backend-fail-connect', $this->name );
		} catch ( Exception $e ) { // some other exception?
			$status->fatal( 'backend-fail-internal' );
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

		// (a) Get a swift proxy connection
		$conn = $this->getConnection();
		if ( !$conn ) {
			$status->fatal( 'backend-fail-connect', $this->name );
			return $status;
		}

		// (b) Check the source container
		try {
			$sContObj = $conn->get_container( $srcCont );
		} catch ( NoSuchContainerException $e ) {
			$status->fatal( 'backend-fail-delete', $params['src'] );
			return $status;
		} catch ( InvalidResponseException $e ) {
			$status->fatal( 'backend-fail-connect', $this->name );
			return $status;
		} catch ( Exception $e ) { // some other exception?
			$status->fatal( 'backend-fail-internal' );
			$this->logException( $e, __METHOD__, $params );
			return $status;
		}

		// (c) Actually delete the object
		try {
			$sContObj->delete_object( $srcRel );
		} catch ( NoSuchObjectException $e ) {
			if ( empty( $params['ignoreMissingSource'] ) ) {
				$status->fatal( 'backend-fail-delete', $params['src'] );
			}
		} catch ( InvalidResponseException $e ) {
			$status->fatal( 'backend-fail-connect', $this->name );
		} catch ( Exception $e ) { // some other exception?
			$status->fatal( 'backend-fail-internal' );
			$this->logException( $e, __METHOD__, $params );
		}

		return $status;
	}

	/**
	 * @see FileBackend::doPrepareInternal()
	 */
	protected function doPrepareInternal( $fullCont, $dir, array $params ) {
		$status = Status::newGood();

		// (a) Get a swift proxy connection
		$conn = $this->getConnection();
		if ( !$conn ) {
			$status->fatal( 'backend-fail-connect', $this->name );
			return $status;
		}

		// (b) Create the destination container
		try {
			$conn->create_container( $fullCont );
		} catch ( InvalidResponseException $e ) {
			$status->fatal( 'backend-fail-connect', $this->name );
		} catch ( Exception $e ) { // some other exception?
			$status->fatal( 'backend-fail-internal' );
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
		return $status; // badgers? We don't need no steenking badgers!
	}

	/**
	 * @see FileBackend::doFileExists()
	 */
	protected function doGetFileStat( array $params ) {
		list( $srcCont, $srcRel ) = $this->resolveStoragePathReal( $params['src'] );
		if ( $srcRel === null ) {
			return false; // invalid storage path
		}

		$conn = $this->getConnection();
		if ( !$conn ) {
			return null;
		}

		$stat = false;
		try {
			$container = $conn->get_container( $srcCont );
			$obj = $container->get_object( $srcRel );
			// Convert "Tue, 03 Jan 2012 22:01:04 GMT" to TS_MW
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

		$conn = $this->getConnection();
		if ( !$conn ) {
			return false;
		}

		$data = false;
		try {
			$container = $conn->get_container( $srcCont );
			$obj = $container->get_object( $srcRel );
			$data = $obj->read();
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
		return new SwiftFileIterator( $this, $fullCont, $dir );
	}

	/**
	 * Do not call this function outside of SwiftFileIterator
	 * 
	 * @param $fullCont string Resolved container name
	 * @param $dir string Resolved storage directory
	 * @param $after string Storage path of file to list items after
	 * @param $limit integer Max number of items to list
	 * @return Array
	 */
	public function getFileListPageInternal( $fullCont, $dir, $after, $limit ) {
		$conn = $this->getConnection();
		if ( !$conn ) {
			return null;
		}

		$files = array();
		try {
			$container = $conn->get_container( $fullCont );
			$files = $container->list_objects( $limit, $after, $dir );
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

		$conn = $this->getConnection();
		if ( !$conn ) {
			$status->fatal( 'backend-fail-connect', $this->name );
		}

		try {
			$cont = $conn->get_container( $srcCont );
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
			$output = fopen("php://output", "w");
			$obj->stream( $output );
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

		$conn = $this->getConnection();
		if ( !$conn ) {
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
			$cont = $conn->get_container( $srcCont );
			$obj = $cont->get_object( $srcRel );
			$obj->save_to_filename( $tmpFile->getPath() );
		} catch ( NoSuchContainerException $e ) {
			$tmpFile = null;
		} catch ( NoSuchObjectException $e ) {
			$tmpFile = null;
		} catch ( IOException $e ) {
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
	 * Get a connection to the swift proxy
	 *
	 * @return CF_Connection|false
	 */
	protected function getConnection() {
		if ( $this->conn === false ) {
			return false; // failed last attempt
		}
		// Authenticate with proxy and get a session key.
		// Session keys expire after a while, so we renew them periodically.
		if ( $this->conn === null || ( time() - $this->connStarted ) > $this->connTTL ) {
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
		return $this->conn;
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
 *
 * @ingroup FileBackend
 */
class SwiftFileIterator implements Iterator {
	/** @var Array */
	protected $bufferIter = array();
	protected $bufferAfter = null; // string; list items *after* this path
	protected $pos = 0; // integer

	/** @var SwiftFileBackend */
	protected $backend; 
	protected $container; //
	protected $dir; // string storage directory

	const PAGE_SIZE = 5000; // file listing buffer size

	/**
	 * Get an FSFileIterator from a file system directory
	 * 
	 * @param $backend SwiftFileBackend
	 * @param $fullCont string Resolved container name
	 * @param $dir string Resolved relateive path
	 */
	public function __construct( SwiftFileBackend $backend, $fullCont, $dir ) {
		$this->container = $fullCont;
		$this->dir = $dir;
		$this->backend = $backend;
	}

	public function current() {
		return current( $this->bufferIter );
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
