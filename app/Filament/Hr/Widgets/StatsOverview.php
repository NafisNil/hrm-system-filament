<?php

namespace App\Filament\Hr\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\PerformanceReview;
use App\Models\User;
use App\Models\LeaveRequest;
use App\Models\Attendance;
use App\Models\Payroll;
class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            //
            Stat::make('Total Employees', User::count())
                ->description('Total number of employees in the system.')
                ->icon('heroicon-o-users')
                ->color('primary'),
            Stat::make('Active Employees', User::where('status', 'active')->count())
                ->description('Total number of active employees in the system.')
                ->icon('heroicon-o-user-group')
                ->color('success'),
            Stat::make('Total Performance Reviews', PerformanceReview::count())
                ->description('Total number of performance reviews in the system.')
                ->icon('heroicon-o-document-text')
                ->color('success'),
            Stat::make('Total Leave Requests', LeaveRequest::count())
                ->description('Total number of leave requests in the system.')
                ->icon('heroicon-o-calendar')
                ->color('warning'),
            Stat::make("Today's Attendance Records", Attendance::whereDate('created_at', now()->toDateString())->count())
                ->description('Total number of attendance records for today.')
                ->icon('heroicon-o-check-circle')
                ->color('info'),
            Stat::make('Total Payroll Records', Payroll::count())
                ->description('Total number of payroll records in the system.')
                ->icon('heroicon-o-currency-dollar')
                ->color('secondary'),
        ];
    }
}
