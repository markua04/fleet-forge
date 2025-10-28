<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddCashForUserCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_adds_cash_to_user(): void
    {
        $user = User::factory()->create(['cash' => 1000]);

        $this->artisan('addCashForUser', [
            'userId' => (string) $user->id,
            'amount' => '5000',
        ])
            ->expectsOutput(sprintf(
                'Added DKK %s to user #%d. New balance: DKK %s.',
                number_format(5000, 2, ',', '.'),
                $user->id,
                number_format(6000, 2, ',', '.')
            ))
            ->assertExitCode(0);

        $this->assertEquals(6000.00, (float) $user->fresh()->cash);
    }

    public function test_fails_when_user_not_found(): void
    {
        $this->artisan('addCashForUser', [
            'userId' => '999',
            'amount' => '100',
        ])
            ->expectsOutput('User with ID 999 was not found.')
            ->assertExitCode(1);
    }
}
