<?php

/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Installer
 */

namespace MediaWiki\Installer;

use MediaWiki\Html\Html;
use MediaWiki\Status\Status;

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
		$types .= "</ul>";

		$settings = '';
		foreach ( $compiledDBs as $type ) {
			$installer = $this->parent->getDBInstaller( $type );
			$types .= "<div class=\"cdx-radio\"><div class=\"cdx-radio__wrapper\">";
			$id = "DBType_$type";
			$types .=
				Html::radio(
					'DBType',
					$type == $defaultType,
					[
						'id' => $id,
						'class' => [ 'cdx-radio__input', 'dbRadio' ],
						'rel' => "DB_wrapper_$type",
						'value' => $type,
					]
				) .
				"\u{00A0}<span class=\"cdx-radio__icon\"></span>" .
				Html::label( $installer->getReadableName(), $id, [ 'class' => 'cdx-radio__label' ] );
			$types .= "</div></div>";
			// Messages: config-header-mysql, config-header-postgres, config-header-sqlite
			$settings .= Html::openElement(
					'div',
					[
						'id' => 'DB_wrapper_' . $type,
						'class' => 'dbWrapper'
					]
				) .
				Html::element( 'h3', [], wfMessage( 'config-header-' . $type )->text() ) .
				$installer->getConnectForm( $this->parent )->getHtml() .
				"</div>\n";

		}

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

		return $installer->getConnectForm( $this->parent )->submit();
	}

}
