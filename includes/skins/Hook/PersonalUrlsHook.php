<?php

namespace MediaWiki\Hook;

use MediaWiki\Title\Title;
use SkinTemplate;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "PersonalUrls" to register handlers implementing this interface.
 *
 * @stable to implement
 * @deprecated since 1.39 Use SkinTemplateNavigation__Universal instead.
 * @ingroup Hooks
 */
interface PersonalUrlsHook {
	/**
	 * Use this hook to alter the user-specific navigation links (e.g. "my page,
	 * my talk page, my contributions" etc).
	 *
	 * @since 1.35
	 * @deprecated since 1.39 Use SkinTemplateNavigation__Universal instead.
	 *
	 * @param array &$personal_urls Array of link specifiers (see SkinTemplate.php)
	 * @param Title &$title Current page
	 * @param SkinTemplate $skin SkinTemplate object providing context (e.g. to check if the user is
	 *   logged in, etc.)
	 * @return void This hook must not abort, it must return no value
	 */
	public function onPersonalUrls( &$personal_urls, &$title, $skin ): void;
}
