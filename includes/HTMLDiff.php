<?php
/* Copyright (C) 2008 Guy Van den Broeck <guy@guyvdb.eu>
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
 */


/**
 * Any element in the DOM tree of an HTML document.
 */
abstract class Node{

	protected $parent;


	function __construct($parent){
		$this->parent = $parent;
		if(!is_null($parent)){
			$parent->addChild($this);
		}
	}

	public function getParent(){
		return $this->parent;
	}

	public function getParentTree(){
		if(!is_null($this->parent)){
			$parentTree = $this->parent->getParentTree();
			$parentTree[] = $this->parent;
			return $parentTree;
		}else{
			return array();
		}
	}

	public abstract function getMinimalDeletedSet($id);

	public function detectIgnorableWhiteSpace(){
		// no op
	}

	public function getLastCommonParent(Node $other){
		if(is_null($other))
		throw new Exception('The given node is NULL');

		$result = new LastCommonParentResult();

		$myParents = $this->getParentTree();
		$otherParents = $other->getParentTree();

		$i = 1;
		$isSame = true;
		while ($isSame && $i < sizeof($myParents) && $i < sizeof($otherParents)) {
			if (!$myParents[$i]->isSameTag($otherParents[$i])) {
				$isSame = false;
			} else {
				// After the while, the index i-1 must be the last common parent
				$i++;
			}
		}

		$result->setLastCommonParentDepth($i - 1);
		$result->setLastCommonParent($myParents[$i - 1]);

		if (!$isSame) {
			$result->setIndexInLastCommonParent($myParents[$i - 1]->getIndexOf($myParents[$i]));
			$result->setSplittingNeeded();
		} else if (sizeof($myParents) < sizeof($otherParents)) {
			$result->setIndexInLastCommonParent($myParents[$i - 1]->getIndexOf($this));
		} else if (sizeof($myParents) > sizeof($otherParents)) {
			// All tags matched but there are tags left in this tree
			$result->setIndexInLastCommonParent($myParents[$i - 1]->getIndexOf($myParents[$i]));
			$result->setSplittingNeeded();
		} else {
			// All tags matched untill the very last one in both trees
			// or there were no tags besides the BODY
			$result->setIndexInLastCommonParent($myParents[$i - 1]->getIndexOf($this));
		}
		return $result;
	}

	public function setParent($parent) {
		$this->parent = $parent;
	}

	public abstract function copyTree();

	public function inPre() {
		$tree = $this->getParentTree();
		foreach ($tree as $ancestor) {
			if ($ancestor->isPre()) {
				return true;
			}
		}
		return false;
	}

	private $whiteBefore = false;

	private $whiteAfter = false;

	public function isWhiteBefore() {
		return $this->whiteBefore;
	}

	public function setWhiteBefore($whiteBefore) {
		$this->whiteBefore = $whiteBefore;
	}

	public function isWhiteAfter() {
		return $this->whiteAfter;
	}

	public function setWhiteAfter($whiteAfter) {
		$this->whiteAfter = $whiteAfter;
	}

	public abstract function getLeftMostChild();

	public abstract function getRightMostChild();
}

/**
 * Node that can contain other nodes. Represents an HTML tag.
 */
class TagNode extends Node{

	public $children = array();

	protected $qName;

	protected $attributes = array();

	function __construct($parent, $qName, /*array*/ $attributes) {
		parent::__construct($parent);
		$this->qName = strtolower($qName);
		foreach($attributes as $key => $value){
			$this->attributes[strtolower($key)] = $value;
		}
	}

	public function addChild(Node $node, $index=-1) {
		if ($node->getParent() !== $this)
		throw new Exception(
                    'The new child must have this node as a parent.');
		if($index == -1){
			$this->children[] = $node;
		}else{
			array_splice($this->children,$index,0,array($node));
		}
	}

	public function getIndexOf(Node $child) {
		// don't trust array_search with objects
		foreach($this->children as $key=>$value){
			if($value === $child){
				return $key;
			}
		}
		return NULL;
	}

	public function getChild($i) {
		return $this->children[$i];
	}

	public function getNbChildren() {
		return count($this->children);
	}

	public function getQName() {
		return $this->qName;
	}

	public function getAttributes() {
		return $this->attributes;
	}

	public function isSameTag(TagNode $other) {
		if (is_null($other))
		return false;
		return $this->getOpeningTag() === $other->getOpeningTag();
	}

	public function getOpeningTag() {
		$s = '<'.$this->getQName();
		foreach ($this->attributes as $attribute => $value) {
			$s .= ' ' . $attribute . '="' . $value . '"';
		}
		return $s .= '>';
	}

	public function getEndTag() {
		return '</' . $this->getQName() + '>"';
	}

	public function getMinimalDeletedSet($id) {
		$nodes = array();

		if ($this->getNbChildren() == 0)
		return $nodes;

		$hasNotDeletedDescendant = false;

		foreach ($this->children as $child) {
			$childrenChildren = $child->getMinimalDeletedSet($id);
			$nodes = array_merge($nodes, $childrenChildren);
			if (!$hasNotDeletedDescendant
			&& !(count($childrenChildren) == 1 && $childrenChildren[0]===$child)) {
				// This child is not entirely deleted
				$hasNotDeletedDescendant = true;
			}
		}
		if (!$hasNotDeletedDescendant) {
			$nodes = array($this);
		}
		return $nodes;
	}

	public function toString() {
		return $this->getOpeningTag();
	}

	public function splitUntill(TagNode $parent, Node $split, $includeLeft) {
		$splitOccured = false;
		if ($parent !== $this) {
			$part1 = new TagNode(NULL, $this->getQName(), $this->getAttributes());
			$part2 = new TagNode(NULL, $this->getQName(), $this->getAttributes());
			$part1->setParent($this->getParent());
			$part2->setParent($this->getParent());

			$i = 0;
			$nbChildren = $this->getNbChildren();
			while ($i < $nbChildren && $this->children[$i] !== $split) {
				$this->children[$i]->setParent($part1);
				$part1->addChild($this->children[$i]);
				++$i;
			}
			if ($i < $nbChildren) {
				if ($includeLeft) {
					$this->children[$i]->setParent($part1);
					$part1->addChild($this->children[$i]);
				} else {
					$this->children[$i]->setParent($part2);
					$part2->addChild($this->children[$i]);
				}
				++$i;
			}
			while ($i < $nbChildren) {
				$this->children[$i]->setParent($part2);
				$part2->addChild($this->children[$i]);
				++$i;
			}
			$myindexinparent = $this->parent->getIndexOf($this);
			if ($part1->getNbChildren() > 0)
			$this->parent->addChild($part1,$myindexinparent);

			if ($part2->getNbChildren() > 0)
			$this->parent->addChild($part2,$myindexinparent);

			if ($part1->getNbChildren() > 0 && $part2->getNbChildren() > 0) {
				$splitOccured = true;
			}

			$this->parent->removeChild($myindexinparent);

			if ($includeLeft)
			$this->parent->splitUntill($parent, $part1, $includeLeft);
			else
			$this->parent->splitUntill($parent, $part2, $includeLeft);
		}
		return $splitOccured;

	}

	private function removeChild($index) {
		unset($this->children[$index]);
		$this->children = array_values($this->children);
	}

	public static $blocks = array('html'=>TRUE,'body'=>TRUE,'p'=>TRUE,'blockquote'=>TRUE,
    	'h1'=>TRUE,'h2'=>TRUE,'h3'=>TRUE,'h4'=>TRUE,'h5'=>TRUE,'pre'=>TRUE,'div'=>TRUE,'ul'=>TRUE,'ol'=>TRUE,'li'=>TRUE,
    	'table'=>TRUE,'tbody'=>TRUE,'tr'=>TRUE,'td'=>TRUE,'th'=>TRUE,'br'=>TRUE);

	public static function isBlockLevelName($qName) {
		return array_key_exists(strtolower($qName),self::$blocks);
	}

	public static function isBlockLevelNode(Node $node) {
		if(! $node instanceof TagNode)
		return false;
		return self::isBlockLevelName($node->getQName());
	}

	public function isBlockLevel() {
		return  self::isBlockLevelNode($this);
	}

	public static function isInlineName($qName) {
		return !self::isBlockLevelName($qName);
	}

	public static function isInlineNode(Node $node) {
		return !self::isBlockLevelNode($node);
	}

	public function isInline() {
		return self::isInlineNode($this);
	}

	public function copyTree() {
		$newThis = new TagNode(NULL, $this->getQName(), $this->getAttributes());
		$newThis->setWhiteBefore($this->isWhiteBefore());
		$newThis->setWhiteAfter($this->isWhiteAfter());
		foreach($this->children as $child) {
			$newChild = $child->copyTree();
			$newChild->setParent($newThis);
			$newThis->addChild($newChild);
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
			$child = $this->getChild($i + $shift);

			if($child instanceof TagNode){
				if (!$child->isPre()) {
					$child->expandWhiteSpace();
				}
			}
			if (!$spaceAdded && $child->isWhiteBefore()) {
				$ws = new WhiteSpaceNode(NULL, ' ', $child->getLeftMostChild());
				$ws->setParent($this);
				$this->addChild($ws,$i + ($shift++));
			}
			if ($child->isWhiteAfter()) {
				$ws = new WhiteSpaceNode(NULL, ' ', $child->getRightMostChild());
				$ws->setParent($this);
				$this->addChild($ws,$i + 1 + ($shift++));
				$spaceAdded = true;
			} else {
				$spaceAdded = false;
			}

		}
	}

	public function getLeftMostChild() {
		if ($this->getNbChildren() < 1)
		return $this;
		return $this->getChild(0)->getLeftMostChild();

	}

	public function getRightMostChild() {
		if ($this->getNbChildren() < 1)
		return $this;
		return $this->getChild($this->getNbChildren() - 1)->getRightMostChild();
	}

	public function isPre() {
		return 0 == strcasecmp($this->getQName(),'pre');
	}

	public static function toDiffLine(TagNode $node){
		return $node->getOpeningTag();
	}
}

/**
 * Represents a piece of text in the HTML file.
 */
class TextNode extends Node{

	private $s;

	private $modification;

	function __construct($parent, $s) {
		parent::__construct($parent);
		$this->modification = new Modification(Modification::NONE);
		$this->s = $s;
	}

	public function copyTree() {
		$clone = clone $this;
		$clone->setParent(NULL);
		return $clone;
	}

	public function getLeftMostChild() {
		return $this;
	}

	public function getRightMostChild() {
		return $this;
	}

	public function getMinimalDeletedSet($id) {
		if ($this->getModification()->getType() == Modification::REMOVED
		&& $this->getModification()->getID() == $id){
			return array($this);
		}
		return array();
	}

	public function getModification() {
		return $this->modification;
	}

	public function getText() {
		return $this->s;
	}

	public function isSameText($other) {
		if (is_null($other) || ! $other instanceof TextNode){
			return false;
		}
		return str_replace('\n', ' ',$this->getText()) === str_replace('\n', ' ',$other->getText());
	}

	public function setModification(Modification $m) {
		//wfDebug("Registered modification for node '$this->s' as ".Modification::typeToString($m->getType()));
		$this->modification = $m;
	}

	public function toString() {
		return $this->getText();
	}

	public static function toDiffLine(TextNode $node){
		return str_replace('\n', ' ',$node->getText());
	}
}

class WhiteSpaceNode extends TextNode {

	function __construct($parent, $s, Node $like = NULL) {
		parent::__construct($parent, $s);
		if(!is_null($like) && $like instanceof TextNode){
			$newModification = clone $like->getModification();
			$newModification->setFirstOfID(false);
			$this->setModification($newModification);
		}
	}

	public static function isWhiteSpace($c) {
		switch ($c) {
			case ' ':
			case '\t':
			case '\r':
			case '\n':
				return true;
			default:
				return false;
		}
	}
}

/**
 * Represents the root of a HTML document.
 */
class BodyNode extends TagNode {

	function __construct() {
		parent::__construct(NULL, 'body', array());
	}

	public function copyTree() {
		$newThis = new BodyNode();
		foreach ($this->children as $child) {
			$newChild = $child->copyTree();
			$newChild->setParent($newThis);
			$newThis->addChild($newChild);
		}
		return $newThis;
	}

	public function getMinimalDeletedSet($id) {
		$nodes = array();
		foreach ($this->children as $child) {
			$childrenChildren = $child->getMinimalDeletedSet($id);
			$nodes = array_merge($nodes, $childrenChildren);
		}
		return $nodes;
	}

}

/**
 * Represents an image in HTML. Even though images do not contain any text they
 * are independent visible objects on the page. They are logically a TextNode.
 */
class ImageNode extends TextNode {

	private $attributes;

	function __construct(TagNode $parent, /*array*/ $attrs) {
		if(!array_key_exists('src',$attrs)){
			//wfDebug('Image without a source:');
			foreach($attrs as $key => $value){
				//wfDebug("$key = $value");
			}
			parent::__construct($parent, '<img></img>');
		}else{
			parent::__construct($parent, '<img>' . strtolower($attrs['src']) . '</img>');
		}
		$this->attributes = $attrs;
	}

	public function isSameText($other) {
		if (is_null($other) || ! $other instanceof ImageNode)
		return false;
		return $this->getText() === $other->getText();
	}

	public function getAttributes() {
		return $this->attributes;
	}

}

/**
 * When detecting the last common parent of two nodes, all results are stored as
 * a LastCommonParentResult.
 */
class LastCommonParentResult {

	// Parent
	private $parent;

	public function getLastCommonParent() {
		return $this->parent;
	}

	public function setLastCommonParent(TagNode $parent) {
		$this->parent = $parent;
	}

	// Splitting
	private $splittingNeeded = false;

	public function isSplittingNeeded() {
		return $this->splittingNeeded;
	}

	public function setSplittingNeeded() {
		$this->splittingNeeded = true;
	}

	// Depth
	private $lastCommonParentDepth = -1;

	public function getLastCommonParentDepth() {
		return $this->lastCommonParentDepth;
	}

	public function setLastCommonParentDepth($depth) {
		$this->lastCommonParentDepth = $depth;
	}

	// Index
	private $indexInLastCommonParent = -1;

	public function getIndexInLastCommonParent() {
		return $this->indexInLastCommonParent;
	}

	public function setIndexInLastCommonParent($index) {
		$this->indexInLastCommonParent = $index;
	}
}

class Modification{

	const NONE = 1;
	const REMOVED = 2;
	const ADDED = 4;
	const CHANGED = 8;

	private $type;

	private $id = -1;

	private $prevMod;

	private $nextMod;

	private $firstOfID = false;

	function __construct($type) {
		$this->type = $type;
	}

	public function copy() {
		$newM = new Modification($this->getType());
		$newM->setID($this->getID());
		$newM->setChanges($this->getChanges());
		$newM->setFirstOfID($this->isFirstOfID());
		$newM->setNext($this->getNext());
		$newM->setPrevious($this->getPrevious());
		return $newM;
	}

	public function getType() {
		return $this->type;
	}

	public function setID($id) {
		$this->id = $id;
	}

	public function getID() {
		return $this->id;
	}

	public function setPrevious($m) {
		$this->prevMod = $m;
	}

	public function getPrevious() {
		return $this->prevMod;
	}

	public function setNext($m) {
		$this->nextMod = $m;
	}

	public function getNext() {
		return $this->nextMod;
	}

	private $changes;

	public function setChanges($changes) {
		$this->changes = $changes;
	}

	public function getChanges() {
		return $this->changes;
	}

	public function isFirstOfID() {
		return $this->firstOfID;
	}

	public function setFirstOfID($firstOfID) {
		$this->firstOfID = $firstOfID;
	}

	public static function typeToString($type){
		switch($type){
			case self::NONE: return 'none';
			case self::REMOVED: return 'removed';
			case self::ADDED: return 'added';
			case self::CHANGED: return 'changed';
		}
	}
}

class DomTreeBuilder {

	private $textNodes = array();

	private $bodyNode;

	private $currentParent;

	private $newWord = "";

	protected $bodyStarted = false;

	protected $bodyEnded = false;

	private $whiteSpaceBeforeThis = false;

	private $lastSibling;

	function __construct(){
		$this->bodyNode = $this->currentParent = new BodyNode();
	}

	public function getBodyNode() {
		return $this->bodyNode;
	}

	public function getTextNodes() {
		return $this->textNodes;
	}

	/**
	 * Must be called manually
	 */
	public function endDocument() {
		$this->endWord();
		//wfDebug(sizeof($this->textNodes) . ' text nodes in document.');
	}

	public function startElement($parser, $name, /*array*/ $attributes) {
		if(!strcasecmp($name, 'body')==0){
			//wfDebug("Starting $name node.");
			$this->endWord();

			$newTagNode = new TagNode($this->currentParent, $name, $attributes);
			$this->currentParent = $newTagNode;
			$this->lastSibling = NULL;
			if ($this->whiteSpaceBeforeThis && $newTagNode->isInline()) {
				$newTagNode->setWhiteBefore(true);
			}
			$this->whiteSpaceBeforeThis = false;
		}
	}

	public function endElement($parser, $name) {
		if(!strcasecmp($name, 'body')==0){
			//wfDebug("Ending $name node.");
			if (0 == strcasecmp($name,'img')) {
				// Insert a dummy leaf for the image
				$img = new ImageNode($this->currentParent, $this->currentParent->getAttributes());
				$img->setWhiteBefore($this->whiteSpaceBeforeThis);
				$this->lastSibling = $img;
				$this->textNodes[] = $img;
			}
			$this->endWord();
			if ($this->currentParent->isInline()) {
				$this->lastSibling = $this->currentParent;
			} else {
				$this->lastSibling = NULL;
			}
			$this->currentParent = $this->currentParent->getParent();
			$this->whiteSpaceBeforeThis = false;
		}else{
			$this->endDocument();
		}
	}

	public function characters($parser, $data){
		//wfDebug('Parsing '. strlen($data).' characters.');
		$array = str_split($data);
		foreach($array as $c) {
			if (self::isDelimiter($c)) {
				$this->endWord();
				if (WhiteSpaceNode::isWhiteSpace($c) && !$this->currentParent->isPre()
				&& !$this->currentParent->inPre()) {
					if (!is_null($this->lastSibling)){
						$this->lastSibling->setWhiteAfter(true);
					}
					$this->whiteSpaceBeforeThis = true;
				} else {
					$textNode = new TextNode($this->currentParent, $c);
					$textNode->setWhiteBefore($this->whiteSpaceBeforeThis);
					$this->whiteSpaceBeforeThis = false;
					$this->lastSibling = $textNode;
					$this->textNodes[] = $textNode;
				}
			} else {
				$this->newWord .= $c;
			}

		}
	}

	private function endWord() {
		if (strlen($this->newWord) > 0) {
			$node = new TextNode($this->currentParent, $this->newWord);
			$node->setWhiteBefore($this->whiteSpaceBeforeThis);
			$this->whiteSpaceBeforeThis = false;
			$this->lastSibling = $node;
			$this->textNodes[] = $node;
			$this->newWord = "";
		}
	}

	public static function isDelimiter($c) {
		switch ($c) {
			// Basic Delimiters
			case '/':
			case '.':
			case '!':
			case ',':
			case ';':
			case '?':
			case '=':
			case '\'':
			case '"':
				// Extra Delimiters
			case '[':
			case ']':
			case '{':
			case '}':
			case '(':
			case ')':
			case '&':
			case '|':
			case '\\':
			case '-':
			case '_':
			case '+':
			case '*':
			case ':':
				return true;
			default:
				return WhiteSpaceNode::isWhiteSpace($c);
		}
	}

	public function getDiffLines(){
		return array_map(array('TextNode','toDiffLine'), $this->textNodes);
	}
}

class TextNodeDiffer{

	private $textNodes;
	private $bodyNode;

	private $oldTextNodes;
	private $oldBodyNode;

	private $lastModified = array();

	function __construct(DomTreeBuilder $tree, DomTreeBuilder $oldTree) {
		$this->textNodes = $tree->getTextNodes();
		$this->bodyNode = $tree->getBodyNode();
		$this->oldTextNodes = $oldTree->getTextNodes();
		$this->oldBodyNode = $oldTree->getBodyNode();
	}

	public function getBodyNode() {
		return $this->bodyNode;
	}

	private $newID = 0;

	public function markAsNew($start, $end) {
		if ($end <= $start)
		return;

		if ($this->whiteAfterLastChangedPart)
		$this->textNodes[$start]->setWhiteBefore(false);

		$nextLastModified = array();

		for ($i = $start; $i < $end; ++$i) {
			$mod = new Modification(Modification::ADDED);
			$mod->setID($this->newID);
			if (sizeof($this->lastModified) > 0) {
				$mod->setPrevious($this->lastModified[0]);
				if (is_null($this->lastModified[0]->getNext())) {
					foreach ($this->lastModified as $lastMod) {
						$lastMod->setNext($mod);
					}
				}
			}
			$nextLastModified[] = $mod;
			$this->textNodes[$i]->setModification($mod);
		}
		if ($start < $end) {
			$this->textNodes[$start]->getModification()->setFirstOfID(true);
		}
		++$this->newID;
		$this->lastModified = $nextLastModified;
	}

	private $changedID = 0;

	private $changedIDUsed = false;

	public function handlePossibleChangedPart($leftstart, $leftend,	$rightstart, $rightend) {
		$i = $rightstart;
		$j = $leftstart;

		if ($this->changedIDUsed) {
			++$this->changedID;
			$this->changedIDUsed = false;
		}

		$nextLastModified = array();

		$changes;
		while ($i < $rightend) {
			$acthis = new AncestorComparator($this->textNodes[$i]->getParentTree());
			$acother = new AncestorComparator($this->oldTextNodes[$j]->getParentTree());
			$result = $acthis->getResult($acother);
			unset($acthis, $acother);

			$nbLastModified = sizeof($this->lastModified);
			if ($result->isChanged()) {
				$mod = new Modification(Modification::CHANGED);

				if (!$this->changedIDUsed) {
					$mod->setFirstOfID(true);
					if (sizeof($nextLastModified) > 0) {
						$this->lastModified = $nextLastModified;
						$nextLastModified = array();
					}
				} else if (!is_null($result->getChanges()) && $result->getChanges() != $this->changes) {
					++$this->changedID;
					$mod->setFirstOfID(true);
					if (sizeof($nextLastModified) > 0) {
						$this->lastModified = $nextLastModified;
						$nextLastModified = array();
					}
				}

				if ($nbLastModified > 0) {
					$mod->setPrevious($this->lastModified[0]);
					if (is_null($this->lastModified[0]->getNext())) {
						foreach ($this->lastModified as $lastMod) {
							$lastMod->setNext($mod);
						}
					}
				}
				$nextLastModified[] = $mod;

				$mod->setChanges($result->getChanges());
				$mod->setID($this->changedID);

				$this->textNodes[$i]->setModification($mod);
				$this->changes = $result->getChanges();
				$this->changedIDUsed = true;
			} else if ($this->changedIDUsed) {
				++$this->changedID;
				$this->changedIDUsed = false;
			}
			++$i;
			++$j;
		}
		if (sizeof($nextLastModified) > 0){
			$this->lastModified = $nextLastModified;
		}
	}

	// used to remove the whitespace between a red and green block
	private $whiteAfterLastChangedPart = false;

	private $deletedID = 0;

	public function markAsDeleted($start, $end, $before) {

		if ($end <= $start)
		return;

		if ($before > 0 && $this->textNodes[$before - 1]->isWhiteAfter()) {
			$this->whiteAfterLastChangedPart = true;
		} else {
			$this->whiteAfterLastChangedPart = false;
		}

		$nextLastModified = array();

		for ($i = $start; $i < $end; ++$i) {
			$mod = new Modification(Modification::REMOVED);
			$mod->setID($this->deletedID);
			if (sizeof($this->lastModified) > 0) {
				$mod->setPrevious($this->lastModified[0]);
				if (is_null($this->lastModified[0]->getNext())) {
					foreach ($this->lastModified as $lastMod) {
						$lastMod->setNext($mod);
					}
				}
			}
			$nextLastModified[] = $mod;

			// oldTextNodes is used here because we're going to move its deleted
			// elements
			// to this tree!
			$this->oldTextNodes[$i]->setModification($mod);
		}
		$this->oldTextNodes[$start]->getModification()->setFirstOfID(true);

		$deletedNodes = $this->oldBodyNode->getMinimalDeletedSet($this->deletedID);

		//wfDebug("Minimal set of deleted nodes of size " . sizeof($deletedNodes));

		// Set prevLeaf to the leaf after which the old HTML needs to be
		// inserted
		if ($before > 0){
			$prevLeaf = $this->textNodes[$before - 1];
		}
		// Set nextLeaf to the leaf before which the old HTML needs to be
		// inserted
		if ($before < sizeof($this->textNodes)){
			$nextLeaf = $this->textNodes[$before];
		}

		while (sizeof($deletedNodes) > 0) {
			if (isset($prevLeaf)) {
				$prevResult = $prevLeaf->getLastCommonParent($deletedNodes[0]);
			} else {
				$prevResult = new LastCommonParentResult();
				$prevResult->setLastCommonParent($this->getBodyNode());
				$prevResult->setIndexInLastCommonParent(0);
			}
			if (isset($nextleaf)) {
				$nextResult = $nextLeaf->getLastCommonParent($deletedNodes[sizeof($deletedNodes) - 1]);
			} else {
				$nextResult = new LastCommonParentResult();
				$nextResult->setLastCommonParent($this->getBodyNode());
				$nextResult->setIndexInLastCommonParent($this->getBodyNode()->getNbChildren());
			}

			if ($prevResult->getLastCommonParentDepth() == $nextResult->getLastCommonParentDepth()) {
				// We need some metric to choose which way to add-...
				if ($deletedNodes[0]->getParent() === $deletedNodes[sizeof($deletedNodes) - 1]->getParent()
				&& $prevResult->getLastCommonParent() === $nextResult->getLastCommonParent()) {
					// The difference is not in the parent
					$prevResult->setLastCommonParentDepth($prevResult->getLastCommonParentDepth() + 1);
				} else {
					// The difference is in the parent, so compare them
					// now THIS is tricky
					$distancePrev = $deletedNodes[0]->getParent()->getMatchRatio($prevResult->getLastCommonParent());
					$distanceNext = $deletedNodes[sizeof($deletedNodes) - 1]->getParent()->getMatchRatio($nextResult->getLastCommonParent());

					if ($distancePrev <= $distanceNext) {
						$prevResult->setLastCommonParentDepth($prevResult->getLastCommonParentDepth() + 1);
					} else {
						$nextResult->setLastCommonParentDepth($nextResult->getLastCommonParentDepth() + 1);
					}
				}

			}

			if ($prevResult->getLastCommonParentDepth() > $nextResult->getLastCommonParentDepth()) {
				// Inserting at the front
				if ($prevResult->isSplittingNeeded()) {
					$prevLeaf->getParent()->splitUntill($prevResult->getLastCommonParent(), $prevLeaf, true);
				}
				$prevLeaf = $deletedNodes[0]->copyTree();
				unset($deletedNodes[0]);
				$deletedNodes = array_values($deletedNodes);
				$prevLeaf->setParent($prevResult->getLastCommonParent());
				$prevResult->getLastCommonParent()->addChild($prevLeaf,$prevResult->getIndexInLastCommonParent() + 1);
			} else if ($prevResult->getLastCommonParentDepth() < $nextResult->getLastCommonParentDepth()) {
				// Inserting at the back
				if ($nextResult->isSplittingNeeded()) {
					$splitOccured = $nextLeaf->getParent()->splitUntill($nextResult->getLastCommonParent(), $nextLeaf, false);
					if ($splitOccured) {
						// The place where to insert is shifted one place to the
						// right
						$nextResult->setIndexInLastCommonParent($nextResult->getIndexInLastCommonParent() + 1);
					}
				}
				$nextLeaf = $deletedNodes[sizeof(deletedNodes) - 1]->copyTree();
				unset($deletedNodes[sizeof(deletedNodes) - 1]);
				$deletedNodes = array_values($deletedNodes);
				$nextLeaf->setParent($nextResult->getLastCommonParent());
				$nextResult->getLastCommonParent()->addChild($nextLeaf,$nextResult->getIndexInLastCommonParent());
			} else
			throw new Exception("Uh?");
		}
		$this->lastModified = $nextLastModified;
		++$this->deletedID;
	}

	public function expandWhiteSpace() {
		$this->getBodyNode()->expandWhiteSpace();
	}

	public function lengthNew(){
		return sizeof($this->textNodes);
	}

	public function lengthOld(){
		return sizeof($this->oldTextNodes);
	}
}

class HTMLDiffer{
	
	private $output;
	
	function __construct($output){
		$this->output = $output;
	}

	function htmlDiff($from, $to){
		// Create an XML parser
		$xml_parser = xml_parser_create('');

		$domfrom = new DomTreeBuilder();

		// Set the functions to handle opening and closing tags
		xml_set_element_handler($xml_parser, array($domfrom,"startElement"), array($domfrom,"endElement"));

		// Set the function to handle blocks of character data
		xml_set_character_data_handler($xml_parser, array($domfrom,"characters"));

		;
		//wfDebug('Parsing '.strlen($from)." characters worth of HTML");
		if (!xml_parse($xml_parser, '<?xml version="1.0" encoding="UTF-8"?>'.Sanitizer::hackDocType().'<body>', FALSE)
		|| !xml_parse($xml_parser, $from, FALSE)
		|| !xml_parse($xml_parser, '</body>', TRUE)){
			wfDebug(sprintf("XML error: %s at line %d",xml_error_string(xml_get_error_code($xml_parser)),xml_get_current_line_number($xml_parser)));
		}
		xml_parser_free($xml_parser);
		unset($from);

		$xml_parser = xml_parser_create('');

		$domto = new DomTreeBuilder();

		// Set the functions to handle opening and closing tags
		xml_set_element_handler($xml_parser, array($domto,"startElement"), array($domto,"endElement"));

		// Set the function to handle blocks of character data
		xml_set_character_data_handler($xml_parser, array($domto,"characters"));

		//wfDebug('Parsing '.strlen($to)." characters worth of HTML");
		if (!xml_parse($xml_parser, '<?xml version="1.0" encoding="UTF-8"?>'.Sanitizer::hackDocType().'<body>', FALSE)
		|| !xml_parse($xml_parser, $to, FALSE)
		|| !xml_parse($xml_parser, '</body>', TRUE)){
			wfDebug(sprintf("XML error in HTML diff: %s at line %d",xml_error_string(xml_get_error_code($xml_parser)),xml_get_current_line_number($xml_parser)));
		}
		xml_parser_free($xml_parser);
		unset($to);

		$diffengine = new _DiffEngine();
		$differences = $this->preProcess($diffengine->diff_range($domfrom->getDiffLines(), $domto->getDiffLines()));
		unset($xml_parser,$diffengine);

		$domdiffer = new TextNodeDiffer($domto, $domfrom);

		$currentIndexLeft = 0;
		$currentIndexRight = 0;
		foreach ($differences as $d) {
			if ($d->leftstart > $currentIndexLeft) {
				$domdiffer->handlePossibleChangedPart($currentIndexLeft, $d->leftstart,
				$currentIndexRight, $d->rightstart);
			}
			if ($d->leftlength > 0) {
				$domdiffer->markAsDeleted($d->leftstart, $d->leftend, $d->rightstart);
			}
			$domdiffer->markAsNew($d->rightstart, $d->rightend);

			$currentIndexLeft = $d->leftend;
			$currentIndexRight = $d->rightend;
		}
		if ($currentIndexLeft < $domdiffer->lengthOld()) {
			$domdiffer->handlePossibleChangedPart($currentIndexLeft,$domdiffer->lengthOld(), $currentIndexRight,$domdiffer->lengthNew());
		}

		$domdiffer->expandWhiteSpace();
		$output = new HTMLOutput('htmldiff', $this->output);
		$output->parse($domdiffer->getBodyNode());
	}

	private function preProcess(/*array*/ $differences){
		$newRanges = array();

		$nbDifferences = sizeof($differences);
		for ($i = 0; $i < $nbDifferences; ++$i) {
			$leftStart = $differences[$i]->leftstart;
			$leftEnd = $differences[$i]->leftend;
			$rightStart = $differences[$i]->rightstart;
			$rightEnd = $differences[$i]->rightend;

			$leftLength = $leftEnd - $leftStart;
			$rightLength = $rightEnd - $rightStart;

			while ($i + 1 < $nbDifferences && self::score($leftLength, $differences[$i + 1]->leftlength,
			$rightLength, $differences[$i + 1]->rightlength) > ($differences[$i + 1]->leftstart - $leftEnd)) {
				$leftEnd = $differences[$i + 1]->leftend;
				$rightEnd = $differences[$i + 1]->rightend;
				$leftLength = $leftEnd - $leftStart;
				$rightLength = $rightEnd - $rightStart;
				++$i;
			}
			$newRanges[] = new RangeDifference($leftStart, $leftEnd, $rightStart, $rightEnd);
		}
		return $newRanges;
	}

	/**
	 * Heuristic to merge differences for readability.
	 */
	public static function score($ll, $nll, $rl, $nrl) {
		if (($ll == 0 && $nll == 0)
		|| ($rl == 0 && $nrl == 0)){
			return 0;
		}
		$numbers = array($ll, $nll, $rl, $nrl);
		$d = 0;
		foreach ($numbers as $number) {
			while ($number > 3) {
				$d += 3;
				$number -= 3;
				$number *= 0.5;
			}
			$d += $number;

		}
		return $d / (1.5 * sizeof($numbers));
	}

}

class TextOnlyComparator{

	public $leafs = array();

	function _construct(TagNode $tree) {
		$this->addRecursive($tree);
		$this->leafs = array_map(array('TextNode','toDiffLine'), $this->leafs);
	}

	private function addRecursive(TagNode $tree) {
		foreach ($tree->children as $child) {
			if ($child instanceof TagNode) {
				$this->addRecursive($child);
			} else if ($child instanceof TextNode) {
				$this->leafs[] = $node;
			}
		}
	}

	public function getMatchRatio(TextOnlyComparator $other) {
		$nbOthers = sizeof($other->leafs);
		$nbThis = sizeof($this->leafs);
		if($nbOthers == 0 || $nbThis == 0){
			return -log(0);
		}
		
		$diffengine = new _DiffEngine();
		$diffengine->diff_local($this->leafs, $other->leafs);

		$distanceThis = array_sum($diffengine->xchanged);
		$distanceOther = array_sum($diffengine->ychanged);

		return ((0.0 + $distanceOther) / $nbOthers + (0.0 + $distanceThis)
		/ $nbThis) / 2.0;
	}
}

class AncestorComparatorResult {

	private $changed = false;

	private $changes = "";

	public function isChanged() {
		return $this->changed;
	}

	public function setChanged($changed) {
		$this->changed = $changed;
	}

	public function getChanges() {
		return $this->changes;
	}

	public function setChanges($changes) {
		$this->changes = $changes;
	}
}

/**
 * A comparator used when calculating the difference in ancestry of two Nodes.
 */
class AncestorComparator{

	public $ancestors;
	public $ancestorsText;

	function __construct(/*array*/ $ancestors) {
		$this->ancestors = $ancestors;
		$this->ancestorsText = array_map(array('TagNode','toDiffLine'), $ancestors);
	}

	private $compareTxt = "";

	public function getCompareTxt() {
		return $this->compareTxt;
	}

	public function getResult(AncestorComparator $other) {
		$result = new AncestorComparatorResult();

		$diffengine = new _DiffEngine();
		$differences = $diffengine->diff_range($this->ancestorsText, $other->ancestorsText);

		if (sizeof($differences) == 0){
			return $result;
		}
		$changeTxt = new ChangeTextGenerator($this, $other);

		$result->setChanged(true);
		$result->setChanges($changeTxt->getChanged($differences)->toString());

		return $result;
	}
}

class ChangeTextGenerator {

	private $new;
	private $old;

	private $factory;

	function __construct(AncestorComparator $old, AncestorComparator $new) {
		$this->new = $new;
		$this->old = $old;
		$this->factory = new TagToStringFactory();
	}

	public function getChanged(/*array*/ $differences) {
		$txt = new ChangeText;

		$rootlistopened = false;

		if (sizeof($differences) > 1) {
			$txt->addHtml('<ul class="changelist">');
			$rootlistopened = true;
		}

		$nbDifferences = sizeof($differences);
		for ($j = 0; $j < $nbDifferences; ++$j) {
			$d = $differences[$j];

			$lvl1listopened = false;

			if ($rootlistopened) {
				$txt->addHtml('<li>');
			}

			if ($d->leftlength + $d->rightlength > 1) {
				$txt->addHtml('<ul class="changelist">');
				$lvl1listopened = true;
			}

			// left are the old ones
			for ($i = $d->leftstart; $i < $d->leftend; ++$i) {
				if ($lvl1listopened){
					$txt->addHtml('<li>');
				}
				// add a bullet for a old tag
				$this->addTagOld($txt, $this->old->ancestors[$i]);

				if ($lvl1listopened){
					$txt->addHtml('</li>');
				}
			}

			// right are the new ones
			for ($i = $d->rightstart; $i < $d->rightend; ++$i) {
				if ($lvl1listopened){
					$txt->addHtml('<li>');
				}

				// add a bullet for a new tag
				$this->addTagNew($txt, $this->new->ancestors[$i]);

				if ($lvl1listopened){
					$txt->addHtml('</li>');
				}

			}

			if ($lvl1listopened) {
				$txt->addHtml('</ul>');
			}

			if ($rootlistopened) {
				$txt->addHtml('</li>');
			}
		}

		if ($rootlistopened) {
			$txt->addHtml('</ul>');
		}

		return $txt;

	}

	private function addTagOld(ChangeText $txt, TagNode $ancestor) {
		$this->factory->create($ancestor)->getRemovedDescription($txt);
	}

	private function addTagNew(ChangeText $txt, TagNode $ancestor) {
		$this->factory->create($ancestor)->getAddedDescription($txt);
	}
}

class ChangeText {

	private $txt = "";

	const newLine = "<br/>";

	public function addText($s) {
		$s = $this->clean($s);
		$this->txt .= $s;
	}

	public function addHtml($s) {
		$this->txt .= $s;
	}

	public function addNewLine() {
		$this->addHtml(self::newLine);
	}

	public function toString() {
		return $this->txt;
	}

	private function clean($s) {
		return htmlspecialchars($s);
	}
}

class TagToStringFactory {

	private static $containerTags = array(
        'html' => TRUE, 
        'body' => TRUE, 
        'p'  => TRUE, 
        'blockquote' => TRUE, 
        'h1' => TRUE, 
        'h2' => TRUE, 
        'h3' => TRUE, 
        'h4' => TRUE, 
        'h5' => TRUE, 
        'pre' => TRUE, 
        'div' => TRUE, 
        'ul' => TRUE, 
        'ol' => TRUE, 
        'li' => TRUE, 
        'table' => TRUE, 
        'tbody' => TRUE, 
        'tr' => TRUE, 
        'td' => TRUE, 
        'th' => TRUE, 
        'br' => TRUE, 
        'hr' => TRUE, 
        'code' => TRUE, 
        'dl' => TRUE, 
        'dt' => TRUE, 
        'dd' => TRUE, 
        'input' => TRUE, 
        'form' => TRUE, 
        'img' => TRUE,
	// in-line tags that can be considered containers not styles
        'span' => TRUE, 
        'a' => TRUE
	);

	private static $styleTags = array(
        'i' => TRUE, 
        'b' => TRUE, 
        'strong' => TRUE, 
        'em' => TRUE, 
        'font' => TRUE, 
        'big' => TRUE, 
        'del' => TRUE, 
        'tt' => TRUE, 
        'sub' => TRUE, 
        'sup' => TRUE, 
        'strike' => TRUE
	);

	const MOVED = 1;
	const STYLE = 2;
	const UNKNOWN = 4;

	public function create(TagNode $node) {
		$sem = $this->getChangeSemantic($node->getQName());
		if (0 == strcasecmp($node->getQName(),'a')){
			return new AnchorToString($node, $sem);
		}
		if (0 == strcasecmp($node->getQName(),'img')){
			return new NoContentTagToString($node, $sem);
		}
		return new TagToString($node, $sem);
	}

	protected function getChangeSemantic($qname) {
		if (array_key_exists(strtolower($qname),self::$containerTags)){
			return self::MOVED;
		}
		if (array_key_exists(strtolower($qname),self::$styleTags)){
			return self::STYLE;
		}
		return self::UNKNOWN;
	}
}

class TagToString {

	protected $node;

	protected $sem;

	function __construct(TagNode $node, $sem) {
		$this->node = $node;
		$this->sem = $sem;
	}

	public function getDescription() {
		return $this->getString('diff-' . $this->node->getQName());
	}

	public function getRemovedDescription(ChangeText $txt) {

		if ($this->sem == TagToStringFactory::MOVED) {
			$txt->addText($this->getMovedOutOf() . ' ' . strtolower($this->getArticle()) . ' ');
			$txt->addHtml('<b>');
			$txt->addText(strtolower($this->getDescription()));
			$txt->addHtml('</b>');
		} else if ($this->sem == TagToStringFactory::STYLE) {
			$txt->addHtml('<b>');
			$txt->addText($this->getDescription());
			$txt->addHtml('</b>');
			$txt->addText(' ' . strtolower($this->getStyleRemoved()));
		} else {
			$txt->addHtml('<b>');
			$txt->addText($this->getDescription());
			$txt->addHtml('</b>');
			$txt->addText(' ' . strtolower($this->getRemoved()));
		}
		$this->addAttributes($txt, $this->node->getAttributes());
		$txt->addText('.');
	}

	public function getAddedDescription(ChangeText $txt) {

		if ($this->sem == TagToStringFactory::MOVED) {
			$txt->addText($this->getMovedTo() . ' ' . strtolower($this->getArticle()) . ' ');
			$txt->addHtml('<b>');
			$txt->addText(strtolower($this->getDescription()));
			$txt->addHtml('</b>');
		} else if ($this->sem == TagToStringFactory::STYLE) {
			$txt->addHtml('<b>');
			$txt->addText($this->getDescription());
			$txt->addHtml('</b>');
			$txt->addText(' ' . strtolower($this->getStyleAdded()));
		} else {
			$txt->addHtml('<b>');
			$txt->addText($this->getDescription());
			$txt->addHtml('</b>');
			$txt->addText(' ' . strtolower($this->getAdded()));
		}
		$this->addAttributes($txt, $this->node->getAttributes());
		$txt->addText('.');
	}

	protected function getMovedTo() {
		return $this->getString('diff-movedto');
	}

	protected function getStyleAdded() {
		return $this->getString('diff-styleadded');
	}

	protected function getAdded() {
		return $this->getString('diff-added');
	}

	protected function getMovedOutOf() {
		return $this->getString('diff-movedoutof');
	}

	protected function getStyleRemoved() {
		return $this->getString('diff-styleremoved');
	}

	protected function getRemoved() {
		return $this->getString('diff-removed');
	}

	protected function addAttributes(ChangeText $txt, array $attributes) {
		if (sizeof($attributes) < 1)
		return;

		$keys = array_keys($attributes);

		$txt->addText(' ' . strtolower($this->getWith()) . ' '
		. $this->translateArgument($keys[0]) . ' '
		. $attributes[$keys[0]]);
		for ($i = 1; $i < sizeof($attributes) - 1; $i++) {
			$txt->addText(', ' . $this->translateArgument($keys[$i]) . ' '
			. $attributes[$keys[$i]]);
		}
		if (sizeof($attributes) > 1) {
			$txt->addText(' '
			. strtolower($this->getAnd())
			. ' '
			. $this->translateArgument($keys[sizeof($attributes) - 1]) . ' '
			. $attributes[$keys[sizeof($attributes) - 1]]);
		}
	}

	private function getAnd() {
		return $this->getString('diff-and');
	}

	private function getWith() {
		return $this->getString('diff-with');
	}

	protected function translateArgument($name) {
		if (0 == strcasecmp($name,'src'))
		return strtolower($this->getSource());
		if (0 == strcasecmp($name,'width'))
		return strtolower($this->getWidth());
		if (0 == strcasecmp($name,'height'))
		return strtolower($this->getHeight());
		return $name;
	}

	private function getHeight() {
		return $this->getString('diff-height');
	}

	private function getWidth() {
		return $this->getString('diff-width');
	}

	protected function getSource() {
		return $this->getString('diff-source');
	}

	protected function getArticle() {
		return $this->getString('diff-' . $this->node->getQName() . '-article');
	}

	public static $bundle = array(
    'diff-movedto' => 'Moved to',
	'diff-styleadded' => 'Style added',
	'diff-added' => 'Added',
	'diff-changedto' => 'Changed to',
	'diff-movedoutof' => 'Moved out of',
	'diff-styleremoved' => 'Style removed',
	'diff-removed' => 'Removed',
	'diff-changedfrom' => 'Changed from',
	'diff-source' => 'Source',
	'diff-withdestination' => 'With destination',
	'diff-and' => 'And',
	'diff-with' => 'With',
	'diff-width' => 'Width',
	'diff-height' => 'Height',
	'diff-html-article' => 'A',
	'diff-html' => 'Html page',
	'diff-body-article' => 'A',
	'diff-body' => 'Html document',
	'diff-p-article' => 'A',
	'diff-p' => 'Paragraph',
	'diff-blockquote-article' => 'A',
	'diff-blockquote' => 'Quote',
	'diff-h1-article' => 'A',
	'diff-h1' => 'Heading (level 1)',
	'diff-h2-article' => 'A',
	'diff-h2' => 'Heading (level 2)',
	'diff-h3-article' => 'A',
	'diff-h3' => 'Heading (level 3)',
	'diff-h4-article' => 'A',
	'diff-h4' => 'Heading (level 4)',
	'diff-h5-article' => 'A',
	'diff-h5' => 'Heading (level 5)',
	'diff-pre-article' => 'A',
	'diff-pre' => 'Preformatted block',
	'diff-div-article' => 'A',
	'diff-div' => 'Division',
	'diff-ul-article' => 'An',
	'diff-ul' => 'Unordered list',
	'diff-ol-article' => 'An',
	'diff-ol' => 'Ordered list',
	'diff-li-article' => 'A',
	'diff-li' => 'List item',
	'diff-table-article' => 'A',
	'diff-table' => 'Table',
	'diff-tbody-article' => 'A',
	'diff-tbody' => "Table's content",
	'diff-tr-article' => 'A',
	'diff-tr' => 'Row',
	'diff-td-article' => 'A',
	'diff-td' => 'Cell',
	'diff-th-article' => 'A',
	'diff-th' => 'Header',
	'diff-br-article' => 'A',
	'diff-br' => 'Break',
	'diff-hr-article' => 'A',
	'diff-hr' => 'Horizontal rule',
	'diff-code-article' => 'A',
	'diff-code' => 'Computer code block',
	'diff-dl-article' => 'A',
	'diff-dl' => 'Definition list',
	'diff-dt-article' => 'A',
	'diff-dt' => 'Definition term',
	'diff-dd-article' => 'A',
	'diff-dd' => 'Definition',
	'diff-input-article' => 'An',
	'diff-input' => 'Input',
	'diff-form-article' => 'A',
	'diff-form' => 'Form',
	'diff-img-article' => 'An',
	'diff-img' => 'Image',
	'diff-span-article' => 'A',
	'diff-span' => 'Span',
	'diff-a-article' => 'A',
	'diff-a' => 'Link',
	'diff-i' => 'Italics',
	'diff-b' => 'Bold',
	'diff-strong' => 'Strong',
	'diff-em' => 'Emphasis',
	'diff-font' => 'Font',
	'diff-big' => 'Big',
	'diff-del' => 'Deleted',
	'diff-tt' => 'Fixed width',
	'diff-sub' => 'Subscript',
	'diff-sup' => 'Superscript',
	'diff-strike' => 'Strikethrough'
	);

	public function getString($key) {
		return self::$bundle[$key];
	}
}

class NoContentTagToString extends TagToString {

	function __construct(TagNode $node, $sem) {
		parent::__construct($node, $sem);
	}

	public function getAddedDescription(ChangeText $txt) {
		$txt.addText($this->getChangedTo() . ' ' + strtolower($this->getArticle()) . ' ');
		$txt.addHtml('<b>');
		$txt.addText(strtolower($this->getDescription()));
		$txt.addHtml('</b>');

		$this->addAttributes($txt, $this->node->getAttributes());
		$txt.addText('.');
	}

	private function getChangedTo() {
		return $this->getString('diff-changedto');
	}

	public function getRemovedDescription(ChangeText $txt) {
		$txt.addText($this->getChangedFrom() . ' ' + strtolower($this->getArticle()) . ' ');
		$txt.addHtml('<b>');
		$txt.addText(strtolower($this->getDescription()));
		$txt.addHtml('</b>');

		$this->addAttributes($txt, $this->node->getAttributes());
		$txt.addText('.');
	}

	private function getChangedFrom() {
		return $this->getString('diff-changedfrom');
	}
}

class AnchorToString extends TagToString {

	function __construct(TagNode $node, $sem) {
		parent::__construct($node, $sem);
	}

	protected function addAttributes(ChangeText $txt, array $attributes) {
		if (array_key_exists('href',$attributes)) {
			$txt->addText(' ' . strtolower($this->getWithDestination()) . ' ' . $attributes['href']);
			unset($attributes['href']);
		}
		parent::addAttributes($txt, $attributes);
	}

	private function getWithDestination() {
		return $this->getString('diff-withdestination');
	}

}

/**
 * Takes a branch root and creates an HTML file for it.
 */
class HTMLOutput{

	private $prefix;
	private $handler;

	function __construct($prefix, $handler) {
		$this->prefix = $prefix;
		$this->handler = $handler;
	}

	public function parse(TagNode $node) {

		if (0 != strcasecmp($node->getQName(),'img') && 0 != strcasecmp($node->getQName(),'body')) {
			$this->handler->startElement($node->getQName(), $node->getAttributes());
		}

		$newStarted = false;
		$remStarted = false;
		$changeStarted = false;
		$changeTXT = '';

		foreach ($node->children as $child) {
			if ($child instanceof TagNode) {
				if ($newStarted) {
					$this->handler->endElement('span');
					$newStarted = false;
				} else if ($changeStarted) {
					$this->handler->endElement('span');
					$changeStarted = false;
				} else if ($remStarted) {
					$this->handler->endElement('span');
					$remStarted = false;
				}
				$this->parse($child);
			} else if ($child instanceof TextNode) {
				$mod = $child->getModification();

				if ($newStarted && ($mod->getType() != Modification::ADDED || $mod->isFirstOfID())) {
					$this->handler->endElement('span');
					$newStarted = false;
				} else if ($changeStarted && ($mod->getType() != Modification::CHANGED || $mod->getChanges() != $changeTXT || $mod->isFirstOfID())) {
					$this->handler->endElement('span');
					$changeStarted = false;
				} else if ($remStarted && ($mod->getType() != Modification::REMOVED || $mod ->isFirstOfID())) {
					$this->handler->endElement('span');
					$remStarted = false;
				}

				// no else because a removed part can just be closed and a new
				// part can start
				if (!$newStarted && $mod->getType() == Modification::ADDED) {
					$attrs = array('class'=>'diff-html-added');
					if ($mod->isFirstOfID()) {
						$attrs['id'] = 'added-' . $this->prefix . '-' . $mod->getID();
					}
					$this->addAttributes($mod, $attrs);
					$attrs['onclick'] = 'return tipA(constructToolTipA(this));';
					$this->handler->startElement('span', $attrs);
					$newStarted = true;
				} else if (!$changeStarted && $mod->getType() == Modification::CHANGED) {
					$attrs = array('class'=>'diff-html-changed');
					if ($mod->isFirstOfID()) {
						$attrs['id'] = 'changed-' . $this->prefix . '-' . $mod->getID();
					}
					$this->addAttributes($mod, $attrs);
					$attrs['onclick'] = 'return tipC(constructToolTipC(this));';
					$this->handler->startElement('span', $attrs);
					
					//tooltip
					$this->handler->startElement('span', array('class'=>'tip'));
					$this->handler->characters($mod->getChanges());
					$this->handler->endElement('span');
			
					$changeStarted = true;
					$changeTXT = $mod->getChanges();
				} else if (!$remStarted && $mod->getType() == Modification::REMOVED) {
					$attrs = array('class'=>'diff-html-removed');
					if ($mod->isFirstOfID()) {
						$attrs['id'] = 'removed-' . $this->prefix . '-' . $mod->getID();
					}
					$this->addAttributes($mod, $attrs);
					$attrs['onclick'] = 'return tipR(constructToolTipR(this));';
					$this->handler->startElement('span', $attrs);
					$remStarted = true;
				}

				$chars = $child->getText();

				if ($child instanceof ImageNode) {
					$this->writeImage($child);
				} else {
					$this->handler->characters($chars);
				}

			}
		}

		if ($newStarted) {
			$this->handler->endElement('span');
			$newStarted = false;
		} else if ($changeStarted) {
			$this->handler->endElement('span');
			$changeStarted = false;
		} else if ($remStarted) {
			$this->handler->endElement('span');
			$remStarted = false;
		}

		if (0 != strcasecmp($node->getQName(),'img')
		&& 0 != strcasecmp($node->getQName(),'body'))
		$this->handler->endElement($node->getQName());
	}

	private function writeImage(ImageNode  $imgNode){
		$attrs = $imgNode->getAttributes();
		if ($imgNode->getModification()->getType() == Modification::REMOVED)
		$attrs['changeType']='diff-removed-image';
		else if ($imgNode->getModification()->getType() == Modification::ADDED)
		$attrs['changeType'] = 'diff-added-image';
		$attrs['onload'] = 'updateOverlays()';
		$attrs['onError'] = 'updateOverlays()';
		$attrs['onAbort'] = 'updateOverlays()';
		$this->handler->startElement('img', $attrs);
		$this->handler->endElement('img');
	}

	private function addAttributes(Modification $mod, /*array*/ &$attrs) {
		if (is_null($mod->getPrevious())) {
			$previous = 'first-' . $this->prefix;
		} else {
			$previous = Modification::typeToString($mod->getPrevious()->getType()) . '-' . $this->prefix . '-'
			. $mod->getPrevious()->getID();
		}
		$attrs['previous'] = $previous;

		$changeId = Modification::typeToString($mod->getType()) . '-' + $this->prefix . '-' . $mod->getID();
		$attrs['changeId'] = $changeId;

		if (is_null($mod->getNext())) {
			$next = 'last-' . $this->prefix;
		} else {
			$next = Modification::typeToString($mod->getNext()->getType()) . '-' . $this->prefix . '-'
			. $mod->getNext()->getID();
		}
		$attrs['next'] = $next;
	}
}

class EchoingContentHandler{

	function startElement($qname, /*array*/ $arguments){
		echo '<'.$qname;
		foreach($arguments as $key => $value){
			echo ' '.$key.'="'.Sanitizer::encodeAttribute($value).'"';
		}
		echo '>';
	}

	function endElement($qname){
		echo '</'.$qname.'>';
	}

	function characters($chars){
		echo $chars;
	}

}

class DelegatingContentHandler{
	
	private $delegate;
	
	function __construct($delegate){
		$this->delegate=$delegate;
	}

	function startElement($qname, /*array*/ $arguments){
		$this->delegate->addHtml('<'.$qname) ;
		foreach($arguments as $key => $value){
			$this->delegate->addHtml(' '.$key.'="'. Sanitizer::encodeAttribute($value) .'"');
		}
		$this->delegate->addHtml('>');
	}

	function endElement($qname){
		$this->delegate->addHtml('</'.$qname.'>');
	}

	function characters($chars){
		$this->delegate->addHtml( $chars );
	}

}
