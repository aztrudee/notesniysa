<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Notes Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body {
            background: #f4f6f9;
            overflow-x: hidden;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            height: 100%;
            width: 260px;
            background: #BEE5B0;
            transition: 0.3s ease;
            z-index: 1000;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .sidebar-brand {
            display: flex;
            align-items: center;
            padding: 20px 25px;
            color: black;
            font-size: 1.1rem;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 15px;
            height: 70px;
        }
        .sidebar-brand img { margin-right: 12px; }
        .sidebar a {
            display: block;
            padding: 12px 25px;
            font-weight: 500;
            color: black;
            text-decoration: none;
            transition: 0.2s;
            font-size: 0.95rem;
        }
        .sidebar a i { margin-right: 10px; }
        .sidebar a:hover { background: #9fb997e5; color: black; }
        .sidebar { overflow-y: auto; }
        .sidebar a {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* TOPBAR */
        .topbar {
            height: 60px;
            background: #ffffff;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            position: fixed;
            top: 0; left: 260px; right: 0;
            z-index: 1001;
            transition: 0.3s ease;
        }
        .breadcrumb-nav {
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 0;
            overflow: hidden;
        }
        .breadcrumb-nav a,
        .breadcrumb-nav span {
            color: #6c757d;
            text-decoration: none;
            white-space: nowrap;
        }
        .breadcrumb-nav .sep { color: #ccc; }
        .toggle-btn {
            font-size: 24px;
            cursor: pointer;
            display: none;
            margin-right: 15px;
            color: #497151;
            transition: 0.3s;
        }

        /* MAIN LAYOUT */
        .main {
            margin-left: 260px;
            min-height: 100vh;
            transition: 0.3s ease;
        }
        .content-wrapper,
        .main-wrapper {
            padding: 80px 25px 25px 25px;
        }

        /* CARDS */
        .card-box {
            border: none;
            border-radius: 12px;
            color: black;
            box-shadow: 10px 8px 0 #497151;
        }
        .bg-users, .bg-notes { background: #BEE5B0; }

        /* CHART */
        .chart-container { position: relative; height: 300px; width: 100%; }

        /* STICKY NOTE */
        .sticky-note {
            position: relative;
            background: #BEE5B0;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 10px 8px 0 #497151;
        }
        .sticky-note::before {
            content: "";
            position: absolute;
            top: 0; left: 0;
            border-width: 0 0 35px 35px;
            border-style: solid;
            border-color: transparent transparent #497151 transparent;
        }
        .sticky-note::after {
            content: "";
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            width: 70px; height: 20px;
            background: #f7c665ac;
            border-radius: 5px;
        }

        /* TABLE */
        .table { border-radius: 10px; overflow: hidden; }
        .table thead { background: #c8eccc; }
        .table th,
        .table td {
            white-space: normal;
            word-break: break-word;
        }

        /* BUTTONS */
        .btn-primary { background: #497151 !important; border: none !important; font-weight: 600; }
        .btn-info    { background: #497151 !important; border: none !important; color: white !important; }
        .btn-primary:hover, .btn-info:hover { background: #76c787 !important; }

        /* MODALS */
        .custom-modal {
            background: #dff5e1;
            border-radius: 16px;
            box-shadow: 10px 8px 0 #497151;
            padding: 10px;
        }

        /* INPUTS */
        .custom-input {
            border-radius: 12px;
            border: 1px solid #a9c7b1;
            padding: 10px;
            transition: 0.2s;
        }
        .custom-input:focus {
            border-color: #497151;
            box-shadow: 0 0 0 2px rgba(73,113,81,0.2);
        }

        /* TOAST */
        .toast-container { position: fixed; top: 70px; right: 20px; z-index: 1055; }

        /* RESPONSIVE <= 992px */
        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); z-index: 1050; }
            .sidebar.show { transform: translateX(0); }
            .topbar { left: 0; right: 0; padding: 0 15px; }
            .topbar .breadcrumb-nav { width: 100%; }
            .topbar .text-end { min-width: 0; }
            .main { margin-left: 0; }
            .toggle-btn { display: block; }
            .chart-container { height: 260px; }
            .sticky-note { box-shadow: 8px 6px 0 #497151; }
            .sticky-note::before { border-width: 0 0 30px 30px; }
            .sticky-note::after { width: 60px; height: 18px; top: -10px; }
        }

        /* RESPONSIVE <= 768px */
        @media (max-width: 768px) {
            .topbar { height: auto; flex-wrap: wrap; gap: 8px; padding: 10px 15px; justify-content: space-between; }
            .topbar .d-flex:first-child { width: auto; }
            .topbar .breadcrumb-nav { display: none !important; }
            .topbar .text-end { display: none !important; }
            .topbar .d-flex:last-child { width: auto; }
            .content-wrapper, .main-wrapper { padding: 90px 15px 20px 15px; }
            .sticky-note { padding: 20px; box-shadow: 7px 5px 0 #497151; }
            .sticky-note::before { border-width: 0 0 25px 25px; }
            .sticky-note::after { width: 55px; height: 17px; top: -9px; }
            .table-container { overflow-x: auto; }
        }

        /* RESPONSIVE <= 576px */
        @media (max-width: 576px) {
            .content-wrapper, .main-wrapper { padding: 90px 10px 15px 10px; }
            .sidebar-brand { padding: 15px 18px; }
            .sidebar a { padding: 10px 18px; font-size: 0.9rem; }
            .sticky-note { padding: 15px; border-radius: 12px; box-shadow: 5px 4px 0 #497151; }
            .sticky-note::before { border-width: 0 0 20px 20px; }
            .sticky-note::after { width: 50px; height: 15px; top: -8px; }
            .table { font-size: 0.85rem; }
            .btn { font-size: 0.85rem; padding: 0.35rem 0.75rem; }
            .table-responsive { border: 0; }
            .w-100-mobile { width: 100% !important; }
        }
    </style>
    @stack('styles')
</head>
<body>

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')

</body>
</html>
