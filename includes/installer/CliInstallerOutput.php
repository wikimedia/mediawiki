<?php

/**
 * Output class modelled on OutputPage.
 *
 * I've opted to use a distinct class rather than derive from OutputPage here in
 * the interests of separation of concerns: if we used a subclass, there would be
 * quite a lot of things you could do in OutputPage that would break the installer,
 * that wouldn't be immediately obvious.
 */
class CliInstallerOutput {

	function __construct( $parent ) {
		$this->parent = $parent;
	}

	function addHTML( $html ) {
		$this->contents .= $html;
	}

	function addWikiText( $text ) {
		$this->addHTML( $this->parent->parse( $text ) );
	}

	function addHTMLNoFlush( $html ) {
		$this->contents .= $html;
	}

	function addWarning( $msg ) {
		$this->warnings .= "<p>$msg</p>\n";
	}

	function addWarningMsg( $msg /*, ... */ ) {
		$params = func_get_args();
		array_shift( $params );
		$this->addWarning( wfMsg( $msg, $params ) );
	}

	function redirect( $url ) {
		if ( $this->headerDone ) {
			throw new MWException( __METHOD__ . ' called after sending headers' );
		}
		$this->redirectTarget = $url;
	}

	function output() {
		$this->flush();
	}

	function useShortHeader( $use = true ) {
	}

	function flush() {
		echo html_entity_decode( strip_tags( $this->contents ), ENT_QUOTES );
		flush();
		$this->contents = '';
	}

	function getDir() {
		global $wgLang;
		if( !is_object( $wgLang ) || !$wgLang->isRtl() )
			return 'ltr';
		else
			return 'rtl';
	}

	function getLanguageCode() {
		global $wgLang;
		if( !is_object( $wgLang ) )
			return 'en';
		else
			return $wgLang->getCode();
	}

	function outputWarnings() {
		$this->addHTML( $this->warnings );
		$this->warnings = '';
	}
}
