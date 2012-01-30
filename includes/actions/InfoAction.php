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
		return $this->msg( 'pageinfo-title', $this->getTitle()->getSubjectPage()->getPrefixedText() )->text();
	}

	public function onView() {
		global $wgDisableCounters;

		$title = $this->getTitle()->getSubjectPage();

		$pageInfo = self::pageCountInfo( $title );
		$talkInfo = self::pageCountInfo( $title->getTalkPage() );

		return Html::rawElement( 'table', array( 'class' => 'wikitable mw-page-info' ),
			Html::rawElement( 'tr', array(),
				Html::element( 'th', array(), '' ) .
				Html::element( 'th', array(), $this->msg( 'pageinfo-subjectpage' )->text() ) .
				Html::element( 'th', array(), $this->msg( 'pageinfo-talkpage' )->text() )
			) .
			Html::rawElement( 'tr', array(),
				Html::element( 'th', array( 'colspan' => 3 ), $this->msg( 'pageinfo-header-edits' )->text() )
			) .
			Html::rawElement( 'tr', array(),
				Html::element( 'td', array(), $this->msg( 'pageinfo-edits' )->text() ) .
				Html::element( 'td', array(), $this->getLanguage()->formatNum( $pageInfo['edits'] ) ) .
				Html::element( 'td', array(), $this->getLanguage()->formatNum( $talkInfo['edits'] ) )
			) .
			Html::rawElement( 'tr', array(),
				Html::element( 'td', array(), $this->msg( 'pageinfo-authors' )->text() ) .
				Html::element( 'td', array(), $this->getLanguage()->formatNum( $pageInfo['authors'] ) ) .
				Html::element( 'td', array(), $this->getLanguage()->formatNum( $talkInfo['authors'] ) )
			) .
			( !$this->getUser()->isAllowed( 'unwatchedpages' ) ? '' :
				Html::rawElement( 'tr', array(),
					Html::element( 'th', array( 'colspan' => 3 ), $this->msg( 'pageinfo-header-watchlist' )->text() )
				) .
				Html::rawElement( 'tr', array(),
					Html::element( 'td', array(), $this->msg( 'pageinfo-watchers' )->text() ) .
					Html::element( 'td', array( 'colspan' => 2 ), $this->getLanguage()->formatNum( $pageInfo['watchers'] ) )
				)
			).
			( $wgDisableCounters ? '' :
				Html::rawElement( 'tr', array(),
					Html::element( 'th', array( 'colspan' => 3 ), $this->msg( 'pageinfo-header-views' )->text() )
				) .
				Html::rawElement( 'tr', array(),
					Html::element( 'td', array(), $this->msg( 'pageinfo-views' )->text() ) .
					Html::element( 'td', array(), $this->getLanguage()->formatNum( $pageInfo['views'] ) ) .
					Html::element( 'td', array(), $this->getLanguage()->formatNum( $talkInfo['views'] ) )
				) .
				Html::rawElement( 'tr', array(),
					Html::element( 'td', array(), $this->msg( 'pageinfo-viewsperedit' )->text() ) .
					Html::element( 'td', array(), $this->getLanguage()->formatNum( sprintf( '%.2f', $pageInfo['edits'] ? $pageInfo['views'] / $pageInfo['edits'] : 0 ) ) ) .
					Html::element( 'td', array(), $this->getLanguage()->formatNum( sprintf( '%.2f', $talkInfo['edits'] ? $talkInfo['views'] / $talkInfo['edits'] : 0 ) ) )
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
