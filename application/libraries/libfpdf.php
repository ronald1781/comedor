<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once APPPATH . "/third_party/fpdf/fpdf.php";

class Libfpdf extends FPDF {

    public function __construct() {
        parent::__construct();
    }

    function MultiCellBlt($w, $h, $blt=º, $txt, $border = 0, $align = 'J', $fill = false) {
        //Get bullet width including margins
        $blt_width = $this->GetStringWidth($blt) + $this->cMargin * 2;

        //Save x
        $bak_x = $this->x;

        //Output bullet
        $this->Cell($blt_width, $h, $blt, 0, '', $fill);

        //Output text
        $this->MultiCell($w - $blt_width, $h, $txt, $border, $align, $fill);

        //Restore x
        $this->x = $bak_x;
    }

    function MultiCellBltArray($w, $h, $blt_array, $border = 0, $align = 'J', $fill = 0) {
        if (!is_array($blt_array)) {
            die('MultiCellBltArray requires an array with the following keys: bullet, margin, text, indent, spacer');
            exit;
        }

        //Save x
        $bak_x = $this->x;

        for ($i = 0; $i < sizeof($blt_array['text']); $i++) {
            //Get bullet width including margin
            $blt_width = $this->GetStringWidth($blt_array['bullet'] . $blt_array['margin']) + $this->cMargin * 2;

            // SetX
            $this->SetX($bak_x);

            //Output indent
            if ($blt_array['indent'] > 0)
                $this->Cell($blt_array['indent']);

            //Output bullet
            $this->Cell($blt_width, $h, $blt_array['bullet'] . $blt_array['margin'], 0, '', $fill);

            //Output text
            $this->MultiCell($w - $blt_width, $h, $blt_array['text'][$i], $border, $align, $fill);

            //Insert a spacer between items if not the last item
            if ($i != sizeof($blt_array['text']) - 1)
                $this->Ln($blt_array['spacer']);

            //Increment bullet if it's a number
            if (is_numeric($blt_array['bullet']))
                $blt_array['bullet'] ++;
        }

        //Restore x
        $this->x = $bak_x;
    }

}
