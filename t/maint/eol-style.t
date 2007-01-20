#!/usr/bin/env perl
#
# Based on php-tag.t
#
use strict;
use warnings;

use Test::More;
use File::Find;

my $ext = qr/(?: php | inc | txt | sql | t)/x;
my @files;

find( sub { push @files, $File::Find::name if -f && /\. $ext $/x }, '.' );

plan tests => scalar @files ;

for my $file (@files) {
	my $res = `svn propget svn:eol-style $file 2>&1` ;

	if( $res =~ 'native' ) {
		ok 1 => "$file svn:eol-style is 'native'";
	} elsif( $res =~ substr( $file, 2 ) ) {
		# not under version control
		next;
	} else {
		ok 0 => "svn:eol-style not native $file";
	}
}
