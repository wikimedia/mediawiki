<?php

namespace MediaWiki\Hook;

use MediaWiki\RecentChanges\ChangesList;
use MediaWiki\RecentChanges\RecentChange;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ChangesListInsertArticleLink" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ChangesListInsertArticleLinkHook {
	/**
	 * Use this hook to override or augment link to article in RC list.
	 *
	 * @since 1.35
	 *
	 * @param ChangesList $changesList
	 * @param string &$articlelink HTML of link to article (already filled-in)
	 * @param string &$s HTML of row that is being constructed
	 * @param RecentChange $rc
	 * @param bool $unpatrolled Whether or not we are showing unpatrolled changes
	 * @param bool $watched Whether or not the change is watched by the user
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onChangesListInsertArticleLink( $changesList, &$articlelink,
		&$s, $rc, $unpatrolled, $watched
	);
}
