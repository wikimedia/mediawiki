<?php
/**
 * Extremely basic "skin" for things that want to output a page without the
 * usual skin elements but still using CSS, JS, and such via OutputPage and
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
 * BaseTemplate class for the 'bare' skin
 * @since 1.24
 */
class SkinBareTemplate extends BaseTemplate {

	public function execute() {
		$this->html( 'headelement' ) ?>

		<div class="mw-body" role="main">
			<h1 class="firstHeading">
				<span dir="auto"><?php $this->html( 'title' ) ?></span>
			</h1>
			<div class="mw-body-content">
				<?php $this->html( 'bodytext' ) ?>
			</div>
		</div>

		<?php $this->printTrail() ?>
		</body></html>

	<?php
	}
}
