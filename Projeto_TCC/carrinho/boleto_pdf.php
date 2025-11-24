<?php

var_dump(file_exists('../libs/fpdf/fpdf.php'));
exit;

require('../libs/fpdf/fpdf.php');


class BoletoPDF extends FPDF {
    function Header() {
        // Logo (se quiser)
        // $this->Image('logo.png',10,6,30);
        $this->SetFont('Arial','B',15);
        $this->Cell(0,10,'Boleto Bancario - MaxAcess',0,1,'C');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'MaxAcess - www.maxacess.com.br - Página '.$this->PageNo(),0,0,'C');
    }

    function corpoBoleto($dados) {
        $this->SetFont('Arial','',12);

        $this->Cell(0,10,'Beneficiario: MaxAcess Ltda',0,1);
        $this->Cell(0,10,'Cliente: '.$dados['cliente'],0,1);
        $this->Cell(0,10,'Valor: R$ '.number_format($dados['valor'], 2, ',', '.'),0,1);
        $this->Cell(0,10,'Vencimento: '.$dados['vencimento'],0,1);
        $this->Cell(0,10,'Codigo de Barras:',0,1);

        // Código de barras fake (apenas linha)
        $this->SetFillColor(0,0,0);
        $this->Rect(10, $this->GetY(), 180, 15, 'F');
        $this->Ln(20);

        $this->SetFont('Arial','I',10);
        $this->Cell(0,10,'Este boleto é apenas para demonstração.',0,1,'C');
    }
}

// Dados do boleto (podem vir de sessão, banco, etc)
$dadosBoleto = [
    'cliente' => 'Fulano de Tal',
    'valor' => 150.75,
    'vencimento' => date('d/m/Y', strtotime('+7 days')),
];

// Gerar PDF
$pdf = new BoletoPDF();
$pdf->AddPage();
$pdf->corpoBoleto($dadosBoleto);
$pdf->Output('I', 'boleto_maxacess.pdf'); // Abre no navegador

?>
