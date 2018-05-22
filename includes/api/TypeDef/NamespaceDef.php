<?php

namespace MediaWiki\Api\TypeDef;

use ApiBase;
use ApiResult;
use IContextSource;
use MWNamespace;

/**
 * API type definition for namespace types
 * @since 1.32
 * @ingroup API
 */
class NamespaceDef extends EnumDef {

	private function getNamespaces( $settings ) {
		$namespaces = MWNamespace::getValidNamespaces();
		if ( isset( $settings[ApiBase::PARAM_EXTRA_NAMESPACES] ) &&
			is_array( $settings[ApiBase::PARAM_EXTRA_NAMESPACES] )
		) {
			$namespaces = array_merge( $namespaces, $settings[ApiBase::PARAM_EXTRA_NAMESPACES] );
		}
		sort( $namespaces );
		return $namespaces;
	}

	public function validate( $name, $value, array $settings, ApiBase $module ) {
		if ( !is_int( $value ) && !preg_match( '/^[+-]?\d+$/', $value ) ) {
			$encName = $module->encodeParamName( $name );
			$module->dieWithError( [ 'apierror-badnamespace', $encName, wfEscapeWikiText( $value ) ] );
		}
		$value = intval( $value );

		$settings = [ ApiBase::PARAM_TYPE => $this->getNamespaces( $settings ) ] + $settings;
		return parent::validate( $name, $value, $settings, $module );
	}

	public function getHelpInfo( IContextSource $context, $name, array $settings, ApiBase $module ) {
		$namespaces = $this->getNamespaces( $settings );
		return [
			$context->msg( 'api-help-param-list' )
				->params( !empty( $settings[ApiBase::PARAM_ISMULTI] ) ? 2 : 1 )
				->params( $context->getLanguage()->commaList( $namespaces ) )
				->parse(),
		];
	}

	public function getEnumValues( $name, array $settings, ApiBase $module ) {
		return $this->getNamespaces( $settings );
	}

	public function normalizeSettings( array $settings ) {
		// Force PARAM_ALL
		if ( !empty( $settings[ApiBase::PARAM_ISMULTI] ) ) {
			$settings[ApiBase::PARAM_ALL] = true;
		}
		return parent::normalizeSettings( $settings );
	}

	public function getParamInfo( $name, array $settings, ApiBase $module ) {
		$info = parent::getParamInfo( $name, $settings, $module );

		if ( isset( $settings[ApiBase::PARAM_EXTRA_NAMESPACES] ) &&
			is_array( $settings[ApiBase::PARAM_EXTRA_NAMESPACES] )
		) {
			$info['extranamespaces'] = $settings[ApiBase::PARAM_EXTRA_NAMESPACES];
			ApiResult::setArrayType( $info['extranamespaces'], 'array' );
			ApiResult::setIndexedTagName( $info['extranamespaces'], 'ns' );
		}

		return $info;
	}

}
