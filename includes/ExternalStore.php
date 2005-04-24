<?php
/**
 * 
 * @package MediaWiki
 *
 * Constructor class for data kept in external repositories
 *
 * External repositories might be populated by maintenance/async 
 * scripts, thus partial moving of data may be possible, as well 
 * as possibility to have any storage format (i.e. for archives)
 *
 */
 
class ExternalStore {
	/* Fetch data from given URL */
	function fetchFromURL($url) {
		global $wgExternalStores;

		if (!$wgExternalStores) 
			return false;

		@list($proto,$path)=explode('://',$url,2);
		/* Bad URL */
		if ($path=="") 
			return false;
		/* Protocol not enabled */
		if (!in_array( $proto, $wgExternalStores ))
			return false;

		$class='ExternalStore'.ucfirst($proto);
		/* Preloaded modules might exist, especially ones serving multiple protocols */
		if (!class_exists($class)) {
			if (!include_once($class.'.php'))
				return false;	
		}
		$store=new $class();
		return $store->fetchFromURL($url);
	}

	/* XXX: may require other methods, for store, delete, 
	 * whatever, for initial ext storage  
	 */
}
?>
