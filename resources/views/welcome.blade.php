<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to FormFlow</title>
    @vite(['resources/css/app.css', 'resources/js/welcome.js']) <!-- Include CSS and JS -->
</head>
<body class="bg-white text-gray-800 min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-6">
        <!-- Logo Section -->
        <header class="text-center mb-12">
            <img src="{{ asset('images/FormFlowLogo1.png') }}" alt="FormFlow Logo" class="mx-auto w-54 h-64 mb-4">
            <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 text-transparent bg-clip-text">
                Welcome to FormFlow
            </h1>
            <p class="mt-4 text-lg text-gray-600">Streamline your workflow with ease and efficiency.</p>
        </header>

        <!-- Feature Cards -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1 -->
            <div class="feature-card p-6 rounded-lg shadow-lg text-white bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600">
                <h2 class="text-xl font-semibold mb-4">Streamlined Forms</h2>
                <p class="mb-4">Create, manage, and share forms effortlessly.</p>
                <a href="#" class="text-white font-semibold underline">Learn More</a>
            </div>

            <!-- Card 2 -->
            <div class="feature-card p-6 rounded-lg shadow-lg text-white bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600">
                <h2 class="text-xl font-semibold mb-4">Data Insights</h2>
                <p class="mb-4">Track submissions and analyze data in real-time.</p>
                <a href="#" class="text-white font-semibold underline">Learn More</a>
            </div>

            <!-- Card 3 -->
            <div class="feature-card p-6 rounded-lg shadow-lg text-white bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600">
                <h2 class="text-xl font-semibold mb-4">Seamless Integration</h2>
                <p class="mb-4">Integrate FormFlow with your favorite tools.</p>
                <a href="#" class="text-white font-semibold underline">Learn More</a>
            </div>
        </section>

        <!-- Call-to-Action -->
        <div class="text-center mt-12">
            <a href="{{ route('login') }}" id="getStarted" class="bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 hover:opacity-90 text-white font-semibold py-3 px-6 rounded-lg transition-opacity duration-300">
                Get Started
            </a>
        </div>
    </div>
</body>
</html>
