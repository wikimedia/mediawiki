<?php

/**
 * Instantiable ORMTable that takes the required table information in the constructor.
 * @see ORMTable
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
class ConfigurableORMTable extends ORMTable {

	/**
	 * @since 1.21
	 *
	 * @var string
	 */
	protected $tableName;

	/**
	 * @since 1.21
	 *
	 * @var string[]
	 */
	protected $fields = array();

	/**
	 * @since 1.21
	 *
	 * @var string
	 */
	protected $fieldPrefix = '';

	/**
	 * @since 1.21
	 *
	 * @var string
	 */
	protected $rowClass = 'ORMRow';

	/**
	 * @since 1.21
	 *
	 * @var array
	 */
	protected $defaults = array();

	/**
	 * Constructor.
	 *
	 * @since 1.21
	 *
	 * @param string $tableName
	 * @param string[] $fields
	 * @param array $defaults
	 * @param string|null $rowClass
	 * @param string $fieldPrefix
	 */
	public function __construct( $tableName, array $fields, array $defaults = array(), $rowClass = null, $fieldPrefix = '' ) {
		$this->tableName = $tableName;
		$this->fields = $fields;
		$this->defaults = $defaults;

		if ( is_string( $rowClass ) ) {
			$this->rowClass = $rowClass;
		}

		$this->fieldPrefix = $fieldPrefix;
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
