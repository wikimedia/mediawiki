<?php
/**
 * Copyright (C) 2017 Kunal Mehta <legoktm@member.fsf.org>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 */

/**
 * Creates a .htaccess in the vendor/ directory
 * to prevent web access.
 *
 * This class runs *outside* of the normal MediaWiki
 * environment and cannot depend upon any MediaWiki
 * code.
 */
class ComposerVendorHtaccessCreator {

	/**
	 * Handle post-install-cmd and post-update-cmd hooks
	 */
	public static function onEvent() {
		$fname = dirname( dirname( __DIR__ ) ) . "/vendor/.htaccess";
		if ( file_exists( $fname ) ) {
			// Already exists
			return;
		}

		file_put_contents( $fname, "Deny from all\n" );
	}
}
