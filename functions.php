<?php

/**
 * @author Nguyen Duc Hanh
 * @copyright 2014
 * @copygith nguyenduchanh.com
 */


/* download file function
----------------------------------------------------------------------------*/
function cleanUrl($url,$is_css = false){
    if($is_css){
        $file_info =  pathinfo($url);
        $extension = $file_info['extension'];
        if($extension!='css'){
            $extension = 'css';
        }
        return  $file_info['dirname'].'/'.remove_special_char($file_info['filename']).'.'.$extension;
    }
    return strtok($url, '?');
}


/* show the step
-----------------------------------------------------------------------------*/
function show_step($show_img = false){
    global $folder, $save_file;
    $arrText = array(
        1   =>  '| Dwnload  html content<br/>',
        2   =>  '| Get css and javascript files<br/>',
        3   =>  '| Save background images<br/>',
        4   =>  '| Update html content<br/>',
        5   =>  '| Finish!<br/>'
    );
    $step = isset($_SESSION['step'])? $_SESSION['step'] : 1;
    for($i = 1; $i <= $step; $i++){
        echo $arrText[$i];
        if($i==3 && $show_img == true && is_array($_SESSION['show_img']) && count($_SESSION['show_img'])>0 ){
            foreach($_SESSION['show_img'] as $images){
                echo '|----'.$images.'<br/>';
            }
        }
        
        if($i==5){
            $site_path = get_site_path();
            echo '<h3>
                    <a href="'.$site_path.'download.php?f='.$folder.'">Download</a>&nbsp;&nbsp;/&nbsp;&nbsp;
                    <a href="'.$site_path.'save/'.$folder.'/'.$save_file.'" target="_blank">View</a>&nbsp;&nbsp;/&nbsp;&nbsp;
                    <a href="'.$site_path.'">Home</a>
                  </h3>';
        }
    }
}



/* show images name that was downloaded
----------------------------------------------------------------------------*/
function show_image_download($img = false){
    if($img){
        $_SESSION['show_img'][] = $img;    
    }
    foreach($_SESSION['show_img'] as $images){
        echo '|----'.$images.'<br/>';
    }
    echo '<div id="bottom">&nbsp;</div>';
}


/* get css include
----------------------------------------------------------------------------*/
function get_css_import($css_file){
    global $css_path;
    $css_file = (strpos($css_file,'//')===false) ? h_parse_url($_SESSION['url']).'/'.$css_file : $css_file;
    if(substr($css_file,0,4)!='http'){
        $css_file = 'http:'.$css_file;
    }
     
    $data = file_get_contents($css_file);
    $css_info = pathinfo($css_file);
    $css_dir = $css_info['dirname'];
    
    $output = array();
    foreach(explode("url(", $data) as $i => $a) { // Split string into array of substrings at boundaries of "url(" and loop through it
        if ($i) {
            $a = explode(")", $a); // Split substring into array at boundaries of ")"
            $url = trim(str_replace(array('"',"'"), "", $a[0])); // Remove " and ' characters
            $url_info = pathinfo($url);
			$save_dir = $css_path."/".$url_info['dirname'];
			if(!is_dir($save_dir)){
				mkdir($save_dir,0777,true);
			}
            if($url_info['extension']=='css'){
                $css_url = $css_dir.'/'.$url;
                $path_info = pathinfo($stylesheet_url);
                $file_path = $save_dir.'/'.$url_info['filename'].".".$url_info['extension'];
                file_put_contents($file_path,get_file_content($css_url));
                array_push($output, $css_url);    
            }
        }
    } 
    return $output;
}


/* get file content with exception gzip
----------------------------------------------------------------------------*/
function get_file_content($url){
    // make url is direct path in server
    if(strpos($url, '//')===false){
        $url = h_parse_url($_SESSION['url']).'/'.$url;
    }
    if(substr($url,0,4)!='http'){
        $url = 'http:'.$url;
    }
    $url = str_replace('&amp;','&',$url);
    
    //user agent is very necessary, otherwise some websites like google.com wont give zipped content
    $opts = array(
        'http'=>array(
            'method'=>"GET",
            'header'=>"Accept-Language: en-US,en;q=0.8rn" .
                        "Accept-Encoding: gzip,deflate,sdchrn" .
                        "Accept-Charset:UTF-8,*;q=0.5rn" .
                        "User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:19.0) Gecko/20100101 Firefox/19.0 FirePHP/0.4rn"
        )
    );
 
    $context = stream_context_create($opts);
    $content = file_get_contents($url ,false,$context);
     
    //If http response header mentions that content is gzipped, then uncompress it
    foreach($http_response_header as $c => $h){
        if(stristr($h, 'content-encoding') and stristr($h, 'gzip')){
            //Now lets uncompress the compressed data
            $content = gzinflate( substr($content,10,-8) );
        }
    }
    
    // if can't get the content
    if(trim($content)==false){
        $content = file_get_contents($url);
    } 
    
    return $content;
}



/* return curent site path example: http://nguyenduchanh.com/css-sprider
----------------------------------------------------------------------------*/
function get_site_path(){
    // get site path
    $ffc_ar = explode( "/", $_SERVER['PHP_SELF'] );
    $ffc_ar_count = count( $ffc_ar );
    $ffc_ar2 = array();
    for( $i = 0; $i < $ffc_ar_count - 1; $i++ ) {
    	$ffc_ar2[$i] = $ffc_ar[$i];
    }
    
    $ffc_webFolderName = implode( "/", $ffc_ar2 );
    if( strpos( $_SERVER['SERVER_SOFTWARE'], "IIS" ) ) {
    	$sPhysicPath = substr( $_SERVER['SCRIPT_FILENAME'], 0, strrpos( $_SERVER['SCRIPT_FILENAME'], '\\' ) + 1 );
    } else {
    	$sPhysicPath = substr( $_SERVER['SCRIPT_FILENAME'], 0, strrpos( $_SERVER['SCRIPT_FILENAME'], '/' ) + 1 );
    }
    $sProtocol = ( strpos( $_SERVER['SERVER_PROTOCOL'], "HTTPS" ) ) ? "https" : "http";
    $sProtocol .= "://";
    
    $sHostName 	= $_SERVER['HTTP_HOST'];
    $sitePath 	= $sProtocol . $sHostName . $ffc_webFolderName . "/";
    
    return $sitePath;
}

/* return main url. 
Example: https://google.com/dhasjkdas/sadsdds/sdda/sdads.html 
return https://google.com
----------------------------------------------------------------------------*/
function h_parse_url($url){
    $parse = parse_url($url);
    return trim($parse['scheme'].'://'.$parse['host']);
}


/* remore special charactor
----------------------------------------------------------------------------*/
function remove_special_char($str){
    // chuyen co dau sang khong dau
    $vietChar = 'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ|é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|ó|ò|ỏ|õ|ọ|ơ|ớ|ờ|ở|ỡ|ợ|ô|ố|ồ|ổ|ỗ|ộ|ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|í|ì|ỉ|ĩ|ị|ý|ỳ|ỷ|ỹ|ỵ|đ|Á|À|Ả|Ã|Ạ|Ă|Ắ|Ằ|Ẳ|Ẵ|Ặ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ|É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ|Ó|Ò|Ỏ|Õ|Ọ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự|Í|Ì|Ỉ|Ĩ|Ị|Ý|Ỳ|Ỷ|Ỹ|Ỵ|Đ';
    $engChar = 'a|a|a|a|a|a|a|a|a|a|a|a|a|a|a|a|a|e|e|e|e|e|e|e|e|e|e|e|o|o|o|o|o|o|o|o|o|o|o|o|o|o|o|o|o|u|u|u|u|u|u|u|u|u|u|u|i|i|i|i|i|y|y|y|y|y|d|A|A|A|A|A|A|A|A|A|A|A|A|A|A|A|A|A|E|E|E|E|E|E|E|E|E|E|E|O|O|O|O|O|O|O|O|O|O|O|O|O|O|O|O|O|U|U|U|U|U|U|U|U|U|U|U|I|I|I|I|I|Y|Y|Y|Y|Y|D';
    $arrVietChar = explode("|", $vietChar);
    $arrEngChar = explode("|", $engChar);
    $str =  str_replace($arrVietChar, $arrEngChar, $str);
    
    // url title 
    $separator = 'dash';
    $lowercase = false;
    if ($separator == 'dash') {
        $search = '_';
        $replace = '-';
    } else {
        $search = '-';
        $replace = '_';
    }

    $trans = array('&\#\d+?;' => '', '&\S+?;' => '', '\s+' => $replace, '[^a-z0-9\-\._]' =>
        '', $replace . '+' => $replace, $replace . '$' => $replace, '^' . $replace => $replace,
        '\.+$' => '');

    $str = strip_tags($str);
    foreach ($trans as $key => $val) {
        $str = preg_replace("#" . $key . "#i", $val, $str);
    }

    if ($lowercase === true) {
        $str = strtolower($str);
    }
    $str =  trim(stripslashes($str));
    
    // return value
    return strtolower($str);
}