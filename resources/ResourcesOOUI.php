<?php
/**
 * Definition of OOjs UI ResourceLoader modules.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

// WARNING: OOjs-UI is NOT TESTED with older browsers and is likely to break
// if loaded in browsers that don't support ES5
return call_user_func( function () {
	$themes = ExtensionRegistry::getInstance()->getAttribute( 'SkinOOUIThemes' );
	// We only use the theme names for file names, and they are lowercase
	$themes = array_map( 'strtolower', $themes );
	$themes['default'] = 'mediawiki';

	// Helper function to generate paths to files used in 'skinStyles' and 'skinScripts'.
	$getSkinSpecific = function ( $module, $ext = 'css' ) use ( $themes ) {
		return array_combine(
			array_keys( $themes ),
			array_map( function ( $theme ) use ( $module, $ext ) {
				$module = $module ? "$module-" : '';
				// TODO Allow extensions to specify this path somehow
				return "resources/lib/oojs-ui/oojs-ui-$module$theme.$ext";
			}, array_values( $themes ) )
		);
	};

	$modules = [];

	// Omnibus module.
	$modules['oojs-ui'] = [
		'dependencies' => [
			'oojs-ui-core',
			'oojs-ui-widgets',
			'oojs-ui-toolbars',
			'oojs-ui-windows',
		],
		'targets' => [ 'desktop', 'mobile' ],
	];

	// The core JavaScript library.
	$modules['oojs-ui-core'] = [
		'scripts' => [
			'resources/lib/oojs-ui/oojs-ui-core.js',
			'resources/src/oojs-ui-local.js',
		],
		'skinScripts' => $getSkinSpecific( null, 'js' ),
		'dependencies' => [
			'es5-shim',
			'oojs',
			'oojs-ui-core.styles',
			'oojs-ui.styles.icons',
			'oojs-ui.styles.indicators',
			'oojs-ui.styles.textures',
			'mediawiki.language',
		],
		'targets' => [ 'desktop', 'mobile' ],
	];
	// This contains only the styles required by core widgets.
	$modules['oojs-ui-core.styles'] = [
		'position' => 'top',
		'styles' => 'resources/src/oojs-ui-local.css', // HACK, see inside the file
		'skinStyles' => $getSkinSpecific( 'core' ),
		'targets' => [ 'desktop', 'mobile' ],
	];

	// Additional widgets and layouts module.
	$modules['oojs-ui-widgets'] = [
		'scripts' => 'resources/lib/oojs-ui/oojs-ui-widgets.js',
		'skinStyles' => $getSkinSpecific( 'widgets' ),
		'dependencies' => 'oojs-ui-core',
		'messages' => [
			'ooui-outline-control-move-down',
			'ooui-outline-control-move-up',
			'ooui-outline-control-remove',
			'ooui-selectfile-button-select',
			'ooui-selectfile-dragdrop-placeholder',
			'ooui-selectfile-not-supported',
			'ooui-selectfile-placeholder',
		],
		'targets' => [ 'desktop', 'mobile' ],
	];
	// Toolbar and tools module.
	$modules['oojs-ui-toolbars'] = [
		'scripts' => 'resources/lib/oojs-ui/oojs-ui-toolbars.js',
		'skinStyles' => $getSkinSpecific( 'toolbars' ),
		'dependencies' => 'oojs-ui-core',
		'messages' => [
			'ooui-toolbar-more',
			'ooui-toolgroup-collapse',
			'ooui-toolgroup-expand',
		],
		'targets' => [ 'desktop', 'mobile' ],
	];
	// Windows and dialogs module.
	$modules['oojs-ui-windows'] = [
		'scripts' => 'resources/lib/oojs-ui/oojs-ui-windows.js',
		'skinStyles' => $getSkinSpecific( 'windows' ),
		'dependencies' => 'oojs-ui-core',
		'messages' => [
			'ooui-dialog-message-accept',
			'ooui-dialog-message-reject',
			'ooui-dialog-process-continue',
			'ooui-dialog-process-dismiss',
			'ooui-dialog-process-error',
			'ooui-dialog-process-retry',
		],
		'targets' => [ 'desktop', 'mobile' ],
	];

	$imageSets = [
		// Comments for greppability
		'icons', // oojs-ui.styles.icons
		'indicators', // oojs-ui.styles.indicators
		'textures', // oojs-ui.styles.textures
		'icons-accessibility', // oojs-ui.styles.icons-accessibility
		'icons-alerts', // oojs-ui.styles.icons-alerts
		'icons-content', // oojs-ui.styles.icons-content
		'icons-editing-advanced', // oojs-ui.styles.icons-editing-advanced
		'icons-editing-core', // oojs-ui.styles.icons-editing-core
		'icons-editing-list', // oojs-ui.styles.icons-editing-list
		'icons-editing-styling', // oojs-ui.styles.icons-editing-styling
		'icons-interactions', // oojs-ui.styles.icons-interactions
		'icons-layout', // oojs-ui.styles.icons-layout
		'icons-location', // oojs-ui.styles.icons-location
		'icons-media', // oojs-ui.styles.icons-media
		'icons-moderation', // oojs-ui.styles.icons-moderation
		'icons-movement', // oojs-ui.styles.icons-movement
		'icons-user', // oojs-ui.styles.icons-user
		'icons-wikimedia', // oojs-ui.styles.icons-wikimedia
	];
	$rootPath = 'resources/lib/oojs-ui/themes';

	foreach ( $imageSets as $name ) {
		$module = [
			'position' => 'top',
			'class' => 'ResourceLoaderOOUIImageModule',
			'name' => $name,
			'rootPath' => $rootPath,
		];

		if ( substr( $name, 0, 5 ) === 'icons' ) {
			$module['selectorWithoutVariant'] = '.oo-ui-icon-{name}, .mw-ui-icon-{name}:before';
			$module['selectorWithVariant'] = '
				.oo-ui-image-{variant}.oo-ui-icon-{name}, .mw-ui-icon-{name}-{variant}:before,
				/* Hack for Flow, see T110051 */
				.mw-ui-hovericon:hover .mw-ui-icon-{name}-{variant}-hover:before,
				.mw-ui-hovericon.mw-ui-icon-{name}-{variant}-hover:hover:before';
		}

		$modules["oojs-ui.styles.$name"] = $module;
	}

	return $modules;
} );
