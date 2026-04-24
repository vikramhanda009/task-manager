<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Task Manager')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Simple CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
        }

        .container {
            width: 800px;
            margin: 40px auto;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        h1 {
            margin-bottom: 20px;
        }

        .btn {
            padding: 8px 14px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-primary { background: #4f46e5; color: white; }
        .btn-danger { background: #ef4444; color: white; }
        .btn-edit { background: #10b981; color: white; }

        .task {
            padding: 12px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            border-radius: 6px;
            background: #fafafa;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .task span {
            font-weight: bold;
        }

        select, input {
            padding: 8px;
            margin-right: 10px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
@stack('scripts')

</body>
</html>