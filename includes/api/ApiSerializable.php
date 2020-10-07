<?php
/**
 * Copyright © 2015 Wikimedia Foundation and contributors
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

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
