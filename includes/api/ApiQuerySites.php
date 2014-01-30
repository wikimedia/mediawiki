<?php

/**
 * @licence GNU GPL v2+
 * @author Adam Shorland
 * @todo filter by group
 * @todo test
 */
class ApiQuerySites extends ApiQueryBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'st' );
		$this->siteStore = SiteSQLStore::newInstance();
	}

	public function execute() {
		$sites = $this->siteStore->getSites();
		$params = $this->extractRequestParams();

		if( isset( $params['stprops'] ) ) {
			$props = $params['stprops'];
		}
		else {
			$props = $this->getPossibleProps();
		}

		/** @var $site Site */
		foreach( $sites as $site ) {
			//@todo filter props
			$siteArray = array(
				'globalId' => $site->getGlobalId(),
				'domain' => $site->getDomain(),
				'group' => $site->getGroup(),
				'language' => $site->getLanguageCode(),
			);
			$this->getResult()->addValue( 'sites', $site->getGlobalId(), $siteArray );
		}
	}

	private function getPossibleProps() {
		//@todo expand to cover more properties
		return array( 'globalId', 'domain', 'group', 'language' );
	}

	public function getAllowedParams() {
		return array(
			'props' => array(
				ApiBase::PARAM_TYPE => $this->getPossibleProps(),
				ApiBase::PARAM_REQUIRED => false,
				APiBase::PARAM_ISMULTI => true,
			),
		);
	}

	public function getParamDescription() {
		return array(
			'props' => 'The properties of the sites to return',
		);
	}

	public function getDescription() {
		return 'Get a list of sites in the sites table';
	}

}