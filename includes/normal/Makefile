FETCH=wget
#FETCH=fetch
BASE=http://www.unicode.org/Public/UNIDATA
PHP=php
#PHP=php-cli

all : UtfNormalData.inc

UtfNormalData.inc : UtfNormalGenerate.php UtfNormalUtil.php UnicodeData.txt CompositionExclusions.txt NormalizationCorrections.txt DerivedNormalizationProps.txt
	$(PHP) UtfNormalGenerate.php

test : UtfNormalTest.php UtfNormalData.inc NormalizationTest.txt
	$(PHP) UtfNormalTest.php

bench : UtfNormalData.inc
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

