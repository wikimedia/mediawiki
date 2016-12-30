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

/**
 * Class SpecialConfigurations
 */
class SpecialConfigurations extends SpecialPage {
	function __construct() {
		parent::__construct( 'Configurations' );
	}

	public function execute( $subPage ) {
		$configRepo = \MediaWiki\MediaWikiServices::getInstance()->getConfigRepository();

		$rows = [];
		foreach ( $configRepo as $name => $config ) {
			$rows[$config['providedby']][] = [
				$name,
				$configRepo->getDescriptionOf( $name ),
				$configRepo->getValueOf( $name ),
				$config['value']
			];
		}

		foreach( $rows as $provider => $configs ) {
			$this->getOutput()->addHTML( '<h1>' . $provider . ' configurations</h1>' );
			$this->getOutput()->addHTML( Xml::buildTable( $configs, [
				'class' => 'wikitable',
			], [
				'Configuration',
				'Description',
				'Value',
				'default value'
			] ) );
		}
	}
}
