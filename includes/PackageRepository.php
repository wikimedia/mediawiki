<?php

/**
 * File holding the PackageRepository class.
 *
 * @file PackageRepository.php
 * @ingroup Deployment
 *
 * @author Jeroen De Dauw
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

/**
 * Base repository class. Deriving classes handle interaction with
 * package repositories of the type they support.
 * 
 * @since 0.1
 * 
 * @ingroup Deployment
 * 
 * @author Jeroen De Dauw
 */
abstract class PackageRepository {
	
	/**
	 * Base location of the repository.
	 * 
	 * @since 0.1
	 * 
	 * @var string
	 */
	protected $location;	
	
	/**
	 * Returns a list of extensions matching the search criteria.
	 * 
	 * @since 0.1
	 * 
	 * @param $filterType String
	 * @param $filterValue String
	 * 
	 * @return array
	 */
	public abstract function findExtenions( $filterType, $filterValue );
	
	/**
	 * Checks if newer versions of an extension are available.
	 * 
	 * @since 0.1
	 * 
	 * @param $extensionName String
	 * @param $currentVersion String
	 * 
	 * @return Mixed: false when there is no update, object with info when there is.
	 */	
	public abstract function extensionHasUpdate( $extensionName, $currentVersion );
	
	/**
	 * Checks if newer versions of MediaWiki is available.
	 * 
	 * @since 0.1
	 * 
	 * @param $currentVersion String
	 * 
	 * @return Mixed: false when there is no update, object with info when there is.
	 */		
	public abstract function coreHasUpdate( $currentVersion );
	
	/**
	 * Checks if there are any updates for this MediaWiki installation and extensions.
	 * 
	 * @since 0.1
	 * 
	 * @param $coreVersion String
	 * @param $extensions Array
	 * 
	 * @return Mixed: false when there is are updates, array with obecjts with info when there are.
	 */		
	public abstract function installationHasUpdates( $coreVersion, array $extensions );
	
	/**
	 * Constructor.
	 * 
	 * @param $location String
	 * 
	 * @since 0.1
	 */
	public function __construct( $location ) {
		$this->location = $location;
	}		
	
}