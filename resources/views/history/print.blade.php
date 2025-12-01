<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Tiket - {{ $order->concert->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
            background: white;
            padding: 20px;
        }
        .print-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
        }
        .ticket-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .concert-name {
            font-size: 28px;
            font-weight: bold;
            color: #111;
            margin-bottom: 8px;
        }
        .concert-date {
            font-size: 14px;
            color: #666;
            margin-bottom: 4px;
        }
        .concert-location {
            font-size: 14px;
            color: #666;
        }
        .qr-section {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 2px solid #ddd;
        }
        .qr-section img {
            width: 280px;
            height: 280px;
            margin: 0 auto;
            display: block;
        }
        .qr-label {
            font-size: 12px;
            color: #999;
            margin-top: 10px;
        }
        .price-section {
            margin-bottom: 40px;
        }
        .price-section h3 {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 12px;
        }
        .price-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            font-size: 13px;
            border-bottom: 1px solid #eee;
        }
        .price-item.total {
            border-bottom: none;
            border-top: 2px solid #000;
            padding-top: 12px;
            padding-bottom: 0;
            margin-top: 12px;
            font-weight: bold;
            font-size: 16px;
        }
        .price-item .label {
            color: #666;
        }
        .price-item .quantity {
            color: #999;
            font-size: 12px;
            margin-left: 8px;
        }
        .price-item .value {
            color: #111;
            font-weight: 500;
        }
        .price-item.total .value {
            color: #4f46e5;
        }
        .reference-section {
            background: #f3f4f6;
            padding: 16px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
        }
        .reference-label {
            font-size: 11px;
            color: #666;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .reference-code {
            font-family: 'Courier New', monospace;
            font-size: 18px;
            font-weight: bold;
            color: #4f46e5;
            letter-spacing: 1px;
        }
        @media print {
            body {
                padding: 0;
                background: white;
            }
            .print-container {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <!-- Header -->
        <div class="ticket-header">
            <div class="concert-name">{{ $order->concert->name }}</div>
            <div class="concert-date">{{ \Carbon\Carbon::parse($order->concert->date)->format('d F Y H:i') }}</div>
            <div class="concert-location">{{ $order->concert->location ?? 'Lokasi tidak tersedia' }}</div>
        </div>

        <!-- QR Code -->
        @php
            $uniqueQrUrls = isset($tickets) ? $tickets->pluck('qr_code_url')->filter()->unique() : collect();
            $qrUrl = $uniqueQrUrls->count() > 0 ? $uniqueQrUrls->first() : null;
        @endphp

        @if(isset($tickets) && $tickets->count() > 0)
            <div class="qr-section">
                <!-- Judul Tiket -->
                <div style="text-align:center; margin-bottom:18px;">
                    <div style="font-size:12px;color:#6b7280;">Judul Tiket</div>
                    <div style="font-size:20px;font-weight:700;color:#111;margin-top:6px;">{{ $order->concert->name }}</div>
                </div>

                @if($qrUrl && !empty($qrUrl))
                    <img src="{{ asset($qrUrl) }}" alt="QR Code" style="width: 280px; height: 280px; margin: 0 auto; display: block;">
                @else
                    <!-- Generate QR dynamically if not stored -->
                    <div id="qrCode" style="width: 280px; height: 280px; margin: 0 auto; display: flex; align-items: center; justify-content: center; background: #f3f4f6; border-radius: 8px;"></div>
                @endif
                <div class="qr-label">Tunjukkan QR Code ini saat check-in</div>

                <!-- Daftar Jenis Tiket -->
                <div style="margin-top: 20px; padding: 12px; background: #f3f4f6; border-radius: 6px; border: 1px solid #e5e7eb;">
                    <div style="font-size: 12px; color: #4b5563; margin-bottom: 10px; font-weight: 600;">Jenis Tiket:</div>
                    @foreach($order->items as $item)
                        <div style="display: flex; justify-content: space-between; font-size: 13px; padding: 6px 0; border-bottom: 1px solid #e5e7eb;">
                            <span>{{ $item->ticketType->name }}</span>
                            <span style="font-weight: 600;">{{ $item->quantity }} tiket</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Price Details -->
        <div class="price-section">
            <h3>Rincian Harga</h3>
            
            @foreach ($order->items as $item)
                <div class="price-item">
                    <div>
                        <span class="label">{{ $item->ticketType->name }}</span>
                        <span class="quantity">{{ $item->quantity }} tiket</span>
                    </div>
                    <span class="value">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                </div>
            @endforeach
            <div class="price-item">
                <span class="label">Subtotal</span>
                <span class="value">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
            @if($order->discount_amount > 0)
            <div class="price-item" style="color: #16a34a;">
                <span class="label">Diskon</span>
                <span class="value">-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="price-item total">
                <span>Total</span>
                <span class="value">Rp {{ number_format($order->total_amount - ($order->discount_amount ?? 0), 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Reference Code -->
        <div class="reference-section">
            <div class="reference-label">Nomor Referensi</div>
            <div class="reference-code">{{ $order->reference_code }}</div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        window.addEventListener('load', function() {
            // Generate QR code if needed
            const qrContainer = document.getElementById('qrCode');
            if (qrContainer && qrContainer.children.length === 0) {
                const qrData = JSON.stringify({
                    reference_code: '{{ $order->reference_code }}',
                    order_id: {{ $order->id }},
                    concert: '{{ $order->concert->name ?? '' }}',
                });
                
                new QRCode(qrContainer, {
                    text: qrData,
                    width: 280,
                    height: 280,
                    colorDark: '#000000',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.H
                });
            }
            
            // Wait a bit for QR to render, then print
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
