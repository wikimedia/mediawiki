<?php
if ( ! defined( 'MEDIAWIKI' ) )
	die( 1 );

/**
 */

/**
 * Image gallery
 *
 * Add images to the gallery using add(), then render that list to HTML using toHTML().
 *
 * @addtogroup Media
 */
class ImageGallery
{
	var $mImages, $mShowBytes, $mShowFilename;
	var $mCaption = false;
	var $mSkin = false;
	var $mRevisionId = 0;

	/**
	 * Is the gallery on a wiki page (i.e. not a special page)
	 */
	var $mParsing;

	/**
	 * Contextual title, used when images are being screened
	 * against the bad image list
	 */
	private $contextTitle = false;

	private $mPerRow = 4; // How many images wide should the gallery be?
	private $mWidths = 120, $mHeights = 120; // How wide/tall each thumbnail should be

	/**
	 * Create a new image gallery object.
	 */
	function __construct( ) {
		$this->mImages = array();
		$this->mShowBytes = true;
		$this->mShowFilename = true;
		$this->mParsing = false;
	}

	/**
	 * Set the "parse" bit so we know to hide "bad" images
	 */
	function setParsing( $val = true ) {
		$this->mParsing = $val;
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
	 * @param $caption Caption
	 */
	public function setCaptionHtml( $caption ) {
		$this->mCaption = $caption;
	}

	/**
	 * Set how many images will be displayed per row.
	 *
	 * @param int $num > 0; invalid numbers will be rejected
	 */
	public function setPerRow( $num ) {
		if ($num > 0) {
			$this->mPerRow = (int)$num;
		}
	}

	/**
	 * Set how wide each image will be, in pixels.
	 *
	 * @param int $num > 0; invalid numbers will be ignored
	 */
	public function setWidths( $num ) {
		if ($num > 0) {
			$this->mWidths = (int)$num;
		}
	}

	/**
	 * Set how high each image will be, in pixels.
	 *
	 * @param int $num > 0; invalid numbers will be ignored
	 */
	public function setHeights( $num ) {
		if ($num > 0) {
			$this->mHeights = (int)$num;
		}
	}

	/**
	 * Instruct the class to use a specific skin for rendering
	 *
	 * @param $skin Skin object
	 */
	function useSkin( $skin ) {
		$this->mSkin = $skin;
	}

	/**
	 * Return the skin that should be used
	 *
	 * @return Skin object
	 */
	function getSkin() {
		if( !$this->mSkin ) {
			global $wgUser;
			$skin = $wgUser->getSkin();
		} else {
			$skin = $this->mSkin;
		}
		return $skin;
	}

	/**
	 * Add an image to the gallery.
	 *
	 * @param $title Title object of the image that is added to the gallery
	 * @param $html  String: additional HTML text to be shown. The name and size of the image are always shown.
	 */
	function add( $title, $html='' ) {
		if ( $title instanceof File ) {
			// Old calling convention
			$title = $title->getTitle();
		}
		$this->mImages[] = array( $title, $html );
		wfDebug( "ImageGallery::add " . $title->getText() . "\n" );
	}

	/**
 	* Add an image at the beginning of the gallery.
 	*
 	* @param $title Title object of the image that is added to the gallery
 	* @param $html  String:  Additional HTML text to be shown. The name and size of the image are always shown.
 	*/
	function insert( $title, $html='' ) {
		if ( $title instanceof File ) {
			// Old calling convention
			$title = $title->getTitle();
		}
		array_unshift( $this->mImages, array( &$title, $html ) );
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
		$this->mShowBytes = ( $f == true);
	}

	/**
	 * Enable/Disable showing of the filename of an image in the gallery.
	 * Enabled by default.
	 *
	 * @param $f Boolean: set to false to disable.
	 */
	function setShowFilename( $f ) {
		$this->mShowFilename = ( $f == true);
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

		$sk = $this->getSkin();

		$s = '<table class="gallery" cellspacing="0" cellpadding="0">';
		if( $this->mCaption )
			$s .= "\n\t<caption>{$this->mCaption}</caption>";

		$params = array( 'width' => $this->mWidths, 'height' => $this->mHeights );
		$i = 0;
		foreach ( $this->mImages as $pair ) {
			$nt = $pair[0];
			$text = $pair[1];
			
			# Give extensions a chance to select the file revision for us
			$time = false;
			wfRunHooks( 'BeforeGalleryFindFile', array( &$this, &$nt, &$time ) );

			$img = wfFindFile( $nt, $time );

			if( $nt->getNamespace() != NS_IMAGE || !$img ) {
				# We're dealing with a non-image, spit out the name and be done with it.
				$thumbhtml = "\n\t\t\t".'<div style="height: '.($this->mHeights*1.25+2).'px;">'
					. htmlspecialchars( $nt->getText() ) . '</div>';
 			} elseif( $this->mParsing && wfIsBadImage( $nt->getDBkey(), $this->getContextTitle() ) ) {
				# The image is blacklisted, just show it as a text link.
				$thumbhtml = "\n\t\t\t".'<div style="height: '.($this->mHeights*1.25+2).'px;">'
					. $sk->makeKnownLinkObj( $nt, htmlspecialchars( $nt->getText() ) ) . '</div>';
			} elseif( !( $thumb = $img->transform( $params ) ) ) {
				# Error generating thumbnail.
				$thumbhtml = "\n\t\t\t".'<div style="height: '.($this->mHeights*1.25+2).'px;">'
					. htmlspecialchars( $img->getLastError() ) . '</div>';
			} else {
				$vpad = floor( ( 1.25*$this->mHeights - $thumb->height ) /2 ) - 2;
				$thumbhtml = "\n\t\t\t".'<div class="thumb" style="padding: ' . $vpad . 'px 0; width: '.($this->mWidths+30).'px;">'
					. $sk->makeKnownLinkObj( $nt, $thumb->toHtml() ) . '</div>';
			}

			//TODO
			//$ul = $sk->makeLink( $wgContLang->getNsText( Namespace::getUser() ) . ":{$ut}", $ut );

			if( $this->mShowBytes ) {
				if( $img ) {
					$nb = wfMsgExt( 'nbytes', array( 'parsemag', 'escape'),
						$wgLang->formatNum( $img->getSize() ) );
				} else {
					$nb = wfMsgHtml( 'filemissing' );
				}
				$nb = "$nb<br />\n";
			} else {
				$nb = '';
			}

			$textlink = $this->mShowFilename ?
				$sk->makeKnownLinkObj( $nt, htmlspecialchars( $wgLang->truncate( $nt->getText(), 20, '...' ) ) ) . "<br />\n" :
				'' ;

			# ATTENTION: The newline after <div class="gallerytext"> is needed to accommodate htmltidy which
			# in version 4.8.6 generated crackpot html in its absence, see:
			# http://bugzilla.wikimedia.org/show_bug.cgi?id=1765 -Ævar

			if ( $i % $this->mPerRow == 0 ) {
				$s .= "\n\t<tr>";
			}
			$s .=
				"\n\t\t" . '<td><div class="gallerybox" style="width: '.($this->mWidths*1.25).'px;">'
					. $thumbhtml
					. "\n\t\t\t" . '<div class="gallerytext">' . "\n"
						. $textlink . $text . $nb
					. "\n\t\t\t</div>"
				. "\n\t\t</div></td>";
			if ( $i % $this->mPerRow == $this->mPerRow - 1 ) {
				$s .= "\n\t</tr>";
			}
			++$i;
		}
		if( $i % $this->mPerRow != 0 ) {
			$s .= "\n\t</tr>";
		}
		$s .= "\n</table>";

		return $s;
	}

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
	 * @return mixed Title or false
	 */
	public function getContextTitle() {
		return is_object( $this->contextTitle ) && $this->contextTitle instanceof Title
				? $this->contextTitle
				: false;
	}

} //class
?>
