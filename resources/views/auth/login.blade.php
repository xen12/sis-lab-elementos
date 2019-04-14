<!DOCTYPE html>
<html lang="en">
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Ingresar</title>

        <!-- Custom fonts for this template-->
        <link
            href="vendor/fontawesome-free/css/all.min.css"
            rel="stylesheet"
            type="text/css">
        <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

        <!-- Custom styles for this template-->
        <link href="css/sb-admin-2.css" rel="stylesheet">

    </head>

    <body class="bg-gradient-success">

        <div class="container">

            <!-- Outer Row -->
            <div class="row justify-content-center">

                <div class="col-xl-10 col-lg-12 col-md-9">

                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="row">
                                <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                                <div class="col-lg-6">
                                    <div class="p-5">
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900 mb-4">Bienvenido Nuevamente</h1>
                                        </div>
                                        <form class="user"  role="form" method="POST" action="{{ url('/login') }}">
                                                {{ csrf_field() }}
                                            <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                                <input
                                                    type="email"
                                                    class="form-control form-control-user"
                                                    id="email"
                                                    aria-describedby="emailHelp"
                                                    name="email"
                                                    placeholder="Dirección de Correo"
                                                    value="{{ old('email') }}"
                                                    required autofocus>
                                                        @if ($errors->has('email'))
                                                            <span class="help-block">
                                                            <strong>{{ $errors->first('email') }}</strong>
                                                            </span>
                                                        @endif
                                            </div>
                                            <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                                <input
                                                    type="password"
                                                    class="form-control form-control-user"
                                                    id="password"
                                                    name="password"
                                                    placeholder="Contraseña"
                                                    required>
                                                    @if ($errors->has('password'))
                                                        <span class="help-block">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                        </span>
                                                    @endif
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox small">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck" name="remember" {{ old('remember') ? 'checked' : ''}}>
                                                    <label class="custom-control-label" for="customCheck">Recordar</label>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                                    Ingresar
                                            </button>
                                        </form>
                                        <hr>
                                        <div class="text-center">
                                            <a class="small" href="{{ url('/password/reset') }}">
                                                ¿Olvidaste tu Contraseña?
                                            </a>
                                        </div>
                                        <div class="text-center">
                                            <a class="small" href="{{ url('/register') }}">Crea una Cuenta</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin-2.min.js"></script>

    </body>

</html>