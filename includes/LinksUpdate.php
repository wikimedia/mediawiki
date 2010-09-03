<?php
/**
 * See docs/deferred.txt
 *
 * @todo document (e.g. one-sentence top-level class description).
 */
class LinksUpdate {

	/**@{{
	 * @private
	 */
	var $mId,            //!< Page ID of the article linked from
		$mTitle,         //!< Title object of the article linked from
		$mLinks,         //!< Map of title strings to IDs for the links in the document
		$mImages,        //!< DB keys of the images used, in the array key only
		$mTemplates,     //!< Map of title strings to IDs for the template references, including broken ones
		$mExternals,     //!< URLs of external links, array key only
		$mCategories,    //!< Map of category names to sort keys
		$mInterlangs,    //!< Map of language codes to titles
		$mProperties,    //!< Map of arbitrary name to value
		$mDb,            //!< Database connection reference
		$mOptions,       //!< SELECT options to be used (array)
		$mRecursive;     //!< Whether to queue jobs for recursive updates
	/**@}}*/

	/**
	 * Constructor
	 *
	 * @param $title Title of the page we're updating
	 * @param $parserOutput ParserOutput: output from a full parse of this page
	 * @param $recursive Boolean: queue jobs for recursive updates?
	 */
	function __construct( $title, $parserOutput, $recursive = true ) {
		global $wgAntiLockFlags;

		if ( $wgAntiLockFlags & ALF_NO_LINK_LOCK ) {
			$this->mOptions = array();
		} else {
			$this->mOptions = array( 'FOR UPDATE' );
		}
		$this->mDb = wfGetDB( DB_MASTER );

		if ( !is_object( $title ) ) {
			throw new MWException( "The calling convention to LinksUpdate::LinksUpdate() has changed. " .
				"Please see Article::editUpdates() for an invocation example.\n" );
		}
		$this->mTitle = $title;
		$this->mId = $title->getArticleID();

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

		$this->mRecursive = $recursive;

		wfRunHooks( 'LinksUpdateConstructed', array( &$this ) );
	}

	/**
	 * Update link tables with outgoing links from an updated article
	 */
	public function doUpdate() {
		global $wgUseDumbLinkUpdate;

		wfRunHooks( 'LinksUpdate', array( &$this ) );
		if ( $wgUseDumbLinkUpdate ) {
			$this->doDumbUpdate();
		} else {
			$this->doIncrementalUpdate();
		}
		wfRunHooks( 'LinksUpdateComplete', array( &$this ) );
	}

	protected function doIncrementalUpdate() {
		wfProfileIn( __METHOD__ );

		# Page links
		$existing = $this->getExistingLinks();
		$this->incrTableUpdate( 'pagelinks', 'pl', $this->getLinkDeletions( $existing ),
			$this->getLinkInsertions( $existing ) );

		# Image links
		$existing = $this->getExistingImages();

		$imageDeletes = $this->getImageDeletions( $existing );
		$this->incrTableUpdate( 'imagelinks', 'il', $imageDeletes, $this->getImageInsertions( $existing ) );

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

		$this->incrTableUpdate( 'categorylinks', 'cl', $categoryDeletes, $this->getCategoryInsertions( $existing ) );

		# Invalidate all categories which were added, deleted or changed (set symmetric difference)
		$categoryInserts = array_diff_assoc( $this->mCategories, $existing );
		$categoryUpdates = $categoryInserts + $categoryDeletes;
		$this->invalidateCategories( $categoryUpdates );
		$this->updateCategoryCounts( $categoryInserts, $categoryDeletes );

		# Page properties
		$existing = $this->getExistingProperties();

		$propertiesDeletes = $this->getPropertyDeletions( $existing );

		$this->incrTableUpdate( 'page_props', 'pp', $propertiesDeletes, $this->getPropertyInsertions( $existing ) );

		# Invalidate the necessary pages
		$changed = $propertiesDeletes + array_diff_assoc( $this->mProperties, $existing );
		$this->invalidateProperties( $changed );

		# Refresh links of all pages including this page
		# This will be in a separate transaction
		if ( $this->mRecursive ) {
			$this->queueRecursiveJobs();
		}

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Link update which clears the previous entries and inserts new ones
	 * May be slower or faster depending on level of lock contention and write speed of DB
	 * Also useful where link table corruption needs to be repaired, e.g. in refreshLinks.php
	 */
	protected function doDumbUpdate() {
		wfProfileIn( __METHOD__ );

		# Refresh category pages and image description pages
		$existing = $this->getExistingCategories();
		$categoryInserts = array_diff_assoc( $this->mCategories, $existing );
		$categoryDeletes = array_diff_assoc( $existing, $this->mCategories );
		$categoryUpdates = $categoryInserts + $categoryDeletes;
		$existing = $this->getExistingImages();
		$imageUpdates = array_diff_key( $existing, $this->mImages ) + array_diff_key( $this->mImages, $existing );

		$this->dumbTableUpdate( 'pagelinks',     $this->getLinkInsertions(),     'pl_from' );
		$this->dumbTableUpdate( 'imagelinks',    $this->getImageInsertions(),    'il_from' );
		$this->dumbTableUpdate( 'categorylinks', $this->getCategoryInsertions(), 'cl_from' );
		$this->dumbTableUpdate( 'templatelinks', $this->getTemplateInsertions(), 'tl_from' );
		$this->dumbTableUpdate( 'externallinks', $this->getExternalInsertions(), 'el_from' );
		$this->dumbTableUpdate( 'langlinks',     $this->getInterlangInsertions(),'ll_from' );
		$this->dumbTableUpdate( 'iwlinks',       $this->getInterwikiInsertions(),'iwl_from' );
		$this->dumbTableUpdate( 'page_props',    $this->getPropertyInsertions(), 'pp_page' );

		# Update the cache of all the category pages and image description
		# pages which were changed, and fix the category table count
		$this->invalidateCategories( $categoryUpdates );
		$this->updateCategoryCounts( $categoryInserts, $categoryDeletes );
		$this->invalidateImageDescriptions( $imageUpdates );

		# Refresh links of all pages including this page
		# This will be in a separate transaction
		if ( $this->mRecursive ) {
			$this->queueRecursiveJobs();
		}

		wfProfileOut( __METHOD__ );
	}

	function queueRecursiveJobs() {
		global $wgUpdateRowsPerJob;
		wfProfileIn( __METHOD__ );

		$cache = $this->mTitle->getBacklinkCache();
		$batches = $cache->partition( 'templatelinks', $wgUpdateRowsPerJob );
		if ( !$batches ) {
			wfProfileOut( __METHOD__ );
			return;
		}
		$jobs = array();
		foreach ( $batches as $batch ) {
			list( $start, $end ) = $batch;
			$params = array(
				'start' => $start,
				'end' => $end,
			);
			$jobs[] = new RefreshLinksJob2( $this->mTitle, $params );
		}
		Job::batchInsert( $jobs );

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Invalidate the cache of a list of pages from a single namespace
	 *
	 * @param $namespace Integer
	 * @param $dbkeys Array
	 */
	function invalidatePages( $namespace, $dbkeys ) {
		if ( !count( $dbkeys ) ) {
			return;
		}

		/**
		 * Determine which pages need to be updated
		 * This is necessary to prevent the job queue from smashing the DB with
		 * large numbers of concurrent invalidations of the same page
		 */
		$now = $this->mDb->timestamp();
		$ids = array();
		$res = $this->mDb->select( 'page', array( 'page_id' ),
			array(
				'page_namespace' => $namespace,
				'page_title IN (' . $this->mDb->makeList( $dbkeys ) . ')',
				'page_touched < ' . $this->mDb->addQuotes( $now )
			), __METHOD__
		);
		while ( $row = $this->mDb->fetchObject( $res ) ) {
			$ids[] = $row->page_id;
		}
		if ( !count( $ids ) ) {
			return;
		}

		/**
		 * Do the update
		 * We still need the page_touched condition, in case the row has changed since
		 * the non-locking select above.
		 */
		$this->mDb->update( 'page', array( 'page_touched' => $now ),
			array(
				'page_id IN (' . $this->mDb->makeList( $ids ) . ')',
				'page_touched < ' . $this->mDb->addQuotes( $now )
			), __METHOD__
		);
	}

	function invalidateCategories( $cats ) {
		$this->invalidatePages( NS_CATEGORY, array_keys( $cats ) );
	}

	/**
	 * Update all the appropriate counts in the category table.
	 * @param $added associative array of category name => sort key
	 * @param $deleted associative array of category name => sort key
	 */
	function updateCategoryCounts( $added, $deleted ) {
		$a = new Article($this->mTitle);
		$a->updateCategoryCounts(
			array_keys( $added ), array_keys( $deleted )
		);
	}

	function invalidateImageDescriptions( $images ) {
		$this->invalidatePages( NS_FILE, array_keys( $images ) );
	}

	function dumbTableUpdate( $table, $insertions, $fromField ) {
		$this->mDb->delete( $table, array( $fromField => $this->mId ), __METHOD__ );
		if ( count( $insertions ) ) {
			# The link array was constructed without FOR UPDATE, so there may
			# be collisions.  This may cause minor link table inconsistencies,
			# which is better than crippling the site with lock contention.
			$this->mDb->insert( $table, $insertions, __METHOD__, array( 'IGNORE' ) );
		}
	}

	/**
	 * Update a table by doing a delete query then an insert query
	 * @private
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
				$where[] = "$toField IN (" . $this->mDb->makeList( array_keys( $deletions ) ) . ')';
			} else {
				$where = false;
			}
		}
		if ( $where ) {
			$this->mDb->delete( $table, $where, __METHOD__ );
		}
		if ( count( $insertions ) ) {
			$this->mDb->insert( $table, $insertions, __METHOD__, 'IGNORE' );
		}
	}


	/**
	 * Get an array of pagelinks insertions for passing to the DB
	 * Skips the titles specified by the 2-D array $existing
	 * @private
	 */
	function getLinkInsertions( $existing = array() ) {
		$arr = array();
		foreach( $this->mLinks as $ns => $dbkeys ) {
			# array_diff_key() was introduced in PHP 5.1, there is a compatibility function
			# in GlobalFunctions.php
			$diffs = isset( $existing[$ns] ) ? array_diff_key( $dbkeys, $existing[$ns] ) : $dbkeys;
			foreach ( $diffs as $dbk => $id ) {
				$arr[] = array(
					'pl_from'      => $this->mId,
					'pl_namespace' => $ns,
					'pl_title'     => $dbk
				);
			}
		}
		return $arr;
	}

	/**
	 * Get an array of template insertions. Like getLinkInsertions()
	 * @private
	 */
	function getTemplateInsertions( $existing = array() ) {
		$arr = array();
		foreach( $this->mTemplates as $ns => $dbkeys ) {
			$diffs = isset( $existing[$ns] ) ? array_diff_key( $dbkeys, $existing[$ns] ) : $dbkeys;
			foreach ( $diffs as $dbk => $id ) {
				$arr[] = array(
					'tl_from'      => $this->mId,
					'tl_namespace' => $ns,
					'tl_title'     => $dbk
				);
			}
		}
		return $arr;
	}

	/**
	 * Get an array of image insertions
	 * Skips the names specified in $existing
	 * @private
	 */
	function getImageInsertions( $existing = array() ) {
		$arr = array();
		$diffs = array_diff_key( $this->mImages, $existing );
		foreach( $diffs as $iname => $dummy ) {
			$arr[] = array(
				'il_from' => $this->mId,
				'il_to'   => $iname
			);
		}
		return $arr;
	}

	/**
	 * Get an array of externallinks insertions. Skips the names specified in $existing
	 * @private
	 */
	function getExternalInsertions( $existing = array() ) {
		$arr = array();
		$diffs = array_diff_key( $this->mExternals, $existing );
		foreach( $diffs as $url => $dummy ) {
			$arr[] = array(
				'el_from'   => $this->mId,
				'el_to'     => $url,
				'el_index'  => wfMakeUrlIndex( $url ),
			);
		}
		return $arr;
	}

	/**
	 * Get an array of category insertions
	 *
	 * @param $existing Array mapping existing category names to sort keys. If both
	 * match a link in $this, the link will be omitted from the output
	 * @private
	 */
	function getCategoryInsertions( $existing = array() ) {
		global $wgContLang, $wgCategoryCollation;
		$diffs = array_diff_assoc( $this->mCategories, $existing );
		$arr = array();
		foreach ( $diffs as $name => $sortkey ) {
			$nt = Title::makeTitleSafe( NS_CATEGORY, $name );
			$wgContLang->findVariantLink( $name, $nt, true );

			if ( $this->mTitle->getNamespace() == NS_CATEGORY ) {
				$type = 'subcat';
			} elseif ( $this->mTitle->getNamespace() == NS_FILE ) {
				$type = 'file';
			} else {
				$type = 'page';
			}

			# TODO: This is kind of wrong, because someone might set a sort
			# key prefix that's the same as the default sortkey for the
			# title.  This should be fixed by refactoring code to replace
			# $sortkey in this array by a prefix, but it's basically harmless
			# (Title::moveTo() has had the same issue for a long time).
			if ( $this->mTitle->getCategorySortkey() == $sortkey ) {
				$prefix = '';
				$sortkey = $wgContLang->convertToSortkey( $sortkey );
			} else {
				# Treat custom sortkeys as a prefix, so that if multiple
				# things are forced to sort as '*' or something, they'll
				# sort properly in the category rather than in page_id
				# order or such.
				$prefix = $sortkey;
				$sortkey = $wgContLang->convertToSortkey(
					$this->mTitle->getCategorySortkey( $prefix ) );
			}

			$arr[] = array(
				'cl_from'    => $this->mId,
				'cl_to'      => $name,
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
	 * @param $existing Array mapping existing language codes to titles
	 * @private
	 */
	function getInterlangInsertions( $existing = array() ) {
	    $diffs = array_diff_assoc( $this->mInterlangs, $existing );
	    $arr = array();
	    foreach( $diffs as $lang => $title ) {
	        $arr[] = array(
	            'll_from'  => $this->mId,
	            'll_lang'  => $lang,
	            'll_title' => $title
	        );
	    }
	    return $arr;
	}

	/**
	 * Get an array of page property insertions
	 */
	function getPropertyInsertions( $existing = array() ) {
		$diffs = array_diff_assoc( $this->mProperties, $existing );
		$arr = array();
		foreach ( $diffs as $name => $value ) {
			$arr[] = array(
				'pp_page'      => $this->mId,
				'pp_propname'  => $name,
				'pp_value'     => $value,
			);
		}
		return $arr;
	}

	/**
	 * Get an array of interwiki insertions for passing to the DB
	 * Skips the titles specified by the 2-D array $existing
	 * @private
	 */
	function getInterwikiInsertions( $existing = array() ) {
		$arr = array();
		foreach( $this->mInterwikis as $prefix => $dbkeys ) {
			# array_diff_key() was introduced in PHP 5.1, there is a compatibility function
			# in GlobalFunctions.php
			$diffs = isset( $existing[$prefix] ) ? array_diff_key( $dbkeys, $existing[$prefix] ) : $dbkeys;
			foreach ( $diffs as $dbk => $id ) {
				$arr[] = array(
					'iwl_from'   => $this->mId,
					'iwl_prefix' => $prefix,
					'iwl_title'  => $dbk
				);
			}
		}
		return $arr;
	}



	/**
	 * Given an array of existing links, returns those links which are not in $this
	 * and thus should be deleted.
	 * @private
	 */
	function getLinkDeletions( $existing ) {
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
	 * @private
	 */
	function getTemplateDeletions( $existing ) {
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
	 * @private
	 */
	function getImageDeletions( $existing ) {
		return array_diff_key( $existing, $this->mImages );
	}

	/**
	 * Given an array of existing external links, returns those links which are not
	 * in $this and thus should be deleted.
	 * @private
	 */
	function getExternalDeletions( $existing ) {
		return array_diff_key( $existing, $this->mExternals );
	}

	/**
	 * Given an array of existing categories, returns those categories which are not in $this
	 * and thus should be deleted.
	 * @private
	 */
	function getCategoryDeletions( $existing ) {
		return array_diff_assoc( $existing, $this->mCategories );
	}

	/**
	 * Given an array of existing interlanguage links, returns those links which are not
	 * in $this and thus should be deleted.
	 * @private
	 */
	function getInterlangDeletions( $existing ) {
	    return array_diff_assoc( $existing, $this->mInterlangs );
	}

	/**
	 * Get array of properties which should be deleted.
	 * @private
	 */
	function getPropertyDeletions( $existing ) {
		return array_diff_assoc( $existing, $this->mProperties );
	}

	/**
	 * Given an array of existing interwiki links, returns those links which are not in $this
	 * and thus should be deleted.
	 * @private
	 */
	function getInterwikiDeletions( $existing ) {
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
	 * @private
	 */
	function getExistingLinks() {
		$res = $this->mDb->select( 'pagelinks', array( 'pl_namespace', 'pl_title' ),
			array( 'pl_from' => $this->mId ), __METHOD__, $this->mOptions );
		$arr = array();
		while ( $row = $this->mDb->fetchObject( $res ) ) {
			if ( !isset( $arr[$row->pl_namespace] ) ) {
				$arr[$row->pl_namespace] = array();
			}
			$arr[$row->pl_namespace][$row->pl_title] = 1;
		}
		return $arr;
	}

	/**
	 * Get an array of existing templates, as a 2-D array
	 * @private
	 */
	function getExistingTemplates() {
		$res = $this->mDb->select( 'templatelinks', array( 'tl_namespace', 'tl_title' ),
			array( 'tl_from' => $this->mId ), __METHOD__, $this->mOptions );
		$arr = array();
		while ( $row = $this->mDb->fetchObject( $res ) ) {
			if ( !isset( $arr[$row->tl_namespace] ) ) {
				$arr[$row->tl_namespace] = array();
			}
			$arr[$row->tl_namespace][$row->tl_title] = 1;
		}
		return $arr;
	}

	/**
	 * Get an array of existing images, image names in the keys
	 * @private
	 */
	function getExistingImages() {
		$res = $this->mDb->select( 'imagelinks', array( 'il_to' ),
			array( 'il_from' => $this->mId ), __METHOD__, $this->mOptions );
		$arr = array();
		while ( $row = $this->mDb->fetchObject( $res ) ) {
			$arr[$row->il_to] = 1;
		}
		return $arr;
	}

	/**
	 * Get an array of existing external links, URLs in the keys
	 * @private
	 */
	function getExistingExternals() {
		$res = $this->mDb->select( 'externallinks', array( 'el_to' ),
			array( 'el_from' => $this->mId ), __METHOD__, $this->mOptions );
		$arr = array();
		while ( $row = $this->mDb->fetchObject( $res ) ) {
			$arr[$row->el_to] = 1;
		}
		return $arr;
	}

	/**
	 * Get an array of existing categories, with the name in the key and sort key in the value.
	 * @private
	 */
	function getExistingCategories() {
		$res = $this->mDb->select( 'categorylinks', array( 'cl_to', 'cl_sortkey' ),
			array( 'cl_from' => $this->mId ), __METHOD__, $this->mOptions );
		$arr = array();
		while ( $row = $this->mDb->fetchObject( $res ) ) {
			$arr[$row->cl_to] = $row->cl_sortkey;
		}
		return $arr;
	}

	/**
	 * Get an array of existing interlanguage links, with the language code in the key and the
	 * title in the value.
	 * @private
	 */
	function getExistingInterlangs() {
		$res = $this->mDb->select( 'langlinks', array( 'll_lang', 'll_title' ),
			array( 'll_from' => $this->mId ), __METHOD__, $this->mOptions );
		$arr = array();
		while ( $row = $this->mDb->fetchObject( $res ) ) {
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
		while ( $row = $this->mDb->fetchObject( $res ) ) {
			if ( !isset( $arr[$row->iwl_prefix] ) ) {
				$arr[$row->iwl_prefix] = array();
			}
			$arr[$row->iwl_prefix][$row->iwl_title] = 1;
		}
		return $arr;
	}

	/**
	 * Get an array of existing categories, with the name in the key and sort key in the value.
	 * @private
	 */
	function getExistingProperties() {
		$res = $this->mDb->select( 'page_props', array( 'pp_propname', 'pp_value' ),
			array( 'pp_page' => $this->mId ), __METHOD__, $this->mOptions );
		$arr = array();
		while ( $row = $this->mDb->fetchObject( $res ) ) {
			$arr[$row->pp_propname] = $row->pp_value;
		}
		return $arr;
	}


	/**
	 * Return the title object of the page being updated
	 */
	function getTitle() {
		return $this->mTitle;
	}
	
	/**
	 * Return the list of images used as generated by the parser
	 */
	public function getImages() {
		return $this->mImages;
	}

	/**
	 * Invalidate any necessary link lists related to page property changes
	 */
	function invalidateProperties( $changed ) {
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
}
