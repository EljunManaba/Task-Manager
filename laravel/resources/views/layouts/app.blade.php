<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Personal Task Manager')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border: 1px solid #ccc;
        }

        h1 {
            font-size: 22px;
            margin-bottom: 5px;
        }

        h2 {
            font-size: 18px;
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
            font-size: 14px;
        }

        th {
            background-color: #e9e9e9;
        }

        .status-pending {
            color: #b8860b;
            font-weight: bold;
        }

        .status-completed {
            color: green;
            font-weight: bold;
        }

        a {
            color: #0056b3;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .btn {
            padding: 5px 10px;
            border: 1px solid #999;
            background-color: #eee;
            cursor: pointer;
            font-size: 13px;
        }

        .btn:hover {
            background-color: #ddd;
        }

        .btn-add {
            display: inline-block;
            padding: 8px 14px;
            background-color: #333;
            color: #fff;
            border: none;
        }

        .btn-add:hover {
            background-color: #555;
            text-decoration: none;
        }

        .btn-delete {
            background-color: #f5c6c6;
        }

        label {
            display: block;
            margin-top: 10px;
            margin-bottom: 3px;
            font-size: 14px;
            font-weight: bold;
        }

        input[type=text], input[type=date], textarea, select {
            width: 100%;
            padding: 6px;
            border: 1px solid #999;
            font-size: 14px;
        }

        .error {
            color: red;
            font-size: 12px;
        }

        .success {
            background-color: #d4edda;
            border: 1px solid #a3d9a5;
            padding: 8px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .filter-links a {
            margin-right: 10px;
        }

        form.inline {
            display: inline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Personal Task Manager</h1>
        <p>WST21-PM-2026-SF</p>
        <hr>
        @yield('content')
    </div>
</body>
</html>