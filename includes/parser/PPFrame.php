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
 * @ingroup Parser
 */

/**
 * @ingroup Parser
 *
 * @property int $depth
 * @property PPFrame $parent
 */
interface PPFrame {
	const NO_ARGS = 1;
	const NO_TEMPLATES = 2;
	const STRIP_COMMENTS = 4;
	const NO_IGNORE = 8;
	const RECOVER_COMMENTS = 16;
	const NO_TAGS = 32;

	const RECOVER_ORIG = self::NO_ARGS | self::NO_TEMPLATES | self::NO_IGNORE |
		self::RECOVER_COMMENTS | self::NO_TAGS;

	/** This constant exists when $indexOffset is supported in newChild() */
	const SUPPORTS_INDEX_OFFSET = 1;

	/**
	 * Create a child frame
	 *
	 * @param array|bool $args
	 * @param bool|Title $title
	 * @param int $indexOffset A number subtracted from the index attributes of the arguments
	 *
	 * @return PPFrame
	 */
	public function newChild( $args = false, $title = false, $indexOffset = 0 );

	/**
	 * Expand a document tree node, caching the result on its parent with the given key
	 * @param string|int $key
	 * @param string|PPNode $root
	 * @param int $flags
	 * @return string
	 */
	public function cachedExpand( $key, $root, $flags = 0 );

	/**
	 * Expand a document tree node
	 * @param string|PPNode $root
	 * @param int $flags
	 * @return string
	 */
	public function expand( $root, $flags = 0 );

	/**
	 * Implode with flags for expand()
	 * @param string $sep
	 * @param int $flags
	 * @param string|PPNode ...$params
	 * @return string
	 */
	public function implodeWithFlags( $sep, $flags, ...$params );

	/**
	 * Implode with no flags specified
	 * @param string $sep
	 * @param string|PPNode ...$params
	 * @return string
	 */
	public function implode( $sep, ...$params );

	/**
	 * Makes an object that, when expand()ed, will be the same as one obtained
	 * with implode()
	 * @param string $sep
	 * @param string|PPNode ...$params
	 * @return PPNode
	 */
	public function virtualImplode( $sep, ...$params );

	/**
	 * Virtual implode with brackets
	 * @param string $start
	 * @param string $sep
	 * @param string $end
	 * @param string|PPNode ...$params
	 * @return PPNode
	 */
	public function virtualBracketedImplode( $start, $sep, $end, ...$params );

	/**
	 * Returns true if there are no arguments in this frame
	 *
	 * @return bool
	 */
	public function isEmpty();

	/**
	 * Returns all arguments of this frame
	 * @return array
	 */
	public function getArguments();

	/**
	 * Returns all numbered arguments of this frame
	 * @return array
	 */
	public function getNumberedArguments();

	/**
	 * Returns all named arguments of this frame
	 * @return array
	 */
	public function getNamedArguments();

	/**
	 * Get an argument to this frame by name
	 * @param int|string $name
	 * @return string|bool
	 */
	public function getArgument( $name );

	/**
	 * Returns true if the infinite loop check is OK, false if a loop is detected
	 *
	 * @param Title $title
	 * @return bool
	 */
	public function loopCheck( $title );

	/**
	 * Return true if the frame is a template frame
	 * @return bool
	 */
	public function isTemplate();

	/**
	 * Set the "volatile" flag.
	 *
	 * Note that this is somewhat of a "hack" in order to make extensions
	 * with side effects (such as Cite) work with the PHP parser. New
	 * extensions should be written in a way that they do not need this
	 * function, because other parsers (such as Parsoid) are not guaranteed
	 * to respect it, and it may be removed in the future.
	 *
	 * @param bool $flag
	 */
	public function setVolatile( $flag = true );

	/**
	 * Get the "volatile" flag.
	 *
	 * Callers should avoid caching the result of an expansion if it has the
	 * volatile flag set.
	 *
	 * @see self::setVolatile()
	 * @return bool
	 */
	public function isVolatile();

	/**
	 * Get the TTL of the frame's output.
	 *
	 * This is the maximum amount of time, in seconds, that this frame's
	 * output should be cached for. A value of null indicates that no
	 * maximum has been specified.
	 *
	 * Note that this TTL only applies to caching frames as parts of pages.
	 * It is not relevant to caching the entire rendered output of a page.
	 *
	 * @return int|null
	 */
	public function getTTL();

	/**
	 * Set the TTL of the output of this frame and all of its ancestors.
	 * Has no effect if the new TTL is greater than the one already set.
	 * Note that it is the caller's responsibility to change the cache
	 * expiry of the page as a whole, if such behavior is desired.
	 *
	 * @see self::getTTL()
	 * @param int $ttl
	 */
	public function setTTL( $ttl );

	/**
	 * Get a title of frame
	 *
	 * @return Title
	 */
	public function getTitle();
}
