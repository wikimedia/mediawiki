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
 * @ingroup Testing
 */

/**
 * Initialize and detect the tidy support
 */
class TidySupport {
	private $enabled;
	private $config;

	/**
	 * Determine if there is a usable tidy.
	 * @param bool $useConfiguration
	 */
	public function __construct( $useConfiguration = false ) {
		global $wgUseTidy, $wgTidyBin, $wgTidyInternal, $wgTidyConfig,
			$wgTidyConf, $wgTidyOpts;

		$this->enabled = true;
		if ( $useConfiguration ) {
			if ( $wgTidyConfig !== null ) {
				$this->config = $wgTidyConfig;
			} elseif ( $wgUseTidy ) {
				$this->config = [
					'tidyConfigFile' => $wgTidyConf,
					'debugComment' => false,
					'tidyBin' => $wgTidyBin,
					'tidyCommandLine' => $wgTidyOpts
				];
				if ( $wgTidyInternal ) {
					$this->config['driver'] = wfIsHHVM() ? 'RaggettInternalHHVM' : 'RaggettInternalPHP';
				} else {
					$this->config['driver'] = 'RaggettExternal';
				}
			} else {
				$this->enabled = false;
			}
		} else {
			$this->config = [ 'driver' => 'RemexHtml' ];
		}
		if ( !$this->enabled ) {
			$this->config = [ 'driver' => 'disabled' ];
		}
	}

	/**
	 * Returns true if tidy is usable
	 *
	 * @return bool
	 */
	public function isEnabled() {
		return $this->enabled;
	}

	public function getConfig() {
		return $this->config;
	}
}
