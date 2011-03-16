<?php
/**
 * Holder of replacement pairs for wiki links
 *
 * @file
 */

/**
 * @ingroup Parser
 */
class LinkHolderArray {
	var $internals = array(), $interwikis = array();
	var $size = 0;
	var $parent;
	protected $tempIdOffset;

	function __construct( $parent ) {
		$this->parent = $parent;
	}

	/**
	 * Reduce memory usage to reduce the impact of circular references
	 */
	function __destruct() {
		foreach ( $this as $name => $value ) {
			unset( $this->$name );
		}
	}

 	/**
	 * Don't serialize the parent object, it is big, and not needed when it is
	 * a parameter to mergeForeign(), which is the only application of 
	 * serializing at present.
	 *
	 * Compact the titles, only serialize the text form.
	 */
	function __sleep() {
		foreach ( $this->internals as $ns => &$nsLinks ) {
			foreach ( $nsLinks as $key => &$entry ) {
				unset( $entry['title'] );
			}
		}
		unset( $nsLinks );
		unset( $entry );

		foreach ( $this->interwikis as $key => &$entry ) {
			unset( $entry['title'] );
		}
		unset( $entry );

		return array( 'internals', 'interwikis', 'size' );
	}

	/**
	 * Recreate the Title objects
	 */
	function __wakeup() {
		foreach ( $this->internals as $ns => &$nsLinks ) {
			foreach ( $nsLinks as $key => &$entry ) {
				$entry['title'] = Title::newFromText( $entry['pdbk'] );
			}
		}
		unset( $nsLinks );
		unset( $entry );

		foreach ( $this->interwikis as $key => &$entry ) {
			$entry['title'] = Title::newFromText( $entry['pdbk'] );
		}
		unset( $entry );
	}

	/**
	 * Merge another LinkHolderArray into this one
	 * @param $other LinkHolderArray
	 */
	function merge( $other ) {
		foreach ( $other->internals as $ns => $entries ) {
			$this->size += count( $entries );
			if ( !isset( $this->internals[$ns] ) ) {
				$this->internals[$ns] = $entries;
			} else {
				$this->internals[$ns] += $entries;
			}
		}
		$this->interwikis += $other->interwikis;
	}

	/**
	 * Merge a LinkHolderArray from another parser instance into this one. The 
	 * keys will not be preserved. Any text which went with the old 
	 * LinkHolderArray and needs to work with the new one should be passed in 
	 * the $texts array. The strings in this array will have their link holders
	 * converted for use in the destination link holder. The resulting array of
	 * strings will be returned.
	 *
	 * @param $other LinkHolderArray
	 * @param $text Array of strings
	 * @return Array
	 */
	function mergeForeign( $other, $texts ) {
		$this->tempIdOffset = $idOffset = $this->parent->nextLinkID();
		$maxId = 0;

		# Renumber internal links
		foreach ( $other->internals as $ns => $nsLinks ) {
			foreach ( $nsLinks as $key => $entry ) {
				$newKey = $idOffset + $key;
				$this->internals[$ns][$newKey] = $entry;
				$maxId = $newKey > $maxId ? $newKey : $maxId;
			}
		}
		$texts = preg_replace_callback( '/(<!--LINK \d+:)(\d+)(-->)/', 
			array( $this, 'mergeForeignCallback' ), $texts );

		# Renumber interwiki links
		foreach ( $other->interwikis as $key => $entry ) {
			$newKey = $idOffset + $key;
			$this->interwikis[$newKey] = $entry;
			$maxId = $newKey > $maxId ? $newKey : $maxId;
		}
		$texts = preg_replace_callback( '/(<!--IWLINK )(\d+)(-->)/', 
			array( $this, 'mergeForeignCallback' ), $texts );

		# Set the parent link ID to be beyond the highest used ID
		$this->parent->setLinkID( $maxId + 1 );
		$this->tempIdOffset = null;
		return $texts;
	}

	protected function mergeForeignCallback( $m ) {
		return $m[1] . ( $m[2] + $this->tempIdOffset ) . $m[3];
	}

	/**
	 * Get a subset of the current LinkHolderArray which is sufficient to
	 * interpret the given text.
	 */
	function getSubArray( $text ) {
		$sub = new LinkHolderArray( $this->parent );

		# Internal links
		$pos = 0;
		while ( $pos < strlen( $text ) ) {
			if ( !preg_match( '/<!--LINK (\d+):(\d+)-->/', 
				$text, $m, PREG_OFFSET_CAPTURE, $pos ) ) 
			{
				break;
			}
			$ns = $m[1][0];
			$key = $m[2][0];
			$sub->internals[$ns][$key] = $this->internals[$ns][$key];
			$pos = $m[0][1] + strlen( $m[0][0] );
		}

		# Interwiki links
		$pos = 0;
		while ( $pos < strlen( $text ) ) {
			if ( !preg_match( '/<!--IWLINK (\d+)-->/', $text, $m, PREG_OFFSET_CAPTURE, $pos ) ) {
				break;
			}
			$key = $m[1][0];
			$sub->interwikis[$key] = $this->interwikis[$key];
			$pos = $m[0][1] + strlen( $m[0][0] );
		}
		return $sub;
	}

	/**
	 * Returns true if the memory requirements of this object are getting large
	 */
	function isBig() {
		global $wgLinkHolderBatchSize;
		return $this->size > $wgLinkHolderBatchSize;
	}

	/**
	 * Clear all stored link holders.
	 * Make sure you don't have any text left using these link holders, before you call this
	 */
	function clear() {
		$this->internals = array();
		$this->interwikis = array();
		$this->size = 0;
	}

	/**
	 * Make a link placeholder. The text returned can be later resolved to a real link with
	 * replaceLinkHolders(). This is done for two reasons: firstly to avoid further
	 * parsing of interwiki links, and secondly to allow all existence checks and
	 * article length checks (for stub links) to be bundled into a single query.
	 *
	 * @param $nt Title
	 */
	function makeHolder( $nt, $text = '', $query = array(), $trail = '', $prefix = ''  ) {
		wfProfileIn( __METHOD__ );
		if ( ! is_object($nt) ) {
			# Fail gracefully
			$retVal = "<!-- ERROR -->{$prefix}{$text}{$trail}";
		} else {
			# Separate the link trail from the rest of the link
			list( $inside, $trail ) = Linker::splitTrail( $trail );

			$entry = array(
				'title' => $nt,
				'text' => $prefix.$text.$inside,
				'pdbk' => $nt->getPrefixedDBkey(),
			);
			if ( $query !== array() ) {
				$entry['query'] = $query;
			}

			if ( $nt->isExternal() ) {
				// Use a globally unique ID to keep the objects mergable
				$key = $this->parent->nextLinkID();
				$this->interwikis[$key] = $entry;
				$retVal = "<!--IWLINK $key-->{$trail}";
			} else {
				$key = $this->parent->nextLinkID();
				$ns = $nt->getNamespace();
				$this->internals[$ns][$key] = $entry;
				$retVal = "<!--LINK $ns:$key-->{$trail}";
			}
			$this->size++;
		}
		wfProfileOut( __METHOD__ );
		return $retVal;
	}

	/**
	 * FIXME: update documentation. makeLinkObj() is deprecated.
	 * Replace <!--LINK--> link placeholders with actual links, in the buffer
	 * Placeholders created in Skin::makeLinkObj()
	 * Returns an array of link CSS classes, indexed by PDBK.
	 */
	function replace( &$text ) {
		wfProfileIn( __METHOD__ );

		$colours = $this->replaceInternal( $text );
		$this->replaceInterwiki( $text );

		wfProfileOut( __METHOD__ );
		return $colours;
	}

	/**
	 * Replace internal links
	 */
	protected function replaceInternal( &$text ) {
		if ( !$this->internals ) {
			return;
		}

		wfProfileIn( __METHOD__ );
		global $wgContLang;

		$colours = array();
		$sk = $this->parent->getOptions()->getSkin( $this->parent->mTitle );
		$linkCache = LinkCache::singleton();
		$output = $this->parent->getOutput();

		wfProfileIn( __METHOD__.'-check' );
		$dbr = wfGetDB( DB_SLAVE );
		$page = $dbr->tableName( 'page' );
		$threshold = $this->parent->getOptions()->getStubThreshold();

		# Sort by namespace
		ksort( $this->internals );

		$linkcolour_ids = array();

		# Generate query
		$query = false;
		$current = null;
		foreach ( $this->internals as $ns => $entries ) {
			foreach ( $entries as $entry ) {
				$title = $entry['title'];
				$pdbk = $entry['pdbk'];

				# Skip invalid entries.
				# Result will be ugly, but prevents crash.
				if ( is_null( $title ) ) {
					continue;
				}

				# Check if it's a static known link, e.g. interwiki
				if ( $title->isAlwaysKnown() ) {
					$colours[$pdbk] = '';
				} elseif ( $ns == NS_SPECIAL ) {
					$colours[$pdbk] = 'new';
				} elseif ( ( $id = $linkCache->getGoodLinkID( $pdbk ) ) != 0 ) {
					$colours[$pdbk] = $sk->getLinkColour( $title, $threshold );
					$output->addLink( $title, $id );
					$linkcolour_ids[$id] = $pdbk;
				} elseif ( $linkCache->isBadLink( $pdbk ) ) {
					$colours[$pdbk] = 'new';
				} else {
					# Not in the link cache, add it to the query
					if ( !isset( $current ) ) {
						$current = $ns;
						$query =  "SELECT page_id, page_namespace, page_title, page_is_redirect, page_len, page_latest";
						$query .= " FROM $page WHERE (page_namespace=$ns AND page_title IN(";
					} elseif ( $current != $ns ) {
						$current = $ns;
						$query .= ")) OR (page_namespace=$ns AND page_title IN(";
					} else {
						$query .= ', ';
					}

					$query .= $dbr->addQuotes( $title->getDBkey() );
				}
			}
		}
		if ( $query ) {
			$query .= '))';

			$res = $dbr->query( $query, __METHOD__ );

			# Fetch data and form into an associative array
			# non-existent = broken
			foreach ( $res as $s ) {
				$title = Title::makeTitle( $s->page_namespace, $s->page_title );
				$pdbk = $title->getPrefixedDBkey();
				$linkCache->addGoodLinkObj( $s->page_id, $title, $s->page_len, $s->page_is_redirect, $s->page_latest );
				$output->addLink( $title, $s->page_id );
				# FIXME: convoluted data flow
				# The redirect status and length is passed to getLinkColour via the LinkCache
				# Use formal parameters instead
				$colours[$pdbk] = $sk->getLinkColour( $title, $threshold );
				//add id to the extension todolist
				$linkcolour_ids[$s->page_id] = $pdbk;
			}
			unset( $res );
		}
		if ( count($linkcolour_ids) ) {
			//pass an array of page_ids to an extension
			wfRunHooks( 'GetLinkColours', array( $linkcolour_ids, &$colours ) );
		}
		wfProfileOut( __METHOD__.'-check' );

		# Do a second query for different language variants of links and categories
		if($wgContLang->hasVariants()) {
			$this->doVariants( $colours );
		}

		# Construct search and replace arrays
		wfProfileIn( __METHOD__.'-construct' );
		$replacePairs = array();
		foreach ( $this->internals as $ns => $entries ) {
			foreach ( $entries as $index => $entry ) {
				$pdbk = $entry['pdbk'];
				$title = $entry['title'];
				$query = isset( $entry['query'] ) ? $entry['query'] : array();
				$key = "$ns:$index";
				$searchkey = "<!--LINK $key-->";
				$displayText = $entry['text'];
				if ( $displayText === '' ) {
					$displayText = null;
				}
				if ( !isset( $colours[$pdbk] ) ) {
					$colours[$pdbk] = 'new';
				}
				$attribs = array();
				if ( $colours[$pdbk] == 'new' ) {
					$linkCache->addBadLinkObj( $title );
					$output->addLink( $title, 0 );
					$type = array( 'broken' );
				} else {
					if ( $colours[$pdbk] != '' ) {
						$attribs['class'] = $colours[$pdbk];
					}
					$type = array( 'known', 'noclasses' );
				}
				$replacePairs[$searchkey] = $sk->link( $title, $displayText,
						$attribs, $query, $type );
			}
		}
		$replacer = new HashtableReplacer( $replacePairs, 1 );
		wfProfileOut( __METHOD__.'-construct' );

		# Do the thing
		wfProfileIn( __METHOD__.'-replace' );
		$text = preg_replace_callback(
			'/(<!--LINK .*?-->)/',
			$replacer->cb(),
			$text);

		wfProfileOut( __METHOD__.'-replace' );
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Replace interwiki links
	 */
	protected function replaceInterwiki( &$text ) {
		if ( empty( $this->interwikis ) ) {
			return;
		}

		wfProfileIn( __METHOD__ );
		# Make interwiki link HTML
		$sk = $this->parent->getOptions()->getSkin( $this->parent->mTitle );
		$output = $this->parent->getOutput();
		$replacePairs = array();
		foreach( $this->interwikis as $key => $link ) {
			$replacePairs[$key] = $sk->link( $link['title'], $link['text'] );
			$output->addInterwikiLink( $link['title'] );
		}
		$replacer = new HashtableReplacer( $replacePairs, 1 );

		$text = preg_replace_callback(
			'/<!--IWLINK (.*?)-->/',
			$replacer->cb(),
			$text );
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Modify $this->internals and $colours according to language variant linking rules
	 */
	protected function doVariants( &$colours ) {
		global $wgContLang;
		$linkBatch = new LinkBatch();
		$variantMap = array(); // maps $pdbkey_Variant => $keys (of link holders)
		$output = $this->parent->getOutput();
		$linkCache = LinkCache::singleton();
		$sk = $this->parent->getOptions()->getSkin( $this->parent->mTitle );
		$threshold = $this->parent->getOptions()->getStubThreshold();
		$titlesToBeConverted = '';
		$titlesAttrs = array();

		// Concatenate titles to a single string, thus we only need auto convert the
		// single string to all variants. This would improve parser's performance
		// significantly.
		foreach ( $this->internals as $ns => $entries ) {
			foreach ( $entries as $index => $entry ) {
				$pdbk = $entry['pdbk'];
				// we only deal with new links (in its first query)
				if ( !isset( $colours[$pdbk] ) ) {
					$title = $entry['title'];
					$titleText = $title->getText();
					$titlesAttrs[] = array(
						'ns' => $ns,
						'key' => "$ns:$index",
						'titleText' => $titleText,
					);
					// separate titles with \0 because it would never appears
					// in a valid title
					$titlesToBeConverted .= $titleText . "\0";
				}
			}
		}

		// Now do the conversion and explode string to text of titles
		$titlesAllVariants = $wgContLang->autoConvertToAllVariants( $titlesToBeConverted );
		$allVariantsName = array_keys( $titlesAllVariants );
		foreach ( $titlesAllVariants as &$titlesVariant ) {
			$titlesVariant = explode( "\0", $titlesVariant );
		}
		$l = count( $titlesAttrs );
		// Then add variants of links to link batch
		for ( $i = 0; $i < $l; $i ++ ) {
			foreach ( $allVariantsName as $variantName ) {
				$textVariant = $titlesAllVariants[$variantName][$i];
				if ( $textVariant != $titlesAttrs[$i]['titleText'] ) {
					$variantTitle = Title::makeTitle( $titlesAttrs[$i]['ns'], $textVariant );
					if( is_null( $variantTitle ) ) {
						continue;
					}
					$linkBatch->addObj( $variantTitle );
					$variantMap[$variantTitle->getPrefixedDBkey()][] = $titlesAttrs[$i]['key'];
				}
			}
		}

		// process categories, check if a category exists in some variant
		$categoryMap = array(); // maps $category_variant => $category (dbkeys)
		$varCategories = array(); // category replacements oldDBkey => newDBkey
		foreach( $output->getCategoryLinks() as $category ){
			$variants = $wgContLang->autoConvertToAllVariants( $category );
			foreach($variants as $variant){
				if($variant != $category){
					$variantTitle = Title::newFromDBkey( Title::makeName(NS_CATEGORY,$variant) );
					if(is_null($variantTitle)) continue;
					$linkBatch->addObj( $variantTitle );
					$categoryMap[$variant] = $category;
				}
			}
		}


		if(!$linkBatch->isEmpty()){
			// construct query
			$dbr = wfGetDB( DB_SLAVE );
			$varRes = $dbr->select( 'page',
				array( 'page_id', 'page_namespace', 'page_title', 'page_is_redirect', 'page_len' ),
				$linkBatch->constructSet( 'page', $dbr ),
				__METHOD__
			);

			$linkcolour_ids = array();

			// for each found variants, figure out link holders and replace
			foreach ( $varRes as $s ) {

				$variantTitle = Title::makeTitle( $s->page_namespace, $s->page_title );
				$varPdbk = $variantTitle->getPrefixedDBkey();
				$vardbk = $variantTitle->getDBkey();

				$holderKeys = array();
				if( isset( $variantMap[$varPdbk] ) ) {
					$holderKeys = $variantMap[$varPdbk];
					$linkCache->addGoodLinkObj( $s->page_id, $variantTitle, $s->page_len, $s->page_is_redirect );
					$output->addLink( $variantTitle, $s->page_id );
				}

				// loop over link holders
				foreach( $holderKeys as $key ) {
					list( $ns, $index ) = explode( ':', $key, 2 );
					$entry =& $this->internals[$ns][$index];
					$pdbk = $entry['pdbk'];

					if(!isset($colours[$pdbk])){
						// found link in some of the variants, replace the link holder data
						$entry['title'] = $variantTitle;
						$entry['pdbk'] = $varPdbk;

						// set pdbk and colour
						# FIXME: convoluted data flow
						# The redirect status and length is passed to getLinkColour via the LinkCache
						# Use formal parameters instead
						$colours[$varPdbk] = $sk->getLinkColour( $variantTitle, $threshold );
						$linkcolour_ids[$s->page_id] = $pdbk;
					}
				}

				// check if the object is a variant of a category
				if(isset($categoryMap[$vardbk])){
					$oldkey = $categoryMap[$vardbk];
					if($oldkey != $vardbk)
						$varCategories[$oldkey]=$vardbk;
				}
			}
			wfRunHooks( 'GetLinkColours', array( $linkcolour_ids, &$colours ) );

			// rebuild the categories in original order (if there are replacements)
			if(count($varCategories)>0){
				$newCats = array();
				$originalCats = $output->getCategories();
				foreach($originalCats as $cat => $sortkey){
					// make the replacement
					if( array_key_exists($cat,$varCategories) )
						$newCats[$varCategories[$cat]] = $sortkey;
					else $newCats[$cat] = $sortkey;
				}
				$output->setCategoryLinks($newCats);
			}
		}
	}

	/**
	 * Replace <!--LINK--> link placeholders with plain text of links
	 * (not HTML-formatted).
	 *
	 * @param $text String
	 * @return String
	 */
	function replaceText( $text ) {
		wfProfileIn( __METHOD__ );

		$text = preg_replace_callback(
			'/<!--(LINK|IWLINK) (.*?)-->/',
			array( &$this, 'replaceTextCallback' ),
			$text );

		wfProfileOut( __METHOD__ );
		return $text;
	}

	/**
	 * Callback for replaceText()
	 *
	 * @param $matches Array
	 * @return string
	 * @private
	 */
	function replaceTextCallback( $matches ) {
		$type = $matches[1];
		$key  = $matches[2];
		if( $type == 'LINK' ) {
			list( $ns, $index ) = explode( ':', $key, 2 );
			if( isset( $this->internals[$ns][$index]['text'] ) ) {
				return $this->internals[$ns][$index]['text'];
			}
		} elseif( $type == 'IWLINK' ) {
			if( isset( $this->interwikis[$key]['text'] ) ) {
				return $this->interwikis[$key]['text'];
			}
		}
		return $matches[0];
	}
}
