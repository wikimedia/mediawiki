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
	 * @return false|PPNode
	 */
	public function getChildren();

	/**
	 * Get the first child of a tree node. False if there isn't one.
	 *
	 * @return false|PPNode
	 */
	public function getFirstChild();

	/**
	 * Get the next sibling of any node. False if there isn't one
	 * @return false|PPNode
	 */
	public function getNextSibling();

	/**
	 * Get all children of this tree node which have a given name.
	 * Returns an array-type node, or false if this is not a tree node.
	 * @param string $type
	 * @return false|PPNode
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
