<?php
/**
 * Interfaces for preprocessors
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
 * @ingroup Parser
 */

/**
 * @ingroup Parser
 */
interface Preprocessor {
	/**
	 * Create a new preprocessor object based on an initialised Parser object
	 *
	 * @param Parser $parser
	 */
	public function __construct( $parser );

	/**
	 * Create a new top-level frame for expansion of a page
	 *
	 * @return PPFrame
	 */
	public function newFrame();

	/**
	 * Create a new custom frame for programmatic use of parameter replacement
	 * as used in some extensions.
	 *
	 * @param array $args
	 *
	 * @return PPFrame
	 */
	public function newCustomFrame( $args );

	/**
	 * Create a new custom node for programmatic use of parameter replacement
	 * as used in some extensions.
	 *
	 * @param array $values
	 */
	public function newPartNodeArray( $values );

	/**
	 * Preprocess text to a PPNode
	 *
	 * @param string $text
	 * @param int $flags
	 *
	 * @return PPNode
	 */
	public function preprocessToObj( $text, $flags = 0 );
}

/**
 * @ingroup Parser
 */
interface PPFrame {
	const NO_ARGS = 1;
	const NO_TEMPLATES = 2;
	const STRIP_COMMENTS = 4;
	const NO_IGNORE = 8;
	const RECOVER_COMMENTS = 16;
	const NO_TAGS = 32;

	const RECOVER_ORIG = 59; // = 1|2|8|16|32 no constant expression support in PHP yet

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
	 * @param string|PPNode $args,...
	 * @return string
	 */
	public function implodeWithFlags( $sep, $flags /*, ... */ );

	/**
	 * Implode with no flags specified
	 * @param string $sep
	 * @param string|PPNode $args,...
	 * @return string
	 */
	public function implode( $sep /*, ... */ );

	/**
	 * Makes an object that, when expand()ed, will be the same as one obtained
	 * with implode()
	 * @param string $sep
	 * @param string|PPNode $args,...
	 * @return PPNode
	 */
	public function virtualImplode( $sep /*, ... */ );

	/**
	 * Virtual implode with brackets
	 * @param string $start
	 * @param string $sep
	 * @param string $end
	 * @param string|PPNode $args,...
	 * @return PPNode
	 */
	public function virtualBracketedImplode( $start, $sep, $end /*, ... */ );

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
	 * @param string $name
	 * @return bool
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

/**
 * There are three types of nodes:
 *     * Tree nodes, which have a name and contain other nodes as children
 *     * Array nodes, which also contain other nodes but aren't considered part of a tree
 *     * Leaf nodes, which contain the actual data
 *
 * This interface provides access to the tree structure and to the contents of array nodes,
 * but it does not provide access to the internal structure of leaf nodes. Access to leaf
 * data is provided via two means:
 *     * PPFrame::expand(), which provides expanded text
 *     * The PPNode::split*() functions, which provide metadata about certain types of tree node
 * @ingroup Parser
 */
interface PPNode {
	/**
	 * Get an array-type node containing the children of this node.
	 * Returns false if this is not a tree node.
	 * @return PPNode
	 */
	public function getChildren();

	/**
	 * Get the first child of a tree node. False if there isn't one.
	 *
	 * @return PPNode
	 */
	public function getFirstChild();

	/**
	 * Get the next sibling of any node. False if there isn't one
	 * @return PPNode
	 */
	public function getNextSibling();

	/**
	 * Get all children of this tree node which have a given name.
	 * Returns an array-type node, or false if this is not a tree node.
	 * @param string $type
	 * @return bool|PPNode
	 */
	public function getChildrenOfType( $type );

	/**
	 * Returns the length of the array, or false if this is not an array-type node
	 */
	public function getLength();

	/**
	 * Returns an item of an array-type node
	 * @param int $i
	 * @return bool|PPNode
	 */
	public function item( $i );

	/**
	 * Get the name of this node. The following names are defined here:
	 *
	 *    h             A heading node.
	 *    template      A double-brace node.
	 *    tplarg        A triple-brace node.
	 *    title         The first argument to a template or tplarg node.
	 *    part          Subsequent arguments to a template or tplarg node.
	 *    #nodelist     An array-type node
	 *
	 * The subclass may define various other names for tree and leaf nodes.
	 * @return string
	 */
	public function getName();

	/**
	 * Split a "<part>" node into an associative array containing:
	 *    name          PPNode name
	 *    index         String index
	 *    value         PPNode value
	 * @return array
	 */
	public function splitArg();

	/**
	 * Split an "<ext>" node into an associative array containing name, attr, inner and close
	 * All values in the resulting array are PPNodes. Inner and close are optional.
	 * @return array
	 */
	public function splitExt();

	/**
	 * Split an "<h>" node
	 * @return array
	 */
	public function splitHeading();
}
