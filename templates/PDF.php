<?php
defined('ABSPATH') || exit;
/*
 Template Name: PDF
 */
?>

<?php
include_once('libs/tfpdf/tfpdf.php');

class PDF extends tFPDF
{
	// Page header
	function Header()
	{
		// // Logo
		// //$this->Image('logo.png', 10, -1, 70);
		// $this->SetFont('Arial', 'B', 13);
		// // Move to the right
		// $this->Cell(80);
		// // Title
		// $this->Cell(80, 10, 'Employee List', 1, 0, 'C');
		// // Line break
		// $this->Ln(20);
	}

	// Page footer
	function Footer()
	{
		// Position at 1.5 cm from bottom
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
	}
	function GetMaxHeightRow($widths, $data, $hh)
	{
		//Calculate the height of the row
		$nb = 0;
		for ($i = 0; $i < count($data); $i++){
			$nb = max($nb, $this->NbLines($widths[$i], $data[$i]));
			//echo var_dump($data[$i]) ;
			//echo var_dump($nb);			
		}
		$h = $hh * $nb;
		return $h;
	}
	function CellMultiLine($w, $hh, $text, $border = 0, $ln = 0, $align = 'L', $fill = false, $h)
	{
		//Issue a page break first if needed
		$this->CheckPageBreak($h);
		//Save the current position
		$x = $this->GetX();
		$y = $this->GetY();
		//Draw the border
		$this->Rect($x, $y, $w, $h);
		//Print the text
		$this->MultiCell($w, $hh, $text, 0, $align);
		//Put the position to the right of the cell
		$this->SetXY($x + $w, $y);
		//Go to the next line
	}
	function CheckPageBreak($h)
	{
		//If the height h would cause an overflow, add a new page immediately
		if ($this->GetY() + $h > $this->PageBreakTrigger)
			$this->AddPage($this->CurOrientation);
	}
	function NbLines($w, $txt)
	{
		//Computes the number of lines a MultiCell of width w will take
		$cw = &$this->CurrentFont['cw'];
		if ($w == 0)
			$w = $this->w - $this->rMargin - $this->x;
		$wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
		$s = str_replace("\r", '', $txt);
		$nb = strlen($s);
		if ($nb > 0 and $s[$nb - 1] == "\n")
			$nb--;
		$sep = -1;
		$i = 0;
		$j = 0;
		$l = 0;
		$nl = 1;
		while ($i < $nb) {
			$c = $s[$i];
			if ($c == "\n") {
				$i++;
				$sep = -1;
				$j = $i;
				$l = 0;
				$nl++;
				continue;
			}
			if ($c == ' ')
				$sep = $i;
			$l += 500;//$this->GetStringWidth($c);
			
			if ($l > $wmax) {
				if ($sep == -1) {
					if ($i == $j)
						$i++;
				} else
					$i = $sep + 1;
				$sep = -1;
				$j = $i;
				$l = 0;
				$nl++;
			} else
				$i++;
		}
		return $nl;
	}
}

$pdf = new PDF();

$w = array(10, 20, 40, 45, 30, 20, 30);
$hH = 12;
$h = 10;
$fz = 13;
$fn = 'DejaVu';
$pdf->AddFont($fn, '', 'DejaVuSansCondensed.ttf', true);
$pdf->AddFont($fn, 'B', 'DejaVuSansCondensed-Bold.ttf', true);
$pdf->SetFont($fn, '', $fz);

for ($i = 1; $i <= 5; $i++) {
	//header
	$pdf->AddPage();
	$pdf->AliasNbPages();
	$pdf->SetFont($fn, 'B');
	$pdf->Cell(200, 12, "Danh sách giao hàng", 0, 0, 'C');
	$pdf->Ln(15);

	$pdf->SetFont($fn, '');
	$pdf->Cell(100, 10, "CTV: Cộng tác viên " . $i, 0, 0, 'l');
	$pdf->Cell(100, 10, "Thời điểm: bữa xế", 0, 0, 'l');
	$pdf->Ln();

	$pdf->Cell(100, 10, "Ngày giao hàng: 04/06/2022", 0, 0, 'l');
	$pdf->Cell(200, 10, "Tổng tiền: 1.000.000 VND", 0, 0, 'l');
	$pdf->Ln();

	$pdf->Cell(200, 12, "Địa chỉ giao hàn: Tần 20 Handico Phạm Hùng Hà Nội", 0, 0, 'l');
	$pdf->Ln();

	$pdf->SetFont($fn, 'B');
	$pdf->Cell(200, 12, "Danh sách sản phẩm", 0, 0, 'l');
	$pdf->Ln();

	$pdf->SetFont($fn, '');
	$pdf->Cell($w[0], $hH, "STT", 1, 0, 'C');
	$pdf->Cell($w[1], $hH, "Mã đơn ", 1, 0, 'C');
	$pdf->Cell($w[2], $hH, "Khách hàng ", 1, 0, 'C');
	$pdf->Cell($w[3], $hH, "Vị trí cụ thể ", 1, 0, 'C');
	$pdf->Cell($w[4], $hH, "Sản phẩm", 1, 0, 'C');
	$pdf->Cell($w[5], $hH, "Số lượng", 1, 0, 'C');
	$pdf->Cell($w[6], $hH, "Tổng tiền", 1, 0, 'C');
	$pdf->Ln();

	for ($j = 0; $j < 30; $j++) {
		$data = array($j, "DH0 " . $j, "KH " . $j, "Vị trí  as dasd asd asd asda dfasd Vị trí  as dasd asd asd asda dfasd" . $j, "Sản phẩm" . $j, 10 * $j, 200 * $j);
		$height = $pdf->GetMaxHeightRow($w, $data, $h);
		$pdf->CellMultiLine($w[0], $h, $data[0], 1, 0, 'C', false, $height);
		$pdf->CellMultiLine($w[1], $h, $data[1], 1, 0, 'l', false, $height);
		$pdf->CellMultiLine($w[2], $h, $data[2], 1,  0, 'l', false, $height);
		$pdf->CellMultiLine($w[3], $h, $data[3], 1, 0, 'l', false, $height);
		$pdf->CellMultiLine($w[4], $h, $data[4], 1, 0, 'l', false, $height);
		$pdf->CellMultiLine($w[5], $h, $data[5], 1, 0,  'R', false, $height);
		$pdf->CellMultiLine($w[6], $h, $data[6], 1, 0,  'R', false, $height);
		$pdf->Ln($height);
	}
	$pdf->Ln(15);
	$pdf->SetFont($fn, 'B');
	$pdf->Cell(200, 12, "SP 1: 3000", 0, 0, 'l');
	$pdf->Ln();
	$pdf->Cell(200, 12, "SP 2: 3000", 0, 0, 'l');
}
$pdf->Output();
?>