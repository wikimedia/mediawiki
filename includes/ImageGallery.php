<?php
if ( ! defined( 'MEDIAWIKI' ) )
	die( 1 );

/**
 * @package MediaWiki
 */

/**
 * Image gallery
 *
 * Add images to the gallery using add(), then render that list to HTML using toHTML().
 *
 * @package MediaWiki
 */
class ImageGallery
{
	var $mImages, $mShowBytes, $mShowFilename;
	var $mCaption = false;
	var $mSkin = false;
	
	/**
	 * Is the gallery on a wiki page (i.e. not a special page)
	 */
	var $mParsing;

	/**
	 * Create a new image gallery object.
	 */
	function ImageGallery( ) {
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
	 * Set the caption
	 *
	 * @param $caption Caption
	 */
	function setCaption( $caption ) {
		$this->mCaption = $caption;
	}

	/**
	 * Instruct the class to use a specific skin for rendering
	 *
	 * @param $skin Skin object
	 */
	function useSkin( $skin ) {
		$this->mSkin =& $skin;
	}
	
	/**
	 * Return the skin that should be used
	 *
	 * @return Skin object
	 */
	function getSkin() {
		if( !$this->mSkin ) {
			global $wgUser;
			$skin =& $wgUser->getSkin();
		} else {
			$skin =& $this->mSkin;
		}
		return $skin;
	}

	/**
	 * Add an image to the gallery.
	 *
	 * @param $image Image object that is added to the gallery
	 * @param $html  String: additional HTML text to be shown. The name and size of the image are always shown.
	 */
	function add( $image, $html='' ) {
		$this->mImages[] = array( &$image, $html );
		wfDebug( "ImageGallery::add " . $image->getName() . "\n" );
	}

	/**
 	* Add an image at the beginning of the gallery.
 	*
 	* @param $image Image object that is added to the gallery
 	* @param $html  String:  Additional HTML text to be shown. The name and size of the image are always shown.
 	*/
	function insert( $image, $html='' ) {
		array_unshift( $this->mImages, array( &$image, $html ) );
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
		global $wgLang, $wgIgnoreImageErrors, $wgGenerateThumbnailOnParse;

		$sk = $this->getSkin();

		$s = '<table class="gallery" cellspacing="0" cellpadding="0">';
		if( $this->mCaption )
			$s .= '<td class="galleryheader" colspan="4"><big>' . htmlspecialchars( $this->mCaption ) . '</big></td>';
		
		$i = 0;
		foreach ( $this->mImages as $pair ) {
			$img =& $pair[0];
			$text = $pair[1];

			$name = $img->getName();
			$nt = $img->getTitle();

			if( $nt->getNamespace() != NS_IMAGE ) {
				# We're dealing with a non-image, spit out the name and be done with it.
				$thumbhtml = '<div style="height: 152px;">' . htmlspecialchars( $nt->getText() ) . '</div>';
 			}
			else if( $this->mParsing && wfIsBadImage( $nt->getDBkey() ) ) {
				# The image is blacklisted, just show it as a text link.
				$thumbhtml = '<div style="height: 152px;">'
					. $sk->makeKnownLinkObj( $nt, htmlspecialchars( $nt->getText() ) ) . '</div>';
			} else if( !( $thumb = $img->getThumbnail( 120, 120, $wgGenerateThumbnailOnParse ) ) ) {
				# Error generating thumbnail.
				$thumbhtml = '<div style="height: 152px;">'
					. htmlspecialchars( $img->getLastError() ) . '</div>';
			}
			else {
				$vpad = floor( ( 150 - $thumb->height ) /2 ) - 2;
				$thumbhtml = '<div class="thumb" style="padding: ' . $vpad . 'px 0;">'
					. $sk->makeKnownLinkObj( $nt, $thumb->toHtml() ) . '</div>';
			}

			//TODO
			//$ul = $sk->makeLink( $wgContLang->getNsText( Namespace::getUser() ) . ":{$ut}", $ut );

			if( $this->mShowBytes ) {
				if( $img->exists() ) {
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

			$s .= ($i%4==0) ? '<tr>' : '';
			$s .= '<td><div class="gallerybox">' . $thumbhtml
				. '<div class="gallerytext">' . "\n" . $textlink . $text . $nb
				. "</div></div></td>\n";
			$s .= ($i%4==3) ? '</tr>' : '';
			$i++;
		}
		if( $i %4 != 0 ) {
			$s .= "</tr>\n";
		}
		$s .= '</table>';

		return $s;
	}

} //class
?>
