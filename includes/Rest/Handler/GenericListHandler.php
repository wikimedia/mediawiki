<?php
namespace MediaWiki\Rest\Handler;

use MediaWiki\Api\ApiBase;
use MediaWiki\Api\ApiRestHelper;
use MediaWiki\Api\ApiRestHelperContext;
use MediaWiki\Api\ApiRestHybrid;
use UnexpectedValueException;

/**
 * A REST handler acting as an adapter for an action API list module.
 *
 * The REST parameters are the list module's parameters without its prefix,
 * e.g. "title" rather than "bltitle" for list=backlinks.
 *
 * NOTE: This class should only be used for "list" style query actions,
 * not meta queries.
 */
class GenericListHandler extends GenericActionHandler {

	private ?ApiBase $listModule = null;

	/**
	 * The list module's REST helper.
	 * Not to be confused with the action module's REST helper tracked
	 * by the parent class.
	 */
	private ?ApiRestHelper $apiRestHelper = null;

	public function __construct(
		protected readonly string $moduleName,
		array $adapterConfig = []
	) {
		parent::__construct( 'query', $adapterConfig );
	}

	/**
	 * @inheritDoc
	 */
	protected function getActionModuleParameters() {
		$params = parent::getActionModuleParameters();

		$mapped = $this->prependListModulePrefix( $params );
		$mapped['list'] = $this->moduleName;
		return $mapped;
	}

	private function prependListModulePrefix( array $values ): array {
		// Only the list module's own parameters have its prefix.
		$prefix = $this->getApiListModule()->getModulePrefix();
		$listParams = $this->getListParamSpecs();

		$mapped = [];
		foreach ( $values as $name => $value ) {
			$prefixed = $prefix . $name;
			$key = isset( $listParams[$name] ) ? $prefixed : $name;
			$mapped[ $key ] = $value;
		}

		return $mapped;
	}

	private function trimListModulePrefix( array $values ): array {
		// Only the list module's own parameters have its prefix.
		$prefix = $this->getApiListModule()->getModulePrefix();
		$listParams = $this->getListParamSpecs();

		$mapped = [];
		foreach ( $values as $name => $value ) {
			if ( !str_starts_with( $name, $prefix ) ) {
				$mapped[ $name ] = $value;
				continue;
			}

			$trimmed = substr( $name, strlen( $prefix ) );
			$key = isset( $listParams[$trimmed] ) ? $trimmed : $name;
			$mapped[ $key ] = $value;
		}

		return $mapped;
	}

	/**
	 * The list module's parameters, without its prefix, and the parameters
	 * of ApiMain, without those that don't apply to the REST API.
	 *
	 * @return array[]
	 */
	protected function getActionModuleParamSpecs(): array {
		return $this->filterSupportedParams(
			$this->getListParamSpecs() + $this->getModuleParamSpecs( $this->getApiMain() )
		);
	}

	/**
	 * The list module's parameter specs. The names have no module prefix,
	 * see ApiBase::encodeParamName(). Each spec has a help message derived
	 * from the list module, not from "query", see getModuleParamSpecs().
	 *
	 * @return array[]
	 */
	private function getListParamSpecs(): array {
		return $this->getModuleParamSpecs( $this->getApiListModule() );
	}

	/**
	 * Use the list module's helper, rather than that of ApiQuery, which is
	 * the action module this handler calls.
	 *
	 * @return ?ApiRestHelper
	 */
	protected function getApiRestHelper(): ?ApiRestHelper {
		$this->getApiListModule();
		return $this->apiRestHelper;
	}

	/**
	 * @inheritDoc
	 */
	protected function mapActionModuleResult( array $data ) {
		$payload = $data['query'][ $this->moduleName ] ?? [];
		$continue = $data[ 'continue' ] ?? null;

		if ( $continue !== null ) {
			// Unset the query module's own continuation parameter,
			// only use the one for the list module itself.
			unset( $continue['continue'] );
			$continue = $this->trimListModulePrefix( $continue );
		}

		return [
			'items' => $payload,
			'pagination' => [
				// TODO: Decide on the envelope structure (T440445).
				'next' => $continue ? [
					'params' => $continue
				] : null, // null means "no more pages"
			],
		];
	}

	/**
	 * Returns the query module that will be called by this handler, based on
	 * the 'list' parameter returned by getActionModuleParameters().
	 *
	 * If that module implements ApiRestHybrid, this method calls initRestMode()
	 * on it.
	 *
	 * @return ApiBase
	 */
	private function getApiListModule(): ApiBase {
		if ( !$this->listModule ) {
			// NOTE: use the module manager from ApiQuery (which is returned by getActionModule())
			$this->listModule = $this->getActionModule()->getModuleManager()
				?->getModule( $this->moduleName, 'list' );

			if ( !$this->listModule ) {
				throw new UnexpectedValueException( "Unknown list module: {$this->moduleName}" );
			}

			if ( $this->listModule instanceof ApiRestHybrid ) {
				// NOTE: This is the list module's ApiRestHelper.
				// The parent class captures the main action's ApiRestHelper,
				// which in our case would be the one for ApiQuery (which is probably null).
				$this->apiRestHelper = $this->listModule->initRestMode(
					new ApiRestHelperContext( $this->adapterConfig, $this->getConfig() )
				);
			}
		}

		return $this->listModule;
	}

}
