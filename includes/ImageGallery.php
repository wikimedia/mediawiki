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

	static function factory( $mode = false ) {
		global $wgGalleryOptions;
		self::loadModes();
		if ( !$mode ) {
			$mode = $wgGalleryOptions['mode'];
		}

		if ( isset( self::$modeMapping[$mode] ) ) {
			return new self::$modeMapping[$mode]( $mode );
		} else {
			throw new MWException( "No gallery class registered for mode $mode" );
		}
	}

	static private function loadModes() {
		if ( self::$modeMapping === false ) {
			self::$modeMapping = array(
				'standard' => 'StandardImageGallery',
				'nolines' => 'StandardImageGallery',
				'height-constrained' => 'HeightConstrainedImageGallery',
				'height-constrained-overlay' => 'HeightConstrainedOverlayGallery',
				'height-constrained-static' => 'HeightConstrainedStaticGallery',
				// Packed, even bordered, one that is boxy but overlay like facebook.
			);
			wfRunHooks( 'GalleryGetModes', self::$modeMapping );
		}
	}

	/**
	 * Create a new image gallery object.
	 */
	function __construct( $mode = 'standard' ) {
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
	 */
	function add( $title, $html = '', $alt = '', $link = '' ) {
		if ( $title instanceof File ) {
			// Old calling convention
			$title = $title->getTitle();
		}
		$this->mImages[] = array( $title, $html, $alt, $link );
		wfDebug( 'ImageGallery::add ' . $title->getText() . "\n" );
	}

	/**
	 * Add an image at the beginning of the gallery.
	 *
	 * @param $title Title object of the image that is added to the gallery
	 * @param $html  String: Additional HTML text to be shown. The name and size of the image are always shown.
	 * @param $alt   String: Alt text for the image
	 */
	function insert( $title, $html = '', $alt = '' ) {
		if ( $title instanceof File ) {
			// Old calling convention
			$title = $title->getTitle();
		}
		array_unshift( $this->mImages, array( &$title, $html, $alt ) );
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
		global $wgLang;
		return $this->mParser
			? $this->mParser->getTargetLanguage()
			: $wgLang;
	}

	/* Old constants no longer used.
	const THUMB_PADDING = 30;
	const GB_PADDING = 5;
	const GB_BORDERS = 8;
	*/

} //class


class StandardImageGallery extends ImageGalleryBase {


	/**
	 * Return a HTML representation of the image gallery
	 *
	 * For each image in the gallery, display
	 * - a thumbnail
	 * - the image name
	 * - the additional text provided when adding the image
	 * - the size of the image
	 *
	 * @return string
	 */
	function toHTML() {
		if ( $this->mPerRow > 0 ) {
			$maxwidth = $this->mPerRow * ( $this->mWidths + $this->getAllPadding() );
			$oldStyle = isset( $this->mAttribs['style'] ) ? $this->mAttribs['style'] : '';
			# _width is ignored by any sane browser. IE6 doesn't know max-width so it uses _width instead
			$this->mAttribs['style'] = "max-width: {$maxwidth}px;_width: {$maxwidth}px;" . $oldStyle;
		}

		$attribs = Sanitizer::mergeAttributes(
			array( 'class' => 'gallery mw-gallery-' . $this->mMode ), $this->mAttribs );

		$modules = $this->getModules();

		if ( $this->mParser ) {
			$this->mParser->getOutput()->addModules( $modules );
		} else {
			$this->getOutput()->addModules( $modules );
		}

		$output = Xml::openElement( 'ul', $attribs );
		if ( $this->mCaption ) {
			$output .= "\n\t<li class='gallerycaption'>{$this->mCaption}</li>";
		}

		$lang = $this->getRenderLang();
		# Output each image...
		foreach ( $this->mImages as $pair ) {
			$nt = $pair[0];
			$text = $pair[1]; # "text" means "caption" here
			$alt = $pair[2];
			$link = $pair[3];

			$descQuery = false;
			if ( $nt->getNamespace() == NS_FILE ) {
				# Get the file...
				if ( $this->mParser instanceof Parser ) {
					# Give extensions a chance to select the file revision for us
					$options = array();
					wfRunHooks( 'BeforeParserFetchFileAndTitle',
						array( $this->mParser, $nt, &$options, &$descQuery ) );
					# Fetch and register the file (file title may be different via hooks)
					list( $img, $nt ) = $this->mParser->fetchFileAndTitle( $nt, $options );
				} else {
					$img = wfFindFile( $nt );
				}
			} else {
				$img = false;
			}

			$params = $this->getThumbParams( $img );

			$thumb = false;

			if ( !$img ) {
				# We're dealing with a non-image, spit out the name and be done with it.
				$thumbhtml = "\n\t\t\t" . '<div class="thumb" style="height: ' . ( $this->getThumbPadding() + $this->mHeights ) . 'px;">'
					. htmlspecialchars( $nt->getText() ) . '</div>';
			} elseif ( $this->mHideBadImages && wfIsBadImage( $nt->getDBkey(), $this->getContextTitle() ) ) {
				# The image is blacklisted, just show it as a text link.
				$thumbhtml = "\n\t\t\t" . '<div class="thumb" style="height: ' . ( $this->getThumbPadding() + $this->mHeights ) . 'px;">' .
					Linker::link(
						$nt,
						htmlspecialchars( $nt->getText() ),
						array(),
						array(),
						array( 'known', 'noclasses' )
					) .
					'</div>';
			} elseif ( !( $thumb = $img->transform( $params ) ) ) {
				# Error generating thumbnail.
				$thumbhtml = "\n\t\t\t" . '<div class="thumb" style="height: ' . ( self::THUMB_PADDING + $this->mHeights ) . 'px;">'
					. htmlspecialchars( $img->getLastError() ) . '</div>';
			} else {
				$vpad = $this->getVPad( $this->mHeights, $thumb->height );

				$imageParameters = array(
					'desc-link' => true,
					'desc-query' => $descQuery,
					'alt' => $alt,
					'custom-url-link' => $link
				);
				# In the absence of both alt text and caption, fall back on providing screen readers with the filename as alt text
				if ( $alt == '' && $text == '' ) {
					$imageParameters['alt'] = $nt->getText();
				}

				$this->adjustImageParameters( $thumb, $imageParameters );

				# Set both fixed width and min-height.
				$thumbhtml = "\n\t\t\t" .
					'<div class="thumb" style="width: ' . $this->getThumbDivWidth( $thumb->width ) . 'px;">'
					# Auto-margin centering for block-level elements. Needed now that we have video
					# handlers since they may emit block-level elements as opposed to simple <img> tags.
					# ref http://css-discuss.incutio.com/?page=CenteringBlockElement
					. '<div style="margin:' . $vpad . 'px auto;">'
					. $thumb->toHtml( $imageParameters ) . '</div></div>';

				// Call parser transform hook
				if ( $this->mParser && $img->getHandler() ) {
					$img->getHandler()->parserTransformHook( $this->mParser, $img );
				}
			}

			//TODO
			// $linkTarget = Title::newFromText( $wgContLang->getNsText( MWNamespace::getUser() ) . ":{$ut}" );
			// $ul = Linker::link( $linkTarget, $ut );

			if ( $this->mShowBytes ) {
				if ( $img ) {
					$fileSize = htmlspecialchars( $lang->formatSize( $img->getSize() ) );
				} else {
					$fileSize = wfMessage( 'filemissing' )->escaped();
				}
				$fileSize = "$fileSize<br />\n";
			} else {
				$fileSize = '';
			}

			$textlink = $this->mShowFilename ?
				Linker::link(
					$nt,
					htmlspecialchars( $lang->truncate( $nt->getText(), $this->mCaptionLength ) ),
					array(),
					array(),
					array( 'known', 'noclasses' )
				) . "<br />\n" :
				'';


			$galleryText  = $textlink . $text . $fileSize;
			$galleryText = $this->wrapGalleryText( $galleryText, $thumb );

			# Weird double wrapping (the extra div inside the li) needed due to FF2 bug
			# Can be safely removed if FF2 falls completely out of existence
			$output .=
				"\n\t\t" . '<li class="gallerybox" style="width: ' . $this->getGBWidth( $thumb ) . 'px">'
					. '<div style="width: ' . $this->getGBWidth( $thumb ) . 'px">'
					. $thumbhtml
					. $galleryText
					. "\n\t\t</div></li>";
		}
		$output .= "\n</ul>";

		return $output;
	}


	/**
	 * Add the wrapper html around the thumb's caption
	 *
	 * @param String $galleryText The caption
	 * @param MTO?|boolean FIXME $thumb The thumb this caption is for or false for bad image.
	 */
	protected function wrapGalleryText( $galleryText, $thumb ) {
		# ATTENTION: The newline after <div class="gallerytext"> is needed to accommodate htmltidy which
		# in version 4.8.6 generated crackpot html in its absence, see:
		# http://bugzilla.wikimedia.org/show_bug.cgi?id=1765 -Ævar

		return "\n\t\t\t" . '<div class="gallerytext">' . "\n"
					. $galleryText
					. "\n\t\t\t</div>";
		
	}

	protected function getThumbPadding() {
		switch( $this->mMode ) {
		case 'standard':
		default:
			return 30;
		case 'nolines':
			return 0;
		}
	}
	protected function getGBPadding() {
		return 5;
	}
	// 2px borders on each side + 2px implied padding on each side
	protected function getGBBorders() {
		switch ($this->mMode) {
		case 'nolines':
			return 0;
		case 'standard':
		default:
			return 8;
		}
	}
	protected function getAllPadding() {
		return $this->getThumbPadding() + $this->getGBPadding() + $this->getGBBorders();
	}
	protected function getVPad( $boxHeight, $thumbHeight ) {
		switch ($this->mMode) {
		case 'standard':
		default:
			return ( $this->getThumbPadding() + $boxHeight - $thumbHeight ) / 2;
		case 'nolines':
			return 0;
		}
	}

	/**
	 * Get the transform parameters for a thumbnail.
	 *
	 * @param File $img The file in question. May be false for invalid image
	 */
	protected function getThumbParams( $img ) {
		return array(
			'width' => $this->mWidths,
			'height' => $this->mHeights
		);
	}

	protected function getThumbDivWidth( $thumbWidth ) {
		return $this->mWidths + $this->getThumbPadding();
	}
	/**
	 * Imporant: parameter will be false if no thumb used.
	 * @param MTO??(FIXME) $thumb thumb object or false.
	 */
	protected function getGBWidth( $thumb ) {
		return $this->mWidths + $this->getThumbPadding() + $this->getGBPadding();
	}

	/**
	 * Get a list of modules to include in the page.
	 *
	 * @return Array modules to include
	 */
	protected function getModules() {
		return array();
	}

	/**
	 * Adjust the image parameters for a thumbnail
	 * @param MediaTransformOutput $thumb The thumbnail
	 * @param Array $imageParameters Array of options
	 */
	protected function adjustImageParameters( $thumb, &$imageParameters ) { }
}

class HeightConstrainedImageGallery extends StandardImageGallery {

	protected function getVPad( $boxHeight, $thumbHeight ) {
		return ( $this->getThumbPadding() + $boxHeight - $thumbHeight/1.5 ) / 2;
	}


	protected function getThumbPadding() {
		return 0;
	}
	protected function getGBPadding() {
		return 2;
	}
	/**
	 * @param File $img The file being transformed. May be false
	 */
	protected function getThumbParams( $img ) {
		if ( $img && $img->getMediaType() === MEDIATYPE_AUDIO ) {
			$width = $this->mWidths;
		} else {
			// We want the width not to be the constraining
			// factor, so use random big number.
			$width = $this->mHeights*10 + 100;
			
		}
		// *1.5 so the js has some room to manipulate sizes.
		return array(
			'width' => $width*1.5,
			'height' => $this->mHeights*1.5,
		);
	}

	protected function getThumbDivWidth( $thumbWidth ) {
		return $thumbWidth/1.5 + $this->getThumbPadding();
	}
	/**
	 * important, $thumb may be false
	 */
	protected function getGBWidth( $thumb ) {
		$thumbWidth = $thumb ? $thumb->getWidth()/1.5 : $this->mWidths;
		return $thumbWidth + $this->getThumbPadding() + $this->getGBPadding();
	}
	protected function adjustImageParameters( $thumb, &$imageParameters ) {
		// Re-adjust back to normal size.
		$imageParameters['override-width'] = intval( $thumb->getWidth() / 1.5 );
		$imageParameters['override-height'] = intval( $thumb->getHeight() / 1.5 );
	}
}

class HeightConstrainedOverlayGallery extends HeightConstrainedStaticGallery {
	// Needed so captions are shown on tab.
	protected function getModules() {
		return array( 'mediawiki.page.gallery' );
		return parent::toHTML();
	}

}
class HeightConstrainedStaticGallery extends HeightConstrainedImageGallery {
	/**
	 * Add the wrapper html around the thumb's caption
	 *
	 * @param String $galleryText The caption
	 * @param MediaTransformOutput|boolean $thumb The thumb this caption is for or false for bad image.
	 */
	protected function wrapGalleryText( $galleryText, $thumb ) {

		// If we have no text, do not output anything to avoid
		// ugly white overlay.
		if ( trim( $galleryText ) === '' ) {
			return '';
		}

		# ATTENTION: The newline after <div class="gallerytext"> is needed to accommodate htmltidy which
		# in version 4.8.6 generated crackpot html in its absence, see:
		# http://bugzilla.wikimedia.org/show_bug.cgi?id=1765 -Ævar

		$thumbWidth = $thumb ? $thumb->width/1.5 : $this->mWidths;
		$captionWidth = intval( $thumbWidth - 20 );

		$outerWrapper = '<div class="gallerytextwrapper" style="width: ' . $captionWidth . 'px">';
		return "\n\t\t\t" . $outerWrapper . '<div class="gallerytext">' . "\n"
					. $galleryText
					. "\n\t\t\t</div>";
		
	}
	
}

/**
 * Backwards compatibility.
 *
 * @deprecated 1.22 Use ImageGalleryBase::factory instead.
 */
class ImageGallery extends StandardImageGallery {
	function __construct( $mode = 'standard' ) {
		wfDeprecated( __METHOD__, '1.22' );
		parent::__construct( $mode );		
	}
}
