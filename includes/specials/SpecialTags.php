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
	/**
	 * @var array List of defined tags
	 */
	public $definedTags;

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
				Xml::tags( 'th', null, $this->msg( 'tags-active-header' )->parse() ) .
				Xml::tags( 'th', null, $this->msg( 'tags-hitcount-header' )->parse() )
			);

		// Used in #doTagRow()
		$this->definedTags = array_fill_keys( ChangeTags::listDefinedTags(), true );

		foreach ( ChangeTags::tagUsageStatistics() as $tag => $hitcount ) {
			$html .= $this->doTagRow( $tag, $hitcount );
		}

		$out->addHTML( Xml::tags(
			'table',
			array( 'class' => 'wikitable sortable mw-tags-table' ),
			$html
		) );
	}

	function doTagRow( $tag, $hitcount ) {
		$user = $this->getUser();
		$newRow = '';
		$newRow .= Xml::tags( 'td', null, Xml::element( 'code', null, $tag ) );

		$disp = ChangeTags::tagDescription( $tag );
		if ( $user->isAllowed( 'editinterface' ) ) {
			$disp .= ' ';
			$editLink = Linker::link(
				Title::makeTitle( NS_MEDIAWIKI, "Tag-$tag" ),
				$this->msg( 'tags-edit' )->escaped()
			);
			$disp .= $this->msg( 'parentheses' )->rawParams( $editLink )->escaped();
		}
		$newRow .= Xml::tags( 'td', null, $disp );

		$msg = $this->msg( "tag-$tag-description" );
		$desc = !$msg->exists() ? '' : $msg->parse();
		if ( $user->isAllowed( 'editinterface' ) ) {
			$desc .= ' ';
			$editDescLink = Linker::link(
				Title::makeTitle( NS_MEDIAWIKI, "Tag-$tag-description" ),
				$this->msg( 'tags-edit' )->escaped()
			);
			$desc .= $this->msg( 'parentheses' )->rawParams( $editDescLink )->escaped();
		}
		$newRow .= Xml::tags( 'td', null, $desc );

		$active = isset( $this->definedTags[$tag] ) ? 'tags-active-yes' : 'tags-active-no';
		$active = $this->msg( $active )->escaped();
		$newRow .= Xml::tags( 'td', null, $active );

		$hitcountLabel = $this->msg( 'tags-hitcount' )->numParams( $hitcount )->escaped();
		$hitcountLink = Linker::link(
			SpecialPage::getTitleFor( 'Recentchanges' ),
			$hitcountLabel,
			array(),
			array( 'tagfilter' => $tag )
		);

		// add raw $hitcount for sorting, because tags-hitcount contains numbers and letters
		$newRow .= Xml::tags( 'td', array( 'data-sort-value' => $hitcount ), $hitcountLink );

		return Xml::tags( 'tr', null, $newRow ) . "\n";
	}

	protected function getGroupName() {
		return 'changes';
	}
}
