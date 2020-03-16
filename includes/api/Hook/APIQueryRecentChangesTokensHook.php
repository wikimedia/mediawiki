<?php

namespace MediaWiki\Api\Hook;

/**
 * @deprecated since 1.24 Use ApiQueryTokensRegisterTypes instead.
 * @ingroup Hooks
 */
interface APIQueryRecentChangesTokensHook {
	/**
	 * Use this hook to add custom tokens to list=recentchanges. Every token has an
	 * action, which will be used in the rctoken parameter and in the output
	 * (actiontoken="..."), and a callback function which should return the token, or
	 * false if the user isn't allowed to obtain it. The prototype of the callback
	 * function is func($pageid, $title, $rc), where $pageid is the page ID of the
	 * page associated to the revision the token is requested for, $title the
	 * associated Title object and $rc the associated RecentChange object. In the
	 * hook, add your callback to the $tokenFunctions array and return true
	 * (returning false makes no sense).
	 *
	 * @since 1.35
	 *
	 * @param array &$tokenFunctions [ action => callback ]
	 * @return bool|void True or no return value
	 */
	public function onAPIQueryRecentChangesTokens( &$tokenFunctions );
}
