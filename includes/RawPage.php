<?php
/**
 * Copyright (C) 2004 Gabriel Wicke <gw@wikidev.net>
 * http://www.aulinx.de/
 * Based on PageHistory and SpecialExport
 * 
 * License: GPL (http://www.gnu.org/copyleft/gpl.html)
 *
 * @author Gabriel Wicke <gw@wikidev.net>
 * @package MediaWiki
 */

/**
 * @todo document
 * @package MediaWiki
 */
class RawPage {

	function RawPage( $article ) {
		global $wgRequest, $wgInputEncoding, $wgSquidMaxage;
		$allowedCTypes = array('text/x-wiki', 'text/javascript', 'text/css', 'application/x-zope-edit');
		$this->mArticle =& $article;
		$this->mTitle =& $article->mTitle;
			
		$ctype = $wgRequest->getText( 'ctype' );
		$charset = $wgRequest->getText( 'charset' );
		$smaxage = $wgRequest->getText( 'smaxage' );
		$maxage = $wgRequest->getText( 'maxage' );
		$this->mOldId = $wgRequest->getInt( 'oldid' );
		# special case for 'generated' raw things: user css/js
		$gen = $wgRequest->getText( 'gen' );
		if($gen == 'css') {
			$this->mGen = $gen;
			if($smaxage == '') $smaxage = $wgSquidMaxage;
			if($ctype == '') $ctype = 'text/css';
		} else if ($gen == 'js') {
			$this->mGen = $gen;
			if($smaxage == '') $smaxage = $wgSquidMaxage;
			if($ctype == '') $ctype = 'text/javascript';
		} else {
			$this->mGen = false;
		}
		$this->mCharset = !empty($charset) ? $charset : $wgInputEncoding;
		$this->mSmaxage = ($smaxage != '') ? $smaxage : 0;
		$this->mMaxage = ($maxage != '') ? $maxage : 86400;
		if(empty($ctype) or !in_array($ctype, $allowedCTypes)) {
			$this->mContentType = 'text/x-wiki';
		} else {
			$this->mContentType = $ctype;
		}
	}
	
	function view() {
		global $wgUser, $wgOut;
		header( "Content-type: ".$this->mContentType.'; charset='.$this->mCharset );
		# allow the client to cache this for 24 hours
		header( 'Cache-Control: s-maxage='.$this->mSmaxage.', max-age='.$this->mMaxage );
		if($this->mGen) {
			$sk = $wgUser->getSkin();
			$sk->initPage($wgOut);
			if($this->mGen == 'css') {
				echo $sk->getUserStylesheet();
			} else if($this->mGen == 'js') {
				echo $sk->getUserJs();
			}
		} else {
			echo $this->getrawtext();
		}
		$wgOut->disable();
	}
	
	function getrawtext () {
		global $wgInputEncoding, $wgLang;
		$fname = 'RawPage::getrawtext';
		
		if( !$this->mTitle ) return '';
		$dbr =& wfGetDB( DB_SLAVE );
		extract( $dbr->tableNames( 'cur', 'old' ) );

		$t = $dbr->strencode( $this->mTitle->getDBKey() );
		$ns = $this->mTitle->getNamespace();
		# special case
		if($ns == NS_MEDIAWIKI) {
			$rawtext = wfMsg($t);
			if($wgInputEncoding != $this->mCharset)
			$rawtext = $wgLang->iconv( $wgInputEncoding, $this->mCharset, $rawtext );
			return $rawtext;
		}
		# else get it from the DB
		if(!empty($this->mOldId)) {
			$sql = "SELECT old_text AS text,old_timestamp AS timestamp,".
				    "old_user AS user,old_flags AS flags FROM $old " .
			"WHERE old_id={$this->mOldId}";
		} else {
			$sql = "SELECT cur_id as id,cur_timestamp as timestamp,cur_user as user,cur_user_text as user_text," .
			"cur_restrictions as restrictions,cur_comment as comment,cur_text as text FROM $cur " .
			"WHERE cur_namespace=$ns AND cur_title='$t'";
		}
		$res = $dbr->query( $sql, $fname );
		if( $s = $dbr->fetchObject( $res ) ) {
			$rawtext = Article::getRevisionText( $s, "" );
			if($wgInputEncoding != $this->mCharset)
			$rawtext = $wgLang->iconv( $wgInputEncoding, $this->mCharset, $rawtext );
			header( 'Last-modified: '.gmdate( "D, j M Y H:i:s", wfTimestamp2Unix( $s->timestamp )).' GMT' );
			return $rawtext;
		} else {
			return '';
		}
	}
}
?>
