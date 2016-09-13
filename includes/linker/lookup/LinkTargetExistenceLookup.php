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

interface LinkTargetExistenceLookup {

	/**
	 * Return values for shouldHandle()
	 *
	 * HANDLE: Handle and allow others to handle as well
	 * HANDLE_ONLY: Handle, but don't allow others to handle
	 * SKIP: Don't handle
	 */
	const HANDLE = 1;
	const HANDLE_ONLY = 2;
	const SKIP = 3;

	/**
	 * Whether this lookup should handle the given
	 * link target
	 *
	 * @param LinkTarget $linkTarget
	 * @return int HANDLE, HANDLE_ONLY, or SKIP constants
	 */
	public function shouldHandle( LinkTarget $linkTarget );

	/**
	 * Add the link target to the internal queue
	 * to be processed
	 *
	 * @param LinkTarget $linkTarget
	 */
	public function add( LinkTarget $linkTarget );

	/**
	 * Do the actual processing to look up link targets
	 */
	public function lookup();

	/**
	 * After lookup() is called, this can be
	 * called to check whether a link exists
	 *
	 *
	 * @param LinkTarget $linkTarget
	 * @return bool
	 */
	public function exists( LinkTarget $linkTarget );
}
