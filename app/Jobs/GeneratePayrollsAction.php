<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\User;
use App\Models\Payroll;

class GeneratePayrollsAction implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;
    public $timeout = 120; // Set the timeout to 120 seconds (2 minutes)
    public $tries = 3; // Set the number of attempts to 3

    /**
     * Create a new job instance.
     */
    public function __construct(public string $month, public int $year, public ?int $userId = null)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $query = User::where('status', 'active')->whereNotNull('salary');
        if ($this->userId) {
            $query->where('id', $this->userId);
        }
        $employees = $query->get();
        foreach ($employees as $employee) {
            try {
                $existingPayroll = Payroll::where('user_id', $employee->id)
                    ->where('month', $this->month)
                    ->where('year', $this->year)
                    ->first();
                if ($existingPayroll) {
                    // Payroll already exists for this employee for the given month and year, skip to the next
                    Log::info("Payroll already exists for user ID {$employee->id} for {$this->month} {$this->year}. Skipping.");
                    continue;
                }

                // Create a new payroll record for the employee
                $basicSalary = $employee->salary;
                $allowances = $employee->allowances ?? 0; // Assuming allowances is a numeric field
                $deductions = $employee->deductions ?? 0; // Assuming deductions is a numeric field
                $bonus = $employee->bonus ?? 0; // Assuming bonus is a numeric field
                $netSalary = $basicSalary + $allowances - $deductions + $bonus;

                Payroll::create([
                    'user_id' => $employee->id,
                    'month' => $this->month,
                    'year' => $this->year,
                    'basic_salary' => $basicSalary,
                    'allowances' => $allowances,
                    'deductions' => $deductions,
                    'bonus' => $bonus,
                    'net_salary' => $netSalary,
                    'status' => 'pending', // Set the initial status to 'pending'
                ]);
                Log::info("Payroll generated for user ID {$employee->id} for {$this->month} {$this->year}.");
            } catch (\Exception $e) {
                // Log the error or handle it as needed
                Log::error("Failed to generate payroll for user ID {$employee->id}: " . $e->getMessage());
            }
        }
    }
}
