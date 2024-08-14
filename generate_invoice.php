<?php
require 'fpdf/fpdf.php';
require_once 'OrderHandler.php';

class PDF extends FPDF {
    // Page header
    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(230, 230, 250);
        $this->Cell(0, 10, 'NEXPLAY INVOICE', 0, 1, 'C',true);
        $this->Ln(10);
    }

    // Page footer 
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

function generateInvoice($orderId) {
    $orderHandler = new OrderHandler();
    $orderDetails = $orderHandler->getOrderDetails($orderId);
    $customerDetails = $orderHandler->getCustomerDetails($orderDetails['CustomerID']);
    $orderItems = $orderHandler->getOrderItems($orderId);

    $pdf = new PDF();
    $pdf->AddPage();
    

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Customer Details:', 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Name: ' . $customerDetails['FirstName'] . ' ' . $customerDetails['LastName'], 0, 1);
    $pdf->Cell(0, 10, 'Email: ' . $customerDetails['Email'], 0, 1);
    $pdf->Cell(0, 10, 'Phone: ' . $customerDetails['Phone'], 0, 1);
    $pdf->Cell(0, 10, 'Shipping Address: ' . $orderDetails['ShippingAddress'], 0, 1);
    $pdf->Cell(0, 10, 'Billing Address: ' . $orderDetails['BillingAddress'], 0, 1);
    $pdf->Ln(10);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(230, 230, 250);
    $pdf->Cell(0, 10, 'Order Details:', 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'Title', 1,0,'',true);
    $pdf->Cell(40, 10, 'Genre', 1,0,'',true);
    $pdf->Cell(40, 10, 'Platform', 1,0,'',true);
    $pdf->Cell(20, 10, 'Qty', 1,0,'',true);
    $pdf->Cell(25, 10, 'Unit Price', 1,0,'',true);
    $pdf->Cell(25, 10, 'Total Price', 1,0,'',true);
    $pdf->Ln();

    $totalAmount = 0;
    while ($item = $orderItems->fetch_assoc()) {
        $itemTotal = $item['Quantity'] * $item['UnitPrice'];
        $totalAmount += $itemTotal;
        $pdf->SetFont('Arial', '',9 );
        $pdf->Cell(40, 10, $item['Title'], 1);
       // $pdf->MultiCell(40, 10, $item['Title'], 1);
       // $pdf->SetX(50);
        //$pdf->Ln();
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, $item['GenreName'], 1);
        $pdf->Cell(40, 10, $item['PlatformName'], 1);
        $pdf->Cell(20, 10, $item['Quantity'], 1);
        $pdf->Cell(25, 10, '$' . number_format($item['UnitPrice'], 2), 1);
        $pdf->Cell(25, 10, '$' . number_format($itemTotal, 2), 1);
        $pdf->Ln();
    }

    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Total Amount: $' . number_format($totalAmount, 2), 0, 1,'R');

    $pdf->Output();
}

