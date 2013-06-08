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
	var $mImages, $mShowBytes, $mShowFilename, $mMode;
	var $mCaption = false;

	/**
	 * Hide blacklisted images?
	 */
	var $mHideBadImages;

	/**
	 * Registered parser object for output callbacks
	 * @var Parser
	 */
	var $mParser;

	/**
	 * Contextual title, used when images are being screened
	 * against the bad image list
	 */
	protected $contextTitle = false;

	protected $mAttribs = array();

	static private $modeMapping = false;

	/**
	 * Get a new image gallery. This is the method other callers
	 * should use to get a gallery.
	 *
	 * @param String|bool $mode Mode to use. False to use the default.
	 */
	static function factory( $mode = false ) {
		global $wgGalleryOptions, $wgContLang;
		self::loadModes();
		if ( !$mode ) {
			$mode = $wgGalleryOptions['mode'];
		}

		$mode = $wgContLang->lc( $mode );

		if ( isset( self::$modeMapping[$mode] ) ) {
			return new self::$modeMapping[$mode]( $mode );
		} else {
			throw new MWException( "No gallery class registered for mode $mode" );
		}
	}

	static private function loadModes() {
		if ( self::$modeMapping === false ) {
			self::$modeMapping = array(
				'traditional' => 'TraditionalImageGallery',
				'nolines' => 'NolinesImageGallery',
				'packed' => 'PackedImageGallery',
				'packed-hover' => 'PackedHoverImageGallery',
				'packed-overlay' => 'PackedOverlayImageGallery',
			);
			// Allow extensions to make a new gallery format.
			wfRunHooks( 'GalleryGetModes', self::$modeMapping );
		}
	}

	/**
	 * Create a new image gallery object.
	 *
	 * You should not call this directly, but instead use
	 * ImageGalleryBase::factory().
	 */
	function __construct( $mode = 'traditional' ) {
		global $wgGalleryOptions;
		$this->mImages = array();
		$this->mShowBytes = $wgGalleryOptions['showBytes'];
		$this->mShowFilename = true;
		$this->mParser = false;
		$this->mHideBadImages = false;
		$this->mPerRow = $wgGalleryOptions['imagesPerRow'];
		$this->mWidths = $wgGalleryOptions['imageWidth'];
		$this->mHeights = $wgGalleryOptions['imageHeight'];
		$this->mCaptionLength = $wgGalleryOptions['captionLength'];
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
	 * @param $parser Parser
	 */
	function setParser( $parser ) {
		$this->mParser = $parser;
	}

	/**
	 * Set bad image flag
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
	 * @param $num Integer >= 0; If perrow=0 the gallery layout will adapt to screensize
	 * invalid numbers will be rejected
	 */
	public function setPerRow( $num ) {
		if ( $num >= 0 ) {
			$this->mPerRow = (int)$num;
		}
	}

	/**
	 * Set how wide each image will be, in pixels.
	 *
	 * @param $num Integer > 0; invalid numbers will be ignored
	 */
	public function setWidths( $num ) {
		if ( $num > 0 ) {
			$this->mWidths = (int)$num;
		}
	}

	/**
	 * Set how high each image will be, in pixels.
	 *
	 * @param $num Integer > 0; invalid numbers will be ignored
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
	 * @param Array $options Attributes of gallery tag.
	 */
	public function setAdditionalOptions( $options ) { }

	/**
	 * Instruct the class to use a specific skin for rendering
	 *
	 * @param $skin Skin object
	 * @deprecated since 1.18 Not used anymore
	 */
	function useSkin( $skin ) {
		wfDeprecated( __METHOD__, '1.18' );
		/* no op */
	}

	/**
	 * Add an image to the gallery.
	 *
	 * @param $title Title object of the image that is added to the gallery
	 * @param $html  String: Additional HTML text to be shown. The name and size of the image are always shown.
	 * @param $alt   String: Alt text for the image
	 * @param $link  String: Override image link (optional)
	 * @param $handlerOpts Array: Array of options for image handler (aka page number)
	 */
	function add( $title, $html = '', $alt = '', $link = '', $handlerOpts = array() ) {
		if ( $title instanceof File ) {
			// Old calling convention
			$title = $title->getTitle();
		}
		$this->mImages[] = array( $title, $html, $alt, $link, $handlerOpts );
		wfDebug( 'ImageGallery::add ' . $title->getText() . "\n" );
	}

	/**
	 * Add an image at the beginning of the gallery.
	 *
	 * @param $title Title object of the image that is added to the gallery
	 * @param $html  String: Additional HTML text to be shown. The name and size of the image are always shown.
	 * @param $alt   String: Alt text for the image
	 * @param $link  String: Override image link (optional)
	 * @param $handlerOpts Array: Array of options for image handler (aka page number)
	 */
	function insert( $title, $html = '', $alt = '', $link = '', $handlerOpts = array() ) {
		if ( $title instanceof File ) {
			// Old calling convention
			$title = $title->getTitle();
		}
		array_unshift( $this->mImages, array( &$title, $html, $alt, $link, $handlerOpts ) );
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
	 * @param $f Boolean: set to false to disable.
	 */
	function setShowBytes( $f ) {
		$this->mShowBytes = (bool)$f;
	}

	/**
	 * Enable/Disable showing of the filename of an image in the gallery.
	 * Enabled by default.
	 *
	 * @param $f Boolean: set to false to disable.
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
	 * @param array $attribs of HTML attribute pairs
	 */
	function setAttributes( $attribs ) {
		$this->mAttribs = $attribs;
	}

	/**
	 * Display an html representation of the gallery
	 *
	 * @return String The html
	 */
	abstract public function toHTML();

	/**
	 * @return Integer: number of images in the gallery
	 */
	public function count() {
		return count( $this->mImages );
	}

	/**
	 * Set the contextual title
	 *
	 * @param $title Title: contextual title
	 */
	public function setContextTitle( $title ) {
		$this->contextTitle = $title;
	}

	/**
	 * Get the contextual title, if applicable
	 *
	 * @return mixed Title or false
	 */
	public function getContextTitle() {
		return is_object( $this->contextTitle ) && $this->contextTitle instanceof Title
			? $this->contextTitle
			: false;
	}

	/**
	 * Determines the correct language to be used for this image gallery
	 * @return Language object
	 */
	protected function getRenderLang() {
		return $this->mParser
			? $this->mParser->getTargetLanguage()
			: $this->getLanguage();
	}

	/* Old constants no longer used.
	const THUMB_PADDING = 30;
	const GB_PADDING = 5;
	const GB_BORDERS = 8;
	*/

}

