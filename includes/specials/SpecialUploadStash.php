<?php
/**
 * Implements Special:UploadStash
 *
 * Web access for files temporarily stored by UploadStash.
 *
 * For example -- files that were uploaded with the UploadWizard extension are stored temporarily
 * before committing them to the db. But we want to see their thumbnails and get other information
 * about them.
 *
 * Since this is based on the user's session, in effect this creates a private temporary file area.
 * However, the URLs for the files cannot be shared.
 *
 * @file
 * @ingroup SpecialPage
 * @ingroup Upload
 */

class SpecialUploadStash extends UnlistedSpecialPage {
	// UploadStash
	private $stash;

	// is the edit request authorized? boolean
	private $isEditAuthorized;

	// did the user request us to clear the stash? boolean
	private $requestedClear;

	// Since we are directly writing the file to STDOUT, 
	// we should not be reading in really big files and serving them out.
	//
	// We also don't want people using this as a file drop, even if they
	// share credentials.
	//
	// This service is really for thumbnails and other such previews while
	// uploading.
	const MAX_SERVE_BYTES = 262144; // 256K

	public function __construct( $request = null ) {
		global $wgRequest;

		parent::__construct( 'UploadStash', 'upload' );
		try {
			$this->stash = RepoGroup::singleton()->getLocalRepo()->getUploadStash();
		} catch ( UploadStashNotAvailableException $e ) {
			return null;
		}

		$this->loadRequest( is_null( $request ) ? $wgRequest : $request );
	}

	/**
	 * Execute page -- can output a file directly or show a listing of them.
	 *
	 * @param $subPage String: subpage, e.g. in http://example.com/wiki/Special:UploadStash/foo.jpg, the "foo.jpg" part
	 * @return Boolean: success
	 */
	public function execute( $subPage ) {
		global $wgUser;

		if ( !$this->userCanExecute( $wgUser ) ) {
			$this->displayRestrictionError();
			return;
		}

		if ( !isset( $subPage ) || $subPage === '' ) {
			return $this->showUploads();
		}

		return $this->showUpload( $subPage );
	}


	/**
	 * If file available in stash, cats it out to the client as a simple HTTP response.
	 * n.b. Most sanity checking done in UploadStashLocalFile, so this is straightforward.
	 *
	 * @param $key String: the key of a particular requested file
	 */
	public function showUpload( $key ) {
		global $wgOut;

		// prevent callers from doing standard HTML output -- we'll take it from here
		$wgOut->disable();

		try {
			if ( preg_match( '/^(\d+)px-(.*)$/', $key, $matches ) ) {
				list( /* full match */, $width, $key ) = $matches;
				return $this->outputThumbFromStash( $key, $width );
			} else {
				return $this->outputFileFromStash( $key );
			}
		} catch( UploadStashFileNotFoundException $e ) {
			$code = 404; 
			$message = $e->getMessage();
		} catch( UploadStashZeroLengthFileException $e ) {
			$code = 500;
			$message = $e->getMessage();
		} catch( UploadStashBadPathException $e ) {
			$code = 500;
			$message = $e->getMessage();
		} catch( SpecialUploadStashTooLargeException $e ) {
			$code = 500;
			$message = 'Cannot serve a file larger than ' . self::MAX_SERVE_BYTES . ' bytes. ' . $e->getMessage();
		} catch( Exception $e ) {
			$code = 500;
			$message = $e->getMessage();
		}

		wfHttpError( $code, OutputPage::getStatusMessage( $code ), $message );
		return false;
	}
		
	/**
	 * Get a file from stash and stream it out. Rely on parent to catch exceptions and transform them into HTTP
	 * @param String: $key - key of this file in the stash, which probably looks like a filename with extension.
	 * @return boolean
	 */
	private function outputFileFromStash( $key ) {
		$file = $this->stash->getFile( $key );
		return $this->outputLocalFile( $file );
	}


	/**
	 * Get a thumbnail for file, either generated locally or remotely, and stream it out
	 * @param String $key: key for the file in the stash
	 * @param int $width: width of desired thumbnail
	 * @return boolean success 
 	 */
	private function outputThumbFromStash( $key, $width ) {
		
		// this global, if it exists, points to a "scaler", as you might find in the Wikimedia Foundation cluster. See outputRemoteScaledThumb()
		// this is part of our horrible NFS-based system, we create a file on a mount point here, but fetch the scaled file from somewhere else that
		// happens to share it over NFS
		global $wgUploadStashScalerBaseUrl;

		// let exceptions propagate to caller.
		$file = $this->stash->getFile( $key );

		// OK, we're here and no exception was thrown,
		// so the original file must exist.

		// let's get ready to transform the original -- these are standard
		$params = array( 'width' => $width );	
		$flags = 0;

		return $wgUploadStashScalerBaseUrl ? $this->outputRemoteScaledThumb( $file, $params, $flags )
						   : $this->outputLocallyScaledThumb( $file, $params, $flags );

	}


	/**
	 * Scale a file (probably with a locally installed imagemagick, or similar) and output it to STDOUT.
 	 * @param $file: File object
	 * @param $params: scaling parameters ( e.g. array( width => '50' ) );
 	 * @param $flags: scaling flags ( see File:: constants )
	 * @throws MWException
	 * @return boolean success
	 */
	private function outputLocallyScaledThumb( $file, $params, $flags ) {

		// n.b. this is stupid, we insist on re-transforming the file every time we are invoked. We rely
		// on HTTP caching to ensure this doesn't happen.
		
		$flags |= File::RENDER_NOW;

		$thumbnailImage = $file->transform( $params, $flags );
		if ( !$thumbnailImage ) {
			throw new MWException( 'Could not obtain thumbnail' );
		}

		// we should have just generated it locally
		if ( ! $thumbnailImage->getPath() ) {
			throw new UploadStashFileNotFoundException( "no local path for scaled item" );
		}

		// now we should construct a File, so we can get mime and other such info in a standard way
		// n.b. mimetype may be different from original (ogx original -> jpeg thumb)
		$thumbFile = new UnregisteredLocalFile( false, $this->stash->repo, $thumbnailImage->getPath(), false );
		if ( ! $thumbFile ) {
			throw new UploadStashFileNotFoundException( "couldn't create local file object for thumbnail" );
		}

		return $this->outputLocalFile( $thumbFile );
	
	}
	
	/**
	 * Scale a file with a remote "scaler", as exists on the Wikimedia Foundation cluster, and output it to STDOUT.
	 * Note: unlike the usual thumbnail process, the web client never sees the cluster URL; we do the whole HTTP transaction to the scaler ourselves 
	 *  and cat the results out.
	 * Note: We rely on NFS to have propagated the file contents to the scaler. However, we do not rely on the thumbnail being created in NFS and then 
	 *   propagated back to our filesystem. Instead we take the results of the HTTP request instead.  
	 * Note: no caching is being done here, although we are instructing the client to cache it forever.
 	 * @param $file: File object
	 * @param $params: scaling parameters ( e.g. array( width => '50' ) );
 	 * @param $flags: scaling flags ( see File:: constants )
	 * @throws MWException
	 * @return boolean success
	 */
	private function outputRemoteScaledThumb( $file, $params, $flags ) {
		
		// this global probably looks something like 'http://upload.wikimedia.org/wikipedia/test/thumb/temp'
		// do not use trailing slash
		global $wgUploadStashScalerBaseUrl;

		$scalerThumbName = $file->getParamThumbName( $file->name, $params );
		$scalerThumbUrl = $wgUploadStashScalerBaseUrl . '/' . $file->getRel() . '/' . $scalerThumbName;
		
		// make a curl call to the scaler to create a thumbnail
		$httpOptions = array( 
			'method' => 'GET',
			'timeout' => 'default'
		);
		$req = MWHttpRequest::factory( $scalerThumbUrl, $httpOptions );
		$status = $req->execute();
		if ( ! $status->isOK() ) {
			$errors = $status->getErrorsArray();	
			throw new MWException( "Fetching thumbnail failed: " . join( ", ", $errors ) );
		}
		$contentType = $req->getResponseHeader( "content-type" );
		if ( ! $contentType ) {
			throw new MWException( "Missing content-type header" );
		}
		return $this->outputContents( $req->getContent(), $contentType );
	}

	/**
	 * Output HTTP response for file
	 * Side effect: writes HTTP response to STDOUT.
	 * XXX could use wfStreamfile (in includes/Streamfile.php), but for consistency with outputContents() doing it this way.
	 * XXX is mimeType really enough, or do we need encoding for full Content-Type header?
	 *
	 * @param $file File object with a local path (e.g. UnregisteredLocalFile, LocalFile. Oddly these don't share an ancestor!)
	 */
	private function outputLocalFile( $file ) {
		if ( $file->getSize() > self::MAX_SERVE_BYTES ) {
			throw new SpecialUploadStashTooLargeException();
		} 
		self::outputFileHeaders( $file->getMimeType(), $file->getSize() );
		readfile( $file->getPath() );
		return true;
	}

	/** 
	 * Output HTTP response of raw content
	 * Side effect: writes HTTP response to STDOUT.
	 * @param String $content: content
	 * @param String $mimeType: mime type
	 */
	private function outputContents( $content, $contentType ) {
		$size = strlen( $content );
		if ( $size > self::MAX_SERVE_BYTES ) {
			throw new SpecialUploadStashTooLargeException();
		}
		self::outputFileHeaders( $contentType, $size );
		print $content;	
		return true;
	}

	/** 
	 * Output headers for streaming
	 * XXX unsure about encoding as binary; if we received from HTTP perhaps we should use that encoding, concatted with semicolon to mimeType as it usually is.
	 * Side effect: preps PHP to write headers to STDOUT.
	 * @param String $contentType : string suitable for content-type header
	 * @param String $size: length in bytes
	 */
	private static function outputFileHeaders( $contentType, $size ) {
		header( "Content-Type: $contentType", true );
		header( 'Content-Transfer-Encoding: binary', true );
		header( 'Expires: Sun, 17-Jan-2038 19:14:07 GMT', true );
		header( "Content-Length: $size", true ); 
	}


	/**
	 * Initialize authorization & actions to take, from the request
	 * @param $request: WebRequest
	 */
	private function loadRequest( $request ) {
                global $wgUser;
		if ( $request->wasPosted() ) {

			$token = $request->getVal( 'wpEditToken' );
			$this->isEditAuthorized = $wgUser->matchEditToken( $token );

			$this->requestedClear = $request->getBool( 'clear' );

		}
	}

	/**
	 * Static callback for the HTMLForm in showUploads, to process 
	 * Note the stash has to be recreated since this is being called in a static context.
	 * This works, because there really is only one stash per logged-in user, despite appearances.
	 *
	 * @return Status
	 */ 
	public static function tryClearStashedUploads( $formData ) {
		wfDebug( __METHOD__ . " form data : " . print_r( $formData, 1 ) );
		if ( isset( $formData['clear'] ) and $formData['clear'] ) {
			$stash = new UploadStash();
			wfDebug( "stash has: " . print_r( $stash->listFiles(), 1 ) );
			if ( ! $stash->clear() ) {
				return Status::newFatal( 'uploadstash-errclear' );
			}
		}
		return Status::newGood();
	}

	/**
	 * Default action when we don't have a subpage -- just show links to the uploads we have,
	 * Also show a button to clear stashed files
	 * @param Status : $status - the result of processRequest
	 */ 
	private function showUploads( $status = null ) {
		global $wgOut;
		if ( $status === null ) {
			$status = Status::newGood();
		}

		// sets the title, etc.
		$this->setHeaders();
		$this->outputHeader();


		// create the form, which will also be used to execute a callback to process incoming form data
		// this design is extremely dubious, but supposedly HTMLForm is our standard now?

		$form = new HTMLForm( array( 
			'Clear' => array( 
				'type' => 'hidden', 
				'default' => true,
				'name' => 'clear',
			) 
		), 'clearStashedUploads' );
		$form->setSubmitCallback( array( __CLASS__, 'tryClearStashedUploads' ) ); 
		$form->setTitle( $this->getTitle() );
		$form->addHiddenField( 'clear', true, array( 'type' => 'boolean' ) );
		$form->setSubmitText( wfMsg( 'uploadstash-clear' ) );

                $form->prepareForm();                                                
                $formResult = $form->tryAuthorizedSubmit();
                                                                    

		// show the files + form, if there are any, or just say there are none
		$refreshHtml = Html::element( 'a', array( 'href' => $this->getTitle()->getLocalURL() ), wfMsg( 'uploadstash-refresh' ) );
		$files = $this->stash->listFiles();
		if ( count( $files ) ) {
			sort( $files );
			$fileListItemsHtml = '';
			foreach ( $files as $file ) {
				$fileListItemsHtml .= Html::rawElement( 'li', array(),
					Html::element( 'a', array( 'href' => $this->getTitle( $file )->getLocalURL() ), $file )
				);
			}
			$wgOut->addHtml( Html::rawElement( 'ul', array(), $fileListItemsHtml ) );
                	$form->displayForm( $formResult );
			$wgOut->addHtml( Html::rawElement( 'p', array(), $refreshHtml ) );
		} else {
			$wgOut->addHtml( Html::rawElement( 'p', array(), 
				Html::element( 'span', array(), wfMsg( 'uploadstash-nofiles' ) )
				. ' ' 
				. $refreshHtml
			) );
		}
	
		return true;
	}
}

class SpecialUploadStashTooLargeException extends MWException {};
