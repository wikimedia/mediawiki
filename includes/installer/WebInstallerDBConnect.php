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
 * @ingroup Installer
 */

class WebInstallerDBConnect extends WebInstallerPage {

	/**
	 * @return string|null When string, "skip" or "continue"
	 */
	public function execute() {
		if ( $this->getVar( '_ExistingDBSettings' ) ) {
			return 'skip';
		}

		$r = $this->parent->request;
		if ( $r->wasPosted() ) {
			$status = $this->submit();

			if ( $status->isGood() ) {
				$this->setVar( '_UpgradeDone', false );

				return 'continue';
			} else {
				$this->parent->showStatusBox( $status );
			}
		}

		$this->startForm();

		$types = "<ul class=\"config-settings-block\">\n";
		$settings = '';
		$defaultType = $this->getVar( 'wgDBtype' );

		// Messages: config-dbsupport-mysql, config-dbsupport-postgres, config-dbsupport-sqlite
		$dbSupport = '';
		foreach ( Installer::getDBTypes() as $type ) {
			$dbSupport .= wfMessage( "config-dbsupport-$type" )->plain() . "\n";
		}
		$this->addHTML( $this->parent->getInfoBox(
			wfMessage( 'config-support-info', trim( $dbSupport ) )->plain() ) );

		// It's possible that the library for the default DB type is not compiled in.
		// In that case, instead select the first supported DB type in the list.
		$compiledDBs = $this->parent->getCompiledDBs();
		if ( !in_array( $defaultType, $compiledDBs ) ) {
			$defaultType = $compiledDBs[0];
		}

		foreach ( $compiledDBs as $type ) {
			$installer = $this->parent->getDBInstaller( $type );
			$types .=
				'<li>' .
				Xml::radioLabel(
					$installer->getReadableName(),
					'DBType',
					$type,
					"DBType_$type",
					$type == $defaultType,
					[ 'class' => 'dbRadio', 'rel' => "DB_wrapper_$type" ]
				) .
				"</li>\n";

			// Messages: config-header-mysql, config-header-postgres, config-header-sqlite
			$settings .= Html::openElement(
					'div',
					[
						'id' => 'DB_wrapper_' . $type,
						'class' => 'dbWrapper'
					]
				) .
				Html::element( 'h3', [], wfMessage( 'config-header-' . $type )->text() ) .
				$installer->getConnectForm() .
				"</div>\n";
		}

		$types .= "</ul><br style=\"clear: left\"/>\n";

		$this->addHTML( $this->parent->label( 'config-db-type', false, $types ) . $settings );
		$this->endForm();

		return null;
	}

	/**
	 * @return Status
	 */
	public function submit() {
		$r = $this->parent->request;
		$type = $r->getVal( 'DBType' );
		if ( !$type ) {
			return Status::newFatal( 'config-invalid-db-type' );
		}
		$this->setVar( 'wgDBtype', $type );
		$installer = $this->parent->getDBInstaller( $type );
		if ( !$installer ) {
			return Status::newFatal( 'config-invalid-db-type' );
		}

		return $installer->submitConnectForm();
	}

}
