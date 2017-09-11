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
 * @license GPL 2+
 * @author Addshore
 */
namespace MediaWiki\Linker;

/**
 * Represents the target of a wiki link.
 *
 * @since 1.27
 */
interface LinkTarget {

	/**
	 * Whether the link is relative to some base page. This is true e.g.
	 * for section links like [[#Kittens]], and for subpage links like
	 * [[/Kittens]]. Sections links have their text part defined to be
	 * the empty string.
	 *
	 * Corollary: a link cannot be external and relative at the same time.
	 *
	 * @since 1.30
	 *
	 * @return bool
	 */
	public function isRelative();

	/**
	 * Resolves a relative link using the given base. If this link is not relative,
	 * the method returns $this. If it is relative, it constructs a new LinkTarget
	 * based on the information in $base. If $base is relative, the result will again
	 * be relative. If $base is absolute, the result will be absolte.
	 *
	 * @since 1.30
	 *
	 * @throw LinkTargetException if $base is also relative
	 * @return LinkTarget
	 */
	public function resolveRelativeLink( LinkTarget $base );

	/**
	 * Get the namespace index.
	 * @since 1.27
	 *
	 * @throw LinkTargetException if this link is relative or external (since 1.30).
	 * @return int Namespace index
	 */
	public function getNamespace();

	/**
	 * Convenience function to test if it is in the namespace
	 * @since 1.27
	 *
	 * @param int $ns
	 *
	 * @throw LinkTargetException if this link is relative or external (since 1.30).
	 * @return bool
	 */
	public function inNamespace( $ns );

	/**
	 * Get the link fragment (i.e. the bit after the #) in text form.
	 * @since 1.27
	 *
	 * @return string link fragment (or the empty string if there is no fragement)
	 */
	public function getFragment();

	/**
	 * Whether the link target has a fragment
	 * @since 1.27
	 *
	 * @return bool
	 */
	public function hasFragment();

	/**
	 * Get the target page's title, with underscores instead of spaces, for use in href attributes.
	 * @since 1.27
	 *
	 * @note If this LinkTarget is a relative section link,
	 *       this method returns the empty string (since 1.30).
	 *
	 * @return string Main part of the link, with underscores.
	 */
	public function getDBkey();

	/**
	 * Returns the link in text form, without namespace prefix or fragment.
	 * This is computed from the DB key by replacing any underscores with spaces.
	 * @since 1.27
	 *
	 * @note If this LinkTarget is a relative section link,
	 *       this method returns the empty string (since 1.30).
	 *
	 * @return string
	 */
	public function getText();

	/**
	 * Creates a new LinkTarget for a different fragment of the same page.
	 * It is expected that the same type of object will be returned, but the
	 * only requirement is that it is a LinkTarget.
	 * @since 1.27
	 *
	 * @param string $fragment The fragment name, or "" for the entire page.
	 *
	 * @return LinkTarget
	 */
	public function createFragmentTarget( $fragment );

	/**
	 * Whether this LinkTarget has an interwiki component.
	 * @since 1.27
	 *
	 * Corollary: a link cannot be external and relative at the same time.
	 *
	 * @return bool
	 */
	public function isExternal();

	/**
	 * The interwiki component of this LinkTarget
	 * @since 1.27
	 *
	 * @return string
	 */
	public function getInterwiki();
}
