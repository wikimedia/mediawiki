<?php
# Copyright (C) 2004 Gabriel Wicke <gw@wikidev.net>
# http://www.aulinx.de/
# Based on PageHistory and SpecialExport
# 
# License: GPL (http://www.gnu.org/copyleft/gpl.html)

class RawPage {

	function RawPage( $article ) {
		global $wgRequest, $wgInputEncoding, $wgSquidMaxage;
		$allowedCTypes = array('text/x-wiki', 'text/javascript', 'text/css', 'application/x-zope-edit');
		$this->mArticle =& $article;
		$this->mTitle =& $article->mTitle;
			
		$ctype = $wgRequest->getText( 'ctype' );
		$smaxage = $wgRequest->getInt( 'smaxage', $wgSquidMaxage );
		$maxage = $wgRequest->getInt( 'maxage', $wgSquidMaxage );
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
		$this->mCharset = $wgInputEncoding;
		$this->mSmaxage = $smaxage;
		$this->mMaxage = $maxage;
		if(empty($ctype) or !in_array($ctype, $allowedCTypes)) {
			$this->mContentType = 'text/x-wiki';
		} else {
			$this->mContentType = $ctype;
		}
	}
	
	function view() {
		global $wgUser, $wgOut, $wgScript;

		if( strcmp( $wgScript, $_SERVER['PHP_SELF'] ) ) {
			# Internet Explorer will ignore the Content-Type header if it
			# thinks it sees a file extension it recognizes. Make sure that
			# all raw requests are done through the script node, which will
			# have eg '.php' and should remain safe.
			
			$destUrl = $this->mTitle->getFullUrl(
				'action=raw' .
				'&ctype=' . urlencode( $this->mContentType ) .
				'&smaxage=' . urlencode( $this->mSmaxage ) .
				'&maxage=' . urlencode( $this->mMaxage ) .
				'&gen=' . urlencode( $this->mGen ) .
				'&oldid=' . urlencode( $this->mOldId ) );
			header( 'Location: ' . $destUrl );
			wfAbruptExit();
		}

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
		wfAbruptExit();
	}
	
	function getrawtext () {
		global $wgInputEncoding, $wgLang, $wgIsPg;
		if( !$this->mTitle ) return '';
		$t = wfStrencode( $this->mTitle->getDBKey() );
		$ns = $this->mTitle->getNamespace();
		# special case
		if($ns == NS_MEDIAWIKI) {
			$rawtext = wfMsg($t);
			return $rawtext;
		}
		# else get it from the DB
		if(!empty($this->mOldId)) {
			$oldtable=$wgIsPg?'"old"':'old';
			$sql = "SELECT old_text AS text,old_timestamp AS timestamp,".
				    "old_user AS user,old_flags AS flags FROM $oldtable " .
			"WHERE old_id={$this->mOldId}";
		} else {
			$sql = "SELECT cur_id as id,cur_timestamp as timestamp,cur_user as user,cur_user_text as user_text," .
			"cur_restrictions as restrictions,cur_comment as comment,cur_text as text FROM cur " .
			"WHERE cur_namespace=$ns AND cur_title='$t'";
		}
		$res = wfQuery( $sql, DB_READ );
		if( $s = wfFetchObject( $res ) ) {
			$rawtext = Article::getRevisionText( $s, "" );
			header( 'Last-modified: '.gmdate( "D, j M Y H:i:s", wfTimestamp2Unix( $s->timestamp )).' GMT' );
			return $rawtext;
		} else {
			return '';
		}
	}
}
?>
