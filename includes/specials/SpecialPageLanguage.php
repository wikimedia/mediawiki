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

	/**
	 * @var array Options selector options
	 */
	private $options;

	/**
	 * @var string Language code for the default wiki language
	 */
	private $defLang;

	public function __construct() {
		parent::__construct( 'PageLanguage', 'pagelang' );
	}

	public function doesWrites() {
		return true;
	}

	protected function preText() {
		$this->getOutput()->addModules( 'mediawiki.special.pageLanguage' );
	}

	protected function getFormFields() {
		// Get default from the subpage of Special page
		$defaultName = $this->par;

		// Get default language code for wiki
		$this->defLang = $this->getConfig()->get( 'LanguageCode' );

		$page = [];
		$page['pagename'] = [
			'type' => 'title',
			'label-message' => 'pagelang-name',
			'default' => $defaultName,
			'autofocus' => $defaultName === null,
			'exists' => true,
		];

		// Options for whether to use the default language or select language
		$selectoptions = [
			(string)$this->msg( 'pagelang-use-default' )->escaped() => 1,
			(string)$this->msg( 'pagelang-select-lang' )->escaped() => 2
		];
		$this->options = array_values($selectoptions);

		$page['selectoptions'] = [
			'id' => 'mw-pl-options',
			'type' => 'radio',
			'options' => $selectoptions,
			'default' => $this->options[0]
		];

		// Building a language selector
		$userLang = $this->getLanguage()->getCode();
		$languages = Language::fetchLanguageNames( $userLang, 'mwfile' );
		ksort( $languages );
		$options = [];
		foreach ( $languages as $code => $name ) {
			$options["$code - $name"] = $code;
		}

		$page['language'] = [
			'id' => 'mw-pl-languageselector',
			'cssclass' => 'mw-languageselector',
			'type' => 'select',
			'options' => $options,
			'label-message' => 'pagelang-language',
			'default' => $this->defLang,
		];

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
		Hooks::run( 'LanguageSelector', [ $this->getOutput(), 'mw-languageselector' ] );
		$form->setSubmitTextMsg( 'pagelang-submit' );
	}

	/**
	 *
	 * @param array $data
	 * @return Status
	 */
	public function onSubmit( array $data ) {
		// Check if user wants to use default language
		if ( $data['selectoptions'] == $this->options[0] ) {
			$lang = null;
		} else {
			$lang = $data['language'];
		}

		$page = $data['pagename'];
		$title = Title::newFromText( $page );

		// Check if title is valid
		if ( !$title ) {
			return Status::newFatal( 'badtitle' );
		}

		$this->redirectTitle = $title;
		$result = PageLanguage::changeLanguage( $title, $lang, $this->getUser(), $this->defLang );
		return $result;
	}

	public function onSuccess() {
		// Title to redirect to after the operation
		$title = $this->redirectTitle;

		// Redirect to the page
		$url = $title->getFullURL();
		$this->getOutput()->redirect( $url );
	}

	function showLogFragment( $title ) {
		$moveLogPage = new LogPage( 'pagelang' );
		$out1 = Xml::element( 'h2', null, $moveLogPage->getName()->text() );
		$out2 = '';
		LogEventsList::showLogExtract( $out2, 'pagelang', $title );
		return $out1 . $out2;
	}

	/**
	 * Return an array of subpages beginning with $search that this special page will accept.
	 *
	 * @param string $search Prefix to search for
	 * @param int $limit Maximum number of results to return (usually 10)
	 * @param int $offset Number of results to skip (usually 0)
	 * @return string[] Matching subpages
	 */
	public function prefixSearchSubpages( $search, $limit, $offset ) {
		return $this->prefixSearchString( $search, $limit, $offset );
	}

	protected function getGroupName() {
		return 'pagetools';
	}
}
