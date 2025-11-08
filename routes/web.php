<?php

use App\Models\DigitalLetter;
use App\Services\DigitalLetterPdfService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Digital Letter PDF Routes (protected by auth middleware)
Route::middleware(['auth'])->group(function () {
    Route::get('/digital-letter/{digitalLetter}/preview', function (DigitalLetter $digitalLetter, DigitalLetterPdfService $pdfService) {
        // Check authorization
        if (! auth()->user()->can('view', $digitalLetter)) {
            abort(403);
        }

        return $pdfService->stream($digitalLetter);
    })->name('digital-letter.preview');

    Route::get('/digital-letter/{digitalLetter}/download', function (DigitalLetter $digitalLetter, DigitalLetterPdfService $pdfService) {
        // Check authorization
        if (! auth()->user()->can('view', $digitalLetter)) {
            abort(403);
        }

        return $pdfService->download($digitalLetter);
    })->name('digital-letter.download');
});

// Public verification route (no auth required)
Route::get('/verify-letter/{id}/{hash}', function ($id, $hash) {
    $letter = DigitalLetter::with(['resident', 'issuedBy'])->findOrFail($id);

    // Verify hash
    $expectedHash = hash('sha256', $letter->letter_number.$letter->issued_date);
    if ($hash !== $expectedHash) {
        abort(403, 'Invalid verification hash');
    }

    return view('verify-letter', compact('letter'));
})->name('digital-letter.verify');
