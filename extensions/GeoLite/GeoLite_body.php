<?php 
/**
 * Special Page for GeoLite
 *
 * @file
 * @ingroup Extensions
 */

// Special page GeoLite

class SpecialGeoLite extends SpecialPage {

        /* Functions */

        public function __construct() {
                // Initialize special page
                parent::__construct( 'GeoLite' );
        }

	public function execute( $sub ) {
		global $wgOut, $wgRequest, $wgLandingPageBase, $wgKnownLandingPages;

		$lang = ( $wgRequest->getVal( 'lang' ) ) ? $wgRequest->getVal( 'lang' ) : 'en' ;
		$utm_source = $wgRequest->getVal( 'utm_source' );
		$utm_medium = $wgRequest->getVal( 'utm_medium' );
		$utm_campaign = $wgRequest->getVal( 'utm_camapaign' );

		$tracking = '?' . "utm_source=$utm_source" . "&utm_medium=$utm_medium" . "&utm_campaign=$utm_campaign";
		
		if ( $wgRequest->getVal( 'ip') ) {
		   $ip = $wgRequest->getVal( 'ip' ); 
		} else { 
		   $ip = $_SERVER[ 'REMOTE_ADDR' ];
		}
		
		if ( IP::isValid( $ip ) ) {
		   $country = geoip_country_code_by_name( $ip );
		
                   if ( is_string ( $country ) && array_key_exists( $country, $wgKnownLandingPages ) ) {
		          $wgOut->redirect( $wgLandingPageBase . "/" . $wgKnownLandingPages[ $country ] . $tracking );
	  	   }
		}
		$wgOut->redirect( $wgLandingPageBase . "/" . $lang . $tracking );
	}

}
