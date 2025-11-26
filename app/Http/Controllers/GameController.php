<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameScore;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    /** Show the main games page */
    public function index()
    {
        return view('games.index');
    }

    /** Show a specific game page */
    public function show($type)
    {
        $allowed = ['speed', 'race', 'letters', 'random-words', 'arabic-typing'];

        if (!in_array($type, $allowed)) {
            abort(404, 'اللعبة غير موجودة');
        }

        return view('games.show', compact('type'));
    }

    /** Save or update player score and return rank */
    public function store(Request $request)
    {
        try {
            // Validate input
            $data = $request->validate([
                'player_name' => 'required|string|max:191',
                'wpm'         => 'required|integer|min:0',
                'accuracy'    => 'required|numeric|min:0|max:100',
                'game_type'   => 'required|in:speed,race,letters,random-words,arabic-typing',
            ]);

            // Add user ID if logged in
            $data['user_id'] = Auth::check() ? Auth::id() : null;

            // Find existing record (by user or player name)
            $existing = GameScore::where('game_type', $data['game_type'])
                ->when($data['user_id'], fn($q) => $q->where('user_id', $data['user_id']))
                ->when(!$data['user_id'], fn($q) => $q->where('player_name', $data['player_name']))
                ->first();

            // Update if better
            if ($existing) {
                if ($data['wpm'] > $existing->wpm || $data['accuracy'] > $existing->accuracy) {
                    $existing->update([
                        'wpm'        => $data['wpm'],
                        'accuracy'   => $data['accuracy'],
                        'updated_at' => now(),
                    ]);
                    $rank = $this->calculateRank($existing->game_type, $data['wpm']);
                    return response()->json([
                        'success'    => true,
                        'message'    => 'تم تحديث نتيجتك بنجاح! لقد حققت أداءً أفضل.',
                        'new_record' => true,
                        'rank'       => $rank,
                        'data'       => $existing,
                    ], 200, [], JSON_UNESCAPED_UNICODE);
                }

                $rank = $this->calculateRank($existing->game_type, $existing->wpm);
                return response()->json([
                    'success'    => true,
                    'message'    => 'لم يتم الحفظ لأن النتيجة الجديدة أقل من السابقة.',
                    'new_record' => false,
                    'rank'       => $rank,
                ], 200, [], JSON_UNESCAPED_UNICODE);
            }

            // First save
            $new = GameScore::create($data);
            $rank = $this->calculateRank($data['game_type'], $data['wpm']);

            return response()->json([
                'success'    => true,
                'message'    => 'تم حفظ نتيجتك الأولى بنجاح.',
                'new_record' => true,
                'rank'       => $rank,
                'data'       => $new,
            ], 200, [], JSON_UNESCAPED_UNICODE);

        } catch (\Throwable $e) {
            Log::error('Game save error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ النتيجة، يرجى المحاولة لاحقًا.',
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /** Calculate player rank based on WPM */
    private function calculateRank(string $gameType, int $wpm): int
    {
        // Count how many players have higher WPM in the same game
        $higher = GameScore::where('game_type', $gameType)
            ->where('wpm', '>', $wpm)
            ->count();

        // Rank = number of higher players + 1
        return $higher + 1;
    }

    /** Get top 10 players */
    public function leaderboard()
    {
        try {
            $topPlayers = GameScore::orderByDesc('wpm')
                ->limit(10)
                ->get(['player_name', 'wpm', 'accuracy', 'game_type', 'created_at']);

            return response()->json($topPlayers, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            Log::error('Leaderboard fetch error: ' . $e->getMessage());
            return response()->json(['error' => 'فشل تحميل قائمة المتصدرين.'], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /** Get all players and their scores */
    /** 🏆 جميع اللاعبين مرتبين حسب السرعة والدقة */
public function all()
{
    try {
        // ✅ ترتيب اللاعبين أولاً حسب السرعة (wpm) ثم حسب الدقة (accuracy)
        $allPlayers = GameScore::orderByDesc('wpm')
            ->orderByDesc('accuracy')
            ->get(['player_name', 'wpm', 'accuracy', 'game_type', 'created_at'])
            ->map(function ($player, $index) {
                // 🔢 إضافة ترتيب تسلسلي (rank)
                $player->rank = $index + 1;
                return $player;
            });

        // ✅ إرجاع النتائج كـ JSON
        return response()->json($allPlayers, 200, [], JSON_UNESCAPED_UNICODE);

    } catch (\Throwable $e) {
        Log::error('All players fetch error: ' . $e->getMessage());

        return response()->json([
            'error' => 'فشل تحميل النتائج.'
        ], 500, [], JSON_UNESCAPED_UNICODE);
    }
}

}
