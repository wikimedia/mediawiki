<?php
/**
 * MonoBook nouveau.
 *
 * Translated from gwicke's previous TAL template version to remove
 * dependency on PHPTAL.
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
	'name' => 'MonoBook',
	'namemsg' => 'skinname-monobook',
	'descriptionmsg' => 'monobook-desc',
	'url' => 'https://www.mediawiki.org/wiki/Skin:MonoBook',
	'author' => array( 'Gabriel Wicke', '...' ),
	'license-name' => 'GPLv2+',
);

// Register files
$wgAutoloadClasses['SkinMonoBook'] = __DIR__ . '/SkinMonoBook.php';
$wgAutoloadClasses['MonoBookTemplate'] = __DIR__ . '/MonoBookTemplate.php';
$wgMessagesDirs['MonoBook'] = __DIR__ . '/i18n';

// Register skin
$wgValidSkinNames['monobook'] = 'MonoBook';

// Register modules
$wgResourceModules['skins.monobook.styles'] = array(
	'styles' => array(
		'main.css' => array( 'media' => 'screen' ),
	),
	'remoteSkinPath' => 'MonoBook',
	'localBasePath' => __DIR__,
);
