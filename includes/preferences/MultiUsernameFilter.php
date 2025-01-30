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

namespace MediaWiki\Preferences;

use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Authority;
use MediaWiki\User\CentralId\CentralIdLookup;

class MultiUsernameFilter implements Filter {
	/**
	 * @var CentralIdLookup|null
	 */
	private $lookup;
	/** @var Authority|int User querying central usernames or one of the audience constants */
	private $authorityOrAudience;

	/**
	 * @param CentralIdLookup|null $lookup
	 * @param Authority|int $authorityOrAudience
	 */
	public function __construct(
		?CentralIdLookup $lookup = null,
		$authorityOrAudience = CentralIdLookup::AUDIENCE_PUBLIC
	) {
		$this->lookup = $lookup;
		$this->authorityOrAudience = $authorityOrAudience;
	}

	/**
	 * @inheritDoc
	 */
	public function filterFromForm( $names ) {
		$names = trim( $names );
		if ( $names !== '' ) {
			$names = preg_split( '/\n/', $names, -1, PREG_SPLIT_NO_EMPTY );
			$ids = $this->getLookup()->centralIdsFromNames( $names, $this->authorityOrAudience );
			if ( $ids ) {
				return implode( "\n", $ids );
			}
		}
		// If the user list is empty, it should be null (don't save) rather than an empty string
		return null;
	}

	/**
	 * @inheritDoc
	 */
	public function filterForForm( $value ) {
		$ids = is_string( $value ) ? self::splitIds( $value ) : [];
		$names = $ids ? $this->getLookup()->namesFromCentralIds( $ids, $this->authorityOrAudience ) : [];
		return implode( "\n", $names );
	}

	/**
	 * Splits a newline separated list of user ids into an array.
	 *
	 * @param string $str
	 * @return int[]
	 */
	public static function splitIds( $str ) {
		return array_map( 'intval', preg_split( '/\n/', $str, -1, PREG_SPLIT_NO_EMPTY ) );
	}

	/**
	 * @return CentralIdLookup
	 */
	private function getLookup() {
		$this->lookup ??= MediaWikiServices::getInstance()->getCentralIdLookup();
		return $this->lookup;
	}
}
