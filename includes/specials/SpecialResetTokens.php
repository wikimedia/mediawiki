<?php
/**
 * Implements Special:ResetTokens
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
 * Let users reset tokens like the watchlist token.
 *
 * @ingroup SpecialPage
 * @deprecated 1.26
 */
class SpecialResetTokens extends FormSpecialPage {
	private $tokensList;

	public function __construct() {
		parent::__construct( 'ResetTokens' );
	}

	/**
	 * Returns the token information list for this page after running
	 * the hook and filtering out disabled preferences.
	 *
	 * @return array
	 */
	protected function getTokensList() {
		if ( !isset( $this->tokensList ) ) {
			$tokens = array(
				array( 'preference' => 'watchlisttoken', 'label-message' => 'resettokens-watchlist-token' ),
			);
			Hooks::run( 'SpecialResetTokensTokens', array( &$tokens ) );

			$hiddenPrefs = $this->getConfig()->get( 'HiddenPrefs' );
			$tokens = array_filter( $tokens, function ( $tok ) use ( $hiddenPrefs ) {
				return !in_array( $tok['preference'], $hiddenPrefs );
			} );

			$this->tokensList = $tokens;
		}

		return $this->tokensList;
	}

	public function execute( $par ) {
		// This is a preferences page, so no user JS for y'all.
		$this->getOutput()->disallowUserJs();
		$this->requireLogin();

		parent::execute( $par );

		$this->getOutput()->addReturnTo( SpecialPage::getTitleFor( 'Preferences' ) );
	}

	public function onSuccess() {
		$this->getOutput()->wrapWikiMsg(
			"<div class='successbox'>\n$1\n</div>",
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
			$tokensForForm = array();
			foreach ( $tokens as $tok ) {
				$label = $this->msg( 'resettokens-token-label' )
					->rawParams( $this->msg( $tok['label-message'] )->parse() )
					->params( $user->getTokenFromOption( $tok['preference'] ) )
					->escaped();
				$tokensForForm[$label] = $tok['preference'];
			}

			$desc = array(
				'label-message' => 'resettokens-tokens',
				'type' => 'multiselect',
				'options' => $tokensForForm,
			);
		} else {
			$desc = array(
				'label-message' => 'resettokens-no-tokens',
				'type' => 'info',
			);
		}

		return array(
			'tokens' => $desc,
		);
	}

	/**
	 * Suppress the submit button if there's nothing to do;
	 * provide additional message on it otherwise.
	 * @param HTMLForm $form
	 */
	protected function alterForm( HTMLForm $form ) {
		if ( $this->getTokensList() ) {
			$form->setSubmitTextMsg( 'resettokens-resetbutton' );
		} else {
			$form->suppressDefaultSubmit();
		}
	}

	protected function getDisplayFormat() {
		return 'ooui';
	}

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

	protected function getGroupName() {
		return 'users';
	}

	public function isListed() {
		return (bool)$this->getTokensList();
	}
}
