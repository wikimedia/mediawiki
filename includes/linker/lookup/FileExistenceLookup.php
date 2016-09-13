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

use Title;

/**
 * Lookup for files, which are special!
 *
 * @todo this should use batching
 * @since 1.28
 */
class FileExistenceLookup implements LinkTargetExistenceLookup {
	public function shouldHandle( LinkTarget $linkTarget ) {
		return ( $linkTarget->inNamespace( NS_FILE ) || $linkTarget->inNamespace( NS_MEDIA ) )
			? self::HANDLE : self::SKIP;
	}

	public function add( LinkTarget $linkTarget ) {
		// no-op
	}

	public function lookup() {
		// no-op
	}

	public function exists( LinkTarget $linkTarget ) {
		return (bool)wfFindFile( Title::newFromLinkTarget( $linkTarget ) );
	}
}
