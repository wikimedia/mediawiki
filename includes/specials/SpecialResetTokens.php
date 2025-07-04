<?php
/**
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

namespace MediaWiki\Specials;

use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\MainConfigNames;
use MediaWiki\SpecialPage\FormSpecialPage;
use MediaWiki\SpecialPage\SpecialPage;

/**
 * Let users reset tokens like the watchlist token.
 *
 * @ingroup SpecialPage
 * @ingroup Auth
 * @deprecated since 1.26
 */
class SpecialResetTokens extends FormSpecialPage {
	/** @var array|null */
	private $tokensList;

	public function __construct() {
		parent::__construct( 'ResetTokens' );
	}

	/** @inheritDoc */
	public function doesWrites() {
		return true;
	}

	/** @inheritDoc */
	public function requiresUnblock() {
		return false;
	}

	/**
	 * Returns the token information list for this page after running
	 * the hook and filtering out disabled preferences.
	 *
	 * @return array
	 */
	protected function getTokensList() {
		if ( !$this->tokensList ) {
			$tokens = [
				[ 'preference' => 'watchlisttoken', 'label-message' => 'resettokens-watchlist-token' ],
			];
			$this->getHookRunner()->onSpecialResetTokensTokens( $tokens );

			$hiddenPrefs = $this->getConfig()->get( MainConfigNames::HiddenPrefs );
			$tokens = array_filter( $tokens, static function ( $tok ) use ( $hiddenPrefs ) {
				return !in_array( $tok['preference'], $hiddenPrefs );
			} );

			$this->tokensList = $tokens;
		}

		return $this->tokensList;
	}

	/** @inheritDoc */
	public function execute( $par ) {
		// This is a preferences page, so no user JS for y'all.
		$this->getOutput()->disallowUserJs();
		$this->requireNamedUser();

		parent::execute( $par );

		$this->getOutput()->addReturnTo( SpecialPage::getTitleFor( 'Preferences' ) );
	}

	public function onSuccess() {
		$this->getOutput()->wrapWikiMsg(
			Html::successBox( '$1' ),
			'resettokens-done'
		);
	}

	/**
	 * Display appropriate message if there's nothing to do.
	 * The submit button is also suppressed in this case (see alterForm()).
	 * @return array
	 */
	protected function getFormFields() {
		$user = $this->getUser();
		$tokens = $this->getTokensList();

		if ( $tokens ) {
			$tokensForForm = [];
			foreach ( $tokens as $tok ) {
				$label = $this->msg( 'resettokens-token-label' )
					->rawParams( $this->msg( $tok['label-message'] )->parse() )
					->params( $user->getTokenFromOption( $tok['preference'] ) )
					->escaped();
				$tokensForForm[$label] = $tok['preference'];
			}

			$desc = [
				'label-message' => 'resettokens-tokens',
				'type' => 'multiselect',
				'options' => $tokensForForm,
			];
		} else {
			$desc = [
				'label-message' => 'resettokens-no-tokens',
				'type' => 'info',
			];
		}

		return [
			'tokens' => $desc,
		];
	}

	/**
	 * Suppress the submit button if there's nothing to do;
	 * provide additional message on it otherwise.
	 */
	protected function alterForm( HTMLForm $form ) {
		$form->setSubmitDestructive();
		if ( $this->getTokensList() ) {
			$form->setSubmitTextMsg( 'resettokens-resetbutton' );
		} else {
			$form->suppressDefaultSubmit();
		}
	}

	/** @inheritDoc */
	protected function getDisplayFormat() {
		return 'ooui';
	}

	/** @inheritDoc */
	public function onSubmit( array $formData ) {
		if ( $formData['tokens'] ) {
			$user = $this->getUser();
			foreach ( $formData['tokens'] as $tokenPref ) {
				$user->resetTokenFromOption( $tokenPref );
			}
			$user->saveSettings();

			return true;
		}

		return false;
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'login';
	}

	/** @inheritDoc */
	public function isListed() {
		return (bool)$this->getTokensList();
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialResetTokens::class, 'SpecialResetTokens' );
