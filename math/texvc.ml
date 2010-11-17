(* vim: set sw=8 ts=8 et: *)
exception LexerException of string

(* *)
let lexer_token_safe lexbuf =
    try Lexer.token lexbuf
    with Failure s -> raise (LexerException s)

(* *)
let render tmppath finalpath tree backcolor =
    let outtex = Util.mapjoin Texutil.render_tex tree in
    let md5 = Digest.to_hex (Digest.string outtex) in
    begin
	let mathml = Mathml.render tree
	and html = Html.render tree
	in print_string (match (html,!Html.conservativeness,mathml) with
	    None,_,None -> "+" ^ md5 
	  | Some h,Html.CONSERVATIVE,None -> "c" ^ md5  ^ h
	  | Some h,Html.MODERATE,None -> "m" ^ md5  ^ h
	  | Some h,Html.LIBERAL,None -> "l" ^ md5  ^ h
	  | Some h,Html.CONSERVATIVE,Some m -> "C" ^ md5  ^ h ^ "\000" ^ m
	  | Some h,Html.MODERATE,Some m -> "M" ^ md5  ^ h ^ "\000" ^ m
	  | Some h,Html.LIBERAL,Some m -> "L" ^ md5 ^ h ^ "\000" ^ m
	  | None,_,Some m -> "X" ^ md5   ^ m
	);
	Render.render tmppath finalpath outtex md5 backcolor
    end

(* TODO: document
 * Arguments:
 * 1st :  
 * 2nd :
 * 3rd :
 * 4th : encoding (Default: UTF-8)
 * 5th : color (Default: rgb 1.0 1.0 1.0)
 *
 * Output one character:
 *  S : Parsing error
 *  E : Lexer exception raised
 *  F : TeX function not recognized
 *  - : Generic/Default failure code. Might be an invalid argument,
 *      output file already exist, a problem with an external
 *      command ...
 * *)
let _ =
    Texutil.set_encoding (try Sys.argv.(4) with _ -> "UTF-8");
    try render Sys.argv.(1) Sys.argv.(2) (
        Parser.tex_expr lexer_token_safe (
            Lexing.from_string Sys.argv.(3))
        ) (try Sys.argv.(5) with _ -> "rgb 1.0 1.0 1.0")
    with Parsing.Parse_error -> print_string "S"
       | LexerException _ -> print_string "E"
       | Texutil.Illegal_tex_function s -> print_string ("F" ^ s)
       | Util.FileAlreadyExists -> print_string "-"
       | Invalid_argument _ -> print_string "-"
       | Failure _ -> print_string "-"
       | Render.ExternalCommandFailure s -> ()
       | _ -> print_string "-"
