<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ChangesListInsertArticleLinkHook {
	/**
	 * Override or augment link to article in RC list.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $changesList ChangesList instance.
	 * @param ?mixed &$articlelink HTML of link to article (already filled-in).
	 * @param ?mixed &$s HTML of row that is being constructed.
	 * @param ?mixed $rc RecentChange instance.
	 * @param ?mixed $unpatrolled Whether or not we are showing unpatrolled changes.
	 * @param ?mixed $watched Whether or not the change is watched by the user.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onChangesListInsertArticleLink( $changesList, &$articlelink,
		&$s, $rc, $unpatrolled, $watched
	);
}
