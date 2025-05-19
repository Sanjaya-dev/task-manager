<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class TaskStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total', Task::count()),
            Stat::make('Completed Tasks', Task::where('completed', true)->count()),
            Stat::make('Completion Rate', function() {
                $total = Task::count();
                if($total === 0) return '0%';
                $completed = Task::where('completed', true)->count();
                return round(($completed / $total) * 100) . '%';
            })
        ];
    }
}
