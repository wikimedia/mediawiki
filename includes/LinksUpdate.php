<?php
/**
 * See deferred.doc
 * @package MediaWiki
 */

/**
 * @todo document
 * @package MediaWiki
 */
class LinksUpdate {

	/**#@+
	 * @access private
	 */
	var $mId, $mTitle;
	/**#@-*/

	/**
	 * Constructor
	 * Initialize private variables
	 * @param integer $id
	 * @param string $title
	 */
	function LinksUpdate( $id, $title ) {
		$this->mId = $id;
		$this->mTitle = $title;
	}

	/**
	 * Update link tables with outgoing links from an updated article
	 * Relies on the 'link cache' to be filled out.
	 */
	
	function doUpdate() {
		global $wgUseBetterLinksUpdate, $wgLinkCache, $wgDBtransactions;
		global $wgEnablePersistentLC, $wgUseCategoryMagic;

		$fname = 'LinksUpdate::doUpdate';
		wfProfileIn( $fname );

		$del = array();
		$add = array();

		$dbw =& wfGetDB( DB_MASTER );
		$links = $dbw->tableName( 'links' );
		$brokenlinks = $dbw->tableName( 'brokenlinks' );
		$imagelinks = $dbw->tableName( 'imagelinks' );
		$categorylinks = $dbw->tableName( 'categorylinks' );
		
		#------------------------------------------------------------------------------
		# Good links

		if ( $wgLinkCache->incrementalSetup( LINKCACHE_GOOD, $del, $add ) ) {
			# Delete where necessary
			if ( count( $del ) ) {
				$sql = "DELETE FROM $links WHERE l_from={$this->mId} AND l_to IN(".
					implode( ',', $del ) . ')';
				$dbw->query( $sql, $fname );
			}
		} else {
			# Delete everything
			$dbw->delete( 'links', array( 'l_from' => $this->mId ), $fname );
						
			# Get the addition list
			$add = $wgLinkCache->getGoodLinks();
		}

		# Do the insertion
		if ( 0 != count( $add ) ) {
			$arr=array();
			foreach($add as $lt=>$lid) 
				array_push( $arr, array(
							'l_from' => $this->mId,
							'l_to'   => $lid ) );
			# The link cache was constructed without FOR UPDATE, so there may be collisions
			# Ignoring for now, I'm not sure if that causes problems or not, but I'm fairly
			# sure it's better than without IGNORE
			$dbw->insert( 'links', $arr, $fname, array( 'IGNORE' ) );
		}

		#------------------------------------------------------------------------------
		# Bad links

		if ( $wgLinkCache->incrementalSetup( LINKCACHE_BAD, $del, $add ) ) {
			# Delete where necessary
			if ( count( $del ) ) {
				$sql = "DELETE FROM $brokenlinks WHERE bl_from={$this->mId} AND bl_to IN(";
				$first = true;
				foreach( $del as $badTitle ) {
					if ( $first ) {
						$first = false;
					} else {
						$sql .= ',';
					}
					$sql .= $dbw->addQuotes( $badTitle );
				}
				$sql .= ')';
				$dbw->query( $sql, $fname );
			}
		} else {
			# Delete all
			$dbw->delete( 'brokenlinks', array( 'bl_from' => $this->mId ) );
			
			# Get addition list
			$add = $wgLinkCache->getBadLinks();
		}

		# Do additions
		$sql = '';
		if ( 0 != count ( $add ) ) {
			$arr = array();
			foreach( $add as $blt ) {
				array_push( $arr, array(
					'bl_from' => $this->mId,
					'bl_to'   => $blt ) );
			}
			$dbw->insert( 'brokenlinks', $arr, $fname, array( 'IGNORE' ) );
		}

		#------------------------------------------------------------------------------
		# Image links
		$sql = "DELETE FROM $imagelinks WHERE il_from='{$this->mId}'";
		$dbw->query( $sql, $fname );
		
		# Get addition list
		$add = $wgLinkCache->getImageLinks();
		
		# Do the insertion
		$sql = '';
		$image = NS_IMAGE;
		if ( 0 != count ( $add ) ) {
			$arr = array();
			foreach ($add as $iname => $val ) {
				$nt = Title::makeTitle( $image, $iname );
				if( !$nt ) continue;
				$nt->invalidateCache();
				array_push( $arr, array(
					'il_from' => $this->mId,
					'il_to'   => $iname ) );
			}
			$dbw->insert('imagelinks', $arr, $fname, array('IGNORE'));
		}

		#------------------------------------------------------------------------------
		# Category links
		if( $wgUseCategoryMagic ) {
			$sql = "DELETE FROM $categorylinks WHERE cl_from='{$this->mId}'";
			$dbw->query( $sql, $fname );
			
			# Get addition list
			$add = $wgLinkCache->getCategoryLinks();
			
			# Do the insertion
			$sql = '';
			if ( 0 != count ( $add ) ) {
				$arr = array();
				foreach( $add as $cname => $sortkey ) {
					$nt = Title::makeTitle( NS_CATEGORY, $cname );
					if( !$nt ) continue;
					$nt->invalidateCache();
					array_push( $arr, array(
						'cl_from'    => $this->mId,
						'cl_to'      => $cname,
						'cl_sortkey' => $sortkey ) );
				}
				$dbw->insert( 'categorylinks', $arr, $fname, array( 'IGNORE' ) );
			}
		}
		
		$this->fixBrokenLinks();

		wfProfileOut( $fname );
	}

	/**
	  * Link update which clears the previous entries and inserts new ones
	  * May be slower or faster depending on level of link contention and write speed of DB
	  * Also useful where link table corruption needs to be repaired, e.g. in refreshLinks.php
	 */
	function doDumbUpdate() {
		global $wgLinkCache, $wgDBtransactions, $wgUseCategoryMagic;
		$fname = 'LinksUpdate::doDumbUpdate';
		wfProfileIn( $fname );
		
		
		$dbw =& wfGetDB( DB_MASTER );
		$links = $dbw->tableName( 'links' );
		$brokenlinks = $dbw->tableName( 'brokenlinks' );
		$imagelinks = $dbw->tableName( 'imagelinks' );
		$categorylinks = $dbw->tableName( 'categorylinks' );
		
		$sql = "DELETE FROM $links WHERE l_from={$this->mId}";
		$dbw->query( $sql, $fname );

		$a = $wgLinkCache->getGoodLinks();
		if ( 0 != count( $a ) ) {
			$arr = array();
			foreach( $a as $lt => $lid ) {
				array_push( $arr, array(
					'l_from' => $this->mId,
					'l_to'   => $lid ) );
			}
			$dbw->insert( 'links', $arr, $fname, array( 'IGNORE' ) );
		}

		$sql = "DELETE FROM $brokenlinks WHERE bl_from={$this->mId}";
		$dbw->query( $sql, $fname );

		$a = $wgLinkCache->getBadLinks();
		if ( 0 != count ( $a ) ) {
			$arr = array();
			foreach( $a as $blt ) {
				array_push($arr,array(
					'bl_from' => $this->mId,
					'bl_to'   => $blt));
			}
			$dbw->insert( 'brokenlinks', $arr, $fname, array( 'IGNORE' ) );
		}
		
		$sql = "DELETE FROM $imagelinks WHERE il_from={$this->mId}";
		$dbw->query( $sql, $fname );

		$a = $wgLinkCache->getImageLinks();
		$sql = '';
		if ( 0 != count ( $a ) ) {
			$arr = array();
			foreach( $a as $iname => $val )
				array_push( $arr, array(
					'il_from' => $this->mId,
					'il_to'   => $iname ) );
			$dbw->insert( 'imagelinks', $arr, $fname, array( 'IGNORE' ) );
		}

		if( $wgUseCategoryMagic ) {
			$sql = "DELETE FROM $categorylinks WHERE cl_from='{$this->mId}'";
			$dbw->query( $sql, $fname );
			
			# Get addition list
			$add = $wgLinkCache->getCategoryLinks();
			
			# Do the insertion
			$sql = '';
			if ( 0 != count ( $add ) ) {
				$arr = array();
				foreach( $add as $cname => $sortkey ) {
					# FIXME: Change all this to avoid unnecessary duplication
					$nt = Title::makeTitle( NS_CATEGORY, $cname );
                                        if( !$nt ) continue;
                                        $nt->invalidateCache();
					array_push( $arr, array(
						'cl_from'    => $this->mId,
						'cl_to'      => $cname,
						'cl_sortkey' => $sortkey ) );
				}
				$dbw->insert( 'categorylinks', $arr, $fname, array( 'IGNORE' ) );
			}
		}
		$this->fixBrokenLinks();
		wfProfileOut( $fname );
	}

	/**
	 * Update any brokenlinks *to* this page
	 * Call for a newly created page, or just to make sure state is consistent
	 */
	function fixBrokenLinks() {
		$fname = 'LinksUpdate::fixBrokenLinks';

		$dbw =& wfGetDB( DB_MASTER );
		$page = $dbw->tableName( 'page' );
		$links = $dbw->tableName( 'links' );
		
		$res = $dbw->select( 'brokenlinks', array( 'bl_from' ), array( 'bl_to' => $this->mTitle ), 
		  $fname, 'FOR UPDATE' );
		if ( 0 == $dbw->numRows( $res ) ) { return; }

		$arr=array();
		$now = $dbw->timestamp();
		$sql2 = "UPDATE $page SET page_touched='{$now}' WHERE page_id IN (";
		$first = true;
		while ( $row = $dbw->fetchObject( $res ) ) {
			if ( ! $first ) { $sql2 .= ","; }
			$first = false;
			array_push( $arr, array(
				'l_from' => $row->bl_from,
				'l_to'   => $this->mId ) );
			$sql2 .= $row->bl_from;
		}
		$sql2 .= ')';
		
		# Ignore errors. If a link existed in both the brokenlinks table and the links 
		# table, that's an error which can be fixed at this stage by simply ignoring collisions
		$dbw->insert( 'links', $arr, $fname, array( 'IGNORE' ) );
		$dbw->query( $sql2, $fname );
		$dbw->delete( 'brokenlinks', array( 'bl_to' => $this->mTitle ), $fname );
	}
}
?>