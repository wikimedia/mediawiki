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
			'fields' => array( 'namespace' => NS_FILE,
					'title' => 'img_name',
					'value' => 'img_major_mime',
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
		global $wgScript;

		$mime = $par ? $par : $this->getRequest()->getText( 'mime' );

		$this->setHeaders();
		$this->outputHeader();
		$this->getOutput()->addHTML(
			Xml::openElement( 'form', array( 'id' => 'specialmimesearch', 'method' => 'get', 'action' => $wgScript ) ) .
			Xml::openElement( 'fieldset' ) .
			Html::hidden( 'title', $this->getTitle()->getPrefixedText() ) .
			Xml::element( 'legend', null, $this->msg( 'mimesearch' )->text() ) .
			Xml::inputLabel( $this->msg( 'mimetype' )->text(), 'mime', 'mime', 20, $mime ) . ' ' .
			Xml::submitButton( $this->msg( 'ilsubmit' )->text() ) .
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

		$download = Linker::makeMediaLinkObj( $nt, $this->msg( 'download' )->escaped() );
		$download = $this->msg( 'parentheses' )->rawParams( $download )->escaped();
		$lang = $this->getLanguage();
		$bytes = htmlspecialchars( $lang->formatSize( $result->img_size ) );
		$dimensions = $this->msg( 'widthheight' )->numParams( $result->img_width,
			$result->img_height )->escaped();
		$user = Linker::link( Title::makeTitle( NS_USER, $result->img_user_text ), htmlspecialchars( $result->img_user_text ) );
		$time = htmlspecialchars( $lang->userTimeAndDate( $result->img_timestamp, $this->getUser() ) );

		return "$download $plink . . $dimensions . . $bytes . . $user . . $time";
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
