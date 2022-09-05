<?php

namespace App\Services\Modules\MMajor;

class Major implements MMajorInterface
{
    public function __construct(public \App\Models\Major $major)
    {
    }

    public function getRatingUserByMajorSlug($slug)
    {
        if (!$major = $this->major::whereSlug($slug)
            ->with([
                'contest_user' => function ($q) {
                    return $q
                        ->selectRaw('sum(contest_users.reward_point) as reward_point,contest_users.user_id')
                        ->groupBy('contest_users.user_id')
                        ->orderByDesc('reward_point');
                }
            ])
            ->first()) return false;
        return $major
            ->contest_user
            ->map(function ($q, $index) use (&$rank, &$maxPoin) {
                if ($index == 0) {
                    $maxPoin = $q->reward_point;
                }
                // **
                if ($index == 0) {
                    $rank = 1;
                }
                // **
                if ($q->reward_point == $maxPoin) {
                    $rank = $rank;
                }
                // **
                if ($q->reward_point < $maxPoin) {
                    $rank += 1;
                }
                // **
                if ($q->reward_point < $maxPoin) {
                    $maxPoin = $q->reward_point;
                }
                // **
                return [
                    'user_name' => $q->user->name,
                    'avatar' => $q->user->avatar,
                    'rank' => $rank,
                    'reward_point' => $q->reward_point,
                    'user' => $q->user,
                ];
            })
            ->toArray();
        // $rank = 0;
        // $maxPoin = 0;
        // return $major
        //     ->contest_user()
        //     ->orderByDesc('reward_point')
        //     ->with('contest')
        //     ->get()
        //     ->map(function ($q, $index) use (&$rank, &$maxPoin) {
        //         if ($index == 0) {
        //             $maxPoin = $q->reward_point;
        //         }
        //         // **
        //         if ($index == 0) {
        //             $rank = 1;
        //         }
        //         // **
        //         if ($q->reward_point == $maxPoin) {
        //             $rank = $rank;
        //         }
        //         // **
        //         if ($q->reward_point < $maxPoin) {
        //             $rank += 1;
        //         }
        //         // **
        //         if ($q->reward_point < $maxPoin) {
        //             $maxPoin = $q->reward_point;
        //         }
        //         // **
        //         return [
        //             'user_name' => $q->user->name,
        //             'avatar' => $q->user->avatar,
        //             'rank' => $rank,
        //             'reward_point' => $q->reward_point,
        //             'contest_name' => $q->contest->name,
        //             'contest' => $q->contest,
        //             'user' => $q->user,
        //         ];
        //     });
    }


    public function getRankUserCapacity($slug)
    {
        if (!$major = $this->major::whereSlug($slug)
            ->first()) return false;

        $major->load(
            ['resultCapacity' => function ($q) {
                return $q->where('contests.type', config('util.TYPE_TEST'))
                    ->where('result_capacity.status', config('util.STATUS_RESULT_CAPACITY_DONE'))
                    ->selectRaw('sum(result_capacity.scores) as total_scores, result_capacity.user_id')
                    ->groupBy('result_capacity.user_id')
                    ->orderByDesc('total_scores');
            }]
        );
        return  $major->resultCapacity;
    }
}