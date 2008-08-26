<?php

/** Copyright (C) 2008 Guy Van den Broeck <guy@guyvdb.eu>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * or see http://www.gnu.org/
 * 
 */
 
/**
 * Any element in the DOM tree of an HTML document.
 * @ingroup DifferenceEngine
 */
class Node {

	public $parent;

	protected $parentTree;

	public $whiteBefore = false;

	public $whiteAfter = false;

	function __construct($parent) {
		$this->parent = $parent;
	}

	public function getParentTree() {
		if (!isset($this->parentTree)) {
			if (!is_null($this->parent)) {
				$this->parentTree = $this->parent->getParentTree();
				$this->parentTree[] = $this->parent;
			} else {
				$this->parentTree = array();
			}
		}
		return $this->parentTree;
	}

	public function getLastCommonParent(Node $other) {
		$result = new LastCommonParentResult();

		$myParents = $this->getParentTree();
		$otherParents = $other->getParentTree();

		$i = 1;
		$isSame = true;
		$nbMyParents = count($myParents);
		$nbOtherParents = count($otherParents);
		while ($isSame && $i < $nbMyParents && $i < $nbOtherParents) {
			if (!$myParents[$i]->openingTag === $otherParents[$i]->openingTag) {
				$isSame = false;
			} else {
				// After a while, the index i-1 must be the last common parent
				$i++;
			}
		}

		$result->lastCommonParentDepth = $i - 1;
		$result->parent = $myParents[$i - 1];

		if (!$isSame || $nbMyParents > $nbOtherParents) {
			// Not all tags matched, or all tags matched but
			// there are tags left in this tree
			$result->indexInLastCommonParent = $myParents[$i - 1]->getIndexOf($myParents[$i]);
			$result->splittingNeeded = true;
		} else if ($nbMyParents <= $nbOtherParents) {
			$result->indexInLastCommonParent = $myParents[$i - 1]->getIndexOf($this);
		}
		return $result;
	}

	public function setParent($parent) {
		$this->parent = $parent;
		unset($this->parentTree);
	}

	public function inPre() {
		$tree = $this->getParentTree();
		foreach ($tree as &$ancestor) {
			if ($ancestor->isPre()) {
				return true;
			}
		}
		return false;
	}
}

/**
 * Node that can contain other nodes. Represents an HTML tag.
 * @ingroup DifferenceEngine
 */
class TagNode extends Node {

	public $children = array();

	public $qName;

	public $attributes = array();

	public $openingTag;

	function __construct($parent, $qName, /*array*/ $attributes) {
		parent::__construct($parent);
		$this->qName = strtolower($qName);
		foreach($attributes as $key => &$value){
			$this->attributes[strtolower($key)] = $value;
		}
		return $this->openingTag = Xml::openElement($this->qName, $this->attributes);
	}

	public function addChildAbsolute(Node $node, $index) {
		array_splice($this->children, $index, 0, array($node));
	}

	public function getIndexOf(Node $child) {
		// don't trust array_search with objects
		foreach ($this->children as $key => &$value){
			if ($value === $child) {
				return $key;
			}
		}
		return null;
	}

	public function getNbChildren() {
		return count($this->children);
	}

	public function getMinimalDeletedSet($id, &$allDeleted, &$somethingDeleted) {
		$nodes = array();

		$allDeleted = false;
		$somethingDeleted = false;
		$hasNonDeletedDescendant = false;

		if (empty($this->children)) {
			return $nodes;
		}

		foreach ($this->children as &$child) {
			$allDeleted_local = false;
			$somethingDeleted_local = false;
			$childrenChildren = $child->getMinimalDeletedSet($id, $allDeleted_local, $somethingDeleted_local);
			if ($somethingDeleted_local) {
				$nodes = array_merge($nodes, $childrenChildren);
				$somethingDeleted = true;
			}
			if (!$allDeleted_local) {
				$hasNonDeletedDescendant = true;
			}
		}
		if (!$hasNonDeletedDescendant) {
			$nodes = array($this);
			$allDeleted = true;
		}
		return $nodes;
	}

	public function splitUntil(TagNode $parent, Node $split, $includeLeft) {
		$splitOccured = false;
		if ($parent !== $this) {
			$part1 = new TagNode(null, $this->qName, $this->attributes);
			$part2 = new TagNode(null, $this->qName, $this->attributes);
			$part1->setParent($this->parent);
			$part2->setParent($this->parent);

			$onSplit = false;
			$pastSplit = false;
			foreach ($this->children as &$child)
			{
				if ($child === $split) {
					$onSplit = true;
				}
				if(!$pastSplit || ($onSplit && $includeLeft)) {
					$child->setParent($part1);
					$part1->children[] = $child;
				} else {
					$child->setParent($part2);
					$part2->children[] = $child;
				}
				if ($onSplit) {
					$onSplit = false;
					$pastSplit = true;
				}
			}
			$myindexinparent = $this->parent->getIndexOf($this);
			if (!empty($part1->children)) {
				$this->parent->addChildAbsolute($part1, $myindexinparent);
			}
			if (!empty($part2->children)) {
				$this->parent->addChildAbsolute($part2, $myindexinparent);
			}
			if (!empty($part1->children) && !empty($part2->children)) {
				$splitOccured = true;
			}

			$this->parent->removeChild($myindexinparent);

			if ($includeLeft) {
				$this->parent->splitUntil($parent, $part1, $includeLeft);
			} else {
				$this->parent->splitUntil($parent, $part2, $includeLeft);
			}
		}
		return $splitOccured;

	}

	private function removeChild($index) {
		unset($this->children[$index]);
		$this->children = array_values($this->children);
	}

	public static $blocks = array('html', 'body','p','blockquote', 'h1',
		'h2', 'h3', 'h4', 'h5', 'pre', 'div', 'ul', 'ol', 'li', 'table',
		'tbody', 'tr', 'td', 'th', 'br');

	public function copyTree() {
		$newThis = new TagNode(null, $this->qName, $this->attributes);
		$newThis->whiteBefore = $this->whiteBefore;
		$newThis->whiteAfter = $this->whiteAfter;
		foreach ($this->children as &$child) {
			$newChild = $child->copyTree();
			$newChild->setParent($newThis);
			$newThis->children[] = $newChild;
		}
		return $newThis;
	}

	public function getMatchRatio(TagNode $other) {
		$txtComp = new TextOnlyComparator($other);
		return $txtComp->getMatchRatio(new TextOnlyComparator($this));
	}

	public function expandWhiteSpace() {
		$shift = 0;
		$spaceAdded = false;

		$nbOriginalChildren = $this->getNbChildren();
		for ($i = 0; $i < $nbOriginalChildren; ++$i) {
			$child = $this->children[$i + $shift];

			if ($child instanceof TagNode) {
				if (!$child->isPre()) {
					$child->expandWhiteSpace();
				}
			}
			if (!$spaceAdded && $child->whiteBefore) {
				$ws = new WhiteSpaceNode(null, ' ', $child->getLeftMostChild());
				$ws->setParent($this);
				$this->addChildAbsolute($ws,$i + ($shift++));
			}
			if ($child->whiteAfter) {
				$ws = new WhiteSpaceNode(null, ' ', $child->getRightMostChild());
				$ws->setParent($this);
				$this->addChildAbsolute($ws,$i + 1 + ($shift++));
				$spaceAdded = true;
			} else {
				$spaceAdded = false;
			}

		}
	}

	public function getLeftMostChild() {
		if (empty($this->children)) {
			return $this;
		}
		return $this->children[0]->getLeftMostChild();
	}

	public function getRightMostChild() {
		if (empty($this->children)) {
			return $this;
		}
		return $this->children[$this->getNbChildren() - 1]->getRightMostChild();
	}

	public function isPre() {
		return 0 == strcasecmp($this->qName,'pre');
	}

	public static function toDiffLine(TagNode $node) {
		return $node->openingTag;
	}
}

/**
 * Represents a piece of text in the HTML file.
 * @ingroup DifferenceEngine
 */
class TextNode extends Node {

	public $text;

	public $modification;

	function __construct($parent, $text) {
		parent::__construct($parent);
		$this->modification = new Modification(Modification::NONE);
		$this->text = $text;
	}

	public function copyTree() {
		$clone = clone $this;
		$clone->setParent(null);
		return $clone;
	}

	public function getLeftMostChild() {
		return $this;
	}

	public function getRightMostChild() {
		return $this;
	}

	public function getMinimalDeletedSet($id, &$allDeleted, &$somethingDeleted) {
		if ($this->modification->type == Modification::REMOVED
					&& $this->modification->id == $id){
			$somethingDeleted = true;
			$allDeleted = true;
			return array($this);
		}
		return array();
	}

	public function isSameText($other) {
		if (is_null($other) || ! $other instanceof TextNode) {
			return false;
		}
		return str_replace('\n', ' ',$this->text) === str_replace('\n', ' ',$other->text);
	}

	public static function toDiffLine(TextNode $node) {
		return str_replace('\n', ' ',$node->text);
	}
}

/**
 * @todo Document
 * @ingroup DifferenceEngine
 */
class WhiteSpaceNode extends TextNode {

	function __construct($parent, $s, Node $like = null) {
		parent::__construct($parent, $s);
		if(!is_null($like) && $like instanceof TextNode) {
			$newModification = clone $like->modification;
			$newModification->firstOfID = false;
			$this->modification = $newModification;
		}
	}
}

/**
 * Represents the root of a HTML document.
 * @ingroup DifferenceEngine
 */
class BodyNode extends TagNode {

	function __construct() {
		parent::__construct(null, 'body', array());
	}

	public function copyTree() {
		$newThis = new BodyNode();
		foreach ($this->children as &$child) {
			$newChild = $child->copyTree();
			$newChild->setParent($newThis);
			$newThis->children[] = $newChild;
		}
		return $newThis;
	}

	public function getMinimalDeletedSet($id, &$allDeleted, &$somethingDeleted) {
		$nodes = array();
		foreach ($this->children as &$child) {
			$childrenChildren = $child->getMinimalDeletedSet($id,
						$allDeleted, $somethingDeleted);
			$nodes = array_merge($nodes, $childrenChildren);
		}
		return $nodes;
	}

}

/**
 * Represents an image in HTML. Even though images do not contain any text they
 * are independent visible objects on the page. They are logically a TextNode.
 * @ingroup DifferenceEngine
 */
class ImageNode extends TextNode {

	public $attributes;

	function __construct(TagNode $parent, /*array*/ $attrs) {
		if(!array_key_exists('src', $attrs)) {
			HTMLDiffer::diffDebug( "Image without a source\n" );
			parent::__construct($parent, '<img></img>');
		}else{
			parent::__construct($parent, '<img>' . strtolower($attrs['src']) . '</img>');
		}
		$this->attributes = $attrs;
	}

	public function isSameText($other) {
		if (is_null($other) || ! $other instanceof ImageNode) {
			return false;
		}
		return $this->text === $other->text;
	}

}

/**
 * No-op node
 * @ingroup DifferenceEngine
 */
class DummyNode extends Node {

	function __construct() {
		// no op
	}

}
