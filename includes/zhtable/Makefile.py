# @author Philip
# You should run this script UNDER python 3000.
import tarfile, zipfile
import os, re, shutil, urllib.request

# DEFINE
SF_MIRROR = 'easynews'
SCIM_TABLES_VER = '0.5.9'
SCIM_PINYIN_VER = '0.5.91'
LIBTABE_VER = '0.2.3'
# END OF DEFINE

def GetFileFromURL( url, dest ):
    if os.path.isfile(dest):
        print( 'File %s up to date.' % dest )
        return
    print( 'Downloading from [%s] ...' % url )
    urllib.request.urlretrieve( url, dest )
    print( 'Download complete.\n' )
    return

def GetFileFromZip( path ):
    print( 'Extracting files from %s ...' % path )
    zipfile.ZipFile(path).extractall()
    return

def GetFileFromTar( path, member, rename ):
    print( 'Extracting %s from %s ...' % (rename, path) )
    tarfile.open(path, 'r:gz').extract(member)
    shutil.move(member, rename)
    tree_rmv = member.split('/')[0]
    shutil.rmtree(tree_rmv)
    return

def ReadBIG5File( dest ):
    print( 'Reading and decoding %s ...' % dest )
    f1 = open( dest, 'r', encoding='big5hkscs', errors='replace' )
    text = f1.read()
    text = text.replace( '\ufffd', '\n' )
    f1.close()
    f2 = open( dest, 'w', encoding='utf8' )
    f2.write(text)
    f2.close()
    return text

def ReadFile( dest ):
    print( 'Reading and decoding %s ...' % dest )
    f = open( dest, 'r', encoding='utf8' )
    ret = f.read()
    f.close()
    return ret

def ReadUnihanFile( dest ):
    print( 'Reading and decoding %s ...' % dest )
    f = open( dest, 'r', encoding='utf8' )
    t2s_code = []
    s2t_code = []
    while True:
        line = f.readline()
        if line:
            if line.startswith('#'):
                continue
            elif not line.find('kSimplifiedVariant') == -1:
                temp = line.split('kSimplifiedVariant')
                t2s_code.append( ( temp[0].strip(), temp[1].strip() ) )
            elif not line.find('kTraditionalVariant') == -1:
                temp = line.split('kTraditionalVariant')
                s2t_code.append( ( temp[0].strip(), temp[1].strip() ) )
        else:
            break
    f.close()
    return ( t2s_code, s2t_code )

def RemoveRows( text, num ):
    text = re.sub( '.*\s*', '', text, num)
    return text

def RemoveOneCharConv( text ):
    preg = re.compile('^.\s*$', re.MULTILINE)
    text = preg.sub( '', text )
    return text

def ConvertToChar( code ):
    code = code.split('<')[0]
    return chr( int( code[2:], 16 ) )

def GetDefaultTable( code_table ):
    char_table = {}
    for ( f, t ) in code_table:
        if f and t:
            from_char = ConvertToChar( f )
            to_chars = [ConvertToChar( code ) for code in t.split()]
            char_table[from_char] = to_chars
    return char_table

def GetManualTable( dest ):
    text = ReadFile( dest )
    temp1 = text.split()
    char_table = {}
    for elem in temp1:
        elem = elem.strip('|')
        if elem:
            temp2 = elem.split( '|', 1 )
            from_char = chr( int( temp2[0][2:7], 16 ) )
            to_chars = [chr( int( code[2:7], 16 ) ) for code in temp2[1].split('|')]
            char_table[from_char] = to_chars
    return char_table

def GetValidTable( src_table ):
    valid_table = {}
    for f, t in src_table.items():
        valid_table[f] = t[0]
    return valid_table

def GetToManyRules( src_table ):
    tomany_table = {}
    for f, t in src_table.items():
        for i in range(1, len(t)):
            tomany_table[t[i]] = True
    return tomany_table

def RemoveRules( dest, table ):
    text = ReadFile( dest )
    temp1 = text.split()
    for elem in temp1:
        f = ''
        t = ''
        elem = elem.strip().replace( '"', '' ).replace( '\'', '' )
        if '=>' in elem:
            if elem.startswith( '=>' ):
                t = elem.replace( '=>', '' ).strip()
            elif elem.endswith( '=>' ):
                f = elem.replace( '=>', '' ).strip()
            else:
                temp2 = elem.split( '=>' )
                f = temp2[0].strip()
                t = temp2[1].strip()
                try:
                    table.pop(f, t)
                    continue
                except:
                    continue
        else:
            f = t = elem
        if f:
            try:
                table.pop(f)
            except:
                x = 1
        if t:
            for temp_f, temp_t in table.copy().items():
                if temp_t == t:
                    table.pop(temp_f)
    return table

def DictToSortedList1( src_table ):
    return sorted( src_table.items(), key = lambda m: m[0] ) #sorted( temp_table, key = lambda m: len( m[0] ) )

def DictToSortedList2( src_table ):
    return sorted( src_table.items(), key = lambda m: m[1] )

def Converter( string, conv_table ):
    i = 0
    while i < len(string):
        for j in range(len(string) - i, 0, -1):
            f = string[i:][:j]
            t = conv_table.get( f )
            if t:
                string = string[:i] + t + string[i:][j:]
                i += len(t) - 1
                break
        i += 1
    return string

def GetDefaultWordsTable( src_wordlist, src_tomany, char_conv_table, char_reconv_table ):
    wordlist = list( set( src_wordlist ) )
    wordlist.sort( key = len, reverse = True )
    word_conv_table = {}
    word_reconv_table = {}
    while wordlist:
        conv_table = {}
        reconv_table = {}
        conv_table.update( word_conv_table )
        conv_table.update( char_conv_table )
        reconv_table.update( word_reconv_table )
        reconv_table.update( char_reconv_table )
        word = wordlist.pop()
        new_word_len = word_len = len(word)
        while new_word_len == word_len:
            rvt_test = False
            for char in word:
                rvt_test = rvt_test or src_tomany.get(char)
            test_word = Converter( word, reconv_table )
            new_word = Converter( word, conv_table )
            if not reconv_table.get( new_word ):
                if not test_word == word:
                    word_conv_table[word] = new_word
                    word_reconv_table[new_word] = word
                elif rvt_test:
                    rvt_word = Converter( new_word, reconv_table )
                    if not rvt_word == word:
                        word_conv_table[word] = new_word
                        word_reconv_table[new_word] = word
            try:
                word = wordlist.pop()
            except IndexError:
                break
            new_word_len = len(word)
    return word_reconv_table

def GetManualWordsTable( src_wordlist, conv_table ):
    src_wordlist = [items.split('#')[0].strip() for items in src_wordlist]
    wordlist = list( set( src_wordlist ) )
    wordlist.sort( key = len, reverse = True )
    reconv_table = {}
    while wordlist:
        word = wordlist.pop()
        new_word = Converter( word, conv_table )
        reconv_table[new_word] = word
    return reconv_table

def CustomRules( dest ):
    text = ReadFile( dest )
    temp = text.split()
    ret = {temp[i]: temp[i + 1] for i in range( 0, len( temp ), 2 )}
    return ret

def GetPHPArray( table ):
    lines = ['\'%s\' => \'%s\',' % (f, t) for (f, t) in table]
    #lines = ['"%s"=>"%s",' % (f, t) for (f, t) in table]
    return '\n'.join(lines)

def RemoveSameChar( src_table ):
    dst_table = {}
    for f, t in src_table.items():
        if not f == t:
            dst_table[f] = t
    return dst_table

def main():
    #Get Unihan.zip:
    url  = 'ftp://ftp.unicode.org/Public/UNIDATA/Unihan.zip'
    han_dest = 'Unihan.zip'
    GetFileFromURL( url, han_dest )
    
    # Get scim-tables-$(SCIM_TABLES_VER).tar.gz:
    url  = 'http://%s.dl.sourceforge.net/sourceforge/scim/scim-tables-%s.tar.gz' % ( SF_MIRROR, SCIM_TABLES_VER )
    tbe_dest = 'scim-tables-%s.tar.gz' % SCIM_TABLES_VER
    GetFileFromURL( url, tbe_dest )
    
    # Get scim-pinyin-$(SCIM_PINYIN_VER).tar.gz:
    url  = 'http://%s.dl.sourceforge.net/sourceforge/scim/scim-pinyin-%s.tar.gz' % ( SF_MIRROR, SCIM_PINYIN_VER )
    pyn_dest = 'scim-pinyin-%s.tar.gz' % SCIM_PINYIN_VER
    GetFileFromURL( url, pyn_dest )
    
    # Get libtabe-$(LIBTABE_VER).tgz:
    url  = 'http://%s.dl.sourceforge.net/sourceforge/libtabe/libtabe-%s.tgz' % ( SF_MIRROR, LIBTABE_VER )
    lbt_dest = 'libtabe-%s.tgz' % LIBTABE_VER
    GetFileFromURL( url, lbt_dest )
    
    # Extract the file from a comressed files
    
    # Unihan.txt Simp. & Trad
    GetFileFromZip( han_dest )
    
    # Make word lists
    t_wordlist = []
    s_wordlist = []
    
    # EZ.txt.in Trad
    src = 'scim-tables-%s/tables/zh/EZ-Big.txt.in' % SCIM_TABLES_VER
    dst = 'EZ.txt.in'
    GetFileFromTar( tbe_dest, src, dst )
    text = ReadFile( dst )
    text = text.split( 'BEGIN_TABLE' )[1].strip()
    text = text.split( 'END_TABLE' )[0].strip()
    text = re.sub( '.*\t', '', text )
    text = RemoveOneCharConv( text )
    t_wordlist.extend( text.split() )
    
    # Wubi.txt.in Simp
    src = 'scim-tables-%s/tables/zh/Wubi.txt.in' % SCIM_TABLES_VER
    dst = 'Wubi.txt.in'
    GetFileFromTar( tbe_dest, src, dst )
    text = ReadFile( dst )
    text = text.split( 'BEGIN_TABLE' )[1].strip()
    text = text.split( 'END_TABLE' )[0].strip()
    text = re.sub( '.*\t(.*?)\t\d*', '\g<1>', text )
    text = RemoveOneCharConv( text )
    s_wordlist.extend( text.split() )
    
    # Ziranma.txt.in Simp
    src = 'scim-tables-%s/tables/zh/Ziranma.txt.in' % SCIM_TABLES_VER
    dst = 'Ziranma.txt.in'
    GetFileFromTar( tbe_dest, src, dst )
    text = ReadFile( dst )
    text = text.split( 'BEGIN_TABLE' )[1].strip()
    text = text.split( 'END_TABLE' )[0].strip()
    text = re.sub( '.*\t(.*?)\t\d*', '\g<1>', text )
    text = RemoveOneCharConv( text )
    s_wordlist.extend( text.split() )
    
    # phrase_lib.txt Simp
    src = 'scim-pinyin-%s/data/phrase_lib.txt' % SCIM_PINYIN_VER
    dst = 'phrase_lib.txt'
    GetFileFromTar( pyn_dest, src, dst )
    text = ReadFile( 'phrase_lib.txt' )
    text = re.sub( '(.*)\t\d\d*.*', '\g<1>', text)
    text = RemoveRows( text, 5 )
    text = RemoveOneCharConv( text )
    s_wordlist.extend( text.split() )
    
    # tsi.src Trad
    src = 'libtabe/tsi-src/tsi.src'
    dst = 'tsi.src'
    GetFileFromTar( lbt_dest, src, dst )
    text = ReadBIG5File( 'tsi.src' )
    text = re.sub( ' \d.*', '', text.replace('# ', ''))
    text = RemoveOneCharConv( text )
    t_wordlist.extend( text.split() )
    
    # remove duplicate elements
    t_wordlist = list( set( t_wordlist ) )
    s_wordlist = list( set( s_wordlist ) )
    
    # simpphrases_exclude.manual Simp
    text = ReadFile( 'simpphrases_exclude.manual' )
    temp = text.split()
    s_string = '\n'.join( s_wordlist )
    for elem in temp:
        s_string = re.sub( '.*%s.*\n' % elem, '', s_string )
    s_wordlist = s_string.split('\n')
    
    # tradphrases_exclude.manual Trad
    text = ReadFile( 'tradphrases_exclude.manual' )
    temp = text.split()
    t_string = '\n'.join( t_wordlist )
    for elem in temp:
        t_string = re.sub( '.*%s.*\n' % elem, '', t_string )
    t_wordlist = t_string.split('\n')
    
    # Make char to char convertion table
    # Unihan.txt, dict t2s_code, s2t_code = { 'U+XXXX': 'U+YYYY( U+ZZZZ) ... ', ... }
    ( t2s_code, s2t_code ) = ReadUnihanFile( 'Unihan.txt' )
    # dict t2s_1tomany = { '\uXXXX': '\uYYYY\uZZZZ ... ', ... }
    t2s_1tomany = {}
    t2s_1tomany.update( GetDefaultTable( t2s_code ) )
    t2s_1tomany.update( GetManualTable( 'trad2simp.manual' ) )
    # dict s2t_1tomany
    s2t_1tomany = {}
    s2t_1tomany.update( GetDefaultTable( s2t_code ) )
    s2t_1tomany.update( GetManualTable( 'simp2trad.manual' ) )
    # dict t2s_1to1 = { '\uXXXX': '\uYYYY', ... }; t2s_trans = { 'ddddd': '', ... }
    t2s_1to1 = GetValidTable( t2s_1tomany )
    s_tomany = GetToManyRules( t2s_1tomany )
    # dict s2t_1to1; s2t_trans
    s2t_1to1 = GetValidTable( s2t_1tomany )
    t_tomany = GetToManyRules( s2t_1tomany )
    # remove noconvert rules
    t2s_1to1 = RemoveRules( 'trad2simp_noconvert.manual', t2s_1to1 )
    s2t_1to1 = RemoveRules( 'simp2trad_noconvert.manual', s2t_1to1 )
    
    # Make word to word convertion table
    t2s_1to1_supp = t2s_1to1.copy()
    s2t_1to1_supp = s2t_1to1.copy()
    # trad2simp_supp_set.manual
    t2s_1to1_supp.update( CustomRules( 'trad2simp_supp_set.manual' ) )
    # simp2trad_supp_set.manual
    s2t_1to1_supp.update( CustomRules( 'simp2trad_supp_set.manual' ) )
    # simpphrases.manual
    text = ReadFile( 'simpphrases.manual' )
    s_wordlist_manual = text.split('\n')
    t2s_word2word_manual = GetManualWordsTable(s_wordlist_manual, s2t_1to1_supp)
    t2s_word2word_manual.update( CustomRules( 'toSimp.manual' ) )
    # tradphrases.manual
    text = ReadFile( 'tradphrases.manual' )
    t_wordlist_manual = text.split('\n')
    s2t_word2word_manual = GetManualWordsTable(t_wordlist_manual, t2s_1to1_supp)
    s2t_word2word_manual.update( CustomRules( 'toTrad.manual' ) )
    # t2s_word2word
    s2t_supp = s2t_1to1_supp.copy()
    s2t_supp.update( s2t_word2word_manual )
    t2s_supp = t2s_1to1_supp.copy()
    t2s_supp.update( t2s_word2word_manual )
    t2s_word2word = GetDefaultWordsTable( s_wordlist, s_tomany, s2t_1to1_supp, t2s_supp )
    ## toSimp.manual
    t2s_word2word.update( t2s_word2word_manual )
    # s2t_word2word
    s2t_word2word = GetDefaultWordsTable( t_wordlist, t_tomany, t2s_1to1_supp, s2t_supp )
    ## toTrad.manual
    s2t_word2word.update( s2t_word2word_manual )
    
    # Final tables
    # sorted list toHans
    t2s_1to1 = RemoveSameChar( t2s_1to1 )
    s2t_1to1 = RemoveSameChar( s2t_1to1 )
    toHans = DictToSortedList1( t2s_1to1 ) + DictToSortedList2( t2s_word2word )
    # sorted list toHant
    toHant = DictToSortedList1( s2t_1to1 ) + DictToSortedList2( s2t_word2word )
    # sorted list toCN
    toCN = DictToSortedList2( CustomRules( 'toCN.manual' ) )
    # sorted list toHK
    toHK = DictToSortedList2( CustomRules( 'toHK.manual' ) )
    # sorted list toSG
    toSG = DictToSortedList2( CustomRules( 'toSG.manual' ) )
    # sorted list toTW
    toTW = DictToSortedList2( CustomRules( 'toTW.manual' ) )
    
    # Get PHP Array
    php = '''<?php
/**
 * Simplified / Traditional Chinese conversion tables
 *
 * Automatically generated using code and data in includes/zhtable/
 * Do not modify directly!
 */

$zh2Hant = array(\n'''
    php += GetPHPArray( toHant )
    php += '\n);\n\n$zh2Hans = array(\n'
    php += GetPHPArray( toHans )
    php += '\n);\n\n$zh2TW = array(\n'
    php += GetPHPArray( toTW )
    php += '\n);\n\n$zh2HK = array(\n'
    php += GetPHPArray( toHK )
    php += '\n);\n\n$zh2CN = array(\n'
    php += GetPHPArray( toCN )
    php += '\n);\n\n$zh2SG = array(\n'
    php += GetPHPArray( toSG )
    php += '\n);'
    
    f = open( 'ZhConversion.php', 'w', encoding = 'utf8' )
    print ('Writing ZhConversion.php ... ')
    f.write( php )
    f.close()

if __name__ == '__main__':
    main()