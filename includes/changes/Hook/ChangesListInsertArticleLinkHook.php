<?php

namespace MediaWiki\Hook;

use ChangesList;
use RecentChange;

/**
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
