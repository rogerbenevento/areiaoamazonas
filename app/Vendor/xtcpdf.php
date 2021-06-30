 <?php
 App::import('Vendor','tcpdf/tcpdf');
 
class XTCPDF  extends TCPDF
{

    var $font = 'helvetica';
    var $xheadertext  = 'PDF created using CakePHP and TCPDF';
    var $xheadercolor = array(0,0,200);
    var $xfootertext  = 'Copyright Â© %d XXXXXXXXXXX. All rights reserved.';
    var $xfooterfont  = PDF_FONT_NAME_MAIN ;
    var $xfooterfontsize = 8 ;
    var $cabecalho='';
	var $flag_cabecalho = true;
	var $flag_rodape = true;
	
	public function DisableCabecalho() {	$this->flag_cabecalho = false;}
	public function DisableRodape() {$this->flag_rodape = false;}
	public function EnableCabecalho() {	$this->flag_cabecalho = true;}
	public function EnableRodape() {$this->flag_rodape = true;}
    
	/**
    * Overwrites the default header
    * set the text in the view using
    *    $fpdf->xheadertext = 'YOUR ORGANIZATION';
    * set the fill color in the view using
    *    $fpdf->xheadercolor = array(0,0,100); (r, g, b)
    * set the font in the view using
    *    $fpdf->setHeaderFont(array('YourFont','',fontsize));
    */
    function Header()
    {
        $this->AliasNbPages(); 
        if($this->flag_cabecalho){
			list($r, $b, $g) = $this->xheadercolor;

			//$this->Image( 'images/logo.png', 10, 8, 50); // importa uma imagem
			$this->SetFont($this->font, '', 8); 
			$this->SetX(45);
			$this->Cell(100, 8, "");
			$this->SetFont($this->font, '', 10);
			$this->SetX(-30);
			$x=$this->GetX();
			$y=$this->GetY();
			if($this->CurOrientation=='L'){
				$this->SetXY(270, 10);
			}else{
				$this->SetXY(180, 10);
			}
			$this->Cell(30, 10, "Página: ".$this->PageNo()."/".$this->getAliasNbPages(), 0, 1); // imprime p�gina X/Total de P�ginas
			$this->SetXY($x, $y);
			$this->SetX(-10);
			$this->line(10, 22, $this->GetX(), 22); // Desenha uma linha
			if ($this->cabecalho) { // Se tem o cabecalho, imprime
				$this->SetFont('Arial', '', 10);
				$this->SetX(10);
				$this->Cell($this->GetStringWidth($this->cabecalho), 5, $this->cabecalho, 0, 1);
			}
		}
        $this->SetXY(10, 125);
//        $this->
//        $this->setY(10); // shouldn't be needed due to page margin, but helas, otherwise it's at the page top
//        $this->SetFillColor($r, $b, $g);
//        $this->SetTextColor(0 , 0, 0);
//        $this->Cell(0,20, '', 0,1,'C', 1);
//        $this->Text(15,26,$this->xheadertext );
    }

    /**
    * Overwrites the default footer
    * set the text in the view using
    * $fpdf->xfootertext = 'Copyright Â© %d YOUR ORGANIZATION. All rights reserved.';
    */
    function Footer(){ 
		// Rodap� : imprime a hora de impressao e Copyright
		if($this->flag_rodape){
			$this->SetXY(-10, -15);
			$this->line(10, $this->GetY()-2, $this->GetX(), $this->GetY()-2);
			$this->SetX(10);
			$this->SetFont('Courier', 'BI', 8);
			$data = strftime("%d/%m/%Y");
			$this->Cell(100, 6, "Impresso : ".$data, 0, 0, 'R');
		}
    }
}
?> 