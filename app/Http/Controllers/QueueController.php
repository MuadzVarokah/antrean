<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    // Halaman utama untuk pengunjung
    public function index()
    {
        return view('queue.index');
    }

    // Ambil nomor antrian
    public function take(Request $request)
    {
        $queueNumber = Queue::generateQueueNumber();
        
        $queue = Queue::create([
            'queue_number' => $queueNumber,
            'status' => 'waiting'
        ]);

        return response()->json([
            'success' => true,
            'queue_number' => $queue->queue_number,
            'message' => 'Nomor antrian berhasil diambil'
        ]);
    }

    // Get current active queue
    public function current()
    {
        $activeQueue = Queue::getActiveQueue();
        
        return response()->json([
            'success' => true,
            'queue' => $activeQueue
        ]);
    }

    // Get list of queues today
    public function list()
    {
        $queues = Queue::getTodayQueues();
        
        return response()->json([
            'success' => true,
            'queues' => $queues
        ]);
    }

    // Get last queue
    public function last()
    {
        $lastQueue = Queue::getLastQueue();
        
        return response()->json([
            'success' => true,
            'queue' => $lastQueue
        ]);
    }
}