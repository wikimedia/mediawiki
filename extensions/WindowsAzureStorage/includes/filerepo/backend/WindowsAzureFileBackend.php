<?php
/**
 * @file
 * @ingroup FileBackend
 * @author Markus Glaser
 * @author Robert Vogel
 * @author Hallo Welt! - Medienwerkstatt GmbH for Microsoft Corp.
 */

/**
 * Copied and modified from Swift FileBackend:
 * 
 * Class for a Windows Azure Blob Storage based file backend.
 * Status messages should avoid mentioning the Azure account name
 * Likewise, error suppression should be used to avoid path disclosure.
 *
 * This requires the PHPAzure library to be present,
 * which is available at http://phpazure.codeplex.com/.
 * All of the library classes must be registed in $wgAutoloadClasses.
 * You may use the WindowsAzureSDK MediaWiki extension to fulfill this
 * requirement.
 *
 * @ingroup FileBackend
 */
class WindowsAzureFileBackend extends FileBackend {

	function doStore( array $p ) {
		return $this->doStoreInternal( $p );
	}
	
	function doCopy( array $p ) {
		return $this->doCopyInternal( $p );
	}
	
	function doDelete( array $p ) {
		return $this->doDeleteInternal( $p );
	}
	
	function doConcatenate( array $p ) {
		return $this->dodoConcatenateInternal( $p );
	}
	
	function doCreate( array $p ) {
		return $this->doCreateInternal( $p );
	}
	
	/**
	 * @see FileBackend::move()
	 */
	protected function doMove( array $params ) {
		// Copy source to dest
		// TODO: Remove backend. I assume, this function does not need to be overridden.
		$status = $this->backend->copy( $params );
		if ( !$status->isOK() ) {
			return $status;
		}
		// Delete source (only fails due to races or medium going down)
		// TODO: Remoce backend
		$status->merge( $this->backend->delete( array( 'src' => $params['src'] ) ) );
		$status->setResult( true, $status->value ); // ignore delete() errors
		return $status;
	}
	
	/** @var Microsoft_WindowsAzure_Storage_Blob */
	protected $storageClient = null;

    /** @var Array Map of container names to Azure container names */
	protected $containerPaths = array();
	
	/**
	 * @see FileBackend::__construct()
	 * Additional $config params include:
	 *    azureHost      : Windows Azure server URL
	 *    azureAccount   : Windows Azure user used by MediaWiki
	 *    azureKey       : Authentication key for the above user (used to get sessions)
	 *    //azureContainer : Identifier of the container. (Optional. If not provided wikiId will be used as container name)
     *    containerPaths : Map of container names to Azure container names
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );
		$this->storageClient = new Microsoft_WindowsAzure_Storage_Blob(
				$config['azureHost'],
				$config['azureAccount'],
				$config['azureKey']
		);

        $this->containerPaths = (array)$config['containerPaths'];
	}

	/**
	 * @see FileBackend::resolveContainerPath()
	 */
	protected function resolveContainerPath( $container, $relStoragePath ) {
        //Azure container naming conventions; http://msdn.microsoft.com/en-us/library/dd135715.aspx

		if ( strlen( urlencode( $relStoragePath ) ) > 1024 ) {
			return null;
		}
		// TODO: Should storagepath not be urlencoded?
		return $relStoragePath;
	}

	/**
	 * @see FileBackend::doStoreInternal()
	 */
	function doStoreInternal( array $params ) {
		$status = Status::newGood();
		// TODO: Use more telling names
		list( $c, $dir ) = $this->resolveStoragePath( $params['dst'] );
		try {
			$result = $this->storageClient->putBlob( $c, $dir, $params['src']);
		}
		catch ( Exception $e ) {
			// TODO: Read exception. Are there different ones?
			$status->fatal( 'backend-fail-put' );
		}
		//error_log( __METHOD__.'::putBlob - result: '.print_r( $result, true ) );
		return $status;
	}

	/**
	 * @see FileBackend::doCopyInternal()
	 */
	function doCopyInternal( array $params ) {
		$status = Status::newGood();
		list( $srcContainer, $srcDir ) = $this->resolveStoragePath( $params['src'] );
		list( $dstContainer, $dstDir ) = $this->resolveStoragePath( $params['dst'] );
		// TODO: check for null
		try {
			$result = $this->storageClient->copyBlob( $srcContainer, $srcDir, $dstContainer, $dstDir);
		}
		catch ( Exception $e ) {
			$status->fatal( 'backend-fail-copy', $e->getMessage() );
		}
		//error_log( __METHOD__.'::copyBlob - result: '.print_r( $result, true ) );			
		return $status;
	}

	/**
	 * @see FileBackend::doDeleteInternal()
	 */
	function doDeleteInternal( array $params ) {
		$status = Status::newGood();

		list( $srcCont, $srcRel ) = $this->resolveStoragePath( $params['src'] );
		if ( $srcRel === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['src'] );
			return $status;
		}

		// (a) Check the source container
		try { //TODO: Unnecessary --> remove
            $container = $this->storageClient->getContainer( $srcCont );
		}
        catch ( Exception $e ) {
			// TODO: remove error_log
            error_log( __METHOD__.':'.__LINE__.' '.$e->getMessage() );
			$status->fatal( 'backend-fail-internal' );
			return $status;
		}

		// (b) Actually delete the object
		try {
			$this->storageClient->deleteBlob( $srcCont, $srcRel );
		}
        catch ( Exception $e ) {
            error_log( __METHOD__.':'.__LINE__.' '.$e->getMessage() );
			$status->fatal( 'backend-fail-internal' );
		}

		return $status;
	}

	/**
	 * @see FileBackend::doCreateInternal()
	 */
	function doCreateInternal( array $params ) {
		$status = Status::newGood();

		list( $dstCont, $dstRel ) = $this->resolveStoragePath( $params['dst'] );
		if ( $dstRel === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );
			return $status;
		}

		// (a) Check if the destination object already exists
        $blobExists = $this->storageClient->blobExists( $dstCont, $dstRel );
        if ( $blobExists && empty( $params['overwriteDest'] ) ) { //Blob exists _and_ should not be overridden
            $status->fatal( 'backend-fail-alreadyexists', $params['dst'] );
            return $status;
        }

		// (b) Actually create the object
		try {
			// TODO: how do I know the container exists? Should we call prepare?
            $this->storageClient->putBlobData( $dstCont, $dstRel,  $params['content'] );
		}
        catch ( Exception $e ) {
            error_log( __METHOD__.':'.__LINE__.' '.$e->getMessage() );
			$status->fatal( 'backend-fail-internal' );
		}

		return $status;
	}

	/**
	 * @see FileBackend::prepare()
	 */
	function prepare( array $params ) {
		$status = Status::newGood();

        list( $c, $dir ) = $this->resolveStoragePath( $params['dir'] );
		if ( $dir === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dir'] );
			return $status; // invalid storage path
		}
        try {
            $this->storageClient->createContainerIfNotExists( $c );
            $this->storageClient->setContainerAcl( $c, Microsoft_WindowsAzure_Storage_Blob::ACL_PUBLIC );//TODO: Really set public?

            //TODO: check if readable and writeable
            //$container = $this->storageClient->getContainer( $c );
            //$status->fatal( 'directoryreadonlyerror', $params['dir'] );
            //$status->fatal( 'directorynotreadableerror', $params['dir'] );
        }
        catch (Exception $e ) {
            $status->fatal( 'directorycreateerror', $params['dir'] );
			return $status;
        }
		return $status;
	}
    
    /**
	 * @see FileBackend::resolveContainerName()
	 */
    protected function resolveContainerName( $container ) {
        //Azure container naming conventions; http://msdn.microsoft.com/en-us/library/dd135715.aspx
        $container = strtolower($container);
        $container = preg_replace( '#[^a-z0-9\-]#', '', $container );
		// TODO: -test und test- geht auch nicht
        $container = preg_replace( '#-{2,}#', '-', $container );

		return $container;
	}

	/**
	 * @see FileBackend::secure()
	 */
	function secure( array $params ) {
		$status = Status::newGood();
		// @TODO: restrict container from $this->swiftProxyUser
		return $status; // badgers? We don't need no steenking badgers!
	}

	/**
	 * @see FileBackend::fileExists()
	 */
	function fileExists( array $params ) {
        list( $c, $dir ) = $this->resolveStoragePath( $params['src'] );
		// TODO: null? Telling names
		$exists = $this->storageClient->blobExists( $c, $dir );
		//error_log( __METHOD__.'::blobExists - result: '.$exists );
		return $exists;
	}

	/**
	 * @see FileBackend::getFileTimestamp()
	 */
	function getFileTimestamp( array $params ) {
		list( $srcCont, $srcRel ) = $this->resolveStoragePath( $params['src'] );
		if ( $srcRel === null ) {
			return false; // invalid storage path
		}

        $timestamp= false;
		try {
            //TODO Maybe use getBlobData()?
            $blob = $this->storageClient->getBlobInstance( $srcCont, $srcRel );
            $timestamp = wfTimestamp( TS_MW, $blob->lastmodified ); //TODO: Timezone?
		} catch ( Exception $e ) { // some other exception?
            error_log( __METHOD__.':'.__LINE__.' '.$e->getMessage() );
		}
        return $timestamp;
	}

	/**
	 * @see FileBackend::getFileList()
	 */
	function getFileList( array $params ) {
        $files = array();
		list( $c, $dir ) = $this->resolveStoragePath( $params['dir'] );
        try {
            if ( $dir === null ) {
                $blobs = $this->storageClient->listBlobs($c);
            }
            else {
                $blobs = $this->storageClient->listBlobs( $c, $dir );//TODO:Check if $dir really is a startsequence of the blob name
            }
            foreach( $blobs as $blob ) {
                $files[] = $blob->name;
            }
        }
        catch( Exception $e ) {
            error_log( __METHOD__.':'.__LINE__.' '.$e->getMessage() );
            return null;
        }

		// if there are no files matching the prefix, return empty array
		return $files;
	}

	/**
	 * @see FileBackend::getLocalCopy()
	 */
	function getLocalCopy( array $params ) {
		list( $srcCont, $srcRel ) = $this->resolveStoragePath( $params['src'] );
		if ( $srcRel === null ) {
			return null;
		}

		// Get source file extension
		$ext = FileBackend::extensionFromPath( $srcRel );
		// Create a new temporary file...
		// TODO: Caution: tempfile should not write a local file.
		$tmpFile = TempFSFile::factory( wfBaseName( $srcRel ) . '_', $ext );
		if ( !$tmpFile ) {
			return null;
		}
		$tmpPath = $tmpFile->getPath();

		try {
            $this->storageClient->getBlob( $srcCont, $srcRel, $tmpPath );
		}
        catch ( Exception $e ) {
            error_log( __METHOD__.':'.__LINE__.' '.$e->getMessage() );
			$tmpFile = null;
		}

		return $tmpFile;
	}
}
