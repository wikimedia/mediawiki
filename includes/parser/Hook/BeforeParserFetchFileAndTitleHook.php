<?php

namespace MediaWiki\Hook;

use Parser;
use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface BeforeParserFetchFileAndTitleHook {
	/**
	 * This hook is called before an image is rendered by Parser.
	 *
	 * @since 1.35
	 *
	 * @param Parser $parser
	 * @param Title $nt Image title
	 * @param array &$options Array of options to RepoGroup::findFile. If it contains 'broken'
	 *   as a key then the file will appear as a broken thumbnail.
	 * @param string &$descQuery Query string to add to thumbnail URL
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBeforeParserFetchFileAndTitle( $parser, $nt, &$options,
		&$descQuery
	);
}
