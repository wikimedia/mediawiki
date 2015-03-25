<?php
/**
 * Implements Special:UploadStash.
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
 * @ingroup SpecialPage
 * @ingroup Upload
 */

/**
 * Web access for files temporarily stored by UploadStash.
 *
 * For example -- files that were uploaded with the UploadWizard extension are stored temporarily
 * before committing them to the db. But we want to see their thumbnails and get other information
 * about them.
 *
 * Since this is based on the user's session, in effect this creates a private temporary file area.
 * However, the URLs for the files cannot be shared.
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
	const MAX_SERVE_BYTES = 1048576; // 1MB

	public function __construct() {
		parent::__construct( 'UploadStash', 'upload' );
	}

	/**
	 * Execute page -- can output a file directly or show a listing of them.
	 *
	 * @param string $subPage Subpage, e.g. in
	 *   http://example.com/wiki/Special:UploadStash/foo.jpg, the "foo.jpg" part
	 * @return bool Success
	 */
	public function execute( $subPage ) {
		$this->stash = RepoGroup::singleton()->getLocalRepo()->getUploadStash( $this->getUser() );
		$this->checkPermissions();

		if ( $subPage === null || $subPage === '' ) {
			return $this->showUploads();
		}

		return $this->showUpload( $subPage );
	}

	/**
	 * If file available in stash, cats it out to the client as a simple HTTP response.
	 * n.b. Most sanity checking done in UploadStashLocalFile, so this is straightforward.
	 *
	 * @param string $key The key of a particular requested file
	 * @throws HttpError
	 * @return bool
	 */
	public function showUpload( $key ) {
		// prevent callers from doing standard HTML output -- we'll take it from here
		$this->getOutput()->disable();

		try {
			$params = $this->parseKey( $key );
			if ( $params['type'] === 'thumb' ) {
				return $this->outputThumbFromStash( $params['file'], $params['params'] );
			} else {
				return $this->outputLocalFile( $params['file'] );
			}
		} catch ( UploadStashFileNotFoundException $e ) {
			$code = 404;
			$message = $e->getMessage();
		} catch ( UploadStashZeroLengthFileException $e ) {
			$code = 500;
			$message = $e->getMessage();
		} catch ( UploadStashBadPathException $e ) {
			$code = 500;
			$message = $e->getMessage();
		} catch ( SpecialUploadStashTooLargeException $e ) {
			$code = 500;
			$message = 'Cannot serve a file larger than ' . self::MAX_SERVE_BYTES .
				' bytes. ' . $e->getMessage();
		} catch ( Exception $e ) {
			$code = 500;
			$message = $e->getMessage();
		}

		throw new HttpError( $code, $message );
	}

	/**
	 * Parse the key passed to the SpecialPage. Returns an array containing
	 * the associated file object, the type ('file' or 'thumb') and if
	 * application the transform parameters
	 *
	 * @param string $key
	 * @throws UploadStashBadPathException
	 * @return array
	 */
	private function parseKey( $key ) {
		$type = strtok( $key, '/' );

		if ( $type !== 'file' && $type !== 'thumb' ) {
			throw new UploadStashBadPathException( "Unknown type '$type'" );
		}
		$fileName = strtok( '/' );
		$thumbPart = strtok( '/' );
		$file = $this->stash->getFile( $fileName );
		if ( $type === 'thumb' ) {
			$srcNamePos = strrpos( $thumbPart, $fileName );
			if ( $srcNamePos === false || $srcNamePos < 1 ) {
				throw new UploadStashBadPathException( 'Unrecognized thumb name' );
			}
			$paramString = substr( $thumbPart, 0, $srcNamePos - 1 );

			$handler = $file->getHandler();
			if ( $handler ) {
				$params = $handler->parseParamString( $paramString );

				return array( 'file' => $file, 'type' => $type, 'params' => $params );
			} else {
				throw new UploadStashBadPathException( 'No handler found for ' .
					"mime {$file->getMimeType()} of file {$file->getPath()}" );
			}
		}

		return array( 'file' => $file, 'type' => $type );
	}

	/**
	 * Get a thumbnail for file, either generated locally or remotely, and stream it out
	 *
	 * @param File $file
	 * @param array $params
	 *
	 * @return bool Success
	 */
	private function outputThumbFromStash( $file, $params ) {
		$flags = 0;
		// this config option, if it exists, points to a "scaler", as you might find in
		// the Wikimedia Foundation cluster. See outputRemoteScaledThumb(). This
		// is part of our horrible NFS-based system, we create a file on a mount
		// point here, but fetch the scaled file from somewhere else that
		// happens to share it over NFS.
		if ( $this->getConfig()->get( 'UploadStashScalerBaseUrl' ) ) {
			$this->outputRemoteScaledThumb( $file, $params, $flags );
		} else {
			$this->outputLocallyScaledThumb( $file, $params, $flags );
		}
	}

	/**
	 * Scale a file (probably with a locally installed imagemagick, or similar)
	 * and output it to STDOUT.
	 * @param File $file
	 * @param array $params Scaling parameters ( e.g. array( width => '50' ) );
	 * @param int $flags Scaling flags ( see File:: constants )
	 * @throws MWException|UploadStashFileNotFoundException
	 * @return bool Success
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
		if ( !$thumbnailImage->getStoragePath() ) {
			throw new UploadStashFileNotFoundException( "no local path for scaled item" );
		}

		// now we should construct a File, so we can get MIME and other such info in a standard way
		// n.b. MIME type may be different from original (ogx original -> jpeg thumb)
		$thumbFile = new UnregisteredLocalFile( false,
			$this->stash->repo, $thumbnailImage->getStoragePath(), false );
		if ( !$thumbFile ) {
			throw new UploadStashFileNotFoundException( "couldn't create local file object for thumbnail" );
		}

		return $this->outputLocalFile( $thumbFile );
	}

	/**
	 * Scale a file with a remote "scaler", as exists on the Wikimedia Foundation
	 * cluster, and output it to STDOUT.
	 * Note: Unlike the usual thumbnail process, the web client never sees the
	 * cluster URL; we do the whole HTTP transaction to the scaler ourselves
	 * and cat the results out.
	 * Note: We rely on NFS to have propagated the file contents to the scaler.
	 * However, we do not rely on the thumbnail being created in NFS and then
	 * propagated back to our filesystem. Instead we take the results of the
	 * HTTP request instead.
	 * Note: No caching is being done here, although we are instructing the
	 * client to cache it forever.
	 *
	 * @param File $file
	 * @param array $params Scaling parameters ( e.g. array( width => '50' ) );
	 * @param int $flags Scaling flags ( see File:: constants )
	 * @throws MWException
	 * @return bool Success
	 */
	private function outputRemoteScaledThumb( $file, $params, $flags ) {
		// This option probably looks something like
		// 'http://upload.wikimedia.org/wikipedia/test/thumb/temp'. Do not use
		// trailing slash.
		$scalerBaseUrl = $this->getConfig()->get( 'UploadStashScalerBaseUrl' );

		if ( preg_match( '/^\/\//', $scalerBaseUrl ) ) {
			// this is apparently a protocol-relative URL, which makes no sense in this context,
			// since this is used for communication that's internal to the application.
			// default to http.
			$scalerBaseUrl = wfExpandUrl( $scalerBaseUrl, PROTO_CANONICAL );
		}

		// We need to use generateThumbName() instead of thumbName(), because
		// the suffix needs to match the file name for the remote thumbnailer
		// to work
		$scalerThumbName = $file->generateThumbName( $file->getName(), $params );
		$scalerThumbUrl = $scalerBaseUrl . '/' . $file->getUrlRel() .
			'/' . rawurlencode( $scalerThumbName );

		// make a curl call to the scaler to create a thumbnail
		$httpOptions = array(
			'method' => 'GET',
			'timeout' => 5 // T90599 attempt to time out cleanly
		);
		$req = MWHttpRequest::factory( $scalerThumbUrl, $httpOptions, __METHOD__ );
		$status = $req->execute();
		if ( !$status->isOK() ) {
			$errors = $status->getErrorsArray();
			$errorStr = "Fetching thumbnail failed: " . print_r( $errors, 1 );
			$errorStr .= "\nurl = $scalerThumbUrl\n";
			throw new MWException( $errorStr );
		}
		$contentType = $req->getResponseHeader( "content-type" );
		if ( !$contentType ) {
			throw new MWException( "Missing content-type header" );
		}

		return $this->outputContents( $req->getContent(), $contentType );
	}

	/**
	 * Output HTTP response for file
	 * Side effect: writes HTTP response to STDOUT.
	 *
	 * @param File $file File object with a local path (e.g. UnregisteredLocalFile,
	 *   LocalFile. Oddly these don't share an ancestor!)
	 * @throws SpecialUploadStashTooLargeException
	 * @return bool
	 */
	private function outputLocalFile( File $file ) {
		if ( $file->getSize() > self::MAX_SERVE_BYTES ) {
			throw new SpecialUploadStashTooLargeException();
		}

		return $file->getRepo()->streamFile( $file->getPath(),
			array( 'Content-Transfer-Encoding: binary',
				'Expires: Sun, 17-Jan-2038 19:14:07 GMT' )
		);
	}

	/**
	 * Output HTTP response of raw content
	 * Side effect: writes HTTP response to STDOUT.
	 * @param string $content Content
	 * @param string $contentType MIME type
	 * @throws SpecialUploadStashTooLargeException
	 * @return bool
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
	 * @todo Unsure about encoding as binary; if we received from HTTP perhaps
	 * we should use that encoding, concatenated with semicolon to `$contentType` as it
	 * usually is.
	 * Side effect: preps PHP to write headers to STDOUT.
	 * @param string $contentType String suitable for content-type header
	 * @param string $size Length in bytes
	 */
	private static function outputFileHeaders( $contentType, $size ) {
		header( "Content-Type: $contentType", true );
		header( 'Content-Transfer-Encoding: binary', true );
		header( 'Expires: Sun, 17-Jan-2038 19:14:07 GMT', true );
		// Bug 53032 - It shouldn't be a problem here, but let's be safe and not cache
		header( 'Cache-Control: private' );
		header( "Content-Length: $size", true );
	}

	/**
	 * Static callback for the HTMLForm in showUploads, to process
	 * Note the stash has to be recreated since this is being called in a static context.
	 * This works, because there really is only one stash per logged-in user, despite appearances.
	 *
	 * @param array $formData
	 * @param HTMLForm $form
	 * @return Status
	 */
	public static function tryClearStashedUploads( $formData, $form ) {
		if ( isset( $formData['Clear'] ) ) {
			$stash = RepoGroup::singleton()->getLocalRepo()->getUploadStash( $form->getUser() );
			wfDebug( 'stash has: ' . print_r( $stash->listFiles(), true ) . "\n" );

			if ( !$stash->clear() ) {
				return Status::newFatal( 'uploadstash-errclear' );
			}
		}

		return Status::newGood();
	}

	/**
	 * Default action when we don't have a subpage -- just show links to the uploads we have,
	 * Also show a button to clear stashed files
	 * @return bool
	 */
	private function showUploads() {
		// sets the title, etc.
		$this->setHeaders();
		$this->outputHeader();

		// create the form, which will also be used to execute a callback to process incoming form data
		// this design is extremely dubious, but supposedly HTMLForm is our standard now?

		$context = new DerivativeContext( $this->getContext() );
		$context->setTitle( $this->getPageTitle() ); // Remove subpage
		$form = new HTMLForm( array(
			'Clear' => array(
				'type' => 'hidden',
				'default' => true,
				'name' => 'clear',
			)
		), $context, 'clearStashedUploads' );
		$form->setSubmitCallback( array( __CLASS__, 'tryClearStashedUploads' ) );
		$form->setSubmitTextMsg( 'uploadstash-clear' );

		$form->prepareForm();
		$formResult = $form->tryAuthorizedSubmit();

		// show the files + form, if there are any, or just say there are none
		$refreshHtml = Html::element( 'a',
			array( 'href' => $this->getPageTitle()->getLocalURL() ),
			$this->msg( 'uploadstash-refresh' )->text() );
		$files = $this->stash->listFiles();
		if ( $files && count( $files ) ) {
			sort( $files );
			$fileListItemsHtml = '';
			foreach ( $files as $file ) {
				// TODO: Use Linker::link or even construct the list in plain wikitext
				$fileListItemsHtml .= Html::rawElement( 'li', array(),
					Html::element( 'a', array( 'href' =>
						$this->getPageTitle( "file/$file" )->getLocalURL() ), $file )
				);
			}
			$this->getOutput()->addHtml( Html::rawElement( 'ul', array(), $fileListItemsHtml ) );
			$form->displayForm( $formResult );
			$this->getOutput()->addHtml( Html::rawElement( 'p', array(), $refreshHtml ) );
		} else {
			$this->getOutput()->addHtml( Html::rawElement( 'p', array(),
				Html::element( 'span', array(), $this->msg( 'uploadstash-nofiles' )->text() )
				. ' '
				. $refreshHtml
			) );
		}

		return true;
	}
}

class SpecialUploadStashTooLargeException extends MWException {
}
