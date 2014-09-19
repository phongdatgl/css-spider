<?php

/**
 * @author Nguyen Duc Hanh
 * @copyright 2014
 * @copygith nguyenduchanh.com
 */
 
session_start();
error_reporting(0);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Css spider</title>
<meta name="description" content="Save complete website" />
<meta http-equiv="CONTENT-TYPE" content="text/html; charset=iso-8859-1"/>
<meta http-equiv="CONTENT-LANGUAGE" content="EN"/>
<meta http-equiv="Cache-Control" content="no-cache/"/>
<meta http-equiv="Pragma" content="no-cache"/>
<meta http-equiv="Expires" content="-1"/>
<meta name="robots" content="noindex, nofollow" />
<script type="text/javascript">
window.location.hash = '#bottom';
</script>
</head>
<body>
<?php

include_once('simple_html_dom.php');
include_once('functions.php');

if(isset($_REQUEST['cmd']) && $_REQUEST['cmd']=='clear'){
    session_destroy();
    echo "Clear all sessions<br/>"; exit;
}

$url = $_SESSION['url'];
$folder = $_SESSION['folder'];
$save_file = $_SESSION['save_file'];
if($url==''){
    echo '<p>Enter valid url</p><br/>';
    exit;
}
if($folder==''){
    echo '<p>Enter valid folder</p><br/>';
    exit;
}
if($save_file==''){
    echo '<p>Enter valid file to save</p><br/>';
    exit;
}



$path = "save/".$folder;
$css_path = "save/".$folder."/asset/css";
$js_path =  "save/".$folder."/asset/js";

/* 
step = 1; // download  html content
step = 2; // get css and javascript file
step = 3; // save background images
step = 4; // update html content
step = 5; // finish
*/

/*
session_destroy();
exit;
*/

if(!is_dir($path)){
    mkdir($path, 0777, true);    
}
if(!is_dir($css_path)){
    mkdir($css_path, 0777, true);    
}
if(!is_dir($js_path)){
    mkdir($js_path, 0777, true);    
}



$step = isset($_SESSION['step']) ? $_SESSION['step'] : 1 ;
switch($step){
    case "1":
        /* download html
        ----------------------------------------------------------------------------*/
        show_step();
        file_put_contents($path.'/tmp',get_file_content($url));
        $step++;
        $_SESSION['step'] = $step;
        echo '<script>location.reload();</script>';
        exit;
    break;
    
    case "2":
        /* get script and css
        ----------------------------------------------------------------------------*/
        show_step();
        $website = file_get_html($path.'/tmp');
        $arrayCss = array();
        foreach ($website->find('link[rel="stylesheet"]') as $stylesheet){
            $stylesheet_url = cleanUrl($stylesheet->href,true);
            if($stylesheet_url!=''){
                $stylesheet_url = (strpos($stylesheet_url,'//')===false) ? h_parse_url($_SESSION['url']).'/'.$stylesheet_url : $stylesheet_url;
                $arrayCss[] = $stylesheet_url;
                $path_info = pathinfo($stylesheet_url);
                $file_path = $css_path."/".$path_info['filename'].".".$path_info['extension'];
                file_put_contents($file_path,get_file_content($stylesheet->href));
                $css_import = get_css_import($stylesheet_url);
                if($css_import){
                    $arrayCss = array_merge($css_import,$arrayCss);
                }
            }
        }
        $_SESSION['arrayCss'] = $arrayCss;
        
        $arrayJs = array();
        foreach ($website->find('script') as $script){
            $script_url = cleanUrl($script->src);
            $stylesheet_url = (strpos($script_url,'//')===false) ? h_parse_url($_SESSION['url']).'/'.$script_url : $script_url; 
            if($script_url){
                $arrayJs[] = $script_url;
                $path_info = pathinfo($script_url);
                $file_path = $js_path."/".$path_info['filename'].".".$path_info['extension'];
                file_put_contents($file_path,get_file_content($script->src));
                //var_dump($file_path);
            }
        }
        $step++;
        $_SESSION['step'] = $step;
        echo '<script>location.reload();</script>';
        exit;
    break;
    
    case "3":
        /* save background images
        ----------------------------------------------------------------------------*/
        show_step();
        $imgs = $_SESSION['img'];
        $arrayCss = $_SESSION['arrayCss'];
        if(count($imgs) > 0 ){
            // remove duplicate value from images array
            $imgs = array_unique($imgs);
            
            $bg_images = array_pop($imgs);
            if($bg_images){
                show_image_download($bg_images);
                $images_info = pathinfo($bg_images);
                $image_path = $css_path.'/'.$images_info['dirname'];
                if(!is_dir($image_path)){
                    mkdir($image_path,0777,true);
                }
                $images_name = $images_info['filename'].'.'.$images_info['extension'];
                $images_url = $_SESSION['css_url'].'/'.$bg_images;
                file_put_contents($image_path.'/'.$images_name,get_file_content($images_url));
                $_SESSION['img'] = $imgs;
                echo '<script>location.reload();</script>';
                exit;
            }else{
                show_image_download(false);
            }
        }else if(count($arrayCss) > 0 ){
            $css_file = array_pop($arrayCss);
            if($css_file){
                $css_content = get_file_content($css_file);
                $re = '/url\(\s*[\'"]?(\S*\.(?:jpe?g|gif|png|otf|eot|svg|ttf|woff))[\'"]?\s*\)[^;}]/i';
                if (preg_match_all($re, $css_content, $matches)) {
                    $imgs = $matches[1];
                }
                $_SESSION['img'] = $imgs;
                $file_info = pathinfo($css_file);
                $_SESSION['css_url'] = $file_info[ 'dirname'];
            }
            $_SESSION['arrayCss'] =  $arrayCss;
            echo '<script>location.reload();</script>';
            exit;
        }
  
        $step++;
        $_SESSION['step'] = $step;
        echo '<script>location.reload();</script>';
        exit;
    break;
    
    case "4":
        /* update html content
        ----------------------------------------------------------------------------*/
        show_step(); // echo current step
        show_image_download(); // echo images downloaded
        
        // load temp html file, keep treserve the break line
        $html_file = file_get_html($path.'/tmp',false,NULL,'-1','-1',true,true,DEFAULT_TARGET_CHARSET,false);
        
        // remore "base" dom element
        foreach($html_file->find('base') as $base){
            $base->outertext = '';
        }
        
        // replace link css
        foreach ($html_file->find('link[rel="stylesheet"]') as $stylesheet){
            $stylesheet_url = cleanUrl($stylesheet->href,true);
            if($stylesheet_url){
                $stylesheet_url = str_replace(h_parse_url($_SESSION['url']),'',$stylesheet_url);
                $path_info = pathinfo($stylesheet_url);
                $file_path = 'asset/css/'.$path_info['filename'].".".$path_info['extension'];
                $stylesheet->href = $file_path;
            }
        }
        // replace link js
        foreach ($html_file->find('script') as $script){
            $script_url = cleanUrl($script->src);
            if($script_url){
                $stylesheet_url = str_replace(h_parse_url($_SESSION['url']),'',$script_url);
                $path_info = pathinfo($script_url);
                $file_path = 'asset/js/'.$path_info['filename'].".".$path_info['extension'];
                $script->src = $file_path;
            }
        }
        
        // recreate images link
        foreach ($html_file->find('img') as $h_img){
            $images_src = $h_img->src;
            if(strpos($images_src,'http')===false){
                $h_img->src =  h_parse_url($_SESSION['url']).'/'.$images_src;
            }
        }
        
        // recreate sub link
        foreach ($html_file->find('a') as $link){
            $link_href = $link->href;
            if(strpos($link_href,'//')===false){
                $link->href = h_parse_url($_SESSION['url']).'/'.$link_href;
            }
        }
        
        // delete temp file and create html file
        unlink($path.'/tmp');
        $html_file->save($path.'/'.$save_file);
        
        $step++;
        $_SESSION['step'] = $step;
        echo '<script>location.reload();</script>';
        exit;
    break;
    
    case "5":
        show_step(true);
        session_destroy(); 
    break;
} // end switch
?>
<div id="bottom">&nbsp;</div>
</body>
</html>
