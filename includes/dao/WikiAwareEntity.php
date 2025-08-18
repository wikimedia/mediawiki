<?php

namespace MediaWiki\DAO;

use MediaWiki\Page\PageIdentity;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\User\UserIdentity;
use Wikimedia\Assert\PreconditionException;

/**
 * Marker interface for entities aware of the wiki they belong to.
 *
 * Instances of classes implementing this interface belong to a specific
 * wiki and may be used in a cross-wiki context. Services using these
 * classes have to ensure the entities they operate on belong to the
 * correct wiki by calling assertWiki().
 *
 * Additionally, some getters of implementing classes can take an optional
 * $wikiId parameter to assert on for extra protection against incorrect
 * cross-wiki access. The parameter should be added if using the property in
 * the context of a wrong wiki will cause DB corruption. Usually the rule of
 * thumb is fields which are commonly used as foreign keys, like page_id, rev_id,
 * user_id, actor_id etc. However, the exact line is left to the best judgement
 * of the implementers.
 *
 * Examples: {@link RevisionRecord::getId()} or {@link PageIdentity::getId()}
 *
 * @see Block
 * @see PageIdentity
 * @see RevisionRecord
 * @see UserIdentity
 *
 * @since 1.36
 */
interface WikiAwareEntity {

	/**
	 * Wiki ID value to use with instances that are defined relative to the local wiki.
	 */
	public const LOCAL = false;

	/**
	 * Throws if $wikiId is different from the return value of getWikiId().
	 *
	 * @param string|false $wikiId The wiki ID expected by the caller.
	 *        Use self::LOCAL for the local wiki.
	 *
	 * @throws PreconditionException If $wikiId is not the ID of the wiki this entity
	 *         belongs to.
	 */
	public function assertWiki( $wikiId );

	/**
	 * Get the ID of the wiki this page belongs to.
	 *
	 * @return string|false The wiki's logical name,
	 *         or self::LOCAL to indicate the local wiki.
	 */
	public function getWikiId();
}
