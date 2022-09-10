<?php
/**
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
 */

namespace MediaWiki\Skin;

/**
 * @internal for use inside Skin and SkinTemplate classes only
 * @unstable
 */
interface SkinComponent {
	/**
	 * This returns all the data that is needed to the component.
	 * Returned array must be serialized. This will be passed directly
	 * to a template (usually Mustache) for rendering.
	 *
	 * @return array Data related to component required to render.
	 */
	public function getTemplateData(): array;
}
