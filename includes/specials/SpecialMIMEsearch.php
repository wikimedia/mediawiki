<?php
/**
 * Implements Special:MIMESearch
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
 * @ingroup SpecialPage
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 */

/**
 * Searches the database for files of the requested MIME type, comparing this with the
 * 'img_major_mime' and 'img_minor_mime' fields in the image table.
 * @ingroup SpecialPage
 */
class MIMEsearchPage extends QueryPage {
	protected $major, $minor;

	function __construct( $name = 'MIMEsearch' ) {
		parent::__construct( $name );
	}

	function isExpensive() { return true; }
	function isSyndicated() { return false; }
	function isCacheable() { return false; }

	function linkParameters() {
		return array( 'mime' => "{$this->major}/{$this->minor}" );
	}

	public function getQueryInfo() {
		return array(
			'tables' => array( 'image' ),
			'fields' => array( "'" . NS_FILE . "' AS namespace",
					'img_name AS title',
					'img_major_mime AS value',
					'img_size',
					'img_width',
					'img_height',
					'img_user_text',
					'img_timestamp' ),
			'conds' => array( 'img_major_mime' => $this->major,
					'img_minor_mime' => $this->minor )
		);
	}

	function execute( $par ) {
		$mime = $par ? $par : $this->getRequest()->getText( 'mime' );

		$this->setHeaders();
		$this->outputHeader();
		$this->getOutput()->addHTML(
			Xml::openElement( 'form', array( 'id' => 'specialmimesearch', 'method' => 'get', 'action' => SpecialPage::getTitleFor( 'MIMEsearch' )->getLocalUrl() ) ) .
			Xml::openElement( 'fieldset' ) .
			Html::hidden( 'title', SpecialPage::getTitleFor( 'MIMEsearch' )->getPrefixedText() ) .
			Xml::element( 'legend', null, wfMsg( 'mimesearch' ) ) .
			Xml::inputLabel( wfMsg( 'mimetype' ), 'mime', 'mime', 20, $mime ) . ' ' .
			Xml::submitButton( wfMsg( 'ilsubmit' ) ) .
			Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' )
		);

		list( $this->major, $this->minor ) = File::splitMime( $mime );
		if ( $this->major == '' || $this->minor == '' || $this->minor == 'unknown' ||
			!self::isValidType( $this->major ) ) {
			return;
		}
		parent::execute( $par );
	}


	function formatResult( $skin, $result ) {
		global $wgContLang;

		$nt = Title::makeTitle( $result->namespace, $result->title );
		$text = $wgContLang->convert( $nt->getText() );
		$plink = Linker::link(
			Title::newFromText( $nt->getPrefixedText() ),
			htmlspecialchars( $text )
		);

		$download = Linker::makeMediaLinkObj( $nt, wfMsgHtml( 'download' ) );
		$lang = $this->getLanguage();
		$bytes = htmlspecialchars( $lang->formatSize( $result->img_size ) );
		$dimensions = htmlspecialchars( wfMsg( 'widthheight',
			$lang->formatNum( $result->img_width ),
			$lang->formatNum( $result->img_height )
		) );
		$user = Linker::link( Title::makeTitle( NS_USER, $result->img_user_text ), htmlspecialchars( $result->img_user_text ) );
		$time = htmlspecialchars( $lang->timeanddate( $result->img_timestamp ) );

		return "($download) $plink . . $dimensions . . $bytes . . $user . . $time";
	}

	/**
	 * @param $type string
	 * @return bool
	 */
	protected static function isValidType( $type ) {
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
}
