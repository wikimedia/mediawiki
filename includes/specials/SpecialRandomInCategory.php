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
		global $wgScript;

		$cat = false;

		$categoryStr = $this->getRequest()->getText( 'category', $par );

		if ( $categoryStr ) {
			$cat = Title::newFromText( $categoryStr, NS_CATEGORY );
		}

		if ( $cat && $cat->getNamespace() !== NS_CATEGORY ) {
			// Someone searching for something like "Wikipedia:Foo"
			$cat = Title::makeTitleSafe( NS_CATEGORY, $categoryStr );
		}

		if ( $cat ) {
			$this->setCategory( $cat );
		}


		if ( !$this->category && $categoryStr ) {
			$this->setHeaders();
			$this->getOutput()->addWikiMsg( 'randomincategory-invalidcategory',
				wfEscapeWikiText( $categoryStr ) );

			return;
		} elseif ( !$this->category ) {
			$this->setHeaders();
			$input = Html::input( 'category' );
			$submitText = $this->msg( 'randomincategory-selectcategory-submit' )->text();
			$submit = Html::input( '', $submitText, 'submit' );

			$msg = $this->msg( 'randomincategory-selectcategory' );
			$form = Html::rawElement( 'form', array( 'action' => $wgScript ),
				Html::hidden( 'title', $this->getTitle()->getPrefixedText() ) .
				$msg->rawParams( $input, $submit )->parse()
			);
			$this->getOutput()->addHtml( $form );

			return;
		}

		$title = $this->getRandomTitle();

		if ( is_null( $title ) ) {
			$this->setHeaders();
			$this->getOutput()->addWikiMsg( 'randomincategory-nopages',
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
		$rand = (float)wfRandom();
		$title = null;

		// Given that timestamps are rather unevenly distributed, we also
		// use an offset between 0 and 30 to make any biases less noticeable.
		$offset = mt_rand( 0, $this->maxOffset );

		if ( mt_rand( 0, 1 ) ) {
			$up = true;
		} else {
			$up = false;
		}

		$row = $this->selectRandomPageFromDB( $rand, $offset, $up );

		// Try again without the timestamp offset (wrap around the end)
		if ( !$row ) {
			$row = $this->selectRandomPageFromDB( false, $offset, $up );
		}

		// Maybe the category is really small and offset too high
		if ( !$row ) {
			$row = $this->selectRandomPageFromDB( $rand, 0, $up );
		}

		// Just get the first entry.
		if ( !$row ) {
			$row = $this->selectRandomPageFromDB( false, 0, true );
		}

		if ( $row ) {
			return Title::makeTitle( $row->page_namespace, $row->page_title );
		}

		return null;
	}

	/**
	 * @param float $rand Random number between 0 and 1
	 * @param int $offset Extra offset to fudge randomness
	 * @param bool $up True to get the result above the random number, false for below
	 *
	 * @note The $up parameter is supposed to counteract what would happen if there
	 *   was a large gap in the distribution of cl_timestamp values. This way instead
	 *   of things to the right of the gap being favoured, both sides of the gap
	 *   are favoured.
	 * @return Array Query information.
	 */
	protected function getQueryInfo( $rand, $offset, $up ) {
		$op = $up ? '>=' : '<=';
		$dir = $up ? 'ASC' : 'DESC';
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
				'ORDER BY' => 'cl_timestamp ' . $dir,
				'LIMIT' => 1,
				'OFFSET' => $offset
			),
			'join_conds' => array(
				'page' => array( 'INNER JOIN', 'cl_from = page_id' )
			)
		);

		$dbr = wfGetDB( DB_SLAVE );
		$minClTime = $this->getTimestampOffset( $rand );
		if ( $minClTime ) {
			$qi['conds'][] = 'cl_timestamp ' . $op . ' ' .
				$dbr->addQuotes( $dbr->timestamp( $minClTime ) );
		}
		return $qi;
	}

	/**
	 * @param float $rand Random number between 0 and 1
	 *
	 * @return int|bool A random (unix) timestamp from the range of the category or false on failure
	 */
	protected function getTimestampOffset( $rand ) {
		if ( $rand === false ) {
			return false;
		}
		if ( !$this->minTimestamp || !$this->maxTimestamp ) {
			try {
				list( $this->minTimestamp, $this->maxTimestamp ) = $this->getMinAndMaxForCat( $this->category );
			} catch ( MWException $e ) {
				// Possibly no entries in category.
				return false;
			}
		}

		$ts = ( $this->maxTimestamp - $this->minTimestamp ) * $rand + $this->minTimestamp;
		return intval( $ts );
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
				'low' => 'MIN( cl_timestamp )',
				'high' => 'MAX( cl_timestamp )'
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
	 * @param bool $up Get the result above the random value
	 * @param String $fname The name of the calling method
	 * @return Array Info for the title selected.
	 */
	private function selectRandomPageFromDB( $rand, $offset, $up, $fname = __METHOD__ ) {
		$dbr = wfGetDB( DB_SLAVE );

		$query = $this->getQueryInfo( $rand, $offset, $up );
		$res = $dbr->select(
			$query['tables'],
			$query['fields'],
			$query['conds'],
			$fname,
			$query['options'],
			$query['join_conds']
		);

		return $res->fetchObject();
	}

	protected function getGroupName() {
		return 'redirects';
	}
}
