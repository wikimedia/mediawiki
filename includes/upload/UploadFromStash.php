<?php
/**
 * Backend for uploading files from previously stored file.
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
 * @ingroup Upload
 */

/**
 * Implements uploading from previously stored file.
 *
 * @ingroup Upload
 * @author Bryan Tong Minh
 */
class UploadFromStash extends UploadBase {
	protected $mFileKey;
	protected $mVirtualTempPath;
	protected $mFileProps;
	protected $mSourceType;

	// an instance of UploadStash
	private $stash;

	// LocalFile repo
	private $repo;

	/**
	 * @param User|bool $user Default: false
	 * @param UploadStash|bool $stash Default: false
	 * @param FileRepo|bool $repo Default: false
	 */
	public function __construct( $user = false, $stash = false, $repo = false ) {
		// user object. sometimes this won't exist, as when running from cron.
		$this->user = $user;

		if ( $repo ) {
			$this->repo = $repo;
		} else {
			$this->repo = RepoGroup::singleton()->getLocalRepo();
		}

		if ( $stash ) {
			$this->stash = $stash;
		} else {
			if ( $user ) {
				wfDebug( __METHOD__ . " creating new UploadStash instance for " . $user->getId() . "\n" );
			} else {
				wfDebug( __METHOD__ . " creating new UploadStash instance with no user\n" );
			}

			$this->stash = new UploadStash( $this->repo, $this->user );
		}
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public static function isValidKey( $key ) {
		// this is checked in more detail in UploadStash
		return (bool)preg_match( UploadStash::KEY_FORMAT_REGEX, $key );
	}

	/**
	 * @param WebRequest $request
	 * @return bool
	 */
	public static function isValidRequest( $request ) {
		// this passes wpSessionKey to getText() as a default when wpFileKey isn't set.
		// wpSessionKey has no default which guarantees failure if both are missing
		// (though that should have been caught earlier)
		return self::isValidKey( $request->getText( 'wpFileKey', $request->getText( 'wpSessionKey' ) ) );
	}

	/**
	 * @param string $key
	 * @param string $name
	 * @param bool $initTempFile
	 */
	public function initialize( $key, $name = 'upload_file', $initTempFile = true ) {
		/**
		 * Confirming a temporarily stashed upload.
		 * We don't want path names to be forged, so we keep
		 * them in the session on the server and just give
		 * an opaque key to the user agent.
		 */
		$metadata = $this->stash->getMetadata( $key );
		$this->initializePathInfo( $name,
			$initTempFile ? $this->getRealPath( $metadata['us_path'] ) : false,
			$metadata['us_size'],
			false
		);

		$this->mFileKey = $key;
		$this->mVirtualTempPath = $metadata['us_path'];
		$this->mFileProps = $this->stash->getFileProps( $key );
		$this->mSourceType = $metadata['us_source_type'];
	}

	/**
	 * @param WebRequest $request
	 */
	public function initializeFromRequest( &$request ) {
		// sends wpSessionKey as a default when wpFileKey is missing
		$fileKey = $request->getText( 'wpFileKey', $request->getText( 'wpSessionKey' ) );

		// chooses one of wpDestFile, wpUploadFile, filename in that order.
		$desiredDestName = $request->getText(
			'wpDestFile',
			$request->getText( 'wpUploadFile', $request->getText( 'filename' ) )
		);

		$this->initialize( $fileKey, $desiredDestName );
	}

	/**
	 * @return string
	 */
	public function getSourceType() {
		return $this->mSourceType;
	}

	/**
	 * Get the base 36 SHA1 of the file
	 * @return string
	 */
	public function getTempFileSha1Base36() {
		return $this->mFileProps['sha1'];
	}

	/*
	 * protected function verifyFile() inherited
	 */

	/**
	 * Stash the file.
	 *
	 * @param User $user
	 * @return UploadStashFile
	 */
	public function stashFile( User $user = null ) {
		// replace mLocalFile with an instance of UploadStashFile, which adds some methods
		// that are useful for stashed files.
		$this->mLocalFile = parent::stashFile( $user );

		return $this->mLocalFile;
	}

	/**
	 * This should return the key instead of the UploadStashFile instance, for backward compatibility.
	 * @return string
	 */
	public function stashSession() {
		return $this->stashFile()->getFileKey();
	}

	/**
	 * Remove a temporarily kept file stashed by saveTempUploadedFile().
	 * @return bool Success
	 */
	public function unsaveUploadedFile() {
		return $this->stash->removeFile( $this->mFileKey );
	}

	/**
	 * Remove the database record after a successful upload.
	 */
	public function postProcessUpload() {
		parent::postProcessUpload();
		$this->unsaveUploadedFile();
	}
}
