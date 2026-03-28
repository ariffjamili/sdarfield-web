// --- DOM Elements ---
const loginSection     = document.getElementById('login-section');
const dashboardSection = document.getElementById('dashboard-section');
const loginForm        = document.getElementById('login-form');
const passwordInput    = document.getElementById('password');
const loginError       = document.getElementById('login-error');
const logoutBtn        = document.getElementById('logout-btn');
const tabComments      = document.getElementById('tab-comments');
const tabUpdates       = document.getElementById('tab-updates');
const sectionComments  = document.getElementById('section-comments');
const sectionUpdates   = document.getElementById('section-updates');
const commentsContainer = document.getElementById('comments-container');
const postsContainer   = document.getElementById('posts-container');
const createPostForm   = document.getElementById('create-post-form');
const postTitle        = document.getElementById('post-title');
const postContent      = document.getElementById('post-content');
const postImage        = document.getElementById('post-image');
const createPostBtn    = document.getElementById('create-post-btn');

// --- Helper ---

function escapeHTML(str) {
    if (!str) return '';
    return str.replace(/[&<>"']/g, m =>
        ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m])
    );
}

// --- Auth ---

function showDashboard() {
    loginSection.classList.add('hidden');
    dashboardSection.classList.remove('hidden');
    loadComments();
    loadPosts();
}

function showLogin() {
    loginSection.classList.remove('hidden');
    dashboardSection.classList.add('hidden');
}

// Check existing session on load
fetch('api/auth.php')
    .then(r => r.json())
    .then(data => { if (data.authenticated) showDashboard(); else showLogin(); });

loginForm.addEventListener('submit', async e => {
    e.preventDefault();
    loginError.classList.add('hidden');

    try {
        const res  = await fetch('api/auth.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ password: passwordInput.value })
        });
        const data = await res.json();

        if (res.ok && data.success) {
            showDashboard();
        } else {
            loginError.innerText = data.error || 'Login failed. Check your password.';
            loginError.classList.remove('hidden');
        }
    } catch {
        loginError.innerText = 'Login failed. Please try again.';
        loginError.classList.remove('hidden');
    }
});

logoutBtn.addEventListener('click', async () => {
    await fetch('api/auth.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'logout' })
    });
    showLogin();
});

// --- Tabs ---

function switchTab(tab) {
    const isComments = tab === 'comments';
    tabComments.classList.toggle('bg-blue-100',  isComments);
    tabComments.classList.toggle('text-blue-800', isComments);
    tabComments.classList.toggle('text-gray-600', !isComments);
    tabUpdates.classList.toggle('bg-blue-100',   !isComments);
    tabUpdates.classList.toggle('text-blue-800', !isComments);
    tabUpdates.classList.toggle('text-gray-600',  isComments);
    sectionComments.classList.toggle('hidden', !isComments);
    sectionUpdates.classList.toggle('hidden',   isComments);
}

tabComments.addEventListener('click', () => switchTab('comments'));
tabUpdates.addEventListener('click',  () => switchTab('updates'));

// --- Comments ---

async function loadComments() {
    try {
        const res      = await fetch('api/comments.php?all=1');
        const comments = await res.json();

        commentsContainer.innerHTML = '';

        if (!comments.length) {
            commentsContainer.innerHTML = '<p class="text-gray-500 text-center py-8">No comments found.</p>';
            return;
        }

        comments.forEach(renderComment);
    } catch {
        commentsContainer.innerHTML = '<p class="text-red-500 text-center py-8">Could not load comments.</p>';
    }
}

function renderComment(data) {
    const dateStr    = data.created_at ? new Date(data.created_at).toLocaleString() : 'N/A';
    const isApproved = data.is_approved;

    const div = document.createElement('div');
    div.className = `bg-white p-4 rounded-lg shadow border-l-4 ${isApproved ? 'border-green-500' : 'border-yellow-500'} mb-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 hover:shadow-md`;
    div.innerHTML = `
        <div class="flex-1">
            <div class="flex items-center gap-2 mb-1">
                <span class="font-bold text-gray-800">${escapeHTML(data.name)}</span>
                ${isApproved
                    ? '<span class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full font-semibold">Approved</span>'
                    : '<span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-0.5 rounded-full font-semibold">Pending Review</span>'}
            </div>
            <p class="text-gray-600 text-sm mb-2">${escapeHTML(data.comment)}</p>
            <p class="text-xs text-gray-400">${dateStr}</p>
        </div>
        <div class="flex items-center gap-2 w-full md:w-auto">
            ${!isApproved ? `<button onclick="approveComment(${data.id})" class="flex-1 md:flex-none bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700">Approve</button>` : ''}
            <button onclick="deleteComment(${data.id})" class="flex-1 md:flex-none bg-red-100 text-red-600 px-4 py-2 rounded text-sm hover:bg-red-200">Delete</button>
        </div>
    `;
    commentsContainer.appendChild(div);
}

async function approveComment(id) {
    const res = await fetch('api/comments.php', {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
    });
    if (res.ok) loadComments(); else alert('Error approving comment.');
}

async function deleteComment(id) {
    if (!confirm('Delete this comment permanently?')) return;
    const res = await fetch('api/comments.php', {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
    });
    if (res.ok) loadComments(); else alert('Error deleting comment.');
}

// --- Posts ---

async function loadPosts() {
    try {
        const res   = await fetch('api/posts.php');
        const posts = await res.json();

        postsContainer.innerHTML = '';

        if (!posts.length) {
            postsContainer.innerHTML = '<p class="text-gray-500 text-center py-8">No updates posted yet.</p>';
            return;
        }

        posts.forEach(renderPost);
    } catch {
        postsContainer.innerHTML = '<p class="text-red-500 text-center py-8">Could not load posts.</p>';
    }
}

function renderPost(data) {
    const dateStr = data.created_at ? new Date(data.created_at).toLocaleString() : 'N/A';
    const div     = document.createElement('div');
    div.className = 'bg-white p-6 rounded-lg shadow mb-6 border border-gray-200';
    div.innerHTML = `
        <div class="flex justify-between items-start">
            <div class="flex items-start">
                ${data.image_url ? `<img src="${escapeHTML(data.image_url)}" class="h-20 w-20 object-cover rounded mr-4 bg-gray-100" />` : ''}
                <div>
                    <h3 class="text-lg font-bold text-gray-900">${escapeHTML(data.title)}</h3>
                    <p class="text-xs text-gray-500 mb-2">Posted on: ${dateStr}</p>
                    <p class="text-sm text-gray-600 line-clamp-3">${escapeHTML(data.content)}</p>
                </div>
            </div>
            <button onclick="deletePost(${data.id})" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded ml-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>
    `;
    postsContainer.appendChild(div);
}

createPostForm.addEventListener('submit', async e => {
    e.preventDefault();
    createPostBtn.disabled  = true;
    createPostBtn.innerText = 'Posting...';

    try {
        const res  = await fetch('api/posts.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                title:    postTitle.value,
                content:  postContent.value,
                imageUrl: postImage.value
            })
        });
        const data = await res.json();

        if (res.ok && data.success) {
            createPostForm.reset();
            alert('Update posted successfully!');
            loadPosts();
        } else {
            alert('Error: ' + (data.error || 'Unknown error'));
        }
    } catch {
        alert('Error creating post. Please try again.');
    } finally {
        createPostBtn.disabled  = false;
        createPostBtn.innerText = 'Post Update';
    }
});

async function deletePost(id) {
    if (!confirm('Delete this update permanently?')) return;
    const res = await fetch('api/posts.php', {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
    });
    if (res.ok) loadPosts(); else alert('Error deleting post.');
}
