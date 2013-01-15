<?php

interface Santiy {}

class SaneTable extends ORMTable implements Santiy {

	protected $tableName;
	protected $fields;
	protected $fieldPrefix = '';
	protected $rowClass = 'ORMRow';
	protected $defaults = array();

	/**
	 * Constructor.
	 *
	 * @since 1.21
	 *
	 * @param string $tableName
	 * @param string[] $fields
	 * @param array $defaults
	 */
	public function __construct( $tableName, array $fields, array $defaults = array() ) {
		$this->tableName = $tableName;
		$this->fields = $fields;
		$this->defaults = $defaults;
	}

	/**
	 * @see ORMTable::getName
	 *
	 * @since 1.21
	 *
	 * @return string
	 */
	public function getName() {
		return $this->tableName;
	}

	/**
	 * @see ORMTable::getFieldPrefix
	 *
	 * @since 1.21
	 *
	 * @return string
	 */
	public function getFieldPrefix() {
		return $this->fieldPrefix;
	}

	/**
	 * @see ORMTable::getRowClass
	 *
	 * @since 1.21
	 *
	 * @return string
	 */
	public function getRowClass() {
		return $this->rowClass;
	}

	/**
	 * @see ORMTable::getFields
	 *
	 * @since 1.21
	 *
	 * @return array
	 */
	public function getFields() {
		return $this->fields;
	}

	/**
	 * @see ORMTable::getDefaults
	 *
	 * @since 1.21
	 *
	 * @return array
	 */
	public function getDefaults() {
		return $this->defaults;
	}

}

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
class SitesTable {



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

}
