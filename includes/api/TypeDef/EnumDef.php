<?php

namespace MediaWiki\Api\TypeDef;

use ApiBase;
use Html;
use IContextSource;
use MediaWiki\Api\ParamValidator;
use MediaWiki\Api\TypeDef;
use Message;

/**
 * API type definition for enum types
 * @since 1.32
 * @ingroup API
 */
class EnumDef extends TypeDef {

	public function validate( $name, $value, array $settings, ApiBase $module ) {
		if ( in_array( $value, $settings[ApiBase::PARAM_TYPE], true ) ) {
			// Set a warning if a deprecated parameter value has been passed
			if ( isset( $settings[ApiBase::PARAM_DEPRECATED_VALUES][$value] ) ) {
				$encName = $module->encodeParamName( $name );
				$feature = "$encName=$value";
				$m = $module;
				while ( !$m->isMain() ) {
					$p = $m->getParent();
					$moduleName = $m->getModuleName();
					$param = $p->encodeParamName( $p->getModuleManager()->getModuleGroup( $moduleName ) );
					$feature = "{$param}={$moduleName}&{$feature}";
					$m = $p;
				}
				$msg = $settings[ApiBase::PARAM_DEPRECATED_VALUES][$value];
				if ( $msg === true ) {
					$msg = [ 'apiwarn-deprecation-parameter', "$encName=$value" ];
				}
				$module->addDeprecation( $msg, $feature );
			}

			return $value;
		}

		$encName = $module->encodeParamName( $name );
		if ( !isset( $settings['values-list'] ) &&
			count( ParamValidator::explodeMultiValue( $value, 2 ) ) > 1
		) {
			$values = array_map( function ( $v ) {
				return '<kbd>' . wfEscapeWikiText( $v ) . '</kbd>';
			}, $settings[ApiBase::PARAM_TYPE] );
			$module->dieWithError( [
				'apierror-multival-only-one-of',
				$encName,
				Message::listParam( $values ),
				count( $values ),
			], "multival_$encName" );
		} else {
			$module->dieWithError(
				[ 'apierror-unrecognizedvalue', $encName, wfEscapeWikiText( $value ) ],
				"unknown_$encName"
			);
		}
	}

	public function getEnumValues( $name, array $settings, ApiBase $module ) {
		return $settings[ApiBase::PARAM_TYPE];
	}

	public function normalizeSettings( array $settings ) {
		// Ignore invalid values by default to match historical behavior
		return parent::normalizeSettings( $settings + [
			ApiBase::PARAM_IGNORE_INVALID_VALUES => true,
		] );
	}

	public function getHelpInfo( IContextSource $context, $name, array $settings, ApiBase $module ) {
		$type = $settings[ApiBase::PARAM_TYPE];
		$deprecatedValues = isset( $settings[ApiBase::PARAM_DEPRECATED_VALUES] )
			? $settings[ApiBase::PARAM_DEPRECATED_VALUES]
			: [];
		$links = isset( $settings[ApiBase::PARAM_VALUE_LINKS] )
			? $settings[ApiBase::PARAM_VALUE_LINKS]
			: [];
		$values = array_map( function ( $v ) use ( $links, $deprecatedValues ) {
			$attr = [];
			if ( $v !== '' ) {
				// We can't know whether this contains LTR or RTL text.
				$attr['dir'] = 'auto';
			}
			if ( isset( $deprecatedValues[$v] ) ) {
				$attr['class'] = 'apihelp-deprecated-value';
			}
			$ret = $attr ? Html::element( 'span', $attr, $v ) : $v;
			if ( isset( $links[$v] ) ) {
				$ret = "[[{$links[$v]}|$ret]]";
			}
			return $ret;
		}, $type );
		$i = array_search( '', $type, true );
		if ( $i === false ) {
			$values = $context->getLanguage()->commaList( $values );
		} else {
			unset( $values[$i] );
			$values = $context->msg( 'api-help-param-list-can-be-empty' )
				->numParams( count( $values ) )
				->params( $context->getLanguage()->commaList( $values ) )
				->parse();
		}

		return [
			$context->msg( 'api-help-param-list' )
			->params( !empty( $settings[ApiBase::PARAM_ISMULTI] ) ? 2 : 1 )
			->params( $values )
			->parse(),
		];
	}

	public function needsHelpParamMultiSeparate() {
		return false;
	}

}
