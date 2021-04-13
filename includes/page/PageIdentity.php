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

namespace MediaWiki\Page;

use RuntimeException;
use Wikimedia\Assert\PreconditionException;

/**
 * Interface for objects (potentially) representing an editable wiki page.
 *
 * The identity of any PageIdentity object is defined by the
 * namespace, the dbkey, and the wiki ID.
 * The page ID together with the wiki ID also identifies the page,
 * unless the page ID is 0.
 * If the wiki ID is self::LOCAL, the identity is relative to the local wiki.
 *
 * @note For compatibility with the Title class, PageIdentity instances
 *   may for now not only represent non-existing pages, but also things
 *   that are not actually pages, such as interwiki links, section links,
 *   or Special pages (which exist, but are not proper editable pages).
 *   This is intended to change in the future, so that a PageIdentity always
 *   represents a "proper" page. Until then, code that requires a proper page
 *   should call canExist() to check, or require a ProperPageIdentity.
 *   Eventually, ProperPageIdentity is intended to become an alias for
 *   PageIdentity.
 *
 * @note For compatibility with the Title class, PageIdentity instances may
 *   be mutable, and return different values from methods such as getId() or exist()
 *   at different times. In the future, the contract of this interface is intended
 *   to be changed to disallow this.
 *
 * @note Instances of Title shall be the only instances of PageIdentity that are not
 *   proper pages. Other classes implementing PageIdentity must represent proper pages,
 *   and also implement ProperPageIdentity. The idea is that once Title has been removed,
 *   all PageIdentities are then proper pages, and the distinction between PageIdentity
 *   and ProperPageIdentity becomes redundant.
 *
 * @note  Code that deserializes instances of PageIdentity must ensure that the original
 *   meaning of the "local" Wiki ID is preserved if the PageIdentity originated on
 *   another wiki.
 *
 * @stable to type
 *
 * @since 1.36
 */
interface PageIdentity extends PageReference {

	/**
	 * Returns the page ID.
	 *
	 * If this ID is 0, this means the page does not exist.
	 *
	 * Implementations must call assertWiki().
	 *
	 * @note As a concession to allowing Title to implement this interface,
	 *       PageIdentity instances may represent things that are not pages,
	 *       such as relative section links or interwiki links. If getId()
	 *       is called on a PageIdentity that does not actually represent a
	 *       page, it must throw a RuntimeException. The idea is that code that
	 *       expects a PageIdentity is expecting an actual page.
	 *       The canExist() method can be used to ensure that it is.
	 *
	 * @param string|false $wikiId Must be provided when accessing the ID of a non-local
	 *        PageIdentity, to prevent data corruption when using a PageIdentity belonging
	 *        to one wiki in the context of another. Should be omitted if expecting the local wiki.
	 *
	 * @return int
	 * @throws RuntimeException if this PageIdentity is not a "proper"
	 *         page identity, but e.g. a relative section link, an interwiki
	 *         link, etc.
	 * @throws PreconditionException if this PageIdentity does not belong to the wiki
	 *         identified by $wikiId.
	 * @see Title::getArticleID()
	 * @see Title::toPageIdentity()
	 * @see canExist()
	 *
	 */
	public function getId( $wikiId = self::LOCAL ): int;

	/**
	 * Checks whether this PageIdentity represents a "proper" page,
	 * meaning that it could exist as an editable page on the wiki.
	 *
	 * @note This method only exists to allow Title to implement this interface.
	 *       Title instances may represent things that are not pages,
	 *       such as relative section links or interwiki links.
	 *       The idea is that code that expects a PageIdentity is expecting an
	 *       actual page. The canExist() method can be used to ensure that it is.
	 *
	 * @note Eventually, this method should be guaranteed to return true,
	 *       then be deprecated, and then removed.
	 *
	 * @return bool
	 * @see Title::getArticleID()
	 * @see Title::toPageIdentity()
	 *
	 * @see getId()
	 */
	public function canExist(): bool;

	/**
	 * Checks if the page currently exists.
	 *
	 * Implementations must ensure that this method returns false
	 * when getId() would throw or return 0.
	 * This also implies that this method must return false
	 * if canExist() would return false.
	 *
	 * @see Title::exists()
	 *
	 * @return bool
	 */
	public function exists(): bool;

}
