<?php

/**
 * Support functions for the importTextFile script
 *
 * @package MediaWiki
 * @subpackage Maintenance
 * @author Rob Church <robchur@gmail.com>
 */

require_once( "$IP/includes/RecentChange.php" );

/**
 * Insert a new article
 *
 * @param $title Title of the article
 * @param $text Text of the article
 * @param $user User associated with the edit
 * @param $comment Edit summary
 * @param $rc Whether or not to add a recent changes event
 * @return bool
 */
function insertNewArticle( &$title, $text, &$user, $comment, $rc ) {
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
		# Update recent changes if appropriate
		if( $rc )
			updateRecentChanges( $dbw, $title, $user, $comment, strlen( $text ), $articleId );
		# Touch links etc.
		Article::onArticleCreate( $title );
		$article->editUpdates( $text, $comment, false, $dbw->timestamp(), $revisionId );
		return true;
	} else {
		# Title exists; touch nothing
		return false;
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
	return Title::newFromText( $parts[0] );
}

/**
 * Update recent changes with the page creation event
 *
 * @param $dbw Database in use
 * @param $title Title of the new page
 * @param $user User responsible for the creation
 * @param $comment Edit summary associated with the edit
 * @param $size Size of the page
 * @param $articleId Article identifier
 */ 
function updateRecentChanges( &$dbw, &$title, &$user, $comment, $size, $articleId ) {
	RecentChange::notifyNew( $dbw->timestamp(), $title, false, $user, $comment, 'default', '', $size, $articleId );
}

?>