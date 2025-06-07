<?php

use Codedge\Fpdf\Fpdf\Fpdf;


class WigaPDF extends Fpdf {

    var $angle=0;

    public $title = null;

    public $filename = null;

    public $rowFonts = null;

    function __construct($orientation='P', $unit='mm', $size='A4',$title = null) {
        parent::__construct($orientation, $unit, $size);
        if($title)
        {
            $this->SetTitle($title);
            $this->title = $title;

            $this->filename = $title.'.pdf';
        }

        $this->SetAutoPageBreak(true,15);
        
        $this->generateAuthor();
        
        $this->AddFont('BernardMTCondensed', '', 'BernardMTCondensed.php',public_path('fonts'));
        $this->AddFont('SPD', '', 'Sugo-Pro-Display-Regular-trial.php',public_path('fonts'));
        $this->AddFont('OpenSans', '', 'OpenSans-Regular.php',public_path('fonts'));
    }
    
    function generateAuthor()
    {
        $this->SetAuthor(env('APP_AUTHOR'));  
        $this->SetCreator(env('APP_NAME'));
    }

}