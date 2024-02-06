<?php

/**
 * Base class for the output of file transformation methods.
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
 * @ingroup Media
 */

use MediaWiki\FileRepo\File\File;
use MediaWiki\Html\Html;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Status\Status;
use Wikimedia\FileBackend\FileBackend;
use Wikimedia\FileBackend\HTTPFileStreamer;

/**
 * Base class for the output of MediaHandler::doTransform() and File::transform().
 *
 * @stable to extend
 * @ingroup Media
 */
abstract class MediaTransformOutput {
	/** @var array Associative array mapping optional supplementary image files
	 *  from pixel density (eg 1.5 or 2) to additional URLs.
	 */
	public $responsiveUrls = [];

	/** @var File */
	protected $file;

	/** @var int Image width */
	protected $width;

	/** @var int Image height */
	protected $height;

	/** @var string|false URL path to the thumb */
	protected $url;

	/** @var string|false */
	protected $page;

	/** @var string|null|false Filesystem path to the thumb */
	protected $path;

	/** @var string|false Language code, false if not set */
	protected $lang;

	/** @var string|false Permanent storage path */
	protected $storagePath = false;

	/**
	 * @return int Width of the output box
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * @return int Height of the output box
	 */
	public function getHeight() {
		return $this->height;
	}

	/**
	 * @return File
	 */
	public function getFile() {
		return $this->file;
	}

	/**
	 * Get the final extension of the thumbnail.
	 * Returns false for scripted transformations.
	 * @stable to override
	 *
	 * @return string|false
	 */
	public function getExtension() {
		return $this->path ? FileBackend::extensionFromPath( $this->path ) : false;
	}

	/**
	 * @stable to override
	 *
	 * @return string|false The thumbnail URL
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * @stable to override
	 *
	 * @return string|false The permanent thumbnail storage path
	 */
	public function getStoragePath() {
		return $this->storagePath;
	}

	/**
	 * @stable to override
	 *
	 * @param string $storagePath The permanent storage path
	 * @return void
	 */
	public function setStoragePath( $storagePath ) {
		$this->storagePath = $storagePath;
		if ( $this->path === false ) {
			$this->path = $storagePath;
		}
	}

	/**
	 * Fetch HTML for this transform output
	 *
	 * @param array $options Associative array of options. Boolean options
	 *     should be indicated with a value of true for true, and false or
	 *     absent for false.
	 *
	 *     alt          Alternate text or caption
	 *     desc-link    Boolean, show a description link
	 *     file-link    Boolean, show a file download link
	 *     custom-url-link    Custom URL to link to
	 *     custom-title-link  Custom Title object to link to
	 *     valign       vertical-align property, if the output is an inline element
	 *     img-class    Class applied to the "<img>" tag, if there is such a tag
	 *
	 * For images, desc-link and file-link are implemented as a click-through. For
	 * sounds and videos, they may be displayed in other ways.
	 *
	 * @return string
	 */
	abstract public function toHtml( $options = [] );

	/**
	 * This will be overridden to return true in error classes
	 * @return bool
	 */
	public function isError() {
		return false;
	}

	/**
	 * Check if an output thumbnail file actually exists.
	 *
	 * This will return false if there was an error, the
	 * thumbnail is to be handled client-side only, or if
	 * transformation was deferred via TRANSFORM_LATER.
	 * This file may exist as a new file in /tmp, a file
	 * in permanent storage, or even refer to the original.
	 *
	 * @return bool
	 */
	public function hasFile() {
		// If TRANSFORM_LATER, $this->path will be false.
		// Note: a null path means "use the source file".
		return ( !$this->isError() && ( $this->path || $this->path === null ) );
	}

	/**
	 * Check if the output thumbnail is the same as the source.
	 * This can occur if the requested width was bigger than the source.
	 *
	 * @return bool
	 */
	public function fileIsSource() {
		return ( !$this->isError() && $this->path === null );
	}

	/**
	 * Get the path of a file system copy of the thumbnail.
	 * Callers should never write to this path.
	 *
	 * @return string|false Returns false if there isn't one
	 */
	public function getLocalCopyPath() {
		if ( $this->isError() ) {
			return false;
		}

		if ( $this->path === null ) {
			return $this->file->getLocalRefPath(); // assume thumb was not scaled
		}
		if ( FileBackend::isStoragePath( $this->path ) ) {
			$be = $this->file->getRepo()->getBackend();
			// The temp file will be process cached by FileBackend
			$fsFile = $be->getLocalReference( [ 'src' => $this->path ] );

			return $fsFile ? $fsFile->getPath() : false;
		}
		return $this->path; // may return false
	}

	/**
	 * Stream the file if there were no errors
	 *
	 * @param array $headers Additional HTTP headers to send on success
	 * @return Status
	 * @since 1.27
	 */
	public function streamFileWithStatus( $headers = [] ) {
		if ( !$this->path ) {
			return Status::newFatal( 'backend-fail-stream', '<no path>' );
		}

		$repo = $this->file->getRepo();

		if ( $repo && FileBackend::isStoragePath( $this->path ) ) {
			return Status::wrap(
				$repo->getBackend()->streamFile(
					[ 'src' => $this->path, 'headers' => $headers, ]
				)
			);
		} else {
			$streamer = new HTTPFileStreamer(
				$this->getLocalCopyPath(),
				$repo ? $repo->getBackend()->getStreamerOptions() : []
			);

			$success = $streamer->stream( $headers );
			return $success ? Status::newGood()
				: Status::newFatal( 'backend-fail-stream', $this->path );
		}
	}

	/**
	 * Stream the file if there were no errors
	 *
	 * @deprecated since 1.26, use streamFileWithStatus
	 * @param array $headers Additional HTTP headers to send on success
	 * @return bool Success
	 */
	public function streamFile( $headers = [] ) {
		return $this->streamFileWithStatus( $headers )->isOK();
	}

	/**
	 * Wrap some XHTML text in an anchor tag with the given attributes
	 * or, fallback to a span in the absence thereof.
	 *
	 * @param array $linkAttribs
	 * @param string $contents
	 * @return string
	 */
	protected function linkWrap( $linkAttribs, $contents ) {
		if ( isset( $linkAttribs['href'] ) ) {
			return Html::rawElement( 'a', $linkAttribs, $contents );
		}
		$parserEnableLegacyMediaDOM = MediaWikiServices::getInstance()
			->getMainConfig()->get( MainConfigNames::ParserEnableLegacyMediaDOM );
		if ( $parserEnableLegacyMediaDOM ) {
			return $contents;
		}
		return Html::rawElement( 'span', $linkAttribs ?: null, $contents );
	}

	/**
	 * @param string|null $title
	 * @param string|array $params Query parameters to add
	 * @return array
	 */
	public function getDescLinkAttribs( $title = null, $params = [] ) {
		if ( is_array( $params ) ) {
			$query = $params;
		} else {
			$query = [];
		}
		if ( $this->page && $this->page !== 1 ) {
			$query['page'] = $this->page;
		}
		if ( $this->lang ) {
			$query['lang'] = $this->lang;
		}

		if ( is_string( $params ) && $params !== '' ) {
			$query = $params . '&' . wfArrayToCgi( $query );
		}

		$attribs = [
			'href' => $this->file->getTitle()->getLocalURL( $query ),
		];

		$parserEnableLegacyMediaDOM = MediaWikiServices::getInstance()
			->getMainConfig()->get( MainConfigNames::ParserEnableLegacyMediaDOM );
		if ( $parserEnableLegacyMediaDOM ) {
			$attribs['class'] = 'image';
		} else {
			$attribs['class'] = 'mw-file-description';
		}

		if ( $title ) {
			$attribs['title'] = $title;
		}

		return $attribs;
	}
}
