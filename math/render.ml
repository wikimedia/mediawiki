let cmd_dvips tmpprefix = "dvips -R -E " ^ tmpprefix ^ ".dvi -f"
let cmd_latex tmpprefix = "latex " ^ tmpprefix ^ ".tex >/dev/null"
let cmd_convert finalpath = "convert -quality 100 -density 120 ps:- " ^ finalpath ^ " >/dev/null 2>/dev/null"

exception ExternalCommandFailure of string

let render tmppath finalpath outtex md5 =
    let tmpprefix = (tmppath^"/"^(string_of_int (Unix.getpid ()))^"_"^md5) in
    let unlink_all () =
      begin
	Sys.remove (tmpprefix ^ ".dvi");
	Sys.remove (tmpprefix ^ ".aux");
	Sys.remove (tmpprefix ^ ".log");
        Sys.remove (tmpprefix ^ ".tex")
      end in
    let f = (Util.open_out_unless_exists (tmpprefix ^ ".tex")) in
      begin
	output_string f (Texutil.get_preface ());
	output_string f outtex;
	output_string f (Texutil.get_footer ());
	close_out f;
	if Util.run_in_other_directory tmppath (cmd_latex tmpprefix) != 0
	then (unlink_all (); raise (ExternalCommandFailure "latex"))
	else if (Sys.command ((cmd_dvips tmpprefix) ^ " | " ^ (cmd_convert (finalpath^"/"^md5^".png"))) != 0)
	then (unlink_all (); raise (ExternalCommandFailure ("dvips")))
	else unlink_all ()
      end
