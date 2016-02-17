<?php

/**
 * Abstract class to support upload tests
 */
abstract class ApiTestCaseUpload extends ApiTestCase {
	/**
	 * Fixture -- run before every test
	 */
	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( [
			'wgEnableUploads' => true,
			'wgEnableAPI' => true,
		] );

		$this->clearFakeUploads();
	}

	/**
	 * Helper function -- remove files and associated articles by Title
	 *
	 * @param Title $title Title to be removed
	 *
	 * @return bool
	 */
	public function deleteFileByTitle( $title ) {
		if ( $title->exists() ) {
			$file = wfFindFile( $title, [ 'ignoreRedirect' => true ] );
			$noOldArchive = ""; // yes this really needs to be set this way
			$comment = "removing for test";
			$restrictDeletedVersions = false;
			$status = FileDeleteForm::doDelete(
				$title,
				$file,
				$noOldArchive,
				$comment,
				$restrictDeletedVersions
			);

			if ( !$status->isGood() ) {
				return false;
			}

			$page = WikiPage::factory( $title );
			$page->doDeleteArticle( "removing for test" );

			// see if it now doesn't exist; reload
			$title = Title::newFromText( $title->getText(), NS_FILE );
		}

		return !( $title && $title instanceof Title && $title->exists() );
	}

	/**
	 * Helper function -- remove files and associated articles with a particular filename
	 *
	 * @param string $fileName Filename to be removed
	 *
	 * @return bool
	 */
	public function deleteFileByFileName( $fileName ) {
		return $this->deleteFileByTitle( Title::newFromText( $fileName, NS_FILE ) );
	}

	/**
	 * Helper function -- given a file on the filesystem, find matching
	 * content in the db (and associated articles) and remove them.
	 *
	 * @param string $filePath Path to file on the filesystem
	 *
	 * @return bool
	 */
	public function deleteFileByContent( $filePath ) {
		$hash = FSFile::getSha1Base36FromPath( $filePath );
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
	 *
	 * @param string $fieldName Name this would have in the upload form
	 * @param string $fileName Name to title this
	 * @param string $type MIME type
	 * @param string $filePath Path where to find file contents
	 *
	 * @throws Exception
	 * @return bool
	 */
	function fakeUploadFile( $fieldName, $fileName, $type, $filePath ) {
		$tmpName = $this->getNewTempFile();
		if ( !file_exists( $filePath ) ) {
			throw new Exception( "$filePath doesn't exist!" );
		}

		if ( !copy( $filePath, $tmpName ) ) {
			throw new Exception( "couldn't copy $filePath to $tmpName" );
		}

		clearstatcache();
		$size = filesize( $tmpName );
		if ( $size === false ) {
			throw new Exception( "couldn't stat $tmpName" );
		}

		$_FILES[$fieldName] = [
			'name' => $fileName,
			'type' => $type,
			'tmp_name' => $tmpName,
			'size' => $size,
			'error' => null
		];

		return true;
	}

	function fakeUploadChunk( $fieldName, $fileName, $type, & $chunkData ) {
		$tmpName = $this->getNewTempFile();
		// copy the chunk data to temp location:
		if ( !file_put_contents( $tmpName, $chunkData ) ) {
			throw new Exception( "couldn't copy chunk data to $tmpName" );
		}

		clearstatcache();
		$size = filesize( $tmpName );
		if ( $size === false ) {
			throw new Exception( "couldn't stat $tmpName" );
		}

		$_FILES[$fieldName] = [
			'name' => $fileName,
			'type' => $type,
			'tmp_name' => $tmpName,
			'size' => $size,
			'error' => null
		];
	}

	/**
	 * Remove traces of previous fake uploads
	 */
	function clearFakeUploads() {
		$_FILES = [];
	}
}
