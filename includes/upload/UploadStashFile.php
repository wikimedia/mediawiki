<?php
/**
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
 */

/**
 * @ingroup Upload
 */
class UploadStashFile extends UnregisteredLocalFile {
	private $fileKey;
	private $urlName;
	protected $url;

	/**
	 * A LocalFile wrapper around a file that has been temporarily stashed,
	 * so we can do things like create thumbnails for it. Arguably
	 * UnregisteredLocalFile should be handling its own file repo but that
	 * class is a bit retarded currently.
	 *
	 * @param FileRepo $repo Repository where we should find the path
	 * @param string $path Path to file
	 * @param string $key Key to store the path and any stashed data under
	 * @throws UploadStashBadPathException
	 * @throws UploadStashFileNotFoundException
	 */
	public function __construct( $repo, $path, $key ) {
		$this->fileKey = $key;

		// resolve mwrepo:// urls
		if ( FileRepo::isVirtualUrl( $path ) ) {
			$path = $repo->resolveVirtualUrl( $path );
		} else {
			// check if path appears to be sane, no parent traversals,
			// and is in this repo's temp zone.
			$repoTempPath = $repo->getZonePath( 'temp' );
			if ( ( !$repo->validateFilename( $path ) ) ||
				( strpos( $path, $repoTempPath ) !== 0 )
			) {
				wfDebug( "UploadStash: tried to construct an UploadStashFile "
					. "from a file that should already exist at '$path', but path is not valid\n" );
				throw new UploadStashBadPathException(
					wfMessage( 'uploadstash-bad-path-invalid' )
				);
			}

			// check if path exists! and is a plain file.
			if ( !$repo->fileExists( $path ) ) {
				wfDebug( "UploadStash: tried to construct an UploadStashFile from "
					. "a file that should already exist at '$path', but path is not found\n" );
				throw new UploadStashFileNotFoundException(
					wfMessage( 'uploadstash-file-not-found-not-exists' )
				);
			}
		}

		parent::__construct( false, $repo, $path, false );

		$this->name = basename( $this->path );
	}

	/**
	 * A method needed by the file transforming and scaling routines in File.php
	 * We do not necessarily care about doing the description at this point
	 * However, we also can't return the empty string, as the rest of MediaWiki
	 * demands this (and calls to imagemagick convert require it to be there)
	 *
	 * @return string Dummy value
	 */
	public function getDescriptionUrl() {
		return $this->getUrl();
	}

	/**
	 * Get the path for the thumbnail (actually any transformation of this file)
	 * The actual argument is the result of thumbName although we seem to have
	 * buggy code elsewhere that expects a boolean 'suffix'
	 *
	 * @param string $thumbName Name of thumbnail (e.g. "120px-123456.jpg" ),
	 *   or false to just get the path
	 * @return string Path thumbnail should take on filesystem, or containing
	 *   directory if thumbname is false
	 */
	public function getThumbPath( $thumbName = false ) {
		$path = dirname( $this->path );
		if ( $thumbName !== false ) {
			$path .= "/$thumbName";
		}

		return $path;
	}

	/**
	 * Return the file/url base name of a thumbnail with the specified parameters.
	 * We override this because we want to use the pretty url name instead of the
	 * ugly file name.
	 *
	 * @param array $params Handler-specific parameters
	 * @param int $flags Bitfield that supports THUMB_* constants
	 * @return string|null Base name for URL, like '120px-12345.jpg', or null if there is no handler
	 */
	function thumbName( $params, $flags = 0 ) {
		return $this->generateThumbName( $this->getUrlName(), $params );
	}

	/**
	 * Helper function -- given a 'subpage', return the local URL,
	 * e.g. /wiki/Special:UploadStash/subpage
	 * @param string $subPage
	 * @return string Local URL for this subpage in the Special:UploadStash space.
	 */
	private function getSpecialUrl( $subPage ) {
		return SpecialPage::getTitleFor( 'UploadStash', $subPage )->getLocalURL();
	}

	/**
	 * Get a URL to access the thumbnail
	 * This is required because the model of how files work requires that
	 * the thumbnail urls be predictable. However, in our model the URL is
	 * not based on the filename (that's hidden in the db)
	 *
	 * @param string $thumbName Basename of thumbnail file -- however, we don't
	 *   want to use the file exactly
	 * @return string URL to access thumbnail, or URL with partial path
	 */
	public function getThumbUrl( $thumbName = false ) {
		wfDebug( __METHOD__ . " getting for $thumbName \n" );

		return $this->getSpecialUrl( 'thumb/' . $this->getUrlName() . '/' . $thumbName );
	}

	/**
	 * The basename for the URL, which we want to not be related to the filename.
	 * Will also be used as the lookup key for a thumbnail file.
	 *
	 * @return string Base url name, like '120px-123456.jpg'
	 */
	public function getUrlName() {
		if ( !$this->urlName ) {
			$this->urlName = $this->fileKey;
		}

		return $this->urlName;
	}

	/**
	 * Return the URL of the file, if for some reason we wanted to download it
	 * We tend not to do this for the original file, but we do want thumb icons
	 *
	 * @return string Url
	 */
	public function getUrl() {
		if ( !isset( $this->url ) ) {
			$this->url = $this->getSpecialUrl( 'file/' . $this->getUrlName() );
		}

		return $this->url;
	}

	/**
	 * Parent classes use this method, for no obvious reason, to return the path
	 * (relative to wiki root, I assume). But with this class, the URL is
	 * unrelated to the path.
	 *
	 * @return string Url
	 */
	public function getFullUrl() {
		return $this->getUrl();
	}

	/**
	 * Getter for file key (the unique id by which this file's location &
	 * metadata is stored in the db)
	 *
	 * @return string File key
	 */
	public function getFileKey() {
		return $this->fileKey;
	}

	/**
	 * Remove the associated temporary file
	 * @return bool Success
	 */
	public function remove() {
		if ( !$this->repo->fileExists( $this->path ) ) {
			// Maybe the file's already been removed? This could totally happen in UploadBase.
			return true;
		}

		return $this->repo->freeTemp( $this->path );
	}

	public function exists() {
		return $this->repo->fileExists( $this->path );
	}
}
