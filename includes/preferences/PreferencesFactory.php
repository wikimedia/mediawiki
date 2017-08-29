<?php
/**
 * This file contains the PreferencesFactory interface.
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

namespace MediaWiki\Preferences;

use IContextSource;
use Preferences;
use User;

/**
 * A PreferencesFactory is a MediaWiki service for creating Preferences objects.
 */
interface PreferencesFactory {

	/**
	 * Get a new Preferences object.
	 * @param User $user The user to whom the preferences belong.
	 * @param IContextSource $contextSource The context in which the preferences will be used.
	 * @return Preferences
	 */
	public function newPreferences( User $user, IContextSource $contextSource );
}
