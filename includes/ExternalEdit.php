<?php
/**
 * License: Public domain
 *
 * @author Erik Moeller <moeller@scireview.de>
 * @package MediaWiki
 */

/**
 * 
 * @package MediaWiki
 *
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

	function ExternalEdit ( $article, $mode ) {
		global $wgInputEncoding;
		$this->mArticle =& $article;
		$this->mTitle =& $article->mTitle;
		$this->mCharset = $wgInputEncoding;
		$this->mMode = $mode;
	}
	
	function edit() {
		global $wgUser, $wgOut, $wgScript, $wgScriptPath, $wgServer;
		$wgOut->disable();
		$name=$this->mTitle->getText();
		$pos=strrpos($name,".")+1;
		header ( "Content-type: application/x-external-editor; charset=".$this->mCharset );
		
		# $type can be "Edit text", "Edit file" or "Diff text" at the moment
		# See the protocol specifications at [[m:Help:External editors/Tech]] for
		# details.
		if(!isset($this->mMode)) {		
			$type="Edit text";		
			$url=$this->mTitle->getFullURL("action=edit&internaledit=true");
			# *.wiki file extension is used by some editors for syntax 
			# highlighting, so we follow that convention
			$extension="wiki"; 
		} elseif($this->mMode=="file") {
			$type="Edit file"; 
			$image = Image::newFromTitle( $this->mTitle );
			$url = $wgServer . $image->getURL();
			$extension=substr($name, $pos);
		}					 
		$control = <<<CONTROL
[Process]
Type=$type
Engine=MediaWiki
Script={$wgServer}{$wgScript}
Server={$wgServer}
Path={$wgScriptPath}

[File]
Extension=$extension
URL=$url
CONTROL;
		echo $control;
	}
}
?>
