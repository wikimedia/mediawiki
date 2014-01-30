<?php

/**
 * @licence GNU GPL v2+
 * @author Adam Shorland
 */
class ApiQuerySites extends ApiQueryBase {

	/**
	 * @var array stprops accepted by this module
	 */
	private $possibleProps;

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'st' );
		$this->siteStore = SiteSQLStore::newInstance();
		$this->possibleProps = array( 'globalId', 'domain', 'group', 'language' );
	}

	public function execute() {
		$sites = $this->siteStore->getSites();
		$requestedProps = $this->getRequestedProps();

		$this->getResult()->addValue( null, 'sites', array() );
		/** @var $site Site */
		foreach( $sites as $site ) {
			$this->getResult()->addValue(
				'sites',
				$site->getGlobalId(),
				$this->getSiteProps( $site, $requestedProps )
			);
		}
	}

	/**
	 * @return array
	 */
	private function getRequestedProps() {
		$params = $this->extractRequestParams();
		if( isset( $params['stprops'] ) ) {
			foreach( $params['stprops'] as $prop ) {
				if( !in_array( $prop, $this->possibleProps ) ) {
					//todo die bad prop requested
				}
			}
			return $params['stprops'];
		}
		return $this->possibleProps;
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
			$siteProps[$prop] = $this->getSiteProp( $site, $prop );
		}
		return $siteProps;
	}

	/**
	 * @param Site $site
	 * @param string $prop
	 *
	 * @return mixed
	 */
	private function getSiteProp( $site, $prop ) {
		switch ( $prop ) {
			case 'globalId':
				return $site->getGlobalId();
			case 'domain':
				return $site->getDomain();
			case 'group':
				return $site->getGroup();
			case 'language':
				return $site->getLanguageCode();
		}
	}

	public function getAllowedParams() {
		return array(
			'props' => array(
				ApiBase::PARAM_TYPE => $this->possibleProps,
				ApiBase::PARAM_REQUIRED => false,
				APiBase::PARAM_ISMULTI => true,
			),
		);
	}

	public function getParamDescription() {
		return array(
			'props' => 'The properties of the sites to return, default returns all',
		);
	}

	public function getDescription() {
		return 'Get a list of sites in the sites table';
	}

}