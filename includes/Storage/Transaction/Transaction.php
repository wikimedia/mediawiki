<?php
namespace MediaWiki\Storage\Transaction;


/**
 * A simple transaction interface.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
interface Transaction {

	public function begin();

	public function commit();

	public function abort();
}