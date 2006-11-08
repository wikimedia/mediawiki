#!/usr/bin/perl

## Rough check that the base and postgres "tables.sql" are in sync
## Should be run from maintenance/postgres

use strict;
use warnings;
use Data::Dumper;

my @old = ("../tables.sql", "../mysql5/tables.sql");
my $new = "tables.sql";
my @xfile;

## Read in exceptions and other metadata
my %ok;
while (<DATA>) {
	next unless /^(\w+)\s*:\s*([^#]+)/;
	my ($name,$val) = ($1,$2);
	chomp $val;
	if ($name eq 'RENAME') {
		die "Invalid rename\n" unless $val =~ /(\w+)\s+(\w+)/;
		$ok{OLD}{$1} = $2;
		$ok{NEW}{$2} = $1;
		next;
	}
	if ($name eq 'XFILE') {
		push @xfile, $val;
		next;
	}
	for (split(/\s+/ => $val)) {
		$ok{$name}{$_} = 0;
	}
}

my $datatype = join '|' => qw(
bool
tinyint int bigint real float
tinytext mediumtext text char varchar varbinary
timestamp datetime
tinyblob mediumblob blob
);
$datatype .= q{|ENUM\([\"\w, ]+\)};
$datatype = qr{($datatype)};

my $typeval = qr{(\(\d+\))?};

my $typeval2 = qr{ unsigned| binary| NOT NULL| NULL| auto_increment| default ['\-\d\w"]+| REFERENCES .+CASCADE};

my $indextype = join '|' => qw(INDEX KEY FULLTEXT), "PRIMARY KEY", "UNIQUE INDEX", "UNIQUE KEY";
$indextype = qr{$indextype};

my $engine = qr{TYPE|ENGINE};

my $tabletype = qr{InnoDB|MyISAM|HEAP|HEAP MAX_ROWS=\d+};

my $charset = qr{utf8};


open my $newfh, "<", $new or die qq{Could not open $new: $!\n};


my ($table,%old);

## Read in the xfiles
my %xinfo;
for my $xfile (@xfile) {
	print "Loading $xfile\n";
	my $info = &parse_sql($xfile);
	for (keys %$info) {
		$xinfo{$_} = $info->{$_};
	}
}

for my $oldfile (@old) {
	print "Loading $oldfile\n";
	my $info = &parse_sql($oldfile);
	for (keys %xinfo) {
		$info->{$_} = $xinfo{$_};
	}
	$old{$oldfile} = $info;
}

sub parse_sql {

	my $oldfile = shift;

	open my $oldfh, "<", $oldfile or die qq{Could not open $oldfile: $!\n};

	my %info;
	while (<$oldfh>) {
		next if /^\s*\-\-/ or /^\s+$/;
		s/\s*\-\- [\w ]+$//;
		chomp;

		if (/CREATE\s*TABLE/i) {
			m{^CREATE TABLE /\*\$wgDBprefix\*/(\w+) \($}
				or die qq{Invalid CREATE TABLE at line $. of $oldfile\n};
			$table = $1;
			$info{$table}{name}=$table;
		}
		elsif (/^\) ($engine)=($tabletype);$/) {
			$info{$table}{engine}=$1;
			$info{$table}{type}=$2;
		}
		elsif (/^\) ($engine)=($tabletype), DEFAULT CHARSET=($charset);$/) {
			$info{$table}{engine}=$1;
			$info{$table}{type}=$2;
			$info{$table}{charset}=$3;
		}
		elsif (/^  (\w+) $datatype$typeval$typeval2{0,3},?$/) {
			$info{$table}{column}{$1} = $2;
		}
		elsif (/^  ($indextype)(?: (\w+))? \(([\w, \(\)]+)\),?$/) {
			$info{$table}{lc $1."_name"} = $2 ? $2 : "";
			$info{$table}{lc $1."pk_target"} = $3;
		}
		else {
			die "Cannot parse line $. of $oldfile:\n$_\n";
		}

	}
	close $oldfh;

	return \%info;

} ## end of parse_sql

for my $oldfile (@old) {

## Begin non-standard indent

## MySQL sanity checks
for my $table (sort keys %{$old{$oldfile}}) {
	my $t = $old{$oldfile}{$table};
	if (($oldfile =~ /5/ and $t->{engine} ne 'ENGINE')
		or
		($oldfile !~ /5/ and $t->{engine} ne 'TYPE')) {
		die "Invalid engine for $oldfile: $t->{engine}\n" unless $t->{name} eq 'profiling';
	}
}

my $dtype = join '|' => qw(
SMALLINT INTEGER BIGINT NUMERIC SERIAL
TEXT CHAR VARCHAR
BYTEA
TIMESTAMPTZ
CIDR
);
$dtype = qr{($dtype)};
my %new;
my ($infunction,$inview,$inrule) = (0,0,0);
seek $newfh, 0, 0;
while (<$newfh>) {
	next if /^\s*\-\-/ or /^\s*$/;
	s/\s*\-\- [\w ']+$//;
	next if /^BEGIN;/ or /^SET / or /^COMMIT;/;
	next if /^CREATE SEQUENCE/;
	next if /^CREATE(?: UNIQUE)? INDEX/;
	next if /^CREATE FUNCTION/;
	next if /^CREATE TRIGGER/ or /^  FOR EACH ROW/;
	next if /^INSERT INTO/ or /^  VALUES \(/;
	next if /^ALTER TABLE/;
	chomp;

	if (/^\$mw\$;?$/) {
		$infunction = $infunction ? 0 : 1;
		next;
	}
	next if $infunction;

	next if /^CREATE VIEW/ and $inview = 1;
	if ($inview) {
		/;$/ and $inview = 0;
		next;
	}

	next if /^CREATE RULE/ and $inrule = 1;
	if ($inrule) {
		/;$/ and $inrule = 0;
		next;
	}

	if (/^CREATE TABLE "?(\w+)"? \($/) {
		$table = $1;
		$new{$table}{name}=$table;
	}
	elsif (/^\);$/) {
	}
	elsif (/^  (\w+) +$dtype/) {
		$new{$table}{column}{$1} = $2;
	}
	else {
		die "Cannot parse line $. of $new:\n$_\n";
	}
}

## Old but not new
for my $t (sort keys %{$old{$oldfile}}) {
	if (!exists $new{$t} and !exists $ok{OLD}{$t}) {
		print "Table not in $new: $t\n";
		next;
	}
	next if exists $ok{OLD}{$t} and !$ok{OLD}{$t};
	my $newt = exists $ok{OLD}{$t} ? $ok{OLD}{$t} : $t;
	my $oldcol = $old{$oldfile}{$t}{column};
	my $newcol = $new{$newt}{column};
	for my $c (keys %$oldcol) {
		if (!exists $newcol->{$c}) {
			print "Column $t.$c not in new\n";
			next;
		}
	}
	for my $c (keys %$newcol) {
		if (!exists $oldcol->{$c}) {
			print "Column $t.$c not in old\n";
			next;
		}
	}
}
## New but not old:
for (sort keys %new) {
	if (!exists $old{$oldfile}{$_} and !exists $ok{NEW}{$_}) {
		print "Not in old: $_\n";
		next;
	}
}


} ## end each file to be parsed


__DATA__
## Known exceptions
OLD: searchindex          ## We use tsearch2 directly on the page table instead
OLD: archive              ## This is a view due to the char(14) timestamp hack
RENAME: user mwuser       ## Reserved word causing lots of problems
RENAME: text pagecontent  ## Reserved word
NEW: archive2             ## The real archive table
NEW: mediawiki_version    ## Just us, for now
XFILE: ../archives/patch-profiling.sql
