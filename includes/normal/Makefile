FETCH=wget
#FETCH=fetch
BASE=http://www.unicode.org/Public/UNIDATA
PHP=php
#PHP=php-cli

all : UtfNormalData.inc

UtfNormalData.inc : UtfNormalGenerate.php UtfNormalUtil.php UnicodeData.txt CompositionExclusions.txt NormalizationCorrections.txt DerivedNormalizationProps.txt
	$(PHP) UtfNormalGenerate.php

test : testutf8 UtfNormalTest.php UtfNormalData.inc NormalizationTest.txt
	$(PHP) UtfNormalTest.php

testutf8 : Utf8Test.php UTF-8-test.txt
	$(PHP) Utf8Test.php

bench : UtfNormalData.inc testdata/washington.txt testdata/berlin.txt testdata/tokyo.txt testdata/sociology.txt
	$(PHP) UtfNormalBench.php

clean :
	rm -f UtfNormalData.inc

distclean : clean
	rm -f CompositionExclusions.txt NormalizationTest.txt NormalizationCorrections.txt UnicodeData.txt DerivedNormalizationProps.txt

# The Unicode data files...
CompositionExclusions.txt :
	$(FETCH) $(BASE)/CompositionExclusions.txt

NormalizationTest.txt :
	$(FETCH) $(BASE)/NormalizationTest.txt

NormalizationCorrections.txt :
	$(FETCH) $(BASE)/NormalizationCorrections.txt

DerivedNormalizationProps.txt :
	$(FETCH) $(BASE)/DerivedNormalizationProps.txt

UnicodeData.txt :
	$(FETCH) $(BASE)/UnicodeData.txt

UTF-8-test.txt :
	$(FETCH) http://www.cl.cam.ac.uk/~mgk25/ucs/examples/UTF-8-test.txt

testdata/berlin.txt :
	mkdir -p testdata && wget -U MediaWiki/test -O testdata/berlin.txt "http://de.wikipedia.org/w/wiki.phtml?title=Berlin&oldid=2775712&action=raw"

testdata/washington.txt :
	mkdir -p testdata && wget -U MediaWiki/test -O testdata/washington.txt "http://en.wikipedia.org/w/wiki.phtml?title=Washington%2C_DC&oldid=6370218&action=raw"

testdata/tokyo.txt :
	mkdir -p testdata && wget -U MediaWiki/test -O testdata/tokyo.txt "http://ja.wikipedia.org/w/wiki.phtml?title=%E6%9D%B1%E4%BA%AC&oldid=823926&action=raw"

testdata/sociology.txt :
	mkdir -p testdata && wget -U MediaWiki/test -O testdata/sociology.txt "http://ko.wikipedia.org/w/wiki.phtml?title=%EC%82%AC%ED%9A%8C%ED%95%99&oldid=16409&action=raw"
