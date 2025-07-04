<?php
/**
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

namespace MediaWiki\Specials;

use BadMethodCallException;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\SpecialPage\FormSpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use stdClass;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * Redirect to a random page in a category
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
 * @author Brian Wolff
 */
class SpecialRandomInCategory extends FormSpecialPage {
	/** @var string[] Extra SQL statements */
	protected $extra = [];
	/** @var Title|false Title object of category */
	protected $category = false;
	/** @var int Max amount to fudge randomness by */
	protected $maxOffset = 30;
	/** @var int|null */
	private $maxTimestamp = null;
	/** @var int|null */
	private $minTimestamp = null;

	private IConnectionProvider $dbProvider;

	public function __construct( IConnectionProvider $dbProvider ) {
		parent::__construct( 'RandomInCategory' );
		$this->dbProvider = $dbProvider;
	}

	/**
	 * Set which category to use.
	 */
	public function setCategory( Title $cat ) {
		$this->category = $cat;
		$this->maxTimestamp = null;
		$this->minTimestamp = null;
	}

	/** @inheritDoc */
	protected function getFormFields() {
		$this->addHelpLink( 'Help:RandomInCategory' );

		return [
			'category' => [
				'type' => 'title',
				'namespace' => NS_CATEGORY,
				'relative' => true,
				'label-message' => 'randomincategory-category',
				'required' => true,
			]
		];
	}

	/** @inheritDoc */
	public function requiresPost() {
		return false;
	}

	/** @inheritDoc */
	protected function getDisplayFormat() {
		return 'ooui';
	}

	protected function alterForm( HTMLForm $form ) {
		$form->setSubmitTextMsg( 'randomincategory-submit' );
	}

	/** @inheritDoc */
	protected function getSubpageField() {
		return 'category';
	}

	/** @inheritDoc */
	public function onSubmit( array $data ) {
		$cat = false;

		$categoryStr = $data['category'];

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
			$msg = $this->msg( 'randomincategory-invalidcategory',
				wfEscapeWikiText( $categoryStr ) );

			return Status::newFatal( $msg );

		} elseif ( !$this->category ) {
			return false; // no data sent
		}

		$title = $this->getRandomTitle();

		if ( $title === null ) {
			$msg = $this->msg( 'randomincategory-nopages',
				$this->category->getText() );

			return Status::newFatal( $msg );
		}

		$query = $this->getRequest()->getQueryValues();
		unset( $query['title'] );
		$this->getOutput()->redirect( $title->getFullURL( $query ) );
	}

	/**
	 * Choose a random title.
	 * @return Title|null Title object or null if nothing to choose from
	 */
	public function getRandomTitle() {
		// Convert to float, since we do math with the random number.
		$rand = (float)wfRandom();

		// Given that timestamps are rather unevenly distributed, we also
		// use an offset between 0 and 30 to make any biases less noticeable.
		$offset = mt_rand( 0, $this->maxOffset );

		if ( mt_rand( 0, 1 ) ) {
			$up = true;
		} else {
			$up = false;
		}

		$row = $this->selectRandomPageFromDB( $rand, $offset, $up, __METHOD__ );

		// Try again without the timestamp offset (wrap around the end)
		if ( !$row ) {
			$row = $this->selectRandomPageFromDB( false, $offset, $up, __METHOD__ );
		}

		// Maybe the category is really small and offset too high
		if ( !$row ) {
			$row = $this->selectRandomPageFromDB( $rand, 0, $up, __METHOD__ );
		}

		// Just get the first entry.
		if ( !$row ) {
			$row = $this->selectRandomPageFromDB( false, 0, true, __METHOD__ );
		}

		if ( $row ) {
			return Title::makeTitle( $row->page_namespace, $row->page_title );
		}

		return null;
	}

	/**
	 * @note The $up parameter is supposed to counteract what would happen if there
	 *   was a large gap in the distribution of cl_timestamp values. This way instead
	 *   of things to the right of the gap being favoured, both sides of the gap
	 *   are favoured.
	 *
	 * @param float|false $rand Random number between 0 and 1
	 * @param int $offset Extra offset to fudge randomness
	 * @param bool $up True to get the result above the random number, false for below
	 * @return SelectQueryBuilder
	 */
	protected function getQueryBuilder( $rand, $offset, $up ) {
		if ( !$this->category instanceof Title ) {
			throw new BadMethodCallException( 'No category set' );
		}
		$dbr = $this->dbProvider->getReplicaDatabase();
		$categoryLinksMigrationStage = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::CategoryLinksSchemaMigrationStage
		);
		$queryBuilder = $dbr->newSelectQueryBuilder()
			->select( [ 'page_title', 'page_namespace' ] )
			->from( 'categorylinks' )
			->join( 'page', null, 'cl_from = page_id' )

			->andWhere( $this->extra )
			->orderBy( 'cl_timestamp', $up ? SelectQueryBuilder::SORT_ASC : SelectQueryBuilder::SORT_DESC )
			->limit( 1 )
			->offset( $offset );
		if ( $categoryLinksMigrationStage & SCHEMA_COMPAT_READ_OLD ) {
			$queryBuilder->where( [ 'cl_to' => $this->category->getDBkey() ] );
		} else {
			$queryBuilder->join( 'linktarget', null, 'cl_target_id = lt_id' )
				->where( [ 'lt_title' => $this->category->getDBkey(), 'lt_namespace' => NS_CATEGORY ] );
		}

		$minClTime = $this->getTimestampOffset( $rand );
		if ( $minClTime ) {
			$op = $up ? '>=' : '<=';
			$queryBuilder->andWhere(
				$dbr->expr( 'cl_timestamp', $op, $dbr->timestamp( $minClTime ) )
			);
		}

		return $queryBuilder;
	}

	/**
	 * @param float|false $rand Random number between 0 and 1
	 * @return int|false A random (unix) timestamp from the range of the category or false on failure
	 */
	protected function getTimestampOffset( $rand ) {
		if ( $rand === false ) {
			return false;
		}
		if ( !$this->minTimestamp || !$this->maxTimestamp ) {
			$minAndMax = $this->getMinAndMaxForCat();
			if ( $minAndMax === null ) {
				// No entries in this category.
				return false;
			}
			[ $this->minTimestamp, $this->maxTimestamp ] = $minAndMax;
		}

		$ts = ( $this->maxTimestamp - $this->minTimestamp ) * $rand + $this->minTimestamp;

		return intval( $ts );
	}

	/**
	 * Get the lowest and highest timestamp for a category.
	 *
	 * @return array|null The lowest and highest timestamp, or null if the category has no entries.
	 */
	protected function getMinAndMaxForCat() {
		$dbr = $this->dbProvider->getReplicaDatabase();
		$categoryLinksMigrationStage = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::CategoryLinksSchemaMigrationStage
		);
		$queryBuilder = $dbr->newSelectQueryBuilder()
			->select( [ 'low' => 'MIN( cl_timestamp )', 'high' => 'MAX( cl_timestamp )' ] )
			->from( 'categorylinks' );
		if ( $categoryLinksMigrationStage & SCHEMA_COMPAT_READ_OLD ) {
			$queryBuilder->where( [ 'cl_to' => $this->category->getDBkey(), ] );
		} else {
			$queryBuilder->join( 'linktarget', null, 'cl_target_id = lt_id' )
				->where( [ 'lt_title' => $this->category->getDBkey(), 'lt_namespace' => NS_CATEGORY ] );
		}
		$res = $queryBuilder->caller( __METHOD__ )->fetchRow();
		if ( !$res ) {
			return null;
		}

		return [ (int)wfTimestamp( TS_UNIX, $res->low ), (int)wfTimestamp( TS_UNIX, $res->high ) ];
	}

	/**
	 * @param float|false $rand A random number that is converted to a random timestamp
	 * @param int $offset A small offset to make the result seem more "random"
	 * @param bool $up Get the result above the random value
	 * @param string $fname The name of the calling method
	 * @return stdClass|false Info for the title selected.
	 */
	private function selectRandomPageFromDB( $rand, $offset, $up, $fname ) {
		return $this->getQueryBuilder( $rand, $offset, $up )->caller( $fname )->fetchRow();
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'redirects';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialRandomInCategory::class, 'SpecialRandomInCategory' );
