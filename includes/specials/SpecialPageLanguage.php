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
 */

/**
 * Special page for changing the content language of a page
 *
 * @ingroup SpecialPage
 */
class SpecialPageLanguage extends FormSpecialPage {
	/**
	 * @var $goToUrl URL to go to if language change successful
	 */
	private $goToUrl;

	public function __construct() {
		parent::__construct( 'PageLanguage', 'changelang' );
	}

	protected function preText() {
		$this->getOutput()->addModules( 'mediawiki.special.pageLanguage' );
	}

	protected function getFormFields() {
		global $wgLanguageCode;
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
			(string)$this->msg( 'pagelang-use-default')->inContentLanguage() => 1,
			(string)$this->msg( 'pagelang-select-lang' )->inContentLanguage() => 2,
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
			'type' => 'select',
			'options' => $options,
			'label-message' => 'pagelang-language',
			'default' => $wgLanguageCode
		);

		return $page;
	}

	public function alterForm( HTMLForm $form ) {
		$form->setDisplayFormat( 'vform' );
		$form->setWrapperLegend( false );
	}

	/**
	 *
	 * @param array $data
	 */
	public function onSubmit( array $data ) {
		global $wgLanguageCode;
		if( $data['pagename'] === '' ) {
			return false;
		}

		$title = Title::newFromText( $data['pagename'] );

		// Check if title is valid
		if ( !isset( $title ) ) {
			return false;
		}

		$pageId =  $title->getArticleID();

		// Check if article exists
		if( !$pageId ) {
			return false;
		}

		// This loads all DB fields
		$title = Title::newFromID( $pageId );
		$langOld = $title->getPageLanguage()->getCode();
		$this->goToUrl = $title->getFullURL();

		// Check if user wants to use default language
		if( $data['selectoptions'] == 1 ) {
			$langNew = $wgLanguageCode;
		} else {
			$langNew = $data['language'];
		}

		// Invalid language change
		if( $langNew === $langOld ) {
			return false;
		}

		// Writing new page language to database
		$dbw = wfGetDB( DB_MASTER );
		$dbw->update(
			'page',
			array( 'page_lang' => $langNew ),
			array( 'page_id' => $pageId ),
			__METHOD__
		);

		// Logging change of language
		$logParams = array(
			'oldlanguage' => $langOld,
			'newlanguage' => $langNew
		);
		$entry = new ManualLogEntry( 'changelang', 'changelang' );
		$entry->setPerformer( $this->getUser() );
		$entry->setTarget( $title );
		$entry->setParameters( $logParams );

		$userLang = $this->getLanguage()->getCode();
		$langNewName = Language::fetchLanguageName( $langNew, $userLang );
		$langOldName = Language::fetchLanguageName( $langOld, $userLang );
		$entry->setComment( $langOldName . "->" . $langNewName );

		$logid = $entry->insert();
		$entry->publish( $logid );

		return true;
	}

	public function onSuccess() {
		// Success causes a redirect
		$this->getOutput()->redirect( $this->goToUrl );
	}
}
