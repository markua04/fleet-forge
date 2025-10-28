<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Repositories\UserRepository;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class AddCashForUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addCashForUser {userId : ID of the user} {amount : Amount (DKK) to add}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add cash to a user balance.';

    public function __construct(private readonly UserRepository $users)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $userId = (int) $this->argument('userId');
        $amount = (float) $this->argument('amount');

        if ($userId <= 0) {
            $this->error('User ID must be a positive integer.');

            return self::FAILURE;
        }

        if ($amount <= 0) {
            $this->error('Amount must be greater than zero.');

            return self::FAILURE;
        }

        try {
            $user = DB::transaction(function () use ($userId, $amount) {
                $freshUser = $this->users->findForUpdate($userId);
                $freshUser->cash = $freshUser->cash + $amount;
                $freshUser->save();

                return $freshUser->fresh();
            });
        } catch (ModelNotFoundException) {
            $this->error(sprintf('User with ID %d was not found.', $userId));

            return self::FAILURE;
        }

        $this->info(sprintf(
            'Added DKK %s to user #%d. New balance: DKK %s.',
            number_format($amount, 2, ',', '.'),
            $userId,
            number_format((float) $user->cash, 2, ',', '.')
        ));

        return self::SUCCESS;
    }
}
