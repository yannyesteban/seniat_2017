<?php
//define ("EXAMPLE_TMP_SERVERPATH","TEMP/");

//define ("EXAMPLE_TMP_URLRELPATH","TEMP/");

include('phpqrcode/qrlib.php');

//QRimage::png('PHP QR Code :)'); 

//exit;

//QRimage::png('PHP QR Code :)'); exit;
    

    // how to configure silent zone (frame) size
    /*
    $tempDir = EXAMPLE_TMP_SERVERPATH;
    
    $codeContents = '123456DEMO';
    
    // generating
    
    // frame config values below 4 are not recomended !!!
   QRcode::png($codeContents, $tempDir.'008_4.png', QR_ECLEVEL_L, 3, 4);  
    QRcode::png($codeContents, $tempDir.'008_6.png', QR_ECLEVEL_L, 3, 6);
    QRcode::png($codeContents, $tempDir.'008_12.png', QR_ECLEVEL_L, 3, 10);

    // displaying
    echo '<img src="'.EXAMPLE_TMP_URLRELPATH.'008_4.png" />';
    echo '<img src="'.EXAMPLE_TMP_URLRELPATH.'008_6.png" />';
    echo '<img src="'.EXAMPLE_TMP_URLRELPATH.'008_12.png" />'; 
	
	
	exit;

*/
    // HTML5 Canvas renderer with custom tag
    /*
    $jsCanvasCode = QRcode::canvas('PHP QR Code :)', 'my-target-canvas-id', QR_ECLEVEL_L, 200);
    
    // reqired JS rendering lib
    echo '<script type="text/javascript" src="../lib/js/qrcanvas.js"></script>';
    
    // Canvas and JS code output
    echo '<canvas id="my-target-canvas-id" width="300" height="250" style="background: yellow;"></canvas>';
    echo $jsCanvasCode; 
	
	exit;
	*/
    $param = "yanny";//$_GET['id']; // remember to sanitize that - it is user input!
    
    // we need to be sure ours script does not output anything!!!
    // otherwise it will break up PNG binary!
    
    ob_start("callback");
	$codeText = 'DEMO - '.$param;
	if(isset($_GET["chl"])){
		
		$codeText = $_GET["chl"];
		
	}
    
    // here DB request or some processing
    
    
    // end of processing here
    $debugLog = ob_get_contents();
    ob_end_clean();
    
    // outputs image directly into browser, as PNG stream
    QRcode::png($codeText);
	
	exit;

    
    
    // outputs image directly into browser, as PNG stream
    QRimage::png('PHP QR Code :)'); 
	
	
?>