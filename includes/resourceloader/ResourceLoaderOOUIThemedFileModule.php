<?php
/**
 * ResourceLoader module for modules that need to know the name of the current OOjs UI theme.
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

class ResourceLoaderOOUIThemedFileModule extends ResourceLoaderFileModule {

	protected function getLessVars( ResourceLoaderContext $context ) {
		$vars = parent::getLessVars( $context );

		$themes = ExtensionRegistry::getInstance()->getAttribute( 'SkinOOUIThemes' );
		$skin = $context->getSkin();
		$theme = isset( $themes[$skin] ) ? $themes[$skin] : 'MediaWiki';

		$vars['oo-ui-theme'] = $theme;
		return $vars;
	}

}
