<!-- Llamado para encabezado de la página -->
<?php
include './generales/header.php';
?>

<header class="d-flex flex-wrap justify-content-center py-3 border-bottom">

    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">

        <img src="../img/SameinLogo.png" height="40" class="logo">
    </a>

    <nav>
        <ul class="nav nav-pills">
            <li class="nav-item"><a href="./dashboard.php" class="nav-link active" aria-current="page">Inicio</a></li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Farmacia">
                    Farmacia
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="./medicines_l.php">Medicamentos</a></li>
                    <li><a class="dropdown-item" href="#">Movimientos</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="#">Reportes</a></li>
                </ul>
            </li>
            <li class="nav-item"><a href="../config/logout.php" class="nav-link">Cerrar Sesión</a></li>
        </ul>
    </nav>

</header>

<div id="tittleModule" class="row border border-end">
    <h1 class="text-center align-self-center">GESTIÓN - PRODUCTOS </h1>
</div>

<div class="row m-1">

    <div class="d-inline-flex flex-row col-12 mt-2">
        <div class="rows">
            <div class="col-auto mx-1">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-record" data-bs-whatever="@fat">Nuevo</button>
            </div>
            <!-- {{$rows->withQueryString()->links()}} -->
        </div>

        <div class="col-4 ms-auto">
            <input type="text" class="form-control" name="searchbox" id="searchbox" placeholder="Busqueda por nombre y código" value="">
        </div>
        <div class="col-auto mx-1">
            <button id="searchbtn" type="submit" class="btn btn-md btn-primary"> Buscar </button>
        </div>
    </div>

</div>

<div class="col-xl-12 mt-1 mb-2">
    <div class="table-responsive">
        <table class="table table-striped mb-1">
            <thead>
                <tr class="table text-light bg-primary">
                    <th hidden></th>
                    <th> Nombre </th>
                    <th colspan="2"> Referencia </th>
                    <th colspan="3"> Observación </th>
                    <th class="text-center"> Opciones </th>

                    <!-- <x-layouts.tables.columns :columns="$columns" /> -->
                </tr>
            </thead>
            <tbody id="dataMedicines">
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modal-record" tabindex="-1" aria-labelledby="modal-record" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-white text-center bg-primary" id="modal-head">
                    <h5 class="modal-title col">Nuevo Medicamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="medicineStored">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="col-form-label">Nombre:</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="reference" class="col-form-label">Referencia:</label>
                            <input type="text" class="form-control" id="reference" name="reference">
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">Observación:</label>
                            <textarea class="form-control" name="observation" id="observation" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="stored">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../Js/medicines.js"></script>