<?php
/**
 * Holder of replacement pairs for wiki links
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

use MediaWiki\MediaWikiServices;

/**
 * @ingroup Parser
 */
class LinkHolderArray {
	public $internals = [];
	public $interwikis = [];
	public $size = 0;

	/**
	 * @var Parser
	 */
	public $parent;
	protected $tempIdOffset;

	/**
	 * @param Parser $parent
	 */
	public function __construct( $parent ) {
		$this->parent = $parent;
	}

	/**
	 * Reduce memory usage to reduce the impact of circular references
	 */
	public function __destruct() {
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
	 * @return array
	 */
	public function __sleep() {
		foreach ( $this->internals as &$nsLinks ) {
			foreach ( $nsLinks as &$entry ) {
				unset( $entry['title'] );
			}
		}
		unset( $nsLinks );
		unset( $entry );

		foreach ( $this->interwikis as &$entry ) {
			unset( $entry['title'] );
		}
		unset( $entry );

		return [ 'internals', 'interwikis', 'size' ];
	}

	/**
	 * Recreate the Title objects
	 */
	public function __wakeup() {
		foreach ( $this->internals as &$nsLinks ) {
			foreach ( $nsLinks as &$entry ) {
				$entry['title'] = Title::newFromText( $entry['pdbk'] );
			}
		}
		unset( $nsLinks );
		unset( $entry );

		foreach ( $this->interwikis as &$entry ) {
			$entry['title'] = Title::newFromText( $entry['pdbk'] );
		}
		unset( $entry );
	}

	/**
	 * Merge another LinkHolderArray into this one
	 * @param LinkHolderArray $other
	 */
	public function merge( $other ) {
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
	 * @param LinkHolderArray $other
	 * @param array $texts Array of strings
	 * @return array
	 */
	public function mergeForeign( $other, $texts ) {
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
		$texts = preg_replace_callback( '/(<!--LINK\'" \d+:)(\d+)(-->)/',
			[ $this, 'mergeForeignCallback' ], $texts );

		# Renumber interwiki links
		foreach ( $other->interwikis as $key => $entry ) {
			$newKey = $idOffset + $key;
			$this->interwikis[$newKey] = $entry;
			$maxId = $newKey > $maxId ? $newKey : $maxId;
		}
		$texts = preg_replace_callback( '/(<!--IWLINK\'" )(\d+)(-->)/',
			[ $this, 'mergeForeignCallback' ], $texts );

		# Set the parent link ID to be beyond the highest used ID
		$this->parent->setLinkID( $maxId + 1 );
		$this->tempIdOffset = null;
		return $texts;
	}

	/**
	 * @param array $m
	 * @return string
	 */
	protected function mergeForeignCallback( $m ) {
		return $m[1] . ( $m[2] + $this->tempIdOffset ) . $m[3];
	}

	/**
	 * Get a subset of the current LinkHolderArray which is sufficient to
	 * interpret the given text.
	 * @param string $text
	 * @return LinkHolderArray
	 */
	public function getSubArray( $text ) {
		$sub = new LinkHolderArray( $this->parent );

		# Internal links
		$pos = 0;
		while ( $pos < strlen( $text ) ) {
			if ( !preg_match( '/<!--LINK\'" (\d+):(\d+)-->/',
				$text, $m, PREG_OFFSET_CAPTURE, $pos )
			) {
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
			if ( !preg_match( '/<!--IWLINK\'" (\d+)-->/', $text, $m, PREG_OFFSET_CAPTURE, $pos ) ) {
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
	 * @return bool
	 */
	public function isBig() {
		global $wgLinkHolderBatchSize;
		return $this->size > $wgLinkHolderBatchSize;
	}

	/**
	 * Clear all stored link holders.
	 * Make sure you don't have any text left using these link holders, before you call this
	 */
	public function clear() {
		$this->internals = [];
		$this->interwikis = [];
		$this->size = 0;
	}

	/**
	 * Make a link placeholder. The text returned can be later resolved to a real link with
	 * replaceLinkHolders(). This is done for two reasons: firstly to avoid further
	 * parsing of interwiki links, and secondly to allow all existence checks and
	 * article length checks (for stub links) to be bundled into a single query.
	 *
	 * @param Title $nt
	 * @param string $text
	 * @param array $query [optional]
	 * @param string $trail [optional]
	 * @param string $prefix [optional]
	 * @return string
	 */
	public function makeHolder( $nt, $text = '', $query = [], $trail = '', $prefix = '' ) {
		if ( !is_object( $nt ) ) {
			# Fail gracefully
			$retVal = "<!-- ERROR -->{$prefix}{$text}{$trail}";
		} else {
			# Separate the link trail from the rest of the link
			list( $inside, $trail ) = Linker::splitTrail( $trail );

			$entry = [
				'title' => $nt,
				'text' => $prefix . $text . $inside,
				'pdbk' => $nt->getPrefixedDBkey(),
			];
			if ( $query !== [] ) {
				$entry['query'] = $query;
			}

			if ( $nt->isExternal() ) {
				// Use a globally unique ID to keep the objects mergable
				$key = $this->parent->nextLinkID();
				$this->interwikis[$key] = $entry;
				$retVal = "<!--IWLINK'\" $key-->{$trail}";
			} else {
				$key = $this->parent->nextLinkID();
				$ns = $nt->getNamespace();
				$this->internals[$ns][$key] = $entry;
				$retVal = "<!--LINK'\" $ns:$key-->{$trail}";
			}
			$this->size++;
		}
		return $retVal;
	}

	/**
	 * Replace <!--LINK--> link placeholders with actual links, in the buffer
	 *
	 * @param string &$text
	 */
	public function replace( &$text ) {
		$this->replaceInternal( $text );
		$this->replaceInterwiki( $text );
	}

	/**
	 * Replace internal links
	 * @param string &$text
	 */
	protected function replaceInternal( &$text ) {
		if ( !$this->internals ) {
			return;
		}

		$colours = [];
		$linkCache = MediaWikiServices::getInstance()->getLinkCache();
		$output = $this->parent->getOutput();
		$linkRenderer = $this->parent->getLinkRenderer();

		$dbr = wfGetDB( DB_REPLICA );

		# Sort by namespace
		ksort( $this->internals );

		$linkcolour_ids = [];

		# Generate query
		$lb = new LinkBatch();
		$lb->setCaller( __METHOD__ );

		foreach ( $this->internals as $ns => $entries ) {
			foreach ( $entries as $entry ) {
				/** @var Title $title */
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
				} else {
					$id = $linkCache->getGoodLinkID( $pdbk );
					if ( $id != 0 ) {
						$colours[$pdbk] = $linkRenderer->getLinkClasses( $title );
						$output->addLink( $title, $id );
						$linkcolour_ids[$id] = $pdbk;
					} elseif ( $linkCache->isBadLink( $pdbk ) ) {
						$colours[$pdbk] = 'new';
					} else {
						# Not in the link cache, add it to the query
						$lb->addObj( $title );
					}
				}
			}
		}
		if ( !$lb->isEmpty() ) {
			$fields = array_merge(
				LinkCache::getSelectFields(),
				[ 'page_namespace', 'page_title' ]
			);

			$res = $dbr->select(
				'page',
				$fields,
				$lb->constructSet( 'page', $dbr ),
				__METHOD__
			);

			# Fetch data and form into an associative array
			# non-existent = broken
			foreach ( $res as $s ) {
				$title = Title::makeTitle( $s->page_namespace, $s->page_title );
				$pdbk = $title->getPrefixedDBkey();
				$linkCache->addGoodLinkObjFromRow( $title, $s );
				$output->addLink( $title, $s->page_id );
				$colours[$pdbk] = $linkRenderer->getLinkClasses( $title );
				// add id to the extension todolist
				$linkcolour_ids[$s->page_id] = $pdbk;
			}
			unset( $res );
		}
		if ( count( $linkcolour_ids ) ) {
			// pass an array of page_ids to an extension
			Hooks::run( 'GetLinkColours', [ $linkcolour_ids, &$colours, $this->parent->getTitle() ] );
		}

		# Do a second query for different language variants of links and categories
		if ( $this->parent->getContentLanguage()->hasVariants() ) {
			$this->doVariants( $colours );
		}

		# Construct search and replace arrays
		$replacePairs = [];
		foreach ( $this->internals as $ns => $entries ) {
			foreach ( $entries as $index => $entry ) {
				$pdbk = $entry['pdbk'];
				$title = $entry['title'];
				$query = $entry['query'] ?? [];
				$key = "$ns:$index";
				$searchkey = "<!--LINK'\" $key-->";
				$displayText = $entry['text'];
				if ( isset( $entry['selflink'] ) ) {
					$replacePairs[$searchkey] = Linker::makeSelfLinkObj( $title, $displayText, $query );
					continue;
				}
				if ( $displayText === '' ) {
					$displayText = null;
				} else {
					$displayText = new HtmlArmor( $displayText );
				}
				if ( !isset( $colours[$pdbk] ) ) {
					$colours[$pdbk] = 'new';
				}
				$attribs = [];
				if ( $colours[$pdbk] == 'new' ) {
					$linkCache->addBadLinkObj( $title );
					$output->addLink( $title, 0 );
					$link = $linkRenderer->makeBrokenLink(
						$title, $displayText, $attribs, $query
					);
				} else {
					$link = $linkRenderer->makePreloadedLink(
						$title, $displayText, $colours[$pdbk], $attribs, $query
					);
				}

				$replacePairs[$searchkey] = $link;
			}
		}

		# Do the thing
		$text = preg_replace_callback(
			'/(<!--LINK\'" .*?-->)/',
			function ( array $matches ) use ( $replacePairs ) {
				return $replacePairs[$matches[1]];
			},
			$text
		);
	}

	/**
	 * Replace interwiki links
	 * @param string &$text
	 */
	protected function replaceInterwiki( &$text ) {
		if ( empty( $this->interwikis ) ) {
			return;
		}

		# Make interwiki link HTML
		$output = $this->parent->getOutput();
		$replacePairs = [];
		$linkRenderer = $this->parent->getLinkRenderer();
		foreach ( $this->interwikis as $key => $link ) {
			$replacePairs[$key] = $linkRenderer->makeLink(
				$link['title'],
				new HtmlArmor( $link['text'] )
			);
			$output->addInterwikiLink( $link['title'] );
		}

		$text = preg_replace_callback(
			'/<!--IWLINK\'" (.*?)-->/',
			function ( array $matches ) use ( $replacePairs ) {
				return $replacePairs[$matches[1]];
			},
			$text
		);
	}

	/**
	 * Modify $this->internals and $colours according to language variant linking rules
	 * @param array &$colours
	 */
	protected function doVariants( &$colours ) {
		$linkBatch = new LinkBatch();
		$variantMap = []; // maps $pdbkey_Variant => $keys (of link holders)
		$output = $this->parent->getOutput();
		$linkCache = MediaWikiServices::getInstance()->getLinkCache();
		$titlesToBeConverted = '';
		$titlesAttrs = [];

		// Concatenate titles to a single string, thus we only need auto convert the
		// single string to all variants. This would improve parser's performance
		// significantly.
		foreach ( $this->internals as $ns => $entries ) {
			if ( $ns == NS_SPECIAL ) {
				continue;
			}
			foreach ( $entries as $index => $entry ) {
				$pdbk = $entry['pdbk'];
				// we only deal with new links (in its first query)
				if ( !isset( $colours[$pdbk] ) || $colours[$pdbk] === 'new' ) {
					$titlesAttrs[] = [ $index, $entry['title'] ];
					// separate titles with \0 because it would never appears
					// in a valid title
					$titlesToBeConverted .= $entry['title']->getText() . "\0";
				}
			}
		}

		// Now do the conversion and explode string to text of titles
		$titlesAllVariants = $this->parent->getContentLanguage()->
			autoConvertToAllVariants( rtrim( $titlesToBeConverted, "\0" ) );
		$allVariantsName = array_keys( $titlesAllVariants );
		foreach ( $titlesAllVariants as &$titlesVariant ) {
			$titlesVariant = explode( "\0", $titlesVariant );
		}

		// Then add variants of links to link batch
		$parentTitle = $this->parent->getTitle();
		foreach ( $titlesAttrs as $i => $attrs ) {
			/** @var Title $title */
			list( $index, $title ) = $attrs;
			$ns = $title->getNamespace();
			$text = $title->getText();

			foreach ( $allVariantsName as $variantName ) {
				$textVariant = $titlesAllVariants[$variantName][$i];
				if ( $textVariant === $text ) {
					continue;
				}

				$variantTitle = Title::makeTitle( $ns, $textVariant );

				// Self-link checking for mixed/different variant titles. At this point, we
				// already know the exact title does not exist, so the link cannot be to a
				// variant of the current title that exists as a separate page.
				if ( $variantTitle->equals( $parentTitle ) && !$title->hasFragment() ) {
					$this->internals[$ns][$index]['selflink'] = true;
					continue 2;
				}

				$linkBatch->addObj( $variantTitle );
				$variantMap[$variantTitle->getPrefixedDBkey()][] = "$ns:$index";
			}
		}

		// process categories, check if a category exists in some variant
		$categoryMap = []; // maps $category_variant => $category (dbkeys)
		$varCategories = []; // category replacements oldDBkey => newDBkey
		foreach ( $output->getCategoryLinks() as $category ) {
			$categoryTitle = Title::makeTitleSafe( NS_CATEGORY, $category );
			$linkBatch->addObj( $categoryTitle );
			$variants = $this->parent->getContentLanguage()->autoConvertToAllVariants( $category );
			foreach ( $variants as $variant ) {
				if ( $variant !== $category ) {
					$variantTitle = Title::makeTitleSafe( NS_CATEGORY, $variant );
					if ( is_null( $variantTitle ) ) {
						continue;
					}
					$linkBatch->addObj( $variantTitle );
					$categoryMap[$variant] = [ $category, $categoryTitle ];
				}
			}
		}

		if ( !$linkBatch->isEmpty() ) {
			// construct query
			$dbr = wfGetDB( DB_REPLICA );
			$fields = array_merge(
				LinkCache::getSelectFields(),
				[ 'page_namespace', 'page_title' ]
			);

			$varRes = $dbr->select( 'page',
				$fields,
				$linkBatch->constructSet( 'page', $dbr ),
				__METHOD__
			);

			$linkcolour_ids = [];
			$linkRenderer = $this->parent->getLinkRenderer();

			// for each found variants, figure out link holders and replace
			foreach ( $varRes as $s ) {
				$variantTitle = Title::makeTitle( $s->page_namespace, $s->page_title );
				$varPdbk = $variantTitle->getPrefixedDBkey();
				$vardbk = $variantTitle->getDBkey();

				$holderKeys = [];
				if ( isset( $variantMap[$varPdbk] ) ) {
					$holderKeys = $variantMap[$varPdbk];
					$linkCache->addGoodLinkObjFromRow( $variantTitle, $s );
					$output->addLink( $variantTitle, $s->page_id );
				}

				// loop over link holders
				foreach ( $holderKeys as $key ) {
					list( $ns, $index ) = explode( ':', $key, 2 );
					$entry =& $this->internals[$ns][$index];
					$pdbk = $entry['pdbk'];

					if ( !isset( $colours[$pdbk] ) || $colours[$pdbk] === 'new' ) {
						// found link in some of the variants, replace the link holder data
						$entry['title'] = $variantTitle;
						$entry['pdbk'] = $varPdbk;

						// set pdbk and colour
						$colours[$varPdbk] = $linkRenderer->getLinkClasses( $variantTitle );
						$linkcolour_ids[$s->page_id] = $pdbk;
					}
				}

				// check if the object is a variant of a category
				if ( isset( $categoryMap[$vardbk] ) ) {
					list( $oldkey, $oldtitle ) = $categoryMap[$vardbk];
					if ( !isset( $varCategories[$oldkey] ) && !$oldtitle->exists() ) {
						$varCategories[$oldkey] = $vardbk;
					}
				}
			}
			Hooks::run( 'GetLinkColours', [ $linkcolour_ids, &$colours, $this->parent->getTitle() ] );

			// rebuild the categories in original order (if there are replacements)
			if ( count( $varCategories ) > 0 ) {
				$newCats = [];
				$originalCats = $output->getCategories();
				foreach ( $originalCats as $cat => $sortkey ) {
					// make the replacement
					if ( array_key_exists( $cat, $varCategories ) ) {
						$newCats[$varCategories[$cat]] = $sortkey;
					} else {
						$newCats[$cat] = $sortkey;
					}
				}
				$output->setCategoryLinks( $newCats );
			}
		}
	}

	/**
	 * Replace <!--LINK--> link placeholders with plain text of links
	 * (not HTML-formatted).
	 *
	 * @param string $text
	 * @return string
	 */
	public function replaceText( $text ) {
		$text = preg_replace_callback(
			'/<!--(LINK|IWLINK)\'" (.*?)-->/',
			[ $this, 'replaceTextCallback' ],
			$text );

		return $text;
	}

	/**
	 * Callback for replaceText()
	 *
	 * @param array $matches
	 * @return string
	 * @private
	 */
	public function replaceTextCallback( $matches ) {
		$type = $matches[1];
		$key = $matches[2];
		if ( $type == 'LINK' ) {
			list( $ns, $index ) = explode( ':', $key, 2 );
			if ( isset( $this->internals[$ns][$index]['text'] ) ) {
				return $this->internals[$ns][$index]['text'];
			}
		} elseif ( $type == 'IWLINK' ) {
			if ( isset( $this->interwikis[$key]['text'] ) ) {
				return $this->interwikis[$key]['text'];
			}
		}
		return $matches[0];
	}
}
