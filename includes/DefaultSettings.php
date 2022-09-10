<?php
/**
 * THIS IS A DEPRECATED STUB FILE!
 *
 * Default settings are now defined in the MainConfigSchema class.
 *
 * To get default values for configuration variables, use the ConfigSchema service object.
 *
 * @file
 * @deprecated since 1.39
 */

use MediaWiki\MainConfigSchema;

if ( function_exists( 'wfDeprecatedMsg' ) ) {
	wfDeprecatedMsg(
		'DefaultSettings.php is deprecated and will be removed. '
		. 'Use the ConfigSchema service object instead.',
		'1.39'
	);
}

// Extract the defaults into the current scope
foreach ( MainConfigSchema::listDefaultValues( 'wg' ) as $defaultSettingsVar => $defaultSettingsValue ) {
	$$defaultSettingsVar = $defaultSettingsValue;
}

unset( $defaultSettingsVar );
unset( $defaultSettingsValue );
