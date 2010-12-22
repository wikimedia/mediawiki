<?php
/**
 * Implements Special:FileDuplicateSearch
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
 * @author Raimond Spekking, based on Special:MIMESearch by Ævar Arnfjörð Bjarmason
 */

/**
 * Searches the database for files of the requested hash, comparing this with the
 * 'img_sha1' field in the image table.
 *
 * @ingroup SpecialPage
 */
class FileDuplicateSearchPage extends QueryPage {
	protected $hash, $filename;

	function __construct( $name = 'FileDuplicateSearch' ) {
		parent::__construct( $name );
	}

	function isSyndicated() { return false; }
	function isCacheable() { return false; }

	function linkParameters() {
		return array( 'filename' => $this->filename );
	}

	function getQueryInfo() {
		return array(
			'tables' => array( 'image' ),
			'fields' => array(
				'img_name AS title',
				'img_sha1 AS value',
				'img_user_text',
				'img_timestamp'
			),
			'conds' => array( 'img_sha1' => $this->hash )
		);
	}
	
	function execute( $par ) {
		global $wgRequest, $wgOut, $wgLang, $wgContLang, $wgScript;
		
		$this->setHeaders();
		$this->outputHeader();
		
		$this->filename =  isset( $par ) ?  $par : $wgRequest->getText( 'filename' );
		$this->hash = '';
		$title = Title::makeTitleSafe( NS_FILE, $this->filename );
		if( $title && $title->getText() != '' ) {
			$dbr = wfGetDB( DB_SLAVE );
			$this->hash = $dbr->selectField( 'image', 'img_sha1', array( 'img_name' => $title->getDBkey() ), __METHOD__ );
		}

		# Create the input form
		$wgOut->addHTML(
			Xml::openElement( 'form', array( 'id' => 'fileduplicatesearch', 'method' => 'get', 'action' => $wgScript ) ) .
			Html::hidden( 'title', $this->getTitle()->getPrefixedDbKey() ) .
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, wfMsg( 'fileduplicatesearch-legend' ) ) .
			Xml::inputLabel( wfMsg( 'fileduplicatesearch-filename' ), 'filename', 'filename', 50, $this->filename ) . ' ' .
			Xml::submitButton( wfMsg( 'fileduplicatesearch-submit' ) ) .
			Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' )
		);

		if( $this->hash != '' ) {
			$align = $wgContLang->alignEnd();

			# Show a thumbnail of the file
			$img = wfFindFile( $title );
			if ( $img ) {
				$thumb = $img->transform( array( 'width' => 120, 'height' => 120 ) );
				if( $thumb ) {
					$wgOut->addHTML( '<div style="float:' . $align . '" id="mw-fileduplicatesearch-icon">' .
						$thumb->toHtml( array( 'desc-link' => false ) ) . '<br />' .
						wfMsgExt( 'fileduplicatesearch-info', array( 'parse' ),
							$wgLang->formatNum( $img->getWidth() ),
							$wgLang->formatNum( $img->getHeight() ),
							$wgLang->formatSize( $img->getSize() ),
							$img->getMimeType()
						) .
						'</div>' );
				}
			}

			parent::execute( $par );

			# Show a short summary
			if( $this->numRows == 1 ) {
				$wgOut->wrapWikiMsg(
					"<p class='mw-fileduplicatesearch-result-1'>\n$1\n</p>",
					array( 'fileduplicatesearch-result-1', $this->filename )
				);
			} elseif ( $this->numRows > 1 ) {
				$wgOut->wrapWikiMsg(
					"<p class='mw-fileduplicatesearch-result-n'>\n$1\n</p>",
					array( 'fileduplicatesearch-result-n', $this->filename,
						$wgLang->formatNum( $this->numRows - 1 ) )
				);
			}
		}
	}

	function formatResult( $skin, $result ) {
		global $wgContLang, $wgLang;

		$nt = Title::makeTitle( NS_FILE, $result->title );
		$text = $wgContLang->convert( $nt->getText() );
		$plink = $skin->link(
			Title::newFromText( $nt->getPrefixedText() ),
			$text
		);

		$user = $skin->link( Title::makeTitle( NS_USER, $result->img_user_text ), $result->img_user_text );
		$time = $wgLang->timeanddate( $result->img_timestamp );

		return "$plink . . $user . . $time";
	}
}
