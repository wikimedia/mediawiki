<?php
/**
 * Copyright (C) 2019 MediaWiki contributors
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 */

/**
 * Temporary trait to allow migration between PHPUnit versions without breaking tests
 *
 * @deprecated since 1.35 Should only be used in (very) rare cases.
 */
trait PHPUnit6And8Compat {
	private static function getAllowedWarningsRegex() {
		static $cache = null;

		if ( !$cache ) {
			$regexes = [
				'assertArraySubset\(\)',
				'assert(Not)?InternalType\(\)',
				'parameter of assertEquals\(\)',
				'Using assert(Not)?Contains\(\) with string haystacks',
				'(read|getObject)Attribute\(\)',

			];

			$cache = '/(' . implode( '|', $regexes ) . ') is deprecated/';
		}

		return $cache;
	}

	/**
	 * Override to silence some deprecation warnings
	 *
	 * @param string $warning
	 */
	public function addWarning( string $warning ) : void {
		if ( preg_match( self::getAllowedWarningsRegex(), $warning ) ) {
			return;
		}
		parent::addWarning( $warning );
	}
}
