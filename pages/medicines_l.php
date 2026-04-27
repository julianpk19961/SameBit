<?php
include './generales/header.php';
?>

<header class="d-flex flex-wrap justify-content-center py-3 border-bottom">

    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
        <img src="../img/logo.png" height="40" class="logo">
    </a>

    <nav>
        <ul class="nav nav-pills">
            <li class="nav-item"><a href="./medicines_l.php" class="nav-link active" aria-current="page">Samecomed</a></li>
            <li class="nav-item"><a href="../config/logout.php" class="nav-link link-secondary"><?php echo __('sign_out'); ?></a></li>
        </ul>
    </nav>

</header>

<div id="tittleModule" class="row border border-end">
    <h1 class="text-center align-self-center"><?php echo __('medicine_management'); ?></h1>
</div>


<div class="col-xl-12 mt-1 mb-2">
    <div class="table table-striped table-bordered" id="medicaldiv">
        <table class="table table-bordered table-striped mb-1" id="medical_tbl" style="width:100%">
            <thead>
                <tr class="table text-light bg-primary">
                    <th hidden></th>
                    <th hidden></th>
                    <th class="text-center"> <?php echo __('status'); ?> </th>
                    <th> <?php echo __('name'); ?> </th>
                    <th> <?php echo __('reference_col'); ?> </th>
                    <th> <?php echo __('observation_col'); ?> </th>
                    <th class="text-center"> <?php echo __('options'); ?> </th>
                </tr>
            </thead>
            <tbody id="dataMedicines">
            </tbody>

            <div class="medicine_id modal fade" id="modal-delete-" tabindex="-1" aria-labelledby="" aria-hidden="true">
                <div class="modal-dialog bg-light rounded-3">
                    <form id="destroyMedicine" method="POST">
                        <div class="modal-content">
                            <div class="modal-header text-center">
                                <h4 class="modal-title" id="drop-modal-title"><?php echo __('delete'); ?></h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input id="modaldel_pk_uuid" hidden value="">
                                <input id="modaldel_z_xone" hidden value="">
                                <p>¿<?php echo __('delete'); ?> <span id='action'></span>: <span id='med-name-display'></span>?<br><?php $app_lang === 'en' ? print('This action cannot be undone.') : print('Esta acción no se puede revertir.'); ?></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo __('close'); ?></button>
                                <button type="submit" class="" data-bs-dismiss="modal"><i id='commit-drop-medicine' class=""></i></button>
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
                    <h5 class="modal-title col"><?php echo __('new_medicine'); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="medicineStored">
                    <div class="modal-body">
                        <input type="text" class="form-control" id="pk_uuid" name="pk_uuid" hidden>

                        <div class="mb-3">
                            <label for="name" class="col-form-label"><?php echo __('name_label'); ?></label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="reference" class="col-form-label"><?php echo __('reference_label'); ?></label>
                            <input type="text" class="form-control" id="reference" name="reference">
                        </div>
                        <div class="mb-3">
                            <label for="observation" class="col-form-label"><?php echo __('observation_label'); ?></label>
                            <textarea class="form-control" name="   " id="observation" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" id="save-buttons">
                    </div>
                </form>
                <div class="container-sm" id="kardex">

                </div>
            </div>
        </div>
    </div>
</div>

<script src="../Js/medicines.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>
