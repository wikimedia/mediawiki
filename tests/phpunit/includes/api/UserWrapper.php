<?php

class UserWrapper {
	public $userName;
	public $password;
	public $user;

	public function __construct( $userName, $password, $group = '' ) {
		$this->userName = $userName;
		$this->password = $password;

		$this->user = User::newFromName( $this->userName );
		if ( !$this->user->getID() ) {
			$this->user = User::createNew( $this->userName, array(
				"email" => "test@example.com",
				"real_name" => "Test User" ) );
		}
		$this->user->setPassword( $this->password );

		if ( $group !== '' ) {
			$this->user->addGroup( $group );
		}
		$this->user->saveSettings();
	}
}
