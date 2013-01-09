<?php

/**
 * Implementation of SiteList using GenericArrayObject.
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
class SiteArray extends GenericArrayObject implements SiteList {
	/**
	 * Update this version number when the SiteArray format
	 * changes in an incompatible way
	 *
	 * @since 1.21
	 *
	 * @var integer
	 */
	const CACHE_VERSION = 1;

	/**
	 * Version number of the SiteArray format of the currently used object
	 *
	 * @since 1.21
	 *
	 * @var integer
	 */
	public $cacheVersion = self::CACHE_VERSION;

	/**
	 * Internal site identifiers pointing to their sites offset value.
	 *
	 * @since 1.21
	 *
	 * @var array of integer
	 */
	protected $byInternalId = array();

	/**
	 * Global site identifiers pointing to their sites offset value.
	 *
	 * @since 1.21
	 *
	 * @var array of string
	 */
	protected $byGlobalId = array();

	/**
	 * @see GenericArrayObject::getObjectType
	 *
	 * @since 1.21
	 *
	 * @return string
	 */
	public function getObjectType() {
		return 'Site';
	}

	/**
	 * @see GenericArrayObject::preSetElement
	 *
	 * @since 1.21
	 *
	 * @param int|string $index
	 * @param Site $site
	 *
	 * @return boolean
	 */
	protected function preSetElement( $index, $site ) {
		if ( $this->hasSite( $site->getGlobalId() ) ) {
			$this->removeSite( $site->getGlobalId() );
		}

		$this->byGlobalId[$site->getGlobalId()] = $index;
		$this->byInternalId[$site->getInternalId()] = $index;

		return true;
	}

	/**
	 * @see ArrayObject::offsetUnset()
	 *
	 * @since 1.21
	 *
	 * @param mixed $index
	 */
	public function offsetUnset( $index ) {
		if ( $this->offsetExists( $index ) ) {
			/**
			 * @var Site $site
			 */
			$site = $this->offsetGet( $index );

			unset( $this->byGlobalId[$site->getGlobalId()] );
			unset( $this->byInternalId[$site->getInternalId()] );
		}

		parent::offsetUnset( $index );
	}

	/**
	 * @see SiteList::getGlobalIdentifiers
	 *
	 * @since 1.21
	 *
	 * @return array
	 */
	public function getGlobalIdentifiers() {
		return array_keys( $this->byGlobalId );
	}

	/**
	 * @see SiteList::hasSite
	 *
	 * @param string $globalSiteId
	 *
	 * @return boolean
	 */
	public function hasSite( $globalSiteId ) {
		return array_key_exists( $globalSiteId, $this->byGlobalId );
	}

	/**
	 * @see SiteList::getSite
	 *
	 * @since 1.21
	 *
	 * @param string $globalSiteId
	 *
	 * @return Site
	 */
	public function getSite( $globalSiteId ) {
		return $this->offsetGet( $this->byGlobalId[$globalSiteId] );
	}

	/**
	 * @see SiteList::removeSite
	 *
	 * @since 1.21
	 *
	 * @param string $globalSiteId
	 */
	public function removeSite( $globalSiteId ) {
		$this->offsetUnset( $this->byGlobalId[$globalSiteId] );
	}

	/**
	 * @see SiteList::isEmpty
	 *
	 * @since 1.21
	 *
	 * @return boolean
	 */
	public function isEmpty() {
		return $this->byGlobalId === array();
	}

	/**
	 * @see SiteList::hasInternalId
	 *
	 * @param integer $id
	 *
	 * @return boolean
	 */
	public function hasInternalId( $id ) {
		return array_key_exists( $id, $this->byInternalId );
	}

	/**
	 * @see SiteList::getSiteByInternalId
	 *
	 * @since 1.21
	 *
	 * @param integer $id
	 *
	 * @return Site
	 */
	public function getSiteByInternalId( $id ) {
		return $this->offsetGet( $this->byInternalId[$id] );
	}

	/**
	 * @see SiteList::removeSiteByInternalId
	 *
	 * @since 1.21
	 *
	 * @param integer $id
	 */
	public function removeSiteByInternalId( $id ) {
		$this->offsetUnset( $this->byInternalId[$id] );
	}

	/**
	 * @see SiteList::setSite
	 *
	 * @since 1.21
	 *
	 * @param Site $site
	 */
	public function setSite( Site $site ) {
		$this[] = $site;
	}

	/**
	 * @see GenericArrayObject::getSerializationData
	 *
	 * @since 1.21
	 *
	 * @return array
	 */
	protected function getSerializationData() {
		return array_merge(
			parent::getSerializationData(),
			array(
				'cacheVersion' => self::CACHE_VERSION,
				'internalIds' => $this->byInternalId,
				'globalIds' => $this->byGlobalId,
			)
		);
	}

	/**
	 * @see GenericArrayObject::unserialize
	 *
	 * @since 1.21
	 *
	 * @param string $serialization
	 *
	 * @return array
	 */
	public function unserialize( $serialization ) {
		$serializationData = parent::unserialize( $serialization );

		$this->cacheVersion = $serializationData['cacheVersion'];
		$this->byInternalId = $serializationData['internalIds'];
		$this->byGlobalId = $serializationData['globalIds'];

		return $serializationData;
	}

}
