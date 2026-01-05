<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Dashboard admin
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    // Next queue
    public function next()
    {
        // Set active queue to completed
        Queue::where('status', 'active')->update(['status' => 'completed']);

        // Get next waiting queue
        $nextQueue = Queue::where('status', 'waiting')
            ->orderBy('created_at', 'asc')
            ->first();

        if ($nextQueue) {
            $nextQueue->update(['status' => 'active']);
            
            return response()->json([
                'success' => true,
                'queue' => $nextQueue,
                'message' => 'Antrian berikutnya dipanggil'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Tidak ada antrian selanjutnya'
        ]);
    }

    // Previous queue
    public function prev()
    {
        $activeQueue = Queue::where('status', 'active')->first();
        
        if ($activeQueue) {
            // Set current active to waiting
            $activeQueue->update(['status' => 'waiting']);

            // Get previous completed queue
            $prevQueue = Queue::where('status', 'completed')
                ->where('id', '<', $activeQueue->id)
                ->orderBy('id', 'desc')
                ->first();

            if ($prevQueue) {
                $prevQueue->update(['status' => 'active']);
                
                return response()->json([
                    'success' => true,
                    'queue' => $prevQueue,
                    'message' => 'Kembali ke antrian sebelumnya'
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Tidak ada antrian sebelumnya'
        ]);
    }

    // Complete current queue
    public function complete()
    {
        $activeQueue = Queue::where('status', 'active')->first();

        if ($activeQueue) {
            $activeQueue->update(['status' => 'completed']);
            
            return response()->json([
                'success' => true,
                'message' => 'Antrian diselesaikan'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Tidak ada antrian aktif'
        ]);
    }

    // Skip current queue
    public function skip()
    {
        // Set active queue to skipped
        Queue::where('status', 'active')->update(['status' => 'skipped']);

        // Get next waiting queue
        $nextQueue = Queue::where('status', 'waiting')
            ->orderBy('created_at', 'asc')
            ->first();

        if ($nextQueue) {
            $nextQueue->update(['status' => 'active']);
            
            return response()->json([
                'success' => true,
                'queue' => $nextQueue,
                'message' => 'Antrian berikutnya dipanggil'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Tidak ada antrian selanjutnya'
        ]);
    }

    // Jump to skipped queue
    public function jump(Request $request)
{
        // Validasi input
        $request->validate([
            'id' => 'required|integer|exists:queues,id',
            'prevStatus' => 'required|in:skipped,completed'
        ]);

        $id = $request->id;
        $prevStatus = $request->prevStatus;

        DB::transaction(function () use ($id, $prevStatus) {

            // Set antrian aktif sebelumnya
            Queue::where('status', 'active')
                ->update(['status' => $prevStatus]);

            // Set antrian terpilih jadi aktif
            Queue::where('id', $id)
                ->update(['status' => 'active']);
        });

        return response()->json([
            'success' => true,
            'message' => 'Antrian berhasil diperbarui'
        ]);
    }

    // Get queue data (for realtime update)
    public function queueData()
    {
        $activeQueue = Queue::getActiveQueue();
        $queues = Queue::getTodayQueues();

        return response()->json([
            'success' => true,
            'active_queue' => $activeQueue,
            'queues' => $queues,
            'waiting_count' => $queues->where('status', 'waiting')->count(),
            'completed_count' => $queues->where('status', 'completed')->count(),
            'skipped_count' => $queues->where('status', 'skipped')->count()
        ]);
    }
}