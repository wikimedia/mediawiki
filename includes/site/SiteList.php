<?php
/**
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
 * Array-like collection of Site objects.
 *
 * This uses ArrayObject to intercept additions and deletions for purposes
 * such as additional indexing, and to enforce that values are restricted
 * to Site objects only.
 *
 * @since 1.21
 * @ingroup Site
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SiteList extends ArrayObject {
	/**
	 * @see SiteList::getNewOffset()
	 * @since 1.20
	 * @var int
	 */
	protected $indexOffset = 0;

	/**
	 * Internal site identifiers pointing to their sites offset value.
	 *
	 * @since 1.21
	 *
	 * @var array Maps int identifiers to local ArrayObject keys
	 */
	protected $byInternalId = [];

	/**
	 * Global site identifiers pointing to their sites offset value.
	 *
	 * @since 1.21
	 *
	 * @var array Maps string identifiers to local ArrayObject keys
	 */
	protected $byGlobalId = [];

	/**
	 * Navigational site identifiers alias inter-language prefixes
	 * pointing to their sites offset value.
	 *
	 * @since 1.23
	 *
	 * @var array Maps string identifiers to local ArrayObject keys
	 */
	protected $byNavigationId = [];

	/**
	 * @see Overrides ArrayObject::__construct https://www.php.net/manual/en/arrayobject.construct.php
	 * @since 1.20
	 * @param null|array $input
	 * @param int $flags
	 * @param string $iterator_class
	 */
	public function __construct( $input = null, $flags = 0, $iterator_class = 'ArrayIterator' ) {
		parent::__construct( [], $flags, $iterator_class );

		if ( $input !== null ) {
			foreach ( $input as $offset => $value ) {
				$this->offsetSet( $offset, $value );
			}
		}
	}

	/**
	 * @see Overrides ArrayObject::append
	 * @since 1.20
	 * @param mixed $value
	 */
	public function append( $value ): void {
		$this->setElement( null, $value );
	}

	/**
	 * @since 1.20
	 * @see Overrides ArrayObject::offsetSet()
	 * @param mixed $index
	 * @param mixed $value
	 */
	public function offsetSet( $index, $value ): void {
		$this->setElement( $index, $value );
	}

	/**
	 * Returns if the provided value has the same type as the elements
	 * that can be added to this ArrayObject.
	 *
	 * @since 1.20
	 * @param mixed $value
	 * @return bool
	 */
	protected function hasValidType( $value ) {
		$class = $this->getObjectType();
		return $value instanceof $class;
	}

	/**
	 * The class or interface type that array elements must match.
	 *
	 * @since 1.21
	 * @return string
	 */
	public function getObjectType() {
		return Site::class;
	}

	/**
	 * Find a new offset for when appending an element.
	 *
	 * @since 1.20
	 * @return int
	 */
	protected function getNewOffset() {
		while ( $this->offsetExists( $this->indexOffset ) ) {
			$this->indexOffset++;
		}

		return $this->indexOffset;
	}

	/**
	 * Actually set the element and enforce type checking and offset resolving.
	 *
	 * @since 1.20
	 * @param int|string|null $index
	 * @param Site $site
	 */
	protected function setElement( $index, $site ) {
		if ( !$this->hasValidType( $site ) ) {
			throw new InvalidArgumentException(
				'Can only add ' . $this->getObjectType() . ' implementing objects to '
				. static::class . '.'
			);
		}

		$index ??= $this->getNewOffset();

		if ( $this->hasSite( $site->getGlobalId() ) ) {
			$this->removeSite( $site->getGlobalId() );
		}

		$this->byGlobalId[$site->getGlobalId()] = $index;
		$this->byInternalId[$site->getInternalId()] = $index;

		$ids = $site->getNavigationIds();
		foreach ( $ids as $navId ) {
			$this->byNavigationId[$navId] = $index;
		}

		parent::offsetSet( $index, $site );
	}

	/**
	 * @see ArrayObject::offsetUnset()
	 *
	 * @since 1.21
	 *
	 * @param mixed $index
	 */
	public function offsetUnset( $index ): void {
		if ( $this->offsetExists( $index ) ) {
			/**
			 * @var Site $site
			 */
			$site = $this->offsetGet( $index );

			unset( $this->byGlobalId[$site->getGlobalId()] );
			unset( $this->byInternalId[$site->getInternalId()] );

			$ids = $site->getNavigationIds();
			foreach ( $ids as $navId ) {
				unset( $this->byNavigationId[$navId] );
			}
		}

		parent::offsetUnset( $index );
	}

	/**
	 * Returns all the global site identifiers.
	 * Optionally only those belonging to the specified group.
	 *
	 * @since 1.21
	 *
	 * @return array
	 */
	public function getGlobalIdentifiers() {
		return array_keys( $this->byGlobalId );
	}

	/**
	 * Returns if the list contains the site with the provided global site identifier.
	 *
	 * @param string $globalSiteId
	 *
	 * @return bool
	 */
	public function hasSite( $globalSiteId ) {
		return array_key_exists( $globalSiteId, $this->byGlobalId );
	}

	/**
	 * Returns the Site with the provided global site identifier.
	 * The site needs to exist, so if not sure, call hasGlobalId first.
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
	 * Removes the site with the specified global site identifier.
	 * The site needs to exist, so if not sure, call hasGlobalId first.
	 *
	 * @since 1.21
	 *
	 * @param string $globalSiteId
	 */
	public function removeSite( $globalSiteId ) {
		$this->offsetUnset( $this->byGlobalId[$globalSiteId] );
	}

	/**
	 * Whether the list contains no sites.
	 *
	 * @since 1.21
	 * @return bool
	 */
	public function isEmpty() {
		return $this->byGlobalId === [];
	}

	/**
	 * Returns if the list contains the site with the provided site id.
	 *
	 * @param int $id
	 *
	 * @return bool
	 */
	public function hasInternalId( $id ) {
		return array_key_exists( $id, $this->byInternalId );
	}

	/**
	 * Returns the Site with the provided site id.
	 * The site needs to exist, so if not sure, call has first.
	 *
	 * @since 1.21
	 *
	 * @param int $id
	 *
	 * @return Site
	 */
	public function getSiteByInternalId( $id ) {
		return $this->offsetGet( $this->byInternalId[$id] );
	}

	/**
	 * Removes the site with the specified site id.
	 * The site needs to exist, so if not sure, call has first.
	 *
	 * @since 1.21
	 *
	 * @param int $id
	 */
	public function removeSiteByInternalId( $id ) {
		$this->offsetUnset( $this->byInternalId[$id] );
	}

	/**
	 * Returns if the list contains the site with the provided navigational site id.
	 *
	 * @param string $id
	 *
	 * @return bool
	 */
	public function hasNavigationId( $id ) {
		return array_key_exists( $id, $this->byNavigationId );
	}

	/**
	 * Returns the Site with the provided navigational site id.
	 * The site needs to exist, so if not sure, call has first.
	 *
	 * @since 1.23
	 *
	 * @param string $id
	 *
	 * @return Site
	 */
	public function getSiteByNavigationId( $id ) {
		return $this->offsetGet( $this->byNavigationId[$id] );
	}

	/**
	 * Removes the site with the specified navigational site id.
	 * The site needs to exist, so if not sure, call has first.
	 *
	 * @since 1.23
	 *
	 * @param string $id
	 */
	public function removeSiteByNavigationId( $id ) {
		$this->offsetUnset( $this->byNavigationId[$id] );
	}

	/**
	 * Sets a site in the list. If the site was not there,
	 * it will be added. If it was, it will be updated.
	 *
	 * @since 1.21
	 *
	 * @param Site $site
	 */
	public function setSite( Site $site ) {
		$this[] = $site;
	}

	/**
	 * Returns the sites that are in the provided group.
	 *
	 * @since 1.21
	 *
	 * @param string $groupName
	 *
	 * @return SiteList
	 */
	public function getGroup( $groupName ) {
		$group = new self();

		/**
		 * @var Site $site
		 */
		foreach ( $this as $site ) {
			if ( $site->getGroup() === $groupName ) {
				$group[] = $site;
			}
		}

		return $group;
	}

	/**
	 * A version ID that identifies the serialization structure used by __serialize()
	 * and unserialize(). This is useful for constructing cache keys in cases where the cache relies
	 * on serialization for storing the SiteList.
	 *
	 * @var string A string uniquely identifying the version of the serialization structure,
	 *             not including any sub-structures.
	 */
	private const SERIAL_VERSION_ID = '2014-03-17';

	/**
	 * Returns the version ID that identifies the serialization structure used by
	 * __serialize() and unserialize(), including the structure of any nested structures.
	 * This is useful for constructing cache keys in cases where the cache relies
	 * on serialization for storing the SiteList.
	 *
	 * @return string A string uniquely identifying the version of the serialization structure,
	 *                including any sub-structures.
	 */
	public static function getSerialVersionId() {
		return self::SERIAL_VERSION_ID . '+Site:' . Site::SERIAL_VERSION_ID;
	}

	/**
	 * @see Overrides Serializable::serialize
	 * @since 1.38
	 * @return array
	 */
	public function __serialize(): array {
		// Data that should go into serialization calls.
		//
		// NOTE: When changing the structure, either implement unserialize() to handle the
		//      old structure too, or update SERIAL_VERSION_ID to kill any caches.
		return [
			'data' => $this->getArrayCopy(),
			'index' => $this->indexOffset,
			'internalIds' => $this->byInternalId,
			'globalIds' => $this->byGlobalId,
			'navigationIds' => $this->byNavigationId,
		];
	}

	/**
	 * @see Overrides Serializable::unserialize
	 * @since 1.38
	 * @param array $serializationData
	 */
	public function __unserialize( $serializationData ): void {
		foreach ( $serializationData['data'] as $offset => $value ) {
			// Just set the element, bypassing checks and offset resolving,
			// as these elements have already gone through this.
			parent::offsetSet( $offset, $value );
		}

		$this->indexOffset = $serializationData['index'];

		$this->byInternalId = $serializationData['internalIds'];
		$this->byGlobalId = $serializationData['globalIds'];
		$this->byNavigationId = $serializationData['navigationIds'];
	}
}
