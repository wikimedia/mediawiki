<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "CanonicalNamespaces" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface CanonicalNamespacesHook {
	/**
	 * Use this hook to add namespaces or alter the defaults.
	 * Note that if you need to specify namespace protection or content model for
	 * a namespace that is added in a CanonicalNamespaces hook handler, you
	 * should do so by altering $wgNamespaceProtection and
	 * $wgNamespaceContentModels outside the handler, in top-level scope. The
	 * point at which the CanonicalNamespaces hook fires is too late for altering
	 * these variables. This applies even if the namespace addition is
	 * conditional; it is permissible to declare a content model and protection
	 * for a namespace and then decline to actually register it.
	 *
	 * @since 1.35
	 *
	 * @param string[] &$namespaces Array of namespace numbers with corresponding canonical names
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onCanonicalNamespaces( &$namespaces );
}
