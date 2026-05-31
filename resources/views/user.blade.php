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
                <span class="text-dark fw-semibold">User Management</span>
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
        <div class="sticky-note">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0 fw-bold">User Management</h4>
                <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="bi bi-plus-lg me-1"></i> Add User
                </button>
            </div>
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th><th>Name</th><th>Email</th>
                                <th>Gender</th><th>Joined Date</th><th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->gender }}</td>
                                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info rounded-pill px-3"
                                        onclick="openEditModal({{ $user->id }},'{{ $user->name }}','{{ $user->email }}','{{ strtolower($user->gender) }}')">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger rounded-pill px-3"
                                        onclick="openDeleteModal({{ $user->id }})">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content custom-modal">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold">Add User</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="addForm" method="POST" action="/user/store">
        @csrf
        <div class="modal-body pt-0">
          <div class="mb-3"><label>Full Name</label><input type="text" name="name" class="form-control custom-input" required></div>
          <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control custom-input" required></div>
          <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control custom-input" required minlength="6"></div>
          <div class="mb-3"><label>Confirm Password</label><input type="password" name="password_confirmation" class="form-control custom-input" required></div>
          <div class="mb-3">
            <label class="d-block">Gender</label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="gender" value="Male" required>
              <label class="form-check-label">Male</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="gender" value="Female" required>
              <label class="form-check-label">Female</label>
            </div>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal" onclick="document.getElementById('addForm').reset()">Cancel</button>
          <button type="button" class="btn btn-success rounded-pill px-4" onclick="submitAddForm()">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content custom-modal">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold">Edit User</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="editForm" method="POST">
        @csrf
        <div class="modal-body pt-0">
          <div class="mb-3"><label class="form-label fw-semibold">Full Name</label><input id="editName" type="text" class="form-control custom-input" name="name" required></div>
          <div class="mb-3"><label class="form-label fw-semibold">Email</label><input id="editEmail" type="email" class="form-control custom-input" name="email" required></div>
          <div><label class="form-label fw-semibold">Gender</label>
            <select id="editGender" class="form-control custom-input" name="gender" required>
              <option value="male">Male</option>
              <option value="female">Female</option>
            </select>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary rounded-pill px-4" onclick="document.getElementById('editForm').submit()">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content custom-modal">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold text-danger">Delete User</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="deleteForm" method="POST">
        @csrf @method('DELETE')
        <div class="modal-body text-center pt-0">
          <p class="mb-1 fw-semibold">Are you sure you want to delete this user?</p>
          <small class="text-muted">This action cannot be undone.</small>
        </div>
        <div class="modal-footer border-0 justify-content-center">
          <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger rounded-pill px-4" onclick="document.getElementById('deleteForm').submit()">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

<!-- TOAST CONTAINER -->
<div style="position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:10px;max-width:90vw;">
    @if(session('toast_success'))
    <div id="toastMain" class="toast align-items-center border-0 show"
         style="background:#d4edda;color:#276138;border-radius:10px;min-width:260px;">
        <div class="d-flex">
            <div class="toast-body fw-semibold">{{ session('toast_success') }}</div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    @endif

    @if(session('toast_error'))
    <div id="toastError" class="toast align-items-center border-0 show"
         style="background:#ffe5e5;color:#c94b4b;border-radius:10px;min-width:260px;">
        <div class="d-flex">
            <div class="toast-body fw-semibold">{{ session('toast_error') }}</div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
function toggleSidebar() { document.getElementById('sidebar').classList.toggle('show'); }

function openEditModal(id, name, email, gender) {
    document.getElementById('editForm').action = '/user/' + id + '/edit';
    document.getElementById('editName').value = name;
    document.getElementById('editEmail').value = email;
    document.getElementById('editGender').value = gender;
    new bootstrap.Modal(document.getElementById('editUserModal')).show();
}

function openDeleteModal(id) {
    document.getElementById('deleteForm').action = '/user/' + id + '/delete';
    new bootstrap.Modal(document.getElementById('deleteUserModal')).show();
}

function submitAddForm() {
    const name = document.querySelector('#addForm input[name="name"]').value.trim();
    const email = document.querySelector('#addForm input[name="email"]').value.trim();
    const gender = document.querySelector('#addForm input[name="gender"]:checked')?.value || '';
    const password = document.querySelector('#addForm input[name="password"]').value;
    const confirm = document.querySelector('#addForm input[name="password_confirmation"]').value;
    if (!name) { alert('Please enter full name'); return; }
    if (!email || !email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) { alert('Please enter a valid email'); return; }
    if (!gender) { alert('Please select a gender'); return; }
    if (password.length < 6) { alert('Password must be at least 6 characters'); return; }
    if (password !== confirm) { alert('Passwords do not match'); return; }
    document.getElementById('addForm').submit();
}

// Auto-dismiss all visible toasts after 3 seconds
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.toast.show').forEach(function (el) {
        setTimeout(function () {
            bootstrap.Toast.getOrCreateInstance(el).hide();
        }, 3000);
    });
});
</script>
@endpush

@push('styles')
<style>
/* RESPONSIVE FOR USER PAGE */
@media (max-width: 768px) {
  .sticky-note { padding: 1.5rem; box-shadow: 7px 5px 0 #497151; }
  .sticky-note h4 { font-size: 1.25rem; }
  .d-flex.justify-content-between { flex-wrap: wrap; gap: 1rem; }
  .btn { font-size: 0.9rem; padding: 0.5rem 1rem; }
  .table { font-size: 0.9rem; }
  .table thead { background: #c8eccc; }
  .table th, .table td { padding: 0.75rem 0.5rem; }
  .table-responsive { border: 0; }
}

@media (max-width: 576px) {
  .sticky-note { padding: 1rem; border-radius: 12px; box-shadow: 5px 4px 0 #497151; }
  .sticky-note h4 { font-size: 1rem; }
  .btn { font-size: 0.8rem; padding: 0.4rem 0.75rem; }
  .btn i { margin-right: 0.25rem; }
  .table { font-size: 0.75rem; min-width: 650px; }
  .table th, .table td { padding: 0.5rem 0.25rem; white-space: nowrap; }
  .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
  .d-flex.justify-content-between { display: flex !important; justify-content: space-between; flex-wrap: wrap; }
  .text-center { text-align: center; }
}
</style>
@endpush
