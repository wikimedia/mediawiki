This directory contains data files for testing XML dumps.
Each file contains an XML structure, full or partial.
These data files are intended to be used with DumpAsserter::assertDOM().

The data files contain XML with variable placeholders using a mustache-like
syntax, e.g. {{site_name}}. The variable placeholder syntax is implemented by
DumpAsserter::resolveVars(), variable values can be defined using DumpAsserter::setVarMapping().
Any differences in whitespace and any comments in the XML files are ignored.

Data files may make use of the <test:data> and <test:end/> tags: <test:data> acts as a
wrapper for a sequence of elements and is ignored during comparison. <test:end/> terminates
the comparison, and causes all subsequent content of the file to be ignored.
The behavior of the <test:data> and <test:end/> tags is controlled by DumpAsserter::assertDOM().

The names of these files, the data they contain, as well as the names of the
variables used, are determined by the PageDumpTestDataTrait, which is a trait
used by some of the subclasses of DumpTestCase.
