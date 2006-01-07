<?php
if ( ! defined( 'MEDIAWIKI' ) )
	die();

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
	 * Create a new image gallery object.
	 */
	function ImageGallery( ) {
		$this->mImages = array();
		$this->mShowBytes = true;
		$this->mShowFilename = true;
	}

	/**
	 * Add an image to the gallery.
	 *
	 * @param Image  $image  Image object that is added to the gallery
	 * @param string $html   Additional HTML text to be shown. The name and size of the image are always shown.
	 */
	function add( $image, $html='' ) {
		$this->mImages[] = array( &$image, $html );
	}

	/**
 	* Add an image at the beginning of the gallery.
 	*
 	* @param Image  $image  Image object that is added to the gallery
 	* @param string $html   Additional HTML text to be shown. The name and size of the image are always shown.
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
	 * @param boolean $f	set to false to disable
	 */
	function setShowBytes( $f ) {
		$this->mShowBytes = ( $f == true);
	}

	/**
	 * Enable/Disable showing of the filename of an image in the gallery.
	 * Enabled by default.
	 *
	 * @param boolean $f	set to false to disable
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
		global $wgLang, $wgUser;

		$sk = $wgUser->getSkin();

		$s = '<table class="gallery" cellspacing="0" cellpadding="0">';
		$i = 0;
		foreach ( $this->mImages as $pair ) {
			$img =& $pair[0];
			$text = $pair[1];

			$name = $img->getName();
			$nt = $img->getTitle();

			// Not an image. Just print the name and skip.
			if ( $nt->getNamespace() != NS_IMAGE ) {
				$s .= '<td><div class="gallerybox" style="height: 152px;">' .
					htmlspecialchars( $nt->getText() ) . '</div></td>' .  (($i%4==3) ? "</tr>\n" : '');
				$i++;

				continue;
			}

			//TODO
			//$ul = $sk->makeLink( $wgContLang->getNsText( Namespace::getUser() ) . ":{$ut}", $ut );

			if( $this->mShowBytes ) {
				if( $img->exists() ) {
					$nb = wfMsgHtml( 'nbytes', $wgLang->formatNum( $img->getSize() ) );
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
			$vpad = floor( ( 150 - $thumb->height ) /2 ) - 2;
			$s .= '<td><div class="gallerybox">' . '<div class="thumb" style="padding: ' . $vpad . 'px 0;">';

			# ATTENTION: The newline after <div class="gallerytext"> is needed to accommodate htmltidy which
			# in version 4.8.6 generated crackpot html in its absence, see:
			# http://bugzilla.wikimedia.org/show_bug.cgi?id=1765 -Ævar
			$s .= $sk->makeKnownLinkObj( $nt, $thumb->toHtml() ) . '</div><div class="gallerytext">' . "\n" .
				$textlink . $text . $nb .
				'</div>';
			$s .= "</div></td>\n";
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
