<?php

namespace App\Auth\Registration;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Manages registrants, storing data on their email and what role they have been assigned as well as generating
 * and verifying tokens to ensure they have been approved.
 */
class RegistrationTokenRepository
{

    private static RegistrationTokenRepository|null $INSTANCE = null;

    public static function get(): RegistrationTokenRepository
    {
        if (self::$INSTANCE === null) {
            $key = Config::get('app.key');
            if (str_starts_with($key, 'base64:')) {
                $key = base64_decode(substr($key, 7));
            }
            self::$INSTANCE = new RegistrationTokenRepository('registration_tokens', $key, 12);
        }
        return self::$INSTANCE;
    }

    private function __construct(
        private readonly string $table,
        private readonly string $key,
        private readonly int $expires
    ) {
    }

    public function create(Registrant $registrant): string
    {
        // Ensure the email does not already have a registration token
        $this->deleteExisting($registrant->getEmail());

        // Create the token and insert the record
        $token = $this->createNewToken();
        $this->getTable()->insert($this->createRecord($registrant, $token));

        return $token;
    }

    private function createNewToken()
    {
        return hash_hmac('sha256', Str::random(40), $this->key);
    }

    private function createRecord(Registrant $registrant, #[\SensitiveParameter] string $token): array
    {
        return [
            'email' => $registrant->getEmail(),
            'is_admin' => $registrant->isAdmin(),
            'token' => Hash::make($token),
            'created_at' => new Carbon
        ];
    }

    public function deleteExisting(string $email): bool
    {
        return $this->getTable()->where('email', $email)->delete();
    }

    public function validate(string $email, #[\SensitiveParameter] string $token): Registrant|null
    {
        $record = (array) $this->getTable()->where('email', $email)->first();
        // Ensure email and token match, are valid and not past expiry
        if (!$record || $this->tokenExpired($record['created_at']) || !Hash::check($token, $record['token'])) {
            return null;
        }
        return $this->readRecord($record);
    }

    protected function tokenExpired($createdAt)
    {
        return Carbon::parse($createdAt)->addHours($this->expires)->isPast();
    }

    private function readRecord($record): Registrant
    {
        return new Registrant($record['email'], $record['is_admin']);
    }

    private function getTable(): Builder
    {
        return DB::table($this->table);
    }

}
