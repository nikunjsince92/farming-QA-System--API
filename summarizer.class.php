<?php
class Summarizer{
	public $sentences_dic = array();
	public $orginal;
	public $summary;
	public $values = array();
	public $n;
	public $myDictionary = array();
	public $rawData = array();
	
	public function __construct(){
		return true;		
	}
	public function split_content_to_sentences($content){
		$result = preg_split('/(?<=[.?!])\s+(?=[a-z$])/i', $content, -1, PREG_SPLIT_NO_EMPTY);
                return $result;
	}

    public function split_content_to_paragraphs($content){
		return explode("$$$", $content);
	}

	public function sentences_intersection($sent1, $sent2){
		$s1 = explode(" ",$sent1);
		$s2 = explode(" ",$sent2);
		$cs1 = count($s1);
		$cs2 = count($s2);
		if( ($cs1 + $cs2) == 0 )	return 0;

		$i = count( array_intersect($s1,$s2) );
		$avg = $i / (($cs1+$cs2) / 2);
		return $avg;
	}
	public function format_sentence($sentence){
		//$sentence = preg_replace('/[^a-z\d ]/i', '', $sentence);
		$sentence = preg_replace("/[^a-zA-Z0-9\s]/", "", $sentence);
		$sentence = str_replace(" ","",$sentence);
        return $sentence;
	}
	public function get_sentences_ranks($content, $status){
		$sentences = $this->split_content_to_sentences($content);
		$n = count( $sentences );
		$this->n = $n;
		//	if($status==true) 
		//	echo "Total sentences: ".$n;
	//		$rawData['total_sentences']=$n;
		$values = array();
		for($i = 0;$i < $n;$i++){
			$s1 = $sentences[$i];
			for($j = 0;$j < $n;$j++){
				$s2 = $sentences[$j];
				$values[$i][$j] = $this->sentences_intersection($s1, $s2);
			}
		}
		$this->values = $values;
		//	if($status==true) 
		//	print_r($values);
	//		$rawData['intersection']=$values;
		$sentences_dic = array();
		for($i = 0;$i < $n;$i++){
			$score = 0;		
			for($j = 0;$j < $n;$j++){
					if( $i == $j)	continue;
					$score = $score + $values[$i][$j];
			}
			$sentences_dic[ $this->format_sentence( $sentences[$i] ) ] = $score;
		}
		$this->sentences_dic = $sentences_dic;
	//	if($status==true)
		//	print_r($this->sentences_dic);
	//		$rawData['sentences_dic']=$sentences_dic;
		return $sentences_dic;
	}
    
	public function get_best_sentence($paragraph){
		$sentences = $this->split_content_to_sentences($paragraph);
		//print_r($sentences);
		if( count($sentences) < 2 )	return "";
		$best_sentence = "";
		$max_value = 0;
		foreach( $sentences as $s){
			$strip_s = $this->format_sentence($s);
			if( !empty($strip_s) ){
				$me = $this->sentences_dic[$strip_s];
				if( $me > $max_value ){
					$max_value = $me;
					$best_sentence = $s;
				}
			}
		}
		
        return $best_sentence.'.';
	}

    public function get_summary($content, $status){
		$sentences_dic = $this->get_sentences_ranks($content, $status);
		$paragraphs = $this->split_content_to_paragraphs($content);
		$c = count( $paragraphs );
	//	if($status==true)
	//		$rawData['paragraphs']=$paragraphs;
		$this->original = $content;

		$summary = array();
		$z=0;
		foreach( $paragraphs as $p ){
	//	for($i=0; $i<$c; $i++){
		//print_r($p."<br><br>");
			$sentence = $this->get_best_sentence($p);
			
			if( !empty($sentence) ){
				$summary[$z++] = $sentence;
			}
			}
	//	}
		$this->summary = implode("$$$",$summary);
		$this->summary = preg_replace("/\.+/",".",$this->summary);
		$this->summary = explode("$$$", $this->summary);
		if($status==true){
			$rawData['total_sentences']=$this->n;
			$intersection = $this->values;
			$rawData['intersection']=array();
			for($i=0; $i<count($intersection); $i++)
			{
				$midintersection = array();
				for($j=0; $j<count($intersection[$i]); $j++)
				{
					$midintersection[" ".$j." "]=$intersection[$i][$j];
				}
				$rawData["intersection"][" ".$i." "] = $midintersection;
			}
			$rawData['sentences_dic']=$sentences_dic;
			$rawData['summary']=array();
			for($i=0; $i<count($this->summary); $i++)
			{
				$tempinfo = array();
				$paraToSentWithScore = array();
				$tempParaSent = $this->split_content_to_sentences($paragraphs[$i]);
				$tempinfo["para"] = array();
				for($j=0; $j<count($tempParaSent); $j++)
				{	
					$sent = $tempParaSent[$j];
					$score = $sentences_dic[$this->format_sentence($sent)];
					$tempinfo["para"][$j] = array($sent, $score);
				}
				$tempinfo["pickedLine"] = $this->summary[$i];
				array_push($rawData['summary'], $tempinfo);
			}
			
			echo raw_json_encode($rawData);
		}
		return $this->summary;
	}
	
	function how_we_did(){
	    print "<hr />";
	    print "Original Length ". strlen($this->original);
		echo "<br />";
	    print "Summary Length ".strlen($this->summary);
		echo "<br />";
	    print "Summary Ratio: ".(100 - (100 * (strlen($this->summary) / (strlen($this->original)))));
		echo "<br />";
	}

}
