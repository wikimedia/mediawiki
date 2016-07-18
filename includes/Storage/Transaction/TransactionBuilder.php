<?php
namespace MediaWiki\Storage\Transaction;


/**
 * Interface for collecting actions to perform when finalizing a transaction.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
interface TransactionBuilder {

	/**
	 * @param callable $callback the action to perform on prepare.
	 *        The callback will be called in the "prepare" phase of a two-phase commit.
	 *        It will be invoked with this TransactionBuilder as the single parameter.
	 *        "Prepare" callbacks are free to throw any exception top indicate a failure.
	 *        Throwing an exception from the callback will abort the transaction.
	 *        Callbacks will be invoked in the reverse order they were registered.
	 * @param string $method a "method name" for logging, typically __METHOD__
	 */
	public function onPrepareDo( $callback, $method );

	/**
	 * @param callable $callback the action to perform on commit.
	 *        The callback will be called in the final "commit" phase of a two-phase commit.
	 *        It will be invoked with this TransactionBuilder as the single parameter.
	 *        "Commit" callbacks have to be atomic and should never fail.
	 *        Throwing an exception from the callback will attempt to abort the transaction,
	 *        but a complete cleanup cannot be guaranteed.
	 *        Callbacks will be invoked in the reverse order they were registered.
	 * @param string $method a "method name" for logging, typically __METHOD__
	 */
	public function onCommitDo( $callback, $method );

	/**
	 * @param callable $callback the action to perform on abort.
	 *        The callback will be called when the transaction is aborted.
	 *        It will be invoked with this TransactionBuilder as the single parameter.
	 *        "Abort" callbacks have to be atomic and should never fail.
	 *        Throwing an exception from the callback will not stop the process of aborting
	 *        the transaction, but a complete cleanup can no longer be guaranteed.
	 *        Callbacks will be invoked in the reverse order they were registered.
	 * @param string $method a "method name" for logging, typically __METHOD__
	 */
	public function onAbortDo( $callback, $method );

}