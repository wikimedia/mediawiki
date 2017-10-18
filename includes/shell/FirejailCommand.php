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

namespace MediaWiki\Shell;

/**
 * Restricts execution of shell commands using firejail
 *
 * @see https://firejail.wordpress.com/
 * @since 1.31
 */
class FirejailCommand extends Command {

	/**
	 * Whether firejail can be used
	 *
	 * @return bool
	 */
	public static function isUsable() {
		// Traditional location if packaged
		return is_executable( '/usr/bin/firejail' )
			// Default location if build from source
			|| is_executable( '/usr/local/bin/firejail' );
	}

	protected function buildFinalCommand() {
		return parent::buildFinalCommand();
	}

}
