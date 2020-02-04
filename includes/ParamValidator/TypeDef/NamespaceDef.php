<?php

namespace MediaWiki\ParamValidator\TypeDef;

use ApiResult;
use NamespaceInfo;
use Wikimedia\ParamValidator\Callbacks;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\EnumDef;

/**
 * Type definition for namespace types
 *
 * A namespace type is an enum type that accepts MediaWiki namespace IDs.
 *
 * @since 1.35
 */
class NamespaceDef extends EnumDef {

	/**
	 * (int[]) Additional namespace IDs to recognize.
	 *
	 * Generally this will be used to include NS_SPECIAL and/or NS_MEDIA.
	 */
	public const PARAM_EXTRA_NAMESPACES = 'param-extra-namespaces';

	/** @var NamespaceInfo */
	private $nsInfo;

	public function __construct( Callbacks $callbacks, NamespaceInfo $nsInfo ) {
		parent::__construct( $callbacks );
		$this->nsInfo = $nsInfo;
	}

	public function validate( $name, $value, array $settings, array $options ) {
		if ( !is_int( $value ) && preg_match( '/^[+-]?\d+$/D', $value ) ) {
			// Convert to int since that's what getEnumValues() returns.
			$value = (int)$value;
		}

		return parent::validate( $name, $value, $settings, $options );
	}

	public function getEnumValues( $name, array $settings, array $options ) {
		$namespaces = $this->nsInfo->getValidNamespaces();
		$extra = $settings[self::PARAM_EXTRA_NAMESPACES] ?? [];
		if ( is_array( $extra ) && $extra !== [] ) {
			$namespaces = array_merge( $namespaces, $extra );
		}
		sort( $namespaces );
		return $namespaces;
	}

	public function normalizeSettings( array $settings ) {
		// Force PARAM_ALL
		if ( !empty( $settings[ParamValidator::PARAM_ISMULTI] ) ) {
			$settings[ParamValidator::PARAM_ALL] = true;
		}
		return parent::normalizeSettings( $settings );
	}

	public function getParamInfo( $name, array $settings, array $options ) {
		$info = parent::getParamInfo( $name, $settings, $options );

		$info['type'] = 'namespace';
		$extra = $settings[self::PARAM_EXTRA_NAMESPACES] ?? [];
		if ( is_array( $extra ) && $extra !== [] ) {
			$info['extranamespaces'] = array_values( $extra );
			if ( isset( $options['module'] ) ) {
				// ApiResult metadata when used with the Action API.
				ApiResult::setIndexedTagName( $info['extranamespaces'], 'ns' );
			}
		}

		return $info;
	}

}
