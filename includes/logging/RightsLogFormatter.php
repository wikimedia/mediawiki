<?php
/**
 * Formatter for user rights log entries.
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
 * @author Alexandre Emsenhuber
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 * @since 1.22
 */

/**
 * This class formats rights log entries.
 *
 * @since 1.21
 */
class RightsLogFormatter extends LogFormatter {
	protected function makePageLink( Title $title = null, $parameters = [], $html = null ) {
		global $wgContLang, $wgUserrightsInterwikiDelimiter;

		if ( !$this->plaintext ) {
			$text = $wgContLang->ucfirst( $title->getDBkey() );
			$parts = explode( $wgUserrightsInterwikiDelimiter, $text, 2 );

			if ( count( $parts ) === 2 ) {
				$titleLink = WikiMap::foreignUserLink(
					$parts[1],
					$parts[0],
					htmlspecialchars(
						strtr( $parts[0], '_', ' ' ) .
						$wgUserrightsInterwikiDelimiter .
						$parts[1]
					)
				);

				if ( $titleLink !== false ) {
					return $titleLink;
				}
			}
		}

		return parent::makePageLink( $title, $parameters, $title ? $title->getText() : null );
	}

	protected function getMessageKey() {
		$key = parent::getMessageKey();
		$params = $this->getMessageParameters();
		if ( !isset( $params[3] ) && !isset( $params[4] ) ) {
			// Messages: logentry-rights-rights-legacy
			$key .= '-legacy';
		}

		return $key;
	}

	protected function getMessageParameters() {
		$params = parent::getMessageParameters();

		// Really old entries that lack old/new groups
		if ( !isset( $params[3] ) && !isset( $params[4] ) ) {
			return $params;
		}

		$oldGroups = $this->makeGroupArray( $params[3] );
		$newGroups = $this->makeGroupArray( $params[4] );

		$userName = $this->entry->getTarget()->getText();
		if ( !$this->plaintext && count( $oldGroups ) ) {
			foreach ( $oldGroups as &$group ) {
				$group = UserGroupMembership::getGroupMemberName( $group, $userName );
			}
		}
		if ( !$this->plaintext && count( $newGroups ) ) {
			foreach ( $newGroups as &$group ) {
				$group = UserGroupMembership::getGroupMemberName( $group, $userName );
			}
		}

		// fetch the metadata about each group membership
		$allParams = $this->entry->getParameters();

		if ( count( $oldGroups ) ) {
			$params[3] = [ 'raw' => $this->formatRightsList( $oldGroups,
				isset( $allParams['oldmetadata'] ) ? $allParams['oldmetadata'] : [] ) ];
		} else {
			$params[3] = $this->msg( 'rightsnone' )->text();
		}
		if ( count( $newGroups ) ) {
			// Array_values is used here because of T44211
			// see use of array_unique in UserrightsPage::doSaveUserGroups on $newGroups.
			$params[4] = [ 'raw' => $this->formatRightsList( array_values( $newGroups ),
				isset( $allParams['newmetadata'] ) ? $allParams['newmetadata'] : [] ) ];
		} else {
			$params[4] = $this->msg( 'rightsnone' )->text();
		}

		$params[5] = $userName;

		return $params;
	}

	protected function formatRightsList( $groups, $serializedUGMs = [] ) {
		$uiLanguage = $this->context->getLanguage();
		$uiUser = $this->context->getUser();
		// separate arrays of temporary and permanent memberships
		$tempList = $permList = [];

		reset( $groups );
		reset( $serializedUGMs );
		while ( current( $groups ) ) {
			$group = current( $groups );

			if ( current( $serializedUGMs ) &&
				isset( current( $serializedUGMs )['expiry'] ) &&
				current( $serializedUGMs )['expiry']
			) {
				// there is an expiry date; format the group and expiry into a friendly string
				$expiry = current( $serializedUGMs )['expiry'];
				$expiryFormatted = $uiLanguage->userTimeAndDate( $expiry, $uiUser );
				$expiryFormattedD = $uiLanguage->userDate( $expiry, $uiUser );
				$expiryFormattedT = $uiLanguage->userTime( $expiry, $uiUser );
				$tempList[] = $this->msg( 'rightslogentry-temporary-group' )->params( $group,
					$expiryFormatted, $expiryFormattedD, $expiryFormattedT )->parse();
			} else {
				// the right does not expire; just insert the group name
				$permList[] = $group;
			}

			next( $groups );
			next( $serializedUGMs );
		}

		// place all temporary memberships first, to avoid the ambiguity of
		// "adinistrator, bureaucrat and importer (temporary, until X time)"
		return $uiLanguage->listToText( array_merge( $tempList, $permList ) );
	}

	protected function getParametersForApi() {
		$entry = $this->entry;
		$params = $entry->getParameters();

		static $map = [
			'4:array:oldgroups',
			'5:array:newgroups',
			'4::oldgroups' => '4:array:oldgroups',
			'5::newgroups' => '5:array:newgroups',
		];
		foreach ( $map as $index => $key ) {
			if ( isset( $params[$index] ) ) {
				$params[$key] = $params[$index];
				unset( $params[$index] );
			}
		}

		// Really old entries do not have log params, so form them from whatever info
		// we have.
		// Also walk through the parallel arrays of groups and metadata, combining each
		// metadata array with the name of the group it pertains to
		if ( isset( $params['4:array:oldgroups'] ) ) {
			$params['4:array:oldgroups'] = $this->makeGroupArray( $params['4:array:oldgroups'] );

			$oldmetadata =& $params['oldmetadata'];
			// unset old metadata entry to ensure metadata goes at the end of the params array
			unset( $params['oldmetadata'] );
			$params['oldmetadata'] = array_map( function( $index ) use ( $params, $oldmetadata ) {
				$result = [ 'group' => $params['4:array:oldgroups'][$index] ];
				if ( isset( $oldmetadata[$index] ) ) {
					$result += $oldmetadata[$index];
				}
				$result['expiry'] = ApiResult::formatExpiry( isset( $result['expiry'] ) ?
					$result['expiry'] : null );

				return $result;
			}, array_keys( $params['4:array:oldgroups'] ) );
		}

		if ( isset( $params['5:array:newgroups'] ) ) {
			$params['5:array:newgroups'] = $this->makeGroupArray( $params['5:array:newgroups'] );

			$newmetadata =& $params['newmetadata'];
			// unset old metadata entry to ensure metadata goes at the end of the params array
			unset( $params['newmetadata'] );
			$params['newmetadata'] = array_map( function( $index ) use ( $params, $newmetadata ) {
				$result = [ 'group' => $params['5:array:newgroups'][$index] ];
				if ( isset( $newmetadata[$index] ) ) {
					$result += $newmetadata[$index];
				}
				$result['expiry'] = ApiResult::formatExpiry( isset( $result['expiry'] ) ?
					$result['expiry'] : null );

				return $result;
			}, array_keys( $params['5:array:newgroups'] ) );
		}

		return $params;
	}

	public function formatParametersForApi() {
		$ret = parent::formatParametersForApi();
		if ( isset( $ret['oldgroups'] ) ) {
			ApiResult::setIndexedTagName( $ret['oldgroups'], 'g' );
		}
		if ( isset( $ret['newgroups'] ) ) {
			ApiResult::setIndexedTagName( $ret['newgroups'], 'g' );
		}
		if ( isset( $ret['oldmetadata'] ) ) {
			ApiResult::setArrayType( $ret['oldmetadata'], 'array' );
			ApiResult::setIndexedTagName( $ret['oldmetadata'], 'g' );
		}
		if ( isset( $ret['newmetadata'] ) ) {
			ApiResult::setArrayType( $ret['newmetadata'], 'array' );
			ApiResult::setIndexedTagName( $ret['newmetadata'], 'g' );
		}
		return $ret;
	}

	private function makeGroupArray( $group ) {
		// Migrate old group params from string to array
		if ( $group === '' ) {
			$group = [];
		} elseif ( is_string( $group ) ) {
			$group = array_map( 'trim', explode( ',', $group ) );
		}
		return $group;
	}
}
