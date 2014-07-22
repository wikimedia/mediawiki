<?php
/**
 * Vector - Modern version of MonoBook with fresh look and many usability
 * improvements.
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
 * @ingroup Skins
 */

$wgExtensionCredits['skin'][] = array(
	'path' => __FILE__,
	'name' => 'Vector',
	'namemsg' => 'skinname-vector',
	'descriptionmsg' => 'vector-skin-desc',
	'url' => 'https://www.mediawiki.org/wiki/Skin:Vector',
	'author' => array( 'Trevor Parscal', 'Roan Kattouw', '...' ),
	'license-name' => 'GPLv2+',
);

// Register files
$wgAutoloadClasses['SkinVector'] = __DIR__ . '/SkinVector.php';
$wgAutoloadClasses['VectorTemplate'] = __DIR__ . '/VectorTemplate.php';
$wgMessagesDirs['Vector'] = __DIR__ . '/i18n';

// Register skin
$wgValidSkinNames['vector'] = 'Vector';

// Register modules
$wgResourceModules['skins.vector.styles'] = array(
	'styles' => array(
		'screen.less' => array( 'media' => 'screen' ),
		'screen-hd.less' => array( 'media' => 'screen and (min-width: 982px)' ),
	),
	'remoteSkinPath' => 'Vector',
	'localBasePath' => __DIR__,
);
$wgResourceModules['skins.vector.js'] = array(
	'scripts' => array(
		'collapsibleTabs.js',
		'vector.js',
	),
	'position' => 'top',
	'dependencies' => array(
		'jquery.throttle-debounce',
		'jquery.tabIndex',
	),
	'remoteSkinPath' => 'Vector',
	'localBasePath' => __DIR__,
);

// Apply module customizations
$wgResourceModuleSkinStyles['vector'] = array(
	'jquery.tipsy' => 'skinStyles/jquery.tipsy.less',
	'jquery.ui.core' => array(
		'skinStyles/jquery.ui/jquery.ui.core.css',
		'skinStyles/jquery.ui/jquery.ui.theme.css',
	),
	'jquery.ui.accordion' => 'skinStyles/jquery.ui/jquery.ui.accordion.css',
	'jquery.ui.autocomplete' => 'skinStyles/jquery.ui/jquery.ui.autocomplete.css',
	'jquery.ui.button' => 'skinStyles/jquery.ui/jquery.ui.button.css',
	'jquery.ui.datepicker' => 'skinStyles/jquery.ui/jquery.ui.datepicker.css',
	'jquery.ui.dialog' => 'skinStyles/jquery.ui/jquery.ui.dialog.css',
	'jquery.ui.progressbar' => 'skinStyles/jquery.ui/jquery.ui.progressbar.css',
	'jquery.ui.resizable' => 'skinStyles/jquery.ui/jquery.ui.resizable.css',
	'jquery.ui.selectable' => 'skinStyles/jquery.ui/jquery.ui.selectable.css',
	'jquery.ui.slider' => 'skinStyles/jquery.ui/jquery.ui.slider.css',
	'jquery.ui.tabs' => 'skinStyles/jquery.ui/jquery.ui.tabs.css',
	'mediawiki.notification' => 'skinStyles/mediawiki.notification.less',
	'mediawiki.special' => 'skinStyles/mediawiki.special.less',
	'mediawiki.special.preferences' => 'skinStyles/mediawiki.special.preferences.less',
	'remoteSkinPath' => 'Vector',
	'localBasePath' => __DIR__,
);

// Configuration options

/**
 * Search form look.
 *  - true = use an icon search button
 *  - false = use Go & Search buttons
 */
$wgVectorUseSimpleSearch = true;

/**
 * Watch and unwatch as an icon rather than a link.
 *  - true = use an icon watch/unwatch button
 *  - false = use watch/unwatch text link
 */
$wgVectorUseIconWatch = true;
