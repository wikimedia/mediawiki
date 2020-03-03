<?php

namespace MediaWiki\SpecialPage\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface RedirectSpecialArticleRedirectParamsHook {
	/**
	 * Lets you alter the set of parameter
	 * names such as "oldid" that are preserved when using redirecting special pages
	 * such as Special:MyPage and Special:MyTalk.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$redirectParams An array of parameters preserved by redirecting special pages.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onRedirectSpecialArticleRedirectParams( &$redirectParams );
}
