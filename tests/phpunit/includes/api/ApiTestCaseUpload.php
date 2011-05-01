<?php

/**
 *  * Abstract class to support upload tests
 */

abstract class ApiTestCaseUpload extends ApiTestCase {
	/**
	 * Fixture -- run before every test
	 */
	public function setUp() {
		global $wgEnableUploads, $wgEnableAPI;
		parent::setUp();

		$wgEnableUploads = true;
		$wgEnableAPI = true;
		wfSetupSession();

		$this->clearFakeUploads();
	}

	/**
	 * Helper function -- remove files and associated articles by Title
	 * @param $title Title: title to be removed
	 */
	public function deleteFileByTitle( $title ) {
		if ( $title->exists() ) {
			$file = wfFindFile( $title, array( 'ignoreRedirect' => true ) );
			$noOldArchive = ""; // yes this really needs to be set this way
			$comment = "removing for test";
			$restrictDeletedVersions = false;
			$status = FileDeleteForm::doDelete( $title, $file, $noOldArchive, $comment, $restrictDeletedVersions );
			if ( !$status->isGood() ) {
				return false;
			}
			$article = new Article( $title );
			$article->doDeleteArticle( "removing for test" );

			// see if it now doesn't exist; reload
			$title = Title::newFromText( $title->getText(), NS_FILE );
		}
		return ! ( $title && $title instanceof Title && $title->exists() );
	}

	/**
	 * Helper function -- remove files and associated articles with a particular filename
	 * @param $fileName String: filename to be removed
	 */
	public function deleteFileByFileName( $fileName ) {
		return $this->deleteFileByTitle( Title::newFromText( $fileName, NS_FILE ) );
	}


	/**
	 * Helper function -- given a file on the filesystem, find matching content in the db (and associated articles) and remove them.
	 * @param $filePath String: path to file on the filesystem
	 */
	public function deleteFileByContent( $filePath ) {
		$hash = File::sha1Base36( $filePath );
		$dupes = RepoGroup::singleton()->findBySha1( $hash );
		$success = true;
		foreach ( $dupes as $dupe ) {
			$success &= $this->deleteFileByTitle( $dupe->getTitle() );
		}
		return $success;
	}

	/**
	 * Fake an upload by dumping the file into temp space, and adding info to $_FILES.
	 * (This is what PHP would normally do).
	 * @param $fieldName String: name this would have in the upload form
	 * @param $fileName String: name to title this
	 * @param $type String: mime type
	 * @param $filePath String: path where to find file contents
	 */
	function fakeUploadFile( $fieldName, $fileName, $type, $filePath ) {
		$tmpName = tempnam( wfTempDir(), "" );
		if ( !file_exists( $filePath ) ) {
			throw new Exception( "$filePath doesn't exist!" );
		};

		if ( !copy( $filePath, $tmpName ) ) {
			throw new Exception( "couldn't copy $filePath to $tmpName" );
		}

		clearstatcache();
		$size = filesize( $tmpName );
		if ( $size === false ) {
			throw new Exception( "couldn't stat $tmpName" );
		}

		$_FILES[ $fieldName ] = array(
			'name'		=> $fileName,
			'type'		=> $type,
			'tmp_name' 	=> $tmpName,
			'size' 		=> $size,
			'error'		=> null
		);

		return true;

	}

	/**
	 * Remove traces of previous fake uploads
	 */
	function clearFakeUploads() {
		$_FILES = array();
	}




}
