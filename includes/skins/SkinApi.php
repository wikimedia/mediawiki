<?php
/**
 * Extremely basic "skin" for API output, which needs to output a page without
 * the usual skin elements but still using CSS, JS, and such via OutputPage and
 * ResourceLoader.
 *
 * Copyright © 2014 Wikimedia Foundation and contributors
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
	public $template = SkinApiTemplate::class;

	public function getDefaultModules() {
		$modules = parent::getDefaultModules();
		$modules['styles']['skin'][] = 'mediawiki.skinning.interface';
		return $modules;
	}

	/**
	 * Override to do nothing since we don't use it
	 */
	function buildSidebar() {
		return [];
	}

	/**
	 * Override to do nothing since we don't use it
	 */
	function getNewtalks() {
		return '';
	}

	/**
	 * Override to do nothing since we don't use it
	 */
	function getSiteNotice() {
		return '';
	}

	/**
	 * Override to do nothing since we don't use it
	 */
	public function getLanguages() {
		return [];
	}

	/**
	 * Override to do nothing since we don't use it
	 */
	protected function buildPersonalUrls() {
		return [];
	}

	/**
	 * Override to do nothing since we don't use it
	 */
	protected function buildContentNavigationUrls() {
		return [];
	}

	/**
	 * Override to do nothing since we don't use it
	 */
	protected function buildNavUrls() {
		return [];
	}
}
