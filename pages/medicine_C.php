<?php
include './generales/header.php';
include './generales/nav.php';
?>

<script src="../Js/medicineSave.js" defer></script>
<form id="medicineStored" action="../pages/medicine_.php" method="POST" class="p-2 form h-100" enctype="multipart/form-data">
    <div class="container bg-light border">
        <div id="tittleModule" class="row border border-end">
            <h1 class="text-center align-self-center">NUEVO MEDICAMENTO</h1>
        </div>
        <div class="row g-12">
            <div class="col-md-12 col-lg-12">
                <div class="row mt-g-3  p-2">

                    <!-- id,KP_UUID,codigo,nombre,referencia,observacion  -->

                    <div class="row">

                        <div class="form-group col-8">
                            <label for="name">Nombre</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Nombre Medicamento" value="">
                        </div>

                        <div class="form-group col-4">
                            <label for="reference">Referencia</label>
                            <input type="text" class="form-control" id="reference" name="reference" placeholder="Referencia del medicamento" value="">
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="observation">Observación</label>
                            <textarea class="form-control w-100" name="observation" id="observation" rows="20"></textarea>
                        </div>

                    </div>

                </div>

            </div>
            <div class="row d-flex flex-row-reverse my-1 me-0">
                <input type="submit" class="btn btn-primary btn-lg col-2 mx-1" Value="Guardar">
                <a type="button" class="btn btn-secondary btn-lg col-2 mx-1" href="javascript:history.back(-1);">Cancelar</a>
            </div>
        </div>

    </div>
    </div>
</form>