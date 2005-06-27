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

/** */
require_once( 'Revision.php' );

/**
 * @todo document
 * @package MediaWiki
 */
class RawPage {

	function RawPage( $article ) {
		global $wgRequest, $wgInputEncoding, $wgSquidMaxage, $wgJsMimeType;
		$allowedCTypes = array('text/x-wiki', $wgJsMimeType, 'text/css', 'application/x-zope-edit');
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
			if($ctype == '') $ctype = $wgJsMimeType;
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

		if( isset( $_SERVER['SCRIPT_URL'] ) ) {
			# Normally we use PHP_SELF to get the URL to the script
			# as it was called, minus the query string.
			#
			# Some sites use Apache rewrite rules to handle subdomains,
			# and have PHP set up in a weird way that causes PHP_SELF
			# to contain the rewritten URL instead of the one that the
			# outside world sees.
			#
			# If in this mode, use SCRIPT_URL instead, which mod_rewrite
			# provides containing the "before" URL.
			$url = $_SERVER['SCRIPT_URL'];
		} else {
			$url = $_SERVER['PHP_SELF'];
		}
		if( strcmp( $wgScript, $url ) ) {
			# Internet Explorer will ignore the Content-Type header if it
			# thinks it sees a file extension it recognizes. Make sure that
			# all raw requests are done through the script node, which will
			# have eg '.php' and should remain safe.
			#
			# We used to redirect to a canonical-form URL as a general
			# backwards-compatibility / good-citizen nice thing. However
			# a lot of servers are set up in buggy ways, resulting in
			# redirect loops which hang the browser until the CSS load
			# times out.
			#
			# Just return a 403 Forbidden and get it over with.
			wfHttpError( 403, 'Forbidden',
				'Raw pages must be accessed through the primary script entry point.' );
			return;
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
		$wgOut->disable();
	}
	
	function getrawtext () {
		global $wgInputEncoding, $wgContLang;
		$fname = 'RawPage::getrawtext';
		
		if( $this->mTitle ) {
			# Special case for MediaWiki: messages; we can hit the message cache.
			if( $this->mTitle->getNamespace() == NS_MEDIAWIKI) {
				$rawtext = wfMsgForContent( $this->mTitle->getDbkey() );
				return $rawtext;
			}
			
			# else get it from the DB
			$rev = Revision::newFromTitle( $this->mTitle, $this->mOldId );
			if( $rev ) {
				$lastmod = wfTimestamp( TS_RFC2822, $rev->getTimestamp() );
				header( 'Last-modified: ' . $lastmod );
				return $rev->getText();
			}
		}
		
		# Bad title or page does not exist
		if( $this->mContentType == 'text/x-wiki' ) {
			# Don't return a 404 response for CSS or JavaScript;
			# 404s aren't generally cached and it would create
			# extra hits when user CSS/JS are on and the user doesn't
			# have the pages.
			header( "HTTP/1.0 404 Not Found" );
		}
		return '';
	}
}
?>
