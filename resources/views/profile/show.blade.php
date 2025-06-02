<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings - Book Hive</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body class="bg-gray-100 font-sans">
    <!-- Navbar -->
    <x-navbar currentPage="profile" />

    <div class="min-h-screen bg-gray-100">
        <!-- Main Section -->
        <main class="container mx-auto py-16 px-6">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">
                    <i class="fa fa-user-cog mr-3"></i>Profile Settings
                </h1>
                <p class="text-gray-600 text-lg">Manage your account information and security settings</p>
            </div>

            <div class="max-w-4xl mx-auto space-y-8">
                @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                    <!-- Profile Information Section -->
                    <div class="bg-white bg-opacity-95 rounded-lg shadow-lg p-6">
                        <div class="flex items-center mb-6">
                            <i class="fa fa-user text-orange-500 text-2xl mr-3"></i>
                            <h2 class="text-2xl font-bold text-gray-800">Profile Information</h2>
                        </div>
                        @livewire('profile.update-profile-information-form')
                    </div>
                @endif

                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                    <!-- Password Section -->
                    <div class="bg-white bg-opacity-95 rounded-lg shadow-lg p-6">
                        <div class="flex items-center mb-6">
                            <i class="fa fa-lock text-orange-500 text-2xl mr-3"></i>
                            <h2 class="text-2xl font-bold text-gray-800">Update Password</h2>
                        </div>
                        @livewire('profile.update-password-form')
                    </div>
                @endif

                @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                    <!-- Two Factor Authentication Section -->
                    <div class="bg-white bg-opacity-95 rounded-lg shadow-lg p-6">
                        <div class="flex items-center mb-6">
                            <i class="fa fa-shield text-orange-500 text-2xl mr-3"></i>
                            <h2 class="text-2xl font-bold text-gray-800">Two Factor Authentication</h2>
                        </div>
                        @livewire('profile.two-factor-authentication-form')
                    </div>
                @endif

                <!-- Browser Sessions Section -->
                <div class="bg-white bg-opacity-95 rounded-lg shadow-lg p-6">
                    <div class="flex items-center mb-6">
                        <i class="fa fa-globe text-orange-500 text-2xl mr-3"></i>
                        <h2 class="text-2xl font-bold text-gray-800">Browser Sessions</h2>
                    </div>
                    @livewire('custom-logout-other-browser-sessions-form')
                </div>

                @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                    <!-- Delete Account Section -->
                    <div class="bg-white bg-opacity-95 rounded-lg shadow-lg p-6 border-l-4 border-red-500">
                        <div class="flex items-center mb-6">
                            <i class="fa fa-trash text-red-500 text-2xl mr-3"></i>
                            <h2 class="text-2xl font-bold text-gray-800">Delete Account</h2>
                        </div>
                        @livewire('profile.delete-user-form')
                    </div>
                @endif

                <!-- Back to Account Button -->
                <div class="text-center">
                    <a href="{{ route('account.show') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-lg text-lg transition duration-200 inline-block">
                        <i class="fa fa-arrow-left mr-2"></i>Back to Account Dashboard
                    </a>
                </div>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <x-footer />
</body>
</html>
