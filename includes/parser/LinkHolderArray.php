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

namespace MediaWiki\Parser;

use MediaWiki\Cache\LinkCache;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Language\ILanguageConverter;
use MediaWiki\Linker\Linker;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;
use Wikimedia\HtmlArmor\HtmlArmor;

/**
 * @internal for using in Parser only.
 *
 * @ingroup Parser
 */
class LinkHolderArray {
	/** @var array<int,array<int,array>> Indexed by numeric namespace and link ids, {@see Parser::nextLinkID} */
	private $internals = [];
	/** @var array<int,array> Indexed by numeric link id */
	private $interwikis = [];
	/** @var int */
	private $size = 0;
	/** @var Parser */
	private $parent;
	/** @var ILanguageConverter */
	private $languageConverter;
	/** @var HookRunner */
	private $hookRunner;

	/**
	 * @param Parser $parent
	 * @param ILanguageConverter $languageConverter
	 * @param HookContainer $hookContainer
	 */
	public function __construct( Parser $parent, ILanguageConverter $languageConverter,
		HookContainer $hookContainer
	) {
		$this->parent = $parent;
		$this->languageConverter = $languageConverter;
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	/**
	 * Reduce memory usage to reduce the impact of circular references
	 */
	public function __destruct() {
		// @phan-suppress-next-line PhanTypeSuspiciousNonTraversableForeach
		foreach ( $this as $name => $_ ) {
			unset( $this->$name );
		}
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
	 * Returns true if the memory requirements of this object are getting large
	 * @return bool
	 */
	public function isBig() {
		$linkHolderBatchSize = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::LinkHolderBatchSize );
		return $this->size > $linkHolderBatchSize;
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
	 * @param string $trail [optional]
	 * @param string $prefix [optional]
	 * @return string
	 */
	public function makeHolder( Title $nt, $text = '', $trail = '', $prefix = '' ) {
		# Separate the link trail from the rest of the link
		[ $inside, $trail ] = Linker::splitTrail( $trail );

		$key = $this->parent->nextLinkID();
		$entry = [
			'title' => $nt,
			'text' => $prefix . $text . $inside,
			'pdbk' => $nt->getPrefixedDBkey(),
		];

		$this->size++;
		if ( $nt->isExternal() ) {
			// Use a globally unique ID to keep the objects mergable
			$this->interwikis[$key] = $entry;
			return "<!--IWLINK'\" $key-->{$trail}";
		} else {
			$ns = $nt->getNamespace();
			$this->internals[$ns][$key] = $entry;
			return "<!--LINK'\" $ns:$key-->{$trail}";
		}
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

		$classes = [];
		$services = MediaWikiServices::getInstance();
		$linkCache = $services->getLinkCache();
		$output = $this->parent->getOutput();
		$linkRenderer = $this->parent->getLinkRenderer();

		$dbr = $services->getConnectionProvider()->getReplicaDatabase();

		# Sort by namespace
		ksort( $this->internals );

		$pagemap = [];

		# Generate query
		$linkBatchFactory = $services->getLinkBatchFactory();
		$lb = $linkBatchFactory->newLinkBatch();
		$lb->setCaller( __METHOD__ );

		foreach ( $this->internals as $ns => $entries ) {
			foreach ( $entries as [ 'title' => $title, 'pdbk' => $pdbk ] ) {
				/** @var Title $title */
				# Check if it's a static known link, e.g. interwiki
				if ( $title->isAlwaysKnown() ) {
					$classes[$pdbk] = '';
				} elseif ( $ns === NS_SPECIAL ) {
					$classes[$pdbk] = 'new';
				} else {
					$id = $linkCache->getGoodLinkID( $pdbk );
					if ( $id ) {
						$classes[$pdbk] = $linkRenderer->getLinkClasses( $title );
						$output->addLink( $title, $id );
						$pagemap[$id] = $pdbk;
					} elseif ( $linkCache->isBadLink( $pdbk ) ) {
						$classes[$pdbk] = 'new';
					} else {
						# Not in the link cache, add it to the query
						$lb->addObj( $title );
					}
				}
			}
		}
		if ( !$lb->isEmpty() ) {
			$res = $dbr->newSelectQueryBuilder()
				->select( LinkCache::getSelectFields() )
				->from( 'page' )
				->where( [ $lb->constructSet( 'page', $dbr ) ] )
				->caller( __METHOD__ )
				->fetchResultSet();

			# Fetch data and form into an associative array
			# non-existent = broken
			foreach ( $res as $s ) {
				$title = Title::makeTitle( $s->page_namespace, $s->page_title );
				$pdbk = $title->getPrefixedDBkey();
				$linkCache->addGoodLinkObjFromRow( $title, $s );
				$output->addLink( $title, $s->page_id );
				$classes[$pdbk] = $linkRenderer->getLinkClasses( $title );
				// add id to the extension todolist
				$pagemap[$s->page_id] = $pdbk;
			}
			unset( $res );
		}
		if ( $pagemap !== [] ) {
			// pass an array of page_ids to an extension
			$this->hookRunner->onGetLinkColours( $pagemap, $classes, $this->parent->getTitle() );
		}

		# Do a second query for different language variants of links and categories
		if ( $this->languageConverter->hasVariants() ) {
			$this->doVariants( $classes );
		}

		# Construct search and replace arrays
		$replacePairs = [];
		foreach ( $this->internals as $ns => $entries ) {
			foreach ( $entries as $index => $entry ) {
				$pdbk = $entry['pdbk'];
				$title = $entry['title'];
				$query = $entry['query'] ?? [];
				$searchkey = "$ns:$index";
				$displayTextHtml = $entry['text'];
				if ( isset( $entry['selflink'] ) ) {
					$replacePairs[$searchkey] = Linker::makeSelfLinkObj(
						$title, $displayTextHtml, $query, '', '',
						Sanitizer::escapeIdForLink( $title->getFragment() )
					);
					continue;
				}

				$displayText = $displayTextHtml === '' ? null : new HtmlArmor( $displayTextHtml );
				if ( !isset( $classes[$pdbk] ) ) {
					$classes[$pdbk] = 'new';
				}
				if ( $classes[$pdbk] === 'new' ) {
					$linkCache->addBadLinkObj( $title );
					$output->addLink( $title, 0 );
					$link = $linkRenderer->makeBrokenLink(
						$title, $displayText, [], $query
					);
				} else {
					$link = $linkRenderer->makePreloadedLink(
						$title, $displayText, $classes[$pdbk], [], $query
					);
				}

				$replacePairs[$searchkey] = $link;
			}
		}

		# Do the thing
		$text = preg_replace_callback(
			'/<!--LINK\'" (-?[\d:]+)-->/',
			static function ( array $matches ) use ( $replacePairs ) {
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
		if ( !$this->interwikis ) {
			return;
		}

		# Make interwiki link HTML
		$output = $this->parent->getOutput();
		$replacePairs = [];
		$linkRenderer = $this->parent->getLinkRenderer();
		foreach ( $this->interwikis as $key => [ 'title' => $title, 'text' => $linkText ] ) {
			$replacePairs[$key] = $linkRenderer->makeLink( $title, new HtmlArmor( $linkText ) );
			$output->addInterwikiLink( $title );
		}

		$text = preg_replace_callback(
			'/<!--IWLINK\'" (\d+)-->/',
			static function ( array $matches ) use ( $replacePairs ) {
				return $replacePairs[$matches[1]];
			},
			$text
		);
	}

	/**
	 * Modify $this->internals and $classes according to language variant linking rules
	 * @param string[] &$classes
	 */
	protected function doVariants( &$classes ) {
		$linkBatchFactory = MediaWikiServices::getInstance()->getLinkBatchFactory();
		$linkBatch = $linkBatchFactory->newLinkBatch();
		$variantMap = []; // maps $pdbkey_Variant => $keys (of link holders)
		$output = $this->parent->getOutput();
		$titlesToBeConverted = '';
		$titlesAttrs = [];

		// Concatenate titles to a single string, thus we only need auto convert the
		// single string to all variants. This would improve parser's performance
		// significantly.
		foreach ( $this->internals as $ns => $entries ) {
			if ( $ns === NS_SPECIAL ) {
				continue;
			}
			foreach ( $entries as $index => [ 'title' => $title, 'pdbk' => $pdbk ] ) {
				// we only deal with new links (in its first query)
				if ( !isset( $classes[$pdbk] ) || $classes[$pdbk] === 'new' ) {
					$titlesAttrs[] = [ $index, $title ];
					// separate titles with \0 because it would never appears
					// in a valid title
					$titlesToBeConverted .= $title->getText() . "\0";
				}
			}
		}

		// Now do the conversion and explode string to text of titles
		$titlesAllVariants = $this->languageConverter->
			autoConvertToAllVariants( rtrim( $titlesToBeConverted, "\0" ) );
		foreach ( $titlesAllVariants as &$titlesVariant ) {
			$titlesVariant = explode( "\0", $titlesVariant );
		}

		// Then add variants of links to link batch
		$parentTitle = $this->parent->getTitle();
		foreach ( $titlesAttrs as $i => [ $index, $title ] ) {
			/** @var Title $title */
			$ns = $title->getNamespace();
			$text = $title->getText();

			foreach ( $titlesAllVariants as $textVariants ) {
				$textVariant = $textVariants[$i];
				if ( $textVariant === $text ) {
					continue;
				}

				$variantTitle = Title::makeTitle( $ns, $textVariant );

				// Self-link checking for mixed/different variant titles. At this point, we
				// already know the exact title does not exist, so the link cannot be to a
				// variant of the current title that exists as a separate page.
				if ( $variantTitle->equals( $parentTitle ) ) {
					$this->internals[$ns][$index]['selflink'] = true;
					continue 2;
				}

				$linkBatch->addObj( $variantTitle );
				$variantMap[$variantTitle->getPrefixedDBkey()][] = "$ns:$index";
			}
		}

		// process categories, check if a category exists in some variant
		$categoryMap = []; // maps $category_variant => $category (dbkeys)
		foreach ( $output->getCategoryNames() as $category ) {
			$categoryTitle = Title::makeTitleSafe( NS_CATEGORY, $category );
			$linkBatch->addObj( $categoryTitle );
			$variants = $this->languageConverter->autoConvertToAllVariants( $category );
			foreach ( $variants as $variant ) {
				if ( $variant !== $category ) {
					$variantTitle = Title::makeTitleSafe( NS_CATEGORY, $variant );
					if ( $variantTitle ) {
						$linkBatch->addObj( $variantTitle );
						$categoryMap[$variant] = [ $category, $categoryTitle ];
					}
				}
			}
		}

		if ( $linkBatch->isEmpty() ) {
			return;
		}

		// construct query
		$dbr = MediaWikiServices::getInstance()->getConnectionProvider()->getReplicaDatabase();

		$varRes = $dbr->newSelectQueryBuilder()
			->select( LinkCache::getSelectFields() )
			->from( 'page' )
			->where( [ $linkBatch->constructSet( 'page', $dbr ) ] )
			->caller( __METHOD__ )
			->fetchResultSet();

		$pagemap = [];
		$varCategories = [];
		$linkCache = MediaWikiServices::getInstance()->getLinkCache();
		$linkRenderer = $this->parent->getLinkRenderer();

		// for each found variants, figure out link holders and replace
		foreach ( $varRes as $s ) {
			$variantTitle = Title::makeTitle( $s->page_namespace, $s->page_title );
			$varPdbk = $variantTitle->getPrefixedDBkey();

			if ( !isset( $variantMap[$varPdbk] ) ) {
				continue;
			}

			$linkCache->addGoodLinkObjFromRow( $variantTitle, $s );
			$output->addLink( $variantTitle, $s->page_id );

			// loop over link holders
			foreach ( $variantMap[$varPdbk] as $key ) {
				[ $ns, $index ] = explode( ':', $key, 2 );
				$entry =& $this->internals[(int)$ns][(int)$index];

				// The selflink we marked above might not have been the first
				// $textVariants so be sure to skip any entries that have
				// subsequently been marked.
				if ( isset( $entry['selflink'] ) ) {
					continue;
				}

				$pdbk = $entry['pdbk'];
				if ( !isset( $classes[$pdbk] ) || $classes[$pdbk] === 'new' ) {
					// found link in some of the variants, replace the link holder data
					$entry['title'] = $variantTitle;
					$entry['pdbk'] = $varPdbk;

					// set pdbk and colour if we haven't checked this title yet.
					if ( !isset( $classes[$varPdbk] ) ) {
						$classes[$varPdbk] = $linkRenderer->getLinkClasses( $variantTitle );
						$pagemap[$s->page_id] = $varPdbk;
					}
				}
			}

			// check if the object is a variant of a category
			$vardbk = $variantTitle->getDBkey();
			if ( isset( $categoryMap[$vardbk] ) ) {
				[ $oldkey, $oldtitle ] = $categoryMap[$vardbk];
				if ( !isset( $varCategories[$oldkey] ) && !$oldtitle->exists() ) {
					$varCategories[$oldkey] = $vardbk;
				}
			}
		}
		$this->hookRunner->onGetLinkColours( $pagemap, $classes, $this->parent->getTitle() );

		// rebuild the categories in original order (if there are replacements)
		if ( $varCategories !== [] ) {
			$newCats = [];
			foreach ( $output->getCategoryNames() as $cat ) {
				$sortkey = $output->getCategorySortKey( $cat );
				// make the replacement
				$newCats[$varCategories[$cat] ?? $cat] = $sortkey;
			}
			$output->setCategories( $newCats );
		}
	}

	/**
	 * Replace <!--LINK'" …--> and <!--IWLINK'" …--> link placeholders with plain text of links
	 * (not HTML-formatted).
	 *
	 * @param string $text
	 * @return string
	 */
	public function replaceText( $text ) {
		return preg_replace_callback(
			'/<!--(IW)?LINK\'" (-?[\d:]+)-->/',
			function ( $matches ) {
				[ $unchanged, $isInterwiki, $key ] = $matches;

				if ( !$isInterwiki ) {
					[ $ns, $index ] = explode( ':', $key, 2 );
					return $this->internals[(int)$ns][(int)$index]['text'] ?? $unchanged;
				} else {
					return $this->interwikis[$key]['text'] ?? $unchanged;
				}
			},
			$text
		);
	}
}

/** @deprecated class alias since 1.43 */
class_alias( LinkHolderArray::class, 'LinkHolderArray' );
