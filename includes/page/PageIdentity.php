<?php
/**
 * An interface representing page identity.
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

namespace MediaWiki\Page;

use MediaWiki\Linker\LinkTarget;

/**
 * An interface representing page identity.This can be used for existing and non-existing pages.
 *
 * This is intended as a drop-in replacement for uses of Title that only need the page ID,
 * namespace, and title text. PageIdentity can be thought of as a reference into the page table.
 *
 * @note PageIdentity SHOULD only be used for pages that can exist as editable articles. However,
 * to allow Title to implement PageIdentity, it can also represent a page in the Special namespace.
 * Use canExist() to check if the PageIdentity refers to something that can exist in the page table.
 *
 * @note PageIdentity is not guaranteed to be stateless/immutable, to allow Title to implement
 * this interface. If an immutable value object is required, use ImmutablePageIdentity.
 *
 * @note PageIdentity does not implement the exists() method found in Title, to avoid having to
 * maintain compatibility with the TitleExists hook. Callers that want to know whether the page
 * exists should check getId() >= 0.
 *
 * @since 1.31
 */
interface PageIdentity {

	/**
	 * @return boolean Whether the page can exist as an editable article. False particularly
	 *   if the link target refers to a page in the special namespace.
	 */
	public function canExist();

	/**
	 * @return int The page ID. May be 0 if the page does not exist.
	 */
	public function getArticleID();

	/**
	 * @return LinkTarget A LinkTarget giving the title and namespace of the page.
	 */
	public function getAsLinkTarget();

	/**
	 * Get the namespace index.
	 *
	 * @see LinkTarget::getNamespace()
	 *
	 * @return int Namespace index
	 */
	public function getNamespace();

	/**
	 * Convenience function to test if it is in the namespace
	 *
	 * @see LinkTarget::inNamespace()
	 *
	 * @param int $ns
	 * @return bool
	 */
	public function inNamespace( $ns );

	/**
	 * Returns the page's title in database key form (with underscores), without namespace prefix.
	 *
	 * @see LinkTarget::getDBkey()
	 *
	 * @return string Main part of the link, with underscores (for use in href attributes)
	 */
	public function getDBkey();

	/**
	 * Returns the page's title in text form (with spaces), without namespace prefix.
	 *
	 * @see LinkTarget::getText()
	 *
	 * @return string
	 */
	public function getText();

	/**
	 * Returns an informative human readable representation of the page,
	 * for use in logging and debugging.
	 *
	 * @return string
	 */
	public function __toString();

}
