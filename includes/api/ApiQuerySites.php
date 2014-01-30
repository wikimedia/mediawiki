<?php

/**
 * @licence GNU GPL v2+
 * @author Adam Shorland
 */
class ApiQuerySites extends ApiQueryBase {

	/**
	 * @var array stprops accepted by this module
	 */
	private $possibleProps = array( 'globalId', 'domain', 'group', 'language' );

	/**
	 * @see ApiBase::__construct()
	 */
	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'st' );
		$this->siteStore = SiteSQLStore::newInstance();
	}

	/**
	 * @see ApiBase::execute()
	 */
	public function execute() {
		$params = $this->extractRequestParams();
		$sites = $this->siteStore->getSites();

		$this->getResult()->addValue( null, 'sites', array() );
		/** @var $site Site */
		foreach( $sites as $site ) {
			$this->getResult()->addValue(
				'sites',
				$site->getGlobalId(),
				$this->getSiteProps( $site, $params['props'] )
			);
		}
	}

	/**
	 * @param Site $site
	 * @param array $props
	 *
	 * @return array
	 */
	private function getSiteProps( $site, $props ) {
		$siteProps = array();
		foreach( $props as $prop ) {
			switch ( $prop ) {
				case 'globalId':
					$siteProps[$prop] = $site->getGlobalId();
					break;
				case 'domain':
					$siteProps[$prop] = $site->getDomain();
					break;
				case 'group':
					$siteProps[$prop] = $site->getGroup();
					break;
				case 'language':
					$siteProps[$prop] = $site->getLanguageCode();
					break;
			}
		}
		return $siteProps;
	}

	/**
	 * @see ApiBase::getAllowedParams()
	 */
	public function getAllowedParams() {
		return array(
			'props' => array(
				ApiBase::PARAM_TYPE => $this->possibleProps,
				ApiBase::PARAM_REQUIRED => false,
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => implode( '|', $this->possibleProps ),
			),
		);
	}

	/**
	 * @see ApiBase::getParamDescription()
	 */
	public function getParamDescription() {
		return array(
			'props' => 'The properties of the sites to return, default returns all',
		);
	}

	/**
	 * @see ApiBase::getDescription()
	 */
	public function getDescription() {
		return 'Get a list of sites in the sites table';
	}

	/**
	 * @see ApiBase::getExamples()
	 */
	protected function getExamples() {
		return array(
			"api.php?action=query&meta=sites"
			=> "Request all possible information about sites in the sites table",
			"api.php?action=query&meta=sites&stprops=globalId"
			=> "Request the globalId of all sites in the sites table",
		);
	}

	/**
	 * @see ApiBase::getHelpUrls()
	 */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Extension:Wikibase/API#' . $this->getModuleName();
	}

}