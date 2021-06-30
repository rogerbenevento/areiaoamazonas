<?php
$this->layout = '';
header("Content-type: application/pdf"); 
/*$pdf = new PDF( 'L', 'mm', 'A4' );
$pdf->AddPage();
$pdf->SetFont( 'Arial', 'B', 10 );

$pdf->Cell( 190, 10, 'Relação de Clientes', 0, 1, 'R' );
$pdf->SetTitle( 'Rogerio' );

echo $pdf->Output( 'teste.pdf', 'D' );*/

$this->Pdf->create( 'L' );
$this->Pdf->setFont();
$this->Pdf->title( 'Relação de Clientes' );

$this->Pdf->setFont( 'Arial', 'B', 10 );
$this->Pdf->write( 'Nome', 'B', 0, 80 );
$this->Pdf->write( 'Telefone', 'B', 0, 30 );
$this->Pdf->write( 'Email', 'B', 0, 100 );
$this->Pdf->write( 'Cidade/UF', 'B', 0, 50 );
$this->Pdf->write( 'Cadastrado', 'B', 1, 20 );

$this->Pdf->setFont( null, '', 8 );
foreach ( $rows as $row ) {
	$this->Pdf->write( $row['Cliente']['nome'], 'B', 0, 80 );
	$this->Pdf->write( $row['Cliente']['telefone'], 'B', 0, 30 );
	$this->Pdf->write( $row['Cliente']['email'], 'B', 0, 100 );
	$this->Pdf->write( $row['Cidade']['nome'] .' / '. $row['Estado']['uf'], 'B', 0, 50 );
	$this->Pdf->write( $this->Time->format( 'd/m/Y', $row['Cliente']['created'] ), 'B', 1, 20 );
}
$this->Pdf->setFont( null, 'B', 8 );
$this->Pdf->write( 'Total de registros cadastrados: '. count( $rows ), 0, 1, 180 );

$this->Pdf->close( 'relacao-clientes', 'D' );