@extends('layouts.main')

@section('content')

<div class="container vh-100 d-flex justify-content-center align-items-center">
  <div class="sticky-note">

    <h3>Login</h3>

    <form action="/login" method="POST">
      @csrf
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="d-flex justify-content-between pb-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox">
          <label class="form-check-label small-link">Remember this account</label>
        </div>
        <a href="#" class="small-link">Forgot password?</a>
      </div>
      <div class="d-grid pb-2">
        <button type="submit" class="btn btn-custom btn-lg">Login</button>
      </div>
      <div class="text-center">
        <small>
          Don't have an account?
          <a href="/register" class="small-link fw-bold">Sign up</a>
        </small>
      </div>
    </form>

    <img src="{{ asset('ASSETS/frog.png') }}" class="frog" alt="frog">

  </div>
</div>

<div style="position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:10px;">
  @if(session('error'))
  <div class="toast align-items-center border-0 show" role="alert"
       style="background:#ffe5e5;color:#c94b4b;border-radius:10px;min-width:260px;">
    <div class="d-flex">
      <div class="toast-body fw-semibold">{{ session('error') }}</div>
      <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
  @endif
  @if(session('success'))
  <div class="toast align-items-center border-0 show" role="alert"
       style="background:#d4edda;color:#276138;border-radius:10px;min-width:260px;">
    <div class="d-flex">
      <div class="toast-body fw-semibold">{{ session('success') }}</div>
      <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
  @endif
</div>

@endsection

@push('styles')
<style>
body { background: #ffffff; font-family: 'Segoe UI', sans-serif; }
.sticky-note {
  position: relative;
  background: #BEE5B0;
  padding: 40px;
  width: 100%;
  max-width: 420px;
  border-radius: 15px;
  box-shadow: 8px 8px 0 #497151;
}
.sticky-note::before {
  content: "";
  position: absolute;
  top: 0; left: 0;
  border-width: 0 0 40px 40px;
  border-style: solid;
  border-color: transparent transparent #497151 transparent;
  border-radius: 0 0 10px 0;
}
.sticky-note::after {
  content: "";
  position: absolute;
  top: -15px; left: 50%;
  transform: translateX(-50%);
  width: 80px; height: 25px;
  background: #f5e6c8;
  opacity: 0.8;
  border-radius: 5px;
}
h3 { text-align: center; color: #3c7a4a; margin-bottom: 25px; }
.form-control {
  background: #f4fff6;
  border: 1px solid #b6dcb9;
  border-radius: 10px;
}
.form-control:focus { border-color: #7acb8a; box-shadow: none; }
.btn-custom {
  background: #8ed39c !important;
  border: none !important;
  border-radius: 25px !important;
  color: black !important;
  font-weight: 600 !important;
}
.btn-custom:hover { background: #76c787 !important; color: black !important; }
.frog { position: absolute; bottom: -10px; right: -10px; width: 90px; }
.small-link { font-size: 12px; color: #4c8c5a; }
.small-link:hover { text-decoration: underline; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.toast.show').forEach(function (el) {
        setTimeout(function () {
            bootstrap.Toast.getOrCreateInstance(el).hide();
        }, 3000);
    });
});
</script>
@endpush
