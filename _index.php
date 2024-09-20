<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.css">

    <title>INAOEP</title>

    <style>
        body {
            padding-top: 65px;
        }
    </style>

</head>

<body>

    <nav class="navbar bg-dark fixed-top" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Inicio</a>
        </div>
    </nav>

    <div class="main">

        <div class="container">

            <div class="table-responsive">
                <table class="table table-hover" id="mainTable">
                    <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Nombre</th>
                          <th scope="col">A. Paterno</th>
                          <th scope="col">A. Materno</th>
                          <th scope="col">CURP</th>
                          <th scope="col">Correo electrónico</th>
                          <th scope="col">Acciones</th>
                        </tr>
                      </thead>
                      <tbody>

                      </tbody>
                </table>
              </div>

        </div>

    </div>


</body>

<script src="/vendor/jquery/jquery.min.js"></script>
<script src="/vendor/bootstrap/js/bootstrap.js"></script>
<script src="/controllers/INAOEPController.js"></script>

</html>