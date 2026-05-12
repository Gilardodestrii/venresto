const sidebar = document.getElementById('sidebar');
const toggle = document.getElementById('toggleSidebar');

if (toggle) {
    toggle.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
    });
}


function toggleSidebarMobile(){
    sidebar.classList.toggle('show');
}

/* ================= THEME ================= */
function toggleTheme(){
    const html = document.documentElement;
    const current = html.getAttribute('data-theme');

    const next = current === 'dark' ? 'light' : 'dark';
    html.setAttribute('data-theme', next);

    localStorage.setItem('theme', next);
}

/* AUTO LOAD THEME */
document.addEventListener('DOMContentLoaded', () => {
    const saved = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', saved);
});

