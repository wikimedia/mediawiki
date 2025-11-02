<?php

namespace MediaWiki\SpecialPage\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SpecialPage_initList" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialPage_initListHook {
	/**
	 * This hook is called when setting up SpecialPageFactory::$list. Use
	 * this hook to remove a core special page or conditionally register special pages.
	 *
	 * @note The existence of a special page must not depend on the request context (e.g. current user or current
	 * title). Possible red/blue links from wiki pages are cached in the parser cache and must be stable across
	 * the requests from different users or for different pages.
	 * Use only site config or checks if extensions are loaded to add or remove special pages from the list.
	 * Override SpecialPage::userCanExecute or set a user right when calling SpecialPage::__construct,
	 * after registering the special page for all users, to restrict the access for users allowed to use the page.
	 * When replacing a (core) special page in the list, it is possible to depend on the request context,
	 * but this hook is also called from Setup and the user is not always safe to load,
	 * call User::isSafeToLoad before using any User class function or one of the user services like
	 * UserOptionsLookup/UserOptionsManager.
	 * Also the title may not be set for all requests (for e.g. api.php or load.php),
	 * so checks for title should be avoided.
	 *
	 * @since 1.35
	 *
	 * @param array &$list List of core special pages,
	 *   mapping of (canonical) page name to class name, factory callback or to ObjectFactory spec
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialPage_initList( &$list );
}
