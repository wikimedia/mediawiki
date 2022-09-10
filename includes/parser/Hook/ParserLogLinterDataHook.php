<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ParserLogLinterData" to register handlers
 * implementing this interface.
 *
 * @unstable
 * @ingroup Hooks
 */
interface ParserLogLinterDataHook {
	/**
	 * This hook allows Parsoid to log additional linting information
	 * during a parse to a database maintained by the [[Extension:Linter]].
	 * See [[Extension:Linter]] for more information.
	 *
	 * @param string $title The title of the page being parsed
	 * @param int $revId The revision ID of the page being parsed
	 *  (this hook is typically only invoked if this corresponds to the
	 *  most recent revision of the given page).
	 * @param array $lints The linter information for the given revision
	 *  of the page.
	 * @return bool Typically returns true, to allow all registered hooks a
	 *  chance to record these lints.
	 *
	 * @unstable temporary hook
	 * @since 1.39
	 */
	public function onParserLogLinterData(
		string $title,
		int $revId,
		array $lints
	): bool;
}
