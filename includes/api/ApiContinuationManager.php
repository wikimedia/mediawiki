<?php
/**
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
 */

/**
 * This manages continuation state.
 * @since 1.25 this is no longer a subclass of ApiBase
 * @ingroup API
 */
class ApiContinuationManager {
	private $source;

	private $allModules = [];
	private $generatedModules = [];

	private $continuationData = [];
	private $generatorContinuationData = [];
	private $generatorNonContinuationData = [];

	private $generatorParams = [];
	private $generatorDone = false;

	/**
	 * @param ApiBase $module Module starting the continuation
	 * @param ApiBase[] $allModules Contains ApiBase instances that will be executed
	 * @param array $generatedModules Names of modules that depend on the generator
	 * @throws UsageException
	 */
	public function __construct(
		ApiBase $module, array $allModules = [], array $generatedModules = []
	) {
		$this->source = get_class( $module );
		$request = $module->getRequest();

		$this->generatedModules = $generatedModules
			? array_combine( $generatedModules, $generatedModules )
			: [];

		$skip = [];
		$continue = $request->getVal( 'continue', '' );
		if ( $continue !== '' ) {
			$continue = explode( '||', $continue );
			if ( count( $continue ) !== 2 ) {
				throw new UsageException(
					'Invalid continue param. You should pass the original value returned by the previous query',
					'badcontinue'
				);
			}
			$this->generatorDone = ( $continue[0] === '-' );
			$skip = explode( '|', $continue[1] );
			if ( !$this->generatorDone ) {
				$params = explode( '|', $continue[0] );
				if ( $params ) {
					$this->generatorParams = array_intersect_key(
						$request->getValues(),
						array_flip( $params )
					);
				}
			} else {
				// When the generator is complete, don't run any modules that
				// depend on it.
				$skip += $this->generatedModules;
			}
		}

		foreach ( $allModules as $module ) {
			$name = $module->getModuleName();
			if ( in_array( $name, $skip, true ) ) {
				$this->allModules[$name] = false;
				// Prevent spurious "unused parameter" warnings
				$module->extractRequestParams();
			} else {
				$this->allModules[$name] = $module;
			}
		}
	}

	/**
	 * Get the class that created this manager
	 * @return string
	 */
	public function getSource() {
		return $this->source;
	}

	/**
	 * Is the generator done?
	 * @return bool
	 */
	public function isGeneratorDone() {
		return $this->generatorDone;
	}

	/**
	 * Get the list of modules that should actually be run
	 * @return ApiBase[]
	 */
	public function getRunModules() {
		return array_values( array_filter( $this->allModules ) );
	}

	/**
	 * Set the continuation parameter for a module
	 * @param ApiBase $module
	 * @param string $paramName
	 * @param string|array $paramValue
	 * @throws UnexpectedValueException
	 */
	public function addContinueParam( ApiBase $module, $paramName, $paramValue ) {
		$name = $module->getModuleName();
		if ( !isset( $this->allModules[$name] ) ) {
			throw new UnexpectedValueException(
				"Module '$name' called " . __METHOD__ .
					' but was not passed to ' . __CLASS__ . '::__construct'
			);
		}
		if ( !$this->allModules[$name] ) {
			throw new UnexpectedValueException(
				"Module '$name' was not supposed to have been executed, but " .
					'it was executed anyway'
			);
		}
		$paramName = $module->encodeParamName( $paramName );
		if ( is_array( $paramValue ) ) {
			$paramValue = implode( '|', $paramValue );
		}
		$this->continuationData[$name][$paramName] = $paramValue;
	}

	/**
	 * Set the non-continuation parameter for the generator module
	 *
	 * In case the generator isn't going to be continued, this sets the fields
	 * to return.
	 *
	 * @since 1.28
	 * @param ApiBase $module
	 * @param string $paramName
	 * @param string|array $paramValue
	 */
	public function addGeneratorNonContinueParam( ApiBase $module, $paramName, $paramValue ) {
		$name = $module->getModuleName();
		$paramName = $module->encodeParamName( $paramName );
		if ( is_array( $paramValue ) ) {
			$paramValue = implode( '|', $paramValue );
		}
		$this->generatorNonContinuationData[$name][$paramName] = $paramValue;
	}

	/**
	 * Set the continuation parameter for the generator module
	 * @param ApiBase $module
	 * @param string $paramName
	 * @param string|array $paramValue
	 */
	public function addGeneratorContinueParam( ApiBase $module, $paramName, $paramValue ) {
		$name = $module->getModuleName();
		$paramName = $module->encodeParamName( $paramName );
		if ( is_array( $paramValue ) ) {
			$paramValue = implode( '|', $paramValue );
		}
		$this->generatorContinuationData[$name][$paramName] = $paramValue;
	}

	/**
	 * Fetch raw continuation data
	 * @return array
	 */
	public function getRawContinuation() {
		return array_merge_recursive( $this->continuationData, $this->generatorContinuationData );
	}

	/**
	 * Fetch raw non-continuation data
	 * @since 1.28
	 * @return array
	 */
	public function getRawNonContinuation() {
		return $this->generatorNonContinuationData;
	}

	/**
	 * Fetch continuation result data
	 * @return array [ (array)$data, (bool)$batchcomplete ]
	 */
	public function getContinuation() {
		$data = [];
		$batchcomplete = false;

		$finishedModules = array_diff(
			array_keys( $this->allModules ),
			array_keys( $this->continuationData )
		);

		// First, grab the non-generator-using continuation data
		$continuationData = array_diff_key( $this->continuationData, $this->generatedModules );
		foreach ( $continuationData as $module => $kvp ) {
			$data += $kvp;
		}

		// Next, handle the generator-using continuation data
		$continuationData = array_intersect_key( $this->continuationData, $this->generatedModules );
		if ( $continuationData ) {
			// Some modules are unfinished: include those params, and copy
			// the generator params.
			foreach ( $continuationData as $module => $kvp ) {
				$data += $kvp;
			}
			$generatorParams = [];
			foreach ( $this->generatorNonContinuationData as $kvp ) {
				$generatorParams += $kvp;
			}
			$generatorParams += $this->generatorParams;
			$data += $generatorParams;
			$generatorKeys = implode( '|', array_keys( $generatorParams ) );
		} elseif ( $this->generatorContinuationData ) {
			// All the generator-using modules are complete, but the
			// generator isn't. Continue the generator and restart the
			// generator-using modules
			$generatorParams = [];
			foreach ( $this->generatorContinuationData as $kvp ) {
				$generatorParams += $kvp;
			}
			$data += $generatorParams;
			$finishedModules = array_diff( $finishedModules, $this->generatedModules );
			$generatorKeys = implode( '|', array_keys( $generatorParams ) );
			$batchcomplete = true;
		} else {
			// Generator and prop modules are all done. Mark it so.
			$generatorKeys = '-';
			$batchcomplete = true;
		}

		// Set 'continue' if any continuation data is set or if the generator
		// still needs to run
		if ( $data || $generatorKeys !== '-' ) {
			$data['continue'] = $generatorKeys . '||' . implode( '|', $finishedModules );
		}

		return [ $data, $batchcomplete ];
	}

	/**
	 * Store the continuation data into the result
	 * @param ApiResult $result
	 */
	public function setContinuationIntoResult( ApiResult $result ) {
		list( $data, $batchcomplete ) = $this->getContinuation();
		if ( $data ) {
			$result->addValue( null, 'continue', $data,
				ApiResult::ADD_ON_TOP | ApiResult::NO_SIZE_CHECK );
		}
		if ( $batchcomplete ) {
			$result->addValue( null, 'batchcomplete', true,
				ApiResult::ADD_ON_TOP | ApiResult::NO_SIZE_CHECK );
		}
	}
}
