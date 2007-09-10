#
# Creating the file ZhConversion.php used for Simplified/Traditional
# Chinese conversion. It gets the basic conversion table from the Unihan 
# database, and construct the phrase tables using phrase libraries in
# the SCIM packages and the libtabe package. There are also special
# tables used to for adjustment. 
#

GREP = LANG=zh_CN.UTF8 grep
SED = LANG=zh_CN.UTF8 sed
DIFF = LANG=zh_CN.UTF8 diff
CC ?= gcc

#installation directory
INSTDIR = /usr/local/share/zhdaemons/

all: ZhConversion.php tradphrases.notsure simpphrases.notsure wordlist toCN.dict toTW.dict toHK.dict toSG.dict

Unihan.txt:
	wget -nc ftp://ftp.unicode.org/Public/UNIDATA/Unihan.zip
	unzip -q Unihan.zip

EZ.txt.in:
	wget -nc http://easynews.dl.sourceforge.net/sourceforge/scim/scim-tables-0.5.1.tar.gz
	tar -xzf scim-tables-0.5.1.tar.gz -O scim-tables-0.5.1/zh/EZ.txt.in > EZ.txt.in

phrase_lib.txt:
	wget -nc http://easynews.dl.sourceforge.net/sourceforge/scim/scim-pinyin-0.5.0.tar.gz
	tar -xzf scim-pinyin-0.5.0.tar.gz -O scim-pinyin-0.5.0/data/phrase_lib.txt > phrase_lib.txt

tsi.src:
	wget -nc http://unc.dl.sourceforge.net/sourceforge/libtabe/libtabe-0.2.3.tgz	
	tar -xzf libtabe-0.2.3.tgz -O libtabe/tsi-src/tsi.src > tsi.src

wordlist: phrase_lib.txt EZ.txt.in tsi.src
	iconv -c -f big5 -t utf8 tsi.src | $(SED) 's/# //g' | $(SED) 's/[ ][0-9].*//' > wordlist
	$(SED) 's/\(.*\)\t[0-9][0-9]*.*/\1/' phrase_lib.txt | $(SED) '1,5d' >>wordlist
	$(SED) '1,/BEGIN_TABLE/d' EZ.txt.in | colrm 1 8 | $(SED) 's/\t.*//' | $(GREP) "^...*" >> wordlist
	sort wordlist | uniq | $(SED) 's/ //g' > t
	mv t wordlist

printutf8: printutf8.c
	$(CC) -o printutf8 printutf8.c

unihan.t2s.t: Unihan.txt printutf8
	$(GREP) kSimplifiedVariant Unihan.txt | $(SED)  '/#/d' | $(SED)  's/kSimplifiedVariant//' | ./printutf8 > unihan.t2s.t

trad2simp.t: trad2simp.manual unihan.t2s.t
	cp unihan.t2s.t tmp1
	for I in `colrm 11 < trad2simp.manual` ; do $(SED) "/^$$I/d" tmp1 > tmp2; mv tmp2 tmp1; done
	cat trad2simp.manual tmp1 > trad2simp.t

unihan.s2t.t: Unihan.txt printutf8
	$(GREP) kTraditionalVariant Unihan.txt | $(SED)  '/#/d' | $(SED)  's/kTraditionalVariant//' | ./printutf8 > unihan.s2t.t

simp2trad.t: unihan.s2t.t simp2trad.manual
	cp unihan.s2t.t tmp1
	for I in `colrm 11 < simp2trad.manual` ; do $(SED) "/^$$I/d" tmp1 > tmp2; mv tmp2 tmp1; done
	cat simp2trad.manual tmp1 > simp2trad.t

t2s_1tomany.t: trad2simp.t
	$(GREP) -s ".\{19,\}" trad2simp.t | $(SED)  's/U+...../"/' | $(SED)  's/|U+...../"=>"/' | $(SED)  's/|U+.....//g' | $(SED)  's/|/",/'  > t2s_1tomany.t

t2s_1to1.t: trad2simp.t s2t_1tomany.t
	$(SED)  "/.*|.*|.*|.*/d" trad2simp.t | $(SED)  's/U+[0-9a-z][0-9a-z]*/"/' | $(SED)  's/|U+[0-9a-z][0-9a-z]*/"=>"/' | $(SED)  's/|/",/' > t2s_1to1.t
	$(GREP)  '"."=>"..",' s2t_1tomany.t | $(SED) 's/\("."\)=>".\(.\)",/"\2"=>\1,/' >> t2s_1to1.t
	$(GREP)  '"."=>"...",' s2t_1tomany.t | $(SED) 's/\("."\)=>".\(.\).",/"\2"=>\1,/' >> t2s_1to1.t
	$(GREP)  '"."=>"...",' s2t_1tomany.t | $(SED) 's/\("."\)=>"..\(.\)",/"\2"=>\1,/' >> t2s_1to1.t
	$(GREP)  '"."=>"....",' s2t_1tomany.t | $(SED) 's/\("."\)=>".\(.\)..",/"\2"=>\1,/' >> t2s_1to1.t
	$(GREP)  '"."=>"....",' s2t_1tomany.t | $(SED) 's/\("."\)=>"..\(.\).",/"\2"=>\1,/' >> t2s_1to1.t
	$(GREP)  '"."=>"....",' s2t_1tomany.t | $(SED) 's/\("."\)=>"...\(.\)",/"\2"=>\1,/' >> t2s_1to1.t
	sort t2s_1to1.t | uniq > t
	mv t t2s_1to1.t


s2t_1tomany.t: simp2trad.t
	$(GREP) -s ".\{19,\}" simp2trad.t | $(SED)  's/U+...../"/' | $(SED)  's/|U+...../"=>"/' | $(SED)  's/|U+.....//g' | $(SED)  's/|/",/' > s2t_1tomany.t

s2t_1to1.t: simp2trad.t t2s_1tomany.t
	$(SED)  "/.*|.*|.*|.*/d" simp2trad.t | $(SED)  's/U+[0-9a-z][0-9a-z]*/"/' | $(SED)  's/|U+[0-9a-z][0-9a-z]*/"=>"/' | $(SED)  's/|/",/' > s2t_1to1.t
	$(GREP)  '"."=>"..",' t2s_1tomany.t | $(SED) 's/\("."\)=>".\(.\)",/"\2"=>\1,/' >> s2t_1to1.t
	$(GREP)  '"."=>"...",' t2s_1tomany.t | $(SED) 's/\("."\)=>".\(.\).",/"\2"=>\1,/' >> s2t_1to1.t
	$(GREP)  '"."=>"...",' t2s_1tomany.t | $(SED) 's/\("."\)=>"..\(.\)",/"\2"=>\1,/' >> s2t_1to1.t
	$(GREP)  '"."=>"....",' t2s_1tomany.t | $(SED) 's/\("."\)=>".\(.\)..",/"\2"=>\1,/' >> s2t_1to1.t
	$(GREP)  '"."=>"....",' t2s_1tomany.t | $(SED) 's/\("."\)=>"..\(.\).",/"\2"=>\1,/' >> s2t_1to1.t
	$(GREP)  '"."=>"....",' t2s_1tomany.t | $(SED) 's/\("."\)=>"...\(.\)",/"\2"=>\1,/' >> s2t_1to1.t
	sort s2t_1to1.t | uniq > t
	mv t s2t_1to1.t

tphrase.t: EZ.txt.in tsi.src
	colrm 1 8 < EZ.txt.in | $(SED) 's/\t//g' | $(GREP) "^.\{2,4\}[0-9]" | $(SED) 's/[0-9]//g' > t
	iconv -c -f big5 -t utf8 tsi.src | $(SED) 's/ [0-9].*//g' | $(SED) 's/[# ]//g'| $(GREP) "^.\{2,4\}"  >> t
	sort t | uniq > tphrase.t

alltradphrases.t: tphrase.t s2t_1tomany.t 
	for i in `cat s2t_1tomany.t | $(SED) 's/.*=>".//' | $(SED) 's/"//g' |$(SED) 's/,/\n/' | $(SED) 's/\(.\)/\1\n/g' |sort | uniq`; do $(GREP) -s $$i tphrase.t ; done  > alltradphrases.t || true


tradphrases_2.t: alltradphrases.t
	cat alltradphrases.t | $(GREP)  "^..$$" | sort | uniq > tradphrases_2.t

tradphrases_3.t: alltradphrases.t
	cat alltradphrases.t | $(GREP)  "^...$$" | sort | uniq > tradphrases_3.t
	for i in `cat tradphrases_2.t`; do $(GREP) $$i tradphrases_3.t ; done | sort | uniq > t3 || true
	$(DIFF) t3 tradphrases_3.t | $(GREP) ">" | $(SED) 's/> //' > t
	mv t tradphrases_3.t


tradphrases_4.t: alltradphrases.t
	cat alltradphrases.t | $(GREP)  "^....$$" | sort | uniq > tradphrases_4.t
	for i in `cat tradphrases_2.t`; do $(GREP) $$i tradphrases_4.t ; done | sort | uniq > t3 || true
	$(DIFF) t3 tradphrases_4.t | $(GREP) ">" | $(SED) 's/> //' > t
	mv t tradphrases_4.t
	for i in `cat tradphrases_3.t`; do $(GREP) $$i tradphrases_4.t ; done | sort | uniq > t3 || true
	$(DIFF) t3 tradphrases_4.t | $(GREP) ">" | $(SED) 's/> //' > t
	mv t tradphrases_4.t

tradphrases.t: tradphrases.manual tradphrases_2.t tradphrases_3.t tradphrases_4.t t2s_1tomany.t
	cat tradphrases.manual tradphrases_2.t tradphrases_3.t tradphrases_4.t |sort | uniq > tradphrases.t
	for i in `$(SED) 's/"\(.\).*/\1/' t2s_1tomany.t ` ; do $(GREP) $$i tradphrases.t ; done | $(DIFF) tradphrases.t - | $(GREP) '<' | $(SED) 's/< //' > t
	mv t tradphrases.t

tradphrases.notsure: tradphrases_2.t tradphrases_3.t tradphrases_4.t t2s_1tomany.t
	cat tradphrases_2.t tradphrases_3.t tradphrases_4.t |sort | uniq > t
	for i in `$(SED) 's/"\(.\).*/\1/' t2s_1tomany.t ` ; do $(GREP) $$i t; done | $(DIFF) t - | $(GREP) '>' | $(SED) 's/> //' > tradphrases.notsure


ph.t: phrase_lib.txt
	$(SED) 's/[\t0-9a-zA-Z]//g' phrase_lib.txt | $(GREP) "^.\{2,4\}$$" > ph.t

allsimpphrases.t: ph.t
	rm -f allsimpphrases.t
	for i in `cat t2s_1tomany.t | $(SED) 's/.*=>".//' | $(SED) 's/"//g' | $(SED) 's/,/\n/' | $(SED) 's/\(.\)/\1\n/g' | sort | uniq `; do $(GREP) $$i ph.t >> allsimpphrases.t; done

simpphrases_2.t: allsimpphrases.t
	cat allsimpphrases.t | $(GREP) "^..$$" | sort | uniq > simpphrases_2.t

simpphrases_3.t: allsimpphrases.t
	cat allsimpphrases.t | $(GREP) "^...$$" | sort | uniq > simpphrases_3.t
	for i in `cat simpphrases_2.t`; do $(GREP) $$i simpphrases_3.t ; done | sort | uniq > t3 || true
	$(DIFF) t3 simpphrases_3.t | $(GREP) ">" | $(SED) 's/> //' > t
	mv t simpphrases_3.t

simpphrases_4.t: allsimpphrases.t
	cat allsimpphrases.t | $(GREP) "^....$$" | sort | uniq > simpphrases_4.t
	rm -f t
	for i in `cat simpphrases_2.t`; do $(GREP) $$i simpphrases_4.t >> t; done || true
	sort t | uniq > t3
	$(DIFF) t3 simpphrases_4.t | $(GREP) ">" | $(SED) 's/> //' > t
	mv t simpphrases_4.t
	for i in `cat simpphrases_3.t`; do $(GREP) $$i simpphrases_4.t; done | sort  | uniq > t3 || true
	$(DIFF) t3 simpphrases_4.t | $(GREP) ">" | $(SED) 's/> //' > t
	mv t simpphrases_4.t

simpphrases.t:simpphrases_2.t simpphrases_3.t simpphrases_4.t t2s_1tomany.t
	cat simpphrases_2.t simpphrases_3.t simpphrases_4.t > simpphrases.t
	for i in `$(SED) 's/"\(.\).*/\1/' t2s_1tomany.t ` ; do $(GREP) $$i simpphrases.t ; done | $(DIFF) simpphrases.t - | $(GREP) '<' | $(SED) 's/< //' > t
	mv t simpphrases.t


simpphrases.notsure:simpphrases_2.t simpphrases_3.t simpphrases_4.t t2s_1tomany.t
	cat simpphrases_2.t simpphrases_3.t simpphrases_4.t > t
	for i in `$(SED) 's/"\(.\).*/\1/' t2s_1tomany.t ` ; do $(GREP) $$i t ; done | $(DIFF) t - | $(GREP) '>' | $(SED) 's/> //' > simpphrases.notsure

trad2simp1to1.t: t2s_1tomany.t t2s_1to1.t
	$(SED)  's/\(.......\).*/\1",/' t2s_1tomany.t > trad2simp1to1.t
	cat t2s_1to1.t >> trad2simp1to1.t

simp2trad1to1.t: s2t_1tomany.t s2t_1to1.t
	$(SED)  's/\(.......\).*/\1",/' s2t_1tomany.t > simp2trad1to1.t
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

simp2trad.phrases.t: trad2simp.php tradphrases.t toTW.manual
	php -f trad2simp.php | $(SED)  's/\(.*\)/"\1" => /' > tmp1
	cat tradphrases.t | $(SED)  's/\(.*\)/"\1",/' > tmp2
	paste tmp1 tmp2 > simp2trad.phrases.t
	$(SED) 's/\(.*\)\t\(.*\)/"\1"=>"\2",/' toTW.manual >> simp2trad.phrases.t

trad2simp.phrases.t: simp2trad.php simpphrases.t toCN.manual
	php -f simp2trad.php | $(SED)  's/\(.*\)/"\1" => /' > tmp1
	cat simpphrases.t | $(SED)  's/\(.*\)/"\1",/' > tmp2
	paste tmp1 tmp2 > trad2simp.phrases.t
	$(SED) 's/\(.*\)\t\(.*\)/"\1"=>"\2",/' toCN.manual >> trad2simp.phrases.t

toCN.dict: trad2simp1to1.t trad2simp.phrases.t
	cat trad2simp1to1.t | $(SED) 's/[, \t]//g' | $(SED) 's/=>/\t/' > toCN.dict
	cat trad2simp.phrases.t | $(SED) 's/[, \t]//g' | $(SED) 's/=>/\t/' >> toCN.dict

toTW.dict: simp2trad1to1.t simp2trad.phrases.t
	cat simp2trad1to1.t | $(SED) 's/[, \t]//g' | $(SED) 's/=>/\t/' > toTW.dict
	cat simp2trad.phrases.t | $(SED) 's/[, \t]//g' | $(SED) 's/=>/\t/' >> toTW.dict

toHK.dict: toHK.manual
	cat toHK.manual | $(SED) 's/ //g' | $(SED) 's/\(^.*\)\t\(.*\)/"\1"\t"\2"/' > toHK.dict

toSG.dict: toSG.manual
	cat toSG.manual | $(SED) 's/ //g' | $(SED) 's/\(^.*\)\t\(.*\)/"\1"\t"\2"/' > toSG.dict



ZhConversion.php: simp2trad1to1.t simp2trad.phrases.t trad2simp1to1.t trad2simp.phrases.t toHK.manual toSG.manual
	printf '<?php\n/**\n * Simplified/Traditional Chinese conversion tables\n' > ZhConversion.php
	printf ' *\n * Automatically generated using code and data in includes/zhtable/\n' >> ZhConversion.php
	printf ' * Do not modify directly! \n *\n * @package MediaWiki\n*/\n\n' >> ZhConversion.php
	printf '$$zh2TW=array(\n' >> ZhConversion.php
	cat simp2trad1to1.t >> ZhConversion.php
	echo >> ZhConversion.php
	cat simp2trad.phrases.t >> ZhConversion.php
	echo >> ZhConversion.php
	echo ');' >> ZhConversion.php
	echo >> ZhConversion.php
	echo >> ZhConversion.php
	printf '$$zh2CN=array(\n' >> ZhConversion.php
	cat trad2simp1to1.t >> ZhConversion.php
	echo >> ZhConversion.php
	cat trad2simp.phrases.t >> ZhConversion.php
	echo >> ZhConversion.php
	printf ');' >> ZhConversion.php
	echo >> ZhConversion.php
	echo >> ZhConversion.php
	printf '$$zh2HK=array(\n' >> ZhConversion.php
	$(SED) 's/\(.*\)\t\(.*\)/"\1" => "\2",/' toHK.manual >> ZhConversion.php
	echo >> ZhConversion.php
	printf ');' >> ZhConversion.php
	echo >> ZhConversion.php
	echo >> ZhConversion.php
	printf '$$zh2SG=array(\n' >> ZhConversion.php
	$(SED) 's/\(.*\)\t\(.*\)/"\1" => "\2",/' toSG.manual >> ZhConversion.php
	echo >> ZhConversion.php
	printf ');' >> ZhConversion.php
	echo >> ZhConversion.php
	printf '?>' >> ZhConversion.php


clean: cleantmp cleandl

cleantmp:
	# Stuff unpacked from the files fetched by wget
	rm -f \
		Unihan.txt \
		EZ.txt.in \
		phrase_lib.txt \
		tsi.src
	# Temporary files and other trash
	rm -f ZhConversion.php tmp1 tmp2 tmp3 t3 *.t trad2simp.php simp2trad.php *.dict printutf8 *~ \
		simpphrases.notsure tradphrases.notsure wordlist

cleandl:
	rm -f \
		Unihan.zip \
		scim-tables-0.5.1.tar.gz \
		scim-pinyin-0.5.0.tar.gz \
		libtabe-0.2.3.tgz

