<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Surat Digital</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .header p {
            opacity: 0.9;
            font-size: 14px;
        }

        .verified-badge {
            background: #10b981;
            color: white;
            padding: 15px 30px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }

        .verified-badge svg {
            width: 24px;
            height: 24px;
            display: inline-block;
            vertical-align: middle;
            margin-right: 10px;
        }

        .content {
            padding: 30px;
        }

        .info-section {
            margin-bottom: 25px;
        }

        .info-section h2 {
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 15px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 8px;
        }

        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #374151;
            width: 150px;
            flex-shrink: 0;
        }

        .info-value {
            color: #6b7280;
            flex: 1;
        }

        .letter-content {
            background: #f9fafb;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #667eea;
            margin-top: 15px;
            line-height: 1.6;
            color: #374151;
        }

        .footer {
            background: #f9fafb;
            padding: 20px 30px;
            text-align: center;
            color: #6b7280;
            font-size: 13px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-valid {
            background: #d1fae5;
            color: #065f46;
        }

        .status-expired {
            background: #fee2e2;
            color: #991b1b;
        }

        @media (max-width: 640px) {
            .info-row {
                flex-direction: column;
            }

            .info-label {
                width: 100%;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✓ Verifikasi Surat Digital</h1>
            <p>RT {{ config('app.rt_number', '001') }} / RW {{ config('app.rw_number', '001') }}</p>
        </div>

        <div class="verified-badge">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            SURAT TERVERIFIKASI
        </div>

        <div class="content">
            <div class="info-section">
                <h2>Informasi Surat</h2>
                
                <div class="info-row">
                    <div class="info-label">Nomor Surat:</div>
                    <div class="info-value"><strong>{{ $letter->letter_number }}</strong></div>
                </div>

                <div class="info-row">
                    <div class="info-label">Jenis Surat:</div>
                    <div class="info-value">{{ ucfirst($letter->letter_type) }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Tanggal Terbit:</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($letter->issued_date)->isoFormat('D MMMM Y') }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Status:</div>
                    <div class="info-value">
                        @if($letter->valid_until && \Carbon\Carbon::parse($letter->valid_until)->isPast())
                            <span class="status-badge status-expired">Kadaluarsa</span>
                        @else
                            <span class="status-badge status-valid">Berlaku</span>
                        @endif
                    </div>
                </div>

                @if($letter->valid_until)
                <div class="info-row">
                    <div class="info-label">Berlaku Hingga:</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($letter->valid_until)->isoFormat('D MMMM Y') }}</div>
                </div>
                @endif
            </div>

            <div class="info-section">
                <h2>Data Warga</h2>
                
                <div class="info-row">
                    <div class="info-label">Nama:</div>
                    <div class="info-value"><strong>{{ $letter->resident->name }}</strong></div>
                </div>

                <div class="info-row">
                    <div class="info-label">NIK:</div>
                    <div class="info-value">{{ $letter->resident->nik }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Alamat:</div>
                    <div class="info-value">{{ $letter->resident->address }}</div>
                </div>
            </div>

            <div class="info-section">
                <h2>Pejabat Penandatangan</h2>
                
                <div class="info-row">
                    <div class="info-label">Nama:</div>
                    <div class="info-value">{{ $letter->issuedBy->name }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Jabatan:</div>
                    <div class="info-value">{{ ucwords(str_replace('_', ' ', $letter->issuedBy->role)) }}</div>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>Surat ini telah diverifikasi melalui sistem digital RT {{ config('app.rt_number', '001') }}</p>
            <p style="margin-top: 8px;">Verifikasi dilakukan pada {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y HH:mm') }} WIB</p>
        </div>
    </div>
</body>
</html>
