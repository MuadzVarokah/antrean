<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $fillable = [
        'queue_number',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Generate nomor antrian baru
    public static function generateQueueNumber(): string
    {
        $today = now()->format('Y-m-d');
        $count = self::whereDate('created_at', $today)->count() + 1;
        return 'A' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    // Ambil antrian aktif
    public static function getActiveQueue()
    {
        return self::where('status', 'active')->first();
    }

    // Ambil semua antrian hari ini
    public static function getTodayQueues()
    {
        return self::whereDate('created_at', now())
            ->orderBy('created_at', 'asc')
            ->get();
    }

    // Ambil antrian terakhir
    public static function getLastQueue()
    {
        return self::latest()->first();
    }
}