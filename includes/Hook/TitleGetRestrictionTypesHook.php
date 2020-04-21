<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface TitleGetRestrictionTypesHook {
	/**
	 * Allows extensions to modify the types of protection
	 * that can be applied.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title The title in question.
	 * @param ?mixed &$types The types of protection available.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onTitleGetRestrictionTypes( $title, &$types );
}
