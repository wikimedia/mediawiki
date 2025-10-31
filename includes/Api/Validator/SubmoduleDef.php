<?php

namespace MediaWiki\Api\Validator;

use MediaWiki\Api\ApiBase;
use MediaWiki\Api\ApiUsageException;
use MediaWiki\Html\Html;
use Wikimedia\ParamValidator\TypeDef\EnumDef;

/**
 * Type definition for submodule types
 *
 * A submodule type is an enum type for selecting Action API submodules.
 *
 * @since 1.35
 */
class SubmoduleDef extends EnumDef {

	/**
	 * (string[]) Map parameter values to submodule paths.
	 *
	 * Default is to use all modules in $options['module']->getModuleManager()
	 * in the group matching the parameter name.
	 */
	public const PARAM_SUBMODULE_MAP = 'param-submodule-map';

	/**
	 * (string) Used to indicate the 'g' prefix added by ApiQueryGeneratorBase
	 * (and similar if anything else ever does that).
	 */
	public const PARAM_SUBMODULE_PARAM_PREFIX = 'param-submodule-param-prefix';

	/** @inheritDoc */
	public function checkSettings( string $name, $settings, array $options, array $ret ): array {
		$map = $settings[self::PARAM_SUBMODULE_MAP] ?? [];
		if ( !is_array( $map ) ) {
			$ret['issues'][self::PARAM_SUBMODULE_MAP] = 'PARAM_SUBMODULE_MAP must be an array, got '
				. gettype( $map );
			// Prevent errors in parent::checkSettings()
			$settings[self::PARAM_SUBMODULE_MAP] = null;
		}

		$ret = parent::checkSettings( $name, $settings, $options, $ret );

		$ret['allowedKeys'] = array_merge( $ret['allowedKeys'], [
			self::PARAM_SUBMODULE_MAP, self::PARAM_SUBMODULE_PARAM_PREFIX,
		] );

		if ( is_array( $map ) ) {
			$module = $options['module'];
			foreach ( $map as $k => $v ) {
				if ( !is_string( $v ) ) {
					$ret['issues'][] = 'Values for PARAM_SUBMODULE_MAP must be strings, '
						. "but value for \"$k\" is " . gettype( $v );
					continue;
				}

				try {
					$submod = $module->getModuleFromPath( $v );
				} catch ( ApiUsageException ) {
					$submod = null;
				}
				if ( !$submod ) {
					$ret['issues'][] = "PARAM_SUBMODULE_MAP contains \"$v\", which is not a valid module path";
				}
			}
		}

		if ( !is_string( $settings[self::PARAM_SUBMODULE_PARAM_PREFIX] ?? '' ) ) {
			$ret['issues'][self::PARAM_SUBMODULE_PARAM_PREFIX] = 'PARAM_SUBMODULE_PARAM_PREFIX must be '
				. 'a string, got ' . gettype( $settings[self::PARAM_SUBMODULE_PARAM_PREFIX] );
		}

		return $ret;
	}

	/** @inheritDoc */
	public function getEnumValues( $name, array $settings, array $options ) {
		if ( isset( $settings[self::PARAM_SUBMODULE_MAP] ) ) {
			$modules = array_keys( $settings[self::PARAM_SUBMODULE_MAP] );
		} else {
			$modules = $options['module']->getModuleManager()->getNames( $name );
		}

		return $modules;
	}

	/** @inheritDoc */
	public function getParamInfo( $name, array $settings, array $options ) {
		$info = parent::getParamInfo( $name, $settings, $options );
		/** @var ApiBase $module */
		$module = $options['module'];

		if ( isset( $settings[self::PARAM_SUBMODULE_MAP] ) ) {
			$info['type'] = array_keys( $settings[self::PARAM_SUBMODULE_MAP] );
			$info['submodules'] = $settings[self::PARAM_SUBMODULE_MAP];
		} else {
			$info['type'] = $module->getModuleManager()->getNames( $name );
			$prefix = $module->isMain() ? '' : ( $module->getModulePath() . '+' );
			$info['submodules'] = [];
			foreach ( $info['type'] as $v ) {
				$info['submodules'][$v] = $prefix . $v;
			}
		}
		if ( isset( $settings[self::PARAM_SUBMODULE_PARAM_PREFIX] ) ) {
			$info['submoduleparamprefix'] = $settings[self::PARAM_SUBMODULE_PARAM_PREFIX];
		}

		$submoduleFlags = []; // for sorting: higher flags are sorted later
		$submoduleNames = []; // for sorting: lexicographical, ascending
		foreach ( $info['submodules'] as $v => $submodulePath ) {
			try {
				$submod = $module->getModuleFromPath( $submodulePath );
			} catch ( ApiUsageException ) {
				$submoduleFlags[] = 0;
				$submoduleNames[] = $v;
				continue;
			}
			$flags = 0;
			if ( $submod && $submod->isDeprecated() ) {
				$info['deprecatedvalues'][] = $v;
				$flags |= 1;
			}
			if ( $submod && $submod->isInternal() ) {
				$info['internalvalues'][] = $v;
				$flags |= 2;
			}
			$submoduleFlags[] = $flags;
			$submoduleNames[] = $v;
		}
		// sort $info['submodules'] and $info['type'] by $submoduleFlags and $submoduleNames
		array_multisort( $submoduleFlags, $submoduleNames, $info['submodules'], $info['type'] );
		if ( isset( $info['deprecatedvalues'] ) ) {
			sort( $info['deprecatedvalues'] );
		}
		if ( isset( $info['internalvalues'] ) ) {
			sort( $info['internalvalues'] );
		}

		return $info;
	}

	private function getSubmoduleMap( ApiBase $module, string $name, array $settings ): array {
		if ( isset( $settings[self::PARAM_SUBMODULE_MAP] ) ) {
			$map = $settings[self::PARAM_SUBMODULE_MAP];
		} else {
			$prefix = $module->isMain() ? '' : ( $module->getModulePath() . '+' );
			$map = [];
			foreach ( $module->getModuleManager()->getNames( $name ) as $submoduleName ) {
				$map[$submoduleName] = $prefix . $submoduleName;
			}
		}

		return $map;
	}

	protected function sortEnumValues(
		string $name, array $values, array $settings, array $options
	): array {
		$module = $options['module'];
		$map = $this->getSubmoduleMap( $module, $name, $settings );

		$submoduleFlags = []; // for sorting: higher flags are sorted later
		foreach ( $values as $k => $v ) {
			$flags = 0;
			try {
				$submod = isset( $map[$v] ) ? $module->getModuleFromPath( $map[$v] ) : null;
				if ( $submod && $submod->isDeprecated() ) {
					$flags |= 1;
				}
				if ( $submod && $submod->isInternal() ) {
					$flags |= 2;
				}
			} catch ( ApiUsageException ) {
				// Ignore
			}
			$submoduleFlags[$k] = $flags;
		}
		array_multisort( $submoduleFlags, $values, SORT_NATURAL );

		return $values;
	}

	/** @inheritDoc */
	protected function getEnumValuesForHelp( $name, array $settings, array $options ) {
		$module = $options['module'];
		$map = $this->getSubmoduleMap( $module, $name, $settings );
		$defaultAttrs = [ 'dir' => 'ltr', 'lang' => 'en' ];

		$values = [];
		$submoduleFlags = []; // for sorting: higher flags are sorted later
		$submoduleNames = []; // for sorting: lexicographical, ascending
		foreach ( $map as $v => $m ) {
			$attrs = $defaultAttrs;
			$flags = 0;
			try {
				$submod = $module->getModuleFromPath( $m );
				if ( $submod && $submod->isDeprecated() ) {
					$attrs['class'][] = 'apihelp-deprecated-value';
					$flags |= 1;
				}
				if ( $submod && $submod->isInternal() ) {
					$attrs['class'][] = 'apihelp-internal-value';
					$flags |= 2;
				}
			} catch ( ApiUsageException ) {
				// Ignore
			}
			$v = Html::element( 'span', $attrs, $v );
			$values[] = "[[Special:ApiHelp/{$m}|{$v}]]";
			$submoduleFlags[] = $flags;
			$submoduleNames[] = $v;
		}
		// sort $values by $submoduleFlags and $submoduleNames
		array_multisort( $submoduleFlags, $submoduleNames, SORT_NATURAL, $values, SORT_NATURAL );

		return $values;
	}

}
