<?php

namespace MediaWiki\User\Hook;

use MediaWiki\Context\IContextSource;
use Wikimedia\Message\MessageSpecifier;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UserRequirementsCondition" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UserRequirementsConditionDisplayHook {
	/**
	 * Use this hook to specify a custom message for displaying user requirements conditions in the user interface.
	 * By default, conditions are displayed using the message "listgrouprights-restrictedgroups-cond-$type", with all
	 * arguments passed as parameters to the message. By implementing this hook, extensions can specify a different
	 * message key or apply preprocessing to the arguments before passing them to the message.
	 *
	 * @since 1.46
	 *
	 * @param string|int $type The condition type
	 * @param array $args Arguments to the condition
	 * @param IContextSource $context The context of the request where the condition is being displayed.
	 * @param null|MessageSpecifier &$messageSpec The message to use. If null, the default message will be used.
	 */
	public function onUserRequirementsConditionDisplay(
		$type,
		array $args,
		IContextSource $context,
		?MessageSpecifier &$messageSpec
	): void;
}
