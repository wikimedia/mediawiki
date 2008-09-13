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
 * @ingroup DifferenceEngine
 */

/**
 * When detecting the last common parent of two nodes, all results are stored as
 * a LastCommonParentResult.
 */
class LastCommonParentResult {

	// Parent
	public $parent;

	// Splitting
	public $splittingNeeded = false;

	// Depth
	public $lastCommonParentDepth = -1;

	// Index
	public $indexInLastCommonParent = -1;
}

class Modification{

	const NONE = 1;
	const REMOVED = 2;
	const ADDED = 4;
	const CHANGED = 8;

	public $type;

	public $id = -1;

	public $firstOfID = false;

	public $changes;

	function __construct($type) {
		$this->type = $type;
	}

	public static function typeToString($type) {
		switch($type) {
			case self::NONE: return 'none';
			case self::REMOVED: return 'removed';
			case self::ADDED: return 'added';
			case self::CHANGED: return 'changed';
		}
	}
}

class DomTreeBuilder {

	public $textNodes = array();

	public $bodyNode;

	private $currentParent;

	private $newWord = '';

	protected $bodyStarted = false;

	protected $bodyEnded = false;

	private $whiteSpaceBeforeThis = false;

	private $lastSibling;

	private $notInPre = true;

	function __construct() {
		$this->bodyNode = $this->currentParent = new BodyNode();
		$this->lastSibling = new DummyNode();
	}

	/**
	 * Must be called manually
	 */
	public function endDocument() {
		$this->endWord();
		HTMLDiffer::diffDebug( count($this->textNodes) . " text nodes in document.\n" );
	}

	public function startElement($parser, $name, /*array*/ $attributes) {
		if (strcasecmp($name, 'body') != 0) {
			HTMLDiffer::diffDebug( "Starting $name node.\n" );
			$this->endWord();

			$newNode = new TagNode($this->currentParent, $name, $attributes);
			$this->currentParent->children[] = $newNode;
			$this->currentParent = $newNode;
			$this->lastSibling = new DummyNode();
			if ($this->whiteSpaceBeforeThis && !in_array(strtolower($this->currentParent->qName),TagNode::$blocks)) {
				$this->currentParent->whiteBefore = true;
			}
			$this->whiteSpaceBeforeThis = false;
			if(strcasecmp($name, 'pre') == 0) {
				$this->notInPre = false;
			}
		}
	}

	public function endElement($parser, $name) {
		if(strcasecmp($name, 'body') != 0) {
			HTMLDiffer::diffDebug( "Ending $name node.\n");
			if (0 == strcasecmp($name,'img')) {
				// Insert a dummy leaf for the image
				$img = new ImageNode($this->currentParent, $this->currentParent->attributes);
				$this->currentParent->children[] = $img;
				$img->whiteBefore = $this->whiteSpaceBeforeThis;
				$this->lastSibling = $img;
				$this->textNodes[] = $img;
			}
			$this->endWord();
			if (!in_array(strtolower($this->currentParent->qName),TagNode::$blocks)) {
				$this->lastSibling = $this->currentParent;
			} else {
				$this->lastSibling = new DummyNode();
			}
			$this->currentParent = $this->currentParent->parent;
			$this->whiteSpaceBeforeThis = false;
			if (!$this->notInPre && strcasecmp($name, 'pre') == 0) {
				$this->notInPre = true;
			}
		} else {
			$this->endDocument();
		}
	}

	const regex = '/([\s\.\,\"\\\'\(\)\?\:\;\!\{\}\-\+\*\=\_\[\]\&\|\$]{1})/';
	const whitespace = '/^[\s]{1}$/';
	const delimiter = '/^[\s\.\,\"\\\'\(\)\?\:\;\!\{\}\-\+\*\=\_\[\]\&\|\$]{1}$/';

	public function characters($parser, $data) {
		$matches = preg_split(self::regex, $data, -1, PREG_SPLIT_DELIM_CAPTURE);

		foreach($matches as &$word) {
			if (preg_match(self::whitespace, $word) && $this->notInPre) {
				$this->endWord();
				$this->lastSibling->whiteAfter = true;
				$this->whiteSpaceBeforeThis = true;
			} else if (preg_match(self::delimiter, $word)) {
				$this->endWord();
				$textNode = new TextNode($this->currentParent, $word);
				$this->currentParent->children[] = $textNode;
				$textNode->whiteBefore = $this->whiteSpaceBeforeThis;
				$this->whiteSpaceBeforeThis = false;
				$this->lastSibling = $textNode;
				$this->textNodes[] = $textNode;
			} else {
				$this->newWord .= $word;
			}
		}
	}

	private function endWord() {
		if ($this->newWord !== '') {
			$node = new TextNode($this->currentParent, $this->newWord);
			$this->currentParent->children[] = $node;
			$node->whiteBefore = $this->whiteSpaceBeforeThis;
			$this->whiteSpaceBeforeThis = false;
			$this->lastSibling = $node;
			$this->textNodes[] = $node;
			$this->newWord = "";
		}
	}

	public function getDiffLines() {
		return array_map(array('TextNode','toDiffLine'), $this->textNodes);
	}
}

class TextNodeDiffer {

	private $textNodes;
	public $bodyNode;

	private $oldTextNodes;
	private $oldBodyNode;

	private $newID = 0;

	private $changedID = 0;

	private $changedIDUsed = false;

	// used to remove the whitespace between a red and green block
	private $whiteAfterLastChangedPart = false;

	private $deletedID = 0;

	function __construct(DomTreeBuilder $tree, DomTreeBuilder $oldTree) {
		$this->textNodes = $tree->textNodes;
		$this->bodyNode = $tree->bodyNode;
		$this->oldTextNodes = $oldTree->textNodes;
		$this->oldBodyNode = $oldTree->bodyNode;
	}

	public function markAsNew($start, $end) {
		if ($end <= $start) {
			return;
		}

		if ($this->whiteAfterLastChangedPart) {
			$this->textNodes[$start]->whiteBefore = false;
		}

		for ($i = $start; $i < $end; ++$i) {
			$mod = new Modification(Modification::ADDED);
			$mod->id = $this->newID;
			$this->textNodes[$i]->modification = $mod;
		}
		if ($start < $end) {
			$this->textNodes[$start]->modification->firstOfID = true;
		}
		++$this->newID;
	}

	public function handlePossibleChangedPart($leftstart, $leftend,	$rightstart, $rightend) {
		$i = $rightstart;
		$j = $leftstart;

		if ($this->changedIDUsed) {
			++$this->changedID;
			$this->changedIDUsed = false;
		}

		$changes;
		while ($i < $rightend) {
			$acthis = new AncestorComparator($this->textNodes[$i]->getParentTree());
			$acother = new AncestorComparator($this->oldTextNodes[$j]->getParentTree());
			$result = $acthis->getResult($acother);
			unset($acthis, $acother);

			if ($result->changed) {
				$mod = new Modification(Modification::CHANGED);

				if (!$this->changedIDUsed) {
					$mod->firstOfID = true;
				} else if (!is_null($result->changes) && $result->changes !== $this->changes) {
					++$this->changedID;
					$mod->firstOfID = true;
				}

				$mod->changes = $result->changes;
				$mod->id = $this->changedID;

				$this->textNodes[$i]->modification = $mod;
				$this->changes = $result->changes;
				$this->changedIDUsed = true;
			} else if ($this->changedIDUsed) {
				++$this->changedID;
				$this->changedIDUsed = false;
			}
			++$i;
			++$j;
		}
	}

	public function markAsDeleted($start, $end, $before) {

		if ($end <= $start) {
			return;
		}

		if ($before > 0 && $this->textNodes[$before - 1]->whiteAfter) {
			$this->whiteAfterLastChangedPart = true;
		} else {
			$this->whiteAfterLastChangedPart = false;
		}

		for ($i = $start; $i < $end; ++$i) {
			$mod = new Modification(Modification::REMOVED);
			$mod->id = $this->deletedID;

			// oldTextNodes is used here because we're going to move its deleted
			// elements to this tree!
			$this->oldTextNodes[$i]->modification = $mod;
		}
		$this->oldTextNodes[$start]->modification->firstOfID = true;

		$root = $this->oldTextNodes[$start]->getLastCommonParent($this->oldTextNodes[$end-1])->parent;

		$junk1 = $junk2 = null;
		$deletedNodes = $root->getMinimalDeletedSet($this->deletedID, $junk1, $junk2);

		HTMLDiffer::diffDebug( "Minimal set of deleted nodes of size " . count($deletedNodes) . "\n" );

		// Set prevLeaf to the leaf after which the old HTML needs to be
		// inserted
		if ($before > 0) {
			$prevLeaf = $this->textNodes[$before - 1];
		}
		// Set nextLeaf to the leaf before which the old HTML needs to be
		// inserted
		if ($before < count($this->textNodes)) {
			$nextLeaf = $this->textNodes[$before];
		}

		while (count($deletedNodes) > 0) {
			if (isset($prevLeaf)) {
				$prevResult = $prevLeaf->getLastCommonParent($deletedNodes[0]);
			} else {
				$prevResult = new LastCommonParentResult();
				$prevResult->parent = $this->bodyNode;
				$prevResult->indexInLastCommonParent = 0;
			}
			if (isset($nextleaf)) {
				$nextResult = $nextLeaf->getLastCommonParent($deletedNodes[count($deletedNodes) - 1]);
			} else {
				$nextResult = new LastCommonParentResult();
				$nextResult->parent = $this->bodyNode;
				$nextResult->indexInLastCommonParent = $this->bodyNode->getNbChildren();
			}

			if ($prevResult->lastCommonParentDepth == $nextResult->lastCommonParentDepth) {
				// We need some metric to choose which way to add-...
				if ($deletedNodes[0]->parent === $deletedNodes[count($deletedNodes) - 1]->parent
						&& $prevResult->parent === $nextResult->parent) {
					// The difference is not in the parent
					$prevResult->lastCommonParentDepth = $prevResult->lastCommonParentDepth + 1;
				} else {
					// The difference is in the parent, so compare them
					// now THIS is tricky
					$distancePrev = $deletedNodes[0]->parent->getMatchRatio($prevResult->parent);
					$distanceNext = $deletedNodes[count($deletedNodes) - 1]->parent->getMatchRatio($nextResult->parent);

					if ($distancePrev <= $distanceNext) {
						$prevResult->lastCommonParentDepth = $prevResult->lastCommonParentDepth + 1;
					} else {
						$nextResult->lastCommonParentDepth = $nextResult->lastCommonParentDepth + 1;
					}
				}

			}

			if ($prevResult->lastCommonParentDepth > $nextResult->lastCommonParentDepth) {
				// Inserting at the front
				if ($prevResult->splittingNeeded) {
					$prevLeaf->parent->splitUntil($prevResult->parent, $prevLeaf, true);
				}
				$prevLeaf = $deletedNodes[0]->copyTree();
				unset($deletedNodes[0]);
				$deletedNodes = array_values($deletedNodes);
				$prevLeaf->setParent($prevResult->parent);
				$prevResult->parent->addChildAbsolute($prevLeaf,$prevResult->indexInLastCommonParent + 1);
			} else if ($prevResult->lastCommonParentDepth < $nextResult->lastCommonParentDepth) {
				// Inserting at the back
				if ($nextResult->splittingNeeded) {
					$splitOccured = $nextLeaf->parent->splitUntil($nextResult->parent, $nextLeaf, false);
					if ($splitOccured) {
						// The place where to insert is shifted one place to the
						// right
						$nextResult->indexInLastCommonParent = $nextResult->indexInLastCommonParent + 1;
					}
				}
				$nextLeaf = $deletedNodes[count(deletedNodes) - 1]->copyTree();
				unset($deletedNodes[count(deletedNodes) - 1]);
				$deletedNodes = array_values($deletedNodes);
				$nextLeaf->setParent($nextResult->parent);
				$nextResult->parent->addChildAbsolute($nextLeaf,$nextResult->indexInLastCommonParent);
			}
		}
		++$this->deletedID;
	}

	public function expandWhiteSpace() {
		$this->bodyNode->expandWhiteSpace();
	}

	public function lengthNew(){
		return count($this->textNodes);
	}

	public function lengthOld(){
		return count($this->oldTextNodes);
	}
}

class HTMLDiffer {

	private $output;
	private static $debug = '';

	function __construct($output) {
		$this->output = $output;
	}

	function htmlDiff($from, $to) {
		wfProfileIn( __METHOD__ );
		// Create an XML parser
		$xml_parser = xml_parser_create('');

		$domfrom = new DomTreeBuilder();

		// Set the functions to handle opening and closing tags
		xml_set_element_handler($xml_parser, array($domfrom, "startElement"), array($domfrom, "endElement"));

		// Set the function to handle blocks of character data
		xml_set_character_data_handler($xml_parser, array($domfrom, "characters"));

		HTMLDiffer::diffDebug( "Parsing " . strlen($from) . " characters worth of HTML\n" );
		if (!xml_parse($xml_parser, '<?xml version="1.0" encoding="UTF-8"?>'.Sanitizer::hackDocType().'<body>', false)
					|| !xml_parse($xml_parser, $from, false)
					|| !xml_parse($xml_parser, '</body>', true)){
			$error = xml_error_string(xml_get_error_code($xml_parser));
			$line = xml_get_current_line_number($xml_parser);
			HTMLDiffer::diffDebug( "XML error: $error at line $line\n" );
		}
		xml_parser_free($xml_parser);
		unset($from);

		$xml_parser = xml_parser_create('');

		$domto = new DomTreeBuilder();

		// Set the functions to handle opening and closing tags
		xml_set_element_handler($xml_parser, array($domto, "startElement"), array($domto, "endElement"));

		// Set the function to handle blocks of character data
		xml_set_character_data_handler($xml_parser, array($domto, "characters"));

		HTMLDiffer::diffDebug( "Parsing " . strlen($to) . " characters worth of HTML\n" );
		if (!xml_parse($xml_parser, '<?xml version="1.0" encoding="UTF-8"?>'.Sanitizer::hackDocType().'<body>', false)
		|| !xml_parse($xml_parser, $to, false)
		|| !xml_parse($xml_parser, '</body>', true)){
			$error = xml_error_string(xml_get_error_code($xml_parser));
			$line = xml_get_current_line_number($xml_parser);
			HTMLDiffer::diffDebug( "XML error: $error at line $line\n" );
		}
		xml_parser_free($xml_parser);
		unset($to);

		$diffengine = new WikiDiff3();
		$differences = $this->preProcess($diffengine->diff_range($domfrom->getDiffLines(), $domto->getDiffLines()));
		unset($xml_parser, $diffengine);

		$domdiffer = new TextNodeDiffer($domto, $domfrom);

		$currentIndexLeft = 0;
		$currentIndexRight = 0;
		foreach ($differences as &$d) {
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
		$oldLength = $domdiffer->lengthOld();
		if ($currentIndexLeft < $oldLength) {
			$domdiffer->handlePossibleChangedPart($currentIndexLeft, $oldLength, $currentIndexRight, $domdiffer->lengthNew());
		}
		$domdiffer->expandWhiteSpace();
		$output = new HTMLOutput('htmldiff', $this->output);
		$output->parse($domdiffer->bodyNode);
		wfProfileOut( __METHOD__ );
	}

	private function preProcess(/*array*/ $differences) {
		$newRanges = array();

		$nbDifferences = count($differences);
		for ($i = 0; $i < $nbDifferences; ++$i) {
			$leftStart = $differences[$i]->leftstart;
			$leftEnd = $differences[$i]->leftend;
			$rightStart = $differences[$i]->rightstart;
			$rightEnd = $differences[$i]->rightend;

			$leftLength = $leftEnd - $leftStart;
			$rightLength = $rightEnd - $rightStart;

			while ($i + 1 < $nbDifferences && self::score($leftLength,
						$differences[$i + 1]->leftlength,
						$rightLength,
						$differences[$i + 1]->rightlength)
					> ($differences[$i + 1]->leftstart - $leftEnd)) {
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
				|| ($rl == 0 && $nrl == 0)) {
			return 0;
		}
		$numbers = array($ll, $nll, $rl, $nrl);
		$d = 0;
		foreach ($numbers as &$number) {
			while ($number > 3) {
				$d += 3;
				$number -= 3;
				$number *= 0.5;
			}
			$d += $number;

		}
		return $d / (1.5 * count($numbers));
	}

	/**
	 * Add to debug output
	 * @param string $str Debug output
	 */
	public static function diffDebug( $str ) {
		self :: $debug .= $str;
	}
	
	/**
	 * Get debug output
	 * @return string
	 */
	public static function getDebugOutput() {
		return self :: $debug;
	}

}

class TextOnlyComparator {

	public $leafs = array();

	function _construct(TagNode $tree) {
		$this->addRecursive($tree);
		$this->leafs = array_map(array('TextNode','toDiffLine'), $this->leafs);
	}

	private function addRecursive(TagNode $tree) {
		foreach ($tree->children as &$child) {
			if ($child instanceof TagNode) {
				$this->addRecursive($child);
			} else if ($child instanceof TextNode) {
				$this->leafs[] = $node;
			}
		}
	}

	public function getMatchRatio(TextOnlyComparator $other) {
		$nbOthers = count($other->leafs);
		$nbThis = count($this->leafs);
		if($nbOthers == 0 || $nbThis == 0){
			return -log(0);
		}

		$diffengine = new WikiDiff3(25000, 1.35);
		$diffengine->diff($this->leafs, $other->leafs);

		$lcsLength = $diffengine->getLcsLength();

		$distanceThis = $nbThis-$lcsLength;

		return (2.0 - $lcsLength/$nbOthers - $lcsLength/$nbThis) / 2.0;
	}
}

class AncestorComparatorResult {

	public $changed = false;

	public $changes = "";
}

/**
 * A comparator used when calculating the difference in ancestry of two Nodes.
 */
class AncestorComparator {

	public $ancestors;
	public $ancestorsText;

	function __construct(/*array*/ $ancestors) {
		$this->ancestors = $ancestors;
		$this->ancestorsText = array_map(array('TagNode','toDiffLine'), $ancestors);
	}

	public $compareTxt = "";

	public function getResult(AncestorComparator $other) {
		$result = new AncestorComparatorResult();

		$diffengine = new WikiDiff3(10000, 1.35);
		$differences = $diffengine->diff_range($other->ancestorsText,$this->ancestorsText);

		if (count($differences) == 0){
			return $result;
		}
		$changeTxt = new ChangeTextGenerator($this, $other);

		$result->changed = true;
		$result->changes = $changeTxt->getChanged($differences)->toString();

		return $result;
	}
}

class ChangeTextGenerator {

	private $ancestorComparator;
	private $other;

	private $factory;

	function __construct(AncestorComparator $ancestorComparator, AncestorComparator $other) {
		$this->ancestorComparator = $ancestorComparator;
		$this->other = $other;
		$this->factory = new TagToStringFactory();
	}

	public function getChanged(/*array*/ $differences) {
		$txt = new ChangeText;
		$rootlistopened = false;
		if (count($differences) > 1) {
			$txt->addHtml('<ul class="changelist">');
			$rootlistopened = true;
		}
		$nbDifferences = count($differences);
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
				$this->addTagOld($txt, $this->other->ancestors[$i]);
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
				$this->addTagNew($txt, $this->ancestorComparator->ancestors[$i]);

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

	public function addHtml($s) {
		$this->txt .= $s;
	}

	public function toString() {
		return $this->txt;
	}
}

class TagToStringFactory {

	private static $containerTags = array('html', 'body', 'p', 'blockquote',
		'h1', 'h2', 'h3', 'h4', 'h5', 'pre', 'div', 'ul', 'ol', 'li',
		'table', 'tbody', 'tr', 'td', 'th', 'br', 'hr', 'code', 'dl',
		'dt', 'dd', 'input', 'form', 'img', 'span', 'a');

	private static $styleTags = array('i', 'b', 'strong', 'em', 'font',
		'big', 'del', 'tt', 'sub', 'sup', 'strike');

	const MOVED = 1;
	const STYLE = 2;
	const UNKNOWN = 4;

	public function create(TagNode $node) {
		$sem = $this->getChangeSemantic($node->qName);
		if (strcasecmp($node->qName,'a') == 0) {
			return new AnchorToString($node, $sem);
		}
		if (strcasecmp($node->qName,'img') == 0) {
			return new NoContentTagToString($node, $sem);
		}
		return new TagToString($node, $sem);
	}

	protected function getChangeSemantic($qname) {
		if (in_array(strtolower($qname),self::$containerTags)) {
			return self::MOVED;
		}
		if (in_array(strtolower($qname),self::$styleTags)) {
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

	public function getRemovedDescription(ChangeText $txt) {
		$tagDescription = wfMsgExt('diff-' . $this->node->qName, 'parseinline' );
		if( wfEmptyMsg( 'diff-' . $this->node->qName, $tagDescription ) ){
			$tagDescription = "&lt;" . $this->node->qName . "&gt;";
		}
		if ($this->sem == TagToStringFactory::MOVED) {
			$txt->addHtml( wfMsgExt( 'diff-movedoutof', 'parseinline', $tagDescription ) );
		} else if ($this->sem == TagToStringFactory::STYLE) {
			$txt->addHtml( wfMsgExt( 'diff-styleremoved' , 'parseinline', $tagDescription ) );
		} else {
			$txt->addHtml( wfMsgExt( 'diff-removed' , 'parseinline', $tagDescription ) );
		}
		$this->addAttributes($txt, $this->node->attributes);
		$txt->addHtml('.');
	}

	public function getAddedDescription(ChangeText $txt) {
		$tagDescription = wfMsgExt('diff-' . $this->node->qName, 'parseinline' );
		if( wfEmptyMsg( 'diff-' . $this->node->qName, $tagDescription ) ){
			$tagDescription = "&lt;" . $this->node->qName . "&gt;";
		}
		if ($this->sem == TagToStringFactory::MOVED) {
			$txt->addHtml( wfMsgExt( 'diff-movedto' , 'parseinline', $tagDescription) );
		} else if ($this->sem == TagToStringFactory::STYLE) {
			$txt->addHtml( wfMsgExt( 'diff-styleadded', 'parseinline', $tagDescription ) );
		} else {
			$txt->addHtml( wfMsgExt( 'diff-added', 'parseinline', $tagDescription ) );
		}
		$this->addAttributes($txt, $this->node->attributes);
		$txt->addHtml('.');
	}

	protected function addAttributes(ChangeText $txt, array $attributes) {
		if (count($attributes) < 1) {
			return;
		}
		$firstOne = true;
		$nbAttributes_min_1 = count($attributes)-1;
		$keys = array_keys($attributes);
		for ($i=0;$i<$nbAttributes_min_1;$i++) {
			$key = $keys[$i];
			$attr = $attributes[$key];
			if($firstOne) {
				$firstOne = false;
				$txt->addHtml( wfMsgExt('diff-with', 'escapenoentities', $this->translateArgument($key), htmlspecialchars($attr) ) );
				continue;
			}
			$txt->addHtml( wfMsgExt( 'comma-separator', 'escapenoentities' ) .
				wfMsgExt( 'diff-with-additional', 'escapenoentities',
				$this->translateArgument( $key ), htmlspecialchars( $attr ) )
			);
		}

		if ($nbAttributes_min_1 > 0) {
			$txt->addHtml( wfMsgExt( 'diff-with-final', 'escapenoentities',
			$this->translateArgument($keys[$nbAttributes_min_1]),
			htmlspecialchars($attributes[$keys[$nbAttributes_min_1]]) ) );
		}
	}

	protected function translateArgument($name) {
		$translation = wfMsgExt('diff-' . $name, 'parseinline' );
		if ( wfEmptyMsg( 'diff-' . $name, $translation ) ) {
			$translation = "&lt;" . $name . "&gt;";;
		}
		return htmlspecialchars( $translation );
	}
}

class NoContentTagToString extends TagToString {

	function __construct(TagNode $node, $sem) {
		parent::__construct($node, $sem);
	}

	public function getAddedDescription(ChangeText $txt) {
		$tagDescription = wfMsgExt('diff-' . $this->node->qName, 'parseinline' );
		if( wfEmptyMsg( 'diff-' . $this->node->qName, $tagDescription ) ){
			$tagDescription = "&lt;" . $this->node->qName . "&gt;";
		}
		$txt->addHtml( wfMsgExt('diff-changedto', 'parseinline', $tagDescription ) );
		$this->addAttributes($txt, $this->node->attributes);
		$txt->addHtml('.');
	}

	public function getRemovedDescription(ChangeText $txt) {
		$txt->addHtml( wfMsgExt('diff-changedfrom', 'parseinline', $tagDescription ) );
		$this->addAttributes($txt, $this->node->attributes);
		$txt->addHtml('.');
	}
}

class AnchorToString extends TagToString {

	function __construct(TagNode $node, $sem) {
		parent::__construct($node, $sem);
	}

	protected function addAttributes(ChangeText $txt, array $attributes) {
		if (array_key_exists('href', $attributes)) {
			$txt->addHtml(' ' . wfMsgExt( 'diff-withdestination', 'parseinline', htmlspecialchars($attributes['href']) ) );
			unset($attributes['href']);
		}
		parent::addAttributes($txt, $attributes);
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
		$handler = &$this->handler;

		if (strcasecmp($node->qName, 'img') != 0 && strcasecmp($node->qName, 'body') != 0) {
			$handler->startElement($node->qName, $node->attributes);
		}

		$newStarted = false;
		$remStarted = false;
		$changeStarted = false;
		$changeTXT = '';

		foreach ($node->children as &$child) {
			if ($child instanceof TagNode) {
				if ($newStarted) {
					$handler->endElement('span');
					$newStarted = false;
				} else if ($changeStarted) {
					$handler->endElement('span');
					$changeStarted = false;
				} else if ($remStarted) {
					$handler->endElement('span');
					$remStarted = false;
				}
				$this->parse($child);
			} else if ($child instanceof TextNode) {
				$mod = $child->modification;

				if ($newStarted && ($mod->type != Modification::ADDED || $mod->firstOfID)) {
					$handler->endElement('span');
					$newStarted = false;
				} else if ($changeStarted && ($mod->type != Modification::CHANGED
						|| $mod->changes != $changeTXT || $mod->firstOfID)) {
					$handler->endElement('span');
					$changeStarted = false;
				} else if ($remStarted && ($mod->type != Modification::REMOVED || $mod ->firstOfID)) {
					$handler->endElement('span');
					$remStarted = false;
				}

				// no else because a removed part can just be closed and a new
				// part can start
				if (!$newStarted && $mod->type == Modification::ADDED) {
					$attrs = array('class' => 'diff-html-added');
					if ($mod->firstOfID) {
						$attrs['id'] = "added-{$this->prefix}-{$mod->id}";
					}
					$handler->startElement('span', $attrs);
					$newStarted = true;
				} else if (!$changeStarted && $mod->type == Modification::CHANGED) {
					$attrs = array('class' => 'diff-html-changed');
					if ($mod->firstOfID) {
						$attrs['id'] = "changed-{$this->prefix}-{$mod->id}";
					}
					$handler->startElement('span', $attrs);

					//tooltip
					$handler->startElement('span', array('class' => 'tip'));
					$handler->html($mod->changes);
					$handler->endElement('span');

					$changeStarted = true;
					$changeTXT = $mod->changes;
				} else if (!$remStarted && $mod->type == Modification::REMOVED) {
					$attrs = array('class'=>'diff-html-removed');
					if ($mod->firstOfID) {
						$attrs['id'] = "removed-{$this->prefix}-{$mod->id}";
					}
					$handler->startElement('span', $attrs);
					$remStarted = true;
				}

				$chars = $child->text;

				if ($child instanceof ImageNode) {
					$this->writeImage($child);
				} else {
					$handler->characters($chars);
				}
			}
		}

		if ($newStarted) {
			$handler->endElement('span');
			$newStarted = false;
		} else if ($changeStarted) {
			$handler->endElement('span');
			$changeStarted = false;
		} else if ($remStarted) {
			$handler->endElement('span');
			$remStarted = false;
		}

		if (strcasecmp($node->qName, 'img') != 0
				&& strcasecmp($node->qName, 'body') != 0) {
			$handler->endElement($node->qName);
		}
	}

	private function writeImage(ImageNode  $imgNode) {
		$attrs = $imgNode->attributes;
		$this->handler->startElement('img', $attrs);
		$this->handler->endElement('img');
	}
}

class EchoingContentHandler {

	function startElement($qname, /*array*/ $arguments) {
		echo Xml::openElement($qname, $arguments);
	}

	function endElement($qname){
		echo Xml::closeElement($qname);
	}

	function characters($chars){
		echo htmlspecialchars($chars);
	}

	function html($html){
		echo $html;
	}

}

class DelegatingContentHandler {

	private $delegate;

	function __construct($delegate) {
		$this->delegate = $delegate;
	}

	function startElement($qname, /*array*/ $arguments) {
		$this->delegate->addHtml(Xml::openElement($qname, $arguments));
	}

	function endElement($qname){
		$this->delegate->addHtml(Xml::closeElement($qname));
	}

	function characters($chars){
		$this->delegate->addHtml(htmlspecialchars($chars));
	}

	function html($html){
		$this->delegate->addHtml($html);
	}
}
