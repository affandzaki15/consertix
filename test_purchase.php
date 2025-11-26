<?php
// Test script untuk verifikasi purchase flow

require_once __DIR__ . '/vendor/autoload.php';

// Inisialisasi Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Concert;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\TicketType;
use App\Models\User;

// Cek data
echo "=== CHECKING DATA ===\n";

// Get first concert
$concert = Concert::first();
if (!$concert) {
    echo "❌ Tidak ada concert\n";
    exit(1);
}
echo "✓ Concert found: {$concert->title}\n";

// Get ticket types untuk concert ini
$ticketTypes = $concert->ticketTypes;
if ($ticketTypes->isEmpty()) {
    echo "❌ Tidak ada ticket types untuk concert ini\n";
    exit(1);
}
echo "✓ Ticket types found: {$ticketTypes->count()}\n";

foreach ($ticketTypes as $type) {
    echo "  - {$type->name}: Rp{$type->price}, Sold: {$type->sold}/{$type->quota}\n";
}

// Get first user
$user = User::first();
if (!$user) {
    echo "❌ Tidak ada user\n";
    exit(1);
}
echo "✓ User found: {$user->name} ({$user->email})\n";

// Check orders untuk user ini
$orders = Order::where('user_id', $user->id)->get();
echo "✓ Orders untuk user ini: {$orders->count()}\n";

foreach ($orders as $order) {
    echo "  - Order #{$order->id}: {$order->status}, Total: Rp{$order->total_amount}\n";
    foreach ($order->items as $item) {
        echo "    - {$item->ticketType->name} x{$item->quantity} @ Rp{$item->price}\n";
    }
}

echo "\n=== TEST COMPLETE ===\n";
