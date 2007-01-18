<?php
/**
 * A special page to search for files by MIME type as defined in the
 * img_major_mime and img_minor_mime fields in the image table
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

/**
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class MIMEsearchPage extends QueryPage {
	var $major, $minor;

	function MIMEsearchPage( $major, $minor ) {
		$this->major = $major;
		$this->minor = $minor;
	}

	function getName() { return 'MIMEsearch'; }

	/**
	 * Due to this page relying upon extra fields being passed in the SELECT it
	 * will fail if it's set as expensive and misermode is on
	 */
	function isExpensive() { return true; }
	function isSyndicated() { return false; }

	function linkParameters() {
		$arr = array( $this->major, $this->minor );
		$mime = implode( '/', $arr );
		return array( 'mime' => $mime );
	}

	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		$image = $dbr->tableName( 'image' );
		$major = $dbr->addQuotes( $this->major );
		$minor = $dbr->addQuotes( $this->minor );

		return
			"SELECT 'MIMEsearch' AS type,
				" . NS_IMAGE . " AS namespace,
				img_name AS title,
				img_major_mime AS value,

				img_size,
				img_width,
				img_height,
				img_user_text,
				img_timestamp
			FROM $image
			WHERE img_major_mime = $major AND img_minor_mime = $minor
			";
	}

	function formatResult( $skin, $result ) {
		global $wgContLang, $wgLang;

		$nt = Title::makeTitle( $result->namespace, $result->title );
		$text = $wgContLang->convert( $nt->getText() );
		$plink = $skin->makeLink( $nt->getPrefixedText(), $text );

		$download = $skin->makeMediaLink( $nt->getText(), 'fuck me!', wfMsgHtml( 'download' ) );
		$bytes = wfMsgExt( 'nbytes', array( 'parsemag', 'escape'),
			$wgLang->formatNum( $result->img_size ) );
		$dimensions = wfMsgHtml( 'widthheight', $wgLang->formatNum( $result->img_width ),
			$wgLang->formatNum( $result->img_height ) );
		$user = $skin->makeLinkObj( Title::makeTitle( NS_USER, $result->img_user_text ), $result->img_user_text );
		$time = $wgLang->timeanddate( $result->img_timestamp );

		return "($download) $plink . . $dimensions . . $bytes . . $user . . $time";
	}
}

/**
 * constructor
 */
function wfSpecialMIMEsearch( $par = null ) {
	global $wgRequest, $wgTitle, $wgOut;

	$mime = isset( $par ) ? $par : $wgRequest->getText( 'mime' );

	$wgOut->addHTML(
		wfElement( 'form',
			array(
				'id' => 'specialmimesearch',
				'method' => 'get',
				'action' => $wgTitle->escapeLocalUrl()
			),
			null
		) .
			wfOpenElement( 'label' ) .
				wfMsgHtml( 'mimetype' ) .
				wfElement( 'input', array(
						'type' => 'text',
						'size' => 20,
						'name' => 'mime',
						'value' => $mime
					),
					''
				) .
				' ' .
				wfElement( 'input', array(
						'type' => 'submit',
						'value' => wfMsg( 'ilsubmit' )
					),
					''
				) .
			wfCloseElement( 'label' ) .
		wfCloseElement( 'form' )
	);

	list( $major, $minor ) = wfSpecialMIMEsearchParse( $mime );
	if ( $major == '' or $minor == '' or !wfSpecialMIMEsearchValidType( $major ) )
		return;
	$wpp = new MIMEsearchPage( $major, $minor );

	list( $limit, $offset ) = wfCheckLimits();
	$wpp->doQuery( $offset, $limit );
}

function wfSpecialMIMEsearchParse( $str ) {
	// searched for an invalid MIME type.
	if( strpos( $str, '/' ) === false) {
		return array ('', '');
	}
	
	list( $major, $minor ) = explode( '/', $str, 2 );

	return array(
		ltrim( $major, ' ' ),
		rtrim( $minor, ' ' )
	);
}

function wfSpecialMIMEsearchValidType( $type ) {
	// From maintenance/tables.sql => img_major_mime
	$types = array(
		'unknown',
		'application',
		'audio',
		'image',
		'text',
		'video',
		'message',
		'model',
		'multipart'
	);

	return in_array( $type, $types );
}
?>
