<?php
function break_string($text,$pcount){
//$sent = explode(".",$text);
$sent = preg_split('/(?<=[.?!])\s+(?=[a-z])/i', $text, -1, PREG_SPLIT_NO_EMPTY);
$sent = array_slice($sent, 0, count($sent)-1);
$num = count($sent);
$lines = ceil($num/$pcount);
$para='';
$a=0;
$counter=0;
$outline = "";
while(1){
        $outline = $outline.$sent[$counter]." ";
		if(++$counter%$lines==0) 
		{
			//array_push($para, $outline);
			$para = $para.$outline."$$$";
			$outline = "";
		}
		
		if($counter>=$num) 
		{
		$para = $para.$outline;
		break;
		}
		
}
return $para;
}
?>