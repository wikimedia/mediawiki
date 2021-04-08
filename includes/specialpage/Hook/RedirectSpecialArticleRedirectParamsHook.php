<?php

namespace MediaWiki\SpecialPage\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "RedirectSpecialArticleRedirectParams" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface RedirectSpecialArticleRedirectParamsHook {
	/**
	 * Use this hook to alter the set of parameter names such as "oldid" that
	 * are preserved when using redirecting special pages such as Special:MyPage
	 * and Special:MyTalk.
	 *
	 * @since 1.35
	 *
	 * @param string[] &$redirectParams Array of parameters preserved by redirecting special pages
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onRedirectSpecialArticleRedirectParams( &$redirectParams );
}
