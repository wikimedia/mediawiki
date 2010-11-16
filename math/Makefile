.PHONY: clean all

OBJ=render_info.cmo tex.cmo texutil.cmo parser.cmo lexer.cmo texvc.cmo \
render_info.cmx tex.cmx texutil.cmx parser.cmx lexer.cmx texvc.cmx \
lexer.cmi parser.cmi render_info.cmi tex.cmi texutil.cmi texvc.cmi \
lexer.o parser.o render_info.o tex.o texutil.o texvc.o \
lexer.ml parser.ml parser.mli texvc texvc.bc texvc_test.cmo \
texvc_test.cmx texvc_test.cmi texvc_test.o texvc_test util.o \
util.cmo util.cmx util.cmi texvc_cgi.cmi texvc_cgi texvc_cgi.cmo \
render.o render.cmi render.cmo render.cmx texvc_tex.cmx \
texvc_tex.o texvc_tex.cmi texvc_tex html.cmi html.cmo html.cmx \
html.o mathml.cmi mathml.cmo mathml.cmx mathml.o
CGIPATH=-I /usr/lib/ocaml/cgi -I /usr/lib/ocaml/netstring -I /usr/lib/ocaml/pcre

COMMON_NATIVE_OBJ  =util.cmx parser.cmx html.cmx mathml.cmx texutil.cmx lexer.cmx
COMMON_BYTECODE_OBJ=util.cmo parser.cmo html.cmo mathml.cmo texutil.cmo lexer.cmo

all: texvc texvc_test texvc_tex
cgi: texvc_cgi.cmo texvc_cgi
clean:
	rm -f $(OBJ)

# Native versions
texvc: $(COMMON_NATIVE_OBJ) render.cmx texvc.cmx
	ocamlopt -o $@ unix.cmxa $^
texvc_test: $(COMMON_NATIVE_OBJ) lexer.cmx texvc_test.cmx
	ocamlopt -o $@ $^
texvc_tex: $(COMMON_NATIVE_OBJ) texvc_tex.cmx
	ocamlopt -o $@ $^

# Bytecode version
texvc.bc: $(COMMON_BYTECODE_OBJ) render.cmo texvc.cmo
	ocamlc -o $@ unix.cma $^

# CGI related targets:
texvc_cgi.cmo: texvc_cgi.ml
	ocamlc -c $(CGIPATH) $<
texvc_cgi: util.cmo parser.cmo texutil.cmo render.cmo lexer.cmo texvc_cgi.cmo
	ocamlc -o $@ unix.cma $(CGIPATH) pcre.cma netstring.cma cgi.cma $^
	chmod g-w $@

#
# Pattern rules
#

#  .ml source  .mli interface
#  .cmi compiled interface
#  .cmo object       .cma library object
#  .cmx object file  .cmxa library object file
%.ml: %.mll
	ocamllex $<
%.mli %.ml: %.mly
	ocamlyacc $<
%.cmo: %.ml
	ocamlc -c $<
%.cmx: %.ml
	ocamlopt -c $<
%.cmi: %.mli
	ocamlc -c $<

# Various dependencies

html.cmo: render_info.cmi tex.cmi util.cmo html.cmi 
html.cmx: render_info.cmi tex.cmi util.cmx html.cmi 
html.cmi: tex.cmi 
lexer.cmo: parser.cmi render_info.cmi tex.cmi texutil.cmi 
lexer.cmx: parser.cmx render_info.cmi tex.cmi texutil.cmx 
mathml.cmo: tex.cmi mathml.cmi 
mathml.cmx: tex.cmi mathml.cmi 
mathml.cmi: tex.cmi 
parser.cmo: render_info.cmi tex.cmi parser.cmi 
parser.cmx: render_info.cmi tex.cmi parser.cmi 
parser.cmi: render_info.cmi tex.cmi 
render.cmo: texutil.cmi util.cmo 
render.cmx: texutil.cmx util.cmx 
tex.cmi: render_info.cmi 
texutil.cmo: html.cmi parser.cmi render_info.cmi tex.cmi util.cmo texutil.cmi 
texutil.cmx: html.cmx parser.cmx render_info.cmi tex.cmi util.cmx texutil.cmi 
texutil.cmi: parser.cmi tex.cmi 
texvc.cmo: html.cmi lexer.cmo mathml.cmi parser.cmi render.cmo texutil.cmi util.cmo 
texvc.cmx: html.cmx lexer.cmx mathml.cmx parser.cmx render.cmx texutil.cmx util.cmx 
texvc_cgi.cmo: lexer.cmo parser.cmi render.cmo texutil.cmi util.cmo 
texvc_cgi.cmx: lexer.cmx parser.cmx render.cmx texutil.cmx util.cmx 
texvc_test.cmo: html.cmi lexer.cmo parser.cmi texutil.cmi util.cmo 
texvc_test.cmx: html.cmx lexer.cmx parser.cmx texutil.cmx util.cmx 
texvc_tex.cmo: lexer.cmo parser.cmi texutil.cmi util.cmo 
texvc_tex.cmx: lexer.cmx parser.cmx texutil.cmx util.cmx 
