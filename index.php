<?php

/**
 * @author Nguyen Duc Hanh
 * @copyright 2014
 * @copygith nguyenduchanh.com
 */


session_start();
error_reporting(0);
if($_POST){
    $url = $_POST['url'];
    if($url==''){
        $err_mgs[] =  '+ Enter valid url';
    }else{
        if(strpos($url,'http')===false){
            $url = 'http://'.$url;    
        }
        $_SESSION['url'] = $url;
        
    }
    
    $parse_url = parse_url($url);
    $folder = $parse_url['host'];
    if($folder==''){
        $err_mgs[] =  '+ Folder is not valid';
    }else{
        $_SESSION['folder'] = $folder;
    }
    
    $save_file = $_POST['save_file'];
    if($save_file==''){
        $err_mgs[] =  '+ Enter valid file to save';
    }else{
        $_SESSION['save_file'] = $save_file;
    }
    
    if($url && $folder && $save_file){
        header("location: process.php");
    }
}else{
    session_destroy();
}



?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Css spider</title>
<meta name="description" content="Save complete website" />
<meta http-equiv="CONTENT-TYPE" content="text/html; charset=utf-8"/>
<meta http-equiv="Cache-Control" content="no-cache/"/>
<meta http-equiv="Pragma" content="no-cache"/>
<meta http-equiv="Expires" content="-1"/>
<meta name="robots" content="noindex, nofollow" />
</head>
<style>
.forgotpass{
    text-decoration: none !important;
}
.forgotpass:hover{
    text-decoration: underline !important;
}

</style>
<body>
<br /><br />
<center>
	<form name="frm" id="frm" action="" method="post">	
	<table width="500" style="border-collapse:collapse;font-size:11px;font-family:tahoma;border-color:#CCC;border: 1px #CCC solid;background-color:#FFF;padding-bottom:10px;" border="1">
	<caption style="font-size: 11px; font-family:tahoma;color:#FF0000; margin-bottom: 5px; text-align: left;">
    <?php
    if(isset($err_mgs)){
        foreach($err_mgs as $msg){
            echo $msg.'<br/>';
        }
    }
    ?>
    </caption>
	<tr><td colspan="2" style="font-weight: bold; color:#FFF; background-color:#1788C8;padding:3px;text-align: center;" >CSS Spider</td></tr>
	<tr><td>&nbsp;URL(Example: google.com):</td><td><input type="text" name="url" style="width: 100%;" class="{required:true,messages:{required:'Enter url!'}}"  /><span class="error"></span></td></tr>
	<tr><td>&nbsp;File name to save</td><td><input type="text" name="save_file" value="index.html" style="width: 100%;"  class="{required:true,messages:{required:'Nhập mật khẩu!'}}"/><span class="error"></span></td></tr>
		<tr><td>&nbsp;</td><td style="padding:3px;"><input type="submit" name="btnSubmit" value="Process" /></td></tr>
	</table>
	</form>
</center>
</body>
</html>