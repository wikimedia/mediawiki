<?php

class BsWebInstallerDBConnect extends WebInstallerDBConnect {

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

		// Messages: config-dbsupport-mysql
		$dbSupport = '';
		$dbSupport .= wfMessage( "config-dbsupport-mysql" )->plain() . "\n";

		$this->addHTML( $this->parent->getInfoBox(
				wfMessage( 'bs-installer-config-support-info', trim( $dbSupport ) )->text() ) );

		// It's possible that the library for the default DB type is not compiled in.
		// In that case, instead select the first supported DB type in the list.
		$compiledDBs = $this->parent->getCompiledDBs();
		if ( !in_array( $defaultType, $compiledDBs ) ) {
			$defaultType = $compiledDBs[0];
		}

		foreach ( $compiledDBs as $type ) {
			$installer = $this->parent->getDBInstaller( $type );
			$types .= '<li>' .
				Xml::radioLabel(
					$installer->getReadableName(), 'DBType', $type, "DBType_$type", $type == $defaultType, [ 'class' => 'dbRadio', 'rel' => "DB_wrapper_$type" ]
				) .
				"</li>\n";

			// Messages: config-header-mysql, config-header-postgres, config-header-oracle,
			// config-header-sqlite
			$settings .= Html::openElement(
					'div', [
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

}
