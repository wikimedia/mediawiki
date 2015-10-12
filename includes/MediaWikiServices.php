<?php
use MediaWiki\ClassicInterwikiLookup;
use MediaWiki\InterwikiLookup;

/**
 * Service locator for MediaWiki core services.
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
 *
 * @since 1.27
 *
 *
 * MediaWikiServices is the service locator for the application scope of MediaWiki.
 * It acts as a top factory/registry for top level services, and defines the object
 * net that defines the services available to MediaWiki's application logic. In this
 * way, it acts as the heart of MediaWiki's dependency injection mechanism.
 */
class MediaWikiServices {

	/**
	 * Returns the global default instance of the top level service locator.
	 *
	 * @note This should only be called by static functions! The instance returned here
	 * should not be passed around! Objects that need access to a service should have
	 * that service injected into the constructor, never a service locator!
	 *
	 * @return MediaWikiServices
	 */
	public static function getInstance() {
		static $instance = null;

		if ( $instance === null ) {
			$instance = new self( new GlobalVarConfig() );
		}

		return $instance;
	}

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @var object[]
	 */
	private $services = array();

	/**
	 * @param Config $config
	 */
	public function __construct( Config $config ) {
		$this->config = $config;
	}

	/**
	 * @return Config
	 */
	public function getConfig() {
		return $this->config;
	}

	/**
	 * @return Language
	 */
	public function getContentLanguage() {
		return $this->getConfig()->get( 'ContLang' );
	}

	/**
	 * @return string language code
	 */
	public function getContentLanguageCode() {
		return $this->getConfig()->get( 'LanguageCode' );
	}

	/**
	 * @param string $name
	 * @param string $reset if 'reset', creation of a fresh instance is forced.
	 *
	 * @return object
	 */
	private function getService( $name, $reset = '' ) {
		if ( $reset === 'reset' || !isset( $this->services[$name] ) ) {
			$this->services[$name] = $this->createService( $name );
		}

		return $this->services[$name];
	}

	/**
	 * @param string $name
	 *
	 * @return object
	 */
	private function createService( $name ) {
		$method = "new$name";
		return $this->$method();
	}

	/**
	 * @return SiteLookup
	 */
	public function getSiteLookup() {
		return $this->getSiteStore();
	}

	/**
	 * @return SiteStore
	 */
	public function getSiteStore() {
		return $this->getService( 'SiteStore' );
	}

	/**
	 * @note should be called by createService() only!
	 */
	private function newSiteStore() {
		$cache = wfGetCache( wfIsHHVM() ? CACHE_ACCEL : CACHE_ANYTHING );
		$siteStore = new DBSiteStore();

		return new CachingSiteStore( $siteStore, $cache );
	}

	/**
	 * @return InterwikiLookup
	 */
	public function getInterwikiLookup() {
		return $this->getService( 'InterwikiLookup' );
	}

	/**
	 * @note should be called by createService() only!
	 */
	private function newInterwikiLookup() {
		return new ClassicInterwikiLookup(
			$this->getConfig()->get( 'ContLang' ),
			$this->getConfig()->get( 'InterwikiExpiry' ),
			$this->getConfig()->get( 'InterwikiCache' ),
			$this->getConfig()->get( 'InterwikiScopes' ),
			$this->getConfig()->get( 'InterwikiFallbackSite' )
		);
	}

	private $titleCodecFingerprint = null;

	/**
	 * Checks the global configuration variables that affect the TitleCodec have
	 * been modified.
	 *
	 * @note This is a hack around the fact that a) the configuration object may depend on mutable
	 * global state and b) the title codec is accessed via static methods in Title. Once TitleParser
	 * and TitleFormatter instances are property getting injected in all places that need them,
	 * there will no longer be need to purge the default instance when the config changes.
	 * Once that is the case, this method should be removed.
	 *
	 * @return bool true if the global configuration variables that affect the TitleCodec have
	 * been modified.
	 */
	private function checkConfigForTitleCodecChanged() {
		$localInterwikis = $this->getConfig()->get( 'LocalInterwikis' );
		$contLang = $this->getConfig()->get( 'ContLang' );

		// $wgContLang and $wgLocalInterwikis may change (especially while testing),
		// make sure we are using the right one. To detect changes over the course
		// of a request, we remember a fingerprint of the config used to create the
		// codec singleton, and re-create it if the fingerprint doesn't match.
		$fingerprint = spl_object_hash( $contLang ) . '|' . join( '+', $localInterwikis );
		$changed = ( $fingerprint !== $this->titleCodecFingerprint );

		$this->titleCodecFingerprint = $fingerprint;
		return $changed;
	}

	/**
	 * @return TitleParser
	 */
	public function getTitleParser() {
		return $this->getService( 'TitleCodec', $this->checkConfigForTitleCodecChanged() ? 'reset' : '' );
	}

	/**
	 * @return TitleFormatter
	 */
	public function getTitleFormatter() {
		// XXX: we will probably want to manage this per language, or at least content language vs user language.
		return $this->getService( 'TitleCodec', $this->checkConfigForTitleCodecChanged() ? 'reset' : '' );
	}

	/**
	 * @note should be called by createService() only!
	 */
	private function newTitleCodec() {
		$localInterwikis = $this->getConfig()->get( 'LocalInterwikis' );

		return new MediaWikiTitleCodec(
			$this->getContentLanguage(),
			$this->getGenderCache(),
			$this->getInterwikiLookup(),
			$localInterwikis
		);
	}

	/**
	 * @return GenderCache
	 */
	public function getGenderCache() {
		// @todo: manage the GenderCache singleton here instead of in the GenderCache class.
		return GenderCache::singleton();
	}

	/**
	 * @return PageLinkRenderer
	 */
	public function getPageLinkRenderer() {
		// XXX: we will probably want to manage this per language, or at least content language vs user language.
		return $this->getService( 'PageLinkRenderer' );
	}

	/**
	 * @note should be called by createService() only!
	 */
	private function newPageLinkRenderer() {
		return new MediaWikiPageLinkRenderer(
			$this->getTitleFormatter()
		);
	}

}
