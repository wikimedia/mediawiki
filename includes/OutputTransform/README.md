Output transformations pipelines for wikitext

The classes in the `Stages/` subdirectory contains HTML and DOM transforms for use in
output processing pipelines, i.e. postprocessors for `ParserOutput` objects that either
directly result from a parse or are fetched from `ParserCache`.

The default pipeline is created by `DefaultOutputTransformFactory`; it corresponds to
what was previously contained in `ParserOutput::getText`. The `shouldRun` method in these
stages uses defaults that indicates if the stage runs or not in the default
`OutputTransformPipeline`.
