<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Page;

use MediaWiki\Title\Title;

/**
 * Interface for type hinting (accepts WikiPage, Article, ImagePage, CategoryPage)
 *
 * @deprecated since 1.35, Use WikiPage or Article instead
 * @method array getActionOverrides()
 * @method Title getTitle()
 */
interface Page {
}

/** @deprecated class alias since 1.44 */
class_alias( Page::class, 'Page' );
