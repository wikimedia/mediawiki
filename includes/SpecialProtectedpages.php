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
		global $wgOut, $wgRequest;

		$wgOut->setPagetitle( wfMsg( "protectedpages" ) );
		if ( "" != $msg ) {
			$wgOut->setSubtitle( $msg );
		}

		// Purge expired entries on one in every 10 queries
		if ( !mt_rand( 0, 10 ) ) {
			Title::purgeExpiredRestrictions();
		}

		$type = $wgRequest->getVal( 'type' );
		$level = $wgRequest->getVal( 'level' );
		$NS = $wgRequest->getIntOrNull( 'namespace' );

		$pager = new ProtectedPagesPager( $this, array(), $type, $level, $NS );	

		$wgOut->addHTML( $this->showOptions( $NS, $type, $level ) );

		if ( $pager->getNumRows() ) {
			$s = $pager->getNavigationBar();
			$s .= "<ul>" . 
				$pager->getBody() .
				"</ul>";
			$s .= $pager->getNavigationBar();
		} else {
			$s = '<p>' . wfMsgHTML( 'protectedpagesempty' ) . '</p>';
		}
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

		$protType = wfMsgHtml( 'restriction-level-' . $row->pr_level );

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
	
	/**
	 * @param $namespace int
	 * @param $type string
	 * @param $level string
	 * @private
	 */
	function showOptions( $namespace, $type, $level ) {
		global $wgScript;
		$action = htmlspecialchars( $wgScript );
		$title = SpecialPage::getTitleFor( 'ProtectedPages' );
		$special = htmlspecialchars( $title->getPrefixedDBkey() );
		return "<form action=\"$action\" method=\"get\">\n" .
			'<fieldset>' .
			Xml::element( 'legend', array(), wfMsg( 'protectedpages' ) ) .
			Xml::hidden( 'title', $special ) . "\n" .
			$this->getNamespaceMenu( $namespace ) . "\n" .
			$this->getTypeMenu( $type ) . "\n" .
			$this->getLevelMenu( $level ) . "\n" .
			Xml::submitButton( wfMsg( 'allpagessubmit' ) ) . "\n" .
			"</fieldset></form>";
	}
	
	function getNamespaceMenu( $namespace=NULL ) {
		return "<label for='namespace'>" . wfMsgHtml('namespace') . "</label>" . HTMLnamespaceselector($namespace, '');
	}
	
	/**
	 * @return string Formatted HTML
	 * @private
	 */
	function getTypeMenu( $pr_type ) {
		global $wgRestrictionTypes, $wgUser;
	
		$out = "<select name='type'>\n";
		$m = array(); // Temporary array

		// First pass to load the log names
		foreach( $wgRestrictionTypes as $type ) {
			$text = wfMsgHtml("restriction-$type");
			$m[$text] = $type;
		}

		// Second pass to sort by name
		ksort($m);

		// Third pass generates sorted XHTML content
		foreach( $m as $text => $type ) {
			$selected = ($type == $pr_type );
			$out .= Xml::option( $text, $type, $selected ) . "\n";
		}

		$out .= '</select>';
		return "<label for='type'>" . wfMsgHtml('restriction-type') . "</label>: " . $out;
	}

	/**
	 * @return string Formatted HTML
	 * @private
	 */	
	function getLevelMenu( $pr_level ) {
		global $wgRestrictionLevels, $wgUser;
	
		$out = "<select name='level'>\n";
		$m = array( wfMsgHtml('restriction-level-all') => 0 ); // Temporary array

		// First pass to load the log names
		foreach( $wgRestrictionLevels as $type ) {
			if ( $type !='' && $type !='*') {
				$text = wfMsgHtml("restriction-level-$type");
				$m[$text] = $type;
			}
		}

		// Second pass to sort by name
		ksort($m);

		// Third pass generates sorted XHTML content
		foreach( $m as $text => $type ) {
			$selected = ($type == $pr_level );
			$out .= Xml::option( $text, $type, $selected ) . "\n";
		}

		$out .= '</select>';
		return "<label for='level'>" . wfMsgHtml('restriction-level') . "</label>: " . $out;
	}
}

/**
 *
 *
 */
class ProtectedPagesPager extends ReverseChronologicalPager {
	public $mForm, $mConds;

	function __construct( $form, $conds = array(), $type, $level, $namespace ) {
		$this->mForm = $form;
		$this->mConds = $conds;
		$this->type = ( $type ) ? $type : 'edit';
		$this->level = $level;
		$this->namespace = $namespace;
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
		$conds[] = 'pr_type=' . $this->mDb->addQuotes( $this->type );
		if ( $this->level )
			$conds[] = 'pr_level=' . $this->mDb->addQuotes( $this->level );
		if ( !is_null($this->namespace) )
			$conds[] = 'page_namespace=' . $this->mDb->addQuotes( $this->namespace );
		return array(
			'tables' => array( 'page_restrictions', 'page' ),
			'fields' => 'max(pr_id) AS pr_id,page_namespace,page_title,pr_type,pr_level,pr_expiry',
			'conds' => $conds,
			'options' => array( 'GROUP BY' => 'page_namespace,page_title,pr_level,pr_expiry' ),
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
