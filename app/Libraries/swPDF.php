<?php
namespace App\Libraries;

use DB;
use PDF;
use TCPDF;
use Carbon\Carbon;
use App\Libraries\dbLib;
use  TCPDF_FONTS;
class swPDF extends TCPDF {

    
   //Page header
   public $pageType = 0;
   public $setSWFont='';
   public $CreatedAt = '';
   public function Header() 
   {
        
      $this->setSWFont = TCPDF_FONTS::addTTFfont(asset('/public/assets/fonts/tahoma.ttf'), 'TrueTypeUnicode', '', 96);  

      $company=dbLib::getCompanyInfo();
      $logo = dbLib::pdfImage($company->id);
      $pt = ($this->pageType==0)?'P':'L';
     
      if($logo)
        $this->Image('@'.$logo['content'], 8, 5, 20, '', $logo['type'], '', 'T', false, 300, '', false, false, 0, false, false, false); 
       
      $X=$this->GetX()+2; $Y=8;      
      $this->SetFont($this->setSWFont, '', 14);
      $this->SetXY($X,$Y);     
      $this->Cell(125, 9,$company->name, 0, false, 'L', 0, '', 0, false, 'M', 'M');
      $this->Ln(6);
      $this->SetX($X);
      $this->SetFont('', '', 10);
      $this->Cell(125, 4, $company->address .' - Ph:'.  $company->phone, 0, false, 'L', 0, '', 0, false, 'M', 'M');
           
      if($pt=='P')
      {
         $this->Ln();
         $this->SetX($X);
      }
      else
      {
         $Y=$this->GetY()+5;
         $this->SetXY(62,$Y);
      } 
      $this->SetFont('', '', 10);
      $this->Cell(125, 4, $company->website .'  - ' . $company->email, 0, false, 'L', 0, '', 0, false, 'M', 'M');
         
          
        
    }
     public  function Footer() {
        $X=40;
        //$Y=100;
       
         // Position at 15 mm from bottom
         $this->SetY(-15);
         $this->SetFont( $this->setSWFont, 'I', 8);
         //$this->SetXY($X,$Y);
        $this->Cell(125, 9,'Print Time:'.Carbon::now(), 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $this->Cell(125, 9,'Created at:'.$this->CreatedAt, 0, false, 'R', 0, '', 0, false, 'M', 'M');
        
        
         // Page number
         $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
     }
     
     
     

    
     public function setTitleFont()
     {
       
        // $fontname1 = TCPDF_FONTS::addTTFfont(asset('/public/assets/fonts/tahoma.ttf'), 'TrueTypeUnicode', '', 96);    
        $this->SetFont($this->setSWFont, 'B', 26);

     }
     public function setTitleFont2($size=15)
     {
       
        // $fontname1 = TCPDF_FONTS::addTTFfont(asset('/public/assets/fonts/tahoma.ttf'), 'TrueTypeUnicode', '', 96);    
        $this->SetFont($this->setSWFont, 'B', $size);

     }

     public function setH1()
     {
       
         $this->SetFont($this->setSWFont, 'B', 16);

     }

     public function setH2()
     {
       
        // $fontname2 = TCPDF_FONTS::addTTFfont(asset('/public/assets/fonts/arial.ttf'), 'TrueTypeUnicode', '', 96);  
        $this->SetFont($this->setSWFont, '', 14);

     }
     public function setH22()
     {
       
        // $fontname2 = TCPDF_FONTS::addTTFfont(asset('/public/assets/fonts/arial.ttf'), 'TrueTypeUnicode', '', 96);  
        $this->SetFont($this->setSWFont, '', 7);

     }
     public function setH3()
     {
       
        // $fontname1 = TCPDF_FONTS::addTTFfont(asset('/public/assets/fonts/tahoma.ttf'), 'TrueTypeUnicode', '', 96);    
        $this->SetFont($this->setSWFont, 'B', 11);

     }
     public function setTableHeading($size=10,$bold='')
     {
            return "font-size:13px; background-color:gray; color:white; font-weight: bold; text-align:center;";

            $this->SetFont($this->setSWFont,$bold,$size);

     }
     public function setT1($size=10,$bold='')
     {
       
        // $fontname2 = TCPDF_FONTS::addTTFfont(asset('/public/assets/fonts/arial.ttf'), 'TrueTypeUnicode', '', 96);  
        $this->SetFont($this->setSWFont,$bold,$size);

     }


     

    }



?>
