<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Upload;

use MediaWiki\FileRepo\File\UnregisteredLocalFile;
use MediaWiki\FileRepo\FileRepo;
use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Upload\Exception\UploadStashBadPathException;
use MediaWiki\Upload\Exception\UploadStashFileNotFoundException;

/**
 * @ingroup Upload
 */
class UploadStashFile extends UnregisteredLocalFile {
	/** @var string */
	private $fileKey;
	/** @var string|null Lazy set as in-memory cache */
	protected $url;
	/** @var string|null */
	private $sha1;

	/**
	 * A LocalFile wrapper around a file that has been temporarily stashed,
	 * so we can do things like create thumbnails for it.
	 *
	 * @param LocalRepo $repo Repository where we should find the path
	 * @param string $path Path to file
	 * @param string $key Key to store the path and any stashed data under
	 * @param string|null $sha1 SHA1 of file. Will calculate if not set
	 * @param string|false $mime Mime type of file. Will calculate if not set
	 * @throws UploadStashBadPathException
	 * @throws UploadStashFileNotFoundException
	 */
	public function __construct( $repo, $path, $key, $sha1 = null, $mime = false ) {
		$this->fileKey = $key;
		$this->sha1 = $sha1;

		// Resolve mwrepo:// URLs
		if ( FileRepo::isVirtualUrl( $path ) ) {
			$path = $repo->resolveVirtualUrl( $path );
		} else {
			// Check if the path appears to be correct, no parent traversals,
			// and is in this repo's temp zone.
			$repoTempPath = $repo->getZonePath( 'temp' );
			if ( ( !$repo->validateFilename( $path ) ) ||
				!str_starts_with( $path, $repoTempPath )
			) {
				wfDebug( "UploadStash: tried to construct an UploadStashFile "
					. "from a file that should already exist at '$path', but path is not valid" );
				throw new UploadStashBadPathException(
					wfMessage( 'uploadstash-bad-path-invalid' )
				);
			}

			// Check if path exists and is a plain file.
			if ( !$repo->fileExists( $path ) ) {
				wfDebug( "UploadStash: tried to construct an UploadStashFile from "
					. "a file that should already exist at '$path', but path is not found" );
				throw new UploadStashFileNotFoundException(
					wfMessage( 'uploadstash-file-not-found-not-exists' )
				);
			}
		}

		parent::__construct( false, $repo, $path, $mime );

		$this->name = basename( $this->path );
	}

	/** @inheritDoc */
	public function getSha1() {
		if ( !$this->sha1 ) {
			$this->sha1 = parent::getSha1();
		}
		return $this->sha1;
	}

	/**
	 * A method needed by the file transforming and scaling routines in File.php.
	 * We do not necessarily care about doing the description at this point.
	 * However, we also can't return the empty string, as the rest of MediaWiki
	 * demands this (and calls to ImageMagick convert require it to be there).
	 *
	 * FIXME: The comment above refers to a bug which was fixed in 2010.
	 *  It should return false per the base class doc comment.
	 *
	 * @return string Dummy value
	 */
	public function getDescriptionUrl() {
		return $this->getUrl();
	}

	/** @inheritDoc */
	public function getThumbPath( $thumbName = false ) {
		$path = dirname( $this->path );
		if ( $thumbName !== false ) {
			$path .= "/$thumbName";
		}

		return $path;
	}

	/**
	 * Return the file/URL base name of a thumbnail with the specified parameters.
	 * We override this because we want to use the pretty url name instead of the
	 * ugly file name.
	 *
	 * @param array $params Handler-specific parameters
	 * @param int $flags
	 * @return string|false
	 */
	public function thumbName( $params, $flags = 0 ) {
		return $this->generateThumbName( $this->getUrlName(), $params );
	}

	/**
	 * Helper function -- given a 'subpage', return the local URL,
	 * e.g. /wiki/Special:UploadStash/subpage
	 *
	 * @param string $subPage
	 * @return string
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
	 * @param string|false $thumbName Basename of thumbnail file -- however, we don't
	 *   want to use the file exactly
	 * @return string URL to access thumbnail, or URL with partial path
	 */
	public function getThumbUrl( $thumbName = false ) {
		wfDebug( __METHOD__ . " getting for $thumbName" );

		return $this->getSpecialUrl( 'thumb/' . $this->getUrlName() . '/' . $thumbName );
	}

	/**
	 * The basename for the URL, which we want to not be related to the filename.
	 * Will also be used as the lookup key for a thumbnail file.
	 *
	 * @return string Base url name, like '1cumins7kwkw.9xoht5.2158737.jpg'
	 */
	public function getUrlName() {
		return $this->fileKey;
	}

	/** @inheritDoc */
	public function getUrl() {
		if ( $this->url === null ) {
			$this->url = $this->getSpecialUrl( 'file/' . $this->getUrlName() );
		}

		return $this->url;
	}

	/**
	 * Parent classes use this method, for no obvious reason, to return the path
	 * (relative to wiki root, I assume). But with this class, the URL is
	 * unrelated to the path.
	 *
	 * FIXME: incorrect assumption -- should not override
	 *
	 * @return string URL
	 */
	public function getFullUrl() {
		return $this->getUrl();
	}

	/**
	 * Get the file key
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

	/** @inheritDoc */
	public function exists() {
		return $this->repo->fileExists( $this->path );
	}
}

/** @deprecated class alias since 1.46 */
class_alias( UploadStashFile::class, 'UploadStashFile' );
