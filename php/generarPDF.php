<?php
require('../fpdf/fpdf.php');
require('./conexion.php');

// Obtener el ID de la compra de la solicitud
$orderId = isset($_GET['orderId']) ? intval($_GET['orderId']) : 0;
$userId = isset($_GET['userId']) ? intval($_GET['userId']) : 0;

// Verificar si se proporcionó un ID de compra
if ($orderId <= 0 || $userId <= 0) {
    die("ID de orden o ID de usuario no válido.");
}

// Obtener los IDs de los productos asociados a la compra
$queryProductIds = "SELECT id_prod FROM orden_id_prod WHERE id = $orderId";
$resultProductIds = $conn->query($queryProductIds);

$productIds = [];
if ($resultProductIds->num_rows > 0) {
    while ($rowProductId = $resultProductIds->fetch_assoc()) {
        $productIds[] = $rowProductId['id_prod'];
    }
} else {
    die("No se encontraron productos para la orden.");
}

// Obtener la fecha de la orden
$queryOrderDate = "SELECT fecha FROM orden WHERE id = $orderId";
$resultOrderDate = $conn->query($queryOrderDate);

if ($resultOrderDate->num_rows > 0) {
    $rowOrderDate = $resultOrderDate->fetch_assoc();
    $orderDate = $rowOrderDate['fecha'];
} else {
    $orderDate = 'Fecha no encontrada';
}

// Realizar la consulta para el texto debajo de "CLIENTE"
$query2 = "SELECT nombre, primerAp, segundoAp, correo, telefono FROM usuarios WHERE id = $userId";
$result2 = $conn->query($query2);

if ($result2->num_rows > 0) {
    $row2 = $result2->fetch_assoc();
    $clientName = $row2['nombre'];
    $apellidoPaterno = $row2['primerAp'];
    $apellidoMaterno = $row2['segundoAp'];
    $correo = $row2['correo'];
    $telefono = $row2['telefono'];
} else {
    $clientName = 'Nombre no encontrado';
    $apellidoPaterno = '';
    $apellidoMaterno = '';
    $correo = 'Correo no encontrado';
    $telefono = 'Teléfono no encontrado';
}

// Inicializar array para los datos de los productos
$data = [];

// Realizar la consulta para obtener los productos
if (!empty($productIds)) {
    foreach ($productIds as $id) {
        $id = intval($id); // Asegurarse de que el ID es un número entero
        $query3 = "SELECT id, titulo, precio, descuento, (precio - descuento) AS precio_final FROM productos WHERE id = $id";
        $result3 = $conn->query($query3);

        if ($result3->num_rows > 0) {
            while ($row3 = $result3->fetch_assoc()) {
                $data[] = array(
                    $row3['id'],
                    $row3['titulo'],
                    number_format($row3['precio'], 2),
                    number_format($row3['descuento'], 2),
                    number_format($row3['precio_final'], 2)
                );
            }
        }
    }
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

    function AddImageWithText($filePath, $x, $y, $width, $text, $subtext1, $subtext2, $subtext3, $date)
    {
        // Add image to the PDF with the specified width
        $this->Image($filePath, $x, $y, $width);
        
        // Set position for the main text (aligned with the top of the image)
        $textX = $x + $width + 5; // Adding a margin of 5 units
        $textY = $y; // Aligning with the top of the image
        
        // Set font for the main text
        $this->SetFont('Arial', 'B', 18);
        // Set text color to black
        $this->SetTextColor(0, 0, 0);
        
        // Set position and add the main text
        $this->SetXY($textX, $textY);
        $this->Cell(0, 10, $text);

        // Set position for the first subtext
        $subtext1Y = $textY + 10; // Position below the main text
        
        // Set font for the subtexts
        $this->SetFont('Arial', '', 12);
        // Set text color to black
        $this->SetTextColor(0, 0, 0);
        
        // Set position and add the first subtext
        $this->SetXY($textX, $subtext1Y);
        $this->Cell(0, 6, $subtext1); // Reduce the height for closer spacing

        // Set position for the second subtext
        $subtext2Y = $subtext1Y + 6; // Position closer to the first subtext
        
        // Set position and add the second subtext
        $this->SetXY($textX, $subtext2Y);
        $this->Cell(0, 6, $subtext2); // Reduce the height for closer spacing

        // Set position for the third subtext
        $subtext3Y = $subtext2Y + 6; // Position closer to the second subtext
        
        // Set position and add the third subtext
        $this->SetXY($textX, $subtext3Y);
        $this->Cell(0, 6, $subtext3); // Reduce the height for closer spacing

        // Set position for the date text
        $dateY = $subtext3Y + 6; // Position below the third subtext
        
        // Set position and add the date
        $this->SetXY($textX, $dateY);
        $this->Cell(0, 6, $date); // Add the date
    }

    function AddLeftAlignedText($text)
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
        $x = 10; // Position to the left with some margin
        $y = $this->GetY() + 20; // Add more spacing below the image

        // Set position
        $this->SetXY($x, $y);
        
        // Draw a filled rectangle with the text aligned to the left
        $this->Cell($width, $height, $text, 0, 1, 'L', true);
    }

    function AddTextBelowLeftAligned($text)
    {
        // Set font
        $this->SetFont('Arial', '', 12);
        // Set text color to black
        $this->SetTextColor(0, 0, 0);
        
        // Calculate position for the cell
        $x = 10; // Position to the left with some margin
        $y = $this->GetY() + 1; // Reduced spacing below the bar

        // Set position
        $this->SetXY($x, $y);
        
        // Draw the text
        $this->Cell(0, 5, $text, 0, 1, 'L', false); // Reduce height for no spacing
    }

    function AddContactInfo($email, $phone)
    {
        // Set font
        $this->SetFont('Arial', '', 12);
        // Set text color to black
        $this->SetTextColor(0, 0, 0);
        
        // Calculate position for the email
        $x = 10; // Position to the left with some margin
        $y = $this->GetY(); // No additional spacing below the previous text

        // Set position and add the email
        $this->SetXY($x, $y);
        $this->Cell(0, 5, $email, 0, 1, 'L', false); // Reduce height for no spacing

        // Calculate position for the phone
        $y = $this->GetY(); // Continue from the current Y position

        // Set position and add the phone
        $this->SetXY($x, $y);
        $this->Cell(0, 5, $phone, 0, 1, 'L', false); // Reduce height for no spacing
    }

    function AddProductsHeader($text)
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
        $x = 10; // Position to the left with some margin
        $y = $this->GetY() + 5; // Spacing below the contact info

        // Set position
        $this->SetXY($x, $y);

        // Draw a filled rectangle with the text aligned to the left
        $this->Cell($width, $height, $text, 0, 1, 'L', true);
    }

    function AddProductsTable($header, $data)
    {
        // Set font for the header
        $this->SetFont('Arial', 'B', 12);
        // Set text color for the header
        $this->SetTextColor(0, 0, 0);

        // Calculate width for each column with additional spacing
        $widths = array(30, 80, 40, 40, 40); // Ajustar los anchos de las columnas con espaciado
        $x = 10; // Position to the left with some margin
        $y = $this->GetY() + 5; // Spacing below the header

        // Set position
        $this->SetXY($x, $y);

        // Draw the header
        foreach ($header as $key => $col) {
            $this->Cell($widths[$key], 7, $col, 0, 0, 'C', false);
        }
        $this->Ln();

        // Draw the data
        $this->SetFont('Arial', '', 12);
        $this->SetTextColor(0, 0, 0);
        foreach ($data as $row) {
            foreach ($row as $key => $col) {
                $align = in_array($key, [0, 2, 3, 4]) ? 'R' : 'L'; // Alinear a la derecha ID, precio, descuento y precio final
                $this->Cell($widths[$key], 6, $col, 0, 0, $align);
            }
            $this->Ln();
        }
    }
}

// Create instance of the PDF class
$pdf = new PDF('L', 'mm', 'A4');
$pdf->AddPage();

// Usar el ID del producto en el ticket de la orden
$pdf->AddOrderTicket('ORDER TICKET No. ' . $orderId);

// Agregar una imagen al PDF y texto al lado de la imagen
$pdf->AddImageWithText('../imgs/logo1.png', 10, 30, 30, 'XALA STORE S.A DE C.V', 'ESCUELA SUPERIOR DE COMPUTO', 'UNIDAD PROFESIONAL ADOLFO LOPEZ MATEOS', 'GUSTAVO A. MADERO, CIUDAD DE MEXICO C.P. 07320', 'FECHA Y HORA DE EXPEDICION: ' . $orderDate);

// Agregar el texto alineado a la izquierda debajo de la imagen
$pdf->AddLeftAlignedText('CLIENTE');

// Concatenar el nombre completo
$fullClientName = $clientName . ' ' . $apellidoPaterno . ' ' . $apellidoMaterno;

// Agregar el texto debajo de "CLIENTE" basado en la consulta
$pdf->AddTextBelowLeftAligned($fullClientName);

// Agregar el correo y el teléfono debajo del nombre del cliente
$pdf->AddContactInfo($correo, $telefono);

// Agregar una barra negra que diga "PRODUCTOS"
$pdf->AddProductsHeader('PRODUCTOS');

// Agregar la tabla de productos
$header = array('ID', 'Nombre', 'Precio', 'Descuento', 'Precio Final');
$pdf->AddProductsTable($header, $data);

// Output the PDF
$pdf->Output();
?>
