#
# Creating the file ZhConversion.php used for Simplified/Traditional
# Chinese conversion. It gets the basic conversion table from the Unihan 
# database, and construct the phrase tables using phrase libraries in
# the SCIM packages. There are also special tables used to for adjustment.
# Some data in the file simp2trad.manual was taken from the following
# paper:
# Requirement: you need to set your locale to zh_CN.UTF-8 (or any
# other utf-8 locales, I suppose)
#
#
all: ZhConversion.php

Unihan.txt:
	wget ftp://ftp.unicode.org/Public/UNIDATA/Unihan.zip
	unzip Unihan.zip

EZ.txt.in:
	wget http://freedesktop.org/~suzhe/sources/scim-tables-0.4.3.tar.gz
	tar zxvf scim-tables-0.4.3.tar.gz > /dev/null
	cp scim-tables-0.4.3/zh/EZ.txt.in .
	rm -rf scim-tables-0.4.3* 

phrase_lib.txt:
	wget http://freedesktop.org/~suzhe/scim-chinese/scim-chinese-0.4.2.tar.gz 
	tar zxvf scim-chinese-0.4.2.tar.gz > /dev/null
	cp scim-chinese-0.4.2/data/phrase_lib.txt .
	rm -rf scim-chinese-0.4.2*

printutf8: printutf8.c
	gcc -o printutf8 printutf8.c

trad2simp.t: Unihan.txt trad2simp.manual printutf8
	grep kSimplifiedVariant Unihan.txt | sed  '/#/d' | sed  's/kSimplifiedVariant//' | ./printutf8 > tmp1
	for I in `colrm 11 < trad2simp.manual` ; do sed "/^$$I/d" tmp1 > tmp2; mv tmp2 tmp1; done
	cat trad2simp.manual tmp1 > trad2simp.t

simp2trad.t: Unihan.txt simp2trad.manual printutf8
	grep kTraditionalVariant Unihan.txt | sed  '/#/d' | sed  's/kTraditionalVariant//' | ./printutf8 > tmp1
	for I in `colrm 11 < simp2trad.manual` ; do sed "/^$$I/d" tmp1 > tmp2; mv tmp2 tmp1; done
	cat simp2trad.manual tmp1 > simp2trad.t

t2s_1tomany.t: trad2simp.t
	grep -s ".\{19,\}" trad2simp.t | sed  's/U+...../"/' | sed  's/|U+...../"=>"/' | sed  's/|U+.....//g' | sed  's/|/",/'  > t2s_1tomany.t

t2s_1to1.t: trad2simp.t s2t_1tomany.t
	sed  "/.*|.*|.*|.*/d" trad2simp.t | sed  's/U+[0-9a-z][0-9a-z]*/"/' | sed  's/|U+[0-9a-z][0-9a-z]*/"=>"/' | sed  's/|/",/' > t2s_1to1.t
	grep  '"."=>"..",' s2t_1tomany.t | sed 's/\("."\)=>".\(.\)",/"\2"=>\1,/' >> t2s_1to1.t
	grep  '"."=>"...",' s2t_1tomany.t | sed 's/\("."\)=>".\(.\).",/"\2"=>\1,/' >> t2s_1to1.t
	grep  '"."=>"...",' s2t_1tomany.t | sed 's/\("."\)=>"..\(.\)",/"\2"=>\1,/' >> t2s_1to1.t
	grep  '"."=>"....",' s2t_1tomany.t | sed 's/\("."\)=>".\(.\)..",/"\2"=>\1,/' >> t2s_1to1.t
	grep  '"."=>"....",' s2t_1tomany.t | sed 's/\("."\)=>"..\(.\).",/"\2"=>\1,/' >> t2s_1to1.t
	grep  '"."=>"....",' s2t_1tomany.t | sed 's/\("."\)=>"...\(.\)",/"\2"=>\1,/' >> t2s_1to1.t
	sort t2s_1to1.t | uniq > t2s_1to1.t


s2t_1tomany.t: simp2trad.t
	grep -s ".\{19,\}" simp2trad.t | sed  's/U+...../"/' | sed  's/|U+...../"=>"/' | sed  's/|U+.....//g' | sed  's/|/",/' > s2t_1tomany.t

s2t_1to1.t: simp2trad.t t2s_1tomany.t
	sed  "/.*|.*|.*|.*/d" simp2trad.t | sed  's/U+[0-9a-z][0-9a-z]*/"/' | sed  's/|U+[0-9a-z][0-9a-z]*/"=>"/' | sed  's/|/",/' > s2t_1to1.t
	grep  '"."=>"..",' t2s_1tomany.t | sed 's/\("."\)=>".\(.\)",/"\2"=>\1,/' >> s2t_1to1.t
	grep  '"."=>"...",' t2s_1tomany.t | sed 's/\("."\)=>".\(.\).",/"\2"=>\1,/' >> s2t_1to1.t
	grep  '"."=>"...",' t2s_1tomany.t | sed 's/\("."\)=>"..\(.\)",/"\2"=>\1,/' >> s2t_1to1.t
	grep  '"."=>"....",' t2s_1tomany.t | sed 's/\("."\)=>".\(.\)..",/"\2"=>\1,/' >> s2t_1to1.t
	grep  '"."=>"....",' t2s_1tomany.t | sed 's/\("."\)=>"..\(.\).",/"\2"=>\1,/' >> s2t_1to1.t
	grep  '"."=>"....",' t2s_1tomany.t | sed 's/\("."\)=>"...\(.\)",/"\2"=>\1,/' >> s2t_1to1.t
	sort s2t_1to1.t | uniq > s2t_1to1.t

ez.t: EZ.txt.in
	colrm 1 8 < EZ.txt.in | sed 's/\t//g' | grep "^.\{2,4\}[0-9]" | sed 's/[0-9]//g' > ez.t

alltradphrases.t: ez.t s2t_1tomany.t
	for i in `cat s2t_1tomany.t | sed 's/.......//' | sed 's/",/\n/' | sed 's/\(.\)/\1\n/g' |sort | uniq`; do grep -s $$i ez.t ; done  > alltradphrases.t || true

tradphrases_2.t: alltradphrases.t
	cat alltradphrases.t | grep  "^..$$" | sort | uniq > tradphrases_2.t

tradphrases_3.t: alltradphrases.t
	cat alltradphrases.t | grep  "^...$$" | sort | uniq > tradphrases_3.t
	for i in `cat tradphrases_2.t`; do grep $$i tradphrases_3.t ; done | sort | uniq > t3 || true
	diff t3 tradphrases_3.t | grep ">" | sed 's/> //' > tradphrases_3.t


tradphrases_4.t: alltradphrases.t
	cat alltradphrases.t | grep  "^....$$" | sort | uniq > tradphrases_4.t
	for i in `cat tradphrases_2.t`; do grep $$i tradphrases_4.t ; done | sort | uniq > t3 || true
	diff t3 tradphrases_4.t | grep ">" | sed 's/> //' > t
	mv t tradphrases_4.t
	for i in `cat tradphrases_3.t`; do grep $$i tradphrases_4.t ; done | sort | uniq > t3 || true
	diff t3 tradphrases_4.t | grep ">" | sed 's/> //' > t
	mv t tradphrases_4.t

tradphrases.t: tradphrases.manual tradphrases_2.t tradphrases_3.t tradphrases_4.t t2s_1tomany.t
	cat tradphrases.manual tradphrases_2.t tradphrases_3.t tradphrases_4.t |sort | uniq > tradphrases.t
	for i in `sed 's/"\(.\).*/\1/' t2s_1tomany.t ` ; do grep $$i tradphrases.t ; done | diff tradphrases.t - | grep '<' | sed 's/< //' > t
	mv t tradphrases.t

ph.t: phrase_lib.txt
	sed 's/[\t0-9a-zA-Z]//g' phrase_lib.txt | grep "^.\{2,4\}$$" > ph.t

allsimpphrases.t: ph.t
	rm -f allsimpphrases.t
	for i in `cat t2s_1tomany.t | sed 's/.......//' | sed 's/",/\n/' | sed 's/\(.\)/\1\n/g' | sort | uniq `; do grep $$i ph.t >> allsimpphrases.t; done

simpphrases_2.t: allsimpphrases.t
	cat allsimpphrases.t | grep "^..$$" | sort | uniq > simpphrases_2.t

simpphrases_3.t: allsimpphrases.t
	cat allsimpphrases.t | grep "^...$$" | sort | uniq > simpphrases_3.t
	for i in `cat simpphrases_2.t`; do grep $$i simpphrases_3.t ; done | sort | uniq > t3 || true
	diff t3 simpphrases_3.t | grep ">" | sed 's/> //' > t
	mv t simpphrases_3.t

simpphrases_4.t: allsimpphrases.t
	cat allsimpphrases.t | grep "^....$$" | sort | uniq > simpphrases_4.t
	rm -f t
	for i in `cat simpphrases_2.t`; do grep $$i simpphrases_4.t >> t; done || true
	sort t | uniq > t3
	diff t3 simpphrases_4.t | grep ">" | sed 's/> //' > t
	mv t simpphrases_4.t
	for i in `cat simpphrases_3.t`; do grep $$i simpphrases_4.t; done | sort  | uniq > t3 || true
	diff t3 simpphrases_4.t | grep ">" | sed 's/> //' > t
	mv t simpphrases_4.t

simpphrases.t:simpphrases_2.t simpphrases_3.t simpphrases_4.t t2s_1tomany.t
	cat simpphrases_2.t simpphrases_3.t simpphrases_4.t > simpphrases.t
	for i in `sed 's/"\(.\).*/\1/' t2s_1tomany.t ` ; do grep $$i simpphrases.t ; done | diff simpphrases.t - | grep '<' | sed 's/< //' > t
	mv t simpphrases.t


trad2simp1to1.t: t2s_1tomany.t t2s_1to1.t
	sed  's/\(.......\).*/\1",/' t2s_1tomany.t > trad2simp1to1.t
	cat t2s_1to1.t >> trad2simp1to1.t

simp2trad1to1.t: s2t_1tomany.t s2t_1to1.t
	sed  's/\(.......\).*/\1",/' s2t_1tomany.t > simp2trad1to1.t
	cat s2t_1to1.t >> simp2trad1to1.t

trad2simp.php: trad2simp1to1.t tradphrases.t
	printf '<?php\n$$trad2simp=array(' > trad2simp.php
	cat trad2simp1to1.t >> trad2simp.php
	printf ');\n$$str=\n"' >> trad2simp.php
	cat tradphrases.t >> trad2simp.php
	printf '";\n$$t=strtr($$str, $$trad2simp);\necho $$t;\n?>' >> trad2simp.php

simp2trad.php: simp2trad1to1.t simpphrases.t
	printf '<?php\n$$simp2trad=array(' > simp2trad.php
	cat simp2trad1to1.t >> simp2trad.php
	printf ');\n$$str=\n"' >> simp2trad.php
	cat simpphrases.t >> simp2trad.php
	printf '";\n$$t=strtr($$str, $$simp2trad);\necho $$t;\n?>' >> simp2trad.php

simp2trad.phrases.t: trad2simp.php tradphrases.t simp2tradPhrases.manual
	php -f trad2simp.php | sed  's/\(.*\)/"\1" => /' > tmp1
	cat tradphrases.t | sed  's/\(.*\)/"\1",/' > tmp2
	paste tmp1 tmp2 > simp2trad.phrases.t
	sed 's/\(.*\)\t\(.*\)/"\1"=>"\2",/' simp2tradPhrases.manual >> simp2trad.phrases.t

trad2simp.phrases.t: simp2trad.php simpphrases.t trad2simpPhrases.manual
	php -f simp2trad.php | sed  's/\(.*\)/"\1" => /' > tmp1
	cat simpphrases.t | sed  's/\(.*\)/"\1",/' > tmp2
	paste tmp1 tmp2 > trad2simp.phrases.t
	sed 's/\(.*\)\t\(.*\)/"\1"=>"\2",/' trad2simpPhrases.manual >> trad2simp.phrases.t

ZhConversion.php: simp2trad1to1.t simp2trad.phrases.t trad2simp1to1.t trad2simp.phrases.t
	printf '<?php\n$$zhSimp2Trad=array(\n' > ZhConversion.php
	cat simp2trad1to1.t >> ZhConversion.php
	echo >> ZhConversion.php
	cat simp2trad.phrases.t >> ZhConversion.php
	echo ');' >> ZhConversion.php
	echo >> ZhConversion.php
	printf '$$zhTrad2Simp=array(\n' >> ZhConversion.php
	cat trad2simp1to1.t >> ZhConversion.php
	echo >> ZhConversion.php
	cat trad2simp.phrases.t >> ZhConversion.php
	printf ');\n?>' >> ZhConversion.php

clean:
	rm -f ZhConversion.php tmp1 tmp2 tmp3 t3 *.t trad2simp.php simp2trad.php