<?php
/**
 * Extremely basic "skin" for API output, which needs to output a page without
 * the usual skin elements but still using CSS, JS, and such via OutputPage and
 * ResourceLoader.
 *
 * Copyright © 2014 Wikimedia Foundation and contributors
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Skin;

/**
 * SkinTemplate class for API output
 * @since 1.25
 */
class SkinApi extends SkinMustache {
	/**
	 * Extension of class methods is discouraged.
	 * Developers are encouraged to improve the flexibility of SkinMustache
	 * wherever possible.
	 */
}

/** @deprecated class alias since 1.44 */
class_alias( SkinApi::class, 'SkinApi' );
