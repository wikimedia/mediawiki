<?php
/**
 * @package MediaWiki
 */

/**
 * This is not a valid entry point, perform no further processing unless MEDIAWIKI is defined
 */
if( defined( 'MEDIAWIKI' ) ) {


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
	 * isEmpty() returns false iff the gallery doesn't contain any images
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
		global $wgLang, $wgContLang, $wgUser;

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
					$nb = wfMsg( 'nbytes', $wgLang->formatNum( $img->getSize() ) );
				} else {
					$nb = wfMsg( 'filemissing' );
				}
				$nb = htmlspecialchars( $nb ) . '<br />';
			} else {
				$nb = '';
			}
				
				'' ;
			$textlink = $this->mShowFilename ?
				$sk->makeKnownLinkObj( $nt, htmlspecialchars( $wgLang->truncate( $nt->getText(), 20, '...' ) ) ) . '<br />' :
				'' ;

			$s .= ($i%4==0) ? '<tr>' : '';
			$thumb = $img->getThumbnail( 120, 120 );
			$vpad = floor( ( 150 - $thumb->height ) /2 ) - 2;
			$s .= '<td><div class="gallerybox">' .
				'<div class="thumb" style="padding: ' . $vpad . 'px 0;">'.
				$sk->makeKnownLinkObj( $nt, $thumb->toHtml() ) . '</div>';
			if($text <> '') {
				$s .= '<div class="gallerytext">' .
					$textlink . $text . $nb .
					'</div>';
			}
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




} // defined( 'MEDIAWIKI' )
?>
