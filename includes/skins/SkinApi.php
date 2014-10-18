<?php
/**
 * Extremely basic "skin" for API output, which needs to output a page without
 * the usual skin elements but still using CSS, JS, and such via OutputPage and
 * ResourceLoader.
 *
 * Created on Sep 08, 2014
 *
 * Copyright © 2014 Brad Jorsch <bjorsch@wikimedia.org>
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

/**
 * SkinTemplate class for API output
 * @since 1.25
 */
class SkinApi extends SkinTemplate {
	public $skinname = 'apioutput';
	public $template = 'SkinApiTemplate';

	public function setupSkinUserCss( OutputPage $out ) {
		parent::setupSkinUserCss( $out );
		$out->addModuleStyles( 'mediawiki.skinning.interface' );
	}

	// Skip work and hooks for stuff we don't use

	function buildSidebar() {
		return array();
	}

	function getNewtalks() {
		return '';
	}

	function getSiteNotice() {
		return '';
	}

	public function getLanguages() {
		return array();
	}

	protected function buildPersonalUrls() {
		return array();
	}

	protected function buildContentNavigationUrls() {
		return array();
	}

	protected function buildNavUrls() {
		return array();
	}
}
