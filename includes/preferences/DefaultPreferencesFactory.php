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
 * The DefaultPreferencesFactory class is a PreferencesFactory for creating a standard Preferences
 * object.
 */
class DefaultPreferencesFactory implements PreferencesFactory {

	/**
	 * @inheritdoc
	 */
	public function newPreferences( User $user, IContextSource $contextSource ) {
		$preferences = new Preferences();
		$preferences->setUser( $user );
		$preferences->setContext( $contextSource );
		return $preferences;
	}
}
