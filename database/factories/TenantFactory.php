<?php
// database/factories/TenantFactory.php
namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition(): array
    {
        $slug = Str::slug($this->faker->unique()->company());
        $slug = strtolower(preg_replace('/[^a-z0-9]+/','-', $slug));

        return [
            'name'          => $this->faker->company(),
            'slug'          => $slug ?: 'tenant-'.Str::random(6),
            'plan_id'       => 1,
            'status'        => 'trialing',
            'trial_ends_at' => now()->addDays(7),
        ];
    }
}

