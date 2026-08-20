<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Badge;


class ChecklistController extends Controller
{
    public function complete(Request $request)
    {
        try {
            $request->validate([
                'key' => 'required|string',
            ]);

            $userId = Auth::id();
            if (! $userId) {
                return response()->json(['success' => false], 401);
            }

            $today = now()->toDateString();

            // ==========================
            // LOGIC LAMA (CHECKLIST)
            // ==========================
            DB::table('daily_checklists')->updateOrInsert(
                [
                    'user_id' => $userId,
                    'key'     => $request->key,
                    'date'    => $today,
                ],
                [
                    'status'       => 'selesai',
                    'completed_at' => now(),
                    'updated_at'   => now(),
                    'created_at'   => now(),
                ]
            );

            // ==========================
            // TAMBAHAN BARU (POIN + BADGE)
            // ==========================
            /** @var \App\Models\User $user */
            $user = Auth::user();


            // tambah poin
            $user->points += 2;

            // cari badge sesuai poin
            $badge = Badge::where('min_point', '<=', $user->points)
                ->where('max_point', '>=', $user->points)
                ->first();

            $naikBadge = false;

            if ($badge && $user->badge_id != $badge->id) {
                $user->badge_id = $badge->id;
                $naikBadge = true;
            }

            $user->save();

            // ==========================
            // RESPONSE (UNTUK POP-UP)
            // ==========================
            return response()->json([
                'success'   => true,
                'points'    => $user->points,
                'naikBadge' => $naikBadge,
                'badge'     => $badge?->name,
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
