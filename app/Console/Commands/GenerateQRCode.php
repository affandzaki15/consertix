<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\File;

class GenerateQRCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qrcode:generate {--order-id=}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Generate QR codes for tickets and save to public/qrcodes/';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Create directory if it doesn't exist
        $qrPath = public_path('qrcodes');
        if (!File::exists($qrPath)) {
            File::makeDirectory($qrPath, 0755, true);
        }

        $orderId = $this->option('order-id');

        if ($orderId) {
            // Generate QR for specific order
            $tickets = Ticket::whereHas('order', function($q) {
                $q->where('id', $this->option('order-id'));
            })->get();

            if ($tickets->isEmpty()) {
                $this->error('No tickets found for order ID: ' . $orderId);
                return;
            }
        } else {
            // Generate QR for all tickets without QR code
            $tickets = Ticket::whereNull('qr_code_url')->orWhere('qr_code_url', '')->get();
        }

        foreach ($tickets as $ticket) {
            try {
                // Generate QR code content
                $qrContent = "TICKET:" . $ticket->id . "|ORDER:" . $ticket->order_id . "|REF:" . $ticket->order->reference;
                
                // Generate filename based on order reference
                $filename = 'order-' . $ticket->order->reference . '.png';
                $filepath = $qrPath . '/' . $filename;

                // Generate and save QR code
                QrCode::format('png')
                    ->size(300)
                    ->generate($qrContent, $filepath);

                // Update ticket with QR code URL
                $ticket->update([
                    'qr_code_url' => '/qrcodes/' . $filename
                ]);

                $this->info('Generated QR for ticket ID: ' . $ticket->id . ' -> ' . $filename);
            } catch (\Exception $e) {
                $this->error('Error generating QR for ticket ID ' . $ticket->id . ': ' . $e->getMessage());
            }
        }

        $this->info('QR code generation completed!');
    }
}
