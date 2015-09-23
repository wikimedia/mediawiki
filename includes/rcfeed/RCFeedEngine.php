<?php

/**
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
 * Interface for RC feed engines, which send formatted notifications
 *
 * @since 1.22
 */
interface RCFeedEngine {
	/**
	 * Sends some text to the specified live feed.
	 *
	 * @see IRCColourfulRCFeedFormatter::cleanupForIRC
	 * @param array $feed The feed, as configured in an associative array
	 * @param string $line The text to send
	 * @return bool Success
	 */
	public function send( array $feed, $line );
}
