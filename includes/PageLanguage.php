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
 * @since 1.28
 * @author Kunal Grover, 2016
 */
class PageLanguage {
	/**
	 * Change page language function
	 *
	 * @param Title $title Object for the page for which language needs to be changed
	 * @param string|null $langNew Language code for the language to be set as page language (null
	 *  for default wiki language)
	 * @param User $user object User making the change
	 *
	 * @return Status object
	 */
	public static function changeLanguage( Title $title, $langNew, $user ) {
		$status = Status::newGood();

		// Check if the language code is valid
		if ( $langNew !== null && !Language::isValidBuiltInCode( $langNew ) ) {
			$status->fatal( 'badlanguage-code' );
			return $status;
		}

		$pageId = $title->getArticleID();
		if ( !$pageId ) {
			$status->fatal( 'nopagetext' );
			return $status;
		}

		// Change of language not allowed for the page
		// Both rights to edit a page and pagelang permission needed
		if ( !self::changeAllowed( $title ) || !$title->userCan( 'edit' )
			|| !$user->isAllowed( 'pagelang' ) ) {
			$status->fatal( 'page-changelang-nopermission' );
			return $status;
		}

		$defLang = RequestContext::getMain()->getConfig()->get( 'LanguageCode' );

		// Load the page language from DB
		$dbw = wfGetDB( DB_MASTER );
		$langOld = $dbw->selectField(
			'page',
			'page_lang',
			[ 'page_id' => $pageId ],
			__METHOD__
		);

		// No change in language
		if ( $langNew === $langOld ) {
			$status->fatal( 'changelang-none' );
			return $status;
		}

		// Hardcoded [def] if the language is set to null
		// Useful for the log formatter
		$logOld = $langOld ? $langOld : $defLang . '[def]';
		$logNew = $langNew ? $langNew : $defLang . '[def]';

		// Writing new page language to database
		$dbw->update(
			'page',
			[ 'page_lang' => $langNew ],
			[
				'page_id' => $pageId,
				'page_lang' => $langOld
			],
			__METHOD__
		);

		if ( !$dbw->affectedRows() ) {
			$status->fatal( 'changelang-fail' );
			return $status;
		}

		// Purge the page's contents
		$page = WikiPage::factory( $title );
		$page->doPurge();

		// Logging change of language
		$logParams = [
			'4::oldlanguage' => $logOld,
			'5::newlanguage' => $logNew
		];
		$entry = new ManualLogEntry( 'pagelang', 'pagelang' );
		$entry->setPerformer( $user );
		$entry->setTarget( $title );
		$entry->setParameters( $logParams );

		$logid = $entry->insert();
		$entry->publish( $logid );

		return $status;
	}

	/**
	 * Check if changing language for a page is allowed
	 *
	 * @param Title $title Object for the page for which language needs to be changed
	 *
	 * @return bool
	 */
	private static function changeAllowed( Title $title ) {
		global $wgPageLanguageUseDB;

		if ( !$wgPageLanguageUseDB ) {
			return false;
		}
		if ( $title->isSpecialPage() || $title->getNamespace() == NS_MEDIAWIKI ) {
			return false;
		}

		return true;
	}

}
