<?php
/**
 * Dummy search engine
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Search
 */

/**
 * Dummy class to be used when non-supported Database engine is present.
 * @todo FIXME: Dummy class should probably try something at least mildly useful,
 * such as a LIKE search through titles.
 * @ingroup Search
 */
class SearchEngineDummy extends SearchEngine {
	// no-op
}
