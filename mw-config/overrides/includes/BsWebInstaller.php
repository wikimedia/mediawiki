<?php
/**
 * Core installer web interface.
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
 * Class for the core and BlueSpice installer web interface.
 *
 * @ingroup Deployment
 * @since 2.27
 *
 * @author Stephan Muggli
 * @author Robert Vogel <vogel@hallowelt.com>
 */
class BsWebInstaller extends WebInstaller {
	protected static $aOptions = null;
	protected $extensionsLoaded = [];

	protected function getOptions() {
		if( !is_null(static::$aOptions) ) {
			return static::$aOptions;
		}

		$sJson = file_get_contents(
			$this->getVar( 'IP' )."/mw-config/overrides/distribution.json"
		);
		static::$aOptions = (array) json_decode( $sJson );
	}

	public function __construct( \WebRequest $request ) {
		// BlueSpice
		global $wgMessagesDirs;
		$wgMessagesDirs['BlueSpiceInstaller'] = __DIR__ .'/../i18n';

		parent::__construct( $request );
		$this->output = new BsWebInstallerOutput( $this );
	}

	/**
	 * Installs the auto-detected extensions.
	 * @override Added "$IP/LocalSettings.BlueSpice.php"
	 * @return Status
	 */
	protected function includeExtensions() {
		global $IP, $wgAutoloadClasses;
		$exts = $this->getVar( '_Extensions' );
		$IP = $this->getVar( 'IP' );
		$wgAutoloadClasses = [];

		require "$IP/includes/DefaultSettings.php";

		// Manual dependency to BSF
		$this->doIncludeExtensions( [
			"BlueSpiceFoundation"
		] );

		// Include all other
		$this->doIncludeExtensions( $exts );

		return Status::newGood();
	}

	/**
	 * Loads all declared dependencies for given path
	 *
	 * @param string $path
	 * @throws Exception
	 */
	private function loadDependencies( $path ) {
		$processor = new ExtensionProcessor();

		$json = file_get_contents( $path );
		if ( $json === false ) {
			return;
		}
		$info = json_decode( $json, true );
		if ( !is_array( $info ) ) {
			return;
		}

		$requires = $processor->getRequirements( $info, false );
		if ( is_array( $requires ) && $requires && isset( $requires['extensions'] ) ) {
			$deps = $requires['extensions'];
			if ( is_array( $deps ) ) {
				// We are not interested in if the correct version is available,
				// we just want to make sure extension is available at all
				$this->doIncludeExtensions( array_keys( $deps ) );
			}
		}
	}

	/**
	 * Loads/includes extensions including all required
	 * extensions in order
	 *
	 * @param array $exts
	 * @throws Exception
	 */
	private function doIncludeExtensions( $exts ) {
		global $IP;

		$registry = ExtensionRegistry::getInstance();
		foreach ( $exts as $e ) {
			$hasExtensionJson = false;
			$path = '';
			if ( file_exists( "$IP/extensions/$e/extension.json" ) ) {
				$path = "$IP/extensions/$e/extension.json";
				$hasExtensionJson = true;
			} else {
				if( file_exists( "$IP/extensions/$e/$e.setup.php" ) ) {
					$path = "$IP/extensions/$e/$e.setup.php";
				} elseif( file_exists( "$IP/extensions/$e/$e.php" ) ) {
					$path = "$IP/extensions/$e/$e.php";
				} else {
					// :(
					continue;
				}
			}

			if ( $this->isExtensionLoaded( $path ) ) {
				continue;
			}

			if ( $hasExtensionJson ) {
				$this->loadDependencies( $path );
				$registry->queue( $path );
			} else {
				require_once $path;
			}
		}
	}

	/**
	 * Checks if the extension has already been included
	 *
	 * @param string $path
	 * @return bool
	 */
	private function isExtensionLoaded( $path ) {
		if ( array_search( $path, $this->extensionsLoaded ) === false ) {
			$this->extensionsLoaded[] = $path;
			return false;
		}
		return true;
	}

	/**
	 * Get an array of install steps. Should always be in the format of
	 * array(
	 *   'name'     => 'someuniquename',
	 *   'callback' => array( $obj, 'method' ),
	 * )
	 * There must be a config-install-$name message defined per step, which will
	 * be shown on install.
	 *
	 * @param $installer DatabaseInstaller so we can make callbacks
	 * @return array
	 */
	protected function getInstallSteps( DatabaseInstaller $installer ) {
		$coreInstallSteps = [
			[ 'name' => 'database', 'callback' => [ $installer, 'setupDatabase' ] ],
			[ 'name' => 'tables', 'callback' => [ $installer, 'createTables' ] ],
			[ 'name' => 'interwiki', 'callback' => [ $installer, 'populateInterwikiTable' ] ],
			[ 'name' => 'stats', 'callback' => [ $this, 'populateSiteStats' ] ],
			[ 'name' => 'keys', 'callback' => [ $this, 'generateKeys' ] ],
			[ 'name' => 'updates', 'callback' => [ $installer, 'insertUpdateKeys' ] ],
			[ 'name' => 'sysop', 'callback' => [ $this, 'createSysop' ] ],
			[ 'name' => 'mainpage', 'callback' => [ $this, 'createMainpage' ] ],
		];

		// Build the array of install steps starting from the core install list,
		// then adding any callbacks that wanted to attach after a given step
		foreach ( $coreInstallSteps as $step ) {
			$this->installSteps[] = $step;
			if ( isset( $this->extraInstallSteps[$step['name']] ) ) {
				$this->installSteps = array_merge(
					$this->installSteps,
					$this->extraInstallSteps[$step['name']]
				);
			}
		}

		// Prepend any steps that want to be at the beginning
		if ( isset( $this->extraInstallSteps['BEGINNING'] ) ) {
			$this->installSteps = array_merge(
				$this->extraInstallSteps['BEGINNING'],
				$this->installSteps
			);
		}

		// Extensions should always go first, chance to tie into hooks and such
		array_unshift( $this->installSteps,
			[ 'name' => 'extensions', 'callback' => [ $this, 'includeExtensions' ] ]
		);
		$this->installSteps[] = [
			'name' => 'extension-tables',
			'callback' => [ $installer, 'createExtensionTables' ]
		];

		return $this->installSteps;
	}

	public function getCheckBox( $params ) {
		$bUserCanEdit = $bRender = true;
		$this->setDefaultOption( $params, $bUserCanEdit, $bRender );
		if( !isset( $params['attribs'] ) ) {
			$params['attribs'] = [];
		}
		$sHidden = "";

		if ( !isset( $params['help'] ) ) {
			$params['help'] = "";
		}
		if( $bRender === false ) {
			return "";
		}
		return parent::getCheckBox( $params ).$sHidden;
	}

	public function getRadioElements($params) {
		$bUserCanEdit = $bRender = true;
		$this->setDefaultOption( $params, $bUserCanEdit, $bRender );
		if( !isset( $params['attribs'] ) ) {
			$params['attribs'] = [];
		}
		if( $bUserCanEdit === false ) {
			$params['attribs']['disabled'] = "disabled";
		}
		if( $bRender === false ) {
			return "";
		}
		return parent::getRadioElements( $params );
	}

	public function setDefaultOption( $aParams, &$bUserCanEdit = true, &$bRender = true ) {
		$aOptions = $this->getOptions();
		if( empty($aOptions) ) {
			return;
		}
		if( empty($aOptions[$aParams['var']]) ) {
			return;
		}
		if( isset($aOptions[$aParams['var']]->render) ) {
			$bRender = $aOptions[$aParams['var']]->render === true
				? true
				: false
			;
		}
		if( isset($aOptions[$aParams['var']]->userCanEdit) ) {
			$bUserCanEdit = $aOptions[$aParams['var']]->userCanEdit === true
				? true
				: false
			;
		}

		//already set by user!
		if( !is_null($this->getVar( $aParams['var'] )) ) {
			return;
		}

		$this->setVar(
			$aParams['var'],
			$aOptions[$aParams['var']]->value
		);
		return true;
	}

	public function getPageByName( $pageName ) {
		if( $pageName === 'Options' ) {
			return new BsWebInstallerOptions( $this );
		}
		if ( $pageName === 'DBConnect' ) {
			return new BsWebInstallerDBConnect( $this );
		}
		if ( $pageName === 'DBSettings' ) {
			return new BsWebInstallerDBSettings ($this);
		}
		return parent::getPageByName( $pageName );
	}
}
