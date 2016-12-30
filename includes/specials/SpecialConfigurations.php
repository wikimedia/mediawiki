<?php
/**
 * Copyright 2016
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

use \MediaWiki\Config\ConfigRepository;

/**
 * Class SpecialConfigurations
 */
class SpecialConfigurations extends SpecialPage {
	function __construct() {
		parent::__construct( 'Configurations', 'viewconfigurations' );
	}

	public function execute( $subPage ) {
		$this->setHeaders();
		$this->outputHeader();
		$this->checkPermissions();
		$out = $this->getOutput();
		$out->addModules( 'jquery.makeCollapsible' );
		$includePrivate = $this->getUser()->isAllowed( 'viewprivateconfigurations' );

		/** @var ConfigRepository $configRepo */
		$configRepo = \MediaWiki\MediaWikiServices::getInstance()->getConfigRepository();

		if ( $configRepo->isEmpty( $includePrivate ) ) {
			$out->addWikiMsg( $includePrivate ? 'sp-configurations-no-public' : 'sp-configurations-no-configurations' );
			return;
		}

		$configs = clone $configRepo;
		if ( $includePrivate ) {
			$configs = $configRepo->getAll();
		}

		$rows = [];
		foreach ( $configs as $name => $config ) {
			$config['name']  = $name;
			$rows[$config['providedby']][] = $config;
		}

		foreach( $rows as $provider => $configs ) {
			$out->addHTML( $this->getConfigTable( $provider, $configs, $configRepo ) );
		}
	}

	/**
	 * Returns an appropriate string representation of the given configuration value. This can
	 * either be the default representations of this class or a separate one, which is provided
	 * by another extension through a hook.
	 *
	 * @param $value
	 * @return string
	 */
	private function formatConfigValue( $value ) {
		switch ( gettype( $value ) ) {
			case 'string':
				if ( $value === '' ) {
					return $this->msg( 'sp-configurations-emptyvalue' )->text();
				}
				return $value;
				break;
			case "NULL":
				return Html::element( 'pre', [], 'NULL' );
				break;
			case "boolean":
				return Html::element( 'pre', [], $value ? 'True' : 'False' );
				break;
			case 'array':
				return $this->makeCollapsed(
						Html::openElement( 'pre' ) .
						print_r( $value, true ) .
						Html::closeElement( 'pre' )
					);
				break;
			default:
				// fallback to the php representation of types (e.g. strings, ...)
				return $value;
		}
	}

	private function makeCollapsed( $html ) {
		return Html::openElement( 'div', [ 'class' => 'mw-collapsible mw-collapsed' ] ) .
			$html .
			Html::closeElement( 'div' );
	}

	/**
	 * Builds a table with the configuration options passed as configs.
	 *
	 * @param string $provider
	 * @param array $configs
	 * @param ConfigRepository $configRepo
	 * @return string The html of a table with a heading
	 */
	private function getConfigTable( $provider, array $configs, ConfigRepository $configRepo ) {
		$html =
			Html::element(
				'h3',
				[ 'id' => $provider ],
				$this->msg( 'sp-configurations-for', $provider )->text()
			) .
			Html::openElement( 'table', [ 'class' => 'wikitable' ] ) .
			Html::openElement( 'thead' ) .
			Html::element( 'th', [], $this->msg( 'sp-configurations-configuration' )->text() ) .
			Html::element( 'th', [], $this->msg( 'sp-configurations-description' )->text() ) .
			Html::element( 'th', [], $this->msg( 'sp-configurations-value' )->text() ) .
			Html::element( 'th', [], $this->msg( 'sp-configurations-defaultvalue' )->text() ) .
			Html::closeElement( 'thead' );

		foreach ( $configs as $config ) {
			$configDefaultValue = $this->formatConfigValue( $config['value'] );
			$configValue = $this->formatConfigValue( $configRepo->getValueOf( $config['name'] ) );
			$html .=
				Html::openElement( 'tr' ) .
					Html::openElement( 'td' ) .
						$config['name'] .
					Html::closeElement( 'td' ) .
					Html::openElement( 'td' ) .
						$configRepo->getDescriptionOf( $config['name'] ) .
					Html::closeElement( 'td' ) .
					Html::openElement( 'td' ) .
						$configValue .
					Html::closeElement( 'td' ) .
					Html::openElement( 'td' ) .
						$configDefaultValue .
					Html::closeElement( 'td' ) .
				Html::closeElement( 'tr' );
		}

		$html .= Html::closeElement( 'table' );

		return $html;
	}
}
