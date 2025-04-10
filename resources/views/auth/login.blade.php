<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SPP</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-secondary">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow" style="width: 400px;">
            <div class="card-body">
                <h2 class="card-title text-center">LOGIN</h2>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login.process') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" name="username" class="form-control" required placeholder="Masukkan Username">
                    </div>

                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" name="password" class="form-control" required placeholder="Masukkan Password">
                    </div>

                    <button type="submit" class="btn btn-info btn-block">LOGIN</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
