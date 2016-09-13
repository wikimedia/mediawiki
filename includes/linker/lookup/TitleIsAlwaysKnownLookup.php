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
 * @license GPL-2.0+
 */
namespace MediaWiki\Linker;

use Hooks;
use Title;

/**
 * Class to handle the deprecated TitleIsAlwaysKnown hook
 *
 * @deprecated since 1.28
 * @since 1.28
 */
class TitleIsAlwaysKnownLookup extends FastExistenceLookup {

	/**
	 * @param LinkTarget $target
	 * @return null|bool
	 */
	private function runHook( LinkTarget $target ) {
		$title = Title::newFromLinkTarget( $target );
		$isKnown = null;
		Hooks::run( 'TitleIsAlwaysKnown', [ $title, &$isKnown ] );
		return $isKnown;
	}

	/**
	 * @param LinkTarget $linkTarget
	 * @return bool
	 */
	public function exists( LinkTarget $linkTarget ) {
		if ( !Hooks::isRegistered( 'TitleIsAlwaysKnown' ) ) {
			return self::SKIP;
		}

		$hook = $this->runHook( $linkTarget );
		if ( $hook === true ) {
			return self::EXISTS;
		} elseif ( $hook === false ) {
			return self::BROKEN;
		} else {
			return self::SKIP;
		}
	}
}
