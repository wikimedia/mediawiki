<?php
/**
 *
 */

/**
 *
 */
class WatchedItem {
	var $mTitle, $mUser;

	# Create a WatchedItem object with the given user and title
	/* static */ function &fromUserTitle( &$user, &$title ) {
		$wl = new WatchedItem;
		$wl->mUser =& $user;
		$wl->mTitle =& $title;
		$wl->id = $user->getId();
		$wl->ns = $title->getNamespace() & ~1;
		$wl->ti = $title->getDBkey();
		return $wl;
	}

	/**
	 * Returns the memcached key for this item
	 */
	function watchKey() {
		global $wgDBname;
		return "$wgDBname:watchlist:user:$this->id:page:$this->ns:$this->ti";
	}
	
	/**
	 * Is mTitle being watched by mUser?
	 */
	function isWatched()
	{
		# Pages and their talk pages are considered equivalent for watching;
		# remember that talk namespaces are numbered as page namespace+1.
		global $wgMemc;
		$fname = 'WatchedItem::isWatched';

		$key = $this->watchKey();
		$iswatched = $wgMemc->get( $key );
		if( is_integer( $iswatched ) ) return $iswatched;
		
		$dbr =& wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'watchlist', 1, array( 'wl_user' => $this->id, 'wl_namespace' => $this->ns, 
			'wl_title' => $this->ti ), $fname );
		$iswatched = ($dbr->numRows( $res ) > 0) ? 1 : 0;
		$wgMemc->set( $key, $iswatched );
		return $iswatched;
	}

	function addWatch() {
		$fname = "WatchedItem::addWatch";
		# REPLACE instead of INSERT because occasionally someone
		# accidentally reloads a watch-add operation.
		$dbw =& wfGetDB( DB_MASTER );
		$dbw->replace( 'watchlist', array(array('wl_user', 'wl_namespace', 'wl_title')),
		  array( 
		    'wl_user' => $this->id,
			'wl_namespace' => $this->ns,
			'wl_title' => $this->ti,
		  ), $fname );

		global $wgMemc;
		$wgMemc->set( $this->watchkey(), 1 );
		return true;
	}

	function removeWatch() {
		$fname = 'WatchedItem::removeWatch';

		$dbw =& wfGetDB( DB_MASTER );
		$dbw->delete( 'watchlist', 
			array( 
				'wl_user' => $this->id, 
				'wl_namespace' => $this->ns,  
				'wl_title' => $this->ti
			), $fname
		);
		
		if ( $dbw->affectedRows() ) {
			global $wgMemc;
			$wgMemc->set( $this->watchkey(), 0 );
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @static
	 */
	function duplicateEntries( $ot, $nt ) {
		$fname = "WatchedItem::duplicateEntries";
		global $wgMemc, $wgDBname;
		$oldnamespace = $ot->getNamespace() & ~1;
		$newnamespace = $nt->getNamespace() & ~1;
		$oldtitle = $ot->getDBkey();
		$newtitle = $nt->getDBkey();

		$dbw =& wfGetDB( DB_MASTER );
		$watchlist = $dbw->tableName( 'watchlist' );
		
		$res = $dbw->select( 'watchlist', 'wl_user', 
			array( 'wl_namespace' => $oldnamespace, 'wl_title' => $oldtitle ),
			$fname, 'FOR UPDATE'
		);
		# Construct array to replace into the watchlist
		$values = array();
		while ( $s = $dbw->fetchObject( $res ) ) {
			$values[] = array(
				'wl_user' => $s->wl_user,
				'wl_namespace' => $newnamespace,
				'wl_title' => $newtitle
			);
		}
		$dbw->freeResult( $res );

		# Perform replace
		# Note that multi-row replace is very efficient for MySQL but may be inefficient for
		# some other DBMSes, mostly due to poor simulation by us
		$dbw->replace( 'watchlist', array(array( 'wl_user', 'wl_namespace', 'wl_title')), $values, $fname );
		return true;
	}


}

?>
