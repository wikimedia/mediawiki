<?php
# Copyright (C) 2004 Gabriel Wicke <gw@wikidev.net>
# http://www.aulinx.de/
# Based on PageHistory and SpecialExport
# 
# License: GPL (http://www.gnu.org/copyleft/gpl.html)

class RawPage {

	function RawPage( $article ) {
		global $wgRequest, $wgInputEncoding;
		$this->mArticle =& $article;
		$this->mTitle =& $article->mTitle;
		$ctype = $wgRequest->getText( 'ctype' );
		$this->mContentType = !empty($ctype)?$ctype:'text/plain';
		$charset = $wgRequest->getText( 'charset' );
		$this->mCharset = !empty($charset) ? $charset : $wgInputEncoding;
		$this->mOldId = $wgRequest->getInt( 'oldid' );
	}
	function view() {
		header( "Content-type: ".$this->mContentType.'; charset='.$this->mCharset );
		# allow the client to cache this for 24 hours
		header( 'Cache-Control: s-maxage=0, max-age=86400' );
		echo $this->getrawtext();
		wfAbruptExit();
	}

	function getrawtext () {
		global $wgInputEncoding, $wgLang;
		if( !$this->mTitle ) return '';
		$t = wfStrencode( $this->mTitle->getDBKey() );
		$ns = $this->mTitle->getNamespace();
		if(!empty($this->mOldId)) {
			$sql = "SELECT old_text as text,old_timestamp as timestamp,old_user as user,old_flags as flags FROM old " .
			"WHERE old_id={$this->mOldId}";
		} else {
			$sql = "SELECT cur_id as id,cur_timestamp as timestamp,cur_user as user,cur_user_text as user_text," .
			"cur_restrictions as restrictions,cur_comment as comment,cur_text as text FROM cur " .
			"WHERE cur_namespace=$ns AND cur_title='$t'";
		}
		$res = wfQuery( $sql, DB_READ );
		if( $s = wfFetchObject( $res ) ) {
			$rawtext = Article::getRevisionText( $s, "" );
			if($wgInputEncoding != $this->mCharset)
			$rawtext = $wgLang->iconv( $wgInputEncoding, $this->mCharset, $rawtext );
			return $rawtext;
		} else {
			return '';
		}
	}
}
?>
