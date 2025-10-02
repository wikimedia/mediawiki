<?php
/**
 * Copyright (C) 2018 Kunal Mehta <legoktm@debian.org>
 *
 * @license GPL-2.0-or-later
 */

/**
 * @since 1.31
 */
trait HamcrestPHPUnitIntegration {

	/**
	 * Wrapper around Hamcrest's assertThat, which marks the assertion
	 * for PHPUnit so the test is not marked as risky
	 * @param mixed ...$args
	 */
	public function assertThatHamcrest( ...$args ) {
		assertThat( ...$args );
		$this->addToAssertionCount( 1 );
	}
}
