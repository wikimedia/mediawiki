<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface BeforeParserFetchTemplateAndtitleHook {
	/**
	 * Before a template is fetched by Parser.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $parser Parser object
	 * @param ?mixed $title title of the template
	 * @param ?mixed &$skip skip this template and link it?
	 * @param ?mixed &$id the id of the revision being parsed
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBeforeParserFetchTemplateAndtitle( $parser, $title, &$skip,
		&$id
	);
}
