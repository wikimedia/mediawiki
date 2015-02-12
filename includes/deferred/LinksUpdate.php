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
 * See docs/deferred.txt
 *
 * @todo document (e.g. one-sentence top-level class description).
 */
class LinksUpdate extends SqlDataUpdate {
	// @todo make members protected, but make sure extensions don't break

	/** @var int Page ID of the article linked from */
	public $mId;

	/** @var Title Title object of the article linked from */
	public $mTitle;

	/** @var ParserOutput */
	public $mParserOutput;

	/** @var array Map of title strings to IDs for the links in the document */
	public $mLinks;

	/** @var array DB keys of the images used, in the array key only */
	public $mImages;

	/** @var array Map of title strings to IDs for the template references, including broken ones */
	public $mTemplates;

	/** @var array URLs of external links, array key only */
	public $mExternals;

	/** @var array Map of category names to sort keys */
	public $mCategories;

	/** @var array Map of language codes to titles */
	public $mInterlangs;

	/** @var array Map of arbitrary name to value */
	public $mProperties;

	/** @var bool Whether to queue jobs for recursive updates */
	public $mRecursive;

	/**
	 * @var null|array Added links if calculated.
	 */
	private $linkInsertions = null;

	/**
	 * @var null|array Deleted links if calculated.
	 */
	private $linkDeletions = null;

	/**
	 * Constructor
	 *
	 * @param Title $title Title of the page we're updating
	 * @param ParserOutput $parserOutput Output from a full parse of this page
	 * @param bool $recursive Queue jobs for recursive updates?
	 * @throws MWException
	 */
	function __construct( $title, $parserOutput, $recursive = true ) {
		parent::__construct( false ); // no implicit transaction

		if ( !( $title instanceof Title ) ) {
			throw new MWException( "The calling convention to LinksUpdate::LinksUpdate() has changed. " .
				"Please see Article::editUpdates() for an invocation example.\n" );
		}

		if ( !( $parserOutput instanceof ParserOutput ) ) {
			throw new MWException( "The calling convention to LinksUpdate::__construct() has changed. " .
				"Please see WikiPage::doEditUpdates() for an invocation example.\n" );
		}

		$this->mTitle = $title;
		$this->mId = $title->getArticleID();

		if ( !$this->mId ) {
			throw new MWException( "The Title object did not provide an article " .
				"ID. Perhaps the page doesn't exist?" );
		}

		$this->mParserOutput = $parserOutput;

		$this->mLinks = $parserOutput->getLinks();
		$this->mImages = $parserOutput->getImages();
		$this->mTemplates = $parserOutput->getTemplates();
		$this->mExternals = $parserOutput->getExternalLinks();
		$this->mCategories = $parserOutput->getCategories();
		$this->mProperties = $parserOutput->getProperties();
		$this->mInterwikis = $parserOutput->getInterwikiLinks();

		# Convert the format of the interlanguage links
		# I didn't want to change it in the ParserOutput, because that array is passed all
		# the way back to the skin, so either a skin API break would be required, or an
		# inefficient back-conversion.
		$ill = $parserOutput->getLanguageLinks();
		$this->mInterlangs = array();
		foreach ( $ill as $link ) {
			list( $key, $title ) = explode( ':', $link, 2 );
			$this->mInterlangs[$key] = $title;
		}

		foreach ( $this->mCategories as &$sortkey ) {
			# If the sortkey is longer then 255 bytes,
			# it truncated by DB, and then doesn't get
			# matched when comparing existing vs current
			# categories, causing bug 25254.
			# Also. substr behaves weird when given "".
			if ( $sortkey !== '' ) {
				$sortkey = substr( $sortkey, 0, 255 );
			}
		}

		$this->mRecursive = $recursive;

		Hooks::run( 'LinksUpdateConstructed', array( &$this ) );
	}

	/**
	 * Update link tables with outgoing links from an updated article
	 */
	public function doUpdate() {
		Hooks::run( 'LinksUpdate', array( &$this ) );
		$this->doIncrementalUpdate();
		Hooks::run( 'LinksUpdateComplete', array( &$this ) );
	}

	protected function doIncrementalUpdate() {

		# Page links
		$existing = $this->getExistingLinks();
		$this->linkDeletions = $this->getLinkDeletions( $existing );
		$this->linkInsertions = $this->getLinkInsertions( $existing );
		$this->incrTableUpdate( 'pagelinks', 'pl', $this->linkDeletions, $this->linkInsertions );

		# Image links
		$existing = $this->getExistingImages();

		$imageDeletes = $this->getImageDeletions( $existing );
		$this->incrTableUpdate( 'imagelinks', 'il', $imageDeletes,
			$this->getImageInsertions( $existing ) );

		# Invalidate all image description pages which had links added or removed
		$imageUpdates = $imageDeletes + array_diff_key( $this->mImages, $existing );
		$this->invalidateImageDescriptions( $imageUpdates );

		# External links
		$existing = $this->getExistingExternals();
		$this->incrTableUpdate( 'externallinks', 'el', $this->getExternalDeletions( $existing ),
			$this->getExternalInsertions( $existing ) );

		# Language links
		$existing = $this->getExistingInterlangs();
		$this->incrTableUpdate( 'langlinks', 'll', $this->getInterlangDeletions( $existing ),
			$this->getInterlangInsertions( $existing ) );

		# Inline interwiki links
		$existing = $this->getExistingInterwikis();
		$this->incrTableUpdate( 'iwlinks', 'iwl', $this->getInterwikiDeletions( $existing ),
			$this->getInterwikiInsertions( $existing ) );

		# Template links
		$existing = $this->getExistingTemplates();
		$this->incrTableUpdate( 'templatelinks', 'tl', $this->getTemplateDeletions( $existing ),
			$this->getTemplateInsertions( $existing ) );

		# Category links
		$existing = $this->getExistingCategories();

		$categoryDeletes = $this->getCategoryDeletions( $existing );

		$this->incrTableUpdate( 'categorylinks', 'cl', $categoryDeletes,
			$this->getCategoryInsertions( $existing ) );

		# Invalidate all categories which were added, deleted or changed (set symmetric difference)
		$categoryInserts = array_diff_assoc( $this->mCategories, $existing );
		$categoryUpdates = $categoryInserts + $categoryDeletes;
		$this->invalidateCategories( $categoryUpdates );
		$this->updateCategoryCounts( $categoryInserts, $categoryDeletes );

		# Page properties
		$existing = $this->getExistingProperties();

		$propertiesDeletes = $this->getPropertyDeletions( $existing );

		$this->incrTableUpdate( 'page_props', 'pp', $propertiesDeletes,
			$this->getPropertyInsertions( $existing ) );

		# Invalidate the necessary pages
		$changed = $propertiesDeletes + array_diff_assoc( $this->mProperties, $existing );
		$this->invalidateProperties( $changed );

		# Update the links table freshness for this title
		$this->updateLinksTimestamp();

		# Refresh links of all pages including this page
		# This will be in a separate transaction
		if ( $this->mRecursive ) {
			$this->queueRecursiveJobs();
		}

	}

	/**
	 * Queue recursive jobs for this page
	 *
	 * Which means do LinksUpdate on all pages that include the current page,
	 * using the job queue.
	 */
	protected function queueRecursiveJobs() {
		self::queueRecursiveJobsForTable( $this->mTitle, 'templatelinks' );
		if ( $this->mTitle->getNamespace() == NS_FILE ) {
			// Process imagelinks in case the title is or was a redirect
			self::queueRecursiveJobsForTable( $this->mTitle, 'imagelinks' );
		}

		$bc = $this->mTitle->getBacklinkCache();
		// Get jobs for cascade-protected backlinks for a high priority queue.
		// If meta-templates change to using a new template, the new template
		// should be implicitly protected as soon as possible, if applicable.
		// These jobs duplicate a subset of the above ones, but can run sooner.
		// Which ever runs first generally no-ops the other one.
		$jobs = array();
		foreach ( $bc->getCascadeProtectedLinks() as $title ) {
			$jobs[] = new RefreshLinksJob( $title, array( 'prioritize' => true ) );
		}
		JobQueueGroup::singleton()->push( $jobs );
	}

	/**
	 * Queue a RefreshLinks job for any table.
	 *
	 * @param Title $title Title to do job for
	 * @param string $table Table to use (e.g. 'templatelinks')
	 */
	public static function queueRecursiveJobsForTable( Title $title, $table ) {
		if ( $title->getBacklinkCache()->hasLinks( $table ) ) {
			$job = new RefreshLinksJob(
				$title,
				array(
					'table' => $table,
					'recursive' => true,
				) + Job::newRootJobParams( // "overall" refresh links job info
					"refreshlinks:{$table}:{$title->getPrefixedText()}"
				)
			);

			JobQueueGroup::singleton()->push( $job );
			JobQueueGroup::singleton()->deduplicateRootJob( $job );
		}
	}

	/**
	 * @param array $cats
	 */
	function invalidateCategories( $cats ) {
		$this->invalidatePages( NS_CATEGORY, array_keys( $cats ) );
	}

	/**
	 * Update all the appropriate counts in the category table.
	 * @param array $added Associative array of category name => sort key
	 * @param array $deleted Associative array of category name => sort key
	 */
	function updateCategoryCounts( $added, $deleted ) {
		$a = WikiPage::factory( $this->mTitle );
		$a->updateCategoryCounts(
			array_keys( $added ), array_keys( $deleted )
		);
	}

	/**
	 * @param array $images
	 */
	function invalidateImageDescriptions( $images ) {
		$this->invalidatePages( NS_FILE, array_keys( $images ) );
	}

	/**
	 * Update a table by doing a delete query then an insert query
	 * @param string $table Table name
	 * @param string $prefix Field name prefix
	 * @param array $deletions
	 * @param array $insertions Rows to insert
	 */
	function incrTableUpdate( $table, $prefix, $deletions, $insertions ) {
		if ( $table == 'page_props' ) {
			$fromField = 'pp_page';
		} else {
			$fromField = "{$prefix}_from";
		}
		$where = array( $fromField => $this->mId );
		if ( $table == 'pagelinks' || $table == 'templatelinks' || $table == 'iwlinks' ) {
			if ( $table == 'iwlinks' ) {
				$baseKey = 'iwl_prefix';
			} else {
				$baseKey = "{$prefix}_namespace";
			}
			$clause = $this->mDb->makeWhereFrom2d( $deletions, $baseKey, "{$prefix}_title" );
			if ( $clause ) {
				$where[] = $clause;
			} else {
				$where = false;
			}
		} else {
			if ( $table == 'langlinks' ) {
				$toField = 'll_lang';
			} elseif ( $table == 'page_props' ) {
				$toField = 'pp_propname';
			} else {
				$toField = $prefix . '_to';
			}
			if ( count( $deletions ) ) {
				$where[$toField] = array_keys( $deletions );
			} else {
				$where = false;
			}
		}
		if ( $where ) {
			$this->mDb->delete( $table, $where, __METHOD__ );
		}
		if ( count( $insertions ) ) {
			$this->mDb->insert( $table, $insertions, __METHOD__, 'IGNORE' );
			Hooks::run( 'LinksUpdateAfterInsert', array( $this, $table, $insertions ) );
		}
	}

	/**
	 * Get an array of pagelinks insertions for passing to the DB
	 * Skips the titles specified by the 2-D array $existing
	 * @param array $existing
	 * @return array
	 */
	private function getLinkInsertions( $existing = array() ) {
		$arr = array();
		foreach ( $this->mLinks as $ns => $dbkeys ) {
			$diffs = isset( $existing[$ns] )
				? array_diff_key( $dbkeys, $existing[$ns] )
				: $dbkeys;
			foreach ( $diffs as $dbk => $id ) {
				$arr[] = array(
					'pl_from' => $this->mId,
					'pl_from_namespace' => $this->mTitle->getNamespace(),
					'pl_namespace' => $ns,
					'pl_title' => $dbk
				);
			}
		}

		return $arr;
	}

	/**
	 * Get an array of template insertions. Like getLinkInsertions()
	 * @param array $existing
	 * @return array
	 */
	private function getTemplateInsertions( $existing = array() ) {
		$arr = array();
		foreach ( $this->mTemplates as $ns => $dbkeys ) {
			$diffs = isset( $existing[$ns] ) ? array_diff_key( $dbkeys, $existing[$ns] ) : $dbkeys;
			foreach ( $diffs as $dbk => $id ) {
				$arr[] = array(
					'tl_from' => $this->mId,
					'tl_from_namespace' => $this->mTitle->getNamespace(),
					'tl_namespace' => $ns,
					'tl_title' => $dbk
				);
			}
		}

		return $arr;
	}

	/**
	 * Get an array of image insertions
	 * Skips the names specified in $existing
	 * @param array $existing
	 * @return array
	 */
	private function getImageInsertions( $existing = array() ) {
		$arr = array();
		$diffs = array_diff_key( $this->mImages, $existing );
		foreach ( $diffs as $iname => $dummy ) {
			$arr[] = array(
				'il_from' => $this->mId,
				'il_from_namespace' => $this->mTitle->getNamespace(),
				'il_to' => $iname
			);
		}

		return $arr;
	}

	/**
	 * Get an array of externallinks insertions. Skips the names specified in $existing
	 * @param array $existing
	 * @return array
	 */
	private function getExternalInsertions( $existing = array() ) {
		$arr = array();
		$diffs = array_diff_key( $this->mExternals, $existing );
		foreach ( $diffs as $url => $dummy ) {
			foreach ( wfMakeUrlIndexes( $url ) as $index ) {
				$arr[] = array(
					'el_id' => $this->mDb->nextSequenceValue( 'externallinks_el_id_seq' ),
					'el_from' => $this->mId,
					'el_to' => $url,
					'el_index' => $index,
				);
			}
		}

		return $arr;
	}

	/**
	 * Get an array of category insertions
	 *
	 * @param array $existing Mapping existing category names to sort keys. If both
	 * match a link in $this, the link will be omitted from the output
	 *
	 * @return array
	 */
	private function getCategoryInsertions( $existing = array() ) {
		global $wgContLang, $wgCategoryCollation;
		$diffs = array_diff_assoc( $this->mCategories, $existing );
		$arr = array();
		foreach ( $diffs as $name => $prefix ) {
			$nt = Title::makeTitleSafe( NS_CATEGORY, $name );
			$wgContLang->findVariantLink( $name, $nt, true );

			if ( $this->mTitle->getNamespace() == NS_CATEGORY ) {
				$type = 'subcat';
			} elseif ( $this->mTitle->getNamespace() == NS_FILE ) {
				$type = 'file';
			} else {
				$type = 'page';
			}

			# Treat custom sortkeys as a prefix, so that if multiple
			# things are forced to sort as '*' or something, they'll
			# sort properly in the category rather than in page_id
			# order or such.
			$sortkey = Collation::singleton()->getSortKey(
				$this->mTitle->getCategorySortkey( $prefix ) );

			$arr[] = array(
				'cl_from' => $this->mId,
				'cl_to' => $name,
				'cl_sortkey' => $sortkey,
				'cl_timestamp' => $this->mDb->timestamp(),
				'cl_sortkey_prefix' => $prefix,
				'cl_collation' => $wgCategoryCollation,
				'cl_type' => $type,
			);
		}

		return $arr;
	}

	/**
	 * Get an array of interlanguage link insertions
	 *
	 * @param array $existing Mapping existing language codes to titles
	 *
	 * @return array
	 */
	private function getInterlangInsertions( $existing = array() ) {
		$diffs = array_diff_assoc( $this->mInterlangs, $existing );
		$arr = array();
		foreach ( $diffs as $lang => $title ) {
			$arr[] = array(
				'll_from' => $this->mId,
				'll_lang' => $lang,
				'll_title' => $title
			);
		}

		return $arr;
	}

	/**
	 * Get an array of page property insertions
	 * @param array $existing
	 * @return array
	 */
	function getPropertyInsertions( $existing = array() ) {
		$diffs = array_diff_assoc( $this->mProperties, $existing );

		$arr = array();
		foreach ( array_keys( $diffs ) as $name ) {
			$arr[] = $this->getPagePropRowData( $name );
		}

		return $arr;
	}

	/**
	 * Returns an associative array to be used for inserting a row into
	 * the page_props table. Besides the given property name, this will
	 * include the page id from $this->mId and any property value from
	 * $this->mProperties.
	 *
	 * The array returned will include the pp_sortkey field if this
	 * is present in the database (as indicated by $wgPagePropsHaveSortkey).
	 * The sortkey value is currently determined by getPropertySortKeyValue().
	 *
	 * @note this assumes that $this->mProperties[$prop] is defined.
	 *
	 * @param string $prop The name of the property.
	 *
	 * @return array
	 */
	private function getPagePropRowData( $prop ) {
		global $wgPagePropsHaveSortkey;

		$value = $this->mProperties[$prop];

		$row = array(
			'pp_page' => $this->mId,
			'pp_propname' => $prop,
			'pp_value' => $value,
		);

		if ( $wgPagePropsHaveSortkey ) {
			$row['pp_sortkey'] = $this->getPropertySortKeyValue( $value );
		}

		return $row;
	}

	/**
	 * Determines the sort key for the given property value.
	 * This will return $value if it is a float or int,
	 * 1 or resp. 0 if it is a bool, and null otherwise.
	 *
	 * @note In the future, we may allow the sortkey to be specified explicitly
	 *       in ParserOutput::setProperty.
	 *
	 * @param mixed $value
	 *
	 * @return float|null
	 */
	private function getPropertySortKeyValue( $value ) {
		if ( is_int( $value ) || is_float( $value ) || is_bool( $value ) ) {
			return floatval( $value );
		}

		return null;
	}

	/**
	 * Get an array of interwiki insertions for passing to the DB
	 * Skips the titles specified by the 2-D array $existing
	 * @param array $existing
	 * @return array
	 */
	private function getInterwikiInsertions( $existing = array() ) {
		$arr = array();
		foreach ( $this->mInterwikis as $prefix => $dbkeys ) {
			$diffs = isset( $existing[$prefix] )
				? array_diff_key( $dbkeys, $existing[$prefix] )
				: $dbkeys;

			foreach ( $diffs as $dbk => $id ) {
				$arr[] = array(
					'iwl_from' => $this->mId,
					'iwl_prefix' => $prefix,
					'iwl_title' => $dbk
				);
			}
		}

		return $arr;
	}

	/**
	 * Given an array of existing links, returns those links which are not in $this
	 * and thus should be deleted.
	 * @param array $existing
	 * @return array
	 */
	private function getLinkDeletions( $existing ) {
		$del = array();
		foreach ( $existing as $ns => $dbkeys ) {
			if ( isset( $this->mLinks[$ns] ) ) {
				$del[$ns] = array_diff_key( $existing[$ns], $this->mLinks[$ns] );
			} else {
				$del[$ns] = $existing[$ns];
			}
		}

		return $del;
	}

	/**
	 * Given an array of existing templates, returns those templates which are not in $this
	 * and thus should be deleted.
	 * @param array $existing
	 * @return array
	 */
	private function getTemplateDeletions( $existing ) {
		$del = array();
		foreach ( $existing as $ns => $dbkeys ) {
			if ( isset( $this->mTemplates[$ns] ) ) {
				$del[$ns] = array_diff_key( $existing[$ns], $this->mTemplates[$ns] );
			} else {
				$del[$ns] = $existing[$ns];
			}
		}

		return $del;
	}

	/**
	 * Given an array of existing images, returns those images which are not in $this
	 * and thus should be deleted.
	 * @param array $existing
	 * @return array
	 */
	private function getImageDeletions( $existing ) {
		return array_diff_key( $existing, $this->mImages );
	}

	/**
	 * Given an array of existing external links, returns those links which are not
	 * in $this and thus should be deleted.
	 * @param array $existing
	 * @return array
	 */
	private function getExternalDeletions( $existing ) {
		return array_diff_key( $existing, $this->mExternals );
	}

	/**
	 * Given an array of existing categories, returns those categories which are not in $this
	 * and thus should be deleted.
	 * @param array $existing
	 * @return array
	 */
	private function getCategoryDeletions( $existing ) {
		return array_diff_assoc( $existing, $this->mCategories );
	}

	/**
	 * Given an array of existing interlanguage links, returns those links which are not
	 * in $this and thus should be deleted.
	 * @param array $existing
	 * @return array
	 */
	private function getInterlangDeletions( $existing ) {
		return array_diff_assoc( $existing, $this->mInterlangs );
	}

	/**
	 * Get array of properties which should be deleted.
	 * @param array $existing
	 * @return array
	 */
	function getPropertyDeletions( $existing ) {
		return array_diff_assoc( $existing, $this->mProperties );
	}

	/**
	 * Given an array of existing interwiki links, returns those links which are not in $this
	 * and thus should be deleted.
	 * @param array $existing
	 * @return array
	 */
	private function getInterwikiDeletions( $existing ) {
		$del = array();
		foreach ( $existing as $prefix => $dbkeys ) {
			if ( isset( $this->mInterwikis[$prefix] ) ) {
				$del[$prefix] = array_diff_key( $existing[$prefix], $this->mInterwikis[$prefix] );
			} else {
				$del[$prefix] = $existing[$prefix];
			}
		}

		return $del;
	}

	/**
	 * Get an array of existing links, as a 2-D array
	 *
	 * @return array
	 */
	private function getExistingLinks() {
		$res = $this->mDb->select( 'pagelinks', array( 'pl_namespace', 'pl_title' ),
			array( 'pl_from' => $this->mId ), __METHOD__, $this->mOptions );
		$arr = array();
		foreach ( $res as $row ) {
			if ( !isset( $arr[$row->pl_namespace] ) ) {
				$arr[$row->pl_namespace] = array();
			}
			$arr[$row->pl_namespace][$row->pl_title] = 1;
		}

		return $arr;
	}

	/**
	 * Get an array of existing templates, as a 2-D array
	 *
	 * @return array
	 */
	private function getExistingTemplates() {
		$res = $this->mDb->select( 'templatelinks', array( 'tl_namespace', 'tl_title' ),
			array( 'tl_from' => $this->mId ), __METHOD__, $this->mOptions );
		$arr = array();
		foreach ( $res as $row ) {
			if ( !isset( $arr[$row->tl_namespace] ) ) {
				$arr[$row->tl_namespace] = array();
			}
			$arr[$row->tl_namespace][$row->tl_title] = 1;
		}

		return $arr;
	}

	/**
	 * Get an array of existing images, image names in the keys
	 *
	 * @return array
	 */
	private function getExistingImages() {
		$res = $this->mDb->select( 'imagelinks', array( 'il_to' ),
			array( 'il_from' => $this->mId ), __METHOD__, $this->mOptions );
		$arr = array();
		foreach ( $res as $row ) {
			$arr[$row->il_to] = 1;
		}

		return $arr;
	}

	/**
	 * Get an array of existing external links, URLs in the keys
	 *
	 * @return array
	 */
	private function getExistingExternals() {
		$res = $this->mDb->select( 'externallinks', array( 'el_to' ),
			array( 'el_from' => $this->mId ), __METHOD__, $this->mOptions );
		$arr = array();
		foreach ( $res as $row ) {
			$arr[$row->el_to] = 1;
		}

		return $arr;
	}

	/**
	 * Get an array of existing categories, with the name in the key and sort key in the value.
	 *
	 * @return array
	 */
	private function getExistingCategories() {
		$res = $this->mDb->select( 'categorylinks', array( 'cl_to', 'cl_sortkey_prefix' ),
			array( 'cl_from' => $this->mId ), __METHOD__, $this->mOptions );
		$arr = array();
		foreach ( $res as $row ) {
			$arr[$row->cl_to] = $row->cl_sortkey_prefix;
		}

		return $arr;
	}

	/**
	 * Get an array of existing interlanguage links, with the language code in the key and the
	 * title in the value.
	 *
	 * @return array
	 */
	private function getExistingInterlangs() {
		$res = $this->mDb->select( 'langlinks', array( 'll_lang', 'll_title' ),
			array( 'll_from' => $this->mId ), __METHOD__, $this->mOptions );
		$arr = array();
		foreach ( $res as $row ) {
			$arr[$row->ll_lang] = $row->ll_title;
		}

		return $arr;
	}

	/**
	 * Get an array of existing inline interwiki links, as a 2-D array
	 * @return array (prefix => array(dbkey => 1))
	 */
	protected function getExistingInterwikis() {
		$res = $this->mDb->select( 'iwlinks', array( 'iwl_prefix', 'iwl_title' ),
			array( 'iwl_from' => $this->mId ), __METHOD__, $this->mOptions );
		$arr = array();
		foreach ( $res as $row ) {
			if ( !isset( $arr[$row->iwl_prefix] ) ) {
				$arr[$row->iwl_prefix] = array();
			}
			$arr[$row->iwl_prefix][$row->iwl_title] = 1;
		}

		return $arr;
	}

	/**
	 * Get an array of existing categories, with the name in the key and sort key in the value.
	 *
	 * @return array Array of property names and values
	 */
	private function getExistingProperties() {
		$res = $this->mDb->select( 'page_props', array( 'pp_propname', 'pp_value' ),
			array( 'pp_page' => $this->mId ), __METHOD__, $this->mOptions );
		$arr = array();
		foreach ( $res as $row ) {
			$arr[$row->pp_propname] = $row->pp_value;
		}

		return $arr;
	}

	/**
	 * Return the title object of the page being updated
	 * @return Title
	 */
	public function getTitle() {
		return $this->mTitle;
	}

	/**
	 * Returns parser output
	 * @since 1.19
	 * @return ParserOutput
	 */
	public function getParserOutput() {
		return $this->mParserOutput;
	}

	/**
	 * Return the list of images used as generated by the parser
	 * @return array
	 */
	public function getImages() {
		return $this->mImages;
	}

	/**
	 * Invalidate any necessary link lists related to page property changes
	 * @param array $changed
	 */
	private function invalidateProperties( $changed ) {
		global $wgPagePropLinkInvalidations;

		foreach ( $changed as $name => $value ) {
			if ( isset( $wgPagePropLinkInvalidations[$name] ) ) {
				$inv = $wgPagePropLinkInvalidations[$name];
				if ( !is_array( $inv ) ) {
					$inv = array( $inv );
				}
				foreach ( $inv as $table ) {
					$update = new HTMLCacheUpdate( $this->mTitle, $table );
					$update->doUpdate();
				}
			}
		}
	}

	/**
	 * Fetch page links added by this LinksUpdate.  Only available after the update is complete.
	 * @since 1.22
	 * @return null|array Array of Titles
	 */
	public function getAddedLinks() {
		if ( $this->linkInsertions === null ) {
			return null;
		}
		$result = array();
		foreach ( $this->linkInsertions as $insertion ) {
			$result[] = Title::makeTitle( $insertion['pl_namespace'], $insertion['pl_title'] );
		}

		return $result;
	}

	/**
	 * Fetch page links removed by this LinksUpdate.  Only available after the update is complete.
	 * @since 1.22
	 * @return null|array Array of Titles
	 */
	public function getRemovedLinks() {
		if ( $this->linkDeletions === null ) {
			return null;
		}
		$result = array();
		foreach ( $this->linkDeletions as $ns => $titles ) {
			foreach ( $titles as $title => $unused ) {
				$result[] = Title::makeTitle( $ns, $title );
			}
		}

		return $result;
	}

	/**
	 * Update links table freshness
	 */
	protected function updateLinksTimestamp() {
		if ( $this->mId ) {
			// The link updates made here only reflect the freshness of the parser output
			$timestamp = $this->mParserOutput->getCacheTime();
			$this->mDb->update( 'page',
				array( 'page_links_updated' => $this->mDb->timestamp( $timestamp ) ),
				array( 'page_id' => $this->mId ),
				__METHOD__
			);
		}
	}
}

/**
 * Update object handling the cleanup of links tables after a page was deleted.
 **/
class LinksDeletionUpdate extends SqlDataUpdate {
	/** @var WikiPage The WikiPage that was deleted */
	protected $mPage;

	/**
	 * Constructor
	 *
	 * @param WikiPage $page Page we are updating
	 * @throws MWException
	 */
	function __construct( WikiPage $page ) {
		parent::__construct( false ); // no implicit transaction

		$this->mPage = $page;

		if ( !$page->exists() ) {
			throw new MWException( "Page ID not known, perhaps the page doesn't exist?" );
		}
	}

	/**
	 * Do some database updates after deletion
	 */
	public function doUpdate() {
		$title = $this->mPage->getTitle();
		$id = $this->mPage->getId();

		# Delete restrictions for it
		$this->mDb->delete( 'page_restrictions', array( 'pr_page' => $id ), __METHOD__ );

		# Fix category table counts
		$cats = array();
		$res = $this->mDb->select( 'categorylinks', 'cl_to', array( 'cl_from' => $id ), __METHOD__ );

		foreach ( $res as $row ) {
			$cats[] = $row->cl_to;
		}

		$this->mPage->updateCategoryCounts( array(), $cats );

		# If using cascading deletes, we can skip some explicit deletes
		if ( !$this->mDb->cascadingDeletes() ) {
			# Delete outgoing links
			$this->mDb->delete( 'pagelinks', array( 'pl_from' => $id ), __METHOD__ );
			$this->mDb->delete( 'imagelinks', array( 'il_from' => $id ), __METHOD__ );
			$this->mDb->delete( 'categorylinks', array( 'cl_from' => $id ), __METHOD__ );
			$this->mDb->delete( 'templatelinks', array( 'tl_from' => $id ), __METHOD__ );
			$this->mDb->delete( 'externallinks', array( 'el_from' => $id ), __METHOD__ );
			$this->mDb->delete( 'langlinks', array( 'll_from' => $id ), __METHOD__ );
			$this->mDb->delete( 'iwlinks', array( 'iwl_from' => $id ), __METHOD__ );
			$this->mDb->delete( 'redirect', array( 'rd_from' => $id ), __METHOD__ );
			$this->mDb->delete( 'page_props', array( 'pp_page' => $id ), __METHOD__ );
		}

		# If using cleanup triggers, we can skip some manual deletes
		if ( !$this->mDb->cleanupTriggers() ) {
			# Clean up recentchanges entries...
			$this->mDb->delete( 'recentchanges',
				array( 'rc_type != ' . RC_LOG,
					'rc_namespace' => $title->getNamespace(),
					'rc_title' => $title->getDBkey() ),
				__METHOD__ );
			$this->mDb->delete( 'recentchanges',
				array( 'rc_type != ' . RC_LOG, 'rc_cur_id' => $id ),
				__METHOD__ );
		}
	}

	/**
	 * Update all the appropriate counts in the category table.
	 * @param array $added Associative array of category name => sort key
	 * @param array $deleted Associative array of category name => sort key
	 */
	function updateCategoryCounts( $added, $deleted ) {
		$a = WikiPage::factory( $this->mTitle );
		$a->updateCategoryCounts(
			array_keys( $added ), array_keys( $deleted )
		);
	}
}
