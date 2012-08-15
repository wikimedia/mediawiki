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
	protected $hash = '', $filename = '';

	/**
	 * @var File $file selected reference file, if present
	 */
	protected $file = null;

	function __construct( $name = 'FileDuplicateSearch' ) {
		parent::__construct( $name );
	}

	function isSyndicated() { return false; }
	function isCacheable() { return false; }
	function isCached() { return false; }

	function linkParameters() {
		return array( 'filename' => $this->filename );
	}

	/**
	 * Fetch dupes from all connected file repositories.
	 *
	 * @return Array of File objects
	 */
	function getDupes() {
		return RepoGroup::singleton()->findBySha1( $this->hash );
	}

	/**
	 *
	 * @param $dupes Array of File objects
	 */
	function showList( $dupes ) {
		$html = array();
		$html[] = $this->openList( 0 );

		foreach ( $dupes as $dupe ) {
			$line = $this->formatResult( null, $dupe );
			$html[] = "<li>" . $line . "</li>";
		}
		$html[] = $this->closeList();

		$this->getOutput()->addHtml( implode( "\n", $html ) );
	}

	function getQueryInfo() {
		return array(
			'tables' => array( 'image' ),
			'fields' => array(
				'title' => 'img_name',
				'value' => 'img_sha1',
				'img_user_text',
				'img_timestamp'
			),
			'conds' => array( 'img_sha1' => $this->hash )
		);
	}

	function execute( $par ) {
		global $wgScript;

		$this->setHeaders();
		$this->outputHeader();

		$this->filename =  isset( $par ) ?  $par : $this->getRequest()->getText( 'filename' );
		$this->file = null;
		$this->hash = '';
		$title = Title::newFromText( $this->filename, NS_FILE );
		if( $title && $title->getText() != '' ) {
			$this->file = wfFindFile( $title );
		}

		$out = $this->getOutput();

		# Create the input form
		$out->addHTML(
			Xml::openElement( 'form', array( 'id' => 'fileduplicatesearch', 'method' => 'get', 'action' => $wgScript ) ) .
			Html::hidden( 'title', $this->getTitle()->getPrefixedDbKey() ) .
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, $this->msg( 'fileduplicatesearch-legend' )->text() ) .
			Xml::inputLabel( $this->msg( 'fileduplicatesearch-filename' )->text(), 'filename', 'filename', 50, $this->filename ) . ' ' .
			Xml::submitButton( $this->msg( 'fileduplicatesearch-submit' )->text() ) .
			Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' )
		);

		if( $this->file ) {
			$this->hash = $this->file->getSha1();
		} elseif( $this->filename !== '' ) {
			$out->wrapWikiMsg(
				"<p class='mw-fileduplicatesearch-noresults'>\n$1\n</p>",
				array( 'fileduplicatesearch-noresults', wfEscapeWikiText( $this->filename ) )
			);
		}

		if( $this->hash != '' ) {
			# Show a thumbnail of the file
			$img = $this->file;
			if ( $img ) {
				$thumb = $img->transform( array( 'width' => 120, 'height' => 120 ) );
				if( $thumb ) {
					$out->addHTML( '<div id="mw-fileduplicatesearch-icon">' .
						$thumb->toHtml( array( 'desc-link' => false ) ) . '<br />' .
						$this->msg( 'fileduplicatesearch-info' )->numParams(
							$img->getWidth(), $img->getHeight() )->params(
							$this->getLanguage()->formatSize( $img->getSize() ),
							$img->getMimeType() )->parseAsBlock() .
						'</div>' );
				}
			}

			$dupes = $this->getDupes();
			$numRows = count( $dupes );

			# Show a short summary
			if( $numRows == 1 ) {
				$out->wrapWikiMsg(
					"<p class='mw-fileduplicatesearch-result-1'>\n$1\n</p>",
					array( 'fileduplicatesearch-result-1', wfEscapeWikiText( $this->filename ) )
				);
			} elseif ( $numRows ) {
				$out->wrapWikiMsg(
					"<p class='mw-fileduplicatesearch-result-n'>\n$1\n</p>",
					array( 'fileduplicatesearch-result-n', wfEscapeWikiText( $this->filename ),
						$this->getLanguage()->formatNum( $numRows - 1 ) )
				);
			}

			$this->doBatchLookups( $dupes );
			$this->showList( $dupes );
		}
	}

	function doBatchLookups( $list ) {
		$batch = new LinkBatch();
		foreach( $list as $file ) {
			$batch->addObj( $file->getTitle() );
			if( $file->isLocal() ) {
				$userName = $file->getUser( 'text' );
				$batch->add( NS_USER, $userName );
				$batch->add( NS_USER_TALK, $userName );
			}
		}
		$batch->execute();
	}

	/**
	 *
	 * @param Skin $skin
	 * @param File $result
	 * @return string
	 */
	function formatResult( $skin, $result ) {
		global $wgContLang;

		$nt = $result->getTitle();
		$text = $wgContLang->convert( $nt->getText() );
		$plink = Linker::link(
			Title::newFromText( $nt->getPrefixedText() ),
			$text
		);

		$userText = $result->getUser( 'text' );
		if ( $result->isLocal() ) {
			$userId = $result->getUser( 'id' );
			$user = Linker::userLink( $userId, $userText );
			$user .= $this->getContext()->msg( 'word-separator' )->plain();
			$user .= '<span style="white-space: nowrap;">';
			$user .= Linker::userToolLinks( $userId, $userText );
			$user .= '</span>';
		} else {
			$user = htmlspecialchars( $userText );
		}

		$time = $this->getLanguage()->userTimeAndDate( $result->getTimestamp(), $this->getUser() );

		return "$plink . . $user . . $time";
	}
}
