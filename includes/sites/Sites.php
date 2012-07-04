<?php

/**
 * Represents the site configuration of a wiki.
 * Holds a list of sites (ie SiteList) and takes care
 * of retrieving and caching site information when appropriate.
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
class Sites {

	/**
	 * @since 1.20
	 * @var SiteList
	 */
	protected $sites;

	/**
	 * Cache for requests to sites that where not found and could thus not be added to the $sites field.
	 * @since 1.20
	 * @var array
	 */
	protected $nonExistingSites = array(
		'local_key' => array(),
		'global_key' => array(),
	);

	/**
	 * Keeps track if all sites where fetched for caching purposes.
	 *
	 * @since 1.20
	 * @var boolean
	 */
	protected $gotAllSites = false;

	/**
	 * Constructor.
	 *
	 * @since 1.20
	 */
	protected function __construct() {
		$this->sites = new SiteList( array() );
	}

	/**
	 * Returns an instance of Sites.
	 *
	 * @since 1.20
	 *
	 * @return Sites
	 */
	public static function singleton() {
		static $instance = false;

		if ( $instance === false ) {
			$instance = new static();
		}

		return $instance;
	}

	/**
	 * Loads the sites matching the provided conditions.
	 *
	 * @since 1.20
	 *
	 * @param array $conditions
	 */
	public function loadSites( array $conditions = array() ) {
		if ( $this->gotAllSites && $conditions === array() ) {
			return;
		}

		$sites = SitesTable::singleton()->select( null, $conditions );

		if ( $conditions === array() ) {
			$this->gotAllSites = true;
		}

		foreach ( $sites as $site ) {
			$this->sites[] = $site;
		}

		$this->trackNonExistingSites( $conditions );
	}

	/**
	 * In case the there is only a single condition that is
	 * an unique identifier for sites, the non-existing sites are
	 * put into the nonExistingSites field (so subsequent
	 * requests for these do not cause additional lookups in the db).
	 *
	 * @since 1.20
	 *
	 * @param array $conditions
	 */
	protected function trackNonExistingSites( array $conditions ) {
		if ( count( $conditions ) === 1 ) {
			reset( $conditions );
			$field = key( $conditions );

			if ( in_array( $field, array( 'global_key', 'local_key' ) ) ) {
				foreach ( (array)$conditions[$field] as $value ) {
					if ( $this->getSiteByField( $field, $value ) === false
						&& !in_array( $value, $this->nonExistingSites[$field] ) ) {
						$this->nonExistingSites[$field][] = $value;
					}
				}
			}
		}
	}

	/**
	 * Looks into the list of loaded sites for the one with the provided
	 * value for the specified field and return it, or false is there is no such site.
	 *
	 * @param string $field
	 * @param mixed $value
	 *
	 * @return Site|false
	 *
	 * @throws \MWException
	 */
	protected function getSiteByField( $field, $value ) {
		if ( $field === 'global_key' ) {
			if ( $this->sites->hasGlobalId( $value ) ) {
				return $this->sites->getSiteByGlobalId( $value );
			}
			return false;
		}
		elseif ( $field === 'local_key' ) {
			if ( $this->sites->hasLocalId( $value ) ) {
				return $this->sites->getSiteByLocalId( $value );
			}
			return false;
		}

		throw new MWException( 'Invalid field name provided' );
	}

	/**
	 * Returns the site that has the provided value for the specified field
	 * or false if there is no such site. Takes care of caching, so no
	 * non-needed database calls are made.
	 *
	 * @param string $field
	 * @param mixed $value
	 *
	 * @return Site|false
	 */
	protected function getSite( $field, $value ) {
		if ( in_array( $value, $this->nonExistingSites[$field] ) ) {
			return false;
		}

		$site = $this->getSiteByField( $field, $value );

		if ( $site !== false ) {
			return $site;
		}

		$this->loadSites( array( $field => $value ) );

		return $this->getSite( $field, $value );
	}

	/**
	 * Fetches a site based on a local identifier and returns it,
	 * or false if there is no such site.
	 *
	 * @since 1.20
	 *
	 * @param string $localSiteId
	 *
	 * @return Site|false
	 */
	public function getSiteByLocalId( $localSiteId ) {
		return $this->getSite( 'local_key', $localSiteId );
	}

	/**
	 * Fetches a site based on a global identifier and returns it,
	 * or false if there is no such site.
	 *
	 * @since 1.20
	 *
	 * @param string $globalSiteId
	 *
	 * @return Site|false
	 */
	public function getSiteByGlobalId( $globalSiteId ) {
		return $this->getSite( 'global_key', $globalSiteId );
	}

	/**
	 * Returns the sites that have been loaded into memory.
	 *
	 * @since 1.20
	 *
	 * @return SiteList
	 */
	public function getLoadedSites() {
		return $this->sites;
	}

	/**
	 * Loads all of the sites into memory.
	 *
	 * @since 1.20
	 *
	 * @return SiteList
	 */
	public function getAllSites() {
		$this->loadSites();
		return $this->getLoadedSites();
	}

	/**
	 * Returns the full url for the specified site.
	 * A page can also be provided, which is then added to the url.
	 *
	 * @since 1.20
	 *
	 * @param string $globalSiteId
	 * @param string $pageName
	 *
	 * @return false|string
	 */
	public function getUrl( $globalSiteId, $pageName = '' ) {
		$site = $this->getSiteByGlobalId( $globalSiteId );

		if ( $site === false ) {
			return false;
		}

		return $site->getPagePath( $pageName );
	}

	/**
	 * Returns the full path for the specified site.
	 * A path can also be provided, which is then added to the base path.
	 *
	 * @since 1.20
	 *
	 * @param string $globalSiteId
	 * @param string $path
	 *
	 * @return false|string
	 */
	public function getPath( $globalSiteId, $path = '' ) {
		$site = $this->getSiteByGlobalId( $globalSiteId );

		if ( $site === false ) {
			return false;
		}

		return $site->getPath( $path );
	}

	/**
	 * Returns the global identifiers.
	 * TODO: cache invalidation
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public function getGlobalIdentifiers() {
		$cache = wfGetMainCache();
		$key = wfMemcKey( __METHOD__, serialize( func_get_args() ) );

		$identifiers = $cache->get( $key );

		if ( !is_array( $identifiers ) ) {
			$identifiers = SitesTable::singleton()->selectFields( 'global_key' );
			$cache->set( $key, $identifiers, 3600 );
		}

		return $identifiers;
	}

	/**
	 * Returns the sites in a certain group.
	 *
	 * @since 1.20
	 *
	 * @param string $group
	 *
	 * @return SiteList
	 */
	public function getGroup( $group ) {
		$this->loadSites( array( 'group' => $group ) );
		return $this->sites->getGroup( $group );
	}

	/**
	 * Convenience method to create new site objects.
	 *
	 * @since 1.20
	 *
	 * @param array $fields
	 * @param bool $loadDefaults
	 *
	 * @return Site
	 */
	public static function newSite( array $fields = array(), $loadDefaults = true ) {
		return SitesTable::singleton()->newRow( $fields, $loadDefaults );
	}

}
