<?php

namespace Tests\Unit\Auth\Registration;

use App\Auth\Registration\Registrant;
use App\Auth\Registration\RegistrationTokenRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegistrationTokenRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_updates_database(): void
    {
        RegistrationTokenRepository::get()->create(new Registrant('test@email.com', false));

        $this->assertDatabaseHas('registration_tokens', [
            'email' => 'test@email.com',
            'is_admin' => false
        ]);
    }

    public function test_validate_returns_null_if_no_codes(): void
    {
        $result = RegistrationTokenRepository::get()->validate('test@email.com', 'test-token');

        $this->assertNull($result);
    }

    public function test_validate_returns_null_if_no_codes_for_email(): void
    {
        RegistrationTokenRepository::get()->create(new Registrant('test@email.com', false));
        $result = RegistrationTokenRepository::get()->validate('another-test@email.com', 'test-token');

        $this->assertNull($result);
    }

    public function test_validate_returns_null_if_token_incorrect(): void
    {
        RegistrationTokenRepository::get()->create(new Registrant('test@email.com', false));
        $result = RegistrationTokenRepository::get()->validate('test@email.com', 'test-token');

        $this->assertNull($result);
    }

    public function test_validate_returns_null_if_token_correct_but_email_doesnt_match(): void
    {
        $token = RegistrationTokenRepository::get()->create(new Registrant('test@email.com', false));
        $result = RegistrationTokenRepository::get()->validate('another-test@email.com', $token);

        $this->assertNull($result);
    }

    public function test_validate_returns_null_if_token_expired(): void
    {
        DB::table('registration_tokens')->insert([
            'email' => 'test@email.com',
            'is_admin' => false,
            'token' => Hash::make('test-token'),
            'created_at' => now()->subHours(12)->subSecond(),
        ]);

        $result = RegistrationTokenRepository::get()->validate('another-test@email.com', 'test-token');

        $this->assertNull($result);
    }

    public function test_validate_returns_data_if_token_correct(): void
    {
        $token = RegistrationTokenRepository::get()->create(new Registrant('test@email.com', false));
        $result = RegistrationTokenRepository::get()->validate('test@email.com', $token);

        $this->assertNotNull($result);
    }

    public function test_validate_returns_correct_data(): void
    {
        $registrant = new Registrant('test@email.com', false);
        $token = RegistrationTokenRepository::get()->create($registrant);
        $result = RegistrationTokenRepository::get()->validate($registrant->getEmail(), $token);

        $this->assertEquals((array) $registrant, (array) $result);
    }

    public function test_delete_existing_updates_database(): void
    {
        DB::table('registration_tokens')->insert([
            'email' => 'test@email.com',
            'is_admin' => false,
            'token' => Hash::make('test-token'),
            'created_at' => now()
        ]);

        RegistrationTokenRepository::get()->deleteExisting('test@email.com');

        $this->assertDatabaseMissing('registration_tokens', [
            'email' => 'test@email.com'
        ]);
    }

    public function test_delete_existing_only_deletes_for_specified_email(): void
    {
        DB::table('registration_tokens')->insert([
            'email' => 'test@email.com',
            'is_admin' => false,
            'token' => Hash::make('test-token'),
            'created_at' => now()
        ]);
        DB::table('registration_tokens')->insert([
            'email' => 'another-test@email.com',
            'is_admin' => false,
            'token' => Hash::make('test-token'),
            'created_at' => now()
        ]);

        RegistrationTokenRepository::get()->deleteExisting('test@email.com');

        $this->assertDatabaseMissing('registration_tokens', [
            'email' => 'test@email.com'
        ]);
        $this->assertDatabaseHas('registration_tokens', [
            'email' => 'another-test@email.com'
        ]);
    }

    public function test_delete_expired_doesnt_delete_unexpired_tokens(): void
    {
        DB::table('registration_tokens')->insert([
            'email' => 'test@email.com',
            'is_admin' => false,
            'token' => Hash::make('test-token'),
            'created_at' => now()->subHours(11)->subMinutes(59),
        ]);
        DB::table('registration_tokens')->insert([
            'email' => 'another-test@email.com',
            'is_admin' => false,
            'token' => Hash::make('test-token'),
            'created_at' => now()->subHours(7),
        ]);
        DB::table('registration_tokens')->insert([
            'email' => 'yet-another-test@email.com',
            'is_admin' => false,
            'token' => Hash::make('test-token'),
            'created_at' => now(),
        ]);
        // Control - should be deleted
        DB::table('registration_tokens')->insert([
            'email' => 'and-yet-another-test@email.com',
            'is_admin' => false,
            'token' => Hash::make('test-token'),
            'created_at' => now()->subHours(12)->subMinute(),
        ]);

        RegistrationTokenRepository::get()->deleteExpired();

        $this->assertDatabaseCount('registration_tokens', 3);
    }

    public function test_delete_expired_deletes_expired_tokens(): void
    {
        DB::table('registration_tokens')->insert([
            'email' => 'test@email.com',
            'is_admin' => false,
            'token' => Hash::make('test-token'),
            'created_at' => now()->subHours(12)->subSecond(),
        ]);
        DB::table('registration_tokens')->insert([
            'email' => 'another-test@email.com',
            'is_admin' => false,
            'token' => Hash::make('test-token'),
            'created_at' => now()->subDays(3),
        ]);
        DB::table('registration_tokens')->insert([
            'email' => 'yet-another-test@email.com',
            'is_admin' => false,
            'token' => Hash::make('test-token'),
            'created_at' => now()->subWeeks(4),
        ]);
        // Control - should not be deleted
        DB::table('registration_tokens')->insert([
            'email' => 'and-yet-another-test@email.com',
            'is_admin' => false,
            'token' => Hash::make('test-token'),
            'created_at' => now()->subHours(7),
        ]);

        RegistrationTokenRepository::get()->deleteExpired();

        $this->assertDatabaseCount('registration_tokens', 1);
    }

}
