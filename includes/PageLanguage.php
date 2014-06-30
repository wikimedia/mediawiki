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
 */
class PageLanguage {
	function __construct() {
	}

	public static function changeLanguage( $page, $langNew ) {
		global $wgUser;

		$title = Title::newFromText( $page );
		// Check if title is valid
		if ( !$title ) {
			return array( 'nopage' => wfMessage( 'badtitle' )->text() );
		}

		$pageId = $title->getArticleID();
		if ( !$pageId ) {
			return array( 'nopage' => wfMessage( 'page-noexist' )->text() );
		}

		if ( !$title->userCan( 'edit' ) ) {
			return array( 'nopermission' => wfMessage( 'page-changelang-nopermissions' )->text() );
		}

		// Get the default language for the wiki
		// Returns the default since the page is not loaded from DB
		$settings = $title->getPageLanguageSettings( false );
		$allowChange = $settings['usedb'];
		$defLang = $settings['pagelanguage']->getCode();
		$langOld = $settings['dbvalue'];

		// Change of language not allowed for the page
		if ( !$allowChange ) {
			return array( 'nopermission' => wfMessage( 'page-changelang-nopermissions' )->text() );
		}

		// No change in language
		if ( $langNew === $langOld ) {
			return array( 'changelang-none' => wfMessage( 'changelang-none' )->text() );
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
			return array( 'changelang-none' => wfMessage( 'changelang-none' )->text() );
		}

		// Logging change of language
		$logParams = array(
			'4::oldlanguage' => $logOld,
			'5::newlanguage' => $logNew
		);
		$entry = new ManualLogEntry( 'pagelang', 'pagelang' );
		$entry->setPerformer( $wgUser );
		$entry->setTarget( $title );
		$entry->setParameters( $logParams );

		$logid = $entry->insert();
		$entry->publish( $logid );

		return true;
	}
}