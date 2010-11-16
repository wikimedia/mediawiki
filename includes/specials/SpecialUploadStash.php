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

	// Since we are directly writing the file to STDOUT, 
	// we should not be reading in really big files and serving them out.
	//
	// We also don't want people using this as a file drop, even if they
	// share credentials.
	//
	// This service is really for thumbnails and other such previews while
	// uploading.
	const MAX_SERVE_BYTES = 262144; // 256K

	public function __construct( ) {
		parent::__construct( 'UploadStash', 'upload' );
		try {
			$this->stash = new UploadStash( );
		} catch (UploadStashNotAvailableException $e) {
			return null;
		}
	}

	/**
	 * If file available in stash, cats it out to the client as a simple HTTP response.
	 * n.b. Most sanity checking done in UploadStashLocalFile, so this is straightforward.
	 *
	 * @param $subPage String: subpage, e.g. in http://example.com/wiki/Special:UploadStash/foo.jpg, the "foo.jpg" part
	 * @return Boolean: success
	 */
	public function execute( $subPage ) {
		global $wgOut, $wgUser;

		if ( !$this->userCanExecute( $wgUser ) ) {
			$this->displayRestrictionError();
			return;
		}

		// prevent callers from doing standard HTML output -- we'll take it from here
		$wgOut->disable();

		$code = 500;
		$message = 'Unknown error';

		if ( !isset( $subPage ) or $subPage === '' ) {
			// the user probably visited the page just to see what would happen, so explain it a bit.
			$code = '400';
			$message = "Missing key\n\n" 
				   . 'This page provides access to temporarily stashed files for the user that '
				   . 'uploaded those files. See the upload API documentation. To access a stashed file, '
				   . 'use the URL of this page, with a slash and the key of the stashed file appended.';
		} else {
			try {
				$file = $this->getStashFile( $subPage );
				$size = $file->getSize();
				if ( $size === 0 ) {
					$code = 500;
					$message = 'File is zero length';
				} else if ( $size > self::MAX_SERVE_BYTES ) {
					$code = 500;
					$message = 'Cannot serve a file larger than ' . self::MAX_SERVE_BYTES . ' bytes';
				} else {
					$this->outputFile( $file );
					return true;
				}
			} catch( UploadStashFileNotFoundException $e ) {
				$code = 404; 
				$message = $e->getMessage();
			} catch( UploadStashBadPathException $e ) {
				$code = 500;
				$message = $e->getMessage();
			} catch( Exception $e ) {
				$code = 500;
				$message = $e->getMessage();
			}
		}

		wfHttpError( $code, OutputPage::getStatusMessage( $code ), $message );
		return false;
	}


	/**
	 * Convert the incoming url portion (subpage of Special page) into a stashed file,
	 * if available.
	 *
	 * @param $subPage String
	 * @return File object
	 * @throws MWException, UploadStashFileNotFoundException, UploadStashBadPathException
	 */
	private function getStashFile( $subPage ) {
		// due to an implementation quirk (and trying to be compatible with older method)
		// the stash key doesn't have an extension
		$key = $subPage;
		$n = strrpos( $subPage, '.' );
		if ( $n !== false ) {
			$key = $n ? substr( $subPage, 0, $n ) : $subPage;
		}

		try {
			$file = $this->stash->getFile( $key );
		} catch ( UploadStashFileNotFoundException $e ) {
			// if we couldn't find it, and it looks like a thumbnail,
			// and it looks like we have the original, go ahead and generate it
			$matches = array();
			if ( ! preg_match( '/^(\d+)px-(.*)$/', $key, $matches ) ) {
				// that doesn't look like a thumbnail. re-raise exception
				throw $e;
			}

			list( $dummy, $width, $origKey ) = $matches;

			// do not trap exceptions, if key is in bad format, or file not found,
			// let exceptions propagate to caller.
			$origFile = $this->stash->getFile( $origKey );

			// ok we're here so the original must exist. Generate the thumbnail.
			// because the file is a UploadStashFile, this thumbnail will also be stashed,
			// and a thumbnailFile will be created in the thumbnailImage composite object
			$thumbnailImage = $origFile->transform( array( 'width' => $width ) );
			if ( !$thumbnailImage ) {
				throw new MWException( 'Could not obtain thumbnail' );
			}
			$file = $thumbnailImage->thumbnailFile;
		}

		return $file;
	}

	/**
	 * Output HTTP response for file
	 * Side effects, obviously, of echoing lots of stuff to stdout.
	 *
	 * @param $file File object
	 */
	private function outputFile( $file ) {
		header( 'Content-Type: ' . $file->getMimeType(), true );
		header( 'Content-Transfer-Encoding: binary', true );
		header( 'Expires: Sun, 17-Jan-2038 19:14:07 GMT', true );
		header( 'Content-Length: ' . $file->getSize(), true ); 
		readfile( $file->getPath() );
	}
}
