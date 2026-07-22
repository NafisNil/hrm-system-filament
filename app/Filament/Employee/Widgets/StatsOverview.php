<?php

namespace App\Filament\Employee\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\LeaveRequest;
use App\Models\Attendance;
use App\Models\Payroll;
class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $userId = auth()->id();
        return [
            //
            Stat::make("Leave Requests", LeaveRequest::where('user_id', $userId)->count())
                ->description("Total Leave Requests")
                ->descriptionIcon("heroicon-o-briefcase"),
            Stat::make("Approved Leave Requests", LeaveRequest::where('user_id', $userId)->where('status', 'approved')->count())
                ->description("Total Approved Leave Requests")
                ->descriptionIcon("heroicon-o-check-circle"),
            Stat::make("Rejected Leave Requests", LeaveRequest::where('user_id', $userId)->where('status', 'rejected')->count())
                ->description("Total Rejected Leave Requests")
                ->descriptionIcon("heroicon-o-x-circle"),
            Stat::make("Pending Leave Requests", LeaveRequest::where('user_id', $userId)->where('status', 'pending')->count())
                ->description("Total Pending Leave Requests")
                ->descriptionIcon("heroicon-o-clock"),
            Stat::make("Attendance Records", Attendance::where('user_id', $userId)->count())
                ->description("Total Attendance Records")
                ->descriptionIcon("heroicon-o-calendar"),
            Stat::make("Payroll Records", Payroll::where('user_id', $userId)->count())
                ->description("Total Payroll Records")
                ->descriptionIcon("heroicon-o-currency-dollar"),
        ];
    }
}
