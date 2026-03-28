<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SDAR Project - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }</style>
</head>

<body>

    <!-- Login Section -->
    <div id="login-section" class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
            <h1 class="text-2xl font-bold text-center text-blue-900 mb-6">Admin Login</h1>
            <form id="login-form" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div id="login-error" class="hidden text-red-500 text-sm text-center bg-red-50 p-2 rounded"></div>
                <button type="submit" class="w-full bg-blue-700 text-white font-bold py-2 rounded-lg hover:bg-blue-800 transition">Sign In</button>
            </form>
            <p class="text-xs text-gray-400 mt-4 text-center">SDAR Field Rehabilitation Project</p>
        </div>
    </div>

    <!-- Dashboard Section -->
    <div id="dashboard-section" class="hidden min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow z-10 sticky top-0">
            <div class="max-w-5xl mx-auto px-4 py-4 flex justify-between items-center">
                <h1 class="text-xl font-bold text-blue-900">Admin Dashboard</h1>
                <button id="logout-btn" class="text-sm text-red-600 hover:text-red-800 font-medium border border-red-200 px-3 py-1 rounded hover:bg-red-50 transition">Logout</button>
            </div>
            <!-- Tabs -->
            <div class="max-w-5xl mx-auto px-4 mt-2 flex gap-2">
                <button id="tab-comments" class="px-4 py-2 rounded-t-lg font-medium bg-blue-100 text-blue-800 transition">Comments Moderation</button>
                <button id="tab-updates"  class="px-4 py-2 rounded-t-lg font-medium text-gray-600 hover:bg-gray-100 transition">Project Updates (Blog)</button>
            </div>
        </header>

        <!-- Content -->
        <main class="flex-1 max-w-5xl mx-auto w-full px-4 py-8">

            <!-- Comments -->
            <div id="section-comments">
                <div class="flex justify-between items-end mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">All Comments</h2>
                    <div class="text-sm text-gray-500">
                        <span class="inline-block w-3 h-3 bg-yellow-400 rounded-full mr-1"></span> Pending
                        <span class="inline-block w-3 h-3 bg-green-500 rounded-full ml-3 mr-1"></span> Approved
                    </div>
                </div>
                <div id="comments-container" class="space-y-4">
                    <div class="animate-pulse space-y-4"><div class="h-24 bg-gray-200 rounded"></div><div class="h-24 bg-gray-200 rounded"></div></div>
                </div>
            </div>

            <!-- Updates -->
            <div id="section-updates" class="hidden">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Create Post -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-md p-6 sticky top-32">
                            <h2 class="text-xl font-bold text-gray-800 mb-4">Post New Update</h2>
                            <form id="create-post-form" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                    <input type="text" id="post-title" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g. Field Construction Completed" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Image URL (Optional)</label>
                                    <input type="url" id="post-image" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="https://example.com/image.jpg">
                                    <p class="text-xs text-gray-400 mt-1">Paste a direct link to an image.</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                                    <textarea id="post-content" rows="6" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Write your update here..." required></textarea>
                                </div>
                                <button type="submit" id="create-post-btn" class="w-full bg-blue-700 text-white font-bold py-2 rounded-lg hover:bg-blue-800 transition shadow-md">Post Update</button>
                            </form>
                        </div>
                    </div>
                    <!-- Posts List -->
                    <div class="lg:col-span-2">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Existing Updates</h2>
                        <div id="posts-container" class="space-y-6">
                            <div class="animate-pulse space-y-6"><div class="h-40 bg-gray-200 rounded"></div><div class="h-40 bg-gray-200 rounded"></div></div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="js/admin.js"></script>
</body>
</html>
