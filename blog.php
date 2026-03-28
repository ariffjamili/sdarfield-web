<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latest Updates - SDAR Field Rehabilitation Project</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>

<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

    <?php $prefix = 'index.php'; $activePage = 'blog'; include 'includes/nav.php'; ?>

    <main class="flex-grow w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <header class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-extrabold text-blue-900 mb-4 tracking-tight">Project Updates</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Latest news, milestones, and announcements from the SDAR Field Rehabilitation Project team.</p>
        </header>

        <!-- Loader -->
        <div id="loader" class="flex justify-center items-center py-20">
            <svg class="animate-spin h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        <div id="posts-container" class="space-y-12"></div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="js/blog.js"></script>
</body>
</html>
