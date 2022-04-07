<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ParserFetchTemplateData" to register handlers
 * implementing this interface.
 *
 * @unstable
 * @ingroup Hooks
 */
interface ParserFetchTemplateDataHook {
	/**
	 * This hook allows Parsoid to fetch additional serialization information
	 * about a Template, including the type of its arguments, whether they
	 * should be serialized as inline or block style wikitext, etc.
	 * See [[Extension:TemplateData]] for more information.
	 *
	 * @param string[] $titles An array of template names
	 * @param array<string,object> &$tplData The hook will create an
	 *  associative array, mapping each given
	 *  template name to an object representing the TemplateData for that
	 *  template, or special objects if the title doesn't exist, is missing
	 *  TemplateData, or has malformed TemplateData.
	 * @return bool Typically returns true, to allow all registered hooks a
	 *  chance to fill in template data.
	 *
	 * @unstable temporary hook; will be cleaned up before it is made stable
	 * @since 1.39
	 */
	public function onParserFetchTemplateData(
		array $titles,
		array &$tplData
	): bool;
}
