<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
$start = microtime(true);
include("google.php");
include("simple_html_dom.php");
include("summarizer.class.php");
include("para.php");
function raw_json_encode($input) {

    return preg_replace_callback(
        '/\\\\u([0-9a-zA-Z]{4})/',
        function ($matches) {
            return mb_convert_encoding(pack('H*',$matches[1]),'UTF-8','UTF-16');
        },
        json_encode($input)
    );

}
function curl($url,$params = array(),$is_coockie_set = false)
{


$str = ''; $str_arr= array();
foreach($params as $key => $value)
{
$str_arr[] = urlencode($key)."=".urlencode($value);
}
if(!empty($str_arr))
$str = '?'.implode('&',$str_arr);

/* STEP 3. visit cookiepage.php */

$Url = $url.$str;

$ch = curl_init ($Url);
curl_setopt ($ch, CURLOPT_COOKIEFILE, $ckfile);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);

$output = curl_exec ($ch);
return $output;
}

function Translate($word,$conversion = 'hi_to_en')
{
$word = urlencode($word);

$url = 'http://translate.google.com/translate_a/t?client=t&text='.$word.'&hl=en&sl=en&tl=hi&ie=UTF-8&oe=UTF-8&multires=1&otf=1&ssel=3&tsel=3&sc=1';
$name_en = curl($url);
$name_en = explode('"',$name_en);
return  $name_en[1];
}

function TranslateArray($arr)
{
$retarr = array();
for($i=0; $i<count($arr); $i++)
	{
	array_push($retarr, Translate($arr[$i],'en_to_hi'));
	}
return $retarr;
}
function html2text($Document) {
    $Rules = array ('@<script[^>]*?>.*?</script>@si',
                    '@<[\/\!]*?[^<>]*?>@si',
                    '@([\r\n])[\s]+@',
                    '@&(quot|#34);@i',
                    '@&(amp|#38);@i',
                    '@&(lt|#60);@i',
                    '@&(gt|#62);@i',
                    '@&(nbsp|#160);@i',
                    '@&(iexcl|#161);@i',
                    '@&(cent|#162);@i',
                    '@&(pound|#163);@i',
                    '@&(copy|#169);@i',
                    '@&(reg|#174);@i',
                    '@&#(d+);@e'
             );
    $Replace = array ('',
                      '',
                      '',
                      '',
                      '&',
                      '<',
                      '>',
                      ' ',
                      chr(161),
                      chr(162),
                      chr(163),
                      chr(169),
                      chr(174),
                      'chr()'
                );
  return preg_replace($Rules, $Replace, $Document);
}
function summary($len,$finalhtml){
	$ch = curl_init("http://api.smmry.com/&SM_API_KEY=".$key."&SM_LENGTH=".$len."&SM_WITH_BREAK");

	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Expect:"));
	// IMPORTANT! Without ^this^ any article over 1000 characters will make SMMRY throw a 417 http error
	curl_setopt($ch, CURLOPT_POST, true); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, "sm_api_input=".$finalhtml);// Your variable is sent as POST
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
	curl_setopt($ch, CURLOPT_TIMEOUT, 20);
	$return = json_decode(curl_exec($ch), true);//You're summary is now stored in $return['sm_api_content'].
	curl_close($ch);
	$arr = explode("[BREAK]", $return['sm_api_content']);
	return array_slice($arr, 0, count($arr)-1);
	}
$response = array();
if(!isset($_GET["query"]))
{
$response["status"] = "missing query";
echo json_encode($response);
die("");
}

$response["status"] = "OK";
$response["author"] = "Nikunj Sharma";

$query = $_GET["query"];

$url = getUrl($query);
$urlYoutube = getUrlYoutube($query);
$html = file_get_html($url);
$vartemp;
foreach($html->find('ol.steps_list_2 li') as $element) 
      { 
	       $element->find(".step_num",0)->innertext = '';
	       $vartemp .= $element->outertext  . " ";
	  
      }
	  $finalhtml = html2text($vartemp);
	  $finalhtml = preg_replace('/\s+/', ' ',$finalhtml);
	  $finalhtml = preg_replace('/\[.*?\]/', '',$finalhtml);
	  $response["original"] = $finalhtml;

    $word_count = str_word_count($finalhtml);
	
function smart($word_count){
	if($word_count<=1200)
		return 4;
	else
		return 6;
}


$para = break_string($finalhtml,smart($word_count));
$st = new Summarizer();
$sums = $st->get_summary($para, true);
?>