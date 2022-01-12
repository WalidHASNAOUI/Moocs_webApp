<?php 

// Generate the size of file 
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

// Generate icons (folder - .mp4 - .pdf)
function generateIcon($filePath)
{
    if(is_dir($filePath))
        return '<i class="fas fa-folder">';
    else {
        switch(pathinfo($filePath)["extension"])
        {
            case "mp4":
                return '<i class="fas fa-file-video"></i>';
            case "pdf":
                return '<i class="fas fa-file-pdf"></i>';
        }
    }
}

?>