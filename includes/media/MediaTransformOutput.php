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

/**
 * Base class for the output of MediaHandler::doTransform() and File::transform().
 *
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

	/** @var string URL path to the thumb */
	protected $url;

	/** @var bool|string */
	protected $page;

	/** @var bool|string Filesystem path to the thumb */
	protected $path;

	/** @var bool|string Language code, false if not set */
	protected $lang;

	/** @var bool|string Permanent storage path */
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
	 * @return string|bool
	 */
	public function getExtension() {
		return $this->path ? FileBackend::extensionFromPath( $this->path ) : false;
	}

	/**
	 * @return string|bool The thumbnail URL
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * @return string|bool The permanent thumbnail storage path
	 */
	public function getStoragePath() {
		return $this->storagePath;
	}

	/**
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
	 * @return string|bool Returns false if there isn't one
	 */
	public function getLocalCopyPath() {
		if ( $this->isError() ) {
			return false;
		} elseif ( $this->path === null ) {
			return $this->file->getLocalRefPath(); // assume thumb was not scaled
		} elseif ( FileBackend::isStoragePath( $this->path ) ) {
			$be = $this->file->getRepo()->getBackend();
			// The temp file will be process cached by FileBackend
			$fsFile = $be->getLocalReference( [ 'src' => $this->path ] );

			return $fsFile ? $fsFile->getPath() : false;
		} else {
			return $this->path; // may return false
		}
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
		} elseif ( FileBackend::isStoragePath( $this->path ) ) {
			$be = $this->file->getRepo()->getBackend();
			return $be->streamFile( [ 'src' => $this->path, 'headers' => $headers ] );
		} else { // FS-file
			$success = StreamFile::stream( $this->getLocalCopyPath(), $headers );
			return $success ? Status::newGood() : Status::newFatal( 'backend-fail-stream', $this->path );
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
		$this->streamFileWithStatus( $headers )->isOK();
	}

	/**
	 * Wrap some XHTML text in an anchor tag with the given attributes
	 *
	 * @param array $linkAttribs
	 * @param string $contents
	 * @return string
	 */
	protected function linkWrap( $linkAttribs, $contents ) {
		if ( $linkAttribs ) {
			return Xml::tags( 'a', $linkAttribs, $contents );
		} else {
			return $contents;
		}
	}

	/**
	 * @param string $title
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
			'class' => 'image',
		];
		if ( $title ) {
			$attribs['title'] = $title;
		}

		return $attribs;
	}
}

/**
 * Media transform output for images
 *
 * @ingroup Media
 */
class ThumbnailImage extends MediaTransformOutput {
	/**
	 * Get a thumbnail object from a file and parameters.
	 * If $path is set to null, the output file is treated as a source copy.
	 * If $path is set to false, no output file will be created.
	 * $parameters should include, as a minimum, (file) 'width' and 'height'.
	 * It may also include a 'page' parameter for multipage files.
	 *
	 * @param File $file
	 * @param string $url URL path to the thumb
	 * @param string|bool $path Filesystem path to the thumb
	 * @param array $parameters Associative array of parameters
	 */
	function __construct( $file, $url, $path = false, $parameters = [] ) {
		# Previous parameters:
		#   $file, $url, $width, $height, $path = false, $page = false

		$defaults = [
			'page' => false,
			'lang' => false
		];

		if ( is_array( $parameters ) ) {
			$actualParams = $parameters + $defaults;
		} else {
			# Using old format, should convert. Later a warning could be added here.
			$numArgs = func_num_args();
			$actualParams = [
				'width' => $path,
				'height' => $parameters,
				'page' => ( $numArgs > 5 ) ? func_get_arg( 5 ) : false
			] + $defaults;
			$path = ( $numArgs > 4 ) ? func_get_arg( 4 ) : false;
		}

		$this->file = $file;
		$this->url = $url;
		$this->path = $path;

		# These should be integers when they get here.
		# If not, there's a bug somewhere.  But let's at
		# least produce valid HTML code regardless.
		$this->width = round( $actualParams['width'] );
		$this->height = round( $actualParams['height'] );

		$this->page = $actualParams['page'];
		$this->lang = $actualParams['lang'];
	}

	/**
	 * Return HTML <img ... /> tag for the thumbnail, will include
	 * width and height attributes and a blank alt text (as required).
	 *
	 * @param array $options Associative array of options. Boolean options
	 *     should be indicated with a value of true for true, and false or
	 *     absent for false.
	 *
	 *     alt          HTML alt attribute
	 *     title        HTML title attribute
	 *     desc-link    Boolean, show a description link
	 *     file-link    Boolean, show a file download link
	 *     valign       vertical-align property, if the output is an inline element
	 *     img-class    Class applied to the \<img\> tag, if there is such a tag
	 *     desc-query   String, description link query params
	 *     override-width     Override width attribute. Should generally not set
	 *     override-height    Override height attribute. Should generally not set
	 *     no-dimensions      Boolean, skip width and height attributes (useful if
	 *                        set in CSS)
	 *     custom-url-link    Custom URL to link to
	 *     custom-title-link  Custom Title object to link to
	 *     custom target-link Value of the target attribute, for custom-target-link
	 *     parser-extlink-*   Attributes added by parser for external links:
	 *          parser-extlink-rel: add rel="nofollow"
	 *          parser-extlink-target: link target, but overridden by custom-target-link
	 *
	 * For images, desc-link and file-link are implemented as a click-through. For
	 * sounds and videos, they may be displayed in other ways.
	 *
	 * @throws MWException
	 * @return string
	 */
	function toHtml( $options = [] ) {
		if ( count( func_get_args() ) == 2 ) {
			throw new MWException( __METHOD__ . ' called in the old style' );
		}

		$alt = isset( $options['alt'] ) ? $options['alt'] : '';

		$query = isset( $options['desc-query'] ) ? $options['desc-query'] : '';

		$attribs = [
			'alt' => $alt,
			'src' => $this->url,
		];

		if ( !empty( $options['custom-url-link'] ) ) {
			$linkAttribs = [ 'href' => $options['custom-url-link'] ];
			if ( !empty( $options['title'] ) ) {
				$linkAttribs['title'] = $options['title'];
			}
			if ( !empty( $options['custom-target-link'] ) ) {
				$linkAttribs['target'] = $options['custom-target-link'];
			} elseif ( !empty( $options['parser-extlink-target'] ) ) {
				$linkAttribs['target'] = $options['parser-extlink-target'];
			}
			if ( !empty( $options['parser-extlink-rel'] ) ) {
				$linkAttribs['rel'] = $options['parser-extlink-rel'];
			}
		} elseif ( !empty( $options['custom-title-link'] ) ) {
			/** @var Title $title */
			$title = $options['custom-title-link'];
			$linkAttribs = [
				'href' => $title->getLinkURL(),
				'title' => empty( $options['title'] ) ? $title->getFullText() : $options['title']
			];
		} elseif ( !empty( $options['desc-link'] ) ) {
			$linkAttribs = $this->getDescLinkAttribs(
				empty( $options['title'] ) ? null : $options['title'],
				$query
			);
		} elseif ( !empty( $options['file-link'] ) ) {
			$linkAttribs = [ 'href' => $this->file->getUrl() ];
		} else {
			$linkAttribs = false;
			if ( !empty( $options['title'] ) ) {
				$attribs['title'] = $options['title'];
			}
		}

		if ( empty( $options['no-dimensions'] ) ) {
			$attribs['width'] = $this->width;
			$attribs['height'] = $this->height;
		}
		if ( !empty( $options['valign'] ) ) {
			$attribs['style'] = "vertical-align: {$options['valign']}";
		}
		if ( !empty( $options['img-class'] ) ) {
			$attribs['class'] = $options['img-class'];
		}
		if ( isset( $options['override-height'] ) ) {
			$attribs['height'] = $options['override-height'];
		}
		if ( isset( $options['override-width'] ) ) {
			$attribs['width'] = $options['override-width'];
		}

		// Additional densities for responsive images, if specified.
		// If any of these urls is the same as src url, it'll be excluded.
		$responsiveUrls = array_diff( $this->responsiveUrls, [ $this->url ] );
		if ( !empty( $responsiveUrls ) ) {
			$attribs['srcset'] = Html::srcSet( $responsiveUrls );
		}

		Hooks::run( 'ThumbnailBeforeProduceHTML', [ $this, &$attribs, &$linkAttribs ] );

		return $this->linkWrap( $linkAttribs, Xml::element( 'img', $attribs ) );
	}
}

/**
 * Basic media transform error class
 *
 * @ingroup Media
 */
class MediaTransformError extends MediaTransformOutput {
	/** @var Message */
	private $msg;

	function __construct( $msg, $width, $height /*, ... */ ) {
		$args = array_slice( func_get_args(), 3 );
		$this->msg = wfMessage( $msg )->params( $args );
		$this->width = intval( $width );
		$this->height = intval( $height );
		$this->url = false;
		$this->path = false;
	}

	function toHtml( $options = [] ) {
		return "<div class=\"MediaTransformError\" style=\"" .
			"width: {$this->width}px; height: {$this->height}px; display:inline-block;\">" .
			$this->getHtmlMsg() .
			"</div>";
	}

	function toText() {
		return $this->msg->text();
	}

	function getHtmlMsg() {
		return $this->msg->escaped();
	}

	function getMsg() {
		return $this->msg;
	}

	function isError() {
		return true;
	}

	function getHttpStatusCode() {
		return 500;
	}
}

/**
 * Shortcut class for parameter validation errors
 *
 * @ingroup Media
 */
class TransformParameterError extends MediaTransformError {
	function __construct( $params ) {
		parent::__construct( 'thumbnail_error',
			max( isset( $params['width'] ) ? $params['width'] : 0, 120 ),
			max( isset( $params['height'] ) ? $params['height'] : 0, 120 ),
			wfMessage( 'thumbnail_invalid_params' )
		);
	}

	function getHttpStatusCode() {
		return 400;
	}
}

/**
 * Shortcut class for parameter file size errors
 *
 * @ingroup Media
 * @since 1.25
 */
class TransformTooBigImageAreaError extends MediaTransformError {
	function __construct( $params, $maxImageArea ) {
		$msg = wfMessage( 'thumbnail_toobigimagearea' );
		$msg->rawParams(
			$msg->getLanguage()->formatComputingNumbers( $maxImageArea, 1000, "size-$1pixel" )
		);

		parent::__construct( 'thumbnail_error',
			max( isset( $params['width'] ) ? $params['width'] : 0, 120 ),
			max( isset( $params['height'] ) ? $params['height'] : 0, 120 ),
			$msg
		);
	}

	function getHttpStatusCode() {
		return 400;
	}
}
