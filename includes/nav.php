<?php
// $prefix : '' for index.php, 'index.php' for other pages
// $activePage : 'index' | 'blog'
$prefix     = $prefix     ?? '';
$activePage = $activePage ?? 'index';
$base       = $prefix ? $prefix : '';
?>
<nav class="sticky top-0 z-50 bg-white/95 backdrop-blur-sm shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo & Title -->
            <a href="index.php" class="flex items-center space-x-4 hover:opacity-80 transition-opacity">
                <img src="images/sdara_logo.png" alt="SDARA Logo" class="h-10 w-10 rounded-full">
                <img src="images/PASL Logo.png" alt="Persatuan Alumni SDAR LIONS Logo" class="h-10 hidden sm:block">
                <span class="text-xl sm:text-2xl font-bold text-blue-900 hidden md:block">Field Rehabilitation<br>Project</span>
            </a>

            <!-- Desktop Nav -->
            <div id="nav-links" class="hidden md:flex items-center space-x-6">
                <a href="<?= $base ?>#overview"        class="text-gray-600 hover:text-blue-700 font-medium transition-colors">Overview</a>
                <a href="<?= $base ?>#timeline"        class="text-gray-600 hover:text-blue-700 font-medium transition-colors">Timeline</a>
                <a href="<?= $base ?>#financials"      class="text-gray-600 hover:text-blue-700 font-medium transition-colors">Financials</a>
                <a href="<?= $base ?>#implementation"  class="text-gray-600 hover:text-blue-700 font-medium transition-colors">Implementation</a>
                <a href="<?= $base ?>#gallery"         class="text-gray-600 hover:text-blue-700 font-medium transition-colors">Gallery</a>
                <a href="blog.php" class="<?= $activePage === 'blog' ? 'text-blue-700 font-bold border-b-2 border-blue-700' : 'text-gray-600 hover:text-blue-700 font-medium transition-colors' ?>">Updates</a>
                <a href="<?= $base ?>#comments"        class="text-gray-600 hover:text-blue-700 font-medium transition-colors">Comments</a>
                <a href="<?= $base ?>#conclusion"      class="bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-semibold shadow-lg hover:bg-blue-800 transition-colors">Conclusion</a>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button id="menu-button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                    <span class="sr-only">Open main menu</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white shadow-lg border-t border-gray-200">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="<?= $base ?>#overview"       class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Overview</a>
            <a href="<?= $base ?>#timeline"       class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Timeline</a>
            <a href="<?= $base ?>#financials"     class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Financials</a>
            <a href="<?= $base ?>#implementation" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Implementation</a>
            <a href="<?= $base ?>#gallery"        class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Gallery</a>
            <a href="blog.php"                    class="block px-3 py-2 rounded-md text-base font-medium <?= $activePage === 'blog' ? 'text-blue-700 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' ?>">Updates</a>
            <a href="<?= $base ?>#comments"       class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Comments</a>
            <a href="<?= $base ?>#conclusion"     class="block px-3 py-2 rounded-md text-base font-medium text-white bg-blue-700 hover:bg-blue-800">Conclusion</a>
        </div>
    </div>
</nav>
