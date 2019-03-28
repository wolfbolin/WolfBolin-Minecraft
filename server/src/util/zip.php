<?php
/**
 * Created by PhpStorm.
 * User: wolfbolin
 * Date: 2019/3/29
 * Time: 2:02
 * @param $zip
 * @param $path
 * @param int $prefix_len
 */

namespace WolfBolin\Util\Zip;

function addFileToZip($zip, $path, $prefix_len=0){
    $handler=opendir($path);
    while(($filename=readdir($handler))!==false){
        if($filename != "." && $filename != ".."){
            if(is_dir($path."/".$filename)){
                addFileToZip($zip, $path."/".$filename, $prefix_len);
            }else{ //将文件加入zip对象
                $file_path = $path."/".$filename;
                $zip_path = substr($file_path, $prefix_len);
                $zip->addFile($file_path, $zip_path);
            }
        }
    }
    closedir($handler);
}