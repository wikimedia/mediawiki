<?php

namespace MediaWiki\User\Options\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ConditionalDefaultOptionsAddCondition" to register handlers implementing
 * this interface. It runs virtually on any request, although it should run once, performance of
 * the hook run should be taken in account.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ConditionalDefaultOptionsAddConditionHook {
	/**
	 * This hook is called when ConditionalDefaultsLookup service is created.
	 * Important: $extraConditions must be added and used in a property defined by the same extension. Using
	 * a condition from a missing extension will result in fatal error.
	 * See also Manual:$wgConditionalUserOptions.
	 *
	 * @since 1.43
	 *
	 * @param array<string,callable> &$extraConditions An empty array to add checker functions to
	 * evaluate conditions set in $wgConditionalUserOptions. The key is the name of the condition
	 * and the value a callable which will take two arguments:
	 * - UserIdentity $user: the user which option is being evaluated
	 * - array $args: the rest of arguments in the relevant condition configuration
	 * @return void This hook must not abort, it must return no value
	 */
	public function onConditionalDefaultOptionsAddCondition( array &$extraConditions ): void;
}
