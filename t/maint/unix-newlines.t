#!/usr/bin/env perl
use strict;
use warnings;

use Test::More;;

use File::Find;
use Socket '$CRLF';

my $ext = qr/(?: t | pm | sql | js | php | inc | xml )/x;

my @files;
find( sub { push @files, $File::Find::name if -f && /\. $ext $/x }, '.' );

plan 'no_plan';

for my $file (@files) {
    open my $fh, "<", $file or die "Can't open $file: $!";
    binmode $fh;

    my $ok = 1;
    while (<$fh>) {
        if (/$CRLF/) {
            fail "$file has \\r\\n on line $.";
            $ok = 0;
        }
    }

    pass "$file has only unix newlines" if $ok;
}



