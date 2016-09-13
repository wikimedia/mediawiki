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

use SpecialPageFactory;

/**
 * Lookup for special pages
 * @since 1.28
 */
class SpecialPageExistenceLookup implements LinkTargetExistenceLookup {

	/**
	 * Handle things in NS_SPECIAL
	 *
	 * @param LinkTarget $linkTarget
	 * @return int
	 */
	public function shouldHandle( LinkTarget $linkTarget ) {
		return $linkTarget->inNamespace( NS_SPECIAL ) ? self::HANDLE : self::SKIP;
	}

	/**
	 * This lookup is so cheap that we don't
	 * bother storing a queue
	 *
	 * @param LinkTarget $linkTarget
	 */
	public function add( LinkTarget $linkTarget ) {
		// no-op
	}

	public function lookup() {
		// no-op
	}

	public function exists( LinkTarget $linkTarget ) {
		return SpecialPageFactory::exists( $linkTarget->getDBkey() );
	}
}
