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
	 * @param string $text   Additional text to be shown. The name and size of the image are always shown.
	 */
	function add( $image, $text='' ) {
		$this->mImages[] = array( &$image, $text );
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

		$s = '<table  style="border:solid 1px #DDDDDD; cellspacing:0; cellpadding:0; margin:1em;">';
		$i = 0;
		foreach ( $this->mImages as $pair ) {
			$img =& $pair[0];
			$text = $pair[1];

			$name = $img->getName();
			$nt = $img->getTitle();

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
			$s .= '<td valign="top" width="150px" style="background-color:#F0F0F0;">' .
				'<table width="100%" height="150px">'.
				'<tr><td align="center" valign="center" style="background-color:#F8F8F8;border:solid 1px #888888;">' .
				$sk->makeKnownLinkObj( $nt, '<img  src="'.$img->createThumb(120,120).'" alt="" />' ) . '</td></tr></table> ' .
				$textlink . $text . $nb; 

			$s .= "</td>\n" .  (($i%4==3) ? "</tr>\n" : '');

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
