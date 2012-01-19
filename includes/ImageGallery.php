<?php
if ( ! defined( 'MEDIAWIKI' ) )
	die( 1 );

/**
 * Image gallery
 *
 * Add images to the gallery using add(), then render that list to HTML using toHTML().
 *
 * @ingroup Media
 */
class ImageGallery {
	var $mImages, $mShowBytes, $mShowFilename;
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

	/**
	 * Fixed margins
	 */
	const THUMB_PADDING = 30;
	const GB_PADDING = 5;
	// 2px borders on each side + 2px implied padding on each side
	const GB_BORDERS = 8;

	/**
	 * Create a new image gallery object.
	 */
	function __construct() {
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
	}

	/**
	 * Register a parser object
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
	 * @param $caption Caption
	 */
	function setCaption( $caption ) {
		$this->mCaption = htmlspecialchars( $caption );
	}

	/**
	 * Set the caption (as HTML)
	 *
	 * @param $caption String: Caption
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
	 */
	function add( $title, $html = '', $alt = '' ) {
		if ( $title instanceof File ) {
			// Old calling convention
			$title = $title->getTitle();
		}
		$this->mImages[] = array( $title, $html, $alt );
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
	 * @param $attribs Array of HTML attribute pairs
	 */
	function setAttributes( $attribs ) {
		$this->mAttribs = $attribs;
	}

	/**
	 * Return a HTML representation of the image gallery
	 *
	 * For each image in the gallery, display
	 * - a thumbnail
	 * - the image name
	 * - the additional text provided when adding the image
	 * - the size of the image
	 *
	 */
	function toHTML() {
		global $wgLang;

		if ( $this->mPerRow > 0 ) {
			$maxwidth = $this->mPerRow * ( $this->mWidths + self::THUMB_PADDING + self::GB_PADDING + self::GB_BORDERS );
			$oldStyle = isset( $this->mAttribs['style'] ) ? $this->mAttribs['style'] : '';
			# _width is ignored by any sane browser. IE6 doesn't know max-width so it uses _width instead
			$this->mAttribs['style'] = "max-width: {$maxwidth}px;_width: {$maxwidth}px;" . $oldStyle;
		}

		$attribs = Sanitizer::mergeAttributes(
			array( 'class' => 'gallery' ), $this->mAttribs );

		$output = Xml::openElement( 'ul', $attribs );
		if ( $this->mCaption ) {
			$output .= "\n\t<li class='gallerycaption'>{$this->mCaption}</li>";
		}

		$params = array(
			'width' => $this->mWidths,
			'height' => $this->mHeights
		);
		# Output each image...
		foreach ( $this->mImages as $pair ) {
			$nt = $pair[0];
			$text = $pair[1]; # "text" means "caption" here
			$alt = $pair[2];

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

			if( !$img ) {
				# We're dealing with a non-image, spit out the name and be done with it.
				$thumbhtml = "\n\t\t\t" . '<div style="height: ' . ( self::THUMB_PADDING + $this->mHeights ) . 'px;">'
					. htmlspecialchars( $nt->getText() ) . '</div>';
			} elseif( $this->mHideBadImages && wfIsBadImage( $nt->getDBkey(), $this->getContextTitle() ) ) {
				# The image is blacklisted, just show it as a text link.
				$thumbhtml = "\n\t\t\t" . '<div style="height: ' . ( self::THUMB_PADDING + $this->mHeights ) . 'px;">' .
					Linker::link(
						$nt,
						htmlspecialchars( $nt->getText() ),
						array(),
						array(),
						array( 'known', 'noclasses' )
					) .
					'</div>';
			} elseif( !( $thumb = $img->transform( $params ) ) ) {
				# Error generating thumbnail.
				$thumbhtml = "\n\t\t\t" . '<div style="height: ' . ( self::THUMB_PADDING + $this->mHeights ) . 'px;">'
					. htmlspecialchars( $img->getLastError() ) . '</div>';
			} else {
				$vpad = ( self::THUMB_PADDING + $this->mHeights - $thumb->height ) /2;

				$imageParameters = array(
					'desc-link' => true,
					'desc-query' => $descQuery,
					'alt' => $alt,
				);
				# In the absence of both alt text and caption, fall back on providing screen readers with the filename as alt text
				if ( $alt == '' && $text == '' ) {
					$imageParameters['alt'] = $nt->getText();
				}

				# Set both fixed width and min-height.
				$thumbhtml = "\n\t\t\t" .
					'<div class="thumb" style="width: ' . ( $this->mWidths + self::THUMB_PADDING ) . 'px;">'
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

			if( $this->mShowBytes ) {
				if( $img ) {
					$fileSize = htmlspecialchars( $wgLang->formatSize( $img->getSize() ) );
				} else {
					$fileSize = wfMsgHtml( 'filemissing' );
				}
				$fileSize = "$fileSize<br />\n";
			} else {
				$fileSize = '';
			}

			$textlink = $this->mShowFilename ?
				Linker::link(
					$nt,
					htmlspecialchars( $wgLang->truncate( $nt->getText(), $this->mCaptionLength ) ),
					array(),
					array(),
					array( 'known', 'noclasses' )
				) . "<br />\n" :
				'' ;

			# ATTENTION: The newline after <div class="gallerytext"> is needed to accommodate htmltidy which
			# in version 4.8.6 generated crackpot html in its absence, see:
			# http://bugzilla.wikimedia.org/show_bug.cgi?id=1765 -Ævar

			# Weird double wrapping (the extra div inside the li) needed due to FF2 bug
			# Can be safely removed if FF2 falls completely out of existance
			$output .=
				"\n\t\t" . '<li class="gallerybox" style="width: ' . ( $this->mWidths + self::THUMB_PADDING + self::GB_PADDING ) . 'px">'
					. '<div style="width: ' . ( $this->mWidths + self::THUMB_PADDING + self::GB_PADDING ) . 'px">'
					. $thumbhtml
					. "\n\t\t\t" . '<div class="gallerytext">' . "\n"
						. $textlink . $text . $fileSize
					. "\n\t\t\t</div>"
				. "\n\t\t</div></li>";
		}
		$output .= "\n</ul>";

		return $output;
	}

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

} //class
