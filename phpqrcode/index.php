<?php    
    include "qrlib.php";    
    
    //html PNG location prefix
    $PNG_WEB_DIR = 'temp/';
    //ofcourse we need rights to create temp dir
    if (!file_exists($PNG_WEB_DIR))
        mkdir($PNG_WEB_DIR);
    //
    $errorCorrectionLevel = 'Q';
    $matrixPointSize = 10;
    $data="onlaf.org/index.php?r=1&c=m001.00000.00200";        
        // user data
        $filename = $PNG_WEB_DIR.'QR'.'m001.00000.00200'.'.png';
        QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
    //display generated file
    echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" />';  
    