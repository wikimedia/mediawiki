
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
 * @package MediaWiki
 */
class ImageGallery
{
	var $mImages;

	function ImageGallery( ) {
		$this->mImages=array();
	}

	function add( $image, $text='' ) {
		$this->mImages[] = array( &$image, $text );
	}

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

			$ilink = '<a href="' . $img->getURL() .  '">' . $nt->getText() . '</a>';
			$nb = wfMsg( "nbytes", $wgLang->formatNum( $img->getSize() ) );

			$s .= ($i%4==0) ? '<tr>' : '';
			$s .= '<td valign="top" width="150px" style="background-color:#F0F0F0;">' .
				'<table width="100%" height="150px">'.
				'<tr><td align="center" valign="center" style="background-color:#F8F8F8;border:solid 1px #888888;">' .
				'<img  src="'.$img->createThumb(120,120).'" alt=""></td></tr></table> ' .
				'(' .  $sk->makeKnownLinkObj( $nt, wfMsg( "imgdesc" ) ) .
				") {$ilink}<br />{$text}{$nb}<br />" ;

			$s .= '</td>' .  (($i%4==3) ? '</tr>' : '');

			$i++;
		}
		$s .= '</table>';

		return $s;
	}

} //class




} // defined( 'MEDIAWIKI' )
?>
