#!/usr/bin/env perl
#
# Based on php-tag.t
#
use strict;
use warnings;

use Test::More;
use File::Find;
use IPC::Open3;
use File::Spec;
use Symbol qw(gensym);

my $ext = qr/(?: php | inc | txt | sql | t)/x;
my @files;

find( sub { push @files, $File::Find::name if -f && /\. $ext $/x }, '.' );

plan tests => scalar @files ;

for my $file (@files) {
	open NULL, '+>', File::Spec->devnull and \*NULL or die;
	my $pid = open3('<&NULL', \*P, '>&NULL', qw'svn propget svn:eol-style', $file);
	my $res = do { local $/; <P> . "" };
	chomp $res;
	waitpid $pid, 0;

	if ( $? != 0 ) {
		ok 1 => "svn propget failed, $file probably not under version control";
	} elsif ( $res eq 'native' ) {
		ok 1 => "$file svn:eol-style is 'native'";
	} else {
		ok 0 => "$file svn:eol-style is '$res', should be 'native'";
	}
}
