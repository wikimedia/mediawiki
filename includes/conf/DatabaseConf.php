<?php
/**
 * Database configuration class
 *
 * Copyright © 2011 Chad Horohoe <chadh@wikimedia.org>
 * http://www.mediawiki.org/
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
 * @ingroup Config
 */
class DatabaseConf extends Conf {
	/**
	 * @see Conf::initChangedSettings()
	 */
	protected function initChangedSettings() {
		$res = wfGetDB( DB_MASTER )->select( 'config', '*', array(), __METHOD__ );
		foreach( $res as $row ) {
			$this->values[$row->cf_name] = unserialize( $row->cf_value );
		}
	}

	/**
	 * @see Conf::writeSetting()
	 */
	protected function writeSetting( $name, $value ) {
		$dbw = wfGetDB( DB_MASTER );
		$value = serialize( $value );
		if( $dbw->selectRow( 'config', 'cf_name', array( 'cf_name' => $name ), __METHOD__ ) ) {
			$dbw->update( 'config', array( 'cf_value' => $value ),
				array( 'cf_name' => $name ), __METHOD__ );
		} else {
			$dbw->insert( 'config',
				array( 'cf_name' => $name, 'cf_value' => $value ), __METHOD__ );
		}
		return (bool)$dbw->affectedRows();
	}
}
