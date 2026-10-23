document.addEventListener('DOMContentLoaded', () => {
  const $ = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => [...r.querySelectorAll(s)];
  const hour = new Date().getHours();
  const timeOfDay = hour < 12 ? 'Good morning' : hour < 18 ? 'Good afternoon' : 'Good evening';
  $('#greeting').textContent = timeOfDay + '! Thanks for visiting my portfolio.';
  const info = {
    'HTML': 'Page structure and semantic markup.',
    'CSS': 'Layout, themes and responsive design.',
    'JavaScript': 'Events, DOM updates and fetch requests.',
    'PHP': 'Server-side rendering, sessions and form handling.',
    'MySQL': 'Relational databases and queries.',
    'IP Addressing': 'Planning and assigning network addresses.',
    'DNS & DHCP': 'Name resolution and automatic address assignment.',
    'TCP/IP': 'How devices talk to each other on a network.',
    'Network Troubleshooting': 'Finding out why a connection is slow or down.',
    'Windows Administration': 'Managing users, settings and services on Windows.',
    'Linux (Ubuntu)': 'Using the command line and managing an Ubuntu system.',
    'PowerShell': 'Automating Windows tasks with scripts.',
    'PC Troubleshooting': 'Finding and fixing hardware and software problems.',
    'Git & GitHub': 'Version control and publishing code.',
    'VS Code': 'My code editor for web projects.',
    'XAMPP': 'Local server for testing PHP and MySQL.',
    'PC Building': 'Choosing parts and assembling desktop PCs.'
  };
  const note = $('#skillNote');
  const noteDefault = note.textContent;
  $$('.chip').forEach(chip => {
    const name = chip.dataset.name || chip.textContent.trim();
    const show = () => { note.textContent = info[name] ? name + ': ' + info[name] : name; };
    const reset = () => { note.textContent = noteDefault; };
    chip.addEventListener('mouseover', show);
    chip.addEventListener('focus', show);
    chip.addEventListener('mouseout', reset);
    chip.addEventListener('blur', reset);
  });
  const navLinks = $$('.pill-nav a');
  const spy = () => {
    let current = navLinks[0];
    navLinks.forEach(a => {
      const section = $(a.getAttribute('href'));
      if (section && section.getBoundingClientRect().top <= 140) current = a;
    });
    if (window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 2) current = navLinks[navLinks.length - 1];
    navLinks.forEach(a => a.classList.toggle('active', a === current));
  };
  window.addEventListener('scroll', spy, { passive: true });
  spy();
  const counters = $$('.stat b');
  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver(entries => entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      const el = entry.target;
      const end = Number(el.dataset.count);
      let n = 0;
      const timer = setInterval(() => {
        n++;
        el.textContent = String(n).padStart(2, '0') + '+';
        if (n >= end) clearInterval(timer);
      }, 120);
      io.unobserve(el);
    }), { threshold: 0.6 });
    counters.forEach(el => { el.textContent = '00+'; io.observe(el); });
  } else {
    counters.forEach(el => { el.textContent = String(el.dataset.count).padStart(2, '0') + '+'; });
  }
  const cards = $$('#grid .card');
  let activeFilter = 'all';
  const applyFilter = () => {
    const q = $('#search').value.trim().toLowerCase();
    let shown = 0;
    cards.forEach(card => {
      const matchesFilter = activeFilter === 'all' || card.dataset.cat === activeFilter;
      const matchesSearch = (card.dataset.title + ' ' + card.dataset.desc + ' ' + card.dataset.tech).toLowerCase().includes(q);
      card.hidden = !(matchesFilter && matchesSearch);
      if (!card.hidden) shown++;
    });
    $('#empty').hidden = shown > 0;
  };
  $$('.filter').forEach(btn => btn.addEventListener('click', () => {
    $$('.filter').forEach(x => x.classList.remove('active'));
    btn.classList.add('active');
    activeFilter = btn.dataset.filter;
    applyFilter();
  }));
  $('#search').addEventListener('input', applyFilter);
  const modal = $('#modal');
  let lastFocused = null;
  const openModal = card => {
    lastFocused = card;
    $('#mTitle').textContent = card.dataset.title;
    $('#mDesc').textContent = card.dataset.desc;
    $('#mTech').textContent = card.dataset.tech;
    $('#mLearned').textContent = card.dataset.learned || '';
    $('#mLearnedWrap').hidden = !card.dataset.learned;
    const link = $('#mLink');
    const url = card.dataset.link;
    link.hidden = !url;
    if (url) link.href = url;
    const shot = $('#mShot');
    shot.hidden = true;
    shot.onload = () => { shot.hidden = false; };
    shot.onerror = () => { shot.hidden = true; };
    shot.alt = 'Screenshot of ' + card.dataset.title;
    if (card.dataset.shot) shot.src = card.dataset.shot; else shot.removeAttribute('src');
    modal.hidden = false;
    $('#closeModal').focus();
  };
  const closeModal = () => {
    if (modal.hidden) return;
    modal.hidden = true;
    if (lastFocused) lastFocused.focus();
  };
  $('#grid').addEventListener('click', e => {
    if (e.target.closest('.admin-btn')) return;
    const card = e.target.closest('.card');
    if (card) openModal(card);
  });
  $('#grid').addEventListener('keydown', e => {
    if (e.target.closest('.admin-btn')) return;
    const card = e.target.closest('.card');
    if (card && (e.key === 'Enter' || e.key === ' ')) { e.preventDefault(); openModal(card); }
  });
  $('#closeModal').addEventListener('click', closeModal);
  modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });
  const loginModal = $('#loginModal');
  const loginBtn = $('#loginBtn');
  const logoutBtn = $('#logoutBtn');
  const loginForm = $('#loginForm');
  const loginPass = $('#loginPass');
  const togglePass = $('#togglePass');
  const closeLogin = () => {
    if (loginModal.hidden) return;
    loginModal.hidden = true;
    if (loginBtn) loginBtn.focus();
  };
  if (loginBtn) {
    loginBtn.addEventListener('click', () => {
      loginModal.hidden = false;
      $('#loginUser').focus();
    });
  }
  $('#closeLogin').addEventListener('click', closeLogin);
  loginModal.addEventListener('click', e => { if (e.target === loginModal) closeLogin(); });
  togglePass.addEventListener('click', () => {
    const hidden = loginPass.type === 'password';
    loginPass.type = hidden ? 'text' : 'password';
    togglePass.textContent = hidden ? 'Hide' : 'Show';
  });
  loginForm.addEventListener('submit', async e => {
    e.preventDefault();
    const status = $('#loginStatus');
    if (!$('#loginUser').value.trim() || !loginPass.value) {
      status.textContent = 'Please enter both username and password.';
      return;
    }
    status.textContent = 'Signing in...';
    try {
      const res = await fetch('auth.php', { method: 'POST', body: new FormData(loginForm) });
      const data = await res.json();
      status.textContent = data.message;
      if (data.ok) window.location.reload();
    } catch (err) {
      status.textContent = 'Could not reach the server. Run the site through PHP (see README).';
    }
  });
  if (logoutBtn) {
    logoutBtn.addEventListener('click', async () => {
      const fd = new FormData();
      fd.append('action', 'logout');
      try { await fetch('auth.php', { method: 'POST', body: fd }); } catch (err) {}
      window.location.reload();
    });
  }
  const inboxGrid = $('#inboxGrid');
  if (inboxGrid) {
    inboxGrid.addEventListener('click', async e => {
      const btn = e.target.closest('.delete-msg');
      if (!btn) return;
      const card = btn.closest('.card');
      if (!confirm('Delete this message? This cannot be undone.')) return;
      const status = $('#inboxStatus');
      const fd = new FormData();
      fd.append('id', card.dataset.id);
      btn.disabled = true;
      try {
        const res = await fetch('delete.php', { method: 'POST', body: fd });
        const data = await res.json();
        status.textContent = data.message;
        if (data.ok) {
          card.remove();
          if (!$$('#inboxGrid .card').length) window.location.reload();
        } else {
          btn.disabled = false;
        }
      } catch (err) {
        status.textContent = 'Could not reach the server.';
        btn.disabled = false;
      }
    });
  }
  const projectModal = $('#projectModal');
  const projectForm = $('#projectForm');
  const closeProject = () => { if (projectModal && !projectModal.hidden) projectModal.hidden = true; };
  const sendToServer = async fd => {
    try {
      const res = await fetch('save.php', { method: 'POST', body: fd });
      return await res.json();
    } catch (err) {
      return { ok: false, message: 'Could not reach the server.' };
    }
  };
  const makeData = obj => {
    const fd = new FormData();
    Object.entries(obj).forEach(([k, v]) => fd.append(k, v));
    return fd;
  };
  if (projectModal) {
    const openProjectForm = card => {
      projectForm.reset();
      $('#projectStatus').textContent = '';
      $('#pHead').textContent = card ? 'Edit project' : 'Add project';
      projectForm.elements['index'].value = card ? card.dataset.index : '';
      if (card) {
        ['title', 'cat', 'desc', 'learned', 'tech', 'img', 'link'].forEach(k => {
          projectForm.elements[k].value = card.dataset[k] || '';
        });
      }
      projectModal.hidden = false;
      projectForm.elements['title'].focus();
    };
    $('#addProject').addEventListener('click', () => openProjectForm(null));
    $('#closeProject').addEventListener('click', closeProject);
    projectModal.addEventListener('click', e => { if (e.target === projectModal) closeProject(); });
    projectForm.addEventListener('submit', async e => {
      e.preventDefault();
      const status = $('#projectStatus');
      status.textContent = 'Saving...';
      const data = await sendToServer(new FormData(projectForm));
      status.textContent = data.message;
      if (data.ok) window.location.reload();
    });
    $('#grid').addEventListener('click', async e => {
      const card = e.target.closest('.card');
      if (!card) return;
      if (e.target.closest('.edit-project')) {
        openProjectForm(card);
      } else if (e.target.closest('.delete-project')) {
        if (!confirm('Delete "' + card.dataset.title + '"? This cannot be undone.')) return;
        const data = await sendToServer(makeData({ action: 'project_delete', index: card.dataset.index }));
        if (data.ok) window.location.reload(); else alert(data.message);
      }
    });
  }
  $$('.skill-add').forEach(f => f.addEventListener('submit', async e => {
    e.preventDefault();
    const input = f.elements['name'];
    if (!input.value.trim()) return;
    const data = await sendToServer(makeData({ action: 'skill_add', group: f.dataset.group, name: input.value }));
    if (data.ok) window.location.reload(); else alert(data.message);
  }));
  $$('.chip-x').forEach(btn => btn.addEventListener('click', async () => {
    if (!confirm('Remove "' + btn.dataset.name + '" from your skills?')) return;
    const data = await sendToServer(makeData({ action: 'skill_remove', group: btn.dataset.group, name: btn.dataset.name }));
    if (data.ok) window.location.reload(); else alert(data.message);
  }));
  document.addEventListener('keydown', e => {
    const typing = /INPUT|TEXTAREA|SELECT/.test(document.activeElement.tagName);
    if (e.key === '/' && !typing) { e.preventDefault(); $('#search').focus(); }
    if (e.key === 'Escape') { closeModal(); closeLogin(); closeProject(); }
  });
  const form = $('#contactForm');
  const fields = $$('#contactForm input, #contactForm textarea');
  const rules = {
    name:    v => (v.trim().length >= 2 && v.trim().length <= 60) || 'Enter your name (2 to 60 characters).',
    email:   v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim()) || 'Enter a valid email like name@example.com.',
    message: v => (v.trim().length >= 10 && v.trim().length <= 500) || 'Write between 10 and 500 characters.'
  };
  const check = el => {
    const result = rules[el.name](el.value);
    $(`.err[data-for="${el.name}"]`).textContent = result === true ? '' : result;
    el.classList.toggle('invalid', result !== true);
    el.classList.toggle('valid', result === true);
    return result === true;
  };
  fields.forEach(el => {
    el.addEventListener('blur', () => check(el));
    el.addEventListener('input', () => { if (el.classList.contains('invalid')) check(el); });
  });
  $('#message').addEventListener('input', e => {
    $('#count').textContent = e.target.value.length + ' / 500';
  });
  form.addEventListener('submit', async e => {
    e.preventDefault();
    const status = $('#status');
    const allValid = fields.map(check).every(Boolean);
    if (!allValid) {
      status.textContent = 'Fix the highlighted fields and send again.';
      return;
    }
    status.textContent = 'Sending...';
    try {
      const res = await fetch('contact.php', { method: 'POST', body: new FormData(form) });
      const data = await res.json();
      status.textContent = data.message;
      if (data.ok) {
        form.reset();
        $('#count').textContent = '0 / 500';
        fields.forEach(f => f.classList.remove('valid', 'invalid'));
      }
    } catch (err) {
      status.textContent = 'Could not reach the server. Run the site through PHP (see README).';
    }
  });
});