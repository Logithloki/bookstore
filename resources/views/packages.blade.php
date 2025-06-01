<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Plans - Book Hive</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body class="bg-gray-100 font-sans antialiased min-h-screen flex flex-col">
    <!-- Navbar -->
    <x-navbar currentPage="packages" />

    <!-- Main Section -->
    <main class="relative flex-grow flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('img/About.jpg') }}" alt="Library Background" 
                class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-br from-black/80 via-black/75 to-black/80"></div>
        </div>

        <!-- Subscription Plans Container -->
        <div class="relative z-10 w-full max-w-6xl animate-fade-in">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-white mb-4 transform hover:scale-105 transition-transform duration-300">
                    Choose Your Perfect Plan
                </h1>
                <p class="text-gray-300 text-lg">Select the subscription that best fits your needs</p>
            </div>

            <!-- Plans Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- Basic Plan -->
                <div class="bg-black/40 backdrop-blur-sm rounded-2xl shadow-2xl p-8 border border-white/10
                    transform transition-all duration-300 hover:scale-[1.02] hover:border-orange-500/50
                    animate-fade-in" style="animation-delay: 0.2s">
                    <div class="text-center">
                        <h2 class="text-2xl font-bold text-white mb-2">Basic Plan</h2>
                        <div class="w-20 h-1 bg-orange-500 mx-auto mb-6"></div>
                        <p class="text-orange-500 font-bold text-3xl mb-6">80 LKR</p>
                        
                        <ul class="space-y-4 text-left mb-8">
                            <li class="flex items-center text-gray-300">
                                <i class="fa fa-check text-orange-500 mr-3"></i>
                                <span>Unlimited Validity</span>
                            </li>
                            <li class="flex items-center text-gray-300">
                                <i class="fa fa-check text-orange-500 mr-3"></i>
                                <span>1 Ad Post</span>
                            </li>
                            <li class="flex items-center text-gray-300">
                                <i class="fa fa-check text-orange-500 mr-3"></i>
                                <span>Basic Support</span>
                            </li>
                        </ul>

                        <button onclick="selectPlan('BASIC')" 
                            class="w-full bg-orange-500 text-white py-3 px-6 rounded-lg font-medium
                                transform transition-all duration-300 hover:bg-orange-600 hover:scale-[1.02]
                                focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:ring-offset-gray-900
                                disabled:opacity-50 disabled:cursor-not-allowed">
                            Select Basic Plan
                        </button>
                    </div>
                </div>

                <!-- Premium Plan -->
                <div class="bg-black/40 backdrop-blur-sm rounded-2xl shadow-2xl p-8 border border-white/10
                    transform transition-all duration-300 hover:scale-[1.02] hover:border-orange-500/50
                    animate-fade-in" style="animation-delay: 0.4s">
                    <div class="text-center">
                        <div class="absolute top-0 right-0 bg-orange-500 text-white px-4 py-1 rounded-bl-lg rounded-tr-lg text-sm font-medium">
                            Popular
                        </div>
                        <h2 class="text-2xl font-bold text-white mb-2">Premium Plan</h2>
                        <div class="w-20 h-1 bg-orange-500 mx-auto mb-6"></div>
                        <p class="text-orange-500 font-bold text-3xl mb-6">600 LKR</p>
                        
                        <ul class="space-y-4 text-left mb-8">
                            <li class="flex items-center text-gray-300">
                                <i class="fa fa-check text-orange-500 mr-3"></i>
                                <span>60 Days Validity</span>
                            </li>
                            <li class="flex items-center text-gray-300">
                                <i class="fa fa-check text-orange-500 mr-3"></i>
                                <span>10 Ad Posts</span>
                            </li>
                            <li class="flex items-center text-gray-300">
                                <i class="fa fa-check text-orange-500 mr-3"></i>
                                <span>Priority Support</span>
                            </li>
                            <li class="flex items-center text-gray-300">
                                <i class="fa fa-check text-orange-500 mr-3"></i>
                                <span>Featured Listings</span>
                            </li>
                        </ul>

                        <button onclick="selectPlan('PREMIUM')" 
                            class="w-full bg-orange-500 text-white py-3 px-6 rounded-lg font-medium
                                transform transition-all duration-300 hover:bg-orange-600 hover:scale-[1.02]
                                focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:ring-offset-gray-900
                                disabled:opacity-50 disabled:cursor-not-allowed">
                            Select Premium Plan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="mt-12 text-center text-gray-300 animate-fade-in" style="animation-delay: 0.6s">
                <p class="text-sm">Need help choosing? <a href="#" class="text-orange-400 hover:text-orange-300 transition-colors duration-300">Contact us</a></p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <div class="mt-auto">
        <x-footer />
    </div>    <script>
        function selectPlan(plan) {
            // Create a form to submit the plan selection
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("subscribe") }}';
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Add plan selection
            const planInput = document.createElement('input');
            planInput.type = 'hidden';
            planInput.name = 'plan';
            planInput.value = plan;
            form.appendChild(planInput);
            
            // Submit the form
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html> 