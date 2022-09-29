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
 *
 * @file
 */

/**
 * IndexPager with an alphabetic list and a formatted navigation bar
 *
 * @stable to extend
 * @ingroup Pager
 */
abstract class AlphabeticPager extends IndexPager {

	/**
	 * @stable to override
	 * @return string HTML
	 */
	public function getNavigationBar() {
		if ( !$this->isNavigationBarShown() ) {
			return '';
		}

		if ( isset( $this->mNavigationBar ) ) {
			return $this->mNavigationBar;
		}

		$navBuilder = $this->getNavigationBuilder()
			->setPrevMsg( 'prevn' )
			->setNextMsg( 'nextn' )
			->setFirstMsg( 'page_first' )
			->setLastMsg( 'page_last' );

		if ( is_array( $this->getIndexField() ) ) {
			$extra = '';
			$msgs = $this->getOrderTypeMessages();
			foreach ( $msgs as $order => $msg ) {
				if ( $extra !== '' ) {
					$extra .= $this->msg( 'pipe-separator' )->escaped();
				}

				if ( $order == $this->mOrderType ) {
					$extra .= $this->msg( $msg )->escaped();
				} else {
					$extra .= $this->makeLink(
						$this->msg( $msg )->escaped(),
						[ 'order' => $order ]
					);
				}
			}

			$navBuilder->setExtra( $extra );
		}

		$this->mNavigationBar = $navBuilder->getHtml();

		return $this->mNavigationBar;
	}

	/**
	 * If this supports multiple order type messages, give the message key for
	 * enabling each one in getNavigationBar.  The return type is an associative
	 * array whose keys must exactly match the keys of the array returned
	 * by getIndexField(), and whose values are message keys.
	 *
	 * @stable to override
	 *
	 * @return array|null
	 */
	protected function getOrderTypeMessages() {
		return null;
	}
}
