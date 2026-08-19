<?php
namespace MediaWiki\Rest\Handler;

use MediaWiki\Api\ApiBase;
use MediaWiki\Api\ApiRestHelper;
use MediaWiki\Api\ApiRestHybrid;
use UnexpectedValueException;
use Wikimedia\ParamValidator\ParamValidator;

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
			$this->getListParamSpecs() + $this->getMainParamSpecs()
		);
	}

	/**
	 * The list module's parameter specs. The names have no module prefix,
	 * see ApiBase::encodeParamName(). Each spec has a help message, since
	 * GenericActionHandler::makeParamSettings() would otherwise derive one
	 * from the action name.
	 *
	 * @return array[]
	 */
	private function getListParamSpecs(): array {
		$module = $this->getApiListModule();
		$path = $module->getModulePath();

		$specs = [];
		foreach ( $module->getFinalParams() as $name => $spec ) {
			// Specs may be in scalar shorthand form, just a default value.
			if ( !is_array( $spec ) ) {
				$spec = [ ParamValidator::PARAM_DEFAULT => $spec ];
			}
			$spec[ApiBase::PARAM_HELP_MSG] ??= "apihelp-$path-param-$name";
			$specs[$name] = $spec;
		}

		return $specs;
	}

	/**
	 * Use the list module's helper, rather than that of ApiQuery, which is
	 * the action module this handler calls.
	 *
	 * @return ?ApiRestHelper
	 */
	protected function getApiRestHelper(): ?ApiRestHelper {
		$module = $this->getApiListModule();
		return $module instanceof ApiRestHybrid ? $module->getRestHelper() : null;
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

	private function getApiListModule(): ApiBase {
		if ( !$this->listModule ) {
			// NOTE: use the module manager from ApiQuery (which is returned by getApiActionModule())
			$this->listModule = $this->getApiActionModule()->getModuleManager()
				?->getModule( $this->moduleName, 'list' );

			if ( !$this->listModule ) {
				throw new UnexpectedValueException( "Unknown list module: {$this->moduleName}" );
			}
		}
		return $this->listModule;
	}

}
