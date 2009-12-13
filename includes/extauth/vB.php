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
 * This class supports the proprietary vBulletin forum system
 * <http://www.vbulletin.com>, versions 3.5 and up.  It calls no functions or
 * code, only reads from the database.  Example lines to put in
 * LocalSettings.php:
 *
 *   $wgExternalAuthType = 'vB';
 *   $wgExternalAuthConf = array(
 *       'server' => 'localhost',
 *       'username' => 'forum',
 *       'password' => 'udE,jSqDJ<""p=fI.K9',
 *       'dbname' => 'forum',
 *       'tableprefix' => ''
 *   );
 */
class ExternalUser_vB extends ExternalUser {
	private $mDb, $mRow;

	protected function initFromName( $name ) {
		return $this->initFromCond( array( 'username' => $name ) );
	}

	protected function initFromId( $id ) {
		return $this->initFromCond( array( 'userid' => $id ) );
	}

	# initFromCookie() not yet implemented

	private function initFromCond( $cond ) {
		global $wgExternalAuthConf;

		$this->mDb = new Database(
			$wgExternalAuthConf['server'],
			$wgExternalAuthConf['username'],
			$wgExternalAuthConf['password'],
			$wgExternalAuthConf['dbname'],
			false, 0,
			$wgExternalAuthConf['tableprefix']
		);

		$row = $this->mDb->selectRow(
			'user',
			array( 'userid', 'username', 'password', 'salt', 'email', 'usergroupid',
			'membergroupids' ),
			$cond,
			__METHOD__
		);
		if ( !$row ) {
			return false;
		}
		$this->mRow = $row;

		return true;
	}

	public function getId() { return $this->mRow->userid; }
	public function getName() { return $this->mRow->username; }

	public function authenticate( $password ) {
		# vBulletin seemingly strips whitespace from passwords
		$password = trim( $password );
		return $this->mRow->password == md5( md5( $password )
			. $this->mRow->salt );
	}

	public function getPref( $pref ) {
		if ( $pref == 'emailaddress' && $this->mRow->email ) {
			# TODO: only return if validated?
			return $this->mRow->email;
		}
		return null;
	}

	public function getGroups() {
		$groups = array( $this->mRow->usergroupid );
		$groups = array_merge( $groups, explode( ',', $this->mRow->membergroupids ) );
		$groups = array_unique( $groups );
		return $groups;
	}
}
