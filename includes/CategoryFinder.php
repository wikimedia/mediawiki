<?php
/**
 * Recent changes filtering by category.
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
 */

use Wikimedia\Rdbms\IDatabase;

/**
 * The "CategoryFinder" class takes a list of articles, creates an internal
 * representation of all their parent categories (as well as parents of
 * parents etc.). From this representation, it determines which of these
 * articles are in one or all of a given subset of categories.
 *
 * Example use :
 * @code
 *     # Determines whether the article with the page_id 12345 is in both
 *     # "Category 1" and "Category 2" or their subcategories, respectively
 *
 *     $cf = new CategoryFinder;
 *     $cf->seed(
 *         [ 12345 ],
 *         [ 'Category 1', 'Category 2' ],
 *         'AND'
 *     );
 *     $a = $cf->run();
 *     print implode( ',' , $a );
 * @endcode
 *
 * @deprecated since 1.31
 */
class CategoryFinder {
	/** @var int[] The original article IDs passed to the seed function */
	protected $articles = [];

	/** @var array Array of DBKEY category names for categories that don't have a page */
	protected $deadend = [];

	/** @var array Array of [ ID => [] ] */
	protected $parents = [];

	/** @var array Array of article/category IDs */
	protected $next = [];

	/** @var int Max layer depth */
	protected $maxdepth = -1;

	/** @var array Array of DBKEY category names */
	protected $targets = [];

	/** @var array */
	protected $name2id = [];

	/** @var string "AND" or "OR" */
	protected $mode;

	/** @var IDatabase Read-DB replica DB */
	protected $dbr;

	/**
	 * Initializes the instance. Do this prior to calling run().
	 * @param array $articleIds Array of article IDs
	 * @param array $categories FIXME
	 * @param string $mode FIXME, default 'AND'.
	 * @param int $maxdepth Maximum layer depth. Where:
	 * 	-1 means deep recursion (default);
	 * 	 0 means no-parents;
	 * 	 1 means one parent layer, etc.
	 * @todo FIXME: $categories/$mode
	 */
	public function seed( $articleIds, $categories, $mode = 'AND', $maxdepth = -1 ) {
		$this->articles = $articleIds;
		$this->next = $articleIds;
		$this->mode = $mode;
		$this->maxdepth = $maxdepth;

		# Set the list of target categories; convert them to DBKEY form first
		$this->targets = [];
		foreach ( $categories as $c ) {
			$ct = Title::makeTitleSafe( NS_CATEGORY, $c );
			if ( $ct ) {
				$c = $ct->getDBkey();
				$this->targets[$c] = $c;
			}
		}
	}

	/**
	 * Iterates through the parent tree starting with the seed values,
	 * then checks the articles if they match the conditions
	 * @return array Array of page_ids (those given to seed() that match the conditions)
	 */
	public function run() {
		$this->dbr = wfGetDB( DB_REPLICA );

		$i = 0;
		$dig = true;
		while ( count( $this->next ) && $dig ) {
			$this->scanNextLayer();

			// Is there any depth limit?
			if ( $this->maxdepth !== -1 ) {
				$dig = $i < $this->maxdepth;
				$i++;
			}
		}

		# Now check if this applies to the individual articles
		$ret = [];

		foreach ( $this->articles as $article ) {
			$conds = $this->targets;
			if ( $this->check( $article, $conds ) ) {
				# Matches the conditions
				$ret[] = $article;
			}
		}
		return $ret;
	}

	/**
	 * Get the parents. Only really useful if run() has been called already
	 * @return array
	 */
	public function getParents() {
		return $this->parents;
	}

	/**
	 * This functions recurses through the parent representation, trying to match the conditions
	 * @param int $id The article/category to check
	 * @param array $conds The array of categories to match
	 * @param array $path Used to check for recursion loops
	 * @return bool Does this match the conditions?
	 */
	private function check( $id, &$conds, $path = [] ) {
		// Check for loops and stop!
		if ( in_array( $id, $path ) ) {
			return false;
		}

		$path[] = $id;

		# Shortcut (runtime paranoia): No conditions=all matched
		if ( count( $conds ) == 0 ) {
			return true;
		}

		if ( !isset( $this->parents[$id] ) ) {
			return false;
		}

		# iterate through the parents
		foreach ( $this->parents[$id] as $p ) {
			$pname = $p->cl_to;

			# Is this a condition?
			if ( isset( $conds[$pname] ) ) {
				# This key is in the category list!
				if ( $this->mode == 'OR' ) {
					# One found, that's enough!
					$conds = [];
					return true;
				} else {
					# Assuming "AND" as default
					unset( $conds[$pname] );
					if ( count( $conds ) == 0 ) {
						# All conditions met, done
						return true;
					}
				}
			}

			# Not done yet, try sub-parents
			if ( !isset( $this->name2id[$pname] ) ) {
				# No sub-parent
				continue;
			}
			$done = $this->check( $this->name2id[$pname], $conds, $path );
			if ( $done || count( $conds ) == 0 ) {
				# Subparents have done it!
				return true;
			}
		}
		return false;
	}

	/**
	 * Scans a "parent layer" of the articles/categories in $this->next
	 */
	private function scanNextLayer() {
		# Find all parents of the article currently in $this->next
		$layer = [];
		$res = $this->dbr->select(
			/* FROM   */ 'categorylinks',
			/* SELECT */ [ 'cl_to', 'cl_from' ],
			/* WHERE  */ [ 'cl_from' => $this->next ],
			__METHOD__ . '-1'
		);
		foreach ( $res as $row ) {
			$k = $row->cl_to;

			# Update parent tree
			if ( !isset( $this->parents[$row->cl_from] ) ) {
				$this->parents[$row->cl_from] = [];
			}
			$this->parents[$row->cl_from][$k] = $row;

			# Ignore those we already have
			if ( in_array( $k, $this->deadend ) ) {
				continue;
			}

			if ( isset( $this->name2id[$k] ) ) {
				continue;
			}

			# Hey, new category!
			$layer[$k] = $k;
		}

		$this->next = [];

		# Find the IDs of all category pages in $layer, if they exist
		if ( count( $layer ) > 0 ) {
			$res = $this->dbr->select(
				/* FROM   */ 'page',
				/* SELECT */ [ 'page_id', 'page_title' ],
				/* WHERE  */ [ 'page_namespace' => NS_CATEGORY, 'page_title' => $layer ],
				__METHOD__ . '-2'
			);
			foreach ( $res as $row ) {
				$id = $row->page_id;
				$name = $row->page_title;
				$this->name2id[$name] = $id;
				$this->next[] = $id;
				unset( $layer[$name] );
			}
		}

		# Mark dead ends
		foreach ( $layer as $v ) {
			$this->deadend[$v] = $v;
		}
	}
}
