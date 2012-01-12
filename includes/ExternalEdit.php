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
class ExternalEdit extends ContextSource {

	/**
	 * Array of URLs to link to
	 * @var Array
	 */
	private $urls;

	/**
	 * Constructor
	 * @param $context IContextSource context to use
	 * @param $urls array
	 */
	public function __construct( IContextSource $context, array $urls = array() ) {
		$this->setContext( $context );
		$this->urls = $urls;
	}

	/**
	 * Check whether external edit or diff should be used.
	 *
	 * @param $context IContextSource context to use
	 * @param $type String can be either 'edit' or 'diff'
	 * @return Bool
	 */
	public static function useExternalEngine( IContextSource $context, $type ) {
		global $wgUseExternalEditor;

		if ( !$wgUseExternalEditor ) {
			return false;
		}

		$pref = $type == 'diff' ? 'externaldiff' : 'externaleditor';
		$request = $context->getRequest();

		return !$request->getVal( 'internaledit' ) &&
			( $context->getUser()->getOption( $pref ) || $request->getVal( 'externaledit' ) );
	}

	/**
	 * Output the information for the external editor
	 */
	public function execute() {
		global $wgContLang, $wgScript, $wgScriptPath, $wgCanonicalServer;

		$this->getOutput()->disable();

		$response = $this->getRequest()->response();
		$response->header( 'Content-type: application/x-external-editor; charset=utf-8' );
		$response->header( 'Cache-control: no-cache' );

		$special = $wgContLang->getNsText( NS_SPECIAL );

		# $type can be "Edit text", "Edit file" or "Diff text" at the moment
		# See the protocol specifications at [[m:Help:External editors/Tech]] for
		# details.
		if ( count( $this->urls ) ) {
			$urls = $this->urls;
			$type = "Diff text";
		} elseif ( $this->getRequest()->getVal( 'mode' ) == 'file' ) {
			$type = "Edit file";
			$image = wfLocalFile( $this->getTitle() );
			$urls = array( 'File' => array(
				'Extension' => $image->getExtension(),
				'URL' => $image->getCanonicalURL()
			) );
		} else {
			$type = "Edit text";
			# *.wiki file extension is used by some editors for syntax
			# highlighting, so we follow that convention
			$urls = array( 'File' => array(
				'Extension' => 'wiki',
				'URL' => $this->getTitle()->getCanonicalURL(
					array( 'action' => 'edit', 'internaledit' => 'true' ) )
			) );
		}

		$files = '';
		foreach( $urls as $key => $vars ) {
			$files .= "\n[$key]\n";
			foreach( $vars as $varname => $varval ) {
				$files .= "$varname=$varval\n";
			}
		}

		$url = $this->getTitle()->getFullURL(
			$this->getRequest()->appendQueryValue( 'internaledit', 1, true ) );

		$control = <<<CONTROL
; You're seeing this file because you're using Mediawiki's External Editor feature.
; This is probably because you selected use external editor in your preferences.
; To edit normally, either disable that preference or go to the URL:
; $url
; See http://www.mediawiki.org/wiki/Manual:External_editors for details.
[Process]
Type=$type
Engine=MediaWiki
Script={$wgCanonicalServer}{$wgScript}
Server={$wgCanonicalServer}
Path={$wgScriptPath}
Special namespace=$special
$files
CONTROL;
		echo $control;
	}
}
