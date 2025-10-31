<?php
/**
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

/**
 * Formatter that outputs nothing, for when you don't care about the response
 * at all
 * @ingroup API
 */
class ApiFormatNone extends ApiFormatBase {

	/** @inheritDoc */
	public function getMimeType() {
		return 'text/plain';
	}

	public function execute() {
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiFormatNone::class, 'ApiFormatNone' );
