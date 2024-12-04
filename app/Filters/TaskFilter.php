<?php
namespace App\Filters;

use Illuminate\Support\Carbon;

class TaskFilter
{
    public function apply($query, $filters)
    {
        if (isset($filters['filterTime'])) {
            if ($filters['filterTime'] === 'last_week') {
                $query->whereBetween('created_at', [
                    Carbon::now()->subWeek()->startOfDay(),
                    Carbon::now()->endOfDay(),
                ]);
            } elseif ($filters['filterTime'] === 'last_month') {
                $query->whereBetween('created_at', [
                    Carbon::now()->subMonth()->startOfDay(),
                    Carbon::now()->endOfDay(),
                ]);
            }
        }
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        return $query;
    }
}
