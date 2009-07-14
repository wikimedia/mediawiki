<?php
if($_FILES){
	print "file uploaded oky:<pre>";
	print_r($_FILES);
	print "</pre>";
	die();
}
?>

<!DOCTYPE HTML>
<html><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>Firefogg - Make Ogg Video in your Browser</title>	
	<script type="text/javascript" src="../mv_embed.js?debug=true"></script> 
<style type="text/css" media="all">@import "http://www.firefogg.org/css/style.css";</style>
<style type="text/css" media="all">
.install{
display:none;
}
input{
font-size:110%;
}
</style>
<script type="text/javascript">
mwAddOnloadHook(function(){
	$j('#fogg-file').firefogg();	
});
</script>
</head><body>
<div id="main">
  <h1>Rewrite Form example</h1>
<br><br><br>
<center>
	<div style="width:500px">		
		<!--  we submit it to ourselves (ie do nothing)  -->
		<form action="Firefogg_ReWriteForm.php" method="POST" enctype='multipart/form-data'>
		<table> 
			<tr><td>
		  Video Name:</td><td> <input type="text" name="fname" /></td>
		  	</tr>
		  	<tr>
		  <td>Video File :</td><td> <input type="file" name="fogg-file" id="fogg-file"/></td>
		  </tr>
		  <tr>
		  <td>
		  <input type="submit" value="Submit" />
		  </td>
		  </tr>
		 </table>
		</form>
	<br><br>
	</div>
</center>
</body></html>
