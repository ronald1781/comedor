<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

require_once APPPATH . "/third_party/fpdf/fpdf.php";

class Fpdf_lib extends FPDF {



	function vcell($c_width,$c_height,$x_axis,$text){ 
		$w_w=$c_height/3;
		$w_w_1=$w_w+2;
		$w_w1=$w_w+$w_w+$w_w+3;
		$len=strlen($text);
		$lengthToSplit=55;

		if($len>$lengthToSplit){
			$w_text=str_split($text,$lengthToSplit);
			$this->SetX($x_axis);
			$this->Cell($c_width,$w_w_1,$w_text[0],'','','');
			if(isset($w_text[1])) {
				$this->SetX($x_axis);
				$this->Cell($c_width,$w_w1,$w_text[1],'','','');
			}
			if(isset($w_text[2])) {
				$w_w2=$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+4;
				$this->SetX($x_axis);
				$this->Cell($c_width,$w_w2,$w_text[2],'','','');
			}
			if(isset($w_text[3])) {
				$w_w3=$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+5;
				$this->SetX($x_axis);
				$this->Cell($c_width,$w_w3,$w_text[3],'','','');
			}
			if(isset($w_text[4])) {
				$w_w4=$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+6;
				$this->SetX($x_axis);
				$this->Cell($c_width,$w_w4,$w_text[4],'','','');
			}
			if(isset($w_text[5])) {
				$w_w5=$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+7;
				$this->SetX($x_axis);
				$this->Cell($c_width,$w_w5,$w_text[5],'','','');
			}
			if(isset($w_text[6])) {
				$w_w6=$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+$w_w+8;
				$this->SetX($x_axis);
				$this->Cell($c_width,$w_w6,$w_text[6],'','','');
			}
			//$this->SetX($x_axis);
			//$this->Cell($c_width,$c_height,'','LR',0,'L',0);
		}
		else{
			$this->SetX($x_axis);
			$this->Cell($c_width,$c_height,$text,'LR',0,'L',0);
		}


	}
var $angle=0;




}