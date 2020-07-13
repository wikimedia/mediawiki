<?php

namespace MediaWiki\Hook;

use Parser;
use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface BeforeParserFetchTemplateAndtitleHook {
	/**
	 * This hook is called before a template is fetched by Parser.
	 *
	 * @since 1.35
	 *
	 * @param Parser $parser
	 * @param Title $title Title of the template
	 * @param bool &$skip Skip this template and link it?
	 * @param int &$id ID of the revision being parsed
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBeforeParserFetchTemplateAndtitle( $parser, $title, &$skip,
		&$id
	);
}
