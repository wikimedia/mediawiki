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
 */
class SpecialResetTokens extends UnlistedSpecialPage {

	public function __construct() {
		parent::__construct( 'ResetTokens' );
	}

	protected function getTokensList() {
		$tokens = array(
			array( 'preference' => 'watchlisttoken', 'label-message' => 'resettokens-watchlist-token' ),
		);
		wfRunHooks( 'SpecialResetTokensTokens', array( &$tokens ) );
		return $tokens;
	}

	/**
	 * True if the tokens list is not empty and any of the tokens is modifiable.
	 *
	 * @return Bool
	 */
	protected function anyTokens() {
		global $wgHiddenPrefs;

		$tokens = $this->getTokensList();

		foreach ( $tokens as $token ) {
			if ( !in_array( $tokens['preference'], $wgHiddenPrefs ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Show if the tokens list is not empty and any of the tokens is modifiable.
	 *
	 * @return Bool
	 */
	public function isListed() {
		return $this->anyTokens();
	}

	/**
	 * Main execution point
	 */
	public function execute( $par ) {
		global $wgAuth;

		$this->setHeaders();
		$this->outputHeader();

		$out = $this->getOutput();
		$out->disallowUserJs();

		if ( $this->getRequest()->getCheck( 'success' ) ) {
			$out->wrapWikiMsg(
				"<div class='successbox'>\n$1\n</div>",
				'resettokens-done'
			);
		}

		if ( !$this->anyTokens() ) {
			$out->addWikiMsg( 'resettokens-no-tokens' );
		} else {
			$this->checkReadOnly();
			$out->addWikiMsg( 'resettokens-text' );
			$this->showForm();
		}

		$this->getOutput()->addReturnTo( SpecialPage::getTitleFor( 'Preferences' ) );
	}

	protected function showForm() {
		$user = $this->getUser();

		$tokensForForm = array();
		foreach ( $this->getTokensList() as $token ) {
			$label = $this->msg( 'resettokens-token-label' )
				->rawParams( $this->msg( $token['label-message'] )->text() )
				->params( $user->getTokenFromOption( $token['preference'] ) )
				->text();
			$tokensForForm[ $label ] = $token['preference'];
		}

		$formDescriptor = array(
			'tokens' => array(
				'label-message' => 'resettokens-tokens',
				'type' => 'multiselect',
				'options' => $tokensForForm,
			)
		);

		$htmlForm = new HTMLForm( $formDescriptor, $this->getContext() );

		$htmlForm->setSubmitTextMsg( 'resettokens-resetbutton' );
		$htmlForm->setWrapperLegendMsg( 'resettokens-legend' );


		$special = $this;
		$htmlForm->setSubmitCallback( function ( $formData ) use ( $special, $user ) {
			foreach ( $formData['tokens'] as $tokenPref ) {
				$user->resetTokenFromOption( $tokenPref );
			}

			$url = $special->getTitle()->getFullURL(
				count( $formData['tokens'] ) ? 'success' : '' // only claim success if anything was done
			);
			$special->getOutput()->redirect( $url );

			return true;
		} );

		$htmlForm->show();
	}

	protected function getGroupName() {
		return 'users';
	}
}
