<?php

define( 'REPORTING_INTERVAL', 10 );

function dumpHTML( $dest, $start ) {
	global $wgUser, $wgTitle, $wgArticle, $wgEnablePersistentLC, $wgLinkCache, $wgOut;
	global $wgMakeDumpLinks, $wgStylePath, $wgArticlePath, $wgUploadPath, $wgLogo;
	$wgMakeDumpLinks = true;
	$wgScriptPath = "../../..";
	$wgStylePath = "$wgScriptPath/skins";
	$wgUploadPath = "$wgScriptPath/images";
	$wgLogo = "$wgStylePath/common/images/wiki.png";
	$wgArticlePath = '../../$1';
	$dbr =& wfGetDB( DB_SLAVE );
	$end = $dbr->selectField( 'cur', 'max(cur_id)', false );
	
	/*global $wgValidSkinNames;
	var_dump( $wgValidSkinNames );
	exit;*/

	print("Creating static HTML dump. Starting from cur_id $start of $end.\n");

	$wgUser = new User;
	$wgUser->setOption( 'skin', 'htmldump' );
	$sk =& $wgUser->getSkin();
	
	if ( !is_dir( $dest ) ) {
		if ( !mkdir( $dest, 0644 ) ) {
			print("Can't make directory $dir, exiting\n");
			return;
		}
	}
	
	for ($id = $start; $id <= $end; $id++) {
		if ( !($id % REPORTING_INTERVAL) ) {
			print("$id\n");
		}
		
		$wgOut = new OutputPage;
		$wgOut->setArticleFlag( true );
		$wgOut->setRobotpolicy( 'index,follow' );
		
		$wgTitle = Title::newFromID( $id );
		if ( is_null( $wgTitle ) ) {
			continue;
		}

		$wgArticle = new Article( $wgTitle );
		$text = $wgArticle->getContent( true );
		$wgLinkCache = new LinkCache;
		$wgLinkCache->forUpdate( true );
		
		global $wgLinkHolders;
		$wgLinkHolders = array(
			'namespaces' => array(),
			'dbkeys' => array(),
			'queries' => array(),
			'texts' => array(),
			'titles' => array()
		);


		# Parse the text and replace links with placeholders
		$wgOut->setPageTitle( $wgTitle->getPrefixedText() );
		$wgOut->addWikiText( $text );
		$wgOut->transformBuffer();
		
		# Execute skin to get complete HTML
		ob_start();
		$sk->outputPage( $wgOut );
		$text = ob_get_contents();
		ob_end_clean();
		
		# Write to file
		$fname = $wgTitle->getHashedFilename();
		$bits = explode( '/', $fname );
		$parentDir = "$dest/{$bits[0]}";
		$fullDir = "$dest/{$bits[0]}/{$bits[1]}";
		$fullName = "$dest/$fname";

		
		if ( !is_dir( $parentDir ) ) {
			if ( !mkdir( $parentDir, 0644 ) ) {
				print("Can't write to directory $parentDir\n");
				return;
			}
		}
		if ( !is_dir( $fullDir ) ) {
			if ( !mkdir( $fullDir, 0644 ) ) {
				print("Can't write to directory $fullDir\n");
				return;
			}
		}
		
		$file = fopen( $fullName, 'w' );
		if ( !$file ) {
			print("Can't open file $fullName for writing\n");
			return;
		}
		
		fwrite( $file, $text );
		fclose( $file );
	}
}	

# vim: syn=php
?>
