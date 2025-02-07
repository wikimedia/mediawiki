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
 * @license GPL-2.0-or-later
 * @since 1.22
 */

use MediaWiki\Api\ApiResult;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\Title\Title;
use MediaWiki\WikiMap\WikiMap;

/**
 * This class formats rights log entries.
 *
 * @stable to extend Since 1.44
 * @since 1.21
 */
class RightsLogFormatter extends LogFormatter {
	protected function makePageLink( ?Title $title = null, $parameters = [], $html = null ) {
		$userrightsInterwikiDelimiter = $this->context->getConfig()
			->get( MainConfigNames::UserrightsInterwikiDelimiter );

		if ( !$this->plaintext ) {
			$text = $this->getContentLanguage()->
				ucfirst( $title->getDBkey() );
			$parts = explode( $userrightsInterwikiDelimiter, $text, 2 );

			if ( count( $parts ) === 2 ) {
				// @phan-suppress-next-line SecurityCheck-DoubleEscaped
				$titleLink = WikiMap::foreignUserLink(
					$parts[1],
					$parts[0],
					htmlspecialchars(
						strtr( $parts[0], '_', ' ' ) .
						$userrightsInterwikiDelimiter .
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

		// Really old entries that lack old/new groups,
		// so don't try to process them
		if ( !$this->shouldProcessParams( $params ) ) {
			return $params;
		}

		// Groups are stored as [ name => expiry|null ]
		$oldGroups = $this->getOldGroups( $params );
		$newGroups = $this->getNewGroups( $params );

		if ( count( $oldGroups ) ) {
			$params[3] = Message::rawParam( $this->formatRightsList( $oldGroups ) );
		} else {
			$params[3] = $this->msg( 'rightsnone' )->text();
		}
		if ( count( $newGroups ) ) {
			$params[4] = Message::rawParam( $this->formatRightsList( $newGroups ) );
		} else {
			$params[4] = $this->msg( 'rightsnone' )->text();
		}

		$userName = $this->entry->getTarget()->getText();

		$params[5] = $userName;

		return $params;
	}

	/**
	 * Checks whether the additional message parameters should be processed.
	 * Typical reason for not processing the parameters is that the log entry
	 * is of legacy format with e.g. some of them missing.
	 * @since 1.44
	 * @stable to override
	 * @param array $params Extracted parameters
	 * @return bool
	 */
	protected function shouldProcessParams( array $params ) {
		return isset( $params[3] ) || isset( $params[4] );
	}

	/**
	 * Returns the old groups related to this log entry together
	 * with their expiry times. The returned array is indexed by the
	 * group name in a ready-to-display form (eg. localized)
	 * @since 1.44
	 * @stable to override
	 * @param array $params Extracted parameters
	 * @return array [ group_name => expiry|null ]
	 */
	protected function getOldGroups( array $params ) {
		if ( !isset( $params[3] ) ) {
			return [];
		}

		$allParams = $this->entry->getParameters();
		return $this->joinGroupsWithExpiries( $params[3], $allParams['oldmetadata'] ?? [] );
	}

	/**
	 * Returns the new groups related to this log entry together
	 * with their expiry times. The returned array is indexed by the
	 * group name in a ready-to-display form (eg. localized)
	 * @since 1.44
	 * @stable to override
	 * @param array $params Extracted parameters
	 * @return array [ group_name => expiry|null ]
	 */
	protected function getNewGroups( array $params ) {
		if ( !isset( $params[4] ) ) {
			return [];
		}

		$allParams = $this->entry->getParameters();
		return $this->joinGroupsWithExpiries( $params[4], $allParams['newmetadata'] ?? [] );
	}

	/**
	 * Joins group names from one array with their expiry times from the another.
	 * Expects that corresponding elements in both arrays are at the same index.
	 * The expiry times are looked up in the 'expiry' key of the elements int the
	 * metadata array. If membership is permanent, the expiry time is null.
	 * If this formatter is not plaintext, the group names are replaced with
	 * localized member names.
	 * @since 1.44
	 * @param array|string $groupNames
	 * @param array $metadata
	 * @return array
	 */
	protected function joinGroupsWithExpiries( $groupNames, array $metadata ) {
		$groupNames = $this->makeGroupArray( $groupNames );
		if ( !$this->plaintext && count( $groupNames ) ) {
			$this->replaceGroupsWithMemberNames( $groupNames );
		}

		$expiries = [];
		foreach (
			array_map( null, $groupNames, $metadata )
				as [ $groupName, $groupMetadata ]
		) {
			if ( isset( $groupMetadata['expiry'] ) ) {
				$expiry = $groupMetadata['expiry'];
			} else {
				$expiry = null;
			}
			$expiries[$groupName] = $expiry;
		}
		return $expiries;
	}

	/**
	 * Replaces the group names in the array with their localized member names.
	 * The array is modified in place.
	 * @since 1.44
	 * @stable to override
	 * @param array &$groupNames
	 */
	protected function replaceGroupsWithMemberNames( array &$groupNames ) {
		$lang = $this->context->getLanguage();
		$userName = $this->entry->getTarget()->getText();
		foreach ( $groupNames as &$group ) {
			$group = $lang->getGroupMemberName( $group, $userName );
		}
	}

	private function formatRightsList( $groups ) {
		$uiLanguage = $this->context->getLanguage();
		// separate arrays of temporary and permanent memberships
		$tempList = $permList = [];

		foreach ( $groups as $group => $expiry ) {
			if ( $expiry ) {
				// format the group and expiry into a friendly string
				$expiryFormatted = $this->formatDate( $expiry );
				$tempList[] = $this->msg( 'rightslogentry-temporary-group' )->params( $group,
					$expiryFormatted['whole'], $expiryFormatted['date'], $expiryFormatted['time'] )
					->parse();
			} else {
				// the right does not expire; just insert the group name
				$permList[] = htmlspecialchars( $group );
			}
		}

		// place all temporary memberships first, to avoid the ambiguity of
		// "administrator, bureaucrat and importer (temporary, until X time)"
		return $uiLanguage->listToText( array_merge( $tempList, $permList ) );
	}

	private function formatDate( $date ) {
		$uiLanguage = $this->context->getLanguage();
		$uiUser = $this->context->getUser();

		return [
			'whole' => $uiLanguage->userTimeAndDate( $date, $uiUser ),
			'date' => $uiLanguage->userDate( $date, $uiUser ),
			'time' => $uiLanguage->userTime( $date, $uiUser ),
		];
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
			$params['oldmetadata'] = array_map( static function ( $index ) use ( $params, $oldmetadata ) {
				$result = [ 'group' => $params['4:array:oldgroups'][$index] ];
				if ( isset( $oldmetadata[$index] ) ) {
					$result += $oldmetadata[$index];
				}
				$result['expiry'] = ApiResult::formatExpiry( $result['expiry'] ?? null );

				return $result;
			}, array_keys( $params['4:array:oldgroups'] ) );
		}

		if ( isset( $params['5:array:newgroups'] ) ) {
			$params['5:array:newgroups'] = $this->makeGroupArray( $params['5:array:newgroups'] );

			$newmetadata =& $params['newmetadata'];
			// unset old metadata entry to ensure metadata goes at the end of the params array
			unset( $params['newmetadata'] );
			$params['newmetadata'] = array_map( static function ( $index ) use ( $params, $newmetadata ) {
				$result = [ 'group' => $params['5:array:newgroups'][$index] ];
				if ( isset( $newmetadata[$index] ) ) {
					$result += $newmetadata[$index];
				}
				$result['expiry'] = ApiResult::formatExpiry( $result['expiry'] ?? null );

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
