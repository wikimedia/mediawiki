<?php
/**
 * Changes page language for a page
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
 * @since 1.24
 * @author Kunal Grover, 2014
 */
class PageLanguage {
	/**
	 * Change page language function
	 *
	 * @param $page string Page name for which language needs to be changed
	 * @param $langNew string Language code for the language to be set as page language
	 *
	 * @return Status object
	 */
	public static function changeLanguage( $page, $langNew, $user = null ) {
		$title = Title::newFromText( $page );
		// Check if title is valid
		if ( !$title ) {
			$status = Status::newFatal( array( 'nopage' => 'badtitle' ) );
			return $status;
//			return array( 'nopage' => 'badtitle' );
		}

		$pageId = $title->getArticleID();
		if ( !$pageId ) {
			$status = Status::newFatal( array( 'nopage' => 'page-noexist' ) );
			return $status;
		}

		if ( !$title->userCan( 'edit' ) ) {
			$status = Status::newFatal( array( 'nopermission' => 'page-changelang-nopermission' ) );
			return $status;
		}

		// Get the current user if not set by the caller
		$user = $user ? $user : RequestContext::getMain()->getUser();

		// Get the default language for the wiki
		// Returns the default since the page is not loaded from DB
		$settings = $title->getPageLanguageSettings( false );
		$allowChange = $settings['usedb'];
		$defLang = $settings['pagelanguage']->getCode();
		$langOld = $settings['dbvalue'];

		// Change of language not allowed for the page
		if ( !$allowChange ) {
			$status = Status::newFatal( array( 'nopermission' => 'page-changelang-nopermission' ) );
			return $status;
		}

		// No change in language
		if ( $langNew === $langOld ) {
			$status = Status::newFatal( array( 'changelang-none' => 'changelang-none' ) );
			return $status;
		}

		// Hardcoded [def] if the language is set to null
		// Useful for the log formatter
		$logOld = $langOld ? $langOld : $defLang . '[def]';
		$logNew = $langNew ? $langNew : $defLang . '[def]';

		$dbw = wfGetDB( DB_MASTER );
		// Writing new page language to database
		$dbw->update(
			'page',
			array( 'page_lang' => $langNew ),
			array(
				'page_id' => $pageId,
				'page_lang' => $langOld
			),
			__METHOD__
		);

		if ( !$dbw->affectedRows() ) {
			$status = Status::newFatal( array( 'changelang-none' => 'changelang-none' ) );
			return $status;
		}

		// Logging change of language
		$logParams = array(
			'4::oldlanguage' => $logOld,
			'5::newlanguage' => $logNew
		);
		$entry = new ManualLogEntry( 'pagelang', 'pagelang' );
		$entry->setPerformer( $user );
		$entry->setTarget( $title );
		$entry->setParameters( $logParams );

		$logid = $entry->insert();
		$entry->publish( $logid );

		$status = Status::newGood( array( 'success' => 'changelang-success' ) );
		return $status;
	}
}
