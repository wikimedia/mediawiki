<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
 *
 * @file
 * @ingroup Actions
 */

namespace MediaWiki\Actions;

/**
 * Handle action=render
 *
 * This is a wrapper that will call Article::render().
 *
 * @ingroup Actions
 */
class RenderAction extends FormlessAction {

	/** @inheritDoc */
	public function getName() {
		return 'render';
	}

	/** @inheritDoc */
	public function onView() {
		return null;
	}

	/** @inheritDoc */
	public function show() {
		$this->getArticle()->render();
	}
}

/** @deprecated class alias since 1.44 */
class_alias( RenderAction::class, 'RenderAction' );
