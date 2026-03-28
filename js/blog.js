const postsContainer = document.getElementById('posts-container');
const loader         = document.getElementById('loader');
const menuButton     = document.getElementById('menu-button');
const mobileMenu     = document.getElementById('mobile-menu');

function escapeHTML(str) {
    if (!str) return '';
    return str.replace(/[&<>"']/g, m =>
        ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m])
    );
}

function formatContent(content) {
    if (!content) return '';
    return escapeHTML(content).replace(/\n/g, '<br>');
}

async function loadPosts() {
    try {
        const res   = await fetch('api/posts.php');
        const posts = await res.json();
        renderPosts(posts);
    } catch {
        postsContainer.innerHTML = '<p class="text-center text-red-500 py-10">Failed to load updates. Please try again later.</p>';
    } finally {
        loader.classList.add('hidden');
    }
}

function renderPosts(posts) {
    if (!posts.length) {
        postsContainer.innerHTML = '<p class="text-center text-gray-500 py-10">No updates posted yet.</p>';
        return;
    }

    postsContainer.innerHTML = '';
    posts.forEach(post => {
        const dateStr = post.created_at
            ? new Date(post.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })
            : 'Date Unavailable';

        const article   = document.createElement('article');
        article.className = 'flex flex-col bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-xl transition-shadow duration-300';

        const imageHtml = post.image_url
            ? `<div class="h-64 w-full bg-gray-100 overflow-hidden relative group">
                <img src="${escapeHTML(post.image_url)}" alt="${escapeHTML(post.title)}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
               </div>`
            : `<div class="h-48 w-full bg-gradient-to-r from-blue-50 to-blue-100 flex items-center justify-center">
                <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
               </div>`;

        article.innerHTML = `
            ${imageHtml}
            <div class="p-6 md:p-8 flex flex-col flex-grow">
                <div class="mb-4">
                    <span class="text-sm font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">${dateStr}</span>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4 hover:text-blue-700 transition-colors">${escapeHTML(post.title)}</h2>
                <div class="prose prose-blue max-w-none text-gray-600 mb-6 flex-grow">${formatContent(post.content)}</div>
            </div>
        `;
        postsContainer.appendChild(article);
    });
}

menuButton.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));

loadPosts();
