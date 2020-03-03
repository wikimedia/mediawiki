<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface BeforeParserFetchFileAndTitleHook {
	/**
	 * Before an image is rendered by Parser.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $parser Parser object
	 * @param ?mixed $nt the image title
	 * @param ?mixed &$options array of options to RepoGroup::findFile. If it contains 'broken'
	 *   as a key then the file will appear as a broken thumbnail.
	 * @param ?mixed &$descQuery query string to add to thumbnail URL
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBeforeParserFetchFileAndTitle( $parser, $nt, &$options,
		&$descQuery
	);
}
