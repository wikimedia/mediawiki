<?php

namespace MediaWiki\Storage;

use CommentStoreComment;
use Title;
use User;
use Wikimedia\Rdbms\IDatabase;

/**
 * deprecated probably? TODO replacement?
 */
interface NullRevisionFactory {

	/**
	 * @deprecated, don't use me, use a method that doesn't exist yet...
	 *
	 * @param IDatabase $dbw
	 * @param Title $title
	 * @param CommentStoreComment $comment
	 * @param bool $minor
	 * @param User $user
	 * @return mixed
	 */
	public function newNullRevision(
		IDatabase $dbw,
		Title $title,
		CommentStoreComment $comment,
		$minor,
		User $user
	);

}
