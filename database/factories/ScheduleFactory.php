<?php
namespace Database\Factories;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    public function definition(): array
    {
        $start = now()->addDays(rand(1, 10))->setTime(rand(9, 18), 0, 0);
        $end = (clone $start)->addHour();

        return [
            'date' => $start->toDateString(),
            'start_time' => $start->toTimeString(),
            'end_time' => $end->toTimeString(),
            'is_available' => true,
        ];
    }
}
