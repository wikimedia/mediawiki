<?php
/**
 * External authentication with a vBulletin database.
 *
 * Copyright © 2009 Aryeh Gregor
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
 * This class supports the proprietary vBulletin forum system
 * <http://www.vbulletin.com>, versions 3.5 and up.  It calls no functions or
 * code, only reads from the database.  Example lines to put in
 * LocalSettings.php:
 *
 *   $wgExternalAuthType = 'ExternalUser_vB';
 *   $wgExternalAuthConf = array(
 *       'server' => 'localhost',
 *       'username' => 'forum',
 *       'password' => 'udE,jSqDJ<""p=fI.K9',
 *       'dbname' => 'forum',
 *       'tablePrefix' => '',
 *       'cookieprefix' => 'bb'
 *   );
 *
 * @ingroup ExternalUser
 */
class ExternalUser_vB extends ExternalUser {
	private $mRow;

	protected function initFromName( $name ) {
		return $this->initFromCond( array( 'username' => $name ) );
	}

	protected function initFromId( $id ) {
		return $this->initFromCond( array( 'userid' => $id ) );
	}

	protected function initFromCookie() {
		# Try using the session table.  It will only have a row if the user has
		# an active session, so it might not always work, but it's a lot easier
		# than trying to convince PHP to give us vB's $_SESSION.
		global $wgExternalAuthConf, $wgRequest;
		if ( !isset( $wgExternalAuthConf['cookieprefix'] ) ) {
			$prefix = 'bb';
		} else {
			$prefix = $wgExternalAuthConf['cookieprefix'];
		}
		if ( $wgRequest->getCookie( 'sessionhash', $prefix ) === null ) {
			return false;
		}

		$db = $this->getDb();

		$row = $db->selectRow(
			array( 'session', 'user' ),
			$this->getFields(),
			array(
				'session.userid = user.userid',
				'sessionhash' => $wgRequest->getCookie( 'sessionhash', $prefix ),
			),
			__METHOD__
		);
		if ( !$row ) {
			return false;
		}
		$this->mRow = $row;

		return true;
	}

	private function initFromCond( $cond ) {
		$db = $this->getDb();

		$row = $db->selectRow(
			'user',
			$this->getFields(),
			$cond,
			__METHOD__
		);
		if ( !$row ) {
			return false;
		}
		$this->mRow = $row;

		return true;
	}

	private function getDb() {
		global $wgExternalAuthConf;
		return DatabaseBase::factory( 'mysql',
			array(
				'host' => $wgExternalAuthConf['server'],
				'user' => $wgExternalAuthConf['username'],
				'password' => $wgExternalAuthConf['password'],
				'dbname' => $wgExternalAuthConf['dbname'],
				'tablePrefix' => $wgExternalAuthConf['tablePrefix'],
			)
		);
	}

	private function getFields() {
		return array( 'user.userid', 'username', 'password', 'salt', 'email',
			'usergroupid', 'membergroupids' );
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
