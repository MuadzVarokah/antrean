<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Admin - Sistem Antrian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
          body {
               background-color: #EBF9FF;
               min-height: 100vh;
               display: flex;
               align-items: center;
               justify-content: center;
               font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
          }

          /* body {
               background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
               min-height: 100vh;
               display: flex;
               align-items: center;
               justify-content: center;
          } */

          .form-control {
               background-color: #f8f9fa;
          }

          .login-icon {
               width: 80px;
               height: 80px;
               border-radius: 50%;
               display: flex;
               align-items: center;
               justify-content: center;
               margin: 0 auto 1rem;
          }
    </style>
</head>

<body>
    <div class="container">
          <div class="login-icon bg-info">
               <i class="fas fa-user-shield fa-2x text-white"></i>
          </div>

          <h3 class="text-center text-muted mb-5">Login Admin</h3>

          <div class="card card-body login-card bg-white border-0 rounded-4 shadow p-3 mx-auto px-5 py-4" style="max-width: 30rem">

               @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                         {{ session('success') }}
                         <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
               @endif

               @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                         {{ $errors->first() }}
                         <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
               @endif

               <form method="POST" action="{{ route('admin.login.post') }}">
                    @csrf

                    <div class="mb-3">
                         <label class="form-label text-muted">
                         <i class="fas fa-envelope"></i> Email
                         </label>
                         <input type="email" name="email" class="form-control form-control-lg"
                         value="{{ old('email') }}" required autofocus>
                    </div>

                    <div class="mb-3">
                         <label class="form-label text-muted">
                         <i class="fas fa-lock"></i> Password
                         </label>
                         <input type="password" name="password" class="form-control form-control-lg" required>
                    </div>

                    <div class="mb-3 form-check">
                         <input type="checkbox" name="remember" class="form-check-input" id="remember">
                         <label class="form-check-label" for="remember">Ingat Saya</label>
                    </div>

                    <button type="submit" class="btn btn-info text-white btn-lg w-100">
                         <i class="fas fa-sign-in-alt"></i> Login
                    </button>
               </form>

               <div class="text-center mt-3">
                    <a href="/" class="text-decoration-none">
                         <i class="fa-solid fa-chevron-left"></i> Kembali ke Halaman Utama
                    </a>
               </div>
          </div>
     </div>

     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
