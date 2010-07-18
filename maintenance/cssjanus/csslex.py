#!/usr/bin/python
#
# Copyright 2007 Google Inc. All Rights Reserved.

"""CSS Lexical Grammar rules.

CSS lexical grammar from http://www.w3.org/TR/CSS21/grammar.html
"""

__author__ = ['elsigh@google.com (Lindsey Simon)',
              'msamuel@google.com (Mike Samuel)']

# public symbols
__all__ = [ "NEWLINE", "HEX", "NON_ASCII", "UNICODE", "ESCAPE", "NMSTART", "NMCHAR", "STRING1", "STRING2", "IDENT", "NAME", "HASH", "NUM", "STRING", "URL", "SPACE", "WHITESPACE", "COMMENT", "QUANTITY", "PUNC" ]

# The comments below are mostly copied verbatim from the grammar.

# "@import"               {return IMPORT_SYM;}
# "@page"                 {return PAGE_SYM;}
# "@media"                {return MEDIA_SYM;}
# "@charset"              {return CHARSET_SYM;}
KEYWORD = r'(?:\@(?:import|page|media|charset))'

# nl                      \n|\r\n|\r|\f ; a newline
NEWLINE = r'\n|\r\n|\r|\f'

# h                       [0-9a-f]      ; a hexadecimal digit
HEX = r'[0-9a-f]'

# nonascii                [\200-\377]
NON_ASCII = r'[\200-\377]'

# unicode                 \\{h}{1,6}(\r\n|[ \t\r\n\f])?
UNICODE = r'(?:(?:\\' + HEX + r'{1,6})(?:\r\n|[ \t\r\n\f])?)'

# escape                  {unicode}|\\[^\r\n\f0-9a-f]
ESCAPE = r'(?:' + UNICODE + r'|\\[^\r\n\f0-9a-f])'

# nmstart                 [_a-z]|{nonascii}|{escape}
NMSTART = r'(?:[_a-z]|' + NON_ASCII + r'|' + ESCAPE + r')'

# nmchar                  [_a-z0-9-]|{nonascii}|{escape}
NMCHAR = r'(?:[_a-z0-9-]|' + NON_ASCII + r'|' + ESCAPE + r')'

# ident                   -?{nmstart}{nmchar}*
IDENT = r'-?' + NMSTART + NMCHAR + '*'

# name                    {nmchar}+
NAME = NMCHAR + r'+'

# hash
HASH = r'#' + NAME

# string1                 \"([^\n\r\f\\"]|\\{nl}|{escape})*\"  ; "string"
STRING1 = r'"(?:[^\"\\]|\\.)*"'

# string2                 \'([^\n\r\f\\']|\\{nl}|{escape})*\'  ; 'string'
STRING2 = r"'(?:[^\'\\]|\\.)*'"

# string                  {string1}|{string2}
STRING = '(?:' + STRING1 + r'|' + STRING2 + ')'

# num                     [0-9]+|[0-9]*"."[0-9]+
NUM = r'(?:[0-9]*\.[0-9]+|[0-9]+)'

# s                       [ \t\r\n\f]
SPACE = r'[ \t\r\n\f]'

# w                       {s}*
WHITESPACE = '(?:' + SPACE + r'*)'

# url special chars
URL_SPECIAL_CHARS = r'[!#$%&*-~]'

# url chars               ({url_special_chars}|{nonascii}|{escape})*
URL_CHARS = r'(?:%s|%s|%s)*' % (URL_SPECIAL_CHARS, NON_ASCII, ESCAPE)

# url
URL = r'url\(%s(%s|%s)%s\)' % (WHITESPACE, STRING, URL_CHARS, WHITESPACE)

# comments
# see http://www.w3.org/TR/CSS21/grammar.html
COMMENT = r'/\*[^*]*\*+([^/*][^*]*\*+)*/'

# {E}{M}             {return EMS;}
# {E}{X}             {return EXS;}
# {P}{X}             {return LENGTH;}
# {C}{M}             {return LENGTH;}
# {M}{M}             {return LENGTH;}
# {I}{N}             {return LENGTH;}
# {P}{T}             {return LENGTH;}
# {P}{C}             {return LENGTH;}
# {D}{E}{G}          {return ANGLE;}
# {R}{A}{D}          {return ANGLE;}
# {G}{R}{A}{D}       {return ANGLE;}
# {M}{S}             {return TIME;}
# {S}                {return TIME;}
# {H}{Z}             {return FREQ;}
# {K}{H}{Z}          {return FREQ;}
# %                  {return PERCENTAGE;}
UNIT = r'(?:em|ex|px|cm|mm|in|pt|pc|deg|rad|grad|ms|s|hz|khz|%)'

# {num}{UNIT|IDENT}                   {return NUMBER;}
QUANTITY = '%s(?:%s%s|%s)?' % (NUM, WHITESPACE, UNIT, IDENT)

# "<!--"                  {return CDO;}
# "-->"                   {return CDC;}
# "~="                    {return INCLUDES;}
# "|="                    {return DASHMATCH;}
# {w}"{"                  {return LBRACE;}
# {w}"+"                  {return PLUS;}
# {w}">"                  {return GREATER;}
# {w}","                  {return COMMA;}
PUNC =  r'<!--|-->|~=|\|=|[\{\+>,:;]'
