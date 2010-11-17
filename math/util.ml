(* vim: set sw=8 ts=8 et: *)

(* TODO document *)
let mapjoin f l = (List.fold_left (fun a b -> a ^ (f b)) "" l)

(* TODO document *)
let mapjoine e f = function
    [] -> ""
  | h::t -> (List.fold_left (fun a b -> a ^ e ^ (f b)) (f h) t)

(* Exception used by open_out_unless_exists below *)
exception FileAlreadyExists

(* Wrapper which raise an exception when output path already exist *)
let open_out_unless_exists path =
    if Sys.file_exists path
    then raise FileAlreadyExists
    else open_out path

(* *)
let run_in_other_directory tmppath cmd =
    let prevdir = Sys.getcwd () in(
        Sys.chdir tmppath;
        let retval = Sys.command cmd in
            (Sys.chdir prevdir; retval)
    )
