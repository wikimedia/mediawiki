<?php
/**
 *
 * @addtogroup SpecialPage
 */

/**
 *
 * @addtogroup SpecialPage
 */
class ProtectedPagesForm {
	function showList( $msg = '' ) {
		global $wgOut;

		$wgOut->setPagetitle( wfMsg( "protectedpages" ) );
		if ( "" != $msg ) {
			$wgOut->setSubtitle( $msg );
		}

		// Purge expired entries on one in every 10 queries
		if ( !mt_rand( 0, 10 ) ) {
			Title::purgeExpiredRestrictions();
		}

		$pager = new ProtectedPagesPager( $this );
		$s = $pager->getNavigationBar();

		if ( $pager->getNumRows() ) {
			$s .= "<ul>" . 
				$pager->getBody() .
				"</ul>";
		} else {
			$s .= '<p>' . wfMsgHTML( 'protectedpagesempty' ) . '</p>';
		}
		$s .= $pager->getNavigationBar();
		$wgOut->addHTML( $s );
	}

	/**
	 * Callback function to output a restriction
	 */
	function formatRow( $row ) {
		global $wgUser, $wgLang;

		wfProfileIn( __METHOD__ );

		static $skin=null;

		if( is_null( $skin ) )
			$skin = $wgUser->getSkin();
		
		$title = Title::makeTitleSafe( $row->page_namespace, $row->page_title );
		$link = $skin->makeLinkObj( $title );

		$description_items = array ();

		$protType = wfMsg( 'restriction-level-' . $row->pr_level );

		$description_items[] = $protType;

		$expiry_description = '';

		if ( $row->pr_expiry != 'infinity' && strlen($row->pr_expiry) ) {
			$expiry = Block::decodeExpiry( $row->pr_expiry );
	
			$expiry_description = wfMsgForContent( 'protect-expiring', $wgLang->timeanddate( $expiry ) );

			$description_items[] = $expiry_description;
		}

		wfProfileOut( __METHOD__ );

		return '<li>' . wfSpecialList( $link, implode( $description_items, ', ' ) ) . "</li>\n";
	}
}

/**
 *
 *
 */
class ProtectedPagesPager extends ReverseChronologicalPager {
	public $mForm, $mConds;

	function __construct( $form, $conds = array() ) {
		$this->mForm = $form;
		$this->mConds = $conds;
		parent::__construct();
	}

	function getStartBody() {
		wfProfileIn( __METHOD__ );
		# Do a link batch query
		$this->mResult->seek( 0 );
		$lb = new LinkBatch;

		while ( $row = $this->mResult->fetchObject() ) {
			$name = str_replace( ' ', '_', $row->page_title );
			$lb->add( $row->page_namespace, $name );
		}

		$lb->execute();
		wfProfileOut( __METHOD__ );
		return '';
	}
	
	function formatRow( $row ) {
		$block = new Block;
		return $this->mForm->formatRow( $row );
	}

	function getQueryInfo() {
		$conds = $this->mConds;
		$conds[] = 'pr_expiry>' . $this->mDb->addQuotes( $this->mDb->timestamp() );
		$conds[] = 'page_id=pr_page';
		return array(
			'tables' => array( 'page_restrictions', 'page' ),
			'fields' => 'page_id, ' . $this->mDb->tableName( 'page_restrictions' ) . '.*, page_title,page_namespace',
			'conds' => $conds,
			'options' => array( 'GROUP BY' => 'page_id' ),
		);
	}

	function getIndexField() {
		return 'pr_id';
	}
}

/**
 * Constructor
 */
function wfSpecialProtectedpages() {

	list( $limit, $offset ) = wfCheckLimits();

	$ppForm = new ProtectedPagesForm();

	$ppForm->showList();
}

?>
