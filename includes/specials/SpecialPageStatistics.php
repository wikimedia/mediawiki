<?php
if (!defined('MEDIAWIKI'))
	die();

class SpecialPageStatistics extends SpecialPage {
	function __construct() {
		parent::__construct( 'PageStatistics' );
	}
	
	function execute( $subpage ) {
		global $wgOut;
		
		$wgOut->setPageTitle( wfMsg( 'pagestatistics' ) );
		$wgOut->setRobotPolicy( "noindex,nofollow" );
		$wgOut->setArticleRelated( false );
		$wgOut->enableClientCache( false );
		
		$this->setHeaders();
		$this->loadParameters( $subpage );
	
		if ($this->page) {
			$this->showStatistics( );
		} else {
			$this->showMain();
		}
		
		
	}
	
	function loadParameters( $subpage ) {
		global $wgRequest;
		
		$this->page = $subpage;
		$this->periodStart = $wgRequest->getVal( 'periodstart' );
		$this->periodEnd = $wgRequest->getVal( 'periodend' );
		
		if ($p = $wgRequest->getVal( 'target' ) )
			$this->page = $p;
	}
	
	function showSearchBox(  ) {
		global $wgOut;
		
		$fields = array();
		$fields['pagestatistics-page'] = Xml::input( 'target', 45, $this->page );
		$fields['pagestatistics-periodstart'] = Xml::input( 'periodstart', 45, $this->periodStart );
		$fields['pagestatistics-periodend'] = Xml::input( 'periodend', 45, $this->periodEnd );
		
		$form = Xml::buildForm( $fields, 'pagestatistics-search' );
		$form .= Xml::hidden( 'title', $this->getTitle()->getPrefixedText() );
		$form = Xml::tags( 'form', array( 'method' => 'GET', 'action' => $this->getTitle()->getFullURL() ), $form );
		$form = Xml::fieldset( wfMsgExt( 'pagestatistics-search-legend', 'parseinline' ), $form );
		
		$wgOut->addHTML( $form );
	}
	
	function showMain() {
		global $wgUser, $wgOut;
		
		$sk = $wgUser->getSkin();
		
		## Create initial intro
		$wgOut->addWikiMsg( 'pagestatistics-intro' );
		
		## Fieldset with search stuff
		$this->showSearchBox( );
	}
	
	function showStatistics() {
		global $wgLang, $wgOut;
		
		$this->showSearchBox();
		
		## For now, just a data table.
		$dbr = wfGetDB( DB_SLAVE );
		
		$article = new Article( Title::newFromText( $this->page ) );
		
		$periodStart = $dbr->addQuotes( $dbr->timestamp( strtotime( $this->periodStart ) ) );
		$periodEnd = $dbr->addQuotes( $dbr->timestamp( strtotime( $this->periodEnd ) ) );
		
		$res = $dbr->select( 'hit_statistics', '*', array( "hs_period_start>=$periodStart", "hs_period_end<=$periodEnd", 'hs_page' => $article->getId() ), __METHOD__ );

		$html = Xml::tags( 'th', null, wfMsgExt( 'pagestatistics-datatable-periodstart', 'parseinline' ) );
		$html .= Xml::tags( 'th', null, wfMsgExt( 'pagestatistics-datatable-periodend', 'parseinline' ) );
		$html .= Xml::tags( 'th', null, wfMsgExt( 'pagestatistics-datatable-count', 'parseinline' ) );
		$html = Xml::tags( 'tr', null, $html );
		
		$total = 0;
		$data = array();
		while( $row = $dbr->fetchObject( $res ) ) {
			$thisData = array(
				'count' => $row->hs_count,
				'start' => $row->hs_period_start,
				'end' => $row->hs_period_end,
			);			
			$data[] = $thisData;
			
			$total += $row->hs_count;
			
			$thisRow = Xml::tags( 'td', null, $wgLang->timeanddate( $row->hs_period_start ) );
			$thisRow .= Xml::tags('td', null, $wgLang->timeanddate( $row->hs_period_end ) );
			$thisRow .= Xml::tags('td', null, $row->hs_count );
			$thisRow = Xml::tags( 'tr', null, $thisRow );
			
			$html .= "$thisRow\n";
		}
		
		## Rollup total row
		$totalLabel = Xml::tags( 'strong', null, wfMsgExt( 'pagestatistics-datatable-total', 'parseinline' ) );
		$thisRow = Xml::tags( 'td', null, $totalLabel );
		$thisRow .= Xml::tags('td', null, $totalLabel );
		$thisRow .= Xml::tags('td', null, $total );
		$thisRow = Xml::tags( 'tr', null, $thisRow );
		
		$html .= "$thisRow\n";
		
		$html = Xml::tags( 'table', null, Xml::tags( 'tbody', null, $html ) );
		$wgOut->addHTML( $html );
		
		$wgOut->addWikitext( 'Purty graph goes here' );
		
		## TODO purty graph :)
	}
}