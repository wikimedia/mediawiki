<?php
/**
 * Display informations about a page.
 * Very inefficient for the moment.
 *
 * Copyright © 2011 Alexandre Emsenhuber
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
 *
 * @file
 * @ingroup Actions
 */

class InfoAction extends FormlessAction {

	public function getName() {
		return 'info';
	}

	public function getRestriction() {
		return 'read';
	}

	protected function getDescription() {
		return '';
	}

	public function requiresWrite() {
		return false;
	}

	public function requiresUnblock() {
		return false;
	}

	protected function getPageTitle() {
		return wfMsg( 'pageinfo-title', $this->getTitle()->getSubjectPage()->getPrefixedText() );
	}

	public function onView() {
		global $wgDisableCounters;

		$title = $this->getTitle()->getSubjectPage();

		$pageInfo = self::pageCountInfo( $title );
		$talkInfo = self::pageCountInfo( $title->getTalkPage() );

		return Html::rawElement( 'table', array( 'class' => 'wikitable mw-page-info' ),
			Html::rawElement( 'tr', array(),
				Html::element( 'th', array(), '' ) .
				Html::element( 'th', array(), wfMsg( 'pageinfo-subjectpage' ) ) .
				Html::element( 'th', array(), wfMsg( 'pageinfo-talkpage' ) )
			) .
			Html::rawElement( 'tr', array(),
				Html::element( 'th', array( 'colspan' => 3 ), wfMsg( 'pageinfo-header-edits' ) )
			) .
			Html::rawElement( 'tr', array(),
				Html::element( 'td', array(), wfMsg( 'pageinfo-edits' ) ) .
				Html::element( 'td', array(), $this->getLang()->formatNum( $pageInfo['edits'] ) ) .
				Html::element( 'td', array(), $this->getLang()->formatNum( $talkInfo['edits'] ) )
			) .
			Html::rawElement( 'tr', array(),
				Html::element( 'td', array(), wfMsg( 'pageinfo-authors' ) ) .
				Html::element( 'td', array(), $this->getLang()->formatNum( $pageInfo['authors'] ) ) .
				Html::element( 'td', array(), $this->getLang()->formatNum( $talkInfo['authors'] ) )
			) .
			( !$this->getUser()->isAllowed( 'unwatchedpages' ) ? '' :
				Html::rawElement( 'tr', array(),
					Html::element( 'th', array( 'colspan' => 3 ), wfMsg( 'pageinfo-header-watchlist' ) )
				) .
				Html::rawElement( 'tr', array(),
					Html::element( 'td', array(), wfMsg( 'pageinfo-watchers' ) ) .
					Html::element( 'td', array( 'colspan' => 2 ), $this->getLang()->formatNum( $pageInfo['watchers'] ) )
				)
			).
			( $wgDisableCounters ? '' :
				Html::rawElement( 'tr', array(),
					Html::element( 'th', array( 'colspan' => 3 ), wfMsg( 'pageinfo-header-views' ) )
				) .
				Html::rawElement( 'tr', array(),
					Html::element( 'td', array(), wfMsg( 'pageinfo-views' ) ) .
					Html::element( 'td', array(), $this->getLang()->formatNum( $pageInfo['views'] ) ) .
					Html::element( 'td', array(), $this->getLang()->formatNum( $talkInfo['views'] ) )
				) .
				Html::rawElement( 'tr', array(),
					Html::element( 'td', array(), wfMsg( 'pageinfo-viewsperedit' ) ) .
					Html::element( 'td', array(), $this->getLang()->formatNum( sprintf( '%.2f', $pageInfo['edits'] ? $pageInfo['views'] / $pageInfo['edits'] : 0 ) ) ) .
					Html::element( 'td', array(), $this->getLang()->formatNum( sprintf( '%.2f', $talkInfo['edits'] ? $talkInfo['views'] / $talkInfo['edits'] : 0 ) ) )
				)
			)
		);
	}

	/**
	 * Return the total number of edits and number of unique editors
	 * on a given page. If page does not exist, returns false.
	 *
	 * @param $title Title object
	 * @return mixed array or boolean false
	 */
	public static function pageCountInfo( $title ) {
		$id = $title->getArticleId();
		$dbr = wfGetDB( DB_SLAVE );

		$watchers = (int)$dbr->selectField(
			'watchlist',
			'COUNT(*)',
			array(
				'wl_title'     => $title->getDBkey(),
				'wl_namespace' => $title->getNamespace()
			),
			__METHOD__
		);

		$edits = (int)$dbr->selectField(
			'revision',
			'COUNT(rev_page)',
			array( 'rev_page' => $id ),
			__METHOD__
		);

		$authors = (int)$dbr->selectField(
			'revision',
			'COUNT(DISTINCT rev_user_text)',
			array( 'rev_page' => $id ),
			__METHOD__
		);

		$views = (int)$dbr->selectField(
			'page',
			'page_counter',
			array( 'page_id' => $id ),
			__METHOD__
		);

		return array( 'watchers' => $watchers, 'edits' => $edits,
			'authors' => $authors, 'views' => $views );
	}
}
