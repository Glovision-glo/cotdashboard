<?php
include '../../tcpdf/examples/tcpdf_include.php';
  class PdfGeneretor{
      private $logo='gvk-emri_527.jpg';
      private $pdfheading;
      public $pdfsubheading;
      public $savelocation;
      public $saveordownload;
      function pdfcreation($data,$filename,$logo,$heading,$subheading){
          if($filename==null)$filename='xya';
          if($logo==null)$logo='gvk-emri_527.jpg';
          if($heading==null)$heading='';
          if($subheading==null)$subheading='';
          $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
          $pdf->SetCreator(PDF_CREATOR);
          $pdf->SetAuthor('Glovision');
          $pdf->SetTitle('Glovision');
          $pdf->SetSubject('Glovision Reports');
          //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
          $pdf->PDF_HEADER_STRING="Hello";
          $pdf->SetHeaderData($logo, PDF_HEADER_LOGO_WIDTH, $heading, $subheading, array(0,64,255), array(0,64,128));

        //  $pdf->setFooterData(array(0,64,0), array(0,64,128));
          $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, 'glovision.co', PDF_FONT_SIZE_MAIN));
          $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, 'glovision.co', PDF_FONT_SIZE_DATA));
          $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
          $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
          $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
          $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
          $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
          $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
          $pdf->setFontSubsetting(true);
          $pdf->SetFont('dejavusans', '', 5, '', true);
          $pdf->AddPage();
          $pdf->setTextShadow(array('enabled'=>false, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

          $html=<<<EOD
           $data
EOD;
          $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
          $pdf->Output('/var/www/html/images/'.$filename.'.pdf', 'F');
      }



   }
?>

