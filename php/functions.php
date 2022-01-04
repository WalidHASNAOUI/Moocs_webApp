<?php 
function configSize($sizeKb)
{
    if($sizeKb >= 1000)
        if($sizeKb >= pow(10,9))
            return round($sizeKb/pow(1024,3),2)."GB";
        elseif($sizeKb >= pow(10,6))
            return round($sizeKb/pow(1024,2),2)."MB";
        else    
            return round($sizeKb/1024,2)."KB";
    else 
        return round($sizeKb,2)."B";
}
?>