<?php

namespace MediaWiki\Content\Hook;

use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ContentModelCanBeUsedOnHook {
	/**
	 * Use this hook to determine whether a content model can be used on a given page.
	 * This is especially useful to prevent some content models from being used in a
	 * certain location.
	 *
	 * @since 1.35
	 *
	 * @param string $contentModel Content model ID
	 * @param Title $title
	 * @param bool &$ok Whether it is OK to use $contentModel on $title.
	 *   Handler functions that modify $ok should generally return false to prevent
	 *   the remaining hooks from further modifying $ok.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onContentModelCanBeUsedOn( $contentModel, $title, &$ok );
}
