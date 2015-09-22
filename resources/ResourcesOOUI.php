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

	$modules = array();
	$modules['oojs-ui'] = array(
		'scripts' => array(
			'resources/lib/oojs-ui/oojs-ui.js',
		),
		'skinScripts' => array_combine(
			array_keys( $themes ),
			array_map( function ( $theme ) {
				// TODO Allow extensions to specify this path somehow
				return "resources/lib/oojs-ui/oojs-ui-$theme.js";
			}, array_values( $themes ) )
		),
		'dependencies' => array(
			'es5-shim',
			'oojs',
			'oojs-ui.styles',
			'oojs-ui.styles.icons',
			'oojs-ui.styles.indicators',
			'oojs-ui.styles.textures',
		),
		'messages' => array(
			'ooui-dialog-message-accept',
			'ooui-dialog-message-reject',
			'ooui-dialog-process-continue',
			'ooui-dialog-process-dismiss',
			'ooui-dialog-process-error',
			'ooui-dialog-process-retry',
			'ooui-outline-control-move-down',
			'ooui-outline-control-move-up',
			'ooui-outline-control-remove',
			'ooui-selectfile-button-select',
			'ooui-selectfile-dragdrop-placeholder',
			'ooui-selectfile-not-supported',
			'ooui-selectfile-placeholder',
			'ooui-toolbar-more',
			'ooui-toolgroup-collapse',
			'ooui-toolgroup-expand',
		),
		'targets' => array( 'desktop', 'mobile' ),
	);
	$modules['oojs-ui.styles'] = array(
		'position' => 'top',
		'styles' => 'resources/src/oojs-ui-local.css', // HACK, see inside the file
		'skinStyles' => array_combine(
			array_keys( $themes ),
			array_map( function ( $theme ) {
				// TODO Allow extensions to specify this path somehow
				return "resources/lib/oojs-ui/oojs-ui-$theme-noimages.css";
			}, array_values( $themes ) )
		),
		'targets' => array( 'desktop', 'mobile' ),
	);

	$imageSets = array(
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
	);
	$rootPath = 'resources/lib/oojs-ui/themes';

	foreach ( $imageSets as $name ) {
		$module = array(
			'position' => 'top',
			'class' => 'ResourceLoaderOOUIImageModule',
			'name' => $name,
			'rootPath' => $rootPath,
		);

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
