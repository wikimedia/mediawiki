<?php
/**
 *
 * @addtogroup SpecialPage
 */

/**
 * @todo document
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
		$sizetype = $wgRequest->getVal( 'sizetype' );
		$size = $wgRequest->getIntOrNull( 'size' );
		$NS = $wgRequest->getIntOrNull( 'namespace' );

		$pager = new ProtectedPagesPager( $this, array(), $type, $level, $NS, $sizetype, $size );	

		$wgOut->addHTML( $this->showOptions( $NS, $type, $level, $sizetype, $size ) );

		if ( $pager->getNumRows() ) {
			$s = $pager->getNavigationBar();
			$s .= "<ul>" . 
				$pager->getBody() .
				"</ul>";
			$s .= $pager->getNavigationBar();
		} else {
			$s = '<p>' . wfMsgHtml( 'protectedpagesempty' ) . '</p>';
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

		$expiry_description = ''; $stxt = '';

		if ( $row->pr_expiry != 'infinity' && strlen($row->pr_expiry) ) {
			$expiry = Block::decodeExpiry( $row->pr_expiry );
	
			$expiry_description = wfMsgForContent( 'protect-expiring', $wgLang->timeanddate( $expiry ) );

			$description_items[] = $expiry_description;
		}
		
		if (!is_null($size = $row->page_len)) {
			if ($size == 0)
				$stxt = ' <small>' . wfMsgHtml('historyempty') . '</small>';
			else
				$stxt = ' <small>' . wfMsgHtml('historysize', $wgLang->formatNum( $size ) ) . '</small>';
		}
		wfProfileOut( __METHOD__ );

		return '<li>' . wfSpecialList( $link . $stxt, implode( $description_items, ', ' ) ) . "</li>\n";
	}
	
	/**
	 * @param $namespace int
	 * @param $type string
	 * @param $level string
	 * @param $minsize int
	 * @private
	 */
	function showOptions( $namespace, $type='edit', $level, $sizetype, $size ) {
		global $wgScript;
		$action = htmlspecialchars( $wgScript );
		$title = SpecialPage::getTitleFor( 'ProtectedPages' );
		$special = htmlspecialchars( $title->getPrefixedDBkey() );
		return "<form action=\"$action\" method=\"get\">\n" .
			'<fieldset>' .
			Xml::element( 'legend', array(), wfMsg( 'protectedpages' ) ) .
			Xml::hidden( 'title', $special ) . "&nbsp\n" .
			$this->getNamespaceMenu( $namespace ) . "&nbsp\n" .
			$this->getTypeMenu( $type ) . "&nbsp\n" .
			$this->getLevelMenu( $level ) . "<br/>\n" .
			$this->getSizeLimit( $sizetype, $size ) . "\n" .
			"&nbsp" . Xml::submitButton( wfMsg( 'allpagessubmit' ) ) . "\n" .
			"</fieldset></form>";
	}
	
	function getNamespaceMenu( $namespace=NULL ) {
		return "<label for='namespace'>" . wfMsgHtml('namespace') . "</label>" . HTMLnamespaceselector($namespace, '');
	}

	/**
	 * @return string Formatted HTML
	 * @private
	 */
	function getSizeLimit( $sizetype, $size ) {	
		$out = Xml::radio( 'sizetype', 'min', ($sizetype=='min'), array('id' => 'wpmin') );
		$out .= Xml::label( wfMsg("minimum-size"), 'wpmin' );
		$out .= "&nbsp".Xml::radio( 'sizetype', 'max', ($sizetype=='max'), array('id' => 'wpmax') );
		$out .= Xml::label( wfMsg("maximum-size"), 'wpmax' );
		$out .= "&nbsp".Xml::input('size', 9, $size, array( 'id' => 'wpsize' ) );
		$out .= ' '.wfMsg('pagesize');
		return $out;
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
 * @todo document
 * @addtogroup Pager
 */
class ProtectedPagesPager extends AlphabeticPager {
	public $mForm, $mConds;

	function __construct( $form, $conds = array(), $type, $level, $namespace, $sizetype='', $size=0 ) {
		$this->mForm = $form;
		$this->mConds = $conds;
		$this->type = ( $type ) ? $type : 'edit';
		$this->level = $level;
		$this->namespace = $namespace;
		$this->sizetype = $sizetype;
		$this->size = intval($size);
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
		
		if( $this->sizetype=='min' )
			$conds[] = 'page_len>=' . $this->size;
		else if( $this->sizetype=='max' )
			$conds[] = 'page_len<=' . $this->size;
		
		if( $this->level )
			$conds[] = 'pr_level=' . $this->mDb->addQuotes( $this->level );
		if( !is_null($this->namespace) )
			$conds[] = 'page_namespace=' . $this->mDb->addQuotes( $this->namespace );
		return array(
			'tables' => array( 'page_restrictions', 'page' ),
			'fields' => 'max(pr_id) AS pr_id,page_namespace,page_title,page_len,pr_type,pr_level,pr_expiry',
			'conds' => $conds,
			'options' => array( 'GROUP BY' => 'page_namespace,page_title,page_len,pr_type,pr_level,pr_expiry' ),
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
