<?php
/**
 *
 *
 * Created on Apr 15, 2012
 *
 * Copyright © 2012 Szymon Świerkosz beau@adres.pl
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
 * API module that facilitates the changing of user's preferences.
 * Requires API write mode to be enabled.
 *
 * @ingroup API
 */
class ApiOptions extends ApiBase {
	/**
	 * Changes preferences of the current user.
	 */
	public function execute() {
		if ( $this->getUser()->isAnon() ) {
			$this->dieUsage( 'Anonymous users cannot change preferences', 'notloggedin' );
		} elseif ( !$this->getUser()->isAllowed( 'editmyoptions' ) ) {
			$this->dieUsage( "You don't have permission to edit your options", 'permissiondenied' );
		}

		$params = $this->extractRequestParams();
		$changed = false;

		if ( isset( $params['optionvalue'] ) && !isset( $params['optionname'] ) ) {
			$this->dieUsageMsg( [ 'missingparam', 'optionname' ] );
		}

		// Load the user from the master to reduce CAS errors on double post (T95839)
		$user = $this->getUser()->getInstanceForUpdate();
		if ( !$user ) {
			$this->dieUsage( 'Anonymous users cannot change preferences', 'notloggedin' );
		}

		if ( $params['reset'] ) {
			$user->resetOptions( $params['resetkinds'], $this->getContext() );
			$changed = true;
		}

		$changes = [];
		if ( count( $params['change'] ) ) {
			foreach ( $params['change'] as $entry ) {
				$array = explode( '=', $entry, 2 );
				$changes[$array[0]] = isset( $array[1] ) ? $array[1] : null;
			}
		}
		if ( isset( $params['optionname'] ) ) {
			$newValue = isset( $params['optionvalue'] ) ? $params['optionvalue'] : null;
			$changes[$params['optionname']] = $newValue;
		}
		if ( !$changed && !count( $changes ) ) {
			$this->dieUsage( 'No changes were requested', 'nochanges' );
		}

		$prefs = Preferences::getPreferences( $user, $this->getContext() );
		$prefsKinds = $user->getOptionKinds( $this->getContext(), $changes );

		$htmlForm = null;
		foreach ( $changes as $key => $value ) {
			switch ( $prefsKinds[$key] ) {
				case 'registered':
					// Regular option.
					if ( $htmlForm === null ) {
						// We need a dummy HTMLForm for the validate callback...
						$htmlForm = new HTMLForm( [], $this );
					}
					$field = HTMLForm::loadInputFromParameters( $key, $prefs[$key], $htmlForm );
					$validation = $field->validate( $value, $user->getOptions() );
					break;
				case 'registered-multiselect':
				case 'registered-checkmatrix':
					// A key for a multiselect or checkmatrix option.
					$validation = true;
					$value = $value !== null ? (bool)$value : null;
					break;
				case 'userjs':
					// Allow non-default preferences prefixed with 'userjs-', to be set by user scripts
					if ( strlen( $key ) > 255 ) {
						$validation = 'key too long (no more than 255 bytes allowed)';
					} elseif ( preg_match( '/[^a-zA-Z0-9_-]/', $key ) !== 0 ) {
						$validation = 'invalid key (only a-z, A-Z, 0-9, _, - allowed)';
					} else {
						$validation = true;
					}
					break;
				case 'special':
					$validation = 'cannot be set by this module';
					break;
				case 'unused':
				default:
					$validation = 'not a valid preference';
					break;
			}
			if ( $validation === true ) {
				$user->setOption( $key, $value );
				$changed = true;
			} else {
				$this->setWarning( "Validation error for '$key': $validation" );
			}
		}

		if ( $changed ) {
			// Commit changes
			$user->saveSettings();
		}

		$this->getResult()->addValue( null, $this->getModuleName(), 'success' );
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		$optionKinds = User::listOptionKinds();
		$optionKinds[] = 'all';

		return [
			'reset' => false,
			'resetkinds' => [
				ApiBase::PARAM_TYPE => $optionKinds,
				ApiBase::PARAM_DFLT => 'all',
				ApiBase::PARAM_ISMULTI => true
			],
			'change' => [
				ApiBase::PARAM_ISMULTI => true,
			],
			'optionname' => [
				ApiBase::PARAM_TYPE => 'string',
			],
			'optionvalue' => [
				ApiBase::PARAM_TYPE => 'string',
			],
		];
	}

	public function needsToken() {
		return 'csrf';
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Options';
	}

	protected function getExamplesMessages() {
		return [
			'action=options&reset=&token=123ABC'
				=> 'apihelp-options-example-reset',
			'action=options&change=skin=vector|hideminor=1&token=123ABC'
				=> 'apihelp-options-example-change',
			'action=options&reset=&change=skin=monobook&optionname=nickname&' .
				'optionvalue=[[User:Beau|Beau]]%20([[User_talk:Beau|talk]])&token=123ABC'
				=> 'apihelp-options-example-complex',
		];
	}
}
