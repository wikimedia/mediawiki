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
	 * Add an image to the gallery.
	 *
	 * @param $image Image object that is added to the gallery
	 * @param $html  String: additional HTML text to be shown. The name and size of the image are always shown.
	 */
	function add( $image, $html='' ) {
		$this->mImages[] = array( &$image, $html );
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
		global $wgLang, $wgUser, $wgIgnoreImageErrors;

		$sk = $wgUser->getSkin();

		$s = '<table class="gallery" cellspacing="0" cellpadding="0">';
		$i = 0;
		foreach ( $this->mImages as $pair ) {
			$img =& $pair[0];
			$text = $pair[1];

			$name = $img->getName();
			$nt = $img->getTitle();

			# If we're dealing with a non-image, or a blacklisted image,
			# spit out the name and be done with it
			if( $nt->getNamespace() != NS_IMAGE
				|| ( $this->mParsing && wfIsBadImage( $nt->getDBkey() ) ) ) {
				$s .=
					(($i%4==0) ? "<tr>\n" : '') .
					'<td><div class="gallerybox" style="height: 152px;">' .
					htmlspecialchars( $nt->getText() ) . '</div></td>' .  
					(($i%4==3) ? "</tr>\n" : '');
				$i++;

				continue;
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

			$s .= ($i%4==0) ? '<tr>' : '';
			$thumb = $img->getThumbnail( 120, 120 );
			if ( !$thumb && $wgIgnoreImageErrors ) {
				$thumb = $img->iconThumb();
			}
			if ( $thumb ) {
				$vpad = floor( ( 150 - $thumb->height ) /2 ) - 2;
				$s .= '<td><div class="gallerybox">' . '<div class="thumb" style="padding: ' . $vpad . 'px 0;">';

				# ATTENTION: The newline after <div class="gallerytext"> is needed to accommodate htmltidy which
				# in version 4.8.6 generated crackpot html in its absence, see:
				# http://bugzilla.wikimedia.org/show_bug.cgi?id=1765 -Ævar
				$s .= $sk->makeKnownLinkObj( $nt, $thumb->toHtml() ) . '</div><div class="gallerytext">' . "\n" .
					$textlink . $text . $nb .
					'</div>';
				$s .= "</div></td>\n";
			} else {
				# Error during thumbnail generation
				$s .= '<td><div class="gallerybox" style="height: 152px;">' .
					#htmlspecialchars( $nt->getText() ) . "<br />\n" .
					htmlspecialchars( $img->getLastError() ) .
					"</div><div class=\"gallerytext\">\n" .
					$textlink . $text . $nb .
					"</div></td>\n";
			}
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
