<?php
/**
 * Implements Special:Protectedpages
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
 */

/**
 * A special page that lists protected pages
 *
 * @ingroup SpecialPage
 */
class SpecialProtectedpages extends SpecialPage {

	protected $IdLevel = 'level';
	protected $IdType  = 'type';

	public function __construct() {
		parent::__construct( 'Protectedpages' );
	}

	public function execute( $par ) {
		global $wgOut, $wgRequest;

		$this->setHeaders();
		$this->outputHeader();

		// Purge expired entries on one in every 10 queries
		if( !mt_rand( 0, 10 ) ) {
			Title::purgeExpiredRestrictions();
		}

		$type = $wgRequest->getVal( $this->IdType );
		$level = $wgRequest->getVal( $this->IdLevel );
		$sizetype = $wgRequest->getVal( 'sizetype' );
		$size = $wgRequest->getIntOrNull( 'size' );
		$NS = $wgRequest->getIntOrNull( 'namespace' );
		$indefOnly = $wgRequest->getBool( 'indefonly' ) ? 1 : 0;
		$cascadeOnly = $wgRequest->getBool('cascadeonly') ? 1 : 0;

		$pager = new ProtectedPagesPager( $this, array(), $type, $level, $NS, $sizetype, $size, $indefOnly, $cascadeOnly );

		$wgOut->addHTML( $this->showOptions( $NS, $type, $level, $sizetype, $size, $indefOnly, $cascadeOnly ) );

		if( $pager->getNumRows() ) {
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
	 * @param $row object Protected title
	 * @return string Formatted <li> element
	 */
	public function formatRow( $row ) {
		global $wgUser, $wgLang, $wgContLang;

		wfProfileIn( __METHOD__ );

		static $skin=null;

		if( is_null( $skin ) )
			$skin = $wgUser->getSkin();

		$title = Title::makeTitleSafe( $row->page_namespace, $row->page_title );
		$link = $skin->link( $title );

		$description_items = array ();

		$protType = wfMsgHtml( 'restriction-level-' . $row->pr_level );

		$description_items[] = $protType;

		if( $row->pr_cascade ) {
			$description_items[] = wfMsg( 'protect-summary-cascade' );
		}

		$expiry_description = '';
		$stxt = '';

		if( $row->pr_expiry != 'infinity' && strlen($row->pr_expiry) ) {
			$expiry = Block::decodeExpiry( $row->pr_expiry );

			$expiry_description = wfMsg( 'protect-expiring' , $wgLang->timeanddate( $expiry ) , 
				$wgLang->date( $expiry ) , $wgLang->time( $expiry ) );

			$description_items[] = htmlspecialchars($expiry_description);
		}

		if(!is_null($size = $row->page_len)) {
			$stxt = $wgContLang->getDirMark() . ' ' . $skin->formatRevisionSize( $size );
		}

		# Show a link to the change protection form for allowed users otherwise a link to the protection log
		if( $wgUser->isAllowed( 'protect' ) ) {
			$changeProtection = ' (' . $skin->linkKnown(
				$title,
				wfMsgHtml( 'protect_change' ),
				array(),
				array( 'action' => 'unprotect' )
			) . ')';
		} else {
			$ltitle = SpecialPage::getTitleFor( 'Log' );
			$changeProtection = ' (' . $skin->linkKnown(
				$ltitle,
				wfMsgHtml( 'protectlogpage' ),
				array(),
				array(
					'type' => 'protect',
					'page' => $title->getPrefixedText()
				)
			) . ')';
		}

		wfProfileOut( __METHOD__ );

		return Html::rawElement(
			'li',
			array(),
			wfSpecialList( $link . $stxt, $wgLang->commaList( $description_items ) ) . $changeProtection ) . "\n";
	}

	/**
	 * @param $namespace Integer
	 * @param $type String: restriction type
	 * @param $level String: restriction level
	 * @param $sizetype String: "min" or "max"
	 * @param $size Integer
	 * @param $indefOnly Boolean: only indefinie protection
	 * @param $cascadeOnly Boolean: only cascading protection
	 * @return String: input form
	 */
	protected function showOptions( $namespace, $type='edit', $level, $sizetype, $size, $indefOnly, $cascadeOnly ) {
		global $wgScript;
		$title = SpecialPage::getTitleFor( 'Protectedpages' );
		return Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript ) ) .
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', array(), wfMsg( 'protectedpages' ) ) .
			Xml::hidden( 'title', $title->getPrefixedDBkey() ) . "\n" .
			$this->getNamespaceMenu( $namespace ) . "&#160;\n" .
			$this->getTypeMenu( $type ) . "&#160;\n" .
			$this->getLevelMenu( $level ) . "&#160;\n" .
			"<br /><span style='white-space: nowrap'>" .
			$this->getExpiryCheck( $indefOnly ) . "&#160;\n" .
			$this->getCascadeCheck( $cascadeOnly ) . "&#160;\n" .
			"</span><br /><span style='white-space: nowrap'>" .
			$this->getSizeLimit( $sizetype, $size ) . "&#160;\n" .
			"</span>" .
			"&#160;" . Xml::submitButton( wfMsg( 'allpagessubmit' ) ) . "\n" .
			Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' );
	}

	/**
	 * Prepare the namespace filter drop-down; standard namespace
	 * selector, sans the MediaWiki namespace
	 *
	 * @param $namespace Mixed: pre-select namespace
	 * @return String
	 */
	protected function getNamespaceMenu( $namespace = null ) {
		return "<span style='white-space: nowrap'>" .
			Xml::label( wfMsg( 'namespace' ), 'namespace' ) . '&#160;'
			. Xml::namespaceSelector( $namespace, '' ) . "</span>";
	}

	/**
	 * @return string Formatted HTML
	 */
	protected function getExpiryCheck( $indefOnly ) {
		return
			Xml::checkLabel( wfMsg('protectedpages-indef'), 'indefonly', 'indefonly', $indefOnly ) . "\n";
	}
	
	/**
	 * @return string Formatted HTML
	 */
	protected function getCascadeCheck( $cascadeOnly ) {
		return
			Xml::checkLabel( wfMsg('protectedpages-cascade'), 'cascadeonly', 'cascadeonly', $cascadeOnly ) . "\n";
	}

	/**
	 * @return string Formatted HTML
	 */
	protected function getSizeLimit( $sizetype, $size ) {
		$max = $sizetype === 'max';

		return
			Xml::radioLabel( wfMsg('minimum-size'), 'sizetype', 'min', 'wpmin', !$max ) .
			'&#160;' .
			Xml::radioLabel( wfMsg('maximum-size'), 'sizetype', 'max', 'wpmax', $max ) .
			'&#160;' .
			Xml::input( 'size', 9, $size, array( 'id' => 'wpsize' ) ) .
			'&#160;' .
			Xml::label( wfMsg('pagesize'), 'wpsize' );
	}

	/**
	 * Creates the input label of the restriction type
	 * @param $pr_type string Protection type
	 * @return string Formatted HTML
	 */
	protected function getTypeMenu( $pr_type ) {
		global $wgRestrictionTypes;

		$m = array(); // Temporary array
		$options = array();

		// First pass to load the log names
		foreach( $wgRestrictionTypes as $type ) {
			$text = wfMsg("restriction-$type");
			$m[$text] = $type;
		}

		// Third pass generates sorted XHTML content
		foreach( $m as $text => $type ) {
			$selected = ($type == $pr_type );
			$options[] = Xml::option( $text, $type, $selected ) . "\n";
		}

		return "<span style='white-space: nowrap'>" .
			Xml::label( wfMsg('restriction-type') , $this->IdType ) . '&#160;' .
			Xml::tags( 'select',
				array( 'id' => $this->IdType, 'name' => $this->IdType ),
				implode( "\n", $options ) ) . "</span>";
	}

	/**
	 * Creates the input label of the restriction level
	 * @param $pr_level string Protection level
	 * @return string Formatted HTML
	 */
	protected function getLevelMenu( $pr_level ) {
		global $wgRestrictionLevels;

		$m = array( wfMsg('restriction-level-all') => 0 ); // Temporary array
		$options = array();

		// First pass to load the log names
		foreach( $wgRestrictionLevels as $type ) {
			// Messages used can be 'restriction-level-sysop' and 'restriction-level-autoconfirmed'
			if( $type !='' && $type !='*') {
				$text = wfMsg("restriction-level-$type");
				$m[$text] = $type;
			}
		}

		// Third pass generates sorted XHTML content
		foreach( $m as $text => $type ) {
			$selected = ($type == $pr_level );
			$options[] = Xml::option( $text, $type, $selected );
		}

		return "<span style='white-space: nowrap'>" .
			Xml::label( wfMsg( 'restriction-level' ) , $this->IdLevel ) . ' ' .
			Xml::tags( 'select',
				array( 'id' => $this->IdLevel, 'name' => $this->IdLevel ),
				implode( "\n", $options ) ) . "</span>";
	}
}

/**
 * @todo document
 * @ingroup Pager
 */
class ProtectedPagesPager extends AlphabeticPager {
	public $mForm, $mConds;
	private $type, $level, $namespace, $sizetype, $size, $indefonly;

	function __construct( $form, $conds = array(), $type, $level, $namespace, $sizetype='', $size=0, 
		$indefonly = false, $cascadeonly = false )
	{
		$this->mForm = $form;
		$this->mConds = $conds;
		$this->type = ( $type ) ? $type : 'edit';
		$this->level = $level;
		$this->namespace = $namespace;
		$this->sizetype = $sizetype;
		$this->size = intval($size);
		$this->indefonly = (bool)$indefonly;
		$this->cascadeonly = (bool)$cascadeonly;
		parent::__construct();
	}

	function getStartBody() {
		# Do a link batch query
		$lb = new LinkBatch;
		foreach ( $this->mResult as $row ) {
			$lb->add( $row->page_namespace, $row->page_title );
		}
		$lb->execute();
		return '';
	}

	function formatRow( $row ) {
		return $this->mForm->formatRow( $row );
	}

	function getQueryInfo() {
		$conds = $this->mConds;
		$conds[] = '(pr_expiry>' . $this->mDb->addQuotes( $this->mDb->timestamp() ) .
				'OR pr_expiry IS NULL)';
		$conds[] = 'page_id=pr_page';
		$conds[] = 'pr_type=' . $this->mDb->addQuotes( $this->type );
		
		if( $this->sizetype=='min' ) {
			$conds[] = 'page_len>=' . $this->size;
		} else if( $this->sizetype=='max' ) {
			$conds[] = 'page_len<=' . $this->size;
		}

		if( $this->indefonly ) {
			$conds[] = "pr_expiry = 'infinity' OR pr_expiry IS NULL";
		}
		if( $this->cascadeonly ) {
			$conds[] = "pr_cascade = '1'";
		}

		if( $this->level )
			$conds[] = 'pr_level=' . $this->mDb->addQuotes( $this->level );
		if( !is_null($this->namespace) )
			$conds[] = 'page_namespace=' . $this->mDb->addQuotes( $this->namespace );
		return array(
			'tables' => array( 'page_restrictions', 'page' ),
			'fields' => 'pr_id,page_namespace,page_title,page_len,pr_type,pr_level,pr_expiry,pr_cascade',
			'conds' => $conds
		);
	}

	function getIndexField() {
		return 'pr_id';
	}
}
