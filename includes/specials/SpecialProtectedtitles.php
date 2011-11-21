<?php
/**
 * Implements Special:Protectedtitles
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
 * A special page that list protected titles from creation
 *
 * @ingroup SpecialPage
 */
class SpecialProtectedtitles extends SpecialPage {

	protected $IdLevel = 'level';
	protected $IdType  = 'type';

	public function __construct() {
		parent::__construct( 'Protectedtitles' );
	}

	function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		// Purge expired entries on one in every 10 queries
		if ( !mt_rand( 0, 10 ) ) {
			Title::purgeExpiredRestrictions();
		}

		$request = $this->getRequest();
		$type = $request->getVal( $this->IdType );
		$level = $request->getVal( $this->IdLevel );
		$sizetype = $request->getVal( 'sizetype' );
		$size = $request->getIntOrNull( 'size' );
		$NS = $request->getIntOrNull( 'namespace' );

		$pager = new ProtectedTitlesPager( $this, array(), $type, $level, $NS, $sizetype, $size );

		$this->getOutput()->addHTML( $this->showOptions( $NS, $type, $level ) );

		if ( $pager->getNumRows() ) {
			$s = $pager->getNavigationBar();
			$s .= "<ul>" .
				$pager->getBody() .
				"</ul>";
			$s .= $pager->getNavigationBar();
		} else {
			$s = '<p>' . wfMsgHtml( 'protectedtitlesempty' ) . '</p>';
		}
		$this->getOutput()->addHTML( $s );
	}

	/**
	 * Callback function to output a restriction
	 *
	 * @return string
	 */
	function formatRow( $row ) {
		wfProfileIn( __METHOD__ );

		static $infinity = null;

		if( is_null( $infinity ) ){
			$infinity = wfGetDB( DB_SLAVE )->getInfinity();
		}

		$title = Title::makeTitleSafe( $row->pt_namespace, $row->pt_title );
		$link = Linker::link( $title );

		$description_items = array ();

		$protType = wfMsgHtml( 'restriction-level-' . $row->pt_create_perm );

		$description_items[] = $protType;

		$lang = $this->getLanguage();
		$expiry = strlen( $row->pt_expiry ) ? $lang->formatExpiry( $row->pt_expiry, TS_MW ) : $infinity;
		if( $expiry != $infinity ) {
			$expiry_description = wfMsg(
				'protect-expiring-local',
				$lang->timeanddate( $expiry, true ),
				$lang->date( $expiry, true ),
				$lang->time( $expiry, true )
			);

			$description_items[] = htmlspecialchars($expiry_description);
		}

		wfProfileOut( __METHOD__ );

		return '<li>' . $lang->specialList( $link, implode( $description_items, ', ' ) ) . "</li>\n";
	}

	/**
	 * @param $namespace Integer:
	 * @param $type string
	 * @param $level string
	 * @private
	 */
	function showOptions( $namespace, $type='edit', $level ) {
		global $wgScript;
		$action = htmlspecialchars( $wgScript );
		$title = $this->getTitle();
		$special = htmlspecialchars( $title->getPrefixedDBkey() );
		return "<form action=\"$action\" method=\"get\">\n" .
			'<fieldset>' .
			Xml::element( 'legend', array(), wfMsg( 'protectedtitles' ) ) .
			Html::hidden( 'title', $special ) . "&#160;\n" .
			$this->getNamespaceMenu( $namespace ) . "&#160;\n" .
			$this->getLevelMenu( $level ) . "&#160;\n" .
			"&#160;" . Xml::submitButton( wfMsg( 'allpagessubmit' ) ) . "\n" .
			"</fieldset></form>";
	}

	/**
	 * Prepare the namespace filter drop-down; standard namespace
	 * selector, sans the MediaWiki namespace
	 *
	 * @param $namespace Mixed: pre-select namespace
	 * @return string
	 */
	function getNamespaceMenu( $namespace = null ) {
		return Xml::label( wfMsg( 'namespace' ), 'namespace' )
			. '&#160;'
			. Xml::namespaceSelector( $namespace, '' );
	}

	/**
	 * @return string Formatted HTML
	 * @private
	 */
	function getLevelMenu( $pr_level ) {
		global $wgRestrictionLevels;

		$m = array( wfMsg('restriction-level-all') => 0 ); // Temporary array
		$options = array();

		// First pass to load the log names
		foreach( $wgRestrictionLevels as $type ) {
			if ( $type !='' && $type !='*') {
				$text = wfMsg("restriction-level-$type");
				$m[$text] = $type;
			}
		}
		// Is there only one level (aside from "all")?
		if( count($m) <= 2 ) {
			return '';
		}
		// Third pass generates sorted XHTML content
		foreach( $m as $text => $type ) {
			$selected = ($type == $pr_level );
			$options[] = Xml::option( $text, $type, $selected );
		}

		return
			Xml::label( wfMsg('restriction-level') , $this->IdLevel ) . '&#160;' .
			Xml::tags( 'select',
				array( 'id' => $this->IdLevel, 'name' => $this->IdLevel ),
				implode( "\n", $options ) );
	}
}

/**
 * @todo document
 * @ingroup Pager
 */
class ProtectedTitlesPager extends AlphabeticPager {
	public $mForm, $mConds;

	function __construct( $form, $conds = array(), $type, $level, $namespace, $sizetype='', $size=0 ) {
		$this->mForm = $form;
		$this->mConds = $conds;
		$this->level = $level;
		$this->namespace = $namespace;
		$this->size = intval($size);
		parent::__construct( $form->getContext() );
	}

	function getStartBody() {
		wfProfileIn( __METHOD__ );
		# Do a link batch query
		$this->mResult->seek( 0 );
		$lb = new LinkBatch;

		foreach ( $this->mResult as $row ) {
			$lb->add( $row->pt_namespace, $row->pt_title );
		}

		$lb->execute();
		wfProfileOut( __METHOD__ );
		return '';
	}

	/**
	 * @return Title
	 */
	function getTitle() {
		return SpecialPage::getTitleFor( 'Protectedtitles' );
	}

	function formatRow( $row ) {
		return $this->mForm->formatRow( $row );
	}

	/**
	 * @return array
	 */
	function getQueryInfo() {
		$conds = $this->mConds;
		$conds[] = 'pt_expiry>' . $this->mDb->addQuotes( $this->mDb->timestamp() );
		if( $this->level )
			$conds['pt_create_perm'] = $this->level;
		if( !is_null($this->namespace) )
			$conds[] = 'pt_namespace=' . $this->mDb->addQuotes( $this->namespace );
		return array(
			'tables' => 'protected_titles',
			'fields' => 'pt_namespace,pt_title,pt_create_perm,pt_expiry,pt_timestamp',
			'conds' => $conds
		);
	}

	function getIndexField() {
		return 'pt_timestamp';
	}
}

