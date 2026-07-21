<?php

namespace App\Filament\Employee\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use UnitEnum;
use App\Models\Attendance;
use Filament\Actions\Action;
class CheckInOut extends Page
{
    use HasPageShield;
    protected string $view = 'filament.employee.pages.check-in-out';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::AcademicCap;
    protected static string|UnitEnum|null $navigationGroup = 'Attendance';

    public $todayAttendance;
    public $canCheckIn = false;
    public $canCheckOut = false;
    public $currentTime;

    public function mount()
    {
        $this->loadAttendance();
        $this->currentTime = now()->format('H:i:s');
    }

    public function loadAttendance()
    {
        $this->todayAttendance = Attendance::where('date', now()->toDateString())
            ->where('user_id', auth()->id())
            ->first();

        if ($this->todayAttendance) {
            $this->canCheckIn = false;
            $this->canCheckOut = !$this->todayAttendance->check_out_time;
        } else {
            $this->canCheckIn = true;
            $this->canCheckOut = false;
        }
    }

    public function checkIn()
    {
        try {
            if ($this->canCheckIn) {
                Attendance::create([
                    'user_id' => auth()->id(),
                    'check_in_time' => now(),
                    'date' => now()->toDateString(),
                    'status' => now()->format('H:i:s') < '09:00:00' ? 'present' : 'late',
                ]);
                $this->loadAttendance();
                Notification::make()
                    ->title('Checked In Successfully')
                    ->body('You have successfully checked in at ' . now()->format('H:i:s'))
                    ->success()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Check-In Failed')
                ->body('An error occurred while checking in: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function checkOut()
    {
        if ($this->canCheckOut && $this->todayAttendance) {
            $this->todayAttendance->update([
                'check_out_time' => now(),
            ]);
            $this->loadAttendance();
            Notification::make()
                ->title('Checked Out Successfully')
                ->body('You have successfully checked out at ' . now()->format('H:i:s'))
                ->success()
                ->send();
        }
    }

    protected function getHeaderActions() : array{
        return [
            Action::make('Check In')
                ->action('checkIn')
                ->visible(fn () => $this->canCheckIn)->icon(Heroicon::ArrowUpOnSquareStack)->color('success')->requiresConfirmation()->modalHeading('Check In Confirmation')->modalDescription('Are you sure you want to check in? This action will record your check-in time.')->modalSubmitActionLabel('Yes, Check In')->action(fn() => $this->checkIn()),

            Action::make('Check Out')
                ->action('checkOut')
                ->visible(fn () => $this->canCheckOut)->icon(Heroicon::ArrowDownOnSquareStack)->color('danger')->requiresConfirmation()->modalHeading('Check Out Confirmation')->modalDescription('Are you sure you want to check out? This action will record your check-out time.')->modalSubmitActionLabel('Yes, Check Out')->action(fn() => $this->checkOut()),
        ];
    }
}
