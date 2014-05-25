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
	 * @var $titleObj Title Object
	 */
	private $titleObj;

	public function __construct() {
		parent::__construct( 'PageLanguage' );
	}

	public function userCanExecute( User $user ) {
		return $this->getUser()->isLoggedIn();
	}

	protected function getFormFields() {
		global $wgLang;
		$page = array();
		$page['pagename'] = array(
			'type' => 'text',
			'label-message' => 'pagelang-name',
		);

		// Building a language selector
		$userLang = $wgLang->getCode();
		$languages = Language::fetchLanguageNames( $userLang, 'mwfile' );
		ksort( $languages );
		$options = array();
		foreach ( $languages as $code => $name ) {
			$options["$code - $name"] = $code;
		}

		$page['language'] = array(
			'type' => 'select',
			'options' => $options,
			'label-message' => 'pagelang-language'
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
	global $wgUser, $wgLang;
	if ( $data['pagename'] !== '' ) {
		$title = Title::newFromText( $data['pagename'] );
		$this->titleObj = $title;
		$page_id =  $title->getArticleID();

		// Check if article exists
		if( !$page_id ) {
			return false;
		}
		$lang = $data['language'];
		$dbw = wfGetDB( DB_MASTER );
		$dbw->update( 'page',
			array(
				'page_lang' => $lang
			),
			array(
				'page_id' => $page_id
			),
			__METHOD__
		);

		// Logging change of language
		$logParams = array(
			'language' => $lang
		);
		$entry = new ManualLogEntry( 'changelang', 'changelang' );
		$entry->setPerformer( $this->getUser() );
		$entry->setTarget( $title );
		$entry->setParameters( $logParams );

		// Don't know if it does the correct thing
		$userLang = $wgLang->getCode();
		$l = Language::fetchLanguageName( $lang, $userLang );
		$entry->setComment( $l );

		$logid = $entry->insert();
		$entry->publish( $logid );
		$p = array(
			'True',
			$title
		);
		return true;
	}
	}

	public function onSuccess() {
		// Success causes a redirect
		$this->getOutput()->redirect( $this->titleObj );
	}
}
