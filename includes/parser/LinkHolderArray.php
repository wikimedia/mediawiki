<?php

class LinkHolderArray {
	var $batchSize = 1000;

	var $internals = array(), $interwikis = array();
	var $size = 0;
	var $parent;

	function __construct( $parent ) {
		$this->parent = $parent;
	}

	/**
	 * Merge another LinkHolderArray into this one
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
	 * Returns true if the memory requirements of this object are getting large
	 */
	function isBig() {
		return $this->size > $this->batchSize;
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
	 */
	function makeHolder( $nt, $text = '', $query = '', $trail = '', $prefix = ''  ) {
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
			if ( $query !== '' ) {
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
		global $wgUser, $wgContLang;

		$pdbks = array();
		$colours = array();
		$linkcolour_ids = array();
		$sk = $this->parent->getOptions()->getSkin();
		$linkCache = LinkCache::singleton();
		$output = $this->parent->getOutput();

		wfProfileIn( __METHOD__.'-check' );
		$dbr = wfGetDB( DB_SLAVE );
		$page = $dbr->tableName( 'page' );
		$threshold = $wgUser->getOption('stubthreshold');

		# Sort by namespace
		ksort( $this->internals );

		# Generate query
		$query = false;
		$current = null;
		foreach ( $this->internals as $ns => $entries ) {
			foreach ( $entries as $index => $entry ) {
				$key = 	"$ns:$index";
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
				} elseif ( ( $id = $linkCache->getGoodLinkID( $pdbk ) ) != 0 ) {
					$colours[$pdbk] = '';
					$output->addLink( $title, $id );
				} elseif ( $linkCache->isBadLink( $pdbk ) ) {
					$colours[$pdbk] = 'new';
				} elseif ( $title->getNamespace() == NS_SPECIAL && !SpecialPage::exists( $pdbk ) ) {
					$colours[$pdbk] = 'new';
				} else {
					# Not in the link cache, add it to the query
					if ( !isset( $current ) ) {
						$current = $ns;
						$query =  "SELECT page_id, page_namespace, page_title, page_is_redirect, page_len";
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
			while ( $s = $dbr->fetchObject($res) ) {
				$title = Title::makeTitle( $s->page_namespace, $s->page_title );
				$pdbk = $title->getPrefixedDBkey();
				$linkCache->addGoodLinkObj( $s->page_id, $title, $s->page_len, $s->page_is_redirect );
				$output->addLink( $title, $s->page_id );
				$colours[$pdbk] = $sk->getLinkColour( $title, $threshold );
				//add id to the extension todolist
				$linkcolour_ids[$s->page_id] = $pdbk;
			}
			unset( $res );
			//pass an array of page_ids to an extension
			wfRunHooks( 'GetLinkColours', array( $linkcolour_ids, &$colours ) );
		}
		wfProfileOut( __METHOD__.'-check' );

		# Do a second query for different language variants of links and categories
		if($wgContLang->hasVariants()){
			$linkBatch = new LinkBatch();
			$variantMap = array(); // maps $pdbkey_Variant => $keys (of link holders)
			$categoryMap = array(); // maps $category_variant => $category (dbkeys)
			$varCategories = array(); // category replacements oldDBkey => newDBkey

			$categories = $output->getCategoryLinks();

			// Add variants of links to link batch
			foreach ( $this->internals as $ns => $entries ) {
				foreach ( $entries as $index => $entry ) {
					$key = "$ns:$index";
					$pdbk = $entry['pdbk'];
					$title = $entry['title'];
					$titleText = $title->getText();

					// generate all variants of the link title text
					$allTextVariants = $wgContLang->convertLinkToAllVariants($titleText);

					// if link was not found (in first query), add all variants to query
					if ( !isset($colours[$pdbk]) ){
						foreach($allTextVariants as $textVariant){
							if($textVariant != $titleText){
								$variantTitle = Title::makeTitle( $ns, $textVariant );
								if(is_null($variantTitle)) continue;
								$linkBatch->addObj( $variantTitle );
								$variantMap[$variantTitle->getPrefixedDBkey()][] = $key;
							}
						}
					}
				}
			}

			// process categories, check if a category exists in some variant
			foreach( $categories as $category ){
				$variants = $wgContLang->convertLinkToAllVariants($category);
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
				$titleClause = $linkBatch->constructSet('page', $dbr);

				$variantQuery =  "SELECT page_id, page_namespace, page_title, page_is_redirect, page_len";

				$variantQuery .= " FROM $page WHERE $titleClause";

				$varRes = $dbr->query( $variantQuery, __METHOD__ );

				// for each found variants, figure out link holders and replace
				while ( $s = $dbr->fetchObject($varRes) ) {

					$variantTitle = Title::makeTitle( $s->page_namespace, $s->page_title );
					$varPdbk = $variantTitle->getPrefixedDBkey();
					$vardbk = $variantTitle->getDBkey();

					$holderKeys = array();
					if(isset($variantMap[$varPdbk])){
						$holderKeys = $variantMap[$varPdbk];
						$linkCache->addGoodLinkObj( $s->page_id, $variantTitle, $s->page_len, $s->page_is_redirect );
						$output->addLink( $variantTitle, $s->page_id );
					}

					// loop over link holders
					foreach($holderKeys as $key){
						list( $ns, $index ) = explode( ':', $key, 2 );
						$entry =& $this->internals[$ns][$index];
						$pdbk = $entry['pdbk'];

						if(!isset($colours[$pdbk])){
							// found link in some of the variants, replace the link holder data
							$entry['title'] = $variantTitle;
							$entry['pdbk'] = $varPdbk;

							// set pdbk and colour
							$colours[$varPdbk] = $sk->getLinkColour( $variantTitle, $threshold );
							$linkcolour_ids[$s->page_id] = $pdbk;
						}
						wfRunHooks( 'GetLinkColours', array( $linkcolour_ids, &$colours ) );
					}

					// check if the object is a variant of a category
					if(isset($categoryMap[$vardbk])){
						$oldkey = $categoryMap[$vardbk];
						if($oldkey != $vardbk)
							$varCategories[$oldkey]=$vardbk;
					}
				}

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
					$this->mOutput->parent->setCategoryLinks($newCats);
				}
			}
		}

		# Construct search and replace arrays
		wfProfileIn( __METHOD__.'-construct' );
		$replacePairs = array();
		foreach ( $this->internals as $ns => $entries ) {
			foreach ( $entries as $index => $entry ) {
				$pdbk = $entry['pdbk'];
				$title = $entry['title'];
				$query = isset( $entry['query'] ) ? $entry['query'] : '';
				$key = "$ns:$index";
				$searchkey = "<!--LINK $key-->";
				if ( !isset( $colours[$pdbk] ) || $colours[$pdbk] == 'new' ) {
					$linkCache->addBadLinkObj( $title );
					$colours[$pdbk] = 'new';
					$output->addLink( $title, 0 );
					$replacePairs[$searchkey] = $sk->makeBrokenLinkObj( $title,
									$entry['text'],
									$query );
				} else {
					$replacePairs[$searchkey] = $sk->makeColouredLinkObj( $title, $colours[$pdbk],
									$entry['text'],
									$query );
				}
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
		$sk = $this->parent->getOptions()->getSkin();
		$replacePairs = array();
		foreach( $this->interwikis as $key => $link ) {
			$replacePairs[$key] = $sk->link( $link['title'], $link['text'] );
		}
		$replacer = new HashtableReplacer( $replacePairs, 1 );

		$text = preg_replace_callback(
			'/<!--IWLINK (.*?)-->/',
			$replacer->cb(),
			$text );
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Replace <!--LINK--> link placeholders with plain text of links
	 * (not HTML-formatted).
	 * @param string $text
	 * @return string
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
	 * @param array $matches
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
