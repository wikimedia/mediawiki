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
		return 'Information for page';
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

		$userCanViewUnwatchedPages = $this->getUser()->isAllowed( 'unwatchedpages' );

		$pageInfo = self::pageCountInfo( $title, $userCanViewUnwatchedPages, $wgDisableCounters );
		$talkInfo = self::pageCountInfo( $title->getTalkPage(), $userCanViewUnwatchedPages, $wgDisableCounters );

		$lang = $this->getLanguage();

		$content =
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
					Html::element( 'td', array(), $lang->formatNum( $pageInfo['edits'] ) ) .
					Html::element( 'td', array(), $lang->formatNum( $talkInfo['edits'] ) )
			) .
			Html::rawElement( 'tr', array(),
				Html::element( 'td', array(), $this->msg( 'pageinfo-authors' )->text() ) .
					Html::element( 'td', array(), $lang->formatNum( $pageInfo['authors'] ) ) .
					Html::element( 'td', array(), $lang->formatNum( $talkInfo['authors'] ) )
			);

		if ( $userCanViewUnwatchedPages ) {
			$content .= Html::rawElement( 'tr', array(),
				Html::element( 'th', array( 'colspan' => 3 ), $this->msg( 'pageinfo-header-watchlist' )->text() )
			) .
				Html::rawElement( 'tr', array(),
					Html::element( 'td', array(), $this->msg( 'pageinfo-watchers' )->text() ) .
						Html::element( 'td', array( 'colspan' => 2 ), $lang->formatNum( $pageInfo['watchers'] ) )
				);
		}

		if ( $wgDisableCounters ) {
			$content .= Html::rawElement( 'tr', array(),
				Html::element( 'th', array( 'colspan' => 3 ), $this->msg( 'pageinfo-header-views' )->text() )
			) .
				Html::rawElement( 'tr', array(),
					Html::element( 'td', array(), $this->msg( 'pageinfo-views' )->text() ) .
						Html::element( 'td', array(), $lang->formatNum( $pageInfo['views'] ) ) .
						Html::element( 'td', array(), $lang->formatNum( $talkInfo['views'] ) )
				) .
				Html::rawElement( 'tr', array(),
					Html::element( 'td', array(), $this->msg( 'pageinfo-viewsperedit' )->text() ) .
						Html::element( 'td', array(), $lang->formatNum( sprintf( '%.2f', $pageInfo['edits'] ? $pageInfo['views'] / $pageInfo['edits'] : 0 ) ) ) .
						Html::element( 'td', array(), $lang->formatNum( sprintf( '%.2f', $talkInfo['edits'] ? $talkInfo['views'] / $talkInfo['edits'] : 0 ) ) )
				);
		}
		return Html::rawElement( 'table', array( 'class' => 'wikitable mw-page-info' ), $content );
	}

	/**
	 * Return the total number of edits and number of unique editors
	 * on a given page. If page does not exist, returns false.
	 *
	 * @param $title Title object
	 * @param $canViewUnwatched bool
	 * @param $disableCounter bool
	 * @return array
	 */
	public static function pageCountInfo( $title, $canViewUnwatched, $disableCounter ) {
		wfProfileIn( __METHOD__ );
		$id = $title->getArticleID();
		$dbr = wfGetDB( DB_SLAVE );

		$result = array();
		if ( $canViewUnwatched ) {
			$watchers = (int)$dbr->selectField(
				'watchlist',
				'COUNT(*)',
				array(
					'wl_namespace' => $title->getNamespace(),
					'wl_title'     => $title->getDBkey(),
				),
				__METHOD__
			);
			$result['watchers'] = $watchers;
		}

		$edits = (int)$dbr->selectField(
			'revision',
			'COUNT(rev_page)',
			array( 'rev_page' => $id ),
			__METHOD__
		);
		$result['edits'] = $edits;

		$authors = (int)$dbr->selectField(
			'revision',
			'COUNT(DISTINCT rev_user_text)',
			array( 'rev_page' => $id ),
			__METHOD__
		);
		$result['authors'] = $authors;

		if ( !$disableCounter ) {
			$views = (int)$dbr->selectField(
				'page',
				'page_counter',
				array( 'page_id' => $id ),
				__METHOD__
			);
			$result['views'] = $views;
		}

		wfProfileOut( __METHOD__ );
		return $result;
	}
}
