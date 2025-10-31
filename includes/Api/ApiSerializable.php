<?php
/**
 * Copyright © 2015 Wikimedia Foundation and contributors
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

/**
 * This interface allows for overriding the default conversion applied by
 * ApiResult::validateValue().
 *
 * @note This is currently an informal interface; it need not be explicitly
 *   implemented, as long as the method is provided. This allows for extension
 *   code to maintain compatibility with older MediaWiki while still taking
 *   advantage of this where it exists.
 *
 * @stable to implement
 *
 * @ingroup API
 * @since 1.25
 */
interface ApiSerializable {
	/**
	 * Return the value to be added to ApiResult in place of this object.
	 *
	 * The returned value must not be an object, and must pass
	 * all checks done by ApiResult::validateValue().
	 *
	 * @return mixed
	 */
	public function serializeForApiResult();
}

/** @deprecated class alias since 1.43 */
class_alias( ApiSerializable::class, 'ApiSerializable' );
