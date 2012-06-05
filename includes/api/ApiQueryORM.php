<?php

/**
 * Base query module for querying results from ORMTables.
 *
 * @since 1.20
 *
 * @file
 * @ingroup API
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
abstract class ApiQueryORM extends ApiQueryBase {

	/**
	 * Returns an instance of the IORMTable table being queried.
	 *
	 * @since 1.20
	 *
	 * @return IORMTable
	 */
	protected abstract function getTable();

	/**
	 * Returns the name of the individual rows.
	 * For example: page, user, contest, campaign, etc.
	 * This is used to appropriately name elements in XML.
	 * Deriving classes typically override this method.
	 *
	 * @since 1.20
	 *
	 * @return string
	 */
	protected function getRowName() {
		return 'item';
	}

	/**
	 * Returns the name of the list of rows.
	 * For example: pages, users, contests, campaigns, etc.
	 * This is used to appropriately name nodes in the output.
	 * Deriving classes typically override this method.
	 *
	 * @since 1.20
	 *
	 * @return string
	 */
	protected function getListName() {
		return 'items';
	}

	/**
	 * Returns the path to where the items results should be added in the result.
	 *
	 * @since 1.20
	 *
	 * @return null|string|array
	 */
	protected function getResultPath() {
		return null;
	}

	/**
	 * Get the parameters, find out what the conditions for the query are,
	 * run it, and add the results.
	 *
	 * @since 1.20
	 */
	public function execute() {
		$params = $this->getParams();

		if ( !in_array( 'id', $params['props'] ) ) {
			$params['props'][] = 'id';
		}

		$results = $this->getResults( $params, $this->getConditions( $params ) );
		$this->addResults( $params, $results );
	}

	/**
	 * Get the request parameters, handle the * value for the props param
	 * and remove all params set to null (ie those that are not actually provided).
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	protected function getParams() {
		// Get the requests parameters.
		$params = $this->extractRequestParams();

		$starPropPosition = array_search( '*', $params['props'] );

		if ( $starPropPosition !== false ) {
			unset( $params['props'][$starPropPosition] );
			$params['props'] = array_merge( $params['props'], $this->getTable()->getFieldNames() );
		}

		return array_filter( $params, create_function( '$p', 'return isset( $p );' ) );
	}

	/**
	 * Get the conditions for the query. These will be provided as
	 * regular parameters, together with limit, props, continue,
	 * and possibly others which we need to get rid off.
	 *
	 * @since 1.20
	 *
	 * @param array $params
	 *
	 * @return array
	 */
	protected function getConditions( array $params ) {
		$conditions = array();
		$fields = $this->getTable()->getFields();

		foreach ( $params as $name => $value ) {
			if ( array_key_exists( $name, $fields ) ) {
				$conditions[$name] = $value;
			}
		}

		return $conditions;
	}

	/**
	 * Get the actual results.
	 *
	 * @since 1.20
	 *
	 * @param array $params
	 * @param array $conditions
	 *
	 * @return ORMResult
	 */
	protected function getResults( array $params, array $conditions ) {
		return $this->getTable()->select(
			$params['props'],
			$conditions,
			array(
				'LIMIT' => $params['limit'] + 1,
				'ORDER BY' => $this->getTable()->getPrefixedField( 'id' ) . ' ASC'
			),
			__METHOD__
		);
	}

	/**
	 * Serialize the results and add them to the result object.
	 *
	 * @since 1.20
	 *
	 * @param array $params
	 * @param ORMResult $results
	 */
	protected function addResults( array $params, ORMResult $results ) {
		$serializedResults = array();
		$count = 0;

		foreach ( $results as /* IORMRow */ $result ) {
			if ( ++$count > $params['limit'] ) {
				// We've reached the one extra which shows that
				// there are additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'continue', $result->getId() );
				break;
			}

			$serializedResults[] = $this->formatRow( $result, $params );
		}

		$this->setIndexedTagNames( $serializedResults );
		$this->addSerializedResults( $serializedResults );
	}

	/**
	 * Formats a row to it's desired output format.
	 *
	 * @since 1.20
	 *
	 * @param IORMRow $result
	 * @param array $params
	 *
	 * @return mixed
	 */
	protected function formatRow( IORMRow $result, array $params ) {
		return $result->toArray( $params['props'] );
	}

	/**
	 * Set the tag names for formats such as XML.
	 *
	 * @since 1.20
	 *
	 * @param array $serializedResults
	 */
	protected function setIndexedTagNames( array &$serializedResults ) {
		$this->getResult()->setIndexedTagName( $serializedResults, $this->getRowName() );
	}

	/**
	 * Add the serialized results to the result object.
	 *
	 * @since 1.20
	 *
	 * @param array $serializedResults
	 */
	protected function addSerializedResults( array $serializedResults ) {
		$this->getResult()->addValue(
			$this->getResultPath(),
			$this->getListName(),
			$serializedResults
		);
	}

	/**
	 * @see ApiBase::getAllowedParams()
	 * @return array
	 */
	public function getAllowedParams() {
		$params = array (
			'props' => array(
				ApiBase::PARAM_TYPE => array_merge( $this->getTable()->getFieldNames(), array( '*' ) ),
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => '*'
			),
			'limit' => array(
				ApiBase::PARAM_DFLT => 20,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			),
			'continue' => null,
		);

		return array_merge( $this->getTable()->getAPIParams(), $params );
	}

	/**
	 * @see ApiBase::getParamDescription()
	 * @return array
	 */
	public function getParamDescription() {
		$descriptions = array (
			'props' => 'Fields to query',
			'continue' => 'Offset number from where to continue the query',
			'limit' => 'Max amount of rows to return',
		);

		return array_merge( $this->getTable()->getFieldDescriptions(), $descriptions );
	}

}
