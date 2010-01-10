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
 * This class supports authentication against an external MediaWiki database,
 * probably any version back to 1.5 or something.  Example configuration:
 *
 *   $wgExternalAuthType = 'ExternalUser_MediaWiki';
 *   $wgExternalAuthConf = array(
 *       'DBtype' => 'mysql',
 *       'DBserver' => 'localhost',
 *       'DBname' => 'wikidb',
 *       'DBuser' => 'quasit',
 *       'DBpassword' => 'a5Cr:yf9u-6[{`g',
 *       'DBprefix' => '',
 *   );
 *
 * All fields must be present.  These mean the same things as $wgDBtype, 
 * $wgDBserver, etc.  This implementation is quite crude; it could easily 
 * support multiple database servers, for instance, and memcached, and it 
 * probably has bugs.  Kind of hard to reuse code when things might rely on who 
 * knows what configuration globals.
 *
 * If either wiki uses the UserComparePasswords hook, password authentication 
 * might fail unexpectedly unless they both do the exact same validation.  
 * There may be other corner cases like this where this will fail, but it 
 * should be unlikely.
 *
 * @ingroup ExternalUser
 */
class ExternalUser_MediaWiki extends ExternalUser {
	private $mRow, $mDb;

	protected function initFromName( $name ) {
		# We might not need the 'usable' bit, but let's be safe.  Theoretically 
		# this might return wrong results for old versions, but it's probably 
		# good enough.
		$name = User::getCanonicalName( $name, 'usable' );

		if ( !is_string( $name ) ) {
			return false;
		}

		return $this->initFromCond( array( 'user_name' => $name ) );
	}

	protected function initFromId( $id ) {
		return $this->initFromCond( array( 'user_id' => $id ) );
	}

	private function initFromCond( $cond ) {
		global $wgExternalAuthConf;

		$class = 'Database' . $wgExternalAuthConf['DBtype'];
		$this->mDb = new $class(
			$wgExternalAuthConf['DBserver'],
			$wgExternalAuthConf['DBuser'],
			$wgExternalAuthConf['DBpassword'],
			$wgExternalAuthConf['DBname'],
			false,
			0,
			$wgExternalAuthConf['DBprefix']
		);

		$row = $this->mDb->selectRow(
			'user',
			array(
				'user_name', 'user_id', 'user_password', 'user_email',
				'user_email_authenticated'
			),
			$cond,
			__METHOD__
		);
		if ( !$row ) {
			return false;
		}
		$this->mRow = $row;

		return true;
	}

	# TODO: Implement initFromCookie().

	public function getId() {
		return $this->mRow->user_id;
	}

	public function getName() {
		return $this->mRow->user_name;
	}

	public function authenticate( $password ) {
		# This might be wrong if anyone actually uses the UserComparePasswords hook 
		# (on either end), so don't use this if you those are incompatible.
		return User::comparePasswords( $this->mRow->user_password, $password,
			$this->mRow->user_id );	
	}

	public function getPref( $pref ) {
		# FIXME: Return other prefs too.  Lots of global-riddled code that does 
		# this normally.
		if ( $pref === 'emailaddress'
		&& $this->row->user_email_authenticated !== null ) {
			return $this->mRow->user_email;
		}
		return null;
	}

	public function getGroups() {
		# FIXME: Untested.
		$groups = array();
		$res = $this->mDb->select(
			'user_groups',
			'ug_group',
			array( 'ug_user' => $this->mRow->user_id ),
			__METHOD__
		);
		foreach ( $res as $row ) {
			$groups[] = $row->ug_group;
		}
		return $groups;
	}

	# TODO: Implement setPref().
}
