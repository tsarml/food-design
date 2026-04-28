<?php

namespace App\Models;

use CodeIgniter\Model;

class SwipeModel extends Model
{
    protected $table      = 'swipes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'food_id',
        'action',
    ];
    protected $useTimestamps = true;
    protected $updatedField  = '';

    public function hasSwipped(int $userId, int $foodId): bool
    {
        return $this->where('user_id', $userId)
            ->where('food_id', $foodId)
            ->countAllResults() > 0;
    }

    public function saveSwipe(int $userId, int $foodId, string $action): bool
    {
        if (!in_array($action, ['like', 'super', 'skip'])) {
            return false;
        }

        if ($this->hasSwipped($userId, $foodId)) {
            return false;
        }

        return $this->insert([
            'user_id' => $userId,
            'food_id' => $foodId,
            'action'  => $action,
        ]) !== false;
    }

    public function getStats(int $userId): array
    {
        $swipes = $this->db->table('swipes s')
            ->select('s.action, f.id, f.name, f.emoji, f.image,
                      f.category, f.time_min, f.calories, f.rating')
            ->join('foods f', 'f.id = s.food_id')
            ->where('s.user_id', $userId)
            ->get()
            ->getResultArray();

        $totalSeen  = count($swipes);
        $liked      = array_filter($swipes, fn($s) => in_array($s['action'], ['like', 'super']));
        $superLiked = array_filter($swipes, fn($s) => $s['action'] === 'super');

        $likedByCategory = [];
        foreach ($liked as $s) {
            $cat = $s['category'];
            $likedByCategory[$cat] = ($likedByCategory[$cat] ?? 0) + 1;
        }
        arsort($likedByCategory);

        $rate = $totalSeen > 0 ? round((count($liked) / $totalSeen) * 100) : 0;

        return [
            'total_seen'        => $totalSeen,
            'total_liked'       => count($liked),
            'total_super'       => count($superLiked),
            'appreciation_rate' => $rate,
            'liked_by_category' => $likedByCategory,
            'liked_foods'       => array_values($liked),
        ];
    }
}
