<?php
/**
 * Implements Special:Tags
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
 */

/**
 * A special page that lists tags for edits
 *
 * @ingroup SpecialPage
 */
class SpecialTags extends SpecialPage {

	function __construct() {
		parent::__construct( 'Tags' );
	}

	function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'tags-title' ) );
		$out->wrapWikiMsg( "<div class='mw-tags-intro'>\n$1\n</div>", 'tags-intro' );

		// Write the headers
		$html = Xml::tags( 'tr', null, Xml::tags( 'th', null, $this->msg( 'tags-tag' )->parse() ) .
				Xml::tags( 'th', null, $this->msg( 'tags-display-header' )->parse() ) .
				Xml::tags( 'th', null, $this->msg( 'tags-description-header' )->parse() ) .
				Xml::tags( 'th', null, $this->msg( 'tags-hitcount-header' )->parse() )
			);
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'change_tag', array( 'ct_tag', 'hitcount' => 'count(*)' ),
			array(), __METHOD__, array( 'GROUP BY' => 'ct_tag', 'ORDER BY' => 'hitcount DESC' ) );

		foreach ( $res as $row ) {
			$html .= $this->doTagRow( $row->ct_tag, $row->hitcount );
		}

		foreach( ChangeTags::listDefinedTags() as $tag ) {
			$html .= $this->doTagRow( $tag, 0 );
		}

		$out->addHTML( Xml::tags( 'table', array( 'class' => 'wikitable mw-tags-table' ), $html ) );
	}

	function doTagRow( $tag, $hitcount ) {
		static $doneTags = array();

		if ( in_array( $tag, $doneTags ) ) {
			return '';
		}

		$newRow = '';
		$newRow .= Xml::tags( 'td', null, Xml::element( 'code', null, $tag ) );

		$disp = ChangeTags::tagDescription( $tag );
		$disp .= ' ';
		$editLink = Linker::link( Title::makeTitle( NS_MEDIAWIKI, "Tag-$tag" ), $this->msg( 'tags-edit' )->escaped() );
		$disp .= $this->msg( 'parentheses' )->rawParams( $editLink )->escaped();
		$newRow .= Xml::tags( 'td', null, $disp );

		$msg = $this->msg( "tag-$tag-description" );
		$desc = !$msg->exists() ? '' : $msg->parse();
		$desc .= ' ';
		$editDescLink = Linker::link( Title::makeTitle( NS_MEDIAWIKI, "Tag-$tag-description" ), $this->msg( 'tags-edit' )->escaped() );
		$desc .= $this->msg( 'parentheses' )->rawParams( $editDescLink )->escaped();
		$newRow .= Xml::tags( 'td', null, $desc );

		$hitcount = $this->msg( 'tags-hitcount' )->numParams( $hitcount )->escaped();
		$hitcount = Linker::link( SpecialPage::getTitleFor( 'Recentchanges' ), $hitcount, array(), array( 'tagfilter' => $tag ) );
		$newRow .= Xml::tags( 'td', null, $hitcount );

		$doneTags[] = $tag;

		return Xml::tags( 'tr', null, $newRow ) . "\n";
	}
}
