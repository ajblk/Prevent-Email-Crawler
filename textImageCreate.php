<?php
error_reporting(E_ALL & ~E_NOTICE);
 
 require_once "fontsqlconfig.php";
 
$string = "@";
$fontPath = "fonts/";
$fontFileName ="times.ttf"; // default Normal Times New Roman

$Best=array();


$emailTextObj = $_GET['emailTextObj'];
$emailTextObjJSONDecoded= json_decode($emailTextObj, true);
extract($emailTextObjJSONDecoded, EXTR_PREFIX_ALL, "emxm");
$red = $green = $blue = 0; // default black color

if	(	is_numeric($emxm_color[0]) && intval($emxm_color[0]) >=0 	&&	intval($emxm_color[0]) <=255 &&
		is_numeric($emxm_color[1]) && intval($emxm_color[1]) >=0	&&	intval($emxm_color[1]) <=255 &&
		is_numeric($emxm_color[2]) && intval($emxm_color[2]) >=0 	&&	intval($emxm_color[2]) <=255 
	)	
{
	$red	=intval($emxm_color[0]);
	$green	=intval($emxm_color[1]);
	$blue	=intval($emxm_color[2]);
}

$fontSize = 16; // default size

if	(	is_numeric($emxm_fontSize[0]) && intval($emxm_fontSize[0]) >0 	&& ( strtolower($emxm_fontSize[1])=="px"  || strtolower($emxm_fontSize[1])=="pt" ))	
{
	$fontSize = intval($emxm_fontSize[0]);
}

$fontRatio = $fontSize/ 72;

//$emxm_fontFamily
if(strlen($emxm_fontFamily)>0)
{
	$fontFamily = $mysqli->real_escape_string($emxm_fontFamily); 
	$fontFamilyWithOutSpaces = preg_replace('/\s+/', '', $fontFamily);
	
	$fontStyle = 'normal'; // default font Style
	
	if(strlen($emxm_fontStyle)>0)
	{
		$fontStyle = strtolower($mysqli->real_escape_string($emxm_fontStyle)); 
	}	

	$fontWeight = 400; // default font weight

	if	(	is_numeric($emxm_fontWeight) && intval($emxm_fontWeight) >0 )	
	{
		$fontWeight = intval($emxm_fontWeight);
	}

	$selQry = "	SELECT * FROM `fontslist`
					WHERE 	`fontName` 				LIKE '%$fontFamily%'
						OR 	`fontSubfamilyID` 		LIKE '%$fontFamily%'
						OR 	`fontFullName` 			LIKE '%$fontFamily%'
						OR 	`fontPostscriptName` 	LIKE '%$fontFamily%'
						OR 	`fontName` 				LIKE '%$fontFamilyWithOutSpaces%'
						OR 	`fontSubfamilyID` 		LIKE '%$fontFamilyWithOutSpaces%'
						OR 	`fontFullName` 			LIKE '%$fontFamilyWithOutSpaces%'
						OR 	`fontPostscriptName` 	LIKE '%$fontFamilyWithOutSpaces%'
	";

    if ($result = $mysqli->query($selQry)) {
	
		$rowCount = $result->num_rows;		
		
		$row = $result->fetch_all(MYSQLI_ASSOC);

		if($rowCount==1)
		{
			$fontFileName  = $row[0]['fileName'];
		}
		else
		{
			for($i=0; $i<$rowCount; $i++)
			{
				$curr = $row[$i];
				$curr['fontSubfamily']  = strtolower($curr['fontSubfamily']);
				
				if( 	$fontStyle=="normal" && ((strrpos($curr['fontSubfamily'], "normal") !==false ) ||( strrpos($curr['fontSubfamily'], "regular")!==false )) && 
						$curr['fontWeight'] == $fontWeight )
				{
					$Best[0][0] = $i;
					break;
				}
				elseif(	$fontStyle=="italic" && 
						(	strrpos($curr['fontSubfamily'], "italic")!==false 		|| strrpos($curr['fontSubfamily'], "italique")!==false 		||
							strrpos($curr['fontFullName'], "italic")!==false 		|| strrpos($curr['fontFullName'], "italique")!==false 		||
							strrpos($curr['fontSubfamilyID'], "italic")!==false 	|| strrpos($curr['fontSubfamilyID'], "italique")!==false	||
							strrpos($curr['fontPostscriptName'], "italic")!==false 	|| strrpos($curr['fontPostscriptName'], "italique")!==false) && 
						$curr['fontWeight'] == $fontWeight )
				{
					$Best[0][0] = $i;
					break;
				}	
				elseif(	$fontStyle=="oblique" && 
						(	strrpos($curr['fontSubfamily'], "oblique")!==false		||
							strrpos($curr['fontFullName'], "oblique")!==false 		|| 
							strrpos($curr['fontSubfamilyID'], "oblique")!==false 	|| 
							strrpos($curr['fontPostscriptName'], "oblique")!==false ) && 
						$curr['fontWeight'] == $fontWeight )
				{
					$Best[0][0] = $i;
					break;
				}
				elseif($curr['fontWeight'] == $fontWeight )
				{
					$Best[1][0] = $i;
				}	
				elseif(	$fontStyle=="normal" && (strrpos($curr['fontSubfamily'], "normal")!==false || strrpos($curr['fontSubfamily'], "regular")!==false) )
				{
					$diff= $curr['fontWeight'] - $fontWeight;
					if(is_null($Best[2][0]))
					{
						$Best[2][0] = $i;
						$Best[2][1] = $diff;
					}
					elseif( abs($diff)<abs($Best[2][1])	||	(	abs($diff)==abs($Best[2][1])	&&	$diff<0 && $Best[2][1] > 0	)	)
					{
						$Best[2][0] = $i;
						$Best[2][1] = $diff;					
					}					
				}
				elseif(	$fontStyle=="italic" && 
						(	strrpos($curr['fontSubfamily'], "italic")!==false 		|| strrpos($curr['fontSubfamily'], "italique")!==false 		||
							strrpos($curr['fontFullName'], "italic")!==false 		|| strrpos($curr['fontFullName'], "italique")!==false 		||
							strrpos($curr['fontSubfamilyID'], "italic")!==false 	|| strrpos($curr['fontSubfamilyID'], "italique")!==false	||
							strrpos($curr['fontPostscriptName'], "italic")!==false 	|| strrpos($curr['fontPostscriptName'], "italique")!==false	) )
				{
					$diff= $curr['fontWeight'] - $fontWeight;
					if(is_null($Best[2][0]))
					{
						$Best[2][0] = $i;
						$Best[2][1] = $diff;
					}
					elseif( abs($diff)<abs($Best[2][1])	||	(	abs($diff)==abs($Best[2][1])	&&	$diff<0 && $Best[2][1] > 0	)	)
					{
						$Best[2][0] = $i;
						$Best[2][1] = $diff;					
					}	
				}	
				elseif(	$fontStyle=="oblique" && 
						(	strrpos($curr['fontSubfamily'], "oblique")!==false		||
							strrpos($curr['fontFullName'], "oblique")!==false 		|| 
							strrpos($curr['fontSubfamilyID'], "oblique")!==false 	|| 
							strrpos($curr['fontPostscriptName'], "oblique")!==false ) )
				{
					$diff= $curr['fontWeight'] - $fontWeight;
					if(is_null($Best[2][0]))
					{
						$Best[2][0] = $i;
						$Best[2][1] = $diff;
					}
					elseif( abs($diff)<abs($Best[2][1])	||	(	abs($diff)==abs($Best[2][1])	&&	$diff<0 && $Best[2][1] > 0	)	)
					{
						$Best[2][0] = $i;
						$Best[2][1] = $diff;					
					}	
				}			
			}
			
			//for each Best
			for($i=0;$i<3;$i++)
			{
				if(!is_null($Best[$i][0]))
				{
					$index = $Best[$i][0];
					$fontFileName  = $row[$Best[$i][0]]['fileName'];
					break;
				}
			}
		}
    }
    $result->close(); 	
}
$font = $fontPath  . $fontFileName;

if(strlen($emxm_content)>0)
{
	
	$stringLength = strlen($string) ;
	
	if(strrpos(strtolower($emxm_content),"default01at")=== 0)
	{
		$string = "@";
		
		$bbox = imagettfbbox($fontSize, 0, $font, $string);	
		$fontWidth  = abs($bbox[2]-$bbox[0]);
		$fontHeight = abs($bbox[7]-$bbox[1]);
		$canvasWidth =  $stringLength * $fontSize ;
		$canvasHeight =	 $fontSize * 1.2;
		$stringY =$fontSize*.9;
		$stringX = 2 ;
		$fontSizeHeightRatio = $fontHeight / $fontSize ;
		$fontSize = $fontSize / $fontSizeHeightRatio;
		
	}
	else if(strrpos(strtolower($emxm_content),"default02dot")===0)
	{
		$string = ".";
		$canvasWidth = $stringLength * $fontSize * 0.6 ;
		$canvasHeight =	$fontSize;	
		$bbox = imagettfbbox($fontSize, 0, $font, $string);
		$fontWidth  = abs($bbox[2]-$bbox[0]);
		$fontHeight = abs($bbox[7]-$bbox[1]);
		$stringX = 0 ;
		$stringY = $canvasHeight-$fontHeight;	
		
	}
	else
	{
		$string = $emxm_content;
		$canvasWidth = $stringLength * $fontSize + (48*$fontRatio) ;
		$canvasHeight =	$fontSize + (24 * $fontRatio) ;		
		$stringX = 1 ;
		$stringY = $fontSize + 1;	
	}
}

	$im = @imagecreatetruecolor($canvasWidth  , $canvasHeight );
    imagealphablending($im, false);
    imagesavealpha($im, true);	
	
	// $black = imagecolorallocate($im, 0, 0, 0);
    $white = imagecolorallocatealpha($im, 255, 255, 255, 127);
    imagefill($im, 0, 0, $white);
	
	$fontColor = imagecolorallocate($im, $red, $green, $blue);
	imagettftext($im, $fontSize, 0, $stringX,$stringY , $fontColor, $font, $string);
	
	
	header("Content-type: image/png");
    imagepng($im);
    imagedestroy($im);

?>