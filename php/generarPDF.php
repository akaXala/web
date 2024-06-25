<?php
require('../fpdf/fpdf.php');
require('./conexion.php');

// Realizar la consulta
$query = "SELECT id FROM productos WHERE id = 1";
$result = $conn->query($query);

// Verificar si hay resultados
if ($result->num_rows > 0) {
    // Obtener el resultado de la consulta
    $row = $result->fetch_assoc();
    $productId = $row['id'];
} else {
    $productId = 'ID not found';
}

class PDF extends FPDF
{
    function AddOrderTicket($ticketNumber)
    {
        // Set font
        $this->SetFont('Arial', 'B', 12);
        // Set fill color to black
        $this->SetFillColor(0, 0, 0);
        // Set text color to white
        $this->SetTextColor(255, 255, 255);
        
        // Calculate width and height for the cell
        $width = 150; // Width of the cell
        $height = 6;  // Height of the cell (more narrow)
        $x = $this->GetPageWidth() - $width - 10; // Position to the right with some margin
        $y = 10; // Position closer to the top of the page

        // Set position
        $this->SetXY($x, $y);
        
        // Draw a filled rectangle with the text aligned to the right
        $this->Cell($width, $height, $ticketNumber, 0, 1, 'R', true);
    }
}

// Create instance of the PDF class
$pdf = new PDF('L', 'mm', 'A4');
$pdf->AddPage();

// Usar el ID del producto en el ticket de la orden
$pdf->AddOrderTicket('ORDER TICKET No. ' . $productId);
$pdf->Output();
?>
