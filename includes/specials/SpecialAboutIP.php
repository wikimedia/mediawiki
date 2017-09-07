<?php
/**
 * Implements Special:Longpages
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
 * @file
 * @ingroup SpecialPage
 */

/**
 *
 * @ingroup SpecialPage
 */
use MediaWiki\GeoIPLookup;

class SpecialAboutIP extends SpecialPage {
	function __construct() {
		parent::__construct( 'AboutIP' );
	}

	function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$out = $this->getOutput();

		if ( $par === null ) {
			$par = $this->getRequest()->getText( 'ip' );
		}

		if ( !GeoIPLookup::isEnabled() ) {
			$out->addWikiMsg( 'geoip-not-enabled',
				$this->msg( 'help-geoip-url' ) );

			return false;
		}

		$this->getPageHeader( $par );

		if ( !IP::isIPAddress( $par ) ) {
			$out->addWikiMsg( 'ip-not-valid', $par );
			return false;
		}

		$this->formatResult( $par );
	}

	function getPageHeader( $ip ) {
		$formDescriptor = [
			'mime' => [
				'type' => 'text',
				'name' => 'ip',
				'label-message' => 'ipaddress',
				'required' => true,
				'default' => $ip
			],
		];

		HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() )
			->setSubmitTextMsg( 'ilsubmit' )
			->setAction( $this->getPageTitle()->getLocalURL() )
			->setMethod( 'get' )
			->prepareForm()
			->displayForm( false );
	}

	function formatResult( $ip ) {
		global $wgGeoIPDataDirectory;

		$lookup = new GeoIPLookup( $wgGeoIPDataDirectory );
		if ( $lookup->getASNInfo( $ip ) !== false ) {
			$asn = $lookup->getASNInfo( $ip )->autonomousSystemOrganization;
		} else {
			$asn = '';
		}
		$cityObj = $lookup->getCityInfo( $ip );
		$continent = $cityObj->continent->names['en'];
		$country = $cityObj->country->names['en'];
		$city = $cityObj->city->names['en'];

		$out = Xml::openElement( 'div', [ 'class' => 'mw-spcontent' ] ) .
			Xml::openElement( 'table', [ 'class' => 'geoip-data' ] ) .
			Xml::openElement( 'tbody' ) .
			Xml::openElement( 'tr' ) .
			Xml::element( 'td', [], $this->msg( 'geoip-asn' )->text() ) .
			Xml::element( 'td', [], $asn ) .
			Xml::closeElement( 'tr' ) .
			Xml::openElement( 'tr' ) .
			Xml::element( 'td', [], $this->msg( 'geoip-continent' )->text() ) .
			Xml::element( 'td', [], $continent ) .
			Xml::closeElement( 'tr' ) .
			Xml::openElement( 'tr' ) .
			Xml::element( 'td', [], $this->msg( 'geoip-country' )->text() ) .
			Xml::element( 'td', [], $country ) .
			Xml::closeElement( 'tr' ) .
			Xml::openElement( 'tr' ) .
			Xml::element( 'td', [], $this->msg( 'geoip-city' ) ) .
			Xml::element( 'td', [], $city ) .
			Xml::closeElement( 'tr' ) .
			Xml::closeElement( 'tbody' ) .
			Xml::closeElement( 'table' ) .
			Xml::closeElement( 'div' );

		$this->getOutput()->addHTML( $out );
	}
}
