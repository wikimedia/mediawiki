<?php
/**
 * Mobile device detection code
 *
 * Copyright © 2011 Patrick Reilly
 * http://www.mediawiki.org/
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
 * Base for classes describing devices and their capabilities
 * @since 1.20
 */
interface IDeviceProperties {
	/**
	 * @return string: 'html' or 'wml'
	 */
	function format();

	/**
	 * @return bool
	 */
	function supportsJavaScript();

	/**
	 * @return bool
	 */
	function supportsJQuery();

	/**
	 * @return bool
	 */
	function disableZoom();
}

/**
 * @since 1.20
 */
interface IDeviceDetector {
	/**
	 * @param $userAgent
	 * @param string $acceptHeader
	 * @return IDeviceProperties
	 */
	function detectDeviceProperties( $userAgent, $acceptHeader = '' );

	/**
	 * @param $deviceName
	 * @return IDeviceProperties
	 */
	function getDeviceProperties( $deviceName );

	/**
	 * @param $userAgent string
	 * @param $acceptHeader string
	 * @return string
	 */
	function detectDeviceName( $userAgent, $acceptHeader = '' );
}

/**
 * MediaWiki's default IDeviceProperties implementation
 */
final class DeviceProperties implements IDeviceProperties {
	private $device;

	public function __construct( array $deviceCapabilities ) {
		$this->device = $deviceCapabilities;
	}

	/**
	 * @return string
	 */
	function format() {
		return $this->device['view_format'];
	}

	/**
	 * @return bool
	 */
	function supportsJavaScript() {
		return $this->device['supports_javascript'];
	}

	/**
	 * @return bool
	 */
	function supportsJQuery() {
		return $this->device['supports_jquery'];
	}

	/**
	 * @return bool
	 */
	function disableZoom() {
		return $this->device['disable_zoom'];
	}
}

/**
 * Provides abstraction for a device.
 * A device can select which format a request should receive and
 * may be extended to provide access to particular device functionality.
 * @since 1.20
 */
class DeviceDetection implements IDeviceDetector {

	private static $formats = array (
			'html' => array (
				'view_format' => 'html',
				'css_file_name' => 'default',
				'supports_javascript' => false,
				'supports_jquery' => false,
				'disable_zoom' => true,
			),
			'capable' => array (
				'view_format' => 'html',
				'css_file_name' => 'default',
				'supports_javascript' => true,
				'supports_jquery' => true,
				'disable_zoom' => true,
			),
			'webkit' => array (
				'view_format' => 'html',
				'css_file_name' => 'webkit',
				'supports_javascript' => true,
				'supports_jquery' => true,
				'disable_zoom' => false,
			),
			'ie' => array (
				'view_format' => 'html',
				'css_file_name' => 'default',
				'supports_javascript' => true,
				'supports_jquery' => true,
				'disable_zoom' => false,
			),
			'android' => array (
				'view_format' => 'html',
				'css_file_name' => 'android',
				'supports_javascript' => true,
				'supports_jquery' => true,
				'disable_zoom' => false,
			),
			'iphone' => array (
				'view_format' => 'html',
				'css_file_name' => 'iphone',
				'supports_javascript' => true,
				'supports_jquery' => true,
				'disable_zoom' => false,
			),
			'iphone2' => array (
				'view_format' => 'html',
				'css_file_name' => 'iphone2',
				'supports_javascript' => true,
				'supports_jquery' => true,
				'disable_zoom' => true,
			),
			'native_iphone' => array (
				'view_format' => 'html',
				'css_file_name' => 'default',
				'supports_javascript' => true,
				'supports_jquery' => true,
				'disable_zoom' => false,
			),
			'palm_pre' => array (
				'view_format' => 'html',
				'css_file_name' => 'palm_pre',
				'supports_javascript' => true,
				'supports_jquery' => false,
				'disable_zoom' => true,
			),
			'kindle' => array (
				'view_format' => 'html',
				'css_file_name' => 'kindle',
				'supports_javascript' => false,
				'supports_jquery' => false,
				'disable_zoom' => true,
			),
			'kindle2' => array (
				'view_format' => 'html',
				'css_file_name' => 'kindle',
				'supports_javascript' => false,
				'supports_jquery' => false,
				'disable_zoom' => true,
			),
			'blackberry' => array (
				'view_format' => 'html',
				'css_file_name' => 'blackberry',
				'supports_javascript' => true,
				'supports_jquery' => false,
				'disable_zoom' => true,
			),
			'blackberry-lt5' => array (
				'view_format' => 'html',
				'css_file_name' => 'blackberry',
				'supports_javascript' => false,
				'supports_jquery' => false,
				'disable_zoom' => true,
			),
			'netfront' => array (
				'view_format' => 'html',
				'css_file_name' => 'simple',
				'supports_javascript' => false,
				'supports_jquery' => false,
				'disable_zoom' => true,
			),
			'wap2' => array (
				'view_format' => 'html',
				'css_file_name' => 'simple',
				'supports_javascript' => false,
				'supports_jquery' => false,
				'disable_zoom' => true,
			),
			'psp' => array (
				'view_format' => 'html',
				'css_file_name' => 'psp',
				'supports_javascript' => false,
				'supports_jquery' => false,
				'disable_zoom' => true,
			),
			'ps3' => array (
				'view_format' => 'html',
				'css_file_name' => 'simple',
				'supports_javascript' => false,
				'supports_jquery' => false,
				'disable_zoom' => true,
			),
			'wii' => array (
				'view_format' => 'html',
				'css_file_name' => 'wii',
				'supports_javascript' => true,
				'supports_jquery' => true,
				'disable_zoom' => true,
			),
			'operamini' => array (
				'view_format' => 'html',
				'css_file_name' => 'operamini',
				'supports_javascript' => false,
				'supports_jquery' => false,
				'disable_zoom' => true,
			),
			'operamobile' => array (
				'view_format' => 'html',
				'css_file_name' => 'operamobile',
				'supports_javascript' => true,
				'supports_jquery' => true,
				'disable_zoom' => true,
			),
			'nokia' => array (
				'view_format' => 'html',
				'css_file_name' => 'nokia',
				'supports_javascript' => true,
				'supports_jquery' => false,
				'disable_zoom' => true,
			),
			'wml' => array (
				'view_format' => 'wml',
				'css_file_name' => null,
				'supports_javascript' => false,
				'supports_jquery' => false,
				'disable_zoom' => true,
			),
		);

	/**
	 * Returns an instance of detection class, overridable by extensions
	 * @return IDeviceDetector
	 */
	public static function factory() {
		global $wgDeviceDetectionClass;

		static $instance = null;
		if ( !$instance ) {
			$instance = new $wgDeviceDetectionClass();
		}
		return $instance;
	}

	/**
	 * @deprecated: Deprecated, will be removed once detectDeviceProperties() will be deployed everywhere on WMF
	 * @param $userAgent
	 * @param string $acceptHeader
	 * @return array
	 */
	public function detectDevice( $userAgent, $acceptHeader = '' ) {
		$formatName = $this->detectFormatName( $userAgent, $acceptHeader );
		return $this->getDevice( $formatName );
	}

	/**
	 * @param $userAgent
	 * @param string $acceptHeader
	 * @return IDeviceProperties
	 */
	public function detectDeviceProperties( $userAgent, $acceptHeader = '' ) {
		$deviceName = $this->detectDeviceName( $userAgent, $acceptHeader );
		return $this->getDeviceProperties( $deviceName );
	}

	/**
	 * @deprecated: Deprecated, will be removed once detectDeviceProperties() will be deployed everywhere on WMF
	 * @param $formatName
	 * @return array
	 */
	public function getDevice( $formatName ) {
		return ( isset( self::$formats[$formatName] ) ) ? self::$formats[$formatName] : array();
	}

	/**
	 * @param $deviceName
	 * @return IDeviceProperties
	 */
	public function getDeviceProperties( $deviceName ) {
		if ( isset( self::$formats[$deviceName] ) ) {
			return new DeviceProperties( self::$formats[$deviceName] );
		} else {
			return new DeviceProperties( array(
				'view_format' => 'html',
				'css_file_name' => 'default',
				'supports_javascript' => true,
				'supports_jquery' => true,
				'disable_zoom' => true,
			) );
		}
	}

	/**
	 * @deprecated: Renamed to detectDeviceName()
	 * @param $userAgent string
	 * @param $acceptHeader string
	 * @return string
	 */
	public function detectFormatName( $userAgent, $acceptHeader = '' ) {
		return $this->detectDeviceName( $userAgent, $acceptHeader );
	}

	/**
	 * @param $userAgent string
	 * @param $acceptHeader string
	 * @return string
	 */
	public function detectDeviceName( $userAgent, $acceptHeader = '' ) {
		wfProfileIn( __METHOD__ );

		$deviceName = '';
		if ( preg_match( '/Android/', $userAgent ) ) {
			$deviceName = 'android';
			if ( strpos( $userAgent, 'Opera Mini' ) !== false ) {
				$deviceName = 'operamini';
			} elseif ( strpos( $userAgent, 'Opera Mobi' ) !== false ) {
				$deviceName = 'operamobile';
			}
		} elseif ( preg_match( '/MSIE 9.0/', $userAgent ) ||
				preg_match( '/MSIE 8.0/', $userAgent ) ) {
			$deviceName = 'ie';
		} elseif( preg_match( '/MSIE/', $userAgent ) ) {
			$deviceName = 'html';
		} elseif ( strpos( $userAgent, 'Opera Mobi' ) !== false ) {
			$deviceName = 'operamobile';
		} elseif ( preg_match( '/iPad.* Safari/', $userAgent ) ) {
			$deviceName = 'iphone';
		} elseif ( preg_match( '/iPhone.* Safari/', $userAgent ) ) {
			if ( strpos( $userAgent, 'iPhone OS 2' ) !== false ) {
				$deviceName = 'iphone2';
			} else {
				$deviceName = 'iphone';
			}
		} elseif ( preg_match( '/iPhone/', $userAgent ) ) {
			if ( strpos( $userAgent, 'Opera' ) !== false ) {
				$deviceName = 'operamini';
			} else {
				$deviceName = 'native_iphone';
			}
		} elseif ( preg_match( '/WebKit/', $userAgent ) ) {
			if ( preg_match( '/Series60/', $userAgent ) ) {
				$deviceName = 'nokia';
			} elseif ( preg_match( '/webOS/', $userAgent ) ) {
				$deviceName = 'palm_pre';
			} else {
				$deviceName = 'webkit';
			}
		} elseif ( preg_match( '/Opera/', $userAgent ) ) {
			if ( strpos( $userAgent, 'Nintendo Wii' ) !== false ) {
				$deviceName = 'wii';
			} elseif ( strpos( $userAgent, 'Opera Mini' ) !== false ) {
				$deviceName = 'operamini';
			} else {
				$deviceName = 'operamobile';
			}
		} elseif ( preg_match( '/Kindle\/1.0/', $userAgent ) ) {
			$deviceName = 'kindle';
		} elseif ( preg_match( '/Kindle\/2.0/', $userAgent ) ) {
			$deviceName = 'kindle2';
		} elseif ( preg_match( '/Firefox/', $userAgent ) ) {
			$deviceName = 'capable';
		} elseif ( preg_match( '/NetFront/', $userAgent ) ) {
			$deviceName = 'netfront';
		} elseif ( preg_match( '/SEMC-Browser/', $userAgent ) ) {
			$deviceName = 'wap2';
		} elseif ( preg_match( '/Series60/', $userAgent ) ) {
			$deviceName = 'wap2';
		} elseif ( preg_match( '/PlayStation Portable/', $userAgent ) ) {
			$deviceName = 'psp';
		} elseif ( preg_match( '/PLAYSTATION 3/', $userAgent ) ) {
			$deviceName = 'ps3';
		} elseif ( preg_match( '/SAMSUNG/', $userAgent ) ) {
			$deviceName = 'capable';
		} elseif ( preg_match( '/BlackBerry/', $userAgent ) ) {
			if( preg_match( '/BlackBerry[^\/]*\/[1-4]\./', $userAgent ) ) {
				$deviceName = 'blackberry-lt5';
			} else {
				$deviceName = 'blackberry';
			}
		}

		if ( $deviceName === '' ) {
			if ( strpos( $acceptHeader, 'application/vnd.wap.xhtml+xml' ) !== false ) {
				// Should be wap2
				$deviceName = 'html';
			} elseif ( strpos( $acceptHeader, 'vnd.wap.wml' ) !== false ) {
				$deviceName = 'wml';
			} else {
				$deviceName = 'html';
			}
		}
		wfProfileOut( __METHOD__ );
		return $deviceName;
	}

	/**
	 * @return array: List of all device-specific stylesheets
	 */
	public function getCssFiles() {
		$files = array();

		foreach ( self::$formats as $dev ) {
			if ( isset( $dev['css_file_name'] ) ) {
				$files[] = $dev['css_file_name'];
			}
		}
		return array_unique( $files );
	}
}
