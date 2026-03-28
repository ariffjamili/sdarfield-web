// --- DOM Elements ---
const commentsList      = document.getElementById('comments-list');
const commentForm       = document.getElementById('comment-form');
const commentName       = document.getElementById('comment-name');
const commentText       = document.getElementById('comment-text');
const commentSubmitBtn  = document.getElementById('comment-submit-button');
const commentSpinner    = document.getElementById('comment-spinner');
const messageModal      = document.getElementById('message-modal');
const messageModalText  = document.getElementById('message-modal-text');
const messageModalClose = document.getElementById('message-modal-close');
const mobileMenu        = document.getElementById('mobile-menu');
const menuButton        = document.getElementById('menu-button');

// --- Helpers ---

function escapeHTML(str) {
    if (!str) return '';
    return str.replace(/[&<>"']/g, m =>
        ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m])
    );
}

function formatDate(dateStr) {
    if (!dateStr) return '';
    return new Date(dateStr).toLocaleDateString('en-US', {
        year: 'numeric', month: 'long', day: 'numeric',
        hour: '2-digit', minute: '2-digit'
    });
}

function showModal(message) {
    messageModalText.innerText = message;
    messageModal.classList.remove('hidden');
    messageModal.classList.add('flex');
}

function hideModal() {
    messageModal.classList.add('hidden');
    messageModal.classList.remove('flex');
}

// --- Comments ---

async function loadComments() {
    try {
        const res      = await fetch('api/comments.php');
        const comments = await res.json();

        commentsList.innerHTML = '';

        if (!comments.length) {
            commentsList.innerHTML = '<p class="text-gray-500 text-center col-span-1">Be the first to leave a comment!</p>';
            return;
        }

        comments.forEach(c => {
            const div = document.createElement('div');
            div.className = 'bg-white p-5 rounded-lg shadow-sm border border-gray-200';
            div.innerHTML = `
                <div class="flex justify-between items-center mb-2">
                    <h4 class="font-semibold text-lg text-blue-800">${escapeHTML(c.name)}</h4>
                    <span class="text-xs text-gray-500">${formatDate(c.created_at)}</span>
                </div>
                <p class="text-gray-700">${escapeHTML(c.comment)}</p>
            `;
            commentsList.appendChild(div);
        });
    } catch (err) {
        console.error('Error loading comments:', err);
        commentsList.innerHTML = '<p class="text-red-500 text-center">Could not load comments at this time.</p>';
    }
}

async function handleCommentSubmit(e) {
    e.preventDefault();

    const name    = commentName.value.trim();
    const comment = commentText.value.trim();

    if (!name || !comment) {
        showModal('Please fill out both your name and comment.');
        return;
    }

    commentSubmitBtn.disabled = true;
    commentSpinner.classList.remove('hidden');

    try {
        const res  = await fetch('api/comments.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name, comment })
        });
        const data = await res.json();

        if (res.ok && data.success) {
            commentForm.reset();
            showModal('Your comment has been submitted for approval.');
        } else {
            showModal('Error: ' + (data.error || 'Could not submit comment. Please try again.'));
        }
    } catch (err) {
        showModal('Error: Could not submit comment. Please try again.');
    } finally {
        commentSubmitBtn.disabled = false;
        commentSpinner.classList.add('hidden');
    }
}

// --- Event Listeners ---

commentForm.addEventListener('submit', handleCommentSubmit);
messageModalClose.addEventListener('click', hideModal);

menuButton.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));

document.querySelectorAll('#mobile-menu a, #nav-links a').forEach(link => {
    link.addEventListener('click', () => mobileMenu.classList.add('hidden'));
});

// Load comments immediately (script is at end of body, DOM is ready)
loadComments();
