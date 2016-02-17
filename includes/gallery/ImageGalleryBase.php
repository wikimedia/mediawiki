<?php
/**
 * Image gallery.
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
 */

/**
 * Image gallery
 *
 * Add images to the gallery using add(), then render that list to HTML using toHTML().
 *
 * @ingroup Media
 */
abstract class ImageGalleryBase extends ContextSource {
	/**
	 * @var array Gallery images
	 */
	protected $mImages;

	/**
	 * @var bool Whether to show the filesize in bytes in categories
	 */
	protected $mShowBytes;

	/**
	 * @var bool Whether to show the filename. Default: true
	 */
	protected $mShowFilename;

	/**
	 * @var string Gallery mode. Default: traditional
	 */
	protected $mMode;

	/**
	 * @var bool|string Gallery caption. Default: false
	 */
	protected $mCaption = false;

	/**
	 * @var bool Hide blacklisted images?
	 */
	protected $mHideBadImages;

	/**
	 * @var Parser Registered parser object for output callbacks
	 */
	public $mParser;

	/**
	 * @var Title Contextual title, used when images are being screened against
	 *   the bad image list
	 */
	protected $contextTitle = false;

	/** @var array */
	protected $mAttribs = [];

	/** @var bool */
	static private $modeMapping = false;

	/**
	 * Get a new image gallery. This is the method other callers
	 * should use to get a gallery.
	 *
	 * @param string|bool $mode Mode to use. False to use the default
	 * @param IContextSource|null $context
	 * @return ImageGalleryBase
	 * @throws MWException
	 */
	static function factory( $mode = false, IContextSource $context = null ) {
		global $wgContLang;
		self::loadModes();
		if ( !$context ) {
			$context = RequestContext::getMainAndWarn( __METHOD__ );
		}
		if ( !$mode ) {
			$galleryOptions = $context->getConfig()->get( 'GalleryOptions' );
			$mode = $galleryOptions['mode'];
		}

		$mode = $wgContLang->lc( $mode );

		if ( isset( self::$modeMapping[$mode] ) ) {
			$class = self::$modeMapping[$mode];
			return new $class( $mode, $context );
		} else {
			throw new MWException( "No gallery class registered for mode $mode" );
		}
	}

	private static function loadModes() {
		if ( self::$modeMapping === false ) {
			self::$modeMapping = [
				'traditional' => 'TraditionalImageGallery',
				'nolines' => 'NolinesImageGallery',
				'packed' => 'PackedImageGallery',
				'packed-hover' => 'PackedHoverImageGallery',
				'packed-overlay' => 'PackedOverlayImageGallery',
			];
			// Allow extensions to make a new gallery format.
			Hooks::run( 'GalleryGetModes', [ &self::$modeMapping ] );
		}
	}

	/**
	 * Create a new image gallery object.
	 *
	 * You should not call this directly, but instead use
	 * ImageGalleryBase::factory().
	 * @param string $mode
	 * @param IContextSource|null $context
	 */
	function __construct( $mode = 'traditional', IContextSource $context = null ) {
		if ( $context ) {
			$this->setContext( $context );
		}

		$galleryOptions = $this->getConfig()->get( 'GalleryOptions' );
		$this->mImages = [];
		$this->mShowBytes = $galleryOptions['showBytes'];
		$this->mShowFilename = true;
		$this->mParser = false;
		$this->mHideBadImages = false;
		$this->mPerRow = $galleryOptions['imagesPerRow'];
		$this->mWidths = $galleryOptions['imageWidth'];
		$this->mHeights = $galleryOptions['imageHeight'];
		$this->mCaptionLength = $galleryOptions['captionLength'];
		$this->mMode = $mode;
	}

	/**
	 * Register a parser object. If you do not set this
	 * and the output of this gallery ends up in parser
	 * cache, the javascript will break!
	 *
	 * @note This also triggers using the page's target
	 *  language instead of the user language.
	 *
	 * @param Parser $parser
	 */
	function setParser( $parser ) {
		$this->mParser = $parser;
	}

	/**
	 * Set bad image flag
	 * @param bool $flag
	 */
	function setHideBadImages( $flag = true ) {
		$this->mHideBadImages = $flag;
	}

	/**
	 * Set the caption (as plain text)
	 *
	 * @param string $caption Caption
	 */
	function setCaption( $caption ) {
		$this->mCaption = htmlspecialchars( $caption );
	}

	/**
	 * Set the caption (as HTML)
	 *
	 * @param string $caption Caption
	 */
	public function setCaptionHtml( $caption ) {
		$this->mCaption = $caption;
	}

	/**
	 * Set how many images will be displayed per row.
	 *
	 * @param int $num Integer >= 0; If perrow=0 the gallery layout will adapt
	 *   to screensize invalid numbers will be rejected
	 */
	public function setPerRow( $num ) {
		if ( $num >= 0 ) {
			$this->mPerRow = (int)$num;
		}
	}

	/**
	 * Set how wide each image will be, in pixels.
	 *
	 * @param int $num Integer > 0; invalid numbers will be ignored
	 */
	public function setWidths( $num ) {
		if ( $num > 0 ) {
			$this->mWidths = (int)$num;
		}
	}

	/**
	 * Set how high each image will be, in pixels.
	 *
	 * @param int $num Integer > 0; invalid numbers will be ignored
	 */
	public function setHeights( $num ) {
		if ( $num > 0 ) {
			$this->mHeights = (int)$num;
		}
	}

	/**
	 * Allow setting additional options. This is meant
	 * to allow extensions to add additional parameters to
	 * <gallery> parser tag.
	 *
	 * @param array $options Attributes of gallery tag
	 */
	public function setAdditionalOptions( $options ) {
	}

	/**
	 * Add an image to the gallery.
	 *
	 * @param Title $title Title object of the image that is added to the gallery
	 * @param string $html Additional HTML text to be shown. The name and size
	 *   of the image are always shown.
	 * @param string $alt Alt text for the image
	 * @param string $link Override image link (optional)
	 * @param array $handlerOpts Array of options for image handler (aka page number)
	 */
	function add( $title, $html = '', $alt = '', $link = '', $handlerOpts = [] ) {
		if ( $title instanceof File ) {
			// Old calling convention
			$title = $title->getTitle();
		}
		$this->mImages[] = [ $title, $html, $alt, $link, $handlerOpts ];
		wfDebug( 'ImageGallery::add ' . $title->getText() . "\n" );
	}

	/**
	 * Add an image at the beginning of the gallery.
	 *
	 * @param Title $title Title object of the image that is added to the gallery
	 * @param string $html Additional HTML text to be shown. The name and size
	 *   of the image are always shown.
	 * @param string $alt Alt text for the image
	 * @param string $link Override image link (optional)
	 * @param array $handlerOpts Array of options for image handler (aka page number)
	 */
	function insert( $title, $html = '', $alt = '', $link = '', $handlerOpts = [] ) {
		if ( $title instanceof File ) {
			// Old calling convention
			$title = $title->getTitle();
		}
		array_unshift( $this->mImages, [ &$title, $html, $alt, $link, $handlerOpts ] );
	}

	/**
	 * Returns the list of images this gallery contains
	 * @return array
	 */
	public function getImages() {
		return $this->mImages;
	}

	/**
	 * isEmpty() returns true if the gallery contains no images
	 * @return bool
	 */
	function isEmpty() {
		return empty( $this->mImages );
	}

	/**
	 * Enable/Disable showing of the file size of an image in the gallery.
	 * Enabled by default.
	 *
	 * @param bool $f Set to false to disable
	 */
	function setShowBytes( $f ) {
		$this->mShowBytes = (bool)$f;
	}

	/**
	 * Enable/Disable showing of the filename of an image in the gallery.
	 * Enabled by default.
	 *
	 * @param bool $f Set to false to disable
	 */
	function setShowFilename( $f ) {
		$this->mShowFilename = (bool)$f;
	}

	/**
	 * Set arbitrary attributes to go on the HTML gallery output element.
	 * Should be suitable for a <ul> element.
	 *
	 * Note -- if taking from user input, you should probably run through
	 * Sanitizer::validateAttributes() first.
	 *
	 * @param array $attribs Array of HTML attribute pairs
	 */
	function setAttributes( $attribs ) {
		$this->mAttribs = $attribs;
	}

	/**
	 * Display an html representation of the gallery
	 *
	 * @return string The html
	 */
	abstract public function toHTML();

	/**
	 * @return int Number of images in the gallery
	 */
	public function count() {
		return count( $this->mImages );
	}

	/**
	 * Set the contextual title
	 *
	 * @param Title $title Contextual title
	 */
	public function setContextTitle( $title ) {
		$this->contextTitle = $title;
	}

	/**
	 * Get the contextual title, if applicable
	 *
	 * @return Title|bool Title or false
	 */
	public function getContextTitle() {
		return is_object( $this->contextTitle ) && $this->contextTitle instanceof Title
			? $this->contextTitle
			: false;
	}

	/**
	 * Determines the correct language to be used for this image gallery
	 * @return Language
	 */
	protected function getRenderLang() {
		return $this->mParser
			? $this->mParser->getTargetLanguage()
			: $this->getLanguage();
	}
}
