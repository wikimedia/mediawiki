<?php
/**
 * Implements Special:PageLanguage
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
 * @author Kunal Grover
 * @since 1.24
 */

/**
 * Special page for changing the content language of a page
 *
 * @ingroup SpecialPage
 */
class SpecialPageLanguage extends FormSpecialPage {
	/**
	 * @var string URL to go to if language change successful
	 */
	private $goToUrl;

	public function __construct() {
		parent::__construct( 'PageLanguage', 'pagelang' );
	}

	protected function preText() {
		$this->getOutput()->addModules( 'mediawiki.special.pageLanguage' );
	}

	protected function getFormFields() {
		// Get default from the subpage of Special page
		$defaultName = $this->par;

		$page = array();
		$page['pagename'] = array(
			'type' => 'text',
			'label-message' => 'pagelang-name',
			'default' => $defaultName,
		);

		// Options for whether to use the default language or select language
		$selectoptions = array(
			(string)$this->msg( 'pagelang-use-default' )->escaped() => 1,
			(string)$this->msg( 'pagelang-select-lang' )->escaped() => 2,
		);
		$page['selectoptions'] = array(
			'id' => 'mw-pl-options',
			'type' => 'radio',
			'options' => $selectoptions,
			'default' => 1
		);

		// Building a language selector
		$userLang = $this->getLanguage()->getCode();
		$languages = Language::fetchLanguageNames( $userLang, 'mwfile' );
		ksort( $languages );
		$options = array();
		foreach ( $languages as $code => $name ) {
			$options["$code - $name"] = $code;
		}

		$page['language'] = array(
			'id' => 'mw-pl-languageselector',
			'cssclass' => 'mw-languageselector',
			'type' => 'select',
			'options' => $options,
			'label-message' => 'pagelang-language',
			'default' => $this->getConfig()->get( 'LanguageCode' ),
		);

		return $page;
	}

	protected function postText() {
		if ( $this->par ) {
			return $this->showLogFragment( $this->par );
		}
		return '';
	}

	protected function getDisplayFormat() {
		return 'ooui';
	}

	public function alterForm( HTMLForm $form ) {
		Hooks::run( 'LanguageSelector', array( $this->getOutput(), 'mw-languageselector' ) );
	}

	/**
	 *
	 * @param array $data
	 * @return bool
	 */
	public function onSubmit( array $data ) {
		$title = Title::newFromText( $data['pagename'] );

		// Check if title is valid
		if ( !$title ) {
			return false;
		}

		// Get the default language for the wiki
		// Returns the default since the page is not loaded from DB
		$defLang = $title->getPageLanguage()->getCode();

		$pageId = $title->getArticleID();

		// Check if article exists
		if ( !$pageId ) {
			return false;
		}

		// Load the page language from DB
		$dbw = wfGetDB( DB_MASTER );
		$langOld = $dbw->selectField(
			'page',
			'page_lang',
			array( 'page_id' => $pageId ),
			__METHOD__
		);

		// Url to redirect to after the operation
		$this->goToUrl = $title->getFullURL();

		// Check if user wants to use default language
		if ( $data['selectoptions'] == 1 ) {
			$langNew = null;
		} else {
			$langNew = $data['language'];
		}

		// No change in language
		if ( $langNew === $langOld ) {
			return false;
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
			return false;
		}

		// Logging change of language
		$logParams = array(
			'4::oldlanguage' => $logOld,
			'5::newlanguage' => $logNew
		);
		$entry = new ManualLogEntry( 'pagelang', 'pagelang' );
		$entry->setPerformer( $this->getUser() );
		$entry->setTarget( $title );
		$entry->setParameters( $logParams );

		$logid = $entry->insert();
		$entry->publish( $logid );

		return true;
	}

	public function onSuccess() {
		// Success causes a redirect
		$this->getOutput()->redirect( $this->goToUrl );
	}

	function showLogFragment( $title ) {
		$moveLogPage = new LogPage( 'pagelang' );
		$out1 = Xml::element( 'h2', null, $moveLogPage->getName()->text() );
		$out2 = '';
		LogEventsList::showLogExtract( $out2, 'pagelang', $title );
		return $out1 . $out2;
	}
}
