#!/usr/bin/env perl
use strict;
use warnings;

use Test::More;;

use File::Find;
use File::Slurp qw< slurp >;
use Socket qw< $CRLF $LF >;

my $ext = qr/(?: t | pm | sql | js | php | inc | xml )/x;

my @files;
find( sub { push @files, $File::Find::name if -f && /\. $ext $/x }, '.' );

plan tests => scalar @files;

for my $file (@files) {
	my $cont = slurp $file;
	if ( $cont and $cont =~ $CRLF ) {
		pass "$file contains windows newlines";
	} else {
		fail "$file is made of unix newlines and win";
	}
}



