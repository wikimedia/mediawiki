<?php
/**
 * Updater for link tracking tables after a page edit.
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
/**
 * Update object handling the cleanup of links tables after a page was deleted.
 **/
class LinksDeletionUpdate extends SqlDataUpdate implements EnqueueableDataUpdate {
	/** @var WikiPage */
	protected $page;
	/** @var integer */
	protected $pageId;

	/**
	 * @param WikiPage $page Page we are updating
	 * @param integer|null $pageId ID of the page we are updating [optional]
	 * @throws MWException
	 */
	function __construct( WikiPage $page, $pageId = null ) {
		parent::__construct( false ); // no implicit transaction

		$this->page = $page;
		if ( $page->exists() ) {
			$this->pageId = $page->getId();
		} elseif ( $pageId ) {
			$this->pageId = $pageId;
		} else {
			throw new MWException( "Page ID not known, perhaps the page doesn't exist?" );
		}
	}

	public function doUpdate() {
		# Page may already be deleted, so don't just getId()
		$id = $this->pageId;

		# Delete restrictions for it
		$this->mDb->delete( 'page_restrictions', [ 'pr_page' => $id ], __METHOD__ );

		# Fix category table counts
		$cats = $this->mDb->selectFieldValues(
			'categorylinks',
			'cl_to',
			[ 'cl_from' => $id ],
			__METHOD__
		);
		$this->page->updateCategoryCounts( [], $cats );

		# If using cascading deletes, we can skip some explicit deletes
		if ( !$this->mDb->cascadingDeletes() ) {
			# Delete outgoing links
			$this->mDb->delete( 'pagelinks', [ 'pl_from' => $id ], __METHOD__ );
			$this->mDb->delete( 'imagelinks', [ 'il_from' => $id ], __METHOD__ );
			$this->mDb->delete( 'categorylinks', [ 'cl_from' => $id ], __METHOD__ );
			$this->mDb->delete( 'templatelinks', [ 'tl_from' => $id ], __METHOD__ );
			$this->mDb->delete( 'externallinks', [ 'el_from' => $id ], __METHOD__ );
			$this->mDb->delete( 'langlinks', [ 'll_from' => $id ], __METHOD__ );
			$this->mDb->delete( 'iwlinks', [ 'iwl_from' => $id ], __METHOD__ );
			$this->mDb->delete( 'redirect', [ 'rd_from' => $id ], __METHOD__ );
			$this->mDb->delete( 'page_props', [ 'pp_page' => $id ], __METHOD__ );
		}

		# If using cleanup triggers, we can skip some manual deletes
		if ( !$this->mDb->cleanupTriggers() ) {
			$title = $this->page->getTitle();
			# Find recentchanges entries to clean up...
			$rcIdsForTitle = $this->mDb->selectFieldValues( 'recentchanges',
				'rc_id',
				[
					'rc_type != ' . RC_LOG,
					'rc_namespace' => $title->getNamespace(),
					'rc_title' => $title->getDBkey()
				],
				__METHOD__
			);
			$rcIdsForPage = $this->mDb->selectFieldValues( 'recentchanges',
				'rc_id',
				[ 'rc_type != ' . RC_LOG, 'rc_cur_id' => $id ],
				__METHOD__
			);

			# T98706: delete PK to avoid lock contention with RC delete log insertions
			$rcIds = array_merge( $rcIdsForTitle, $rcIdsForPage );
			if ( $rcIds ) {
				$this->mDb->delete( 'recentchanges', [ 'rc_id' => $rcIds ], __METHOD__ );
			}
		}
	}

	public function getAsJobSpecification() {
		return [
			'wiki' => $this->mDb->getWikiID(),
			'job'  => new JobSpecification(
				'deleteLinks',
				[ 'pageId' => $this->pageId ],
				[ 'removeDuplicates' => true ],
				$this->page->getTitle()
			)
		];
	}
}
