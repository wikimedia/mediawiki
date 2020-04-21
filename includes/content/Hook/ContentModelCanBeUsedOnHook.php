<?php

namespace MediaWiki\Content\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ContentModelCanBeUsedOnHook {
	/**
	 * Called to determine whether that content model can
	 * be used on a given page. This is especially useful to prevent some content
	 * models to be used in some special location.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $contentModel ID of the content model in question
	 * @param ?mixed $title the Title in question.
	 * @param ?mixed &$ok Output parameter, whether it is OK to use $contentModel on $title.
	 *   Handler functions that modify $ok should generally return false to prevent
	 *   further hooks from further modifying $ok.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onContentModelCanBeUsedOn( $contentModel, $title, &$ok );
}
