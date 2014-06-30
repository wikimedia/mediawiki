<?php
/**
 *
 * Copyright © 2014 Kunal Grover
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

/**
 * API module to allow change page language. Requires API write mode
 * to be enabled.
 *
 * @ingroup API
 */
class ApiPageLanguage extends ApiBase {
	public function execute() {
		$user = $this->getUser();
		$params = $this->extractRequestParams();

		if ( !$user->isAllowed( 'pagelang' ) ) {
			$this->dieUsageMsg( 'cantchangelang' );
		}

		$langNew = $params['lang'];
		$page = $params['page'];
		$title = Title::newFromText( $page );

		// Check if title is valid
		if ( !$title ) {
			$this->dieUsageMsg( 'invalidtitle' );
		}

		$rights = $user->getRights();

		if ( $title->isProtected() && !in_array( 'protect', $rights ) ) {
			$this->dieUsageMsg( 'protectedpage' );
		}

		// For semi protected pages, user should be logged in
		if ( $title->isSemiProtected() && !$user->isLoggedIn() ) {
			$this->dieUsageMsg( 'protectedpage' );
		}

		// Get the default language for the wiki
		// Returns the default since the page is not loaded from DB
		$defLang = $title->getPageLanguageSettings( false )['pagelanguage']->getCode();

		$pageId =  $title->getArticleID();

		// Check if article exists
		if ( !$pageId ) {
			$this->dieUsageMsg( 'nocreate-missing' );
		}

		// Load the page language from DB
		$dbw = wfGetDB( DB_MASTER );
		$langOld = $dbw->selectField(
			'page',
			'page_lang',
			array( 'page_id' => $pageId ),
			__METHOD__
		);

		// No change in language
		if ( $langNew === $langOld ) {
			$this->dieUsageMsg( 'no-change' );
		}

		// Hardcoded [def] if the language is set to null
		$logOld = $langOld ? $langOld : $defLang . '[def]';
		$logNew = $langNew ? $langNew : $defLang . '[def]';

		// Writing new page language to database
		$dbw = wfGetDB( DB_MASTER );
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
			$this->dieUsageMsg( 'no-change' );
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

		$res['langOld'] = $langOld;
		$res['langNew'] = $langNew;
		$res['defLang'] = $defLang;
		$this->getResult()->addValue( null, $this->getModuleName(), $res );
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return array(
			'page' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
			'lang' => array(
				ApiBase::PARAM_TYPE => 'string'
			),
			'token' => null
		);
	}

	public function getParamDescription() {
		return array(
			'page' => 'Page for which language is being changed.',
			'lang' => 'New language for page.'
		);
	}

	public function getDescription() {
		return 'Change page language for a page.';
	}

	public function needsToken() {
		return true;
	}

	public function getTokenSalt() {
		return '';
	}

	public function getExamples() {
		return 'api.php?action=pagelang&page=Main_page&lang=es';
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:PageLanguage';
	}
}
