<?php

namespace App\Services;

use App\Models\DigitalLetter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DigitalLetterPdfService
{
    /**
     * Generate verification URL for QR code
     */
    protected function generateVerificationUrl(DigitalLetter $letter): string
    {
        return route('digital-letter.verify', [
            'id' => $letter->id,
            'hash' => hash('sha256', $letter->letter_number.$letter->issued_date),
        ]);
    }

    /**
     * Generate QR code as base64 image
     */
    protected function generateQrCode(DigitalLetter $letter): string
    {
        $verificationUrl = $this->generateVerificationUrl($letter);

        // Generate QR code as PNG base64 using SVG format (no extension needed)
        $qrCode = QrCode::format('svg')
            ->size(200)
            ->margin(1)
            ->generate($verificationUrl);

        return 'data:image/svg+xml;base64,'.base64_encode($qrCode);
    }

    /**
     * Generate PDF for a digital letter
     */
    public function generate(DigitalLetter $letter): string
    {
        // Load the letter with relationships
        $letter->load(['resident', 'issuedBy']);

        // Generate QR code
        $qrCodeBase64 = $this->generateQrCode($letter);

        // Generate PDF from view
        $pdf = Pdf::loadView('pdf.digital-letter', [
            'letter' => $letter,
            'qrCode' => $qrCodeBase64,
        ]);

        // Set paper size and orientation
        $pdf->setPaper('a4', 'portrait');

        // Generate filename
        $filename = $this->generateFilename($letter);

        // Save PDF to storage
        $pdfContent = $pdf->output();
        Storage::disk('public')->put('letters/pdf/'.$filename, $pdfContent);

        // Update the letter record with PDF path
        $letter->update([
            'pdf_path' => 'letters/pdf/'.$filename,
        ]);

        return 'letters/pdf/'.$filename;
    }

    /**
     * Download PDF for a digital letter
     */
    public function download(DigitalLetter $letter): \Symfony\Component\HttpFoundation\Response
    {
        // Load the letter with relationships
        $letter->load(['resident', 'issuedBy']);

        // Generate QR code
        $qrCodeBase64 = $this->generateQrCode($letter);

        // Generate PDF from view
        $pdf = Pdf::loadView('pdf.digital-letter', [
            'letter' => $letter,
            'qrCode' => $qrCodeBase64,
        ]);

        // Set paper size and orientation
        $pdf->setPaper('a4', 'portrait');

        // Generate filename for download
        $filename = $this->generateFilename($letter);

        // Return PDF as download
        return $pdf->download($filename);
    }

    /**
     * Stream/Preview PDF for a digital letter
     */
    public function stream(DigitalLetter $letter): \Symfony\Component\HttpFoundation\Response
    {
        // Load the letter with relationships
        $letter->load(['resident', 'issuedBy']);

        // Generate QR code
        $qrCodeBase64 = $this->generateQrCode($letter);

        // Generate PDF from view
        $pdf = Pdf::loadView('pdf.digital-letter', [
            'letter' => $letter,
            'qrCode' => $qrCodeBase64,
        ]);

        // Set paper size and orientation
        $pdf->setPaper('a4', 'portrait');

        // Stream PDF to browser
        return $pdf->stream();
    }

    /**
     * Generate filename for PDF
     */
    protected function generateFilename(DigitalLetter $letter): string
    {
        $letterNumber = str_replace('/', '-', $letter->letter_number);
        $letterType = strtolower($letter->letter_type);

        return "surat-{$letterType}-{$letterNumber}.pdf";
    }

    /**
     * Delete PDF file from storage
     */
    public function delete(DigitalLetter $letter): bool
    {
        if ($letter->pdf_path && Storage::disk('public')->exists($letter->pdf_path)) {
            return Storage::disk('public')->delete($letter->pdf_path);
        }

        return false;
    }
}
