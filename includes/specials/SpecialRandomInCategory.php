<?php
/**
 * Implements Special:RandomInCategory
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
 * @ingroup SpecialPage
 * @author Brian Wolff
 */

/**
 * Special page to direct the user to a random page
 *
 * @note The method used here is rather biased. It is assumed that
 * the use of this page will be people wanting to get a random page
 * out of a maintenance category, to fix it up. The method used by
 * this page should return different pages in an unpredictable fashion
 * which is hoped to be sufficient, even if some pages are selected
 * more often than others.
 *
 * A more unbiased method could be achieved by adding a cl_random field
 * to the categorylinks table.
 *
 * The method used here is as follows:
 *  * Find the smallest and largest timestamp in the category
 *  * Pick a random timestamp in between
 *  * Pick an offset between 0 and 30
 *  * Get the offset'ed page that is newer than the timestamp selected
 * The offset is meant to counter the fact the timestamps aren't usually
 * uniformly distributed, so if things are very non-uniform at least we
 * won't have the same page selected 99% of the time.
 *
 * @ingroup SpecialPage
 */
class SpecialRandomInCategory extends SpecialPage {
	protected $extra = array(); // Extra SQL statements
	protected $category = false; // Title object of category
	protected $maxOffset = 30; // Max amount to fudge randomness by.
	private $maxTimestamp = null;
	private $minTimestamp = null;

	public function __construct( $name = 'RandomInCategory' ) {
		parent::__construct( $name );
	}

	/**
	 * Set which category to use.
	 * @param Title $cat
	 */
	public function setCategory( Title $cat ) {
		$this->category = $cat;
		$this->maxTimestamp = null;
		$this->minTimestamp = null;
	}

	public function execute( $par ) {
		$cat = false;

		$categoryStr = $this->getRequest()->getText( 'category', $par );

		if ( $categoryStr ) {
			$cat = Title::newFromText( $categoryStr, NS_CATEGORY );
		}

		if ( $cat ) {
			$this->setCategory( $cat );
		}


		if ( !$this->category && $categoryStr ) {
			$this->setHeaders();
			// For grep: uses message randomincategory-invalidcategory
			$this->getOutput()->addWikiMsg( strtolower( $this->getName() ) . '-invalidcategory',
				wfEscapeWikiText( $categoryStr ) );

			return;
		} elseif ( !$this->category ) {
			$this->setHeaders();
			// For grep: uses message randomincategory-selectcategory, randomincategory-selectcategory-submit.
			$input = Html::input( 'category' );
			$submitText = $this->msg( strtolower( $this->getName() ) . '-selectcategory-submit' )->text();
			$submit = Html::input( '', $submitText, 'submit' );

			$msg = $this->msg( strtolower( $this->getName() ) . '-selectcategory' );
			$form = Html::rawElement( 'form', array( 'action' => $this->getTitle()->getLocalUrl() ),
				$msg->rawParams( $input, $submit )->parse()
			);
			$this->getOutput()->addHtml( $form );

			return;
		}

		$title = $this->getRandomTitle();

		if ( is_null( $title ) ) {
			$this->setHeaders();
			// For grep: Uses message randomincategory-nopages
			$this->getOutput()->addWikiMsg( strtolower( $this->getName() ) . '-nopages',
				$this->category->getText() );

			return;
		}

		$query = $this->getRequest()->getValues();
		unset( $query['title'] );
		unset( $query['category'] );
		$this->getOutput()->redirect( $title->getFullURL( $query ) );
	}

	/**
	 * Choose a random title.
	 * @return Title object (or null if nothing to choose from)
	 */
	public function getRandomTitle() {
		// Convert to float, since we do math with the random number.
		$rand = (float) wfRandom();
		$title = null;

		// Given that timestamps are rather unevenly distributed, we also
		// use an offset between 0 and 30 to make any biases less noticeable.
		$offset = mt_rand( 0, $this->maxOffset );

		$row = $this->selectRandomPageFromDB( $rand, $offset );

		// Try again without the timestamp offset (wrap around the end)
		if ( !$row ) {
			$row = $this->selectRandomPageFromDB( 0, $offset );
		}

		// Maybe the category is really small and offset too high
		if ( !$row ) {
			$row = $this->selectRandomPageFromDB( $rand, 0 );
		}

		// Just get the first entry.
		if ( !$row ) {
			$row = $this->selectRandomPageFromDB( 0, 0 );
		}

		if ( $row ) {
			return Title::makeTitleSafe( $row->page_namespace, $row->page_title );
		}

		return null;
	}

	protected function getQueryInfo( $rand, $offset ) {
		if ( !$this->category instanceof Title ) {
			throw new MWException( 'No category set' );
		}
		$qi = array(
			'tables' => array( 'categorylinks', 'page' ),
			'fields' => array( 'page_title', 'page_namespace' ),
			'conds' => array_merge( array(
				'cl_to' => $this->category->getDBKey(),
			), $this->extra ),
			'options' => array(
				'ORDER BY' => 'cl_timestamp',
				'LIMIT' => 1,
				'OFFSET' => $offset
			),
			'join_conds' => array(
				'page' => array( 'INNER JOIN', 'cl_from = page_id' )
			)
		);
		$minClTime = $this->getTimestampOffset( $rand );
		if ( $minClTime ) {
			$dbr = wfGetDB( DB_SLAVE );
			$qi['conds'][] = 'cl_timestamp > ' . $dbr->addQuotes( $minClTime );
		}
		return $qi;
	}

	/**
	 * @param float $rand Random number between 0 and 1
	 *
	 * @return string|bool A random timestamp from the range of the category or false on failure
	 */
	protected function getTimestampOffset( $rand ) {
		if ( !$this->minTimestamp || !$this->maxTimestamp ) {
			try {
				list( $this->minTimestamp, $this->maxTimestamp ) = $this->getMinAndMaxForCat( $this->category );
			} catch( MWException $e ) {
				// Possibly no entries in category.
				return false;
			}
		}

		$ts = ( $this->maxTimestamp - $this->minTimestamp ) * $rand + $this->minTimestamp;

		// XXX: The cl_timestamp field is weird in mysql schema and uses a different format
		// than what $dbr->timestamp() yields
		return wfTimestamp( TS_DB, intval( $ts ) );
	}

	/**
	 * Get the lowest and highest timestamp for a category.
	 *
	 * @param Title $category
	 * @return Array The lowest and highest timestamp
	 * @throws MWException if category has no entries.
	 */
	protected function getMinAndMaxForCat( Title $category ) {
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->selectRow(
			'categorylinks',
			array(
				'MIN( cl_timestamp ) AS low',
				'MAX( cl_timestamp ) AS high'
			),
			array(
				'cl_to' => $this->category->getDBKey(),
			),
			__METHOD__,
			array(
				'LIMIT' => 1
			)
		);
		if ( !$res ) {
			throw new MWException( 'No entries in category' );
		}
		return array( wfTimestamp( TS_UNIX, $res->low ), wfTimestamp( TS_UNIX, $res->high ) );
	}

	/**
	 * @param float $rand A random number that is converted to a random timestamp
	 * @param int $offset A small offset to make the result seem more "random"
	 * @param String $fname The name of the calling method
	 * @return Array Info for the title selected.
	 */
	private function selectRandomPageFromDB( $rand, $offset, $fname = __METHOD__ ) {
		$dbr = wfGetDB( DB_SLAVE );

		$query = $this->getQueryInfo( $rand, $offset );
		$res = $dbr->select(
			$query['tables'],
			$query['fields'],
			$query['conds'],
			$fname,
			$query['options'],
			$query['join_conds']
		);

		return $dbr->fetchObject( $res );
	}

	protected function getGroupName() {
		return 'redirects';
	}
}
