<?php

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

		// Messages: config-dbsupport-mysql, config-dbsupport-postgres, config-dbsupport-oracle,
		// config-dbsupport-sqlite, config-dbsupport-mssql
		$dbSupport = '';
		foreach ( Installer::getDBTypes() as $type ) {
			$dbSupport .= wfMessage( "config-dbsupport-$type" )->plain() . "\n";
		}
		$this->addHTML( $this->parent->getInfoBox(
			wfMessage( 'config-support-info', trim( $dbSupport ) )->text() ) );

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
					array( 'class' => 'dbRadio', 'rel' => "DB_wrapper_$type" )
				) .
				"</li>\n";

			// Messages: config-header-mysql, config-header-postgres, config-header-oracle,
			// config-header-sqlite
			$settings .= Html::openElement(
					'div',
					array(
						'id' => 'DB_wrapper_' . $type,
						'class' => 'dbWrapper'
					)
				) .
				Html::element( 'h3', array(), wfMessage( 'config-header-' . $type )->text() ) .
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


