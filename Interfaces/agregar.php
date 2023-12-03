<?php include "../php/bd.php" ?>

<div class="modal fade" id="agregar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h3 class="modal-title" id="exampleModalLabel">Crear Proyecto</h3>
                <button type="button" class="btn btn-primary" data-dismiss="modal">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="../php/bd.php" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre del Proyecto:</label>
                                <input type="text" id="nombre" name="nombre" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción:</label>
                                <input type="text" id="descripcion" name="descripcion" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="archivo" class="form-label">Selecciona el primer archivo Excel:</label>
                        <input type="file" class="form-control" id="archivo1" name="archivo1" accept=".xlsx">
                    </div>
                    <div class="mb-3">
                        <label for="archivo" class="form-label">Selecciona el segundo archivo Excel:</label>
                        <input type="file" class="form-control" id="archivo2" name="archivo2" accept=".xlsx">
                    </div>
                    <!-- <div class="mb-3">
                        <label for="archivo" class="form-label">Selecciona el tercer archivo Excel:</label>
                        <input type="file" class="form-control" id="archivo3" name="archivo3" accept=".xlsx">
                    </div> -->
                    <br>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="crearProyecto">Subir archivo</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>