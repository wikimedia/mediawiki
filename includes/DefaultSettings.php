<?php
/**
 * THIS IS A DEPRECATED STUB FILE!
 *
 * Default settings are now defined in the MainConfigSchema class.
 *
 * To get default values for configuration variables, use MainConfigSchema::listDefaultValues()
 * or MainConfigSchema::getDefaultValue().
 *
 * @file
 * @deprecated since 1.39
 */

use MediaWiki\MainConfigSchema;

if ( function_exists( 'wfDeprecatedMsg' ) ) {
	wfDeprecatedMsg(
		'DefaultSettings.php is deprecated and will be removed. '
		. 'Use MainConfigSchema::listDefaultValues() or MainConfigSchema::getDefaultValue() instead.',
		'1.39'
	);
}

// Extract the defaults into the current scope
foreach ( MainConfigSchema::listDefaultValues( 'wg' ) as $defaultSettingsVar => $defaultSettingsValue ) {
	$$defaultSettingsVar = $defaultSettingsValue;
}

unset( $defaultSettingsVar );
unset( $defaultSettingsValue );
