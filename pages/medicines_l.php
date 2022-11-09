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
    <h1 class="text-center align-self-center">GESTIÓN - MEDICAMENTOS </h1>
</div>


<div class="col-xl-12 mt-1 mb-2">
    <div class="table table-striped table-bordered">
        <div class="col-auto mx-1">
            <button id="new-item" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-record" data-bs-whatever="@fat">Nuevo</button>
        </div>
        <table class="table display table-striped mb-1" id="medical_tbl">
            <thead>
                <tr class="table text-light bg-primary">
                    <th hidden></th>
                    <th hidden></th>
                    <th class="text-center"> Estado </th>
                    <th> Nombre </th>
                    <th> Referencia </th>
                    <th> Observación </th>
                    <th class="text-center"> Opciones </th>
                </tr>
            </thead>
            <tbody id="dataMedicines">
            </tbody>
            <div class="medicine_id modal fade" id="modal-delete-" tabindex="-1" aria-labelledby="" aria-hidden="true">
                <div class="modal-dialog">
                    <form id="destroyMedicine" method="POST">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="">Eliminar</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input id="modaldel_pk_uuid" hidden value="">
                                <input id="modaldel_z_xone" hidden value="">
                                <p>¿Está seguro de eliminar: registro ? <br> Esta acción no se puede revertir</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-danger" data-bs-dismiss="modal">Eliminar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </table>
    </div>

    <div class="modal fade" id="modal-record" tabindex="-1" aria-labelledby="modal-record" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-white text-center bg-primary" id="modal-head">
                    <h5 class="modal-title col">Nuevo Medicamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="medicineStored">
                    <div class="modal-body">
                        <input type="text" class="form-control" id="pk_uuid" name="pk_uuid" hidden>

                        <div class="mb-3">
                            <label for="name" class="col-form-label">Nombre:</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="reference" class="col-form-label">Referencia:</label>
                            <input type="text" class="form-control" id="reference" name="reference">
                        </div>
                        <div class="mb-3">
                            <label for="observation" class="col-form-label">Observación:</label>
                            <textarea class="form-control" name="   " id="observation" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="save-buttons modal-footer">
                    </div>
                </form>
                <div class="container-sm" id="kardex">
                    
                </div>
            </div>
        </div>
    </div>
</div>
<script src="../Js/medicines.js"></script>