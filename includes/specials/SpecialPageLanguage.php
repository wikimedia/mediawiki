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
	 * @var Title object for page to go to on successful change of language
	 */
	private $redirectTitle;

	public function __construct() {
		parent::__construct( 'PageLanguage', 'pagelang' );
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
		// Check if user wants to use default language
		if ( $data['selectoptions'] == 1 ) {
			$lang = null;
		} else {
			$lang = $data['language'];
		}

		$page = $data['pagename'];
		$title = Title::newFromText( $page );
		$result = PageLanguage::changeLanguage( $title, $lang, $this->getUser() );

		if ( $result->isOk() ) {
			$this->redirectTitle = $title;
			return true;
		}
		return $result->getErrorsArray();
	}

	public function onSuccess() {
		// Title to redirect to after the operation
		$title = $this->redirectTitle;

		// Purge the page's contents
		$page = WikiPage::factory( $title );
		$page->doPurge();

		// Redirect to the page
		$url = $title->getFullURL();
		$this->getOutput()->redirect( $url );
	}
}
