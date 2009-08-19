<?php

if (!defined('MEDIAWIKI'))
	die;

class SpecialTags extends SpecialPage {

	function __construct() {
		parent::__construct( 'Tags' );
	}

	function execute( $par ) {
		global $wgOut, $wgUser, $wgMessageCache;

		$wgMessageCache->loadAllMessages();

		$sk = $wgUser->getSkin();
		$wgOut->setPageTitle( wfMsg( 'tags-title' ) );
		$wgOut->wrapWikiMsg( "<div class='mw-tags-intro'>\n$1</div>", 'tags-intro' );

		// Write the headers
		$html = '';
		$html = Xml::tags( 'tr', null, Xml::tags( 'th', null, wfMsgExt( 'tags-tag', 'parseinline' ) ) .
				Xml::tags( 'th', null, wfMsgExt( 'tags-display-header', 'parseinline' ) ) .
				Xml::tags( 'th', null, wfMsgExt( 'tags-description-header', 'parseinline' ) ) .
				Xml::tags( 'th', null, wfMsgExt( 'tags-hitcount-header', 'parseinline' ) )
			);
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'change_tag', array( 'ct_tag', 'count(*) as hitcount' ), array(), __METHOD__, array( 'GROUP BY' => 'ct_tag', 'ORDER BY' => 'hitcount DESC' ) );

		while ( $row = $res->fetchObject() ) {
			$html .= $this->doTagRow( $row->ct_tag, $row->hitcount );
		}

		foreach( ChangeTags::listDefinedTags() as $tag ) {
			$html .= $this->doTagRow( $tag, 0 );
		}

		$wgOut->addHTML( Xml::tags( 'table', array( 'class' => 'wikitable mw-tags-table' ), $html ) );
	}

	function doTagRow( $tag, $hitcount ) {
		static $sk=null, $doneTags=array();
		if (!$sk) {
			global $wgUser;
			$sk = $wgUser->getSkin();
		}

		if ( in_array( $tag, $doneTags ) ) {
			return '';
		}

		global $wgLang;
		
		$newRow = '';
		$newRow .= Xml::tags( 'td', null, Xml::element( 'tt', null, $tag ) );

		$disp = ChangeTags::tagDescription( $tag );
		$disp .= ' (' . $sk->link( Title::makeTitle( NS_MEDIAWIKI, "Tag-$tag" ), wfMsgHtml( 'tags-edit' ) ) . ')';
		$newRow .= Xml::tags( 'td', null, $disp );

		$desc = wfMsgExt( "tag-$tag-description", 'parseinline' );
		$desc = wfEmptyMsg( "tag-$tag-description", $desc ) ? '' : $desc;
		$desc .= ' (' . $sk->link( Title::makeTitle( NS_MEDIAWIKI, "Tag-$tag-description" ), wfMsgHtml( 'tags-edit' ) ) . ')';
		$newRow .= Xml::tags( 'td', null, $desc );

		$hitcount = wfMsgExt( 'tags-hitcount', array( 'parsemag' ), $wgLang->formatNum( $hitcount ) );
		$hitcount = $sk->link( SpecialPage::getTitleFor( 'Recentchanges' ), $hitcount, array(), array( 'tagfilter' => $tag ) );
		$newRow .= Xml::tags( 'td', null, $hitcount );

		$doneTags[] = $tag;

		return Xml::tags( 'tr', null, $newRow ) . "\n";
	}
}