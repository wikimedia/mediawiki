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

use Wikimedia\Rdbms\IDatabase;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use Wikimedia\ScopedCallback;

/**
 * Class the manages updates of *_link tables as well as similar extension-managed tables
 *
 * @note: LinksUpdate is managed by DeferredUpdates::execute(). Do not run this in a transaction.
 *
 * See docs/deferred.txt
 */
class LinksUpdate extends DataUpdate implements EnqueueableDataUpdate {
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

	/** @var array 2-D map of (prefix => DBK => 1) */
	public $mInterwikis;

	/** @var array Map of arbitrary name to value */
	public $mProperties;

	/** @var bool Whether to queue jobs for recursive updates */
	public $mRecursive;

	/** @var Revision Revision for which this update has been triggered */
	private $mRevision;

	/**
	 * @var null|array Added links if calculated.
	 */
	private $linkInsertions = null;

	/**
	 * @var null|array Deleted links if calculated.
	 */
	private $linkDeletions = null;

	/**
	 * @var null|array Added properties if calculated.
	 */
	private $propertyInsertions = null;

	/**
	 * @var null|array Deleted properties if calculated.
	 */
	private $propertyDeletions = null;

	/**
	 * @var User|null
	 */
	private $user;

	/** @var IDatabase */
	private $db;

	/**
	 * @param Title $title Title of the page we're updating
	 * @param ParserOutput $parserOutput Output from a full parse of this page
	 * @param bool $recursive Queue jobs for recursive updates?
	 * @throws MWException
	 */
	function __construct( Title $title, ParserOutput $parserOutput, $recursive = true ) {
		parent::__construct();

		$this->mTitle = $title;
		$this->mId = $title->getArticleID( Title::GAID_FOR_UPDATE );

		if ( !$this->mId ) {
			throw new InvalidArgumentException(
				"The Title object yields no ID. Perhaps the page doesn't exist?"
			);
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
		$this->mInterlangs = [];
		foreach ( $ill as $link ) {
			list( $key, $title ) = explode( ':', $link, 2 );
			$this->mInterlangs[$key] = $title;
		}

		foreach ( $this->mCategories as &$sortkey ) {
			# If the sortkey is longer then 255 bytes, it is truncated by DB, and then doesn't match
			# when comparing existing vs current categories, causing T27254.
			$sortkey = mb_strcut( $sortkey, 0, 255 );
		}

		$this->mRecursive = $recursive;

		// Avoid PHP 7.1 warning from passing $this by reference
		$linksUpdate = $this;
		Hooks::run( 'LinksUpdateConstructed', [ &$linksUpdate ] );
	}

	/**
	 * Update link tables with outgoing links from an updated article
	 *
	 * @note this is managed by DeferredUpdates::execute(). Do not run this in a transaction.
	 */
	public function doUpdate() {
		if ( $this->ticket ) {
			// Make sure all links update threads see the changes of each other.
			// This handles the case when updates have to batched into several COMMITs.
			$scopedLock = self::acquirePageLock( $this->getDB(), $this->mId );
			if ( !$scopedLock ) {
				throw new RuntimeException( "Could not acquire lock for page ID '{$this->mId}'." );
			}
		}

		// Avoid PHP 7.1 warning from passing $this by reference
		$linksUpdate = $this;
		Hooks::run( 'LinksUpdate', [ &$linksUpdate ] );
		$this->doIncrementalUpdate();

		// Commit and release the lock (if set)
		ScopedCallback::consume( $scopedLock );
		// Run post-commit hook handlers without DBO_TRX
		DeferredUpdates::addUpdate( new AutoCommitUpdate(
			$this->getDB(),
			__METHOD__,
			function () {
				// Avoid PHP 7.1 warning from passing $this by reference
				$linksUpdate = $this;
				Hooks::run( 'LinksUpdateComplete', [ &$linksUpdate, $this->ticket ] );
			}
		) );
	}

	/**
	 * Acquire a lock for performing link table updates for a page on a DB
	 *
	 * @param IDatabase $dbw
	 * @param int $pageId
	 * @param string $why One of (job, atomicity)
	 * @return ScopedCallback|null
	 * @since 1.27
	 */
	public static function acquirePageLock( IDatabase $dbw, $pageId, $why = 'atomicity' ) {
		$key = "LinksUpdate:$why:pageid:$pageId";
		$scopedLock = $dbw->getScopedLockAndFlush( $key, __METHOD__, 15 );
		if ( !$scopedLock ) {
			$logger = LoggerFactory::getInstance( 'SecondaryDataUpdate' );
			$logger->info( "Could not acquire lock '{key}' for page ID '{page_id}'.", [
				'key' => $key,
				'page_id' => $pageId,
			] );
			return null;
		}

		return $scopedLock;
	}

	protected function doIncrementalUpdate() {
		# Page links
		$existingPL = $this->getExistingLinks();
		$this->linkDeletions = $this->getLinkDeletions( $existingPL );
		$this->linkInsertions = $this->getLinkInsertions( $existingPL );
		$this->incrTableUpdate( 'pagelinks', 'pl', $this->linkDeletions, $this->linkInsertions );

		# Image links
		$existingIL = $this->getExistingImages();
		$imageDeletes = $this->getImageDeletions( $existingIL );
		$this->incrTableUpdate(
			'imagelinks',
			'il',
			$imageDeletes,
			$this->getImageInsertions( $existingIL ) );

		# Invalidate all image description pages which had links added or removed
		$imageUpdates = $imageDeletes + array_diff_key( $this->mImages, $existingIL );
		$this->invalidateImageDescriptions( $imageUpdates );

		# External links
		$existingEL = $this->getExistingExternals();
		$this->incrTableUpdate(
			'externallinks',
			'el',
			$this->getExternalDeletions( $existingEL ),
			$this->getExternalInsertions( $existingEL ) );

		# Language links
		$existingLL = $this->getExistingInterlangs();
		$this->incrTableUpdate(
			'langlinks',
			'll',
			$this->getInterlangDeletions( $existingLL ),
			$this->getInterlangInsertions( $existingLL ) );

		# Inline interwiki links
		$existingIW = $this->getExistingInterwikis();
		$this->incrTableUpdate(
			'iwlinks',
			'iwl',
			$this->getInterwikiDeletions( $existingIW ),
			$this->getInterwikiInsertions( $existingIW ) );

		# Template links
		$existingTL = $this->getExistingTemplates();
		$this->incrTableUpdate(
			'templatelinks',
			'tl',
			$this->getTemplateDeletions( $existingTL ),
			$this->getTemplateInsertions( $existingTL ) );

		# Category links
		$existingCL = $this->getExistingCategories();
		$categoryDeletes = $this->getCategoryDeletions( $existingCL );
		$this->incrTableUpdate(
			'categorylinks',
			'cl',
			$categoryDeletes,
			$this->getCategoryInsertions( $existingCL ) );
		$categoryInserts = array_diff_assoc( $this->mCategories, $existingCL );
		$categoryUpdates = $categoryInserts + $categoryDeletes;

		# Page properties
		$existingPP = $this->getExistingProperties();
		$this->propertyDeletions = $this->getPropertyDeletions( $existingPP );
		$this->incrTableUpdate(
			'page_props',
			'pp',
			$this->propertyDeletions,
			$this->getPropertyInsertions( $existingPP ) );

		# Invalidate the necessary pages
		$this->propertyInsertions = array_diff_assoc( $this->mProperties, $existingPP );
		$changed = $this->propertyDeletions + $this->propertyInsertions;
		$this->invalidateProperties( $changed );

		# Invalidate all categories which were added, deleted or changed (set symmetric difference)
		$this->invalidateCategories( $categoryUpdates );
		$this->updateCategoryCounts( $categoryInserts, $categoryDeletes );

		# Refresh links of all pages including this page
		# This will be in a separate transaction
		if ( $this->mRecursive ) {
			$this->queueRecursiveJobs();
		}

		# Update the links table freshness for this title
		$this->updateLinksTimestamp();
	}

	/**
	 * Queue recursive jobs for this page
	 *
	 * Which means do LinksUpdate on all pages that include the current page,
	 * using the job queue.
	 */
	protected function queueRecursiveJobs() {
		$action = $this->getCauseAction();
		$agent = $this->getCauseAgent();

		self::queueRecursiveJobsForTable( $this->mTitle, 'templatelinks', $action, $agent );
		if ( $this->mTitle->getNamespace() == NS_FILE ) {
			// Process imagelinks in case the title is or was a redirect
			self::queueRecursiveJobsForTable( $this->mTitle, 'imagelinks', $action, $agent );
		}

		$bc = $this->mTitle->getBacklinkCache();
		// Get jobs for cascade-protected backlinks for a high priority queue.
		// If meta-templates change to using a new template, the new template
		// should be implicitly protected as soon as possible, if applicable.
		// These jobs duplicate a subset of the above ones, but can run sooner.
		// Which ever runs first generally no-ops the other one.
		$jobs = [];
		foreach ( $bc->getCascadeProtectedLinks() as $title ) {
			$jobs[] = RefreshLinksJob::newPrioritized(
				$title,
				[
					'causeAction' => $action,
					'causeAgent' => $agent
				]
			);
		}
		JobQueueGroup::singleton()->push( $jobs );
	}

	/**
	 * Queue a RefreshLinks job for any table.
	 *
	 * @param Title $title Title to do job for
	 * @param string $table Table to use (e.g. 'templatelinks')
	 * @param string $action Triggering action
	 * @param string $userName Triggering user name
	 */
	public static function queueRecursiveJobsForTable(
		Title $title, $table, $action = 'unknown', $userName = 'unknown'
	) {
		if ( $title->getBacklinkCache()->hasLinks( $table ) ) {
			$job = new RefreshLinksJob(
				$title,
				[
					'table' => $table,
					'recursive' => true,
				] + Job::newRootJobParams( // "overall" refresh links job info
					"refreshlinks:{$table}:{$title->getPrefixedText()}"
				) + [ 'causeAction' => $action, 'causeAgent' => $userName ]
			);

			JobQueueGroup::singleton()->push( $job );
		}
	}

	/**
	 * @param array $cats
	 */
	private function invalidateCategories( $cats ) {
		PurgeJobUtils::invalidatePages( $this->getDB(), NS_CATEGORY, array_keys( $cats ) );
	}

	/**
	 * Update all the appropriate counts in the category table.
	 * @param array $added Associative array of category name => sort key
	 * @param array $deleted Associative array of category name => sort key
	 */
	private function updateCategoryCounts( array $added, array $deleted ) {
		global $wgUpdateRowsPerQuery;

		if ( !$added && !$deleted ) {
			return;
		}

		$domainId = $this->getDB()->getDomainID();
		$wp = WikiPage::factory( $this->mTitle );
		$lbf = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		// T163801: try to release any row locks to reduce contention
		$lbf->commitAndWaitForReplication( __METHOD__, $this->ticket, [ 'domain' => $domainId ] );

		foreach ( array_chunk( array_keys( $added ), $wgUpdateRowsPerQuery ) as $addBatch ) {
			$wp->updateCategoryCounts( $addBatch, [], $this->mId );
			$lbf->commitAndWaitForReplication(
				__METHOD__, $this->ticket, [ 'domain' => $domainId ] );
		}

		foreach ( array_chunk( array_keys( $deleted ), $wgUpdateRowsPerQuery ) as $deleteBatch ) {
			$wp->updateCategoryCounts( [], $deleteBatch, $this->mId );
			$lbf->commitAndWaitForReplication(
				__METHOD__, $this->ticket, [ 'domain' => $domainId ] );
		}
	}

	/**
	 * @param array $images
	 */
	private function invalidateImageDescriptions( array $images ) {
		PurgeJobUtils::invalidatePages( $this->getDB(), NS_FILE, array_keys( $images ) );
	}

	/**
	 * Update a table by doing a delete query then an insert query
	 * @param string $table Table name
	 * @param string $prefix Field name prefix
	 * @param array $deletions
	 * @param array $insertions Rows to insert
	 */
	private function incrTableUpdate( $table, $prefix, $deletions, $insertions ) {
		$services = MediaWikiServices::getInstance();
		$bSize = $services->getMainConfig()->get( 'UpdateRowsPerQuery' );
		$lbf = $services->getDBLoadBalancerFactory();

		if ( $table === 'page_props' ) {
			$fromField = 'pp_page';
		} else {
			$fromField = "{$prefix}_from";
		}

		$deleteWheres = []; // list of WHERE clause arrays for each DB delete() call
		if ( $table === 'pagelinks' || $table === 'templatelinks' || $table === 'iwlinks' ) {
			$baseKey = ( $table === 'iwlinks' ) ? 'iwl_prefix' : "{$prefix}_namespace";

			$curBatchSize = 0;
			$curDeletionBatch = [];
			$deletionBatches = [];
			foreach ( $deletions as $ns => $dbKeys ) {
				foreach ( $dbKeys as $dbKey => $unused ) {
					$curDeletionBatch[$ns][$dbKey] = 1;
					if ( ++$curBatchSize >= $bSize ) {
						$deletionBatches[] = $curDeletionBatch;
						$curDeletionBatch = [];
						$curBatchSize = 0;
					}
				}
			}
			if ( $curDeletionBatch ) {
				$deletionBatches[] = $curDeletionBatch;
			}

			foreach ( $deletionBatches as $deletionBatch ) {
				$deleteWheres[] = [
					$fromField => $this->mId,
					$this->getDB()->makeWhereFrom2d( $deletionBatch, $baseKey, "{$prefix}_title" )
				];
			}
		} else {
			if ( $table === 'langlinks' ) {
				$toField = 'll_lang';
			} elseif ( $table === 'page_props' ) {
				$toField = 'pp_propname';
			} else {
				$toField = $prefix . '_to';
			}

			$deletionBatches = array_chunk( array_keys( $deletions ), $bSize );
			foreach ( $deletionBatches as $deletionBatch ) {
				$deleteWheres[] = [ $fromField => $this->mId, $toField => $deletionBatch ];
			}
		}

		$domainId = $this->getDB()->getDomainID();

		foreach ( $deleteWheres as $deleteWhere ) {
			$this->getDB()->delete( $table, $deleteWhere, __METHOD__ );
			$lbf->commitAndWaitForReplication(
				__METHOD__, $this->ticket, [ 'domain' => $domainId ]
			);
		}

		$insertBatches = array_chunk( $insertions, $bSize );
		foreach ( $insertBatches as $insertBatch ) {
			$this->getDB()->insert( $table, $insertBatch, __METHOD__, 'IGNORE' );
			$lbf->commitAndWaitForReplication(
				__METHOD__, $this->ticket, [ 'domain' => $domainId ]
			);
		}

		if ( count( $insertions ) ) {
			Hooks::run( 'LinksUpdateAfterInsert', [ $this, $table, $insertions ] );
		}
	}

	/**
	 * Get an array of pagelinks insertions for passing to the DB
	 * Skips the titles specified by the 2-D array $existing
	 * @param array $existing
	 * @return array
	 */
	private function getLinkInsertions( $existing = [] ) {
		$arr = [];
		foreach ( $this->mLinks as $ns => $dbkeys ) {
			$diffs = isset( $existing[$ns] )
				? array_diff_key( $dbkeys, $existing[$ns] )
				: $dbkeys;
			foreach ( $diffs as $dbk => $id ) {
				$arr[] = [
					'pl_from' => $this->mId,
					'pl_from_namespace' => $this->mTitle->getNamespace(),
					'pl_namespace' => $ns,
					'pl_title' => $dbk
				];
			}
		}

		return $arr;
	}

	/**
	 * Get an array of template insertions. Like getLinkInsertions()
	 * @param array $existing
	 * @return array
	 */
	private function getTemplateInsertions( $existing = [] ) {
		$arr = [];
		foreach ( $this->mTemplates as $ns => $dbkeys ) {
			$diffs = isset( $existing[$ns] ) ? array_diff_key( $dbkeys, $existing[$ns] ) : $dbkeys;
			foreach ( $diffs as $dbk => $id ) {
				$arr[] = [
					'tl_from' => $this->mId,
					'tl_from_namespace' => $this->mTitle->getNamespace(),
					'tl_namespace' => $ns,
					'tl_title' => $dbk
				];
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
	private function getImageInsertions( $existing = [] ) {
		$arr = [];
		$diffs = array_diff_key( $this->mImages, $existing );
		foreach ( $diffs as $iname => $dummy ) {
			$arr[] = [
				'il_from' => $this->mId,
				'il_from_namespace' => $this->mTitle->getNamespace(),
				'il_to' => $iname
			];
		}

		return $arr;
	}

	/**
	 * Get an array of externallinks insertions. Skips the names specified in $existing
	 * @param array $existing
	 * @return array
	 */
	private function getExternalInsertions( $existing = [] ) {
		$arr = [];
		$diffs = array_diff_key( $this->mExternals, $existing );
		foreach ( $diffs as $url => $dummy ) {
			foreach ( wfMakeUrlIndexes( $url ) as $index ) {
				$arr[] = [
					'el_from' => $this->mId,
					'el_to' => $url,
					'el_index' => $index,
					'el_index_60' => substr( $index, 0, 60 ),
				];
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
	private function getCategoryInsertions( $existing = [] ) {
		global $wgCategoryCollation;
		$diffs = array_diff_assoc( $this->mCategories, $existing );
		$arr = [];
		$contLang = MediaWikiServices::getInstance()->getContentLanguage();
		$collation = Collation::singleton();
		foreach ( $diffs as $name => $prefix ) {
			$nt = Title::makeTitleSafe( NS_CATEGORY, $name );
			$contLang->findVariantLink( $name, $nt, true );

			$type = MWNamespace::getCategoryLinkType( $this->mTitle->getNamespace() );

			# Treat custom sortkeys as a prefix, so that if multiple
			# things are forced to sort as '*' or something, they'll
			# sort properly in the category rather than in page_id
			# order or such.
			$sortkey = $collation->getSortKey( $this->mTitle->getCategorySortkey( $prefix ) );

			$arr[] = [
				'cl_from' => $this->mId,
				'cl_to' => $name,
				'cl_sortkey' => $sortkey,
				'cl_timestamp' => $this->getDB()->timestamp(),
				'cl_sortkey_prefix' => $prefix,
				'cl_collation' => $wgCategoryCollation,
				'cl_type' => $type,
			];
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
	private function getInterlangInsertions( $existing = [] ) {
		$diffs = array_diff_assoc( $this->mInterlangs, $existing );
		$arr = [];
		foreach ( $diffs as $lang => $title ) {
			$arr[] = [
				'll_from' => $this->mId,
				'll_lang' => $lang,
				'll_title' => $title
			];
		}

		return $arr;
	}

	/**
	 * Get an array of page property insertions
	 * @param array $existing
	 * @return array
	 */
	function getPropertyInsertions( $existing = [] ) {
		$diffs = array_diff_assoc( $this->mProperties, $existing );

		$arr = [];
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

		$row = [
			'pp_page' => $this->mId,
			'pp_propname' => $prop,
			'pp_value' => $value,
		];

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
	private function getInterwikiInsertions( $existing = [] ) {
		$arr = [];
		foreach ( $this->mInterwikis as $prefix => $dbkeys ) {
			$diffs = isset( $existing[$prefix] )
				? array_diff_key( $dbkeys, $existing[$prefix] )
				: $dbkeys;

			foreach ( $diffs as $dbk => $id ) {
				$arr[] = [
					'iwl_from' => $this->mId,
					'iwl_prefix' => $prefix,
					'iwl_title' => $dbk
				];
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
		$del = [];
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
		$del = [];
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
		$del = [];
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
		$res = $this->getDB()->select( 'pagelinks', [ 'pl_namespace', 'pl_title' ],
			[ 'pl_from' => $this->mId ], __METHOD__ );
		$arr = [];
		foreach ( $res as $row ) {
			if ( !isset( $arr[$row->pl_namespace] ) ) {
				$arr[$row->pl_namespace] = [];
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
		$res = $this->getDB()->select( 'templatelinks', [ 'tl_namespace', 'tl_title' ],
			[ 'tl_from' => $this->mId ], __METHOD__ );
		$arr = [];
		foreach ( $res as $row ) {
			if ( !isset( $arr[$row->tl_namespace] ) ) {
				$arr[$row->tl_namespace] = [];
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
		$res = $this->getDB()->select( 'imagelinks', [ 'il_to' ],
			[ 'il_from' => $this->mId ], __METHOD__ );
		$arr = [];
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
		$res = $this->getDB()->select( 'externallinks', [ 'el_to' ],
			[ 'el_from' => $this->mId ], __METHOD__ );
		$arr = [];
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
		$res = $this->getDB()->select( 'categorylinks', [ 'cl_to', 'cl_sortkey_prefix' ],
			[ 'cl_from' => $this->mId ], __METHOD__ );
		$arr = [];
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
		$res = $this->getDB()->select( 'langlinks', [ 'll_lang', 'll_title' ],
			[ 'll_from' => $this->mId ], __METHOD__ );
		$arr = [];
		foreach ( $res as $row ) {
			$arr[$row->ll_lang] = $row->ll_title;
		}

		return $arr;
	}

	/**
	 * Get an array of existing inline interwiki links, as a 2-D array
	 * @return array (prefix => array(dbkey => 1))
	 */
	private function getExistingInterwikis() {
		$res = $this->getDB()->select( 'iwlinks', [ 'iwl_prefix', 'iwl_title' ],
			[ 'iwl_from' => $this->mId ], __METHOD__ );
		$arr = [];
		foreach ( $res as $row ) {
			if ( !isset( $arr[$row->iwl_prefix] ) ) {
				$arr[$row->iwl_prefix] = [];
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
		$res = $this->getDB()->select( 'page_props', [ 'pp_propname', 'pp_value' ],
			[ 'pp_page' => $this->mId ], __METHOD__ );
		$arr = [];
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
	 * Set the revision corresponding to this LinksUpdate
	 *
	 * @since 1.27
	 *
	 * @param Revision $revision
	 */
	public function setRevision( Revision $revision ) {
		$this->mRevision = $revision;
	}

	/**
	 * @since 1.28
	 * @return null|Revision
	 */
	public function getRevision() {
		return $this->mRevision;
	}

	/**
	 * Set the User who triggered this LinksUpdate
	 *
	 * @since 1.27
	 * @param User $user
	 */
	public function setTriggeringUser( User $user ) {
		$this->user = $user;
	}

	/**
	 * @since 1.27
	 * @return null|User
	 */
	public function getTriggeringUser() {
		return $this->user;
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
					$inv = [ $inv ];
				}
				foreach ( $inv as $table ) {
					DeferredUpdates::addUpdate(
						new HTMLCacheUpdate( $this->mTitle, $table, 'page-props' )
					);
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
		$result = [];
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
		$result = [];
		foreach ( $this->linkDeletions as $ns => $titles ) {
			foreach ( $titles as $title => $unused ) {
				$result[] = Title::makeTitle( $ns, $title );
			}
		}

		return $result;
	}

	/**
	 * Fetch page properties added by this LinksUpdate.
	 * Only available after the update is complete.
	 * @since 1.28
	 * @return null|array
	 */
	public function getAddedProperties() {
		return $this->propertyInsertions;
	}

	/**
	 * Fetch page properties removed by this LinksUpdate.
	 * Only available after the update is complete.
	 * @since 1.28
	 * @return null|array
	 */
	public function getRemovedProperties() {
		return $this->propertyDeletions;
	}

	/**
	 * Update links table freshness
	 */
	private function updateLinksTimestamp() {
		if ( $this->mId ) {
			// The link updates made here only reflect the freshness of the parser output
			$timestamp = $this->mParserOutput->getCacheTime();
			$this->getDB()->update( 'page',
				[ 'page_links_updated' => $this->getDB()->timestamp( $timestamp ) ],
				[ 'page_id' => $this->mId ],
				__METHOD__
			);
		}
	}

	/**
	 * @return IDatabase
	 */
	private function getDB() {
		if ( !$this->db ) {
			$this->db = wfGetDB( DB_MASTER );
		}

		return $this->db;
	}

	public function getAsJobSpecification() {
		if ( $this->user ) {
			$userInfo = [
				'userId' => $this->user->getId(),
				'userName' => $this->user->getName(),
			];
		} else {
			$userInfo = false;
		}

		if ( $this->mRevision ) {
			$triggeringRevisionId = $this->mRevision->getId();
		} else {
			$triggeringRevisionId = false;
		}

		return [
			'wiki' => WikiMap::getWikiIdFromDomain( $this->getDB()->getDomainID() ),
			'job'  => new JobSpecification(
				'refreshLinksPrioritized',
				[
					// Reuse the parser cache if it was saved
					'rootJobTimestamp' => $this->mParserOutput->getCacheTime(),
					'useRecursiveLinksUpdate' => $this->mRecursive,
					'triggeringUser' => $userInfo,
					'triggeringRevisionId' => $triggeringRevisionId,
					'causeAction' => $this->getCauseAction(),
					'causeAgent' => $this->getCauseAgent()
				],
				[ 'removeDuplicates' => true ],
				$this->getTitle()
			)
		];
	}
}
