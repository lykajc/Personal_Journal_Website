<?php
require_once 'connect_db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Journal</title>

  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />

  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  
  <link rel="stylesheet" href="journalStyle.css">


</head>
<body>

<!--HEADER -->
<header class="journal-header">
  <div class="container text-center">
    <h1><i class="bi bi-journals fill me-2"></i>Ykay's Journal</h1>
    <p>Where noisy thoughts find their voice.</p>
  </div>
</header>

<!--  MAIN -->
<main class="container pb-5">
  <div class="row g-4">

  <!-- searchbar -->
      <div class="d-flex align-items-center gap-4 mb-2">
        <div class="input-group flex-grow-1">
          <span class="input-group-text bg-white border-end-0 rounded-start-pill border"
                style="border-color:var(--journal-border)">
            <i class="bi bi-search text-muted"></i>
          </span>
          <input type="search" class="form-control border-start-0 rounded-end-pill"
                 id="searchInput" placeholder="Search entries…"
                 style="border-color:var(--journal-border)" />
        </div>
      </div>

    <!--  Add Entry Form -->
    <div class="col-lg-4">
      <div class="card" >
        <div class="card-header py-3">
          <i class="bi bi-pencil-fill me-2"></i>New Entry
        </div>
        <div class="card-body p-4">
          <form id="addForm" novalidate>
            <div class="mb-3">
              <label class="form-label fw-semibold" for="addTitle">Title</label>
              <input type="text" class="form-control" id="addTitle"
                     placeholder="Give your entry a title…" required maxlength="255" />
              <div class="invalid-feedback">Please enter a title.</div>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold" for="addDate">Date</label>
              <input type="date" class="form-control" id="addDate" required />
              <div class="invalid-feedback">Please pick a date.</div>
            </div>
            <div class="mb-4">
              <label class="form-label fw-semibold" for="addContent">What's on your mind?</label>
              <textarea class="form-control" id="addContent"
                        placeholder="Write freely…" required></textarea>
              <div class="invalid-feedback">Please write something.</div>
            </div>
            <button type="submit" class="btn btn-primary w-100" id="addBtn">
              <i class="bi bi-plus-circle me-1"></i>Save Entry
            </button>
          </form>
        </div>
      </div>
    </div>

    <!--  RIGHT: Entry List  -->
    <div class="col-lg-8">

      <!-- Spinner -->
      <div id="loadingSpinner" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading…</span>
        </div>
      </div>

      <!-- Entry list -->
      <div id="entriesList"></div>

      <!-- Empty state -->
      <div id="emptyState" class="text-center py-5">
        <i class="bi bi-journal-x fs-1 text-muted d-block mb-3"></i>
        <p class="text-muted fs-5">No entries yet. Start writing!</p>
      </div>

    </div>
  </div>
</main>

<!--  VIEW MODAL  -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <div>
          <div class="view-modal-title" id="viewTitle"></div>
          <div class="view-modal-date">
            <i class="bi bi-calendar3"></i>
            <span id="viewDate"></span>
          </div>
        </div>
      </div>
      <div class="modal-body p-4">
        <div class="view-modal-content" id="viewContent"></div>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button class="btn btn-view-close" data-bs-dismiss="modal">
          <i class="bi bi-x me-1"></i>Close
        </button>
        <button class="btn btn-view-edit" id="viewEditBtn">
          <i class="bi bi-pencil me-1"></i>Edit This Entry
        </button>
      </div>
    </div>
  </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Entry</h5>
      </div>
      <div class="modal-body p-4">
        <form id="editForm" novalidate>
          <input type="hidden" id="editId" />
          <div class="mb-3">
            <label class="form-label fw-semibold" for="editTitle">Title</label>
            <input type="text" class="form-control" id="editTitle" required maxlength="255" />
            <div class="invalid-feedback">Please enter a title.</div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" for="editDate">Date</label>
            <input type="date" class="form-control" id="editDate" required />
            <div class="invalid-feedback">Please pick a date.</div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" for="editContent">Content</label>
            <textarea class="form-control" id="editContent" required></textarea>
            <div class="invalid-feedback">Please write something.</div>
          </div>
        </form>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" id="saveEditBtn">
          <i class="bi bi-check-circle me-1"></i>Update Entry
        </button>
      </div>
    </div>
  </div>
</div>

<!--  DELETE CONFIRM MODAL  -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white" style="border-radius:14px 14px 0 0">
        <h5 class="modal-title"><i class="bi bi-trash3 me-2"></i>Delete Entry</h5>
      </div>
      <div class="modal-body text-center py-4">
        <p class="mb-0">Are you sure you want to delete<br>
           <strong id="deleteEntryTitle" class="text-danger"></strong>?<br>
           <small class="text-muted">This cannot be undone.</small>
        </p>
      </div>
      <div class="modal-footer border-0 justify-content-center">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-outline-danger" id="confirmDeleteBtn">
          <i class="bi bi-trash3 me-1"></i>Delete
        </button>
      </div>
    </div>
  </div>
</div>

<!--  TOAST  -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
  <div id="appToast" class="toast align-items-center border-0 text-white"
       role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body fw-semibold" id="toastMsg"></div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto"
              data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

const API = 'operation.php';

// Bootstrap instances
const viewModal   = new bootstrap.Modal('#viewModal');
const editModal   = new bootstrap.Modal('#editModal');
const deleteModal = new bootstrap.Modal('#deleteModal');
const toastEl     = document.getElementById('appToast');
const bsToast     = new bootstrap.Toast(toastEl, { delay: 3000 });

let allEntries    = []; 
let pendingDelete = null;
let viewCurrentId = null; 

// ── Toast 
function showToast(msg, type = 'success') {
  toastEl.className = `toast align-items-center border-0 text-white bg-${type}`;
  document.getElementById('toastMsg').textContent = msg;
  bsToast.show();
}

// ── API 
async function api(action, body = null) {
  const opts = { method: body ? 'POST' : 'GET' };
  let url = `${API}?action=${action}`;
  if (body) { opts.body = body; }
  const res  = await fetch(url, opts);
  const json = await res.json();
  if (!json.success) throw new Error(json.message || 'Unknown error');
  return json.data;
}

// ── Format helpers 
function fmtDate(iso) {
  const [y, m, d] = iso.split('-');
  return new Date(y, m - 1, d).toLocaleDateString('en-US', {
    year: 'numeric', month: 'long', day: 'numeric'
  });
}
function excerpt(text, max = 160) {
  return text.length > max ? text.slice(0, max).trimEnd() + '…' : text;
}

// ── Render entries
function renderEntries(entries) {
  const list  = document.getElementById('entriesList');
  const empty = document.getElementById('emptyState');
  const count = document.getElementById('entryCount');

  if (count) count.textContent = entries.length;

  if (entries.length === 0) {
    list.innerHTML = '';
    empty.style.display = 'block';
    return;
  }
  empty.style.display = 'none';

  list.innerHTML = entries.map(e => `
    <div class="card entry-card" data-id="${e.id}">
      <div class="card-body p-4">
        <div class="entry-title" onclick="openView(${e.id})">${escHtml(e.title)}</div>
        <div class="entry-date">
          <i class="bi bi-calendar3"></i>${fmtDate(e.entry_date)}
        </div>
        <p class="entry-content mb-3">${escHtml(excerpt(e.content))}</p>
        <div class="d-flex entry-actions">
          <button class="btn btn-sm btn-view" onclick="openView(${e.id})">
            <i class="bi bi-eye me-1"></i>View
          </button>
          <button class="btn btn-sm btn-edit" onclick="openEdit(${e.id})">
            <i class="bi bi-pencil me-1"></i>Edit
          </button>
          <button class="btn btn-sm btn-delete" onclick="openDelete(${e.id}, '${escAttr(e.title)}')">
            <i class="bi bi-trash3 me-1"></i>Delete
          </button>
        </div>
      </div>
    </div>`).join('');
}

function escHtml(s) {
  return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
          .replace(/"/g,'&quot;').replace(/'/g,'&#39;');
}
function escAttr(s) { return escHtml(s); }

// ── Load all entries
async function loadEntries() {
  document.getElementById('loadingSpinner').style.display = 'block';
  document.getElementById('entriesList').innerHTML = '';
  document.getElementById('emptyState').style.display = 'none';
  try {
    allEntries = await api('list');
    filterEntries();
  } catch (err) {
    showToast('Failed to load entries: ' + err.message, 'danger');
  } finally {
    document.getElementById('loadingSpinner').style.display = 'none';
  }
}

// ── Search
function filterEntries() {
  const q = document.getElementById('searchInput').value.toLowerCase().trim();
  const filtered = q
    ? allEntries.filter(e =>
        e.title.toLowerCase().includes(q) ||
        e.content.toLowerCase().includes(q) ||
        e.entry_date.includes(q)
      )
    : allEntries;
  renderEntries(filtered);
}
document.getElementById('searchInput').addEventListener('input', filterEntries);

// ── ADD form 
document.getElementById('addDate').value = new Date().toISOString().slice(0, 10);

document.getElementById('addForm').addEventListener('submit', async e => {
  e.preventDefault();
  const form = e.target;
  if (!form.checkValidity()) { form.classList.add('was-validated'); return; }

  const btn = document.getElementById('addBtn');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving…';

  const fd = new FormData();
  fd.append('title',      document.getElementById('addTitle').value.trim());
  fd.append('content',    document.getElementById('addContent').value.trim());
  fd.append('entry_date', document.getElementById('addDate').value);

  try {
    await api('create', fd);
    form.reset();
    form.classList.remove('was-validated');
    document.getElementById('addDate').value = new Date().toISOString().slice(0, 10);
    showToast('Entry saved!', 'success');
    await loadEntries();
  } catch (err) {
    showToast(err.message, 'danger');
  } finally {
    btn.disabled = false;
    btn.innerHTML = '<i class="bi bi-plus-circle me-1"></i>Save Entry';
  }
});

// ── VIEW 
async function openView(id) {
  try {
    const entry = await api(`get&id=${id}`);
    viewCurrentId = entry.id;
    document.getElementById('viewTitle').textContent   = entry.title;
    document.getElementById('viewDate').textContent    = fmtDate(entry.entry_date);
    document.getElementById('viewContent').textContent = entry.content;
    viewModal.show();
  } catch (err) {
    showToast('Could not load entry: ' + err.message, 'danger');
  }
}

// Edit 
document.getElementById('viewEditBtn').addEventListener('click', () => {
  viewModal.hide();
 
  document.getElementById('viewModal').addEventListener('hidden.bs.modal', () => {
    openEdit(viewCurrentId);
  }, { once: true });
});

// ── EDIT
async function openEdit(id) {
  try {
    const entry = await api(`get&id=${id}`);
    document.getElementById('editId').value      = entry.id;
    document.getElementById('editTitle').value   = entry.title;
    document.getElementById('editDate').value    = entry.entry_date;
    document.getElementById('editContent').value = entry.content;
    document.getElementById('editForm').classList.remove('was-validated');
    editModal.show();
  } catch (err) {
    showToast('Could not load entry: ' + err.message, 'danger');
  }
}

document.getElementById('saveEditBtn').addEventListener('click', async () => {
  const form = document.getElementById('editForm');
  if (!form.checkValidity()) { form.classList.add('was-validated'); return; }

  const btn = document.getElementById('saveEditBtn');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving…';

  const fd = new FormData();
  fd.append('id',         document.getElementById('editId').value);
  fd.append('title',      document.getElementById('editTitle').value.trim());
  fd.append('content',    document.getElementById('editContent').value.trim());
  fd.append('entry_date', document.getElementById('editDate').value);

  try {
    await api('update', fd);
    editModal.hide();
    showToast('Entry updated!', 'success');
    await loadEntries();
  } catch (err) {
    showToast(err.message, 'danger');
  } finally {
    btn.disabled = false;
    btn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Update Entry';
  }
});

// ── DELETE 
function openDelete(id, title) {
  pendingDelete = id;
  document.getElementById('deleteEntryTitle').textContent = title;
  deleteModal.show();
}

document.getElementById('confirmDeleteBtn').addEventListener('click', async () => {
  if (!pendingDelete) return;
  const btn = document.getElementById('confirmDeleteBtn');
  btn.disabled = true;

  const fd = new FormData();
  fd.append('id', pendingDelete);

  try {
    await api('delete', fd);
    deleteModal.hide();
    showToast('Entry deleted.', 'secondary');
    await loadEntries();
  } catch (err) {
    showToast(err.message, 'danger');
  } finally {
    btn.disabled = false;
    pendingDelete = null;
  }
});

// ── Boot 
loadEntries();
</script>
</body>
</html>
