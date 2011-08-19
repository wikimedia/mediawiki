<?php
/**
 * External editors support
 *
 * License: Public domain
 *
 * @file
 * @author Erik Moeller <moeller@scireview.de>
 */

/**
 * Support for external editors to modify both text and files
 * in external applications. It works as follows: MediaWiki
 * sends a meta-file with the MIME type 'application/x-external-editor'
 * to the client. The user has to associate that MIME type with
 * a helper application (a reference implementation in Perl
 * can be found in extensions/ee), which will launch the editor,
 * and save the modified data back to the server.
 *
 */
class ExternalEdit {
	/**
	 * Title to perform the edit on
	 * @var Title
	 */
	private $title;

	/**
	 * Mode of editing
	 * @var String
	 */
	private $mode;

	/**
	 * Constructor
	 * @param $title Title object we're performing the edit on
	 * @param $mode String What mode we're using. Only 'file' has any effect
	 */
	public function __construct( $title, $mode ) {
		$this->title = $title;
		$this->mode = $mode;
	}

	/**
	 * Output the information for the external editor
	 */
	public function edit() {
		global $wgOut, $wgScript, $wgScriptPath, $wgCanonicalServer, $wgLang;
		$wgOut->disable();
		header( 'Content-type: application/x-external-editor; charset=utf-8' );
		header( 'Cache-control: no-cache' );

		# $type can be "Edit text", "Edit file" or "Diff text" at the moment
		# See the protocol specifications at [[m:Help:External editors/Tech]] for
		# details.
		if( $this->mode == "file" ) {
			$type = "Edit file";
			$image = wfLocalFile( $this->title );
			$url = $image->getCanonicalURL();
			$extension = $image->getExtension();
		} else {
			$type = "Edit text";
			$url = $this->title->getCanonicalURL(
				array( 'action' => 'edit', 'internaledit' => 'true' ) );
			# *.wiki file extension is used by some editors for syntax
			# highlighting, so we follow that convention
			$extension = "wiki";
		}
		$special = $wgLang->getNsText( NS_SPECIAL );
		$control = <<<CONTROL
; You're seeing this file because you're using Mediawiki's External Editor
; feature. This is probably because you selected use external editor
; in your preferences. To edit normally, either disable that preference
; or go to the URL $url .
; See http://www.mediawiki.org/wiki/Manual:External_editors for details.
[Process]
Type=$type
Engine=MediaWiki
Script={$wgCanonicalServer}{$wgScript}
Server={$wgCanonicalServer}
Path={$wgScriptPath}
Special namespace=$special

[File]
Extension=$extension
URL=$url
CONTROL;
		echo $control;
	}
}
