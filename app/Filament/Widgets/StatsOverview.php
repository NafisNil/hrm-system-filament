<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Department;
use App\Models\User;
use App\Models\LeaveRequest;
use App\Models\Attendance;
class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            //
            Stat::make('Departments', Department::count())
                ->description('Total Departments')->descriptionIcon('heroicon-o-building-office-2')
                ->chart([10, 25, 15, 30, 12, 15])
                ->color('success'),
            Stat::make('Employees', User::count())
                ->description('Total Employees')->descriptionIcon('heroicon-o-users')
                ->chart([10, 25, 15, 30, 12, 15])
                ->color('info'),
            Stat::make('Leave Request', LeaveRequest::count())->description('Total Leave Request')->descriptionIcon('heroicon-o-document-text')
                ->chart([10, 25, 15, 30, 12, 15])
                ->color('warning'),
            Stat::make('Attendance', Attendance::count())->description('Total Attendance')->descriptionIcon('heroicon-o-calendar')
                ->chart([10, 25, 15, 30, 12, 15])
                ->color('danger'),
        ];
    }
}
