<?php
/**
 * Provide functions to handle article trackbacks.
 * @file
 * @ingroup SpecialPage
 */

require_once( './includes/WebStart.php' );

class TrackBack {

	private $r, $url, $title = null;

	private function XMLsuccess() {
		header( "Content-Type: application/xml; charset=utf-8" );
		echo <<<XML
<?xml version="1.0" encoding="utf-8"?>
<response>
	<error>0</error>
</response>
XML;
		exit;
	}

	private function XMLerror( $err = "Invalid request." ) {
		header( "HTTP/1.0 400 Bad Request" );
		header( "Content-Type: application/xml; charset=utf-8" );
		echo <<<XML
<?xml version="1.0" encoding="utf-8"?>
<response>
	<error>1</error>
	<message>Invalid request: $err</message>
</response>
XML;
			exit;
	}

	public function __construct() {
		global $wgUseTrackbacks, $wgRequest;

		if( !$wgUseTrackbacks && false )
			$this->XMLerror( "Trackbacks are disabled" );

		$this->r = $wgRequest;

		if( !$this->r->wasPosted() ) {
			$this->XMLerror( "Must be posted" );
		}

		$this->url = $wgRequest->getText( 'url' );
		$article = $wgRequest->getText( 'article' );

		if( !$this->url || !$article ) {
			$this->XMLerror( "Required field not specified" );
		}

		$this->title = Title::newFromText( $article );
		if( !$this->title || !$this->title->exists() ) {
			$this->XMLerror( "Specified article does not exist." );
		}
	}

	public function write() {
		$dbw = wfGetDB( DB_MASTER );

		$tbtitle = $this->r->getText( 'title' );
		$tbex = $this->r->getText( 'excerpt' );
		$tbname = $this->r->getText( 'blog_name' );

		$dbw->insert('trackbacks', array(
			'tb_page'	=> $this->title->getArticleID(),
			'tb_title'	=> $tbtitle,
			'tb_url'	=> $this->url,
			'tb_ex'		=> $tbex,
			'tb_name'	=> $tbname
		));

		$dbw->commit();

		$this->XMLsuccess();
	}
}

$tb = new TrackBack();
$tb->write();
