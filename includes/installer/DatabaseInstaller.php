<?php
/**
 * DBMS-specific installation helper.
 *
 * @file
 * @ingroup Deployment
 */

/**
 * Base class for DBMS-specific installation helper classes.
 *
 * @ingroup Deployment
 * @since 1.17
 */
abstract class DatabaseInstaller {
	
	/**
	 * The Installer object.
	 *
	 * TODO: naming this parent is confusing, 'installer' would be clearer.
	 *
	 * @var Installer
	 */
	public $parent;

	/**
	 * The database connection.
	 *
	 * @var DatabaseBase
	 */
	public $db;

	/**
	 * Internal variables for installation.
	 *
	 * @var array
	 */
	protected $internalDefaults = array();

	/**
	 * Array of MW configuration globals this class uses.
	 *
	 * @var array
	 */
	protected $globalNames = array();

	/**
	 * Return the internal name, e.g. 'mysql', or 'sqlite'.
	 */
	public abstract function getName();

	/**
	 * @return true if the client library is compiled in.
	 */
	public abstract function isCompiled();

	/**
	 * Get HTML for a web form that configures this database. Configuration
	 * at this time should be the minimum needed to connect and test
	 * whether install or upgrade is required.
	 *
	 * If this is called, $this->parent can be assumed to be a WebInstaller.
	 */
	public abstract function getConnectForm();

	/**
	 * Set variables based on the request array, assuming it was submitted
	 * via the form returned by getConnectForm(). Validate the connection
	 * settings by attempting to connect with them.
	 *
	 * If this is called, $this->parent can be assumed to be a WebInstaller.
	 *
	 * @return Status
	 */
	public abstract function submitConnectForm();

	/**
	 * Get HTML for a web form that retrieves settings used for installation.
	 * $this->parent can be assumed to be a WebInstaller.
	 * If the DB type has no settings beyond those already configured with
	 * getConnectForm(), this should return false.
	 */
	public function getSettingsForm() {
		return false;
	}

	/**
	 * Set variables based on the request array, assuming it was submitted via
	 * the form return by getSettingsForm().
	 *
	 * @return Status
	 */
	public function submitSettingsForm() {
		return Status::newGood();
	}

	/**
	 * Connect to the database using the administrative user/password currently
	 * defined in the session. On success, return the connection, on failure,
	 *
	 * This may be called multiple times, so the result should be cached.
	 *
	 * @return Status
	 */
	public abstract function getConnection();
	
	/**
	 * Create the database and return a Status object indicating success or
	 * failure.
	 *
	 * @return Status
	 */
	public abstract function setupDatabase();

	/**
	 * Create database tables from scratch.
	 *
	 * @return Status
	 */
	public function createTables() {
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}
		$this->db->selectDB( $this->getVar( 'wgDBname' ) );

		if( $this->db->tableExists( 'user' ) ) {
			$status->warning( 'config-install-tables-exist' );
			return $status;
		}

		$error = $this->db->sourceFile( $this->db->getSchema() );
		if( $error !== true ) {
			$this->db->reportQueryError( $error, 0, $sql, __METHOD__ );
			$status->fatal( 'config-install-tables-failed', $error );
		}
		return $status;
	}

	/**
	 * Get the DBMS-specific options for LocalSettings.php generation.
	 *
	 * @return String
	 */
	public abstract function getLocalSettings();
	
	/**
	 * Perform database upgrades
	 *
	 * @return Boolean
	 */
	public function doUpgrade() {
		# Maintenance scripts like wfGetDB()
		LBFactory::enableBackend();

		$ret = true;
		ob_start( array( $this, 'outputHandler' ) );
		try {
			$up = DatabaseUpdater::newForDB( $this->db );
			$up->doUpdates();
		} catch ( MWException $e ) {
			echo "\nAn error occured:\n";
			echo $e->getText();
			$ret = false;
		}
		ob_end_flush();
		return $ret;
	}
	
	/**
	 * Allow DB installers a chance to make last-minute changes before installation
	 * occurs. This happens before setupDatabase() or createTables() is called, but
	 * long after the constructor. Helpful for things like modifying setup steps :)
	 */
	public function preInstall() {
	
	}

	/**
	 * Allow DB installers a chance to make checks before upgrade.
	 */
	public function preUpgrade() {
	
	}

	/**
	 * Get an array of MW configuration globals that will be configured by this class.
	 */
	public function getGlobalNames() {
		return $this->globalNames;
	}

	/**
	 * Return any table options to be applied to all tables that don't
	 * override them.
	 *
	 * @return Array
	 */
	public function getTableOptions() {
		return array();
	}

	/**
	 * Construct and initialise parent.
	 * This is typically only called from Installer::getDBInstaller()
	 */
	public function __construct( $parent ) {
		$this->parent = $parent;
	}

	/**
	 * Convenience function.
	 * Check if a named extension is present.
	 *
	 * @see wfDl
	 */
	protected static function checkExtension( $name ) {
		wfSuppressWarnings();
		$compiled = wfDl( $name );
		wfRestoreWarnings();
		return $compiled;
	}

	/**
	 * Get the internationalised name for this DBMS.
	 */
	public function getReadableName() {
		return wfMsg( 'config-type-' . $this->getName() );
	}
	
	/**
	 * Get a name=>value map of MW configuration globals that overrides.
	 * DefaultSettings.php
	 */
	public function getGlobalDefaults() {
		return array();
	}

	/**
	 * Get a name=>value map of internal variables used during installation.
	 */
	public function getInternalDefaults() {
		return $this->internalDefaults;
	}

	/**
	 * Get a variable, taking local defaults into account.
	 */
	public function getVar( $var, $default = null ) {
		$defaults = $this->getGlobalDefaults();
		$internal = $this->getInternalDefaults();
		if ( isset( $defaults[$var] ) ) {
			$default = $defaults[$var];
		} elseif ( isset( $internal[$var] ) ) {
			$default = $internal[$var];
		}
		return $this->parent->getVar( $var, $default );
	}

	/**
	 * Convenience alias for $this->parent->setVar()
	 */
	public function setVar( $name, $value ) {
		$this->parent->setVar( $name, $value );
	}

	/**
	 * Get a labelled text box to configure a local variable.
	 */
	public function getTextBox( $var, $label, $attribs = array(), $helpData = "" ) {
		$name = $this->getName() . '_' . $var;
		$value = $this->getVar( $var );
		if ( !isset( $attribs ) ) {
		    $attribs = array();
		}
		return $this->parent->getTextBox( array(
			'var' => $var,
			'label' => $label,
			'attribs' => $attribs,
			'controlName' => $name,
			'value' => $value,
		    'help' => $helpData
		) );
	}

	/**
	 * Get a labelled password box to configure a local variable.
	 * Implements password hiding.
	 */
	public function getPasswordBox( $var, $label, $attribs = array(), $helpData = "" ) {
		$name = $this->getName() . '_' . $var;
		$value = $this->getVar( $var );
		if ( !isset( $attribs ) ) {
		    $attribs = array();
		}
		return $this->parent->getPasswordBox( array(
			'var' => $var,
			'label' => $label,
			'attribs' => $attribs,
			'controlName' => $name,
			'value' => $value,
		    'help' => $helpData
		) );
	}

	/**
	 * Get a labelled checkbox to configure a local boolean variable.
	 */
	public function getCheckBox( $var, $label, $attribs = array(), $helpData = "" ) {
		$name = $this->getName() . '_' . $var;
		$value = $this->getVar( $var );
		return $this->parent->getCheckBox( array(
			'var' => $var,
			'label' => $label,
			'attribs' => $attribs,
			'controlName' => $name,
			'value' => $value,
		    'help' => $helpData
		));
	}

	/**
	 * Get a set of labelled radio buttons.
	 *
	 * @param $params Array:
	 *    Parameters are:
	 *      var:            The variable to be configured (required)
	 *      label:          The message name for the label (required)
	 *      itemLabelPrefix: The message name prefix for the item labels (required)
	 *      values:         List of allowed values (required)
	 *      itemAttribs     Array of attribute arrays, outer key is the value name (optional)
	 *
	 */
	public function getRadioSet( $params ) {
		$params['controlName'] = $this->getName() . '_' . $params['var'];
		$params['value'] = $this->getVar( $params['var'] );
		return $this->parent->getRadioSet( $params );
	}

	/**
	 * Convenience function to set variables based on form data.
	 * Assumes that variables containing "password" in the name are (potentially
	 * fake) passwords.
	 * @param $varNames Array
	 */
	public function setVarsFromRequest( $varNames ) {
		return $this->parent->setVarsFromRequest( $varNames, $this->getName() . '_' );
	}

	/**
	 * Determine whether an existing installation of MediaWiki is present in
	 * the configured administrative connection. Returns true if there is
	 * such a wiki, false if the database doesn't exist.
	 *
	 * Traditionally, this is done by testing for the existence of either
	 * the revision table or the cur table.
	 *
	 * @return Boolean
	 */
	public function needsUpgrade() {
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return false;
		}

		if ( !$this->db->selectDB( $this->getVar( 'wgDBname' ) ) ) {
			return false;
		}
		return $this->db->tableExists( 'cur' ) || $this->db->tableExists( 'revision' );
	}

	/**
	 * Get a standard install-user fieldset.
	 */
	public function getInstallUserBox() {
		return
			Html::openElement( 'fieldset' ) .
			Html::element( 'legend', array(), wfMsg( 'config-db-install-account' ) ) .
			$this->getTextBox( '_InstallUser', 'config-db-username', array(), $this->parent->getHelpBox( 'config-db-install-username' ) ) .
			$this->getPasswordBox( '_InstallPassword', 'config-db-password', array(), $this->parent->getHelpBox( 'config-db-install-password' ) ) .
			Html::closeElement( 'fieldset' );
	}

	/**
	 * Submit a standard install user fieldset.
	 */
	public function submitInstallUserBox() {
		$this->setVarsFromRequest( array( '_InstallUser', '_InstallPassword' ) );
		return Status::newGood();
	}

	/**
	 * Get a standard web-user fieldset
	 * @param $noCreateMsg String: Message to display instead of the creation checkbox.
	 *   Set this to false to show a creation checkbox.
	 */
	public function getWebUserBox( $noCreateMsg = false ) {
		$name = $this->getName();
		$s = Html::openElement( 'fieldset' ) .
			Html::element( 'legend', array(), wfMsg( 'config-db-web-account' ) ) .
			$this->getCheckBox(
				'_SameAccount', 'config-db-web-account-same',
				array( 'class' => 'hideShowRadio', 'rel' => 'dbOtherAccount' )
			) .
			Html::openElement( 'div', array( 'id' => 'dbOtherAccount', 'style' => 'display: none;' ) ) .
			$this->getTextBox( 'wgDBuser', 'config-db-username' ) .
			$this->getPasswordBox( 'wgDBpassword', 'config-db-password' ) .
			$this->parent->getHelpBox( 'config-db-web-help' );
		if ( $noCreateMsg ) {
			$s .= $this->parent->getWarningBox( wfMsgNoTrans( $noCreateMsg ) );
		} else {
			$s .= $this->getCheckBox( '_CreateDBAccount', 'config-db-web-create' );
		}
		$s .= Html::closeElement( 'div' ) . Html::closeElement( 'fieldset' );
		return $s;
	}

	/**
	 * Submit the form from getWebUserBox().
	 *
	 * @return Status
	 */
	public function submitWebUserBox() {
		$this->setVarsFromRequest(
			array( 'wgDBuser', 'wgDBpassword', '_SameAccount', '_CreateDBAccount' )
		);
		
		if ( $this->getVar( '_SameAccount' ) ) {
			$this->setVar( 'wgDBuser', $this->getVar( '_InstallUser' ) );
			$this->setVar( 'wgDBpassword', $this->getVar( '_InstallPassword' ) );
		}
		
		return Status::newGood();
	}

	/**
	 * Common function for databases that don't understand the MySQLish syntax of interwiki.sql.
	 */
	public function populateInterwikiTable() {
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}
		$this->db->selectDB( $this->getVar( 'wgDBname' ) );

		if( $this->db->selectRow( 'interwiki', '*', array(), __METHOD__ ) ) {
			$status->warning( 'config-install-interwiki-exists' );
			return $status;
		}
		global $IP;
		$rows = file( "$IP/maintenance/interwiki.list",
			FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
		$interwikis = array();
		if ( !$rows ) {
			return Status::newFatal( 'config-install-interwiki-sql' );
		}
		foreach( $rows as $row ) {
			$row = preg_replace( '/^\s*([^#]*?)\s*(#.*)?$/', '\\1', $row ); // strip comments - whee
			if ( $row == "" ) continue;
			$row .= "||";
			$interwikis[] = array_combine(
				array( 'iw_prefix', 'iw_url', 'iw_local', 'iw_api', 'iw_wikiid' ),
				explode( '|', $row )
			);
		}
		$this->db->insert( 'interwiki', $interwikis, __METHOD__ );
		return Status::newGood();
	}

	public function outputHandler( $string ) {
		return htmlspecialchars( $string );
	}
}
