<?php

namespace App\Http\Controllers\Eo;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EoVoucherController extends Controller
{
    /**
     * Display a listing of vouchers for the EO.
     */
    public function index()
    {
        $organizer = auth()->user()->organizer;
        $vouchers = $organizer->vouchers()->paginate(15);

        return view('eo.vouchers.index', compact('vouchers'));
    }

    /**
     * Show the form for creating a new voucher.
     */
    public function create()
    {
        return view('eo.vouchers.create');
    }

    /**
     * Store a newly created voucher in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code',
            'description' => 'nullable|string|max:255',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|integer|min:1',
            'usage_limit' => 'nullable|integer|min:1',
            'max_per_user' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
        ]);

        $validated['organizer_id'] = auth()->user()->organizer->id;
        $validated['code'] = strtoupper($validated['code']);
        
        if ($validated['discount_type'] === 'percentage' && $validated['discount_value'] > 100) {
            return back()->withErrors(['discount_value' => 'Persentase tidak boleh lebih dari 100%']);
        }

        Voucher::create($validated);

        return redirect()->route('eo.vouchers.index')->with('success', 'Voucher berhasil dibuat');
    }

    /**
     * Show the form for editing the specified voucher.
     */
    public function edit(Voucher $voucher)
    {
        if ($voucher->organizer_id !== auth()->user()->organizer->id) {
            abort(403);
        }
        return view('eo.vouchers.edit', compact('voucher'));
    }

    /**
     * Update the specified voucher in storage.
     */
    public function update(Request $request, Voucher $voucher)
    {
        if ($voucher->organizer_id !== auth()->user()->organizer->id) {
            abort(403);
        }

        $validated = $request->validate([
            'description' => 'nullable|string|max:255',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|integer|min:1',
            'usage_limit' => 'nullable|integer|min:1',
            'max_per_user' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
        ]);

        if ($validated['discount_type'] === 'percentage' && $validated['discount_value'] > 100) {
            return back()->withErrors(['discount_value' => 'Persentase tidak boleh lebih dari 100%']);
        }

        $voucher->update($validated);

        return redirect()->route('eo.vouchers.index')->with('success', 'Voucher berhasil diperbarui');
    }

    /**
     * Delete the specified voucher.
     */
    public function destroy(Voucher $voucher)
    {
        if ($voucher->organizer_id !== auth()->user()->organizer->id) {
            abort(403);
        }
        
        $voucher->delete();
        return redirect()->route('eo.vouchers.index')->with('success', 'Voucher berhasil dihapus');
    }

    /**
     * Show voucher statistics.
     */
    public function stats(Voucher $voucher)
    {
        if ($voucher->organizer_id !== auth()->user()->organizer->id) {
            abort(403);
        }
        
        $usages = $voucher->usages()->with('user')->paginate(20);
        $totalUsages = $voucher->usage_count;
        $remainingUsages = $voucher->usage_limit ? $voucher->usage_limit - $voucher->usage_count : null;

        return view('eo.vouchers.stats', compact('voucher', 'usages', 'totalUsages', 'remainingUsages'));
    }
}
