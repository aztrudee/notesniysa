@extends('layouts.main')

@section('content')

<nav id="sidebar" class="sidebar d-flex flex-column">
    <div>
        <div class="sidebar-brand">
            <img src="{{ asset('ASSETS/frog.png') }}" style="width:32px;height:32px;" alt="Logo">
            <span>Notes Manager</span>
        </div>
        <div class="mt-3">
            <a href="/dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="/user"><i class="bi bi-person"></i> User</a>
            <a href="/notes"><i class="bi bi-journal-text"></i> My Notes</a>
        </div>
    </div>
    <div class="mt-auto mb-3">
        <a href="/logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</nav>

<div class="main">
    <header class="topbar">
        <div class="d-flex align-items-center">
            <div class="toggle-btn" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </div>
            <nav class="breadcrumb-nav ms-3">
                <a href="#">Portal</a>
                <span class="sep">›</span>
                <span class="text-dark fw-semibold">My Notes</span>
            </nav>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="text-end">
                <div class="small fw-semibold">{{ $user->name }}</div>
                <div class="small text-muted">{{ $user->email }}</div>
            </div>
            <a href="/profile" class="d-flex align-items-center text-decoration-none">
                @if(auth()->user()->profile_picture_base64)
                    <img src="{{ auth()->user()->profile_picture_base64 }}" alt="Profile"
                         style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                @else
                    <img src="{{ asset('ASSETS/blank-pfp.png') }}" alt="Profile"
                         style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                @endif
            </a>
        </div>
    </header>

    <main class="main-wrapper">
        <div class="toast-container">
            <div id="toastAdd" class="toast align-items-center text-bg-success border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">✅ Note added successfully!</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
            <div id="toastEdit" class="toast align-items-center text-bg-primary border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">✏️ Note updated successfully!</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
            <div id="toastDelete" class="toast align-items-center text-bg-danger border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">🗑️ Note deleted successfully!</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>

        <div class="sticky-note">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0 fw-bold">My Notes</h4>
                <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="bi bi-plus-lg me-1"></i> Add Note
                </button>
            </div>
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th><th>User</th><th>Title</th>
                                <th>Content</th><th>Date Created</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($notes->count())
                                @foreach($notes as $note)
                                <tr>
                                    <td>{{ $note->id }}</td>
                                    <td class="fw-semibold">{{ $note->user->name }}</td>
                                    <td>{{ $note->title }}</td>
                                    <td>{{ substr($note->content, 0, 50) }}{{ strlen($note->content) > 50 ? '...' : '' }}</td>
                                    <td>{{ $note->created_at->format('Y-m-d') }}</td>
                                    <td class="text-end">
                                        <button class="btn btn-sm rounded-pill btn-secondary px-3"
                                            onclick="openViewModal('{{ addslashes($note->title) }}','{{ addslashes($note->content) }}','{{ $note->user->name }}')">
                                            <i class="bi bi-eye-fill"></i>
                                        </button>
                                        @if($note->user_id === $user->id)
                                        <button class="btn btn-sm rounded-pill btn-info px-3"
                                            onclick="openEditModal({{ $note->id }},'{{ addslashes($note->title) }}','{{ addslashes($note->content) }}')">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <button class="btn btn-sm rounded-pill btn-danger px-3"
                                            onclick="openDeleteModal({{ $note->id }})">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr><td colspan="6" class="text-center text-muted py-4">No notes yet. Create one to get started!</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- ADD MODAL -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content custom-modal">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold">Add Note</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body pt-0">
        <div class="mb-3">
          <label class="form-label fw-semibold">Title</label>
          <input id="addTitle" type="text" class="form-control custom-input" placeholder="Enter note title">
        </div>
        <div>
          <label class="form-label fw-semibold">Content</label>
          <textarea id="addContent" class="form-control custom-input" rows="4" placeholder="Enter note content"></textarea>
        </div>
      </div>
      <div class="modal-footer border-0">
        <button class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success rounded-pill px-4" onclick="saveNote()">Save</button>
      </div>
    </div>
  </div>
</div>

<!-- VIEW MODAL -->
<div class="modal fade" id="viewModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content custom-modal">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold" id="viewTitle">View Note</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body pt-0">
        <div class="mb-3"><small class="text-muted">By <span id="viewCreator" class="fw-semibold"></span></small></div>
        <p id="viewContent" class="mb-0"></p>
      </div>
    </div>
  </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content custom-modal">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold">Edit Note</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body pt-0">
        <input type="hidden" id="editId">
        <div class="mb-3">
          <label class="form-label fw-semibold">Title</label>
          <input type="text" id="editTitle" class="form-control custom-input">
        </div>
        <div>
          <label class="form-label fw-semibold">Content</label>
          <textarea id="editContent" class="form-control custom-input" rows="4"></textarea>
        </div>
      </div>
      <div class="modal-footer border-0">
        <button class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary rounded-pill px-4" onclick="saveEditNote()">Save Changes</button>
      </div>
    </div>
  </div>
</div>

<!-- DELETE MODAL -->
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content custom-modal">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold text-danger">Delete Note</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center pt-0">
        <p class="mb-1 fw-semibold">Are you sure you want to delete this note?</p>
        <small class="text-muted">This action cannot be undone.</small>
        <input type="hidden" id="deleteId">
      </div>
      <div class="modal-footer border-0 justify-content-center">
        <button class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger rounded-pill px-4" onclick="deleteNote()">Delete</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
function toggleSidebar() { document.getElementById('sidebar').classList.toggle('show'); }

function openViewModal(title, content, creator) {
    document.getElementById('viewTitle').textContent = title;
    document.getElementById('viewContent').textContent = content;
    document.getElementById('viewCreator').textContent = creator;
    new bootstrap.Modal(document.getElementById('viewModal')).show();
}
function openEditModal(id, title, content) {
    document.getElementById('editId').value = id;
    document.getElementById('editTitle').value = title;
    document.getElementById('editContent').value = content;
    new bootstrap.Modal(document.getElementById('editModal')).show();
}
function openDeleteModal(id) {
    document.getElementById('deleteId').value = id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function saveNote() {
    const title = document.getElementById('addTitle').value.trim();
    const content = document.getElementById('addContent').value.trim();
    if (!title || !content) { alert('Please fill in all fields'); return; }
    fetch('/notes/store', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ title, content })
    }).then(r => r.json()).then(data => {
        if (!data.success) { alert('Error: ' + data.message); return; }
        bootstrap.Modal.getInstance(document.getElementById('addModal'))?.hide();
        document.getElementById('addTitle').value = '';
        document.getElementById('addContent').value = '';
        showToast('add');
        setTimeout(() => location.reload(), 1000);
    });
}

function saveEditNote() {
    const id = document.getElementById('editId').value;
    const title = document.getElementById('editTitle').value.trim();
    const content = document.getElementById('editContent').value.trim();
    if (!title || !content) { alert('Please fill in all fields'); return; }
    fetch(`/notes/${id}/update`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ title, content })
    }).then(r => r.json()).then(data => {
        if (!data.success) { alert('Error: ' + data.message); return; }
        bootstrap.Modal.getInstance(document.getElementById('editModal'))?.hide();
        showToast('edit');
        setTimeout(() => location.reload(), 1000);
    });
}

function deleteNote() {
    const id = document.getElementById('deleteId').value;
    if (!confirm('Are you sure?')) return;
    fetch(`/notes/${id}/delete`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    }).then(r => r.json()).then(data => {
        if (!data.success) { alert('Error: ' + data.message); return; }
        bootstrap.Modal.getInstance(document.getElementById('deleteModal'))?.hide();
        showToast('delete');
        setTimeout(() => location.reload(), 1000);
    });
}

function showToast(type) {
    new bootstrap.Toast(document.getElementById('toast' + type.charAt(0).toUpperCase() + type.slice(1))).show();
}
</script>
@endpush
