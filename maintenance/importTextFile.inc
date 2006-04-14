<?php

/**
 * Support functions for the importTextFile script
 *
 * @package MediaWiki
 * @subpackage Maintenance
 * @author Rob Church <robchur@gmail.com>
 */
 
/**
 * Insert a new article
 *
 * @param $title Title of the article
 * @param $text Text of the article
 * @param $user User associated with the edit
 * @param $comment Edit summary
 * @return bool
 */
function insertNewArticle( &$title, $text, &$user, $comment ) {
	if( !$title->exists() ) {
		# Create the article
		$dbw =& wfGetDB( DB_MASTER );
		$dbw->immediateBegin();
		$article = new Article( $title );
		$articleId = $article->insertOn( $dbw );
		# Prepare and save associated revision
		$revision = new Revision( array( 'page' => $articleId, 'text' => $text, 'user' => $user->mId, 'user_text' => $user->getName(), 'comment' => $comment ) );
		$revisionId = $revision->insertOn( $dbw );
		# Make it the current revision
		$article->updateRevisionOn( $dbw, $revision );
		$dbw->immediateCommit();
		return( true );
	} else {
		# Title exists; touch nothing
		return( false );
	}
}

/**
 * Turn a filename into a title
 *
 * @param $filename Filename to be transformed
 * @return Title
 */
function titleFromFilename( $filename ) {
	$parts = explode( '/', $filename );
	$parts = explode( '.', $parts[ count( $parts ) - 1 ] );
	return( Title::newFromText( $parts[0] ) );
}

?>