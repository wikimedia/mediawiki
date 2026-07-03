<?php

namespace MediaWiki\ChangeTags\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ListRestrictedTags" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ListRestrictedTagsHook {
	/**
	 * This hook is called when building the map of rights-restricted change tags to the rights that can view them.
	 *
	 * A restricted tag is one whose name starts with {@link ChangeTagsStore::PRIVATE_TAG_PREFIX}. A rights-restricted
	 * tag that is not present in the map is visible to no-one.
	 *
	 * @param array<string,string|string[]> &$restrictedTags Map of tag name to the right(s) required to view the tag.
	 *   A viewer may see the tag if they have any of the listed rights. All keys MUST use the
	 *   {@link ChangeTagsStore::PRIVATE_TAG_PREFIX} prefix.
	 * @since 1.47
	 */
	public function onListRestrictedTags( array &$restrictedTags ): void;
}
