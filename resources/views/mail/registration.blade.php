<x-layouts.auth.simple>
    <flux:heading size="xl">Hello there!</flux:heading>
    <flux:text>You have been invited by an admin of the site to register! Click the button below and enter your
    details to get started. You have 12 hours from the receipt of this email to complete your registration.</flux:text>
    <flux:button href="{{ route('register', [$token, 'email' => $email]) }}" variant="primary" iconLeading="user-circle">{{ __('Register') }}</flux:button>
</x-layouts.auth.simple>
