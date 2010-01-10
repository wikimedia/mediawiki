<?php

# Copyright (C) 2009 Aryeh Gregor
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
# http://www.gnu.org/copyleft/gpl.html

/**
 * This class supports external authentication from a literal array dumped in
 * LocalSettings.php.  It's mostly useful for testing.  Example configuration:
 *
 *   $wgExternalAuthType = 'ExternalUser_Hardcoded';
 *   $wgExternalAuthConf = array(
 *       'Bob Smith' => array(
 *           'password' => 'literal string',
 *           'emailaddress' => 'bob@example.com',
 *       ),
 *   );
 *
 * Multiple names may be provided.  The keys of the inner arrays can be either
 * 'password', or the name of any preference.
 *
 * @ingroup ExternalUser
 */
class ExternalUser_Hardcoded extends ExternalUser {
	private $mName;

	protected function initFromName( $name ) {
		global $wgExternalAuthConf;

		if ( isset( $wgExternalAuthConf[$name] ) ) {
			$this->mName = $name;
			return true;
		}
		return false;
	}

	protected function initFromId( $id ) {
		return $this->initFromName( $id );
	}

	public function getId() {
		return $this->mName;
	}

	public function getName() {
		return $this->mName;
	}

	public function authenticate( $password ) {
		global $wgExternalAuthConf;

		return isset( $wgExternalAuthConf[$this->mName]['password'] )
			&& $wgExternalAuthConf[$this->mName]['password'] == $password;
	}

	public function getPref( $pref ) {
		global $wgExternalAuthConf;

		if ( isset( $wgExternalAuthConf[$this->mName][$pref] ) ) {
			return $wgExternalAuthConf[$this->mName][$pref];
		}
		return null;
	}

	# TODO: Implement setPref() via regex on LocalSettings.  (Just kidding.)
}
