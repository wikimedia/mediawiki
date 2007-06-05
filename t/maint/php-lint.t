#!/usr/bin/env perl
#
# Based on php-tag.t and eol-style
#
use strict;
use warnings;

use Test::More;
use File::Find;
use IPC::Open3;
use File::Spec;
use Symbol qw(gensym);

my $ext = qr/(?: php | inc )/x;
my @files;

find( sub { push @files, $File::Find::name if -f && /\. $ext $/x }, '.' );

plan tests => scalar @files ;

for my $file (@files) {
	open NULL, '+>', File::Spec->devnull and \*NULL or die;
	my $pid = open3('<&NULL', \*P, '>&NULL', qw'php -l', $file);
	my $res = do { local $/; <P> . "" };
	chomp $res;
	waitpid $pid, 0;

	if ( $? == 0 ) {
		pass($file);
	} else {
		fail("$file does not pass php -l. Error was: $res");
	}
}
