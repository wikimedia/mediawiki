<?php
/**
 * Implements Special:Mostredirects
 *
 * Copyright © 2012 Umherirrender
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
 * @author Umherirrender
 */

/**
 * A special page that list pages that have highest redirects linking to them
 *
 * @ingroup SpecialPage
 */
class MostredirectsPage extends QueryPage {

	function __construct( $name = 'Mostredirects' ) {
		parent::__construct( $name );
	}

	function isExpensive() { return true; }
	function isSyndicated() { return false; }

	function getQueryInfo() {
		return array (
			'tables' => array (
				'redirect',
				'page'
			),
			'fields' => array (
				'namespace' => 'rd_namespace',
				'title' => 'rd_title',
				'value' => 'COUNT(*)'
			),
			'conds' => array (
				'rd_namespace' => MWNamespace::getContentNamespaces(),
				'rd_interwiki IS NULL OR rd_interwiki = \'\'', //only local redirects
			),
			'options' => array (
				'HAVING' => 'COUNT(*) > 1',
				'GROUP BY' => array (
					'rd_namespace',
					'rd_title'
				)
			),
			'join_conds' => array (
				'page' => array (
					'LEFT JOIN',
					'page_id = rd_from'
				)
			)
		);
	}

	/**
	 * Pre-fill the link cache
	 *
	 * @param $db DatabaseBase
	 * @param $res
	 */
	function preprocessResults( $db, $res ) {
		# There's no point doing a batch check if we aren't caching results;
		# the page must exist for it to have been pulled out of the table
		if ( !$this->isCached() || !$res->numRows() ) {
			return;
		}

		$batch = new LinkBatch;
		foreach ( $res as $row ) {
			$batch->add( $row->namespace, $row->title );
		}
		$batch->execute();

		// Back to start for display
		$res->seek( 0 );
	}

	/**
	 * Make a link to "what links here" for the specified title
	 *
	 * @param $title Title being queried
	 * @param $caption String: text to display on the link
	 * @return String
	 */
	function makeWlhLink( $title, $caption ) {
		$wlh = SpecialPage::getTitleFor( 'Whatlinkshere', $title->getPrefixedDBkey() );
		return Linker::linkKnown( $wlh, $caption, array(), array( 'hidetrans' => '1', 'hidelinks' => '1' ) );
	}

	/**
	 * @param $skin Skin
	 * @param $result
	 * @return string
	 */
	function formatResult( $skin, $result ) {
		$title = Title::makeTitleSafe( $result->namespace, $result->title );
		if ( !$title ) {
			return Html::element( 'span', array( 'class' => 'mw-invalidtitle' ),
				Linker::getInvalidTitleDescription( $this->getContext(), $result->namespace, $result->title ) );
		}

		if ( $this->isCached() ) {
			$link = Linker::link( $title );
		} else {
			$link = Linker::linkKnown( $title );
		}

		$wlh = $this->makeWlhLink( $title,
			$this->msg( 'nredirects' )->numParams( $result->value )->escaped() );
		return $this->getLanguage()->specialList( $link, $wlh );
	}
}
