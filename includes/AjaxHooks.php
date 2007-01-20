<?php
if( !defined( 'MEDIAWIKI' ) )
	die( 1 );

/**
	$article: the article (object) saved
	$user: the user (object) who saved the article
	$text: the new article text
	$summary: the article summary (comment)
	$isminor: minor flag
	$iswatch: watch flag
	$section: section #
*/
function wfAjaxShowEditorsCleanup( $article, $user ) {
	$articleId = $article->getID();
	$userId = $user->getName();

	$dbw =& wfGetDB(DB_MASTER);
	$dbw->delete('editings',
		array(
			'editings_page' => $articleId,
			'editings_user' => $userId,
		),
		__METHOD__
	);
}

$wgHooks['ArticleSaveComplete'][] = 'wfAjaxShowEditorsCleanup';
?>
