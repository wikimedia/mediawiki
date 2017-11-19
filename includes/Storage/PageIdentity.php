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

namespace MediaWiki\Storage;

use MediaWiki\Linker\LinkTarget;

/**
 * An interface representing page identity.
 *
 * This can be used for existing and non-existing pages. It cannot however be used for
 * pages that cannot exist.
 *
 * @note This is intended as a drop-in replacement for uses of Title that only need the page ID,
 * namespace, and title text. PageIdentity is similar to LinkTarget, but can only refer to local
 * pages, and it provides the page ID (along with the information whether the page exists).
 *
 * FIXME: change RevisionRecord:::getPageAsLinkTarget to getPageIdentity!
 *
 * FIXME: create Title::newFromPageIdentity and Title::asPageIdentity.
 * NOTE: Title should probably not implement PageIdentity, since PageIdentity guarantees
 * that it's a page that can at least potentially exist locally as a non-special page,
 * while Title can also be external, or a special page, or refer to a fragment.
 */
interface PageIdentity {

	/**
	 * @return boolean Whether the page exists
	 */
	public function exists();

	/**
	 * @return int The page ID. May be 0 if the page does not exist.
	 */
	public function getId();

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
	public function getTitleDBkey();

	/**
	 * Returns the page's title in text form (with spaces), without namespace prefix.
	 *
	 * @see LinkTarget::getText()
	 *
	 * @return string
	 */
	public function getTitleText();

	/**
	 * Returns an informative human readable representation of the page,
	 * for use in logging and debugging.
	 *
	 * @return string
	 */
	public function __toString();

}
