<?php

namespace MediaWiki\Api\TypeDef;

use ApiBase;
use ApiUsageException;
use Html;
use IContextSource;

/**
 * API type definition for submodule types
 * @since 1.32
 * @ingroup API
 */
class SubmoduleDef extends EnumDef {

	private function getSubmodules( $name, array $settings, ApiBase $module ) {
		if ( isset( $settings[ApiBase::PARAM_SUBMODULE_MAP] ) ) {
			$modules = array_keys( $settings[ApiBase::PARAM_SUBMODULE_MAP] );
		} else {
			$modules = $module->getModuleManager()->getNames( $name );
		}

		return $modules;
	}

	public function validate( $name, $value, array $settings, ApiBase $module ) {
		$settings = [
			ApiBase::PARAM_TYPE => $this->getSubmodules( $name, $settings, $module ),
		] + $settings;
		return parent::validate( $name, $value, $settings, $module );
	}

	public function getHelpInfo( IContextSource $context, $name, array $settings, ApiBase $module ) {
		$groups[] = $name;

		if ( isset( $settings[ApiBase::PARAM_SUBMODULE_MAP] ) ) {
			$map = $settings[ApiBase::PARAM_SUBMODULE_MAP];
			$defaultAttrs = [];
		} else {
			$prefix = $module->isMain() ? '' : ( $module->getModulePath() . '+' );
			$map = [];
			foreach ( $module->getModuleManager()->getNames( $name ) as $submoduleName ) {
				$map[$submoduleName] = $prefix . $submoduleName;
			}
			$defaultAttrs = [ 'dir' => 'ltr', 'lang' => 'en' ];
		}
		ksort( $map );

		$submodules = [];
		$deprecatedSubmodules = [];
		foreach ( $map as $v => $m ) {
			$attrs = $defaultAttrs;
			$arr = &$submodules;
			try {
				$submod = $module->getModuleFromPath( $m );
				if ( $submod ) {
					if ( $submod->isDeprecated() ) {
						$arr = &$deprecatedSubmodules;
						$attrs['class'] = 'apihelp-deprecated-value';
					}
				}
			} catch ( ApiUsageException $ex ) {
				// Ignore
			}
			if ( $attrs ) {
				$v = Html::element( 'span', $attrs, $v );
			}
			$arr[] = "[[Special:ApiHelp/{$m}|{$v}]]";
		}
		$submodules = array_merge( $submodules, $deprecatedSubmodules );
		return [
			$context->msg( 'api-help-param-list' )
				->params( !empty( $settings[ApiBase::PARAM_ISMULTI] ) ? 2 : 1 )
				->params( $context->getLanguage()->commaList( $submodules ) )
				->parse(),
		];
	}

	public function getEnumValues( $name, array $settings, ApiBase $module ) {
		return $this->getSubmodules( $name, $settings, $module );
	}

	public function getParamInfo( $name, array $settings, ApiBase $module ) {
		$info = parent::getParamInfo( $name, $settings, $module );

		if ( isset( $settings[ApiBase::PARAM_SUBMODULE_MAP] ) ) {
			ksort( $settings[ApiBase::PARAM_SUBMODULE_MAP] );
			$info['type'] = array_keys( $settings[ApiBase::PARAM_SUBMODULE_MAP] );
			$info['submodules'] = $settings[ApiBase::PARAM_SUBMODULE_MAP];
		} else {
			$info['type'] = $module->getModuleManager()->getNames( $name );
			sort( $info['type'] );
			$prefix = $module->isMain()
				? '' : ( $module->getModulePath() . '+' );
			$info['submodules'] = [];
			foreach ( $info['type'] as $v ) {
				$info['submodules'][$v] = $prefix . $v;
			}
		}
		if ( isset( $settings[ApiBase::PARAM_SUBMODULE_PARAM_PREFIX] ) ) {
			$info['submoduleparamprefix'] = $settings[ApiBase::PARAM_SUBMODULE_PARAM_PREFIX];
		}

		$deprecatedSubmodules = [];
		foreach ( $info['submodules'] as $v => $submodulePath ) {
			try {
				$submod = $module->getModuleFromPath( $submodulePath );
				if ( $submod && $submod->isDeprecated() ) {
					$deprecatedSubmodules[] = $v;
				}
			} catch ( ApiUsageException $ex ) {
				// Ignore
			}
		}
		if ( $deprecatedSubmodules ) {
			$info['type'] = array_merge(
				array_diff( $info['type'], $deprecatedSubmodules ),
				$deprecatedSubmodules
			);
			$info['deprecatedvalues'] = $deprecatedSubmodules;
		}

		return $info;
	}

}
