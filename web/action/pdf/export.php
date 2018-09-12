<?php
require_once 'ext/vendor/autoload.php';

use fly\Action;
/**
 * 生成PDF文档
 */
class export extends Action {
    public function getFilter() {
    }
    public function exec(array $params = null) {
    	// export new PDF document
    	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    	
    	// set document information
    	$pdf->SetCreator(PDF_CREATOR);
    	$pdf->SetAuthor('Nicola Asuni');
    	$pdf->SetTitle('TCPDF Example 012');
    	$pdf->SetSubject('TCPDF Tutorial');
    	$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
    	
    	// disable header and footer
    	$pdf->setPrintHeader(false);
    	$pdf->setPrintFooter(false);
    	
    	// set default monospaced font
    	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    	
    	// set margins
    	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    	
    	// set auto page breaks
    	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    	
    	// set image scale factor
    	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    	
    	// set some language-dependent strings (optional)
    	if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    		require_once(dirname(__FILE__).'/lang/eng.php');
    		$pdf->setLanguageArray($l);
    	}
    	
    	// ---------------------------------------------------------
    	
    	// set font
    	$pdf->SetFont('helvetica', '', 10);
    	
    	// add a page
    	$pdf->AddPage();
    	
    	$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '10,20,5,10', 'phase' => 10, 'color' => array(255, 0, 0));
    	$style2 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(255, 0, 0));
    	$style3 = array('width' => 1, 'cap' => 'round', 'join' => 'round', 'dash' => '2,10', 'color' => array(255, 0, 0));
    	$style4 = array('L' => 0,
    			'T' => array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => '20,10', 'phase' => 10, 'color' => array(100, 100, 255)),
    			'R' => array('width' => 0.50, 'cap' => 'round', 'join' => 'miter', 'dash' => 0, 'color' => array(50, 50, 127)),
    			'B' => array('width' => 0.75, 'cap' => 'square', 'join' => 'miter', 'dash' => '30,10,5,10'));
    	$style5 = array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 64, 128));
    	$style6 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '10,10', 'color' => array(0, 128, 0));
    	$style7 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(255, 128, 0));
    	
    	// Line
    	$pdf->Text(5, 4, 'Line examples');
    	$pdf->Line(5, 10, 80, 30, $style);
    	$pdf->Line(5, 10, 5, 30, $style2);
    	$pdf->Line(5, 10, 80, 10, $style3);
    	
    	// Polygonal Line
    	$pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 164)));
    	$pdf->PolyLine(array(80,165,90,160,100,165,110,160,120,165,130,160,140,165), 'D', array(), array()); 	
    	
    	// - . - . - . - . - . - . - . - . - . - . - . - . - . - . -
    	
    	// ellipse
    	
    	// add a page
    	$pdf->AddPage();
    	
    	$pdf->Cell(0, 0, 'Arc of Ellipse');
    	
    	// center of ellipse
    	$xc=100;
    	$yc=100;
    	
    	// X Y axis
    	$pdf->SetDrawColor(200, 200, 200);
    	$pdf->Line($xc-50, $yc, $xc+50, $yc);
    	$pdf->Line($xc, $yc-50, $xc, $yc+50);
    	
    	// ellipse axis
    	$pdf->SetDrawColor(200, 220, 255);
    	$pdf->Line($xc-50, $yc-50, $xc+50, $yc+50);
    	$pdf->Line($xc-50, $yc+50, $xc+50, $yc-50);
    	
    	// ellipse
    	$pdf->SetDrawColor(200, 255, 200);
    	$pdf->Ellipse($xc, $yc, 30, 15, 45, 0, 360, 'D', array(), array(), 2);
    	
    	// ellipse arc
    	$pdf->SetDrawColor(255, 0, 0);
    	$pdf->Ellipse($xc, $yc, 30, 15, 45, 45, 90, 'D', array(), array(), 2);
    	
    	
    	// ---------------------------------------------------------
    	
    	//Close and output PDF document
    	$pdf->Output('example_012.pdf', 'I');
    	
    	//============================================================+
    	// END OF FILE
    	//============================================================+
      return ['status' => 'ok'];
    }
    public function getPrivilege() {
      return 0;
    }
}
