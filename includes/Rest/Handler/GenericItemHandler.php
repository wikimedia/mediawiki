<?php
namespace MediaWiki\Rest\Handler;

use MediaWiki\Api\ApiListRestHelper;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Rest\LocalizedHttpException;
use Wikimedia\Message\ListParam;
use Wikimedia\Message\ListType;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * A REST handler acting as an adapter for getting a single item from an
 * action API list module, e.g. /users/{name} on top of list=users.
 *
 * List modules take a list of names and return a list of items. This handler
 * passes the path parameters to the module as lists of one value, and returns
 * the single item of the result instead of the list.
 *
 * NOTE: This class should only be used for "list" style query actions,
 * not meta queries.
 */
class GenericItemHandler extends GenericListHandler {

	/**
	 * @inheritDoc
	 */
	protected function getActionModuleParameters() {
		// The action API splits values on "|", or on "\x1f" if the value starts
		// with it. A path parameter identifies a single item, so it must not
		// contain either, even if the module accepts a list.
		foreach ( $this->getRequest()->getPathParams() as $name => $value ) {
			if ( str_contains( $value, '|' ) || str_contains( $value, "\x1f" ) ) {
				throw new LocalizedHttpException(
					new MessageValue( 'rest-item-single-value', [ $name ] ),
					400,
					[ // match fields used by Validator::validateParams()
						'error' => 'parameter-validation-failed',
						'name' => $name,
						'value' => $value,
						'failureCode' => 'notmulti',
					]
				);
			}
		}

		return parent::getActionModuleParameters();
	}

	/**
	 * Path parameters are declared single-valued, since getActionModuleParameters()
	 * rejects multiple values.
	 *
	 * @inheritDoc
	 */
	protected function makeParamSettings( array $names, string $source ): array {
		$settings = parent::makeParamSettings( $names, $source );

		if ( $source === 'path' ) {
			foreach ( array_keys( $settings ) as $name ) {
				unset( $settings[$name][ParamValidator::PARAM_ISMULTI] );
			}
		}

		return $settings;
	}

	/**
	 * @inheritDoc
	 */
	protected function mapActionModuleResult( array $data ) {
		// TODO: warning headers and such (T436750).
		$items = $data['query'][ $this->moduleName ] ?? [];

		if ( count( $items ) > 1 ) {
			// The module doesn't return one item per name, or the route exposes
			// another parameter that identifies items. Either way, the route is
			// misconfigured. The REST framework doesn't report HttpExceptions,
			// so log it here.
			LoggerFactory::getInstance( 'rest' )->error(
				'List module {module} returned {count} items for an item endpoint',
				[ 'module' => $this->moduleName, 'count' => count( $items ), 'path' => $this->getPath() ]
			);

			throw new LocalizedHttpException(
				new MessageValue( 'rest-item-multiple-results' ),
				500
			);
		}

		// Continuation is ignored, it doesn't apply to a single item.
		$item = reset( $items );
		$status = $item === false ? 404 : $this->getItemStatus( $item );

		if ( $status >= 400 ) {
			$key = match ( $status ) {
				404 => 'rest-item-not-found',
				400 => 'rest-item-invalid',
				default => 'rest-item-unavailable',
			};
			$ids = array_values( $this->getRequest()->getPathParams() );

			throw new LocalizedHttpException(
				new MessageValue( $key, [ new ListParam( ListType::COMMA, $ids ) ] ),
				$status
			);
		}

		return $item;
	}

	/**
	 * The status for the item: the one the helper returns, if any, otherwise
	 * 400 for an invalid item, 404 for a missing item that isn't known (as
	 * ApiQuery reports pages), and 200.
	 */
	private function getItemStatus( array $item ): int {
		$status = $this->getApiListRestHelper()?->getItemStatus( $item ) ?? 0;

		if ( $status ) {
			return $status;
		}

		if ( $item['invalid'] ?? false ) {
			return 400;
		}

		if ( ( $item['missing'] ?? false ) && !( $item['known'] ?? false ) ) {
			return 404;
		}

		return 200;
	}

	/**
	 * The schema of a single item, rather than the list envelope that
	 * ApiListRestHelper::getResponseBodySchema() returns.
	 *
	 * @inheritDoc
	 */
	protected function getResponseBodySchema( string $method ): ?array {
		return $this->getApiListRestHelper()?->getResponseItemSchema();
	}

	/**
	 * The list module's helper, if it is an ApiListRestHelper.
	 */
	private function getApiListRestHelper(): ?ApiListRestHelper {
		$helper = $this->getApiRestHelper();
		return $helper instanceof ApiListRestHelper ? $helper : null;
	}

}
