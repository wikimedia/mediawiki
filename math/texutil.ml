open Parser
open Render_info
open Tex
open Util

let tex_part = function
    HTMLABLE (_,t,_) -> t
  | HTMLABLEM (_,t,_) -> t
  | HTMLABLEC (_,t,_) -> t
  | MHTMLABLEC (_,t,_,_,_) -> t
  | HTMLABLE_BIG (t,_) -> t
  | TEX_ONLY t -> t
let rec render_tex = function
    TEX_FQ (a,b,c) -> (render_tex a) ^ "_{" ^ (render_tex  b) ^ "}^{" ^ (render_tex  c) ^ "}"
  | TEX_DQ (a,b) -> (render_tex a) ^ "_{" ^ (render_tex  b) ^ "}"
  | TEX_UQ (a,b) -> (render_tex a) ^ "^{" ^ (render_tex  b) ^ "}"
  | TEX_LITERAL s -> tex_part s
  | TEX_FUN1 (f,a) -> "{" ^ f ^ " " ^ (render_tex a) ^ "}"
  | TEX_FUN1hl (f,_,a) -> "{" ^ f ^ " " ^ (render_tex a) ^ "}"
  | TEX_FUN1hf (f,_,a) -> "{" ^ f ^ " " ^ (render_tex a) ^ "}"
  | TEX_DECLh (f,_,a) -> "{" ^ f ^ "{" ^ (mapjoin render_tex a) ^ "}}"
  | TEX_FUN2 (f,a,b) -> "{" ^ f ^ " " ^ (render_tex a) ^ (render_tex b) ^ "}"
  | TEX_FUN2h (f,_,a,b) -> "{" ^ f ^ " " ^ (render_tex a) ^ (render_tex b) ^ "}"
  | TEX_FUN2sq (f,a,b) -> "{" ^ f ^ "[ " ^ (render_tex a) ^ "]" ^ (render_tex b) ^ "}"
  | TEX_CURLY (tl) -> "{" ^ (mapjoin render_tex tl) ^ "}"
  | TEX_INFIX (s,ll,rl) -> "{" ^ (mapjoin render_tex ll) ^ " " ^ s ^ "" ^ (mapjoin render_tex rl) ^ "}"
  | TEX_INFIXh (s,_,ll,rl) -> "{" ^ (mapjoin render_tex ll) ^ " " ^ s ^ "" ^ (mapjoin render_tex rl) ^ "}"
  | TEX_BOX (bt,s) -> "{"^bt^"{" ^ s ^ "}}"
  | TEX_BIG (bt,d) -> "{"^bt^(tex_part d)^"}"
  | TEX_MATRIX (t,rows) -> "{\\begin{"^t^"}"^(mapjoine "\\\\" (mapjoine "&" (mapjoin render_tex)) rows)^"\\end{"^t^"}}"
  | TEX_LR (l,r,tl) -> "\\left "^(tex_part l)^(mapjoin render_tex tl)^"\\right "^(tex_part r)

(* Dynamic loading*)
type encoding_t = LATIN1 | LATIN2 | UTF8

let modules_ams = ref false
let modules_nonascii = ref false
let modules_encoding = ref UTF8
let modules_color = ref false

let tex_use_ams ()     = modules_ams := true
let tex_use_nonascii () = modules_nonascii := true
let tex_use_color ()  = modules_color := true
let tex_mod_reset ()   = (modules_ams := false; modules_nonascii := false; modules_encoding := UTF8; modules_color := false)

let get_encoding = function
    UTF8 -> "\\usepackage{ucs}\n\\usepackage[utf8]{inputenc}\n"
  | LATIN1 -> "\\usepackage[latin1]{inputenc}\n"
  | LATIN2 -> "\\usepackage[latin2]{inputenc}\n"

let get_preface ()  = "\\nonstopmode\n\\documentclass[12pt]{article}\n" ^
              (if !modules_nonascii then get_encoding !modules_encoding else "") ^
              (if !modules_ams then "\\usepackage{amsmath}\n\\usepackage{amsfonts}\n\\usepackage{amssymb}\n" else "") ^
              (if !modules_color then "\\usepackage[dvips,usenames]{color}\n" else "") ^
              "\\pagestyle{empty}\n\\begin{document}\n$$\n"
let get_footer  ()  = "\n$$\n\\end{document}\n"

let set_encoding = function
    "ISO-8859-1" -> modules_encoding := LATIN1
  | "iso-8859-1" -> modules_encoding := LATIN1
  | "ISO-8859-2" -> modules_encoding := LATIN2
  | _ -> modules_encoding := UTF8

(* Turn that into hash table lookup *)
exception Illegal_tex_function of string

let find = function
      "\\alpha"            -> LITERAL (HTMLABLEC (FONT_UF,  "\\alpha ", "&alpha;"))
    | "\\Alpha"            -> LITERAL (HTMLABLEC (FONT_RTI, "A", "&Alpha;"))
    | "\\beta"             -> LITERAL (HTMLABLEC (FONT_UF,  "\\beta ",  "&beta;"))
    | "\\Beta"             -> LITERAL (HTMLABLEC (FONT_RTI, "B",  "&Beta;"))
    | "\\gamma"            -> LITERAL (HTMLABLEC (FONT_UF,  "\\gamma ", "&gamma;"))
    | "\\Gamma"            -> LITERAL (HTMLABLEC (FONT_RTI, "\\Gamma ", "&Gamma;"))
    | "\\delta"            -> LITERAL (HTMLABLEC (FONT_UF,  "\\delta ", "&delta;"))
    | "\\Delta"            -> LITERAL (HTMLABLEC (FONT_RTI, "\\Delta ", "&Delta;"))
    | "\\epsilon"          -> LITERAL (HTMLABLEC (FONT_UF,  "\\epsilon ", "&epsilon;"))
    | "\\Epsilon"          -> LITERAL (HTMLABLEC (FONT_RTI, "E", "&Epsilon;"))
    | "\\varepsilon"       -> LITERAL (TEX_ONLY "\\varepsilon ")
    | "\\zeta"             -> LITERAL (HTMLABLEC (FONT_UF,  "\\zeta ", "&zeta;"))
    | "\\Zeta"             -> LITERAL (HTMLABLEC (FONT_RTI, "Z", "&Zeta;"))
    | "\\eta"              -> LITERAL (HTMLABLEC (FONT_UF,  "\\eta ", "&eta;"))
    | "\\Eta"              -> LITERAL (HTMLABLEC (FONT_RTI, "H", "&Eta;"))
    | "\\theta"            -> LITERAL (HTMLABLEC (FONT_UF,  "\\theta ", "&theta;"))
    | "\\Theta"            -> LITERAL (HTMLABLEC (FONT_RTI, "\\Theta ", "&Theta;"))
    | "\\vartheta"         -> LITERAL (HTMLABLE  (FONT_UF,  "\\vartheta ", "&thetasym;"))
    | "\\thetasym"         -> LITERAL (HTMLABLE  (FONT_UF,  "\\vartheta ", "&thetasym;"))
    | "\\iota"             -> LITERAL (HTMLABLEC (FONT_UF,  "\\iota ", "&iota;"))
    | "\\Iota"             -> LITERAL (HTMLABLEC (FONT_RTI, "I", "&Iota;"))
    | "\\kappa"            -> LITERAL (HTMLABLEC (FONT_UF,  "\\kappa ", "&kappa;"))
    | "\\Kappa"            -> LITERAL (HTMLABLEC (FONT_RTI, "K", "&Kappa;"))
    | "\\lambda"           -> LITERAL (HTMLABLEC (FONT_UF,  "\\lambda ", "&lambda;"))
    | "\\Lambda"           -> LITERAL (HTMLABLEC (FONT_RTI, "\\Lambda ", "&Lambda;"))
    | "\\mu"               -> LITERAL (HTMLABLEC (FONT_UF,  "\\mu ", "&mu;"))
    | "\\Mu"               -> LITERAL (HTMLABLEC (FONT_RTI, "M", "&Mu;"))
    | "\\nu"               -> LITERAL (HTMLABLEC (FONT_UF,  "\\nu ", "&nu;"))
    | "\\Nu"               -> LITERAL (HTMLABLEC (FONT_RTI, "N", "&Nu;"))
    | "\\pi"               -> LITERAL (HTMLABLEC (FONT_UF,  "\\pi ", "&pi;"))
    | "\\Pi"               -> LITERAL (HTMLABLEC (FONT_RTI, "\\Pi ", "&Pi;"))
    | "\\varpi"            -> LITERAL (TEX_ONLY "\\varpi ")
    | "\\rho"              -> LITERAL (HTMLABLEC (FONT_UF,  "\\rho ", "&rho;"))
    | "\\Rho"              -> LITERAL (HTMLABLEC (FONT_RTI, "P", "&Rho;"))
    | "\\varrho"           -> LITERAL (TEX_ONLY "\\varrho ")
    | "\\sim"              -> LITERAL (HTMLABLEC (FONT_UF,  "\\sim ", "&tilde;"))
    | "\\sigma"            -> LITERAL (HTMLABLEC (FONT_UF,  "\\sigma ", "&sigma;"))
    | "\\Sigma"            -> LITERAL (HTMLABLEC (FONT_RTI, "\\Sigma ", "&Sigma;"))
    | "\\varsigma"         -> LITERAL (TEX_ONLY "\\varsigma ")
    | "\\tau"              -> LITERAL (HTMLABLEC (FONT_UF,  "\\tau ", "&tau;"))
    | "\\Tau"              -> LITERAL (HTMLABLEC (FONT_RTI, "T", "&Tau;"))
    | "\\upsilon"          -> LITERAL (HTMLABLEC (FONT_UF,  "\\upsilon ", "&upsilon;"))
    | "\\Upsilon"          -> LITERAL (HTMLABLEC (FONT_RTI, "\\Upsilon ", "&Upsilon;"))
    | "\\phi"              -> LITERAL (HTMLABLEC (FONT_UF,  "\\phi ", "&phi;"))
    | "\\Phi"              -> LITERAL (HTMLABLEC (FONT_RTI, "\\Phi ", "&Phi;"))
    | "\\varphi"           -> LITERAL (TEX_ONLY "\\varphi ")
    | "\\chi"              -> LITERAL (HTMLABLEC (FONT_UF,  "\\chi ", "&chi;"))
    | "\\Chi"              -> LITERAL (HTMLABLEC (FONT_RTI, "X", "&Chi;"))
    | "\\psi"              -> LITERAL (HTMLABLEC (FONT_UF,  "\\psi ", "&psi;"))
    | "\\Psi"              -> LITERAL (HTMLABLEC (FONT_RTI, "\\Psi ", "&Psi;"))
    | "\\omega"            -> LITERAL (HTMLABLEC (FONT_UF,  "\\omega ", "&omega;"))
    | "\\Omega"            -> LITERAL (HTMLABLEC (FONT_RTI, "\\Omega ", "&Omega;"))
    | "\\xi"               -> LITERAL (HTMLABLEC (FONT_UF,  "\\xi ", "&xi;"))
    | "\\Xi"               -> LITERAL (HTMLABLEC (FONT_RTI, "\\Xi ", "&Xi;"))
    | "\\aleph"            -> LITERAL (HTMLABLE  (FONT_UF,  "\\aleph ", "&alefsym;"))
    | "\\alef"             -> LITERAL (HTMLABLE  (FONT_UF,  "\\aleph ", "&alefsym;"))
    | "\\alefsym"          -> LITERAL (HTMLABLE  (FONT_UF,  "\\aleph ", "&alefsym;"))
    | "\\larr"             -> LITERAL (HTMLABLEM (FONT_UF,  "\\leftarrow ", "&larr;"))
    | "\\leftarrow"        -> LITERAL (HTMLABLEM (FONT_UF,  "\\leftarrow ", "&larr;"))
    | "\\rarr"             -> LITERAL (HTMLABLEM (FONT_UF,  "\\rightarrow ", "&rarr;"))
    | "\\to"               -> LITERAL (HTMLABLEM (FONT_UF,  "\\to ", "&rarr;"))
    | "\\gets"             -> LITERAL (HTMLABLEM (FONT_UF,  "\\gets ", "&larr;"))
    | "\\rightarrow"       -> LITERAL (HTMLABLEM (FONT_UF,  "\\rightarrow ", "&rarr;"))
    | "\\longleftarrow"    -> LITERAL (HTMLABLE  (FONT_UF,  "\\longleftarrow ", "&larr;"))
    | "\\longrightarrow"   -> LITERAL (HTMLABLE  (FONT_UF,  "\\longrightarrow ", "&rarr;"))
    | "\\Larr"             -> LITERAL (HTMLABLE  (FONT_UF,  "\\Leftarrow ", "&lArr;"))
    | "\\lArr"             -> LITERAL (HTMLABLE  (FONT_UF,  "\\Leftarrow ", "&lArr;"))
    | "\\Leftarrow"        -> LITERAL (HTMLABLE  (FONT_UF,  "\\Leftarrow ", "&lArr;"))
    | "\\Rarr"             -> LITERAL (HTMLABLE  (FONT_UF,  "\\Rightarrow ", "&rArr;"))
    | "\\rArr"             -> LITERAL (HTMLABLE  (FONT_UF,  "\\Rightarrow ", "&rArr;"))
    | "\\Rightarrow"       -> LITERAL (HTMLABLEM (FONT_UF,  "\\Rightarrow ", "&rArr;"))
    | "\\mapsto"           -> LITERAL (HTMLABLE  (FONT_UF,  "\\mapsto ", "&rarr;"))
    | "\\longmapsto"       -> LITERAL (HTMLABLE  (FONT_UF,  "\\longmapsto ", "&rarr;"))
    | "\\Longleftarrow"    -> LITERAL (HTMLABLE  (FONT_UF,  "\\Longleftarrow ", "&lArr;"))
    | "\\Longrightarrow"   -> LITERAL (HTMLABLE  (FONT_UF,  "\\Longrightarrow ", "&rArr;"))
    | "\\uarr"             -> DELIMITER (HTMLABLEM (FONT_UF,  "\\uparrow ", "&uarr;"))
    | "\\uparrow"          -> DELIMITER (HTMLABLEM (FONT_UF,  "\\uparrow ", "&uarr;"))
    | "\\uArr"             -> DELIMITER (HTMLABLE  (FONT_UF,  "\\Uparrow ", "&uArr;"))
    | "\\Uarr"             -> DELIMITER (HTMLABLE  (FONT_UF,  "\\Uparrow ", "&uArr;"))
    | "\\Uparrow"          -> DELIMITER (HTMLABLE  (FONT_UF,  "\\Uparrow ", "&uArr;"))
    | "\\darr"             -> DELIMITER (HTMLABLEM (FONT_UF,  "\\downarrow ", "&darr;"))
    | "\\downarrow"        -> DELIMITER (HTMLABLEM (FONT_UF,  "\\downarrow ", "&darr;"))
    | "\\dArr"             -> DELIMITER (HTMLABLE  (FONT_UF,  "\\Downarrow ", "&dArr;"))
    | "\\Darr"             -> DELIMITER (HTMLABLE  (FONT_UF,  "\\Downarrow ", "&dArr;"))
    | "\\Downarrow"        -> DELIMITER (HTMLABLE  (FONT_UF,  "\\Downarrow ", "&dArr;"))
    | "\\updownarrow"      -> DELIMITER (TEX_ONLY "\\updownarrow ")
    | "\\Updownarrow"      -> DELIMITER (TEX_ONLY "\\Updownarrow ")
    | "\\ulcorner"         -> (tex_use_ams (); DELIMITER (TEX_ONLY "\\ulcorner "))
    | "\\urcorner"         -> (tex_use_ams (); DELIMITER (TEX_ONLY "\\urcorner "))
    | "\\llcorner"         -> (tex_use_ams (); DELIMITER (TEX_ONLY "\\llcorner "))
    | "\\lrcorner"         -> (tex_use_ams (); DELIMITER (TEX_ONLY "\\lrcorner "))
    | "\\twoheadleftarrow" -> (tex_use_ams (); DELIMITER (TEX_ONLY "\\twoheadleftarrow "))
    | "\\twoheadrightarrow" -> (tex_use_ams (); DELIMITER (TEX_ONLY "\\twoheadrightarrow "))
    | "\\rightleftharpoons" -> DELIMITER (TEX_ONLY "\\rightleftharpoons ")
    | "\\leftrightarrow"   -> LITERAL (HTMLABLE  (FONT_UF,  "\\leftrightarrow ", "&harr;"))
    | "\\lrarr"            -> LITERAL (HTMLABLE  (FONT_UF,  "\\leftrightarrow ", "&harr;"))
    | "\\harr"             -> LITERAL (HTMLABLE  (FONT_UF,  "\\leftrightarrow ", "&harr;"))
    | "\\Leftrightarrow"   -> LITERAL (HTMLABLE  (FONT_UF,  "\\Leftrightarrow ", "&hArr;"))
    | "\\Lrarr"            -> LITERAL (HTMLABLE  (FONT_UF,  "\\Leftrightarrow ", "&hArr;"))
    | "\\Harr"             -> LITERAL (HTMLABLE  (FONT_UF,  "\\Leftrightarrow ", "&hArr;"))
    | "\\lrArr"            -> LITERAL (HTMLABLE  (FONT_UF,  "\\Leftrightarrow ", "&hArr;"))
    | "\\hAar"             -> LITERAL (HTMLABLE  (FONT_UF,  "\\Leftrightarrow ", "&hArr;"))
    | "\\Longleftrightarrow"->LITERAL (HTMLABLE  (FONT_UF,  "\\Longleftrightarrow ", "&harr;"))
    | "\\iff"              -> LITERAL (HTMLABLE  (FONT_UF,  "\\iff ", "&harr;"))
    | "\\ll"		   -> LITERAL (TEX_ONLY "\\ll ")
    | "\\gg"		   -> LITERAL (TEX_ONLY "\\gg ")
    | "\\div"		   -> LITERAL (TEX_ONLY "\\div ")
    | "\\searrow"          -> LITERAL (TEX_ONLY "\\searrow ")
    | "\\nearrow"          -> LITERAL (TEX_ONLY "\\nearrow ")
    | "\\swarrow"          -> LITERAL (TEX_ONLY "\\swarrow ")
    | "\\nwarrow"          -> LITERAL (TEX_ONLY "\\nwarrow ")
    | "\\simeq"            -> LITERAL (TEX_ONLY "\\simeq ")
    | "\\star"             -> LITERAL (TEX_ONLY "\\star ")
    | "\\ell"              -> LITERAL (TEX_ONLY "\\ell ")
    | "\\P"                -> LITERAL (TEX_ONLY "\\P ")
    | "\\smile"            -> LITERAL (TEX_ONLY "\\smile ")
    | "\\frown"            -> LITERAL (TEX_ONLY "\\frown ")
    | "\\bigcap"           -> LITERAL (TEX_ONLY "\\bigcap ")
    | "\\bigodot"          -> LITERAL (TEX_ONLY "\\bigodot ")
    | "\\bigcup"           -> LITERAL (TEX_ONLY "\\bigcup ")
    | "\\bigotimes"        -> LITERAL (TEX_ONLY "\\bigotimes ")
    | "\\coprod"           -> LITERAL (TEX_ONLY "\\coprod ")
    | "\\bigsqcup"         -> LITERAL (TEX_ONLY "\\bigsqcup ")
    | "\\bigoplus"         -> LITERAL (TEX_ONLY "\\bigoplus ")
    | "\\bigvee"           -> LITERAL (TEX_ONLY "\\bigvee ")
    | "\\biguplus"         -> LITERAL (TEX_ONLY "\\biguplus ")
    | "\\oint"             -> LITERAL (TEX_ONLY "\\oint ")
    | "\\bigwedge"         -> LITERAL (TEX_ONLY "\\bigwedge ")
    | "\\models"           -> LITERAL (TEX_ONLY "\\models ")
    | "\\vdash"            -> LITERAL (TEX_ONLY "\\vdash ")
    | "\\triangle"         -> LITERAL (TEX_ONLY "\\triangle ")
	| "\\bowtie"           -> LITERAL (TEX_ONLY "\\bowtie ")
    | "\\wr"               -> LITERAL (TEX_ONLY "\\wr ")
    | "\\triangleleft"     -> LITERAL (TEX_ONLY "\\triangleleft ")
    | "\\triangleright"    -> LITERAL (TEX_ONLY "\\triangleright ")
    | "\\textvisiblespace" -> LITERAL (TEX_ONLY "\\textvisiblespace ")
    | "\\ker"              -> LITERAL (TEX_ONLY "\\ker ")
    | "\\lim"              -> LITERAL (TEX_ONLY "\\lim ")
    | "\\limsup"           -> LITERAL (TEX_ONLY "\\limsup ")
    | "\\liminf"           -> LITERAL (TEX_ONLY "\\liminf ")
    | "\\sup"              -> LITERAL (TEX_ONLY "\\sup ")
    | "\\Pr"               -> LITERAL (TEX_ONLY "\\Pr ")
    | "\\hom"              -> LITERAL (TEX_ONLY "\\hom ")
    | "\\arg"              -> LITERAL (TEX_ONLY "\\arg ")
    | "\\dim"              -> LITERAL (TEX_ONLY "\\dim ")
    | "\\inf"              -> LITERAL (TEX_ONLY "\\inf ")
    | "\\circ"             -> LITERAL (TEX_ONLY "\\circ ")
    | "\\hbar"             -> LITERAL (TEX_ONLY "\\hbar ")
    | "\\imath"            -> LITERAL (TEX_ONLY "\\imath ")
    | "\\lnot"             -> LITERAL (TEX_ONLY "\\lnot ")
    | "\\hookrightarrow"   -> LITERAL (TEX_ONLY "\\hookrightarrow ")
    | "\\hookleftarrow"    -> LITERAL (TEX_ONLY "\\hookleftarrow ")
    | "\\mp"               -> LITERAL (TEX_ONLY "\\mp ")
    | "\\approx"           -> LITERAL (TEX_ONLY "\\approx ")
    | "\\propto"           -> LITERAL (TEX_ONLY "\\propto ")
    | "\\flat"             -> LITERAL (TEX_ONLY "\\flat ")
    | "\\sharp"            -> LITERAL (TEX_ONLY "\\sharp ")
    | "\\natural"          -> LITERAL (TEX_ONLY "\\natural ")
    | "\\int"              -> LITERAL (HTMLABLE_BIG ("\\int ", "&int;"))
    | "\\sum"              -> LITERAL (HTMLABLE_BIG ("\\sum ", "&sum;"))
    | "\\prod"             -> LITERAL (HTMLABLE_BIG ("\\prod ", "&prod;"))
    | "\\vdots"            -> LITERAL (TEX_ONLY "\\vdots ")
    | "\\limits"           -> LITERAL (TEX_ONLY "\\limits ")
    | "\\nolimits"         -> LITERAL (TEX_ONLY "\\nolimits ")
    | "\\top"              -> LITERAL (TEX_ONLY "\\top ")
    | "\\sin"              -> LITERAL (HTMLABLEC(FONT_UFH,"\\sin ","sin"))
    | "\\cos"              -> LITERAL (HTMLABLEC(FONT_UFH,"\\cos ","cos"))
    | "\\sinh"             -> LITERAL (HTMLABLEC(FONT_UFH,"\\sinh ","sinh"))
    | "\\cosh"             -> LITERAL (HTMLABLEC(FONT_UFH,"\\cosh ","cosh"))
    | "\\tan"              -> LITERAL (HTMLABLEC(FONT_UFH,"\\tan ","tan"))
    | "\\tanh"             -> LITERAL (HTMLABLEC(FONT_UFH,"\\tanh ","tanh"))
    | "\\sec"              -> LITERAL (HTMLABLEC(FONT_UFH,"\\sec ","sec"))
    | "\\csc"              -> LITERAL (HTMLABLEC(FONT_UFH,"\\csc ","csc"))
    | "\\arcsin"           -> LITERAL (HTMLABLEC(FONT_UFH,"\\arcsin ","arcsin"))
    | "\\arctan"           -> LITERAL (HTMLABLEC(FONT_UFH,"\\arctan ","arctan"))
    | "\\arccos"           -> (tex_use_ams (); LITERAL (HTMLABLEC(FONT_UFH,"\\mathop{\\mathrm{arccos}}","arccos")))
    | "\\arccot"           -> (tex_use_ams (); LITERAL (HTMLABLEC(FONT_UFH,"\\mathop{\\mathrm{arccot}}","arccot")))
    | "\\arcsec"           -> (tex_use_ams (); LITERAL (HTMLABLEC(FONT_UFH,"\\mathop{\\mathrm{arcsec}}","arcsec")))
    | "\\arccsc"           -> (tex_use_ams (); LITERAL (HTMLABLEC(FONT_UFH,"\\mathop{\\mathrm{arccsc}}","arccsc")))
    | "\\sgn"              -> (tex_use_ams (); LITERAL (HTMLABLEC(FONT_UFH,"\\mathop{\\mathrm{sgn}}","sgn")))
    | "\\cot"              -> LITERAL (HTMLABLEC(FONT_UFH,"\\cot ","cot"))
    | "\\coth"             -> LITERAL (HTMLABLEC(FONT_UFH,"\\coth ","coth"))
    | "\\log"              -> LITERAL (HTMLABLEC(FONT_UFH,"\\log ", "log"))
    | "\\lg"               -> LITERAL (HTMLABLEC(FONT_UFH,"\\lg ", "lg"))
    | "\\ln"               -> LITERAL (HTMLABLEC(FONT_UFH,"\\ln ", "ln"))
    | "\\exp"              -> LITERAL (HTMLABLEC(FONT_UFH,"\\exp ", "exp"))
    | "\\min"              -> LITERAL (HTMLABLEC(FONT_UFH,"\\min ", "min"))
    | "\\max"              -> LITERAL (HTMLABLEC(FONT_UFH,"\\max ", "max"))
    | "\\gcd"              -> LITERAL (HTMLABLEC(FONT_UFH,"\\gcd ", "gcd"))
    | "\\deg"              -> LITERAL (HTMLABLEC(FONT_UFH,"\\deg ", "deg"))
    | "\\det"              -> LITERAL (HTMLABLEC(FONT_UFH,"\\det ", "det"))
    | "\\bullet"           -> LITERAL (HTMLABLE (FONT_UFH, "\\bullet ", "&bull;"))
    | "\\bull"             -> LITERAL (HTMLABLE (FONT_UFH, "\\bullet ", "&bull;"))
    | "\\angle"            -> (tex_use_ams (); LITERAL (HTMLABLE (FONT_UF, "\\angle ", "&ang;")))
    | "\\dagger"           -> LITERAL (HTMLABLEM(FONT_UFH, "\\dagger ", "&dagger;"))
    | "\\ddagger"          -> LITERAL (HTMLABLEM(FONT_UFH, "\\ddagger ", "&Dagger;"))
    | "\\Dagger"           -> LITERAL (HTMLABLEM(FONT_UFH, "\\ddagger ", "&Dagger;"))
    | "\\colon"            -> LITERAL (HTMLABLEC(FONT_UFH, "\\colon ", ":"))
    | "\\Vert"             -> DELIMITER (HTMLABLEM(FONT_UFH, "\\Vert ", "||"))
    | "\\vert"             -> DELIMITER (HTMLABLEM(FONT_UFH, "\\vert ", "|"))
    | "\\wp"               -> LITERAL (HTMLABLE (FONT_UF,  "\\wp ", "&weierp;"))
    | "\\weierp"           -> LITERAL (HTMLABLE (FONT_UF,  "\\wp ", "&weierp;"))
    | "\\wedge"            -> LITERAL (HTMLABLE (FONT_UF,  "\\wedge ", "&and;"))
    | "\\and"              -> LITERAL (HTMLABLE (FONT_UF,  "\\land ", "&and;"))
    | "\\land"             -> LITERAL (HTMLABLE (FONT_UF,  "\\land ", "&and;"))
    | "\\vee"              -> LITERAL (HTMLABLE (FONT_UF,  "\\vee ", "&or;"))
    | "\\or"               -> LITERAL (HTMLABLE (FONT_UF,  "\\lor ", "&or;"))
    | "\\lor"              -> LITERAL (HTMLABLE (FONT_UF,  "\\lor ", "&or;"))
    | "\\sub"              -> LITERAL (HTMLABLE (FONT_UF,  "\\subset ", "&sub;"))
    | "\\supe"             -> LITERAL (HTMLABLE (FONT_UF,  "\\supseteq ", "&supe;"))
    | "\\sube"             -> LITERAL (HTMLABLE (FONT_UF,  "\\subseteq ", "&sube;"))
    | "\\supset"           -> LITERAL (HTMLABLE (FONT_UF,  "\\supset ", "&sup;"))
    | "\\subset"           -> LITERAL (HTMLABLE (FONT_UF,  "\\subset ", "&sub;"))
    | "\\supseteq"         -> LITERAL (HTMLABLE (FONT_UF,  "\\supseteq ", "&supe;"))
    | "\\subseteq"         -> LITERAL (HTMLABLE (FONT_UF,  "\\subseteq ", "&sube;"))
    | "\\sqsupset"         -> (tex_use_ams (); LITERAL (TEX_ONLY "\\sqsupset "))
    | "\\sqsubset"         -> (tex_use_ams (); LITERAL (TEX_ONLY "\\sqsubset "))
    | "\\sqsupseteq"       -> (tex_use_ams (); LITERAL (TEX_ONLY "\\sqsupseteq "))
    | "\\sqsubseteq"       -> (tex_use_ams (); LITERAL (TEX_ONLY "\\sqsubseteq "))
    | "\\perp"             -> LITERAL (HTMLABLE (FONT_UF,  "\\perp ", "&perp;"))
    | "\\bot"              -> LITERAL (HTMLABLE (FONT_UF,  "\\bot ", "&perp;"))
    | "\\lfloor"           -> DELIMITER (HTMLABLE (FONT_UF,  "\\lfloor ", "&lfloor;"))
    | "\\rfloor"           -> DELIMITER (HTMLABLE (FONT_UF,  "\\rfloor ", "&rfloor;"))
    | "\\lceil"            -> DELIMITER (HTMLABLE (FONT_UF,  "\\lceil ", "&lceil;"))
    | "\\rceil"            -> DELIMITER (HTMLABLE (FONT_UF,  "\\rceil ", "&rceil;"))
    | "\\lbrace"           -> DELIMITER (HTMLABLEC(FONT_UFH, "\\lbrace ", "{"))
    | "\\rbrace"           -> DELIMITER (HTMLABLEC(FONT_UFH, "\\rbrace ", "}"))
    | "\\infty"            -> LITERAL (HTMLABLEM(FONT_UF,  "\\infty ", "&infin;"))
    | "\\infin"            -> LITERAL (HTMLABLEM(FONT_UF,  "\\infty ", "&infin;"))
    | "\\isin"             -> LITERAL (HTMLABLE (FONT_UF,  "\\in ", "&isin;"))
    | "\\in"               -> LITERAL (HTMLABLE (FONT_UF,  "\\in ", "&isin;"))
    | "\\ni"               -> LITERAL (HTMLABLE (FONT_UF,  "\\ni ", "&ni;"))
    | "\\notin"            -> LITERAL (HTMLABLE (FONT_UF,  "\\notin ", "&notin;"))
    | "\\smallsetminus"    -> (tex_use_ams (); LITERAL (TEX_ONLY "\\smallsetminus "))
    | "\\And"              -> (tex_use_ams (); LITERAL (HTMLABLEM(FONT_UFH, "\\And ", "&nbsp;&amp;&nbsp;")))
    | "\\forall"           -> LITERAL (HTMLABLE (FONT_UFH, "\\forall ", "&forall;"))
    | "\\exists"           -> LITERAL (HTMLABLE (FONT_UFH, "\\exists ", "&exist;"))
    | "\\exist"            -> LITERAL (HTMLABLE (FONT_UFH, "\\exists ", "&exist;"))
    | "\\equiv"            -> LITERAL (HTMLABLEM(FONT_UFH, "\\equiv ", "&equiv;"))
    | "\\ne"               -> LITERAL (HTMLABLEM(FONT_UFH, "\\neq ", "&ne;"))
    | "\\neq"              -> LITERAL (HTMLABLEM(FONT_UFH, "\\neq ", "&ne;"))
    | "\\Re"               -> LITERAL (HTMLABLE (FONT_UF,  "\\Re ", "&real;"))
    | "\\real"             -> LITERAL (HTMLABLE (FONT_UF,  "\\Re ", "&real;"))
    | "\\Im"               -> LITERAL (HTMLABLE (FONT_UF,  "\\Im ", "&image;"))
    | "\\image"            -> LITERAL (HTMLABLE (FONT_UF,  "\\Im ", "&image;"))
    | "\\prime"            -> LITERAL (HTMLABLE (FONT_UFH,"\\prime ", "&prime;"))
    | "\\backslash"        -> DELIMITER (HTMLABLEM(FONT_UFH,"\\backslash ", "\\"))
    | "\\setminus"         -> LITERAL (HTMLABLEM(FONT_UFH,"\\setminus ", "\\"))
    | "\\times"            -> LITERAL (HTMLABLEM(FONT_UFH,"\\times ", "&times;"))
    | "\\pm"               -> LITERAL (HTMLABLEM(FONT_UFH,"\\pm ", "&plusmn;"))
    | "\\plusmn"           -> LITERAL (HTMLABLEM(FONT_UFH,"\\pm ", "&plusmn;"))
    | "\\cdot"             -> LITERAL (HTMLABLE (FONT_UFH,"\\cdot ", "&sdot;"))
    | "\\AA"               -> LITERAL (HTMLABLE (FONT_UFH,"\\AA ", "&Aring;"))
    | "\\cdots"            -> LITERAL (HTMLABLE (FONT_UFH,"\\cdots ", "&sdot;&sdot;&sdot;"))
    | "\\sdot"             -> LITERAL (HTMLABLE (FONT_UFH,"\\cdot ", "&sdot;"))
    | "\\oplus"            -> LITERAL (HTMLABLE (FONT_UF, "\\oplus ", "&oplus;"))
    | "\\otimes"           -> LITERAL (HTMLABLE (FONT_UF, "\\otimes ", "&otimes;"))
    | "\\cap"              -> LITERAL (HTMLABLEM(FONT_UF, "\\cap ", "&cap;"))
    | "\\cup"              -> LITERAL (HTMLABLE (FONT_UF, "\\cup ", "&cup;"))
    | "\\sqcap"            -> (tex_use_ams (); LITERAL (TEX_ONLY "\\sqcap "))
    | "\\sqcup"            -> (tex_use_ams (); LITERAL (TEX_ONLY "\\sqcup "))
    | "\\empty"            -> LITERAL (HTMLABLE (FONT_UF, "\\emptyset ", "&empty;"))
    | "\\emptyset"         -> LITERAL (HTMLABLE (FONT_UF, "\\emptyset ", "&empty;"))
    | "\\O"                -> LITERAL (HTMLABLE (FONT_UF, "\\emptyset ", "&empty;"))
    | "\\S"                -> LITERAL (HTMLABLEM(FONT_UFH,"\\S ", "&sect;"))
    | "\\sect"             -> LITERAL (HTMLABLEM(FONT_UFH,"\\S ", "&sect;"))
    | "\\nabla"            -> LITERAL (HTMLABLE (FONT_UF, "\\nabla ", "&nabla;"))
    | "\\geq"              -> LITERAL (HTMLABLE (FONT_UFH,"\\geq ", "&ge;"))
    | "\\ge"               -> LITERAL (HTMLABLE (FONT_UFH,"\\geq ", "&ge;"))
    | "\\leq"              -> LITERAL (HTMLABLE (FONT_UFH,"\\leq ", "&le;"))
    | "\\le"               -> LITERAL (HTMLABLE (FONT_UFH,"\\leq ", "&le;"))
    | "\\cong"             -> LITERAL (HTMLABLE (FONT_UF, "\\cong ", "&cong;"))
    | "\\ang"              -> LITERAL (HTMLABLE (FONT_UF, "\\angle ", "&ang;"))
    | "\\part"             -> LITERAL (HTMLABLEM(FONT_UF, "\\partial ", "&part;"))
    | "\\partial"          -> LITERAL (HTMLABLEM(FONT_UF, "\\partial ", "&part;"))
    | "\\ldots"            -> LITERAL (HTMLABLEM(FONT_UFH,"\\ldots ", "..."))
    | "\\dots"             -> LITERAL (HTMLABLEM(FONT_UFH,"\\dots ", "..."))
    | "\\quad"             -> LITERAL (HTMLABLE (FONT_UF, "\\quad ","&nbsp;&nbsp;"))
    | "\\qquad"            -> LITERAL (HTMLABLE (FONT_UF, "\\qquad ","&nbsp;&nbsp;&nbsp;&nbsp;"))
    | "\\mid"              -> LITERAL (HTMLABLEM(FONT_UFH,"\\mid ", " | "))
    | "\\neg"              -> LITERAL (HTMLABLEM(FONT_UFH,"\\neg ", "&not;"))
    | "\\langle"           -> DELIMITER (HTMLABLE (FONT_UFH,"\\langle ","&lang;"))
    | "\\rangle"           -> DELIMITER (HTMLABLE (FONT_UFH,"\\rangle ","&rang;"))
    | "\\lang"             -> DELIMITER (HTMLABLE (FONT_UFH,"\\langle ","&lang;"))
    | "\\rang"             -> DELIMITER (HTMLABLE (FONT_UFH,"\\rangle ","&rang;"))
    | "\\lbrack"           -> DELIMITER (HTMLABLEC(FONT_UFH,"[","["))
    | "\\rbrack"           -> DELIMITER (HTMLABLEC(FONT_UFH,"]","]"))
    | "\\ddots"            -> LITERAL (TEX_ONLY "\\ddots ")
    | "\\clubs"            -> LITERAL (TEX_ONLY "\\clubsuit ")
    | "\\clubsuit"         -> LITERAL (TEX_ONLY "\\clubsuit ")
    | "\\spades"           -> LITERAL (TEX_ONLY "\\spadesuit ")
    | "\\spadesuit"        -> LITERAL (TEX_ONLY "\\spadesuit ")
    | "\\hearts"           -> LITERAL (TEX_ONLY "\\heartsuit ")
    | "\\heartsuit"        -> LITERAL (TEX_ONLY "\\heartsuit ")
    | "\\diamonds"         -> LITERAL (TEX_ONLY "\\diamondsuit ")
    | "\\diamondsuit"      -> LITERAL (TEX_ONLY "\\diamondsuit ")
    | "\\implies"          -> (tex_use_ams (); LITERAL (HTMLABLE (FONT_UF, "\\implies ", "&rArr;")))
    | "\\mod"              -> (tex_use_ams (); LITERAL (HTMLABLE (FONT_UFH,"\\mod ", "mod")))
    | "\\Diamond"          -> (tex_use_ams (); LITERAL (HTMLABLE (FONT_UF, "\\Diamond ", "&loz;")))
    | "\\dotsb"            -> (tex_use_ams (); LITERAL (HTMLABLE (FONT_UF, "\\dotsb ", "&sdot;&sdot;&sdot;")))
    | "\\reals"            -> (tex_use_ams (); LITERAL (HTMLABLE (FONT_UFH,"\\mathbb{R}", "<b>R</b>")))
    | "\\Reals"            -> (tex_use_ams (); LITERAL (HTMLABLE (FONT_UFH,"\\mathbb{R}", "<b>R</b>")))
    | "\\R"                -> (tex_use_ams (); LITERAL (HTMLABLE (FONT_UFH,"\\mathbb{R}", "<b>R</b>")))
    | "\\cnums"            -> (tex_use_ams (); LITERAL (HTMLABLE (FONT_UFH,"\\mathbb{C}", "<b>C</b>")))
    | "\\Complex"          -> (tex_use_ams (); LITERAL (HTMLABLE (FONT_UFH,"\\mathbb{C}", "<b>C</b>")))
    | "\\Z"                -> (tex_use_ams (); LITERAL (HTMLABLE (FONT_UFH,"\\mathbb{Z}", "<b>Z</b>")))
    | "\\natnums"          -> (tex_use_ams (); LITERAL (HTMLABLE (FONT_UFH,"\\mathbb{N}", "<b>N</b>")))
    | "\\N"                -> (tex_use_ams (); LITERAL (HTMLABLE (FONT_UFH,"\\mathbb{N}", "<b>N</b>")))
    | "\\lVert"            -> (tex_use_ams (); LITERAL (HTMLABLE (FONT_UFH,"\\lVert ", "||")))
    | "\\rVert"            -> (tex_use_ams (); LITERAL (HTMLABLE (FONT_UFH,"\\rVert ", "||")))
    | "\\nmid"             -> (tex_use_ams (); LITERAL (TEX_ONLY "\\nmid "))
    | "\\lesssim"          -> (tex_use_ams (); LITERAL (TEX_ONLY "\\lesssim "))
    | "\\ngeq"             -> (tex_use_ams (); LITERAL (TEX_ONLY "\\ngeq "))
    | "\\smallsmile"       -> (tex_use_ams (); LITERAL (TEX_ONLY "\\smallsmile "))
    | "\\smallfrown"       -> (tex_use_ams (); LITERAL (TEX_ONLY "\\smallfrown "))
    | "\\nleftarrow"       -> (tex_use_ams (); LITERAL (TEX_ONLY "\\nleftarrow "))
    | "\\nrightarrow"      -> (tex_use_ams (); LITERAL (TEX_ONLY "\\nrightarrow "))
    | "\\trianglelefteq"   -> (tex_use_ams (); LITERAL (TEX_ONLY "\\trianglelefteq "))
    | "\\trianglerighteq"  -> (tex_use_ams (); LITERAL (TEX_ONLY "\\trianglerighteq "))
    | "\\square"           -> (tex_use_ams (); LITERAL (TEX_ONLY "\\square "))
    | "\\checkmark"        -> (tex_use_ams (); LITERAL (TEX_ONLY "\\checkmark "))
    | "\\supsetneq"        -> (tex_use_ams (); LITERAL (TEX_ONLY "\\supsetneq "))
    | "\\subsetneq"        -> (tex_use_ams (); LITERAL (TEX_ONLY "\\subsetneq "))
    | "\\Box"              -> (tex_use_ams (); LITERAL (TEX_ONLY "\\Box "))
    | "\\nleq"             -> (tex_use_ams (); LITERAL (TEX_ONLY "\\nleq "))
    | "\\upharpoonright"   -> (tex_use_ams (); LITERAL (TEX_ONLY "\\upharpoonright "))
    | "\\upharpoonleft"    -> (tex_use_ams (); LITERAL (TEX_ONLY "\\upharpoonleft "))
    | "\\downharpoonright" -> (tex_use_ams (); LITERAL (TEX_ONLY "\\downharpoonright "))
    | "\\downharpoonleft"  -> (tex_use_ams (); LITERAL (TEX_ONLY "\\downharpoonleft "))
    | "\\rightharpoonup"   -> (tex_use_ams (); LITERAL (TEX_ONLY "\\rightharpoonup "))
    | "\\rightharpoondown" -> (tex_use_ams (); LITERAL (TEX_ONLY "\\rightharpoondown "))
    | "\\leftharpoonup"    -> (tex_use_ams (); LITERAL (TEX_ONLY "\\leftharpoonup "))
    | "\\leftharpoondown"  -> (tex_use_ams (); LITERAL (TEX_ONLY "\\leftharpoondown "))
    | "\\nless"            -> (tex_use_ams (); LITERAL (TEX_ONLY "\\nless "))
    | "\\Vdash"            -> (tex_use_ams (); LITERAL (TEX_ONLY "\\Vdash "))
    | "\\vDash"            -> (tex_use_ams (); LITERAL (TEX_ONLY "\\vDash "))
    | "\\varkappa"         -> (tex_use_ams (); LITERAL (TEX_ONLY "\\varkappa "))
    | "\\digamma"          -> (tex_use_ams (); LITERAL (TEX_ONLY "\\digamma "))
    | "\\beth"             -> (tex_use_ams (); LITERAL (TEX_ONLY "\\beth "))
    | "\\daleth"           -> (tex_use_ams (); LITERAL (TEX_ONLY "\\daleth "))
    | "\\gimel"            -> (tex_use_ams (); LITERAL (TEX_ONLY "\\gimel "))
    | "\\complement"       -> (tex_use_ams (); LITERAL (TEX_ONLY "\\complement "))
    | "\\eth"              -> (tex_use_ams (); LITERAL (TEX_ONLY "\\eth "))
    | "\\hslash"           -> (tex_use_ams (); LITERAL (TEX_ONLY "\\hslash "))
    | "\\mho"              -> (tex_use_ams (); LITERAL (TEX_ONLY "\\mho "))
    | "\\Finv"             -> (tex_use_ams (); LITERAL (TEX_ONLY "\\Finv "))
    | "\\Game"             -> (tex_use_ams (); LITERAL (TEX_ONLY "\\Game "))
    | "\\varlimsup"        -> (tex_use_ams (); LITERAL (TEX_ONLY "\\varlimsup "))
    | "\\varliminf"        -> (tex_use_ams (); LITERAL (TEX_ONLY "\\varliminf "))
    | "\\varinjlim"        -> (tex_use_ams (); LITERAL (TEX_ONLY "\\varinjlim "))
    | "\\varprojlim"       -> (tex_use_ams (); LITERAL (TEX_ONLY "\\varprojlim "))
    | "\\injlim"           -> (tex_use_ams (); LITERAL (TEX_ONLY "\\injlim "))
    | "\\projlim"          -> (tex_use_ams (); LITERAL (TEX_ONLY "\\projlim "))
    | "\\iint"             -> (tex_use_ams (); LITERAL (TEX_ONLY "\\iint "))
    | "\\iiint"            -> (tex_use_ams (); LITERAL (TEX_ONLY "\\iiint "))
    | "\\iiiint"           -> (tex_use_ams (); LITERAL (TEX_ONLY "\\iiiint "))
    | "\\varnothing"       -> (tex_use_ams (); LITERAL (TEX_ONLY "\\varnothing "))
    | "\\left"             -> LEFT
    | "\\right"            -> RIGHT
    | "\\hat"              -> FUN_AR1 "\\hat "
    | "\\widehat"          -> FUN_AR1 "\\widehat "
    | "\\overline"         -> FUN_AR1 "\\overline "
    | "\\overbrace"        -> FUN_AR1 "\\overbrace "
    | "\\underline"        -> FUN_AR1 "\\underline "
    | "\\underbrace"       -> FUN_AR1 "\\underbrace "
    | "\\overleftarrow"    -> FUN_AR1 "\\overleftarrow "
    | "\\overrightarrow"   -> FUN_AR1 "\\overrightarrow "
    | "\\overleftrightarrow"->FUN_AR1 "\\overleftrightarrow "
    | "\\check"            -> FUN_AR1 "\\check "
    | "\\acute"            -> FUN_AR1 "\\acute "
    | "\\grave"            -> FUN_AR1 "\\grave "
    | "\\bar"              -> FUN_AR1 "\\bar "
    | "\\vec"              -> FUN_AR1 "\\vec "
    | "\\dot"              -> FUN_AR1 "\\dot "
    | "\\ddot"             -> FUN_AR1 "\\ddot "
    | "\\breve"            -> FUN_AR1 "\\breve "
    | "\\tilde"            -> FUN_AR1 "\\tilde "
    | "\\not"              -> LITERAL (TEX_ONLY "\\not ")
    | "\\choose"           -> FUN_INFIX "\\choose "
    | "\\atop"             -> FUN_INFIX "\\atop "
    | "\\binom"            -> FUN_AR2 "\\binom "
    | "\\stackrel"         -> FUN_AR2 "\\stackrel "
    | "\\frac"             -> FUN_AR2h ("\\frac ", fun num den -> Html.html_render [num], "<hr style=\"{background: black}\"/>", Html.html_render [den])
    | "\\cfrac"            -> (tex_use_ams (); FUN_AR2h ("\\cfrac ", fun num den -> Html.html_render [num], "<hr style=\"{background: black}\">", Html.html_render [den]))
    | "\\over"             -> FUN_INFIXh ("\\over ", fun num den -> Html.html_render num, "<hr style=\"{background: black}\"/>", Html.html_render den)
    | "\\sqrt"             -> FUN_AR1 "\\sqrt "
    | "\\pmod"             -> FUN_AR1hl ("\\pmod ", ("(mod ", ")"))
    | "\\bmod"             -> FUN_AR1hl ("\\bmod ", ("mod ", ""))
    | "\\emph"             -> FUN_AR1 "\\emph "
    | "\\texttt"           -> FUN_AR1 "\\texttt "
    | "\\textbf"           -> FUN_AR1 "\\textbf "
    | "\\textit"           -> FUN_AR1hf ("\\textit ", FONTFORCE_IT)
    | "\\textrm"           -> FUN_AR1hf ("\\textrm ", FONTFORCE_RM)
    | "\\rm"               -> DECLh ("\\rm ", FONTFORCE_RM)
    | "\\it"               -> DECLh ("\\it ", FONTFORCE_IT)
    | "\\cal"              -> DECL "\\cal "
    | "\\displaystyle"     -> LITERAL (TEX_ONLY  "\\displaystyle ")
    | "\\scriptstyle"      -> LITERAL (TEX_ONLY "\\scriptstyle ")
    | "\\textstyle"        -> LITERAL (TEX_ONLY "\\textstyle ")
    | "\\scriptscriptstyle"-> LITERAL (TEX_ONLY "\\scriptscriptstyle ")
    | "\\bf"               -> DECL "\\bf "
    | "\\big"              -> BIG "\\big "
    | "\\Big"              -> BIG "\\Big "
    | "\\bigg"             -> BIG "\\bigg "
    | "\\Bigg"             -> BIG "\\Bigg "
    | "\\mathit"           -> (tex_use_ams (); FUN_AR1hf ("\\mathit ", FONTFORCE_IT))
    | "\\mathrm"           -> (tex_use_ams (); FUN_AR1hf ("\\mathrm ", FONTFORCE_RM))
    | "\\mathop"           -> (tex_use_ams (); FUN_AR1 "\\mathop ")
    | "\\boldsymbol"       -> (tex_use_ams (); FUN_AR1 "\\boldsymbol ")
    | "\\bold"             -> (tex_use_ams (); FUN_AR1 "\\mathbf ")
    | "\\Bbb"              -> (tex_use_ams (); FUN_AR1 "\\mathbb ")
    | "\\mathbf"           -> (tex_use_ams (); FUN_AR1 "\\mathbf ")
    | "\\mathsf"           -> (tex_use_ams (); FUN_AR1 "\\mathsf ")
    | "\\mathcal"          -> (tex_use_ams (); FUN_AR1 "\\mathcal ")
    | "\\mathbb"           -> (tex_use_ams (); FUN_AR1 "\\mathbb ")
    | "\\mathfrak"         -> (tex_use_ams (); FUN_AR1 "\\mathfrak ")
    | "\\operatorname"     -> (tex_use_ams (); FUN_AR1 "\\operatorname ")
    | "\\mbox"             -> raise (Failure "malformatted \\mbox")
    | "\\vbox"             -> raise (Failure "malformatted \\vbox")
    | "\\hbox"             -> raise (Failure "malformatted \\hbox")
    | "\\color"            -> (tex_use_color (); LITERAL (TEX_ONLY "\\color"))
    | s                    -> raise (Illegal_tex_function s)
