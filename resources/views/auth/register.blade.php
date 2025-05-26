<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #121212;
        }

        .register-card {
            background-color: #1f1f1f;
            border: none;
            color: #ffffff;
            border-radius: 10px;
            /* box-shadow: 0 0 15px rgba(255, 0, 0, 0.2); */
            padding: 2rem;
            width: 100%;
            max-width: 500px;
        }

        .form-control {
            background-color: #2c2c2c;
            border: 1px solid #555;
            color: #fff;
        }

        .form-control:focus {
            border-color: #ff0000;
            box-shadow: none;
        }

        .btn-primary {
            background-color: #ff0000;
            border: none;
        }

        .btn-primary:hover {
            background-color: #cc0000;
        }

        .invalid-feedback {
            display: block;
            color: #ff0000;
        }

        a {
            color: #ff0000;
            text-decoration: none;
        }

        a:hover {
            color: #ff0000;
        }

        .text-center h3 {
            color: #ff0000;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="register-card">
        <div class="text-center mb-4">
            <h3>Register</h3>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input id="name" type="text"
                       class="form-control @error('name') is-invalid @enderror"
                       name="name" value="{{ old('name') }}" required autofocus>
                @error('name')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input id="email" type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}" required>
                @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="phone_number" class="form-label">Phone Number</label>
                <input id="phone_number" type="text"
                       class="form-control @error('phone_number') is-invalid @enderror"
                       name="phone_number" value="{{ old('phone_number') }}" required>
                @error('phone_number')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       name="password" required>
                @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password-confirm" class="form-label">Confirm Password</label>
                <input id="password-confirm" type="password"
                       class="form-control" name="password_confirmation" required>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary">
                    Register
                </button>
            </div>

            <div class="text-center">
                <p class="text-white">
                    Already have an account?
                    <a href="{{ route('login') }}" class="fw-bold">Login here</a>
                </p>
            </div>
        </form>
    </div>
</div>

</body>
</html>
