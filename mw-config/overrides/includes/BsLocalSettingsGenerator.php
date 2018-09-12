<?php
/**
 * Generator for LocalSettings.php file.
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
 * @ingroup Deployment
 */

/**
 * Class for generating BlueSpice LocalSettings.php file.
 *
 * @ingroup Deployment
 * @since 2.27
 *
 * @author Stephan Muggli
 * @author Robert Vogel <vogel@hallowelt.com>
 */
class BsLocalSettingsGenerator extends LocalSettingsGenerator {

	/**
	 * Return the full text of the generated LocalSettings.php file,
	 * including the extensions and skins.
	 *
	 * @return string
	 */
	public function getText() {
		$this->extensions = [];
		$this->skins = [];

		$localSettings = parent::getText();

		$localSettings .= "
# This is the main settings file for all BlueSpice extensions and settings
# It will include all files in \"\$IP/settings.d/\" directory
require_once \"\$IP/LocalSettings.BlueSpice.php\";
";

		$localSettings .= "\n";	
		$localSettings .= "
\$wgUserMergeProtectedGroups = array();
\$wgUserMergeUnmergeable = array();
\$wgMFAutodetectMobileView = true;
\$wgMFEnableDesktopResources = true;

# Convenience for debugging
# \$wgShowSQLErrors = true;
# \$wgDebugDumpSql  = true;
# \$wgShowExceptionDetails = true;
# \$wgShowDBErrorBacktrace = true;

";
		$old=['$wgPingback = true;'];
		$new=['$wgPingback = false;'];
		$localSettings= str_replace($old, $new, $localSettings);

		return $localSettings;
	}
}
