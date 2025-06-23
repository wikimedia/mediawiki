<?php

// phpcs:disable

/* @phan-file-suppress PhanTypeSuspiciousEcho, PhanTypeConversionFromArray, PhanPluginUseReturnValueInternalKnown, PhanNoopNew */
/* @phan-file-suppress PhanTypeMismatchArgument Ignore list/array mismatch for taint checks */
/* @phan-file-suppress PhanParamTooFewInPHPDoc */

/*
 * This test ensures that taint-check knows about unsafe methods in MediaWiki. Knowledge about those methods
 * can come either from annotations on the methods themselves, or from the plugin. It does not really matter,
 * as long as taint-check knows about them.
 *
 * If phan reports new security issues or unused suppressions in this file, DO NOT just fix the errors, and instead
 * make sure that your patch is not causing some of the taintedness data to be lost.
 *
 * If you are introducing an alias for any of these classes, then duplicate the relevant test so that it covers
 * both the old and the new class name.
 */

use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Html\Html;
use MediaWiki\Linker\Linker;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Request\WebRequest;
use MediaWiki\Shell\Result;
use MediaWiki\Shell\Shell;
use MediaWiki\Status\Status;
use MediaWiki\Status\StatusFormatter;
use Shellbox\Command\UnboxedResult;
use Shellbox\Shellbox;
use Wikimedia\Rdbms\Database\DbQuoter;
use Wikimedia\Rdbms\DeleteQueryBuilder;
use Wikimedia\HtmlArmor\HtmlArmor;
use Wikimedia\Rdbms\Expression;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\InsertQueryBuilder;
use Wikimedia\Rdbms\JoinGroupBase;
use Wikimedia\Rdbms\Platform\ISQLPlatform;
use Wikimedia\Rdbms\RawSQLExpression;
use Wikimedia\Rdbms\RawSQLValue;
use Wikimedia\Rdbms\ReplaceQueryBuilder;
use Wikimedia\Rdbms\SelectQueryBuilder;
use Wikimedia\Rdbms\UnionQueryBuilder;
use Wikimedia\Rdbms\UpdateQueryBuilder;

die( 'This file should never be loaded' );

class TaintCheckAnnotationsTest {
	function testDatabase( \Wikimedia\Rdbms\Database $db ) {
		$db->query( $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->query( 'safe' ); // @phan-suppress-current-line SecurityCheck-XSS

		$db->select( $_GET['a'], '' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->select( '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->select( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->select( 'safe', 'safe' ); // @phan-suppress-current-line SecurityCheck-XSS

		$db->selectField( $_GET['a'], '' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectField( '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectField( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->selectField( 'safe', 'safe' ); // @phan-suppress-current-line SecurityCheck-XSS

		$db->selectFieldValues( $_GET['a'], '' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectFieldValues( '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectFieldValues( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->selectFieldValues( 'safe', 'safe' ); // @phan-suppress-current-line SecurityCheck-XSS

		$db->selectSQLText( $_GET['a'], '' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectSQLText( '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectSQLText( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->selectSQLText( 'safe', 'safe' ); // @phan-suppress-current-line SecurityCheck-XSS
		$db->query( $db->selectSQLText( 'safe', 'safe', [ 'foo' => $_GET['a'] ] ) ); // Safe

		$db->selectRowCount( $_GET['a'], '' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectRowCount( '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectRowCount( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->selectRowCount( 'safe', 'safe' ); // Safe

		$db->selectRow( $_GET['a'], '', [] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectRow( '', $_GET['a'], [] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectRow( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->selectRow( 'safe', 'safe', [] ); // @phan-suppress-current-line SecurityCheck-XSS

		$db->delete( $_GET['a'], '' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->delete( '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->delete( '', '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->select( 'safe', 'safe' ); // @phan-suppress-current-line SecurityCheck-XSS

		$db->insert( $_GET['a'], [] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->insert( '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->insert( '', [], $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->insert( '', [], '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->insert( 'safe', [] ); // Safe

		$db->update( $_GET['a'], [], [] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->update( '', [ $_GET['a'] ], [] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->update( '', [], [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->update( '', [], [], $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->update( 'safe', [], [] ); // Safe

		$identQuoted = $db->addIdentifierQuotes( $_GET['a'] );
		echo $identQuoted;// @phan-suppress-current-line SecurityCheck-XSS
		$db->query( $identQuoted );// Safe

		$quoted = $db->addQuotes( $_GET['a'] );
		echo $quoted;// @phan-suppress-current-line SecurityCheck-XSS
		$db->query( $quoted );// Safe

		echo $db->buildLike( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS
		$db->query( $db->buildLike( $_GET['a'] ) );// Safe
		echo $db->buildLike( '', $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS
		$db->query( $db->buildLike( '', $_GET['a'] ) );// Safe
		echo $db->buildLike( '', '', '', '', '', $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS
		$db->query( $db->buildLike( '', '', '', '', '', $_GET['a'] ) );// Safe

		echo $db->makeList( [ $_GET['a'] ] );// @phan-suppress-current-line SecurityCheck-XSS
		$db->query( $db->makeList( $_GET['a'] ) );// Safe
		echo $db->makeList( [] );// Safe
	}

	function testIDatabase( IDatabase $db ) {
		$db->query( $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->query( 'safe' ); // @phan-suppress-current-line SecurityCheck-XSS

		$db->select( $_GET['a'], '' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->select( '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->select( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->select( 'safe', 'safe' ); // @phan-suppress-current-line SecurityCheck-XSS

		$db->selectField( $_GET['a'], '' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectField( '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectField( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->selectField( 'safe', 'safe' ); // @phan-suppress-current-line SecurityCheck-XSS

		$db->selectFieldValues( $_GET['a'], '' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectFieldValues( '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectFieldValues( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->selectFieldValues( 'safe', 'safe' ); // @phan-suppress-current-line SecurityCheck-XSS

		$db->selectSQLText( $_GET['a'], '' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectSQLText( '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectSQLText( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->selectSQLText( 'safe', 'safe' ); // @phan-suppress-current-line SecurityCheck-XSS
		$db->query( $db->selectSQLText( 'safe', 'safe', [ 'foo' => $_GET['a'] ] ) ); // Safe

		$db->selectRowCount( $_GET['a'], '' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectRowCount( '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectRowCount( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->selectRowCount( 'safe', 'safe' ); // Safe

		$db->selectRow( $_GET['a'], '', [] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectRow( '', $_GET['a'], [] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectRow( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->selectRow( 'safe', 'safe', [] ); // @phan-suppress-current-line SecurityCheck-XSS

		$db->delete( $_GET['a'], '' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->delete( '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->delete( '', '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->select( 'safe', 'safe' ); // @phan-suppress-current-line SecurityCheck-XSS

		$db->insert( $_GET['a'], [] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->insert( '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->insert( '', [], $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->insert( '', [], '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->insert( 'safe', [] ); // Safe

		$db->update( $_GET['a'], [], [] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->update( '', [ $_GET['a'] ], [] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->update( '', [], [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->update( '', [], [], $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->update( 'safe', [], [] ); // Safe

		$identQuoted = $db->addIdentifierQuotes( $_GET['a'] );
		echo $identQuoted;// @phan-suppress-current-line SecurityCheck-XSS
		$db->query( $identQuoted );// Safe

		$quoted = $db->addQuotes( $_GET['a'] );
		echo $quoted;// @phan-suppress-current-line SecurityCheck-XSS
		$db->query( $quoted );// Safe

		echo $db->buildLike( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS
		$db->query( $db->buildLike( $_GET['a'] ) );// Safe
		echo $db->buildLike( '', $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS
		$db->query( $db->buildLike( '', $_GET['a'] ) );// Safe
		echo $db->buildLike( '', '', '', '', '', $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS
		$db->query( $db->buildLike( '', '', '', '', '', $_GET['a'] ) );// Safe

		echo $db->makeList( [ $_GET['a'] ] );// @phan-suppress-current-line SecurityCheck-XSS
		$db->query( $db->makeList( $_GET['a'] ) );// Safe
		echo $db->makeList( [] );// Safe
	}

	function testIReadableDatabase( \Wikimedia\Rdbms\IReadableDatabase $dbr ) {
		$dbr->select( $_GET['a'], '' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$dbr->select( '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$dbr->select( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $dbr->select( 'safe', 'safe' ); // @phan-suppress-current-line SecurityCheck-XSS

		$dbr->selectField( $_GET['a'], '' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$dbr->selectField( '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$dbr->selectField( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $dbr->selectField( 'safe', 'safe' ); // @phan-suppress-current-line SecurityCheck-XSS

		$dbr->selectFieldValues( $_GET['a'], '' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$dbr->selectFieldValues( '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$dbr->selectFieldValues( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $dbr->selectFieldValues( 'safe', 'safe' ); // @phan-suppress-current-line SecurityCheck-XSS

		$dbr->selectRowCount( $_GET['a'], '' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$dbr->selectRowCount( '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$dbr->selectRowCount( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $dbr->selectRowCount( 'safe', 'safe' ); // Safe

		$dbr->selectRow( $_GET['a'], '', [] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$dbr->selectRow( '', $_GET['a'], [] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$dbr->selectRow( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $dbr->selectRow( 'safe', 'safe', [] ); // @phan-suppress-current-line SecurityCheck-XSS
	}

	function testIMaintainableDatabase( \Wikimedia\Rdbms\IMaintainableDatabase $db ) {
		$db->query( $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->query( 'safe' ); // @phan-suppress-current-line SecurityCheck-XSS

		$db->select( $_GET['a'], '' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->select( '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->select( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->select( 'safe', 'safe' ); // @phan-suppress-current-line SecurityCheck-XSS

		$db->selectField( $_GET['a'], '' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectField( '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectField( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->selectField( 'safe', 'safe' ); // @phan-suppress-current-line SecurityCheck-XSS

		$db->selectFieldValues( $_GET['a'], '' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectFieldValues( '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectFieldValues( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->selectFieldValues( 'safe', 'safe' ); // @phan-suppress-current-line SecurityCheck-XSS

		$db->selectSQLText( $_GET['a'], '' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectSQLText( '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectSQLText( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->selectSQLText( 'safe', 'safe' ); // @phan-suppress-current-line SecurityCheck-XSS
		$db->query( $db->selectSQLText( 'safe', 'safe', [ 'foo' => $_GET['a'] ] ) ); // Safe

		$db->selectRowCount( $_GET['a'], '' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectRowCount( '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectRowCount( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->selectRowCount( 'safe', 'safe' ); // Safe

		$db->selectRow( $_GET['a'], '', [] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectRow( '', $_GET['a'], [] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectRow( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->selectRow( 'safe', 'safe', [] ); // @phan-suppress-current-line SecurityCheck-XSS

		$db->delete( $_GET['a'], '' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->delete( '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->delete( '', '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->select( 'safe', 'safe' ); // @phan-suppress-current-line SecurityCheck-XSS

		$db->insert( $_GET['a'], [] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->insert( '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->insert( '', [], $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->insert( '', [], '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->insert( 'safe', [] ); // Safe

		$db->update( $_GET['a'], [], [] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->update( '', [ $_GET['a'] ], [] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->update( '', [], [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->update( '', [], [], $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->update( 'safe', [], [] ); // Safe

		$identQuoted = $db->addIdentifierQuotes( $_GET['a'] );
		echo $identQuoted;// @phan-suppress-current-line SecurityCheck-XSS
		$db->query( $identQuoted );// Safe

		$quoted = $db->addQuotes( $_GET['a'] );
		echo $quoted;// @phan-suppress-current-line SecurityCheck-XSS
		$db->query( $quoted );// Safe
	}

	function testDBConnRef( \Wikimedia\Rdbms\DBConnRef $db ) {
		$db->query( $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->query( 'safe' ); // @phan-suppress-current-line SecurityCheck-XSS

		$db->select( $_GET['a'], '' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->select( '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->select( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->select( 'safe', 'safe' ); // @phan-suppress-current-line SecurityCheck-XSS

		$db->selectField( $_GET['a'], '' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectField( '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectField( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->selectField( 'safe', 'safe' ); // @phan-suppress-current-line SecurityCheck-XSS

		$db->selectFieldValues( $_GET['a'], '' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectFieldValues( '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectFieldValues( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->selectFieldValues( 'safe', 'safe' ); // @phan-suppress-current-line SecurityCheck-XSS

		$db->selectSQLText( $_GET['a'], '' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectSQLText( '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectSQLText( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->selectSQLText( 'safe', 'safe' ); // @phan-suppress-current-line SecurityCheck-XSS
		$db->query( $db->selectSQLText( 'safe', 'safe', [ 'foo' => $_GET['a'] ] ) ); // Safe

		$db->selectRowCount( $_GET['a'], '' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectRowCount( '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectRowCount( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->selectRowCount( 'safe', 'safe' ); // Safe

		$db->selectRow( $_GET['a'], '', [] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectRow( '', $_GET['a'], [] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->selectRow( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->selectRow( 'safe', 'safe', [] ); // @phan-suppress-current-line SecurityCheck-XSS

		$db->delete( $_GET['a'], '' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->delete( '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->delete( '', '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->select( 'safe', 'safe' ); // @phan-suppress-current-line SecurityCheck-XSS

		$db->insert( $_GET['a'], [] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->insert( '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->insert( '', [], $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->insert( '', [], '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->insert( 'safe', [] ); // Safe

		$db->update( $_GET['a'], [], [] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->update( '', [ $_GET['a'] ], [] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->update( '', [], [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->update( '', [], [], $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $db->update( 'safe', [], [] ); // Safe

		$identQuoted = $db->addIdentifierQuotes( $_GET['a'] );
		echo $identQuoted;// @phan-suppress-current-line SecurityCheck-XSS
		$db->query( $identQuoted );// Safe

		$quoted = $db->addQuotes( $_GET['a'] );
		echo $quoted;// @phan-suppress-current-line SecurityCheck-XSS
		$db->query( $quoted );// Safe
	}

	function testDatabaseMySQL( \Wikimedia\Rdbms\DatabaseMySQL $db ) {
		$quoted = $db->addQuotes( $_GET['a'] );
		echo $quoted;// @phan-suppress-current-line SecurityCheck-XSS
		$db->query( $quoted );// Safe

		$identQuoted = $db->addIdentifierQuotes( $_GET['a'] );
		echo $identQuoted;// @phan-suppress-current-line SecurityCheck-XSS
		$db->query( $identQuoted );// Safe
	}

	function testDatabasePostgres( \Wikimedia\Rdbms\DatabasePostgres $db ) {
		$quoted = $db->addQuotes( $_GET['a'] );
		echo $quoted;// @phan-suppress-current-line SecurityCheck-XSS
		$db->query( $quoted );// Safe
	}

	function testDatabaseSqlite( \Wikimedia\Rdbms\DatabaseSqlite $db ) {
		$quoted = $db->addQuotes( $_GET['a'] );
		echo $quoted;// @phan-suppress-current-line SecurityCheck-XSS
		$db->query( $quoted );// Safe
	}

	function testISQLPlatform( ISQLPlatform $platform, IDatabase $dbForQueryCalls ) {
		$platform->selectSQLText( $_GET['a'], '' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$platform->selectSQLText( '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$platform->selectSQLText( '', '', [ $_GET['a'] ] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		echo $platform->selectSQLText( 'safe', 'safe' ); // @phan-suppress-current-line SecurityCheck-XSS
		$dbForQueryCalls->query( $platform->selectSQLText( 'safe', 'safe' ) ); // Safe

		$identQuoted = $platform->addIdentifierQuotes( $_GET['a'] );
		echo $identQuoted;// @phan-suppress-current-line SecurityCheck-XSS
		$dbForQueryCalls->query( $identQuoted );// Safe

		echo $platform->buildLike( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS
		$dbForQueryCalls->query( $platform->buildLike( $_GET['a'] ) );// Safe
		echo $platform->buildLike( '', $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS
		$dbForQueryCalls->query( $platform->buildLike( '', $_GET['a'] ) );// Safe
		echo $platform->buildLike( '', '', '', '', '', $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS
		$dbForQueryCalls->query( $platform->buildLike( '', '', '', '', '', $_GET['a'] ) );// Safe

		echo $platform->makeList( [ $_GET['a'] ] );// @phan-suppress-current-line SecurityCheck-XSS
		$dbForQueryCalls->query( $platform->makeList( $_GET['a'] ) );// Safe
		echo $platform->makeList( [] );// Safe
	}

	function testDbQuoter( DbQuoter $quoter, IDatabase $dbForQueryCalls ) {
		$quoted = $quoter->addQuotes( $_GET['a'] );
		echo $quoted;// @phan-suppress-current-line SecurityCheck-XSS
		$dbForQueryCalls->query( $quoted );// Safe
	}

	function testSelectQueryBuilder( SelectQueryBuilder $sqb ) {
		$sqb->table( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$sqb->table( '', $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$sqb->tables( [ $_GET['a'] ] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$sqb->from( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$sqb->from( '', $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection

		$sqb->fields( [ $_GET['a'] ] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$sqb->select( [ $_GET['a'] ] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$sqb->field( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$sqb->field( '', $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection

		$sqb->where( [ $_GET['a'] ] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$sqb->where( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$sqb->where( [ 'foo' => $_GET['a'] ] );// Safe
		$sqb->andWhere( [ $_GET['a'] ] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$sqb->andWhere( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$sqb->andWhere( [ 'foo' => $_GET['a'] ] );// Safe
		$sqb->conds( [ $_GET['a'] ] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$sqb->conds( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$sqb->conds( [ 'foo' => $_GET['a'] ] );// Safe
		$sqb->groupBy( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$sqb->having( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$sqb->orderBy( $_GET['a'], $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$sqb->useIndex( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$sqb->ignoreIndex( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection

		$sqb->caller( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection

		echo $sqb->fetchResultSet();// @phan-suppress-current-line SecurityCheck-XSS
		echo $sqb->fetchField();// @phan-suppress-current-line SecurityCheck-XSS
		echo $sqb->fetchFieldValues();// @phan-suppress-current-line SecurityCheck-XSS
		echo $sqb->fetchRow();// @phan-suppress-current-line SecurityCheck-XSS
	}

	function testJoinGroupBase( JoinGroupBase $jgb ) {
		$jgb->join( $_GET['a'], '', '1=1' );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$jgb->join( '', $_GET['a'], '1=1' );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$jgb->join( '', '', $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$jgb->join( '', '', [ $_GET['a'] ] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$jgb->join( '', '', [ 'safe' => $_GET['a'] ] );// Safe

		$jgb->leftJoin( $_GET['a'], '', '1=1' );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$jgb->leftJoin( '', $_GET['a'], '1=1' );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$jgb->leftJoin( '', '', $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$jgb->leftJoin( '', '', [ $_GET['a'] ] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$jgb->leftJoin( '', '', [ 'safe' => $_GET['a'] ] );// Safe

		$jgb->straightJoin( $_GET['a'], '', '1=1' );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$jgb->straightJoin( '', $_GET['a'], '1=1' );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$jgb->straightJoin( '', '', $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$jgb->straightJoin( '', '', [ $_GET['a'] ] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$jgb->straightJoin( '', '', [ 'safe' => $_GET['a'] ] );// Safe
	}

	function testInsertQueryBuilder( InsertQueryBuilder $iqb ) {
		$iqb->table( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$iqb->insert( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$iqb->insertInto( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection

		$iqb->row( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$iqb->row( [ 'bar' => $_GET['a'] ] );// Safe
		$iqb->row( [ $_GET['a'] => 'foo' ] );// @phan-suppress-current-line SecurityCheck-SQLInjection

		$iqb->rows( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$iqb->rows( [ $_GET['a'] ] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$iqb->rows( [ $_GET['a'] => [] ] );// Safe
		$iqb->rows( [ $_GET['a'] => [ 'foo' => $_GET['a'] ] ] );// Safe
		$iqb->rows( [ $_GET['a'] => [ $_GET['a'] => 'foo' ] ] );// @phan-suppress-current-line SecurityCheck-SQLInjection

		$iqb->set( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$iqb->set( [ $_GET['a'] ] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$iqb->set( [ 'x' => $_GET['a'] ] );// Safe

		$iqb->andSet( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$iqb->andSet( [ $_GET['a'] ] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$iqb->andSet( [ 'x' => $_GET['a'] ] );// Safe

		$iqb->caller( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
	}

	function testReplaceQueryBuilder( ReplaceQueryBuilder $rqb ) {
		$rqb->table( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$rqb->replaceInto( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection

		// FIXME: After T361523 and a new release, the suppression must be enabled
		$rqb->row( $_GET['a'] );// phan-suppress-current-line SecurityCheck-SQLInjection
		$rqb->row( [ 'bar' => $_GET['a'] ] );// Safe
		// FIXME: After T361523 and a new release, the suppression must be enabled
		$rqb->row( [ $_GET['a'] => 'foo' ] );// phan-suppress-current-line SecurityCheck-SQLInjection

		// FIXME: After T361523 and a new release, the suppression must be enabled
		$rqb->rows( $_GET['a'] );// phan-suppress-current-line SecurityCheck-SQLInjection
		// FIXME: After T361523 and a new release, the suppression must be enabled
		$rqb->rows( [ $_GET['a'] ] );// phan-suppress-current-line SecurityCheck-SQLInjection
		$rqb->rows( [ $_GET['a'] => [] ] );// Safe
		$rqb->rows( [ $_GET['a'] => [ 'foo' => $_GET['a'] ] ] );// Safe
		// FIXME: After T361523 and a new release, the suppression must be enabled
		$rqb->rows( [ $_GET['a'] => [ $_GET['a'] => 'foo' ] ] );// phan-suppress-current-line SecurityCheck-SQLInjection

		$rqb->caller( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
	}

	function testUpdateQueryBuilder( UpdateQueryBuilder $uqb ) {
		$uqb->table( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$uqb->update( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection

		$uqb->where( [ $_GET['a'] ] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$uqb->where( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$uqb->where( [ 'foo' => $_GET['a'] ] );// Safe
		$uqb->andWhere( [ $_GET['a'] ] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$uqb->andWhere( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$uqb->andWhere( [ 'foo' => $_GET['a'] ] );// Safe
		$uqb->conds( [ $_GET['a'] ] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$uqb->conds( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$uqb->conds( [ 'foo' => $_GET['a'] ] );// Safe

		$uqb->set( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$uqb->set( [ $_GET['a'] ] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$uqb->set( [ 'x' => $_GET['a'] ] );// Safe
		$uqb->andSet( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$uqb->andSet( [ $_GET['a'] ] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$uqb->andSet( [ 'x' => $_GET['a'] ] );// Safe

		$uqb->caller( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
	}

	function testDeleteQueryBuilder( DeleteQueryBuilder $dqb ) {
		$dqb->table( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$dqb->deleteFrom( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$dqb->delete( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection

		$dqb->where( [ $_GET['a'] ] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$dqb->where( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$dqb->where( [ 'foo' => $_GET['a'] ] );// Safe
		$dqb->andWhere( [ $_GET['a'] ] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$dqb->andWhere( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$dqb->andWhere( [ 'foo' => $_GET['a'] ] );// Safe
		$dqb->conds( [ $_GET['a'] ] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$dqb->conds( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
		$dqb->conds( [ 'foo' => $_GET['a'] ] );// Safe

		$dqb->caller( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection
	}

	function testUnionQueryBuilder( UnionQueryBuilder $uqb ) {
		$uqb->orderBy( $_GET['a'], $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection

		$uqb->caller( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-SQLInjection

		echo $uqb->fetchResultSet();// @phan-suppress-current-line SecurityCheck-XSS
		echo $uqb->fetchField();// @phan-suppress-current-line SecurityCheck-XSS
		echo $uqb->fetchFieldValues();// @phan-suppress-current-line SecurityCheck-XSS
		echo $uqb->fetchRow();// @phan-suppress-current-line SecurityCheck-XSS
	}

	/**
	 * @suppress PhanPluginUseReturnValueKnown
	 */
	function testExpression( \Wikimedia\Rdbms\IDatabase $db ) {
		$db->expr( $_GET['field'], '=', 'a' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->expr( 'a', $_GET['op'], 'a' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$db->expr( 'a', '=', $_GET['value'] ); // Safe

		new Expression( $_GET['field'], '=', 'a' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		new Expression( 'a', $_GET['op'], 'a' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		new Expression( 'a', '=', $_GET['value'] ); // Safe

		new Expression( $_GET['field'], '=', new RawSQLValue( 'a' ) ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		new Expression( 'a', $_GET['op'], new RawSQLValue( 'a' ) ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		new Expression( 'a', '=', new RawSQLValue( $_GET['value'] ) ); // @phan-suppress-current-line SecurityCheck-SQLInjection

		$safeExpr = new Expression( 'a', '=', 'a' );
		$safeExpr->and( $_GET['field'], '=', 'a' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$safeExpr->and( 'a', $_GET['op'], 'a' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$safeExpr->and( 'a', '=', $_GET['value'] ); // Safe
		$safeExpr->or( $_GET['field'], '=', 'a' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$safeExpr->or( 'a', $_GET['op'], 'a' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$safeExpr->or( 'a', '=', $_GET['value'] ); // Safe

		$andExpr = $safeExpr->andExpr( $safeExpr );
		$andExpr->and( $_GET['field'], '=', 'a' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$andExpr->and( 'a', $_GET['op'], 'a' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$andExpr->and( 'a', '=', $_GET['value'] ); // Safe
		$andExpr2 = $db->andExpr( [ $safeExpr ] );

		$orExpr = $safeExpr->orExpr( $safeExpr );
		$orExpr->or( $_GET['field'], '=', 'a' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$orExpr->or( 'a', $_GET['op'], 'a' ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$orExpr->or( 'a', '=', $_GET['value'] ); // Safe
		$orExpr2 = $db->orExpr( [ $safeExpr ] );

		$unsafeExpr = new Expression( $_GET['a'], $_GET['a'], $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection

		$unsafeRawSQL = new RawSQLExpression( $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$unsafeRawSQL->andExpr( new RawSQLExpression( 'b > ' . $_GET['a'] ) ); // @phan-suppress-current-line SecurityCheck-SQLInjection
		$unsafeRawSQL->andExpr( new RawSQLExpression( 'a > b ' ) ); // Safe

		// Not validated at this point, only when building the Expression
		$db->newSelectQueryBuilder()->where( $safeExpr );
		$db->newSelectQueryBuilder()->where( $unsafeExpr );
		$db->newSelectQueryBuilder()->where( $unsafeRawSQL );
	}

	function testMessage( Message $msg ) {
		echo $msg->plain();// @phan-suppress-current-line SecurityCheck-XSS
		echo $msg->text();// @phan-suppress-current-line SecurityCheck-XSS
		echo $msg->parseAsBlock(); // Safe
		htmlspecialchars( $msg->parseAsBlock() );// @phan-suppress-current-line SecurityCheck-DoubleEscaped
		echo $msg->parse(); // Safe
		htmlspecialchars( $msg->parse() );// @phan-suppress-current-line SecurityCheck-DoubleEscaped
		echo $msg->escaped(); // Safe
		htmlspecialchars( $msg->escaped() );// @phan-suppress-current-line SecurityCheck-DoubleEscaped
		echo $msg->__toString(); // Safe
		htmlspecialchars( $msg->__toString() );// @phan-suppress-current-line SecurityCheck-DoubleEscaped
		$msg->rawParams( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS
		echo $msg->rawParams( '' );// Safe
		shell_exec( $msg->rawParams( '' ) );// Safe
	}

	function testStripState( StripState $ss ) {
		$ss->addNoWiki( $_GET['a'], '' );//Safe
		$ss->addNoWiki( '', $_GET['b'] );// @phan-suppress-current-line SecurityCheck-XSS
		$ss->addGeneral( $_GET['a'], '' );//Safe
		$ss->addGeneral( '', $_GET['b'] );// @phan-suppress-current-line SecurityCheck-XSS
	}

	function testShellFunctions(
		Shell $shell,
		\MediaWiki\Shell\Command $shellCmd,
		\Shellbox\Command\Command $shellboxCmd,
		Result $result, // Alias of UnboxedResult
		UnboxedResult $unboxedResult
	) {
		wfShellExec( [ $_GET['a'] ] );// Safe
		wfShellExec( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-ShellInjection
		echo wfShellExec( '' );// @phan-suppress-current-line SecurityCheck-XSS

		wfShellExecWithStderr( [ $_GET['a'] ] );// Safe
		wfShellExecWithStderr( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-ShellInjection
		echo wfShellExecWithStderr( '' );// @phan-suppress-current-line SecurityCheck-XSS

		shell_exec( wfEscapeShellArg( $_GET['a'] ) ); // Safe
		shell_exec( wfEscapeShellArg( '', '', '', '', '', $_GET['a'] ) ); // Safe
		echo wfEscapeShellArg( $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-XSS
		echo wfEscapeShellArg( '', '', '', '', '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-XSS

		shell_exec( $shell->escape( $_GET['a'] ) ); // Safe
		shell_exec( $shell->escape( '', '', '', '', '', $_GET['a'] ) ); // Safe
		echo $shell->escape( $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-XSS
		echo $shell->escape( '', '', '', '', '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-XSS

		$shellCmd->unsafeParams( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-ShellInjection
		$shellCmd->unsafeParams( '', '', '', '', $_GET['a'] );// @phan-suppress-current-line SecurityCheck-ShellInjection

		shell_exec( Shellbox::escape( $_GET['a'] ) ); // Safe
		shell_exec( Shellbox::escape( '', '', '', '', '', $_GET['a'] ) ); // Safe
		echo Shellbox::escape( $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-XSS
		echo Shellbox::escape( '', '', '', '', '', $_GET['a'] ); // @phan-suppress-current-line SecurityCheck-XSS

		$shellboxCmd->unsafeParams( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-ShellInjection
		$shellboxCmd->unsafeParams( '', '', '', '', $_GET['a'] );// @phan-suppress-current-line SecurityCheck-ShellInjection

		echo $result->getStdout();// @phan-suppress-current-line SecurityCheck-XSS
		echo $result->getStderr();// @phan-suppress-current-line SecurityCheck-XSS

		echo $unboxedResult->getStdout();// @phan-suppress-current-line SecurityCheck-XSS
		echo $unboxedResult->getStderr();// @phan-suppress-current-line SecurityCheck-XSS
	}

	function testHtml() {
		echo Html::rawElement( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS
		Html::rawElement( '', [ htmlspecialchars( '' ) ] );// @phan-suppress-current-line SecurityCheck-DoubleEscaped
		echo Html::rawElement( '', $_GET['a'] );// Safe
		echo Html::rawElement( '', [], $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS
		echo Html::rawElement( '', [], '' );// Safe
		htmlspecialchars( Html::rawElement( '', [], '' ) );// @phan-suppress-current-line SecurityCheck-DoubleEscaped

		echo Html::element( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS
		Html::element( '', [ htmlspecialchars( '' ) ] );// @phan-suppress-current-line SecurityCheck-DoubleEscaped
		echo Html::element( '', $_GET['a'] );// Safe
		echo Html::element( '', [], htmlspecialchars( '' ) );// @phan-suppress-current-line SecurityCheck-DoubleEscaped
		echo Html::element( '', [], $_GET['a'] );// Safe
		echo Html::element( '', [], '' );// Safe
		htmlspecialchars( Html::element( '', [], '' ) );// @phan-suppress-current-line SecurityCheck-DoubleEscaped

		echo Html::encodeJsVar( $_GET['a'] );// Safe
		echo Html::encodeJsVar( htmlspecialchars( '' ) );// @phan-suppress-current-line SecurityCheck-DoubleEscaped

		echo Html::encodeJsCall( $_GET['a'], [] );// @phan-suppress-current-line SecurityCheck-XSS
		echo Html::encodeJsCall( '', $_GET['a'] );// Safe
		echo Html::encodeJsCall( '', [ htmlspecialchars( '' ) ] );// @phan-suppress-current-line SecurityCheck-DoubleEscaped
	}

	function textXml() {
		echo \MediaWiki\Xml\Xml::tags( $_GET['a'], [], '' );// @phan-suppress-current-line SecurityCheck-XSS
		\MediaWiki\Xml\Xml::tags( '', [ htmlspecialchars( '' ) ], '' );// @phan-suppress-current-line SecurityCheck-DoubleEscaped
		echo \MediaWiki\Xml\Xml::tags( '', $_GET['a'], '' );// Safe
		echo \MediaWiki\Xml\Xml::tags( '', [], $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS
		echo \MediaWiki\Xml\Xml::tags( '', [], '' );// Safe
		htmlspecialchars( \MediaWiki\Xml\Xml::tags( '', [], '' ) );// @phan-suppress-current-line SecurityCheck-DoubleEscaped

		echo \MediaWiki\Xml\Xml::element( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS
		\MediaWiki\Xml\Xml::element( '', [ htmlspecialchars( '' ) ] );// @phan-suppress-current-line SecurityCheck-DoubleEscaped
		echo \MediaWiki\Xml\Xml::element( '', $_GET['a'] );// Safe
		echo \MediaWiki\Xml\Xml::element( '', [], htmlspecialchars( '' ) );// @phan-suppress-current-line SecurityCheck-DoubleEscaped
		echo \MediaWiki\Xml\Xml::element( '', [], $_GET['a'] );// Safe
		echo \MediaWiki\Xml\Xml::element( '', [], '' );// Safe
		htmlspecialchars( \MediaWiki\Xml\Xml::element( '', [], '' ) );// @phan-suppress-current-line SecurityCheck-DoubleEscaped

		echo \MediaWiki\Xml\Xml::encodeJsVar( $_GET['a'] );// Safe
		echo \MediaWiki\Xml\Xml::encodeJsVar( htmlspecialchars( '' ) );// @phan-suppress-current-line SecurityCheck-DoubleEscaped

		echo \MediaWiki\Xml\Xml::encodeJsCall( $_GET['a'], [] );// @phan-suppress-current-line SecurityCheck-XSS
		echo \MediaWiki\Xml\Xml::encodeJsCall( '', $_GET['a'] );// Safe
		echo \MediaWiki\Xml\Xml::encodeJsCall( '', [ htmlspecialchars( '' ) ] );// @phan-suppress-current-line SecurityCheck-DoubleEscaped
	}

	function testHtmlArmor() {
		new HtmlArmor( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS
	}

	function testOutputPage( \MediaWiki\Output\OutputPage $out ) {
		$out->addHeadItem( $_GET['a'], '' );// safe
		$out->addHeadItem( '', $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS
		$out->addHeadItems( [ 'foo' => $_GET['a'] ] );// @phan-suppress-current-line SecurityCheck-XSS

		$out->addHTML( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS

		$out->prependHTML( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS

		$out->addInlineStyle( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS
		$out->addSubtitle( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS
		$out->setSubtitle( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS
		$out->addScript( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS
		$out->addInlineScript( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS
		$out->setIndicators( [ 'foo' => $_GET['a'] ] );// @phan-suppress-current-line SecurityCheck-XSS
	}

	/**
	 * Non-namespaced alias of the OutputPage class.
	 */
	function testOutputPageAlias( \OutputPage $out ) {
		$out->addHeadItem( $_GET['a'], '' );// safe
		$out->addHeadItem( '', $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS
		$out->addHeadItems( [ 'foo' => $_GET['a'] ] );// @phan-suppress-current-line SecurityCheck-XSS

		$out->addHTML( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS

		$out->prependHTML( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS

		$out->addInlineStyle( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS
		$out->addSubtitle( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS
		$out->setSubtitle( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS
		$out->addScript( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS
		$out->addInlineScript( $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS
		$out->setIndicators( [ 'foo' => $_GET['a'] ] );// @phan-suppress-current-line SecurityCheck-XSS
	}

	function testSanitizer() {
		echo Sanitizer::escapeHtmlAllowEntities( $_GET['a'] );// Safe
		shell_exec( Sanitizer::escapeHtmlAllowEntities( $_GET['a'] ) );// @phan-suppress-current-line SecurityCheck-ShellInjection
		htmlspecialchars( Sanitizer::escapeHtmlAllowEntities( '' ) );// @phan-suppress-current-line SecurityCheck-DoubleEscaped

		echo Sanitizer::safeEncodeAttribute( $_GET['a'] );// Safe
		Sanitizer::safeEncodeAttribute( htmlspecialchars( '' ) );// @phan-suppress-current-line SecurityCheck-DoubleEscaped
		htmlspecialchars( Sanitizer::safeEncodeAttribute( '' ) );// @phan-suppress-current-line SecurityCheck-DoubleEscaped

		echo Sanitizer::encodeAttribute( $_GET['a'] );// Safe
		Sanitizer::encodeAttribute( htmlspecialchars( '' ) );// @phan-suppress-current-line SecurityCheck-DoubleEscaped
		htmlspecialchars( Sanitizer::encodeAttribute( '' ) );// @phan-suppress-current-line SecurityCheck-DoubleEscaped
	}

	/**
	 * Non-namespaced alias of the Sanitizer class.
	 */
	function testSanitizerAlias() {
		echo \Sanitizer::escapeHtmlAllowEntities( $_GET['a'] );// Safe
		shell_exec( \Sanitizer::escapeHtmlAllowEntities( $_GET['a'] ) );// @phan-suppress-current-line SecurityCheck-ShellInjection
		htmlspecialchars( \Sanitizer::escapeHtmlAllowEntities( '' ) );// @phan-suppress-current-line SecurityCheck-DoubleEscaped

		echo \Sanitizer::safeEncodeAttribute( $_GET['a'] );// Safe
		\Sanitizer::safeEncodeAttribute( htmlspecialchars( '' ) );// @phan-suppress-current-line SecurityCheck-DoubleEscaped
		htmlspecialchars( \Sanitizer::safeEncodeAttribute( '' ) );// @phan-suppress-current-line SecurityCheck-DoubleEscaped

		echo \Sanitizer::encodeAttribute( $_GET['a'] );// Safe
		\Sanitizer::encodeAttribute( htmlspecialchars( '' ) );// @phan-suppress-current-line SecurityCheck-DoubleEscaped
		htmlspecialchars( \Sanitizer::encodeAttribute( '' ) );// @phan-suppress-current-line SecurityCheck-DoubleEscaped
	}

	function testWebRequest( WebRequest $req ) {
		// @phan-suppress-next-line PhanAccessMethodPrivate
		echo $req->getGPCVal( [], '', '' );// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getRawVal( '' );// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getVal( '' );// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getArray( '' );// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getIntArray( '' );// Safe
		echo $req->getInt( '' );// Safe
		echo $req->getIntOrNull( '' );// Safe
		echo $req->getFloat( '' );// Safe
		echo $req->getBool( '' );// Safe
		echo $req->getFuzzyBool( '' );// Safe
		echo $req->getCheck( '' );// Safe
		echo $req->getText( '' );// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getValues( '' );// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getValueNames( [] );// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getQueryValues();// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getRawQueryString();// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getRawPostString();// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getRawInput();// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getCookie( '' );// @phan-suppress-current-line SecurityCheck-XSS
		echo WebRequest::getGlobalRequestURL();// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getRequestURL();// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getFullRequestURL();// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getAllHeaders();// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getHeader( '' );// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getAcceptLang();// @phan-suppress-current-line SecurityCheck-XSS
	}

	/**
	 * Non-namespaced alias of the WebRequest class.
	 */
	function testWebRequestAlias( \WebRequest $req ) {
		// @phan-suppress-next-line PhanAccessMethodPrivate
		echo $req->getGPCVal( [], '', '' );// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getRawVal( '' );// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getVal( '' );// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getArray( '' );// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getIntArray( '' );// Safe
		echo $req->getInt( '' );// Safe
		echo $req->getIntOrNull( '' );// Safe
		echo $req->getFloat( '' );// Safe
		echo $req->getBool( '' );// Safe
		echo $req->getFuzzyBool( '' );// Safe
		echo $req->getCheck( '' );// Safe
		echo $req->getText( '' );// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getValues( '' );// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getValueNames( [] );// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getQueryValues();// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getRawQueryString();// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getRawPostString();// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getRawInput();// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getCookie( '' );// @phan-suppress-current-line SecurityCheck-XSS
		echo WebRequest::getGlobalRequestURL();// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getRequestURL();// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getFullRequestURL();// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getAllHeaders();// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getHeader( '' );// @phan-suppress-current-line SecurityCheck-XSS
		echo $req->getAcceptLang();// @phan-suppress-current-line SecurityCheck-XSS
	}

	function testCommentStore( CommentStore $store, \Wikimedia\Rdbms\IDatabase $db ) {
		echo $store->insert( $db, '' );// Safe
		echo $store->getJoin( '' );// Safe
	}

	function testLinker( LinkTarget $target ) {
		$unsafeTarget = $this->getUnsafeLinkTarget();
		// Make sure taint-check knows it's unsafe
		echo $unsafeTarget;// @phan-suppress-current-line SecurityCheck-XSS
		echo Linker::linkKnown( $unsafeTarget );// Safe
		echo Linker::linkKnown( $target, $_GET['a'] );// @phan-suppress-current-line SecurityCheck-XSS
		echo Linker::linkKnown( $target, '', $_GET['a'] );// Safe
		echo Linker::linkKnown( $target, '', [], $_GET['a'] );// Safe
		echo Linker::linkKnown( $target, '', [], [], $_GET['a'] );// Safe
		htmlspecialchars( Linker::linkKnown( $target ) );// @phan-suppress-current-line SecurityCheck-DoubleEscaped
	}

	function testLinkRenderer( LinkRenderer $linkRenderer, LinkTarget $target ) {
		$unsafeTarget = $this->getUnsafeLinkTarget();
		// Make sure taint-check knows it's unsafe
		echo $unsafeTarget;// @phan-suppress-current-line SecurityCheck-XSS

		echo $linkRenderer->makeLink( $unsafeTarget );// Safe
		echo $linkRenderer->makeLink( $target, $_GET['a'] );// Safe
		$linkRenderer->makeLink( $target, htmlspecialchars( '' ) );// @phan-suppress-current-line SecurityCheck-DoubleEscaped
		echo $linkRenderer->makeLink( $target, '', $_GET['a'] );// Safe
		echo $linkRenderer->makeLink( $target, '', [], $_GET['a'] );// Safe
		htmlspecialchars( $linkRenderer->makeLink( $target ) );// @phan-suppress-current-line SecurityCheck-DoubleEscaped

		echo $linkRenderer->makeKnownLink( $unsafeTarget );// Safe
		echo $linkRenderer->makeKnownLink( $target, $_GET['a'] );// Safe
		$linkRenderer->makeKnownLink( $target, htmlspecialchars( '' ) );// @phan-suppress-current-line SecurityCheck-DoubleEscaped
		echo $linkRenderer->makeKnownLink( $target, '', $_GET['a'] );// Safe
		echo $linkRenderer->makeKnownLink( $target, '', [], $_GET['a'] );// Safe
		htmlspecialchars( $linkRenderer->makeKnownLink( $target ) );// @phan-suppress-current-line SecurityCheck-DoubleEscaped

		echo $linkRenderer->makePreloadedLink( $unsafeTarget );// Safe
		echo $linkRenderer->makePreloadedLink( $target, $_GET['a'] );// Safe
		$linkRenderer->makePreloadedLink( $target, htmlspecialchars( '' ) );// @phan-suppress-current-line SecurityCheck-DoubleEscaped
		echo $linkRenderer->makePreloadedLink( $target, '', $_GET['a'] );// Safe
		echo $linkRenderer->makePreloadedLink( $target, '', '', $_GET['a'] );// Safe
		htmlspecialchars( $linkRenderer->makePreloadedLink( $target ) );// @phan-suppress-current-line SecurityCheck-DoubleEscaped

		echo $linkRenderer->makeBrokenLink( $unsafeTarget );// Safe
		echo $linkRenderer->makeBrokenLink( $target, $_GET['a'] );// Safe
		$linkRenderer->makeBrokenLink( $target, htmlspecialchars( '' ) );// @phan-suppress-current-line SecurityCheck-DoubleEscaped
		echo $linkRenderer->makeBrokenLink( $target, '', $_GET['a'] );// Safe
		echo $linkRenderer->makeBrokenLink( $target, '', [], $_GET['a'] );// Safe
		htmlspecialchars( $linkRenderer->makeBrokenLink( $target ) );// @phan-suppress-current-line SecurityCheck-DoubleEscaped
	}

	/**
	 * NOTE: we can't type hint this as LinkTarget, or taint-check will think that it's safe
	 * due to __toString().
	 *
	 * @return-taint tainted
	 */
	function getUnsafeLinkTarget() {
		return $GLOBALS['unsafeLinkTarget'];
	}

	function testStatusValue() {
		echo StatusValue::newGood( $_GET['a'] );// Safe
		echo StatusValue::newGood( $_GET['a'] )->getValue();// Safe
		echo StatusValue::newGood( $_GET['a'] )->setResult( true, $_GET['a'] );// Safe
	}

	function testStatus() {
		echo Status::newGood( $_GET['a'] );// Safe
		echo Status::newGood( $_GET['a'] )->getValue();// Safe
		echo Status::newGood( $_GET['a'] )->setResult( true, $_GET['a'] );// Safe
	}

	function testStatusFormatter( StatusFormatter $f, StatusValue $sv ) {
		echo $f->getWikiText( $sv ); // @phan-suppress-current-line SecurityCheck-XSS
		echo $f->getHTML( $sv ); // Safe
		echo $f->getMessage( $sv )->plain(); // @phan-suppress-current-line SecurityCheck-XSS
		echo $f->getMessage( $sv )->parse(); // Safe

		// Legacy deprecated methods
		$status = Status::wrap( $sv );
		echo $status->getWikiText(); // @phan-suppress-current-line SecurityCheck-XSS
		echo $status->getHTML(); // Safe
		echo $status->getMessage()->plain(); // @phan-suppress-current-line SecurityCheck-XSS
		echo $status->getMessage()->parse(); // Safe
	}

	/**
	 * Non-namespaced alias of the Status class.
	 */
	function testStatusAlias() {
		echo \Status::newGood( $_GET['a'] );// Safe
		echo \Status::newGood( $_GET['a'] )->getValue();// Safe
		echo \Status::newGood( $_GET['a'] )->setResult( true, $_GET['a'] );// Safe
	}

	function testParserOutput( ParserOutput $po ) {
		$po->setIndicator( 'foo', $_GET['a'] ); //@phan-suppress-current-line SecurityCheck-XSS
		$po->setRawText( $_GET['a'] ); //@phan-suppress-current-line SecurityCheck-XSS
	}
}
