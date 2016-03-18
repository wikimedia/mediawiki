<?php

class UserWrapper {
	public $userName;
	public $password;
	public $user;

	public function __construct( $userName, $password, $group = '' ) {
		$this->userName = $userName;
		$this->password = $password;

		$this->user = User::newFromName( $this->userName );
		if ( !$this->user->getId() ) {
			$this->user = User::createNew( $this->userName, [
				"email" => "test@example.com",
				"real_name" => "Test User" ] );
		}
		TestUser::setPasswordForUser( $this->user, $this->password );

		if ( $group !== '' ) {
			$this->user->addGroup( $group );
		}
		$this->user->saveSettings();
	}
}
