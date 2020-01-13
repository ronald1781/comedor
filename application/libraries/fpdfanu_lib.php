<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

require_once APPPATH . "/third_party/fpdf/fpdf.php";

class Fpdfanu_lib extends FPDF {

	function Header()
{
    //Put the watermark
    $this->SetFont('Arial','B',50);
    $this->SetTextColor(255,192,203);
    $this->RotatedText(45,190,'A N U L A D O',45);
}

function RotatedText($x, $y, $txt, $angle)
{
    //Text rotated around its origin
    $this->Rotate($angle,$x,$y);
    $this->Text($x,$y,$txt);
    $this->Rotate(0);
}

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

function Rotate($angle,$x=-1,$y=-1)
{
    if($x==-1)
        $x=$this->x;
    if($y==-1)
        $y=$this->y;
    if($this->angle!=0)
        $this->_out('Q');
    $this->angle=$angle;
    if($angle!=0)
    {
        $angle*=M_PI/180;
        $c=cos($angle);
        $s=sin($angle);
        $cx=$x*$this->k;
        $cy=($this->h-$y)*$this->k;
        $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
    }
}



}