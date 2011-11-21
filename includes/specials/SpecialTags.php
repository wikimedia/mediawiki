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

if (!defined('MEDIAWIKI'))
	die;

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
		$html = Xml::tags( 'tr', null, Xml::tags( 'th', null, wfMsgExt( 'tags-tag', 'parseinline' ) ) .
				Xml::tags( 'th', null, wfMsgExt( 'tags-display-header', 'parseinline' ) ) .
				Xml::tags( 'th', null, wfMsgExt( 'tags-description-header', 'parseinline' ) ) .
				Xml::tags( 'th', null, wfMsgExt( 'tags-hitcount-header', 'parseinline' ) )
			);
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'change_tag', array( 'ct_tag', 'count(*) AS hitcount' ),
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
		$newRow .= Xml::tags( 'td', null, Xml::element( 'tt', null, $tag ) );

		$disp = ChangeTags::tagDescription( $tag );
		$disp .= ' (' . Linker::link( Title::makeTitle( NS_MEDIAWIKI, "Tag-$tag" ), wfMsgHtml( 'tags-edit' ) ) . ')';
		$newRow .= Xml::tags( 'td', null, $disp );

		$msg = wfMessage( "tag-$tag-description" );
		$desc = !$msg->exists() ? '' : $msg->parse();
		$desc .= ' (' . Linker::link( Title::makeTitle( NS_MEDIAWIKI, "Tag-$tag-description" ), wfMsgHtml( 'tags-edit' ) ) . ')';
		$newRow .= Xml::tags( 'td', null, $desc );

		$hitcount = wfMsgExt( 'tags-hitcount', array( 'parsemag' ), $this->getLanguage()->formatNum( $hitcount ) );
		$hitcount = Linker::link( SpecialPage::getTitleFor( 'Recentchanges' ), $hitcount, array(), array( 'tagfilter' => $tag ) );
		$newRow .= Xml::tags( 'td', null, $hitcount );

		$doneTags[] = $tag;

		return Xml::tags( 'tr', null, $newRow ) . "\n";
	}
}
