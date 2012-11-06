<?php

/**
 * Represents the sites database table.
 * All access to this table should be done through this class.
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
 * @since 1.21
 *
 * @file
 * @ingroup Site
 *
 * @license GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SitesTable extends ORMTable {

	/**
	 * @see IORMTable::getName()
	 * @since 1.21
	 * @return string
	 */
	public function getName() {
		return 'sites';
	}

	/**
	 * @see IORMTable::getFieldPrefix()
	 * @since 1.21
	 * @return string
	 */
	public function getFieldPrefix() {
		return 'site_';
	}

	/**
	 * @see IORMTable::getRowClass()
	 * @since 1.21
	 * @return string
	 */
	public function getRowClass() {
		return 'SiteObject';
	}

	/**
	 * @see IORMTable::getFields()
	 * @since 1.21
	 * @return array
	 */
	public function getFields() {
		return array(
			'id' => 'id',

			// Site data
			'global_key' => 'str',
			'type' => 'str',
			'group' => 'str',
			'source' => 'str',
			'language' => 'str',
			'protocol' => 'str',
			'domain' => 'str',
			'data' => 'array',

			// Site config
			'forward' => 'bool',
			'config' => 'array',
		);
	}

	/**
	 * @see IORMTable::getDefaults()
	 * @since 1.21
	 * @return array
	 */
	public function getDefaults() {
		return array(
			'type' => Site::TYPE_UNKNOWN,
			'group' => Site::GROUP_NONE,
			'source' => Site::SOURCE_LOCAL,
			'data' => array(),

			'forward' => false,
			'config' => array(),
			'language' => 'en', // XXX: can we default to '' instead?
		);
	}

	/**
	 * Returns the class name for the provided site type.
	 *
	 * @since 1.21
	 *
	 * @param integer $siteType
	 *
	 * @return string
	 */
	protected static function getClassForType( $siteType ) {
		global $wgSiteTypes;
		return array_key_exists( $siteType, $wgSiteTypes ) ? $wgSiteTypes[$siteType] : 'SiteObject';
	}

	/**
	 * Factory method to construct a new Site instance.
	 *
	 * @since 1.21
	 *
	 * @param array $data
	 * @param boolean $loadDefaults
	 *
	 * @return Site
	 */
	public function newRow( array $data, $loadDefaults = false ) {
		if ( !array_key_exists( 'type', $data ) ) {
			$data['type'] = Site::TYPE_UNKNOWN;
		}

		$class = static::getClassForType( $data['type'] );

		return new $class( $this, $data, $loadDefaults );
	}

}