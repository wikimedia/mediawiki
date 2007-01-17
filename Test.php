<?php

/*

=head1 NAME

Test.php - L<Test::More> for PHP

=head1 SYNOPSIS

  require 'Test.php';

  plan( $num ); # plan $num tests
  # or
  plan( 'no_plan' ); # We don't know how many
  # or
  plan( 'skip_all' ); # Skip all tests
  # or
  plan( 'skip_all', $reason ); # Skip all tests with a reason

  diag( 'message in test output' ) # Trailing \n not required

  # $test_name is always optional and should be a short description of
  # the test, e.g. "some_function() returns an integer"

  # Various ways to say "ok"
  ok( $got == $expected, $test_name );
  
  # Compare with == and !=
  is( $got, $expected, $test_name );
  isnt( $got, $expected, $test_name );

  # Run a preg match on some data
  like( $got, $regex, $test_name );
  unlike( $got, $regex, $test_name );

  # Compare something with a given comparison operator
  cmp_ok( $got, '==', $expected, $test_name );
  # Compare something with a comparison function (should return bool)
  cmp_ok( $got, $func, $expected, $test_name );

  # Recursively check datastructures for equalness
  is_deeply( $got, $expected, $test_name );

  # Always pass or fail a test under an optional name
  pass( $test_name );
  fail( $test_name );

=head1 DESCRIPTION

F<Test.php> is an implementation of Perl's L<Test::More> and Pugs's B<Test> for
PHP. Like those two modules it produces TAP output (see L<TAP>) which
can then be gathered, formatted and summarized by a program that
understands TAP such as L<prove(1)>.

=cut

*/

register_shutdown_function('test_ends');

$Test = array(
	  'run'       => 0,
	  'failed'    => 0,
	  'badpass'   => 0,
	  'planned'   => null
);

function plan( $plan, $why = '' )
{
	global $Test;

	$Test['planned'] = true;

	switch ( $plan )
	{
	case 'no_plan':
		$Test['planned'] = false;
		break;
	case 'skip_all';
		printf( "1..0%s\n", $why ? " # Skip $why" : '' );
		exit;
	default:
		printf( "1..%d\n", $plan );
		break;
	}
}

function pass( $desc = '' )
{
	return proclaim(true, $desc);
}

function fail( $desc = '' )
{
	return proclaim( false, $desc );
}

function ok( $cond, $desc = '' ) {
	return proclaim( $cond, $desc );
}

function is( $got, $expected, $desc = '' ) {
	$pass = $got == $expected;
	return proclaim( $pass, $desc, /* todo */ false, $got, $expected );
}

function isnt( $got, $expected, $desc = '' ) {
	$pass = $got != $expected;
	return proclaim( $pass, $desc, /* todo */ false, $got, $expected, /* negated */ true );
}

function like( $got, $expected, $desc = '' ) {
	$pass = preg_match( $expected, $got );
	return proclaim( $pass, $desc,  /* todo */ false, $got, $expected );
}

function unlike( $got, $expected, $desc = '' ) {
	$pass = ! preg_match( $expected, $got );
	return proclaim( $pass, $desc,  /* todo */ false, $got, $expected, /* negated */ true );
}

function cmp_ok($got, $op, $expected, $desc = '')
{
	$pass = null;

	/* See http://www.php.net/manual/en/language.operators.comparison.php */
	switch ($op)
	{
	case '==':
		$pass = $got == $expected;
		break;
	case '===':
		$pass = $got === $expected;
		break;
	case '!=':
	case '<>':
		$pass = $got != $expected;
		break;
	case '!==':
		$pass = $got !== $expected;
		break;
	case '<':
		$pass = $got < $expected;
		break;
	case '>':
		$pass = $got > $expected;
		break;
	case '<=':
		$pass = $got <= $expected;
		break;
	case '>=':
		$pass = $got >= $expected;
		break;
	default:
		if ( function_exists( $op ) ) {
			$pass = $op( $got, $expected );
		} else {
			die("No such operator or function $op\n");
		}
	}

	return proclaim( $pass, $desc, /* todo */ false, $got, "$op $expected" );
}

function diag($message)
{
    if (is_array($message))
	{
	    $message = implode("\n", $message);
	}

    $messages = explode("\n", $message);

    foreach ($messages as $msg)
	{
        echo "# $msg\n";
    }
}

function include_ok( $file, $desc = '' )
{
    $pass = include $file;
    return proclaim( $pass, $desc == '' ? "include $file" : $desc );
}

function require_ok( $file, $desc = '' )
{
    $pass = require $file;
    return proclaim( $pass, $desc == '' ? "require $file" : $desc );
} 

function is_deeply( $got, $expected, $desc = '' )
{
    // hack
    $s_got = serialize( $got );
	$s_exp = serialize( $expected );

	$pass = $s_got == $s_exp;

	proclaim( $pass, $desc, /* todo */ false, $got, $expected );
}

function isa_ok( $obj, $expected, $desc = '' ) {
	$name = get_class( $obj );
	$pass = $name == $expected;
	proclaim( $pass, $desc, /* todo */ false, $name, $expected );
} 

function proclaim(
	$cond, // bool
	$desc = '',
	$todo = false,
	$got = null,
	$expected = null,
	$negate = false ) {

	global $Test;

	$Test['run'] += 1;

	# TODO: force_todo

	# Everything after the first # is special, so escape user-supplied messages
	$desc = str_replace( '#', '\\#', $desc );
	$desc = str_replace( "\n", '\\n', $desc );

	$ok = $cond ? "ok" : "not ok";
	$directive = $todo === false ? '' : '# TODO aoeu';

	printf( "%s %d %s%s\n", $ok, $Test['run'], $desc, $directive );

	if ( ! $cond ) {
		report_failure( $desc, $got, $expected, $negate, $todo );
	}

	return $cond;
}

function report_failure( $desc, $got, $expected, $negate, $todo ) {
	# Every public function in this file proclaim which then calls
    #  this function, so our culprit is the third item in the stack
	$caller = debug_backtrace();
	$call = $caller['2'];

	diag(
		sprintf( " Failed%stest '%s'\n in %s at line %d\n       got: %s\n  expected: %s",
			$todo ? ' TODO ' : ' ',
			$desc,
			$call['file'],
			$call['line'],
			$got,
			$expected
		)
	);
}

function test_ends ()
{
	global $Test;

	if ( $Test['planned'] === false ) {
		printf( "1..%d\n", $Test['run'] );
	}
}

/*

=head1 TODO

=over

=item * Fully document this file

=item *

Add TODO support, maybe via C<ok(0, "foo # TODO fix this")>
C<ok(1, "foo", array( 'todo' => 'fix this'))>.

=back

=head1 SEE ALSO

=over

=item L<TAP> - The TAP protocol

=item L<Test::More> 

=item Pugs's Test.pm

=back

=head1 AUTHOR

Ævar Arnfjörð Bjarmason <avarab@gmail.com>

=head1 LICENSING

This program is free software; you can redistribute it and/or modify it
under the same terms as Perl itself.

=cut

*/

?>
