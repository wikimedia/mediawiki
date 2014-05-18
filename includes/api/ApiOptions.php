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
		$user = $this->getUser();

		if ( $user->isAnon() ) {
			$this->dieUsage( 'Anonymous users cannot change preferences', 'notloggedin' );
		}

		if ( !$user->isAllowed( 'editmyoptions' ) ) {
			$this->dieUsage( 'You don\'t have permission to edit your options', 'permissiondenied' );
		}

		$params = $this->extractRequestParams();
		$changed = false;

		if ( isset( $params['optionvalue'] ) && !isset( $params['optionname'] ) ) {
			$this->dieUsageMsg( array( 'missingparam', 'optionname' ) );
		}

		if ( $params['reset'] ) {
			$user->resetOptions( $params['resetkinds'], $this->getContext() );
			$changed = true;
		}

		$changes = array();
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

		foreach ( $changes as $key => $value ) {
			switch ( $prefsKinds[$key] ) {
				case 'registered':
					// Regular option.
					$field = HTMLForm::loadInputFromParameters( $key, $prefs[$key] );
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
						$validation = "key too long (no more than 255 bytes allowed)";
					} elseif ( preg_match( "/[^a-zA-Z0-9_-]/", $key ) !== 0 ) {
						$validation = "invalid key (only a-z, A-Z, 0-9, _, - allowed)";
					} else {
						$validation = true;
					}
					break;
				case 'special':
					$validation = "cannot be set by this module";
					break;
				case 'unused':
				default:
					$validation = "not a valid preference";
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

		return array(
			'token' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
			'reset' => false,
			'resetkinds' => array(
				ApiBase::PARAM_TYPE => $optionKinds,
				ApiBase::PARAM_DFLT => 'all',
				ApiBase::PARAM_ISMULTI => true
			),
			'change' => array(
				ApiBase::PARAM_ISMULTI => true,
			),
			'optionname' => array(
				ApiBase::PARAM_TYPE => 'string',
			),
			'optionvalue' => array(
				ApiBase::PARAM_TYPE => 'string',
			),
		);
	}

	public function getResultProperties() {
		return array(
			'' => array(
				'*' => array(
					ApiBase::PROP_TYPE => array(
						'success'
					)
				)
			)
		);
	}

	public function getParamDescription() {
		return array(
			'token' => 'An options token previously obtained through the action=tokens',
			'reset' => 'Resets preferences to the site defaults',
			'resetkinds' => 'List of types of options to reset when the "reset" option is set',
			'change' => array( 'List of changes, formatted name=value (e.g. skin=vector), ' .
				'value cannot contain pipe characters. If no value is given (not ',
				'even an equals sign), e.g., optionname|otheroption|..., the ' .
				'option will be reset to its default value'
			),
			'optionname' => 'A name of a option which should have an optionvalue set',
			'optionvalue' => 'A value of the option specified by the optionname, ' .
				'can contain pipe characters',
		);
	}

	public function getDescription() {
		return array(
			'Change preferences of the current user.',
			'Only options which are registered in core or in one of installed extensions,',
			'or as options with keys prefixed with \'userjs-\' (intended to be used by user',
			'scripts), can be set.'
		);
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'notloggedin', 'info' => 'Anonymous users cannot change preferences' ),
			array( 'code' => 'nochanges', 'info' => 'No changes were requested' ),
		) );
	}

	public function needsToken() {
		return true;
	}

	public function getTokenSalt() {
		return '';
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Options';
	}

	public function getExamples() {
		return array(
			'api.php?action=options&reset=&token=123ABC',
			'api.php?action=options&change=skin=vector|hideminor=1&token=123ABC',
			'api.php?action=options&reset=&change=skin=monobook&optionname=nickname&' .
				'optionvalue=[[User:Beau|Beau]]%20([[User_talk:Beau|talk]])&token=123ABC',
		);
	}
}
