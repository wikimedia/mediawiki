<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\ChangeTags\Event;

use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\DomainEvent\DomainEvent;
use MediaWiki\User\UserIdentity;
use Wikimedia\Assert\Assert;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Base class for domain events representing updates to the change tags
 * attached to an entity, such as a revision or a log entry.
 *
 * Change tags are modeled as a property of the entity they are attached to,
 * so every kind of taggable entity has its own subclass of this event. A single
 * update may affect more than one entity, in which case one event is emitted
 * per affected entity. However, logged actions about a revision might not
 * necessarily have an associated revision tag change event.
 *
 * Tags are reported as maps of tag name (change_tag_def.ctd_name) to tag data.
 * The tag data is an array with these keys:
 * - id: The tag id (change_tag.ct_tag_id).
 * - params: The tag params (change_tag.ct_params). This is null if the tag has
 *   no params, or if the params are not known.
 *
 * The base class itself doesn't carry any information about the tagged entity
 * and is not particularly useful on its own. Listeners interested in a single
 * kind of entity should register for the respective subtype.
 *
 * @todo Tags can also be attached to a recentchanges row,
 *   but there is currently no immutable representation of a RecentChange row,
 *   which is required to be emitted as a domain event.
 *
 * @see ChangeTagsStore::updateTags()
 *
 * @since 1.47
 * @unstable
 */
abstract class EntityTagsUpdatedEvent extends DomainEvent {

	public const TYPE = 'EntityTagsUpdated';

	/** @var array<string,array{id:int,params:?string}> */
	private array $tagsBefore;

	/** @var array<string,array{id:int,params:?string}> */
	private array $tagsAfter;

	private ?string $params;
	private ?UserIdentity $performer;

	/**
	 * @stable to call
	 *
	 * @param array<string,array{id:int,params:?string}> $tagsBefore Tags
	 *   that were present prior to the update, as a map of tag name to tag data.
	 *   See the class documentation for the format of the tag data.
	 * @param array<string,array{id:int,params:?string}> $tagsAfter Tags
	 *   that are present after the update, in the same format as $tagsBefore.
	 * @param string|null $params Tag params, stored in change_tag.ct_params.
	 * @param UserIdentity|null $performer The user who performed the tagging
	 *   when the tagging is subsequent to the tagged action, or null when the
	 *   tagging accompanies the action.
	 * @param string|ConvertibleTimestamp|false $timestamp
	 */
	public function __construct(
		array $tagsBefore,
		array $tagsAfter,
		?string $params = null,
		?UserIdentity $performer = null,
		$timestamp = false
	) {
		parent::__construct( $timestamp );

		$this->declareEventType( self::TYPE );

		self::assertTagData( $tagsBefore, '$tagsBefore' );
		self::assertTagData( $tagsAfter, '$tagsAfter' );

		$this->tagsBefore = $tagsBefore;
		$this->tagsAfter = $tagsAfter;
		$this->params = $params;
		$this->performer = $performer;
	}

	/**
	 * Returns the public tags that were effectively added by the update.
	 * Tags that were already present before the update are not included.
	 *
	 * @return array<string,array{id:int,params:?string}> Map of tag name to tag data
	 */
	public function getAddedPublicTags(): array {
		return self::filterPublicTags( $this->getAddedPublicAndPrivateTags() );
	}

	/**
	 * Returns the private tags that were effectively added by the update.
	 * Tags that were already present before the update are not included.
	 *
	 * @warning The returned tags must not be exposed to users who are not
	 *   allowed to view them, see ChangeTagsStore::filterViewableTags().
	 *
	 * @return array<string,array{id:int,params:?string}> Map of tag name to tag data
	 */
	public function getAddedPrivateTags(): array {
		return self::filterPrivateTags( $this->getAddedPublicAndPrivateTags() );
	}

	/**
	 * Returns the tags that were effectively added by the update.
	 * Tags that were already present before the update are not included.
	 *
	 * @warning The returned tags may include private tags.
	 *   Use getAddedPublicTags() to get only public tags, or
	 *   ChangeTagsStore::filterViewableTags() to filter for a specific viewer.
	 *
	 * @return array<string,array{id:int,params:?string}> Map of tag name to tag data
	 */
	public function getAddedPublicAndPrivateTags(): array {
		return array_diff_key( $this->tagsAfter, $this->tagsBefore );
	}

	/**
	 * Returns the public tags that were effectively removed by the update.
	 * Tags that were not present before the update are not included.
	 *
	 * @return array<string,array{id:int,params:?string}> Map of tag name to tag data
	 */
	public function getRemovedPublicTags(): array {
		return self::filterPublicTags( $this->getRemovedPublicAndPrivateTags() );
	}

	/**
	 * Returns the private tags that were effectively removed by the update.
	 * Tags that were not present before the update are not included.
	 *
	 * @warning The returned tags must not be exposed to users who are not
	 *   allowed to view them, see ChangeTagsStore::filterViewableTags().
	 *
	 * @return array<string,array{id:int,params:?string}> Map of tag name to tag data
	 */
	public function getRemovedPrivateTags(): array {
		return self::filterPrivateTags( $this->getRemovedPublicAndPrivateTags() );
	}

	/**
	 * Returns the tags that were effectively removed by the update.
	 * Tags that were not present before the update are not included.
	 *
	 * @warning The returned tags may include private tags.
	 *   Use getRemovedPublicTags() to get only public tags, or
	 *   ChangeTagsStore::filterViewableTags() to filter for a specific viewer.
	 *
	 * @return array<string,array{id:int,params:?string}> Map of tag name to tag data
	 */
	public function getRemovedPublicAndPrivateTags(): array {
		return array_diff_key( $this->tagsBefore, $this->tagsAfter );
	}

	/**
	 * Returns the public tags that were present before the update.
	 *
	 * @return array<string,array{id:int,params:?string}> Map of tag name to tag data
	 */
	public function getPublicTagsBefore(): array {
		return self::filterPublicTags( $this->tagsBefore );
	}

	/**
	 * Returns the private tags that were present before the update.
	 *
	 * @warning The returned tags must not be exposed to users who are not
	 *   allowed to view them, see ChangeTagsStore::filterViewableTags().
	 *
	 * @return array<string,array{id:int,params:?string}> Map of tag name to tag data
	 */
	public function getPrivateTagsBefore(): array {
		return self::filterPrivateTags( $this->tagsBefore );
	}

	/**
	 * Returns the tags that were present before the update.
	 *
	 * @warning The returned tags may include private tags.
	 *   Use getPublicTagsBefore() to get only public tags, or
	 *   ChangeTagsStore::filterViewableTags() to filter for a specific viewer.
	 *
	 * @return array<string,array{id:int,params:?string}> Map of tag name to tag data
	 */
	public function getPublicAndPrivateTagsBefore(): array {
		return $this->tagsBefore;
	}

	/**
	 * Returns the public tags that are present after the update.
	 *
	 * @return array<string,array{id:int,params:?string}> Map of tag name to tag data
	 */
	public function getPublicTagsAfter(): array {
		return self::filterPublicTags( $this->tagsAfter );
	}

	/**
	 * Returns the private tags that are present after the update.
	 *
	 * @warning The returned tags must not be exposed to users who are not
	 *   allowed to view them, see ChangeTagsStore::filterViewableTags().
	 *
	 * @return array<string,array{id:int,params:?string}> Map of tag name to tag data
	 */
	public function getPrivateTagsAfter(): array {
		return self::filterPrivateTags( $this->tagsAfter );
	}

	/**
	 * Returns the tags that are present after the update.
	 *
	 * @warning The returned tags may include private tags.
	 *   Use getPublicTagsAfter() to get only public tags, or
	 *   ChangeTagsStore::filterViewableTags() to filter for a specific viewer.
	 *
	 * @return array<string,array{id:int,params:?string}> Map of tag name to tag data
	 */
	public function getPublicAndPrivateTagsAfter(): array {
		return $this->tagsAfter;
	}

	/**
	 * Returns the params associated with the added tags, if any.
	 * @see ChangeTagsStore::updateTags()
	 */
	public function getParams(): ?string {
		return $this->params;
	}

	/**
	 * Returns the user that performed the tagging, if the tagging is subsequent
	 * to the tagged action. Returns null if the tagging accompanies the action,
	 * in which case the performer is the author of the tagged action itself.
	 */
	public function getPerformer(): ?UserIdentity {
		return $this->performer;
	}

	/**
	 * Returns only the public tags from the given map.
	 *
	 * @param array<string,array{id:int,params:?string}> $tags
	 * @return array<string,array{id:int,params:?string}>
	 */
	private static function filterPublicTags( array $tags ): array {
		return array_filter(
			$tags,
			static fn ( string $tag ) => !str_starts_with( $tag, ChangeTagsStore::PRIVATE_TAG_PREFIX ),
			ARRAY_FILTER_USE_KEY
		);
	}

	/**
	 * Returns only the private tags from the given map.
	 *
	 * @param array<string,array{id:int,params:?string}> $tags
	 * @return array<string,array{id:int,params:?string}>
	 */
	private static function filterPrivateTags( array $tags ): array {
		return array_filter(
			$tags,
			static fn ( string $tag ) => str_starts_with( $tag, ChangeTagsStore::PRIVATE_TAG_PREFIX ),
			ARRAY_FILTER_USE_KEY
		);
	}

	/**
	 * Makes sure that each value in the given map is tag data,
	 * see the class documentation.
	 *
	 * @param array $tags Map of tag name to tag data
	 * @param string $name The name of the parameter
	 */
	private static function assertTagData( array $tags, string $name ): void {
		Assert::parameterElementType( 'array', $tags, $name );
		foreach ( $tags as $tagData ) {
			Assert::parameter(
				is_int( $tagData['id'] ?? null ) &&
				array_key_exists( 'params', $tagData ) &&
				( $tagData['params'] === null || is_string( $tagData['params'] ) ),
				$name,
				'each value must have an integer "id" and a string or null "params"'
			);
		}
	}

}
