<?php

namespace MW;

/**
 * Represents the sites database table.
 * All access to this table should be done through this class.
 *
 * @since 1.20
 *
 * @file
 * @ingroup Sites
 * @ingroup Sites
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SitesTable extends \ORMTable {

	/**
	 * @see IORMTable::getName()
	 * @since 1.20
	 * @return string
	 */
	public function getName() {
		return 'sites';
	}

	/**
	 * @see IORMTable::getFieldPrefix()
	 * @since 1.20
	 * @return string
	 */
	public function getFieldPrefix() {
		return 'site_';
	}

	/**
	 * @see IORMTable::getRowClass()
	 * @since 1.20
	 * @return string
	 */
	public function getRowClass() {
		return '\MW\SiteRow';
	}

	/**
	 * @see IORMTable::getFields()
	 * @since 1.20
	 * @return array
	 */
	public function getFields() {
		return array(
			'id' => 'id',

			// Site data
			'global_key' => 'str',
			'type' => 'int',
			'group' => 'int',
			'url' => 'str',
			'page_path' => 'str',
			'file_path' => 'str',
			'language' => 'str',
			'data' => 'array',

			// Site config
			'local_key' => 'str',
			'link_inline' => 'bool',
			'link_navigation' => 'bool',
			'forward' => 'bool',
			'config' => 'array',
		);
	}

	/**
	 * @see IORMTable::getDefaults()
	 * @since 1.20
	 * @return array
	 */
	public function getDefaults() {
		return array(
			'type' => SITE_TYPE_UNKNOWN,
			'group' => SITE_GROUP_NONE,
			'data' => array(),

			'link_inline' => false,
			'link_navigation' => false,
			'forward' => false,
			'config' => array(),
		);
	}

	/**
	 * Returns the class name for the provided site type.
	 *
	 * @since 1.20
	 *
	 * @param integer $siteType
	 *
	 * @return string
	 */
	protected static function getClassForType( $siteType ) {
		global $wgSiteTypes;
		return array_key_exists( $siteType, $wgSiteTypes ) ? $wgSiteTypes[$siteType] : 'MW\SiteRow';
	}

	/**
	 * Factory method to construct a new MW\SiteRow instance.
	 *
	 * @since 1.20
	 *
	 * @param array $data
	 * @param boolean $loadDefaults
	 *
	 * @return SiteRow
	 */
	public function newRow( array $data, $loadDefaults = false ) {
		if ( !array_key_exists( 'type', $data ) ) {
			$data['type'] = SITE_TYPE_UNKNOWN;
		}

		$class = static::getClassForType( $data['type'] );

		return new $class( $this, $data, $loadDefaults );
	}

}