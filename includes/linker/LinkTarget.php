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
 * @author Addshore
 */
namespace MediaWiki\Linker;

/**
 * @since 1.27
 */
interface LinkTarget {

	/**
	 * Get the namespace index.
	 * @since 1.27
	 *
	 * @return int Namespace index
	 */
	public function getNamespace();

	/**
	 * Convenience function to test if it is in the namespace
	 * @since 1.27
	 *
	 * @param int $ns
	 * @return bool
	 */
	public function inNamespace( $ns );

	/**
	 * Get the link fragment (i.e. the bit after the #) in text form.
	 * @since 1.27
	 *
	 * @return string link fragment
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
	 * Get the main part with underscores.
	 * @since 1.27
	 *
	 * @return string Main part of the link, with underscores (for use in href attributes)
	 */
	public function getDBkey();

	/**
	 * Returns the link in text form, without namespace prefix or fragment.
	 * This is computed from the DB key by replacing any underscores with spaces.
	 * @since 1.27
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
	 * Whether this LinkTarget has an interwiki component
	 * @since 1.27
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

	/**
	 * Returns an informative human readable representation of the link target,
	 * for use in logging and debugging. There is no requirement for the return
	 * value to have any relationship with the input of TitleParser.
	 * @since 1.31
	 *
	 * @return string
	 */
	public function __toString();

}
