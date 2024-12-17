   

   <!-- Lista de Registros del Día -->
   <div class="mb-4">
       <h5 class="text-primary">Registros del Día</h5>

         <!-- Botón para Registrar Nuevo Ingreso/Egreso -->
        <div class="my-4 mt-4 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registerModal">
                <i class="bi bi-plus-circle"></i> Registrar
            </button>
        </div>
       <div class="table-responsive">
           <table class="table table-bordered table-striped">
               <thead>
                   <tr>
                       <th>Usuario</th>
                       <th>Tipo</th>
                       <th>Monto</th>
                       <th>Fecha y Hora</th>
                   </tr>
               </thead>
               <tbody id="daily-records-table">
                   <tr>
                       <td>1</td>
                       <td>Carga</td>
                       <td>$150.00</td>
                       <td>2024-12-13 09:00 AM</td>
                   </tr>
                   <tr>
                       <td>2</td>
                       <td>Retiro</td>
                       <td>$50.00</td>
                       <td>2024-12-13 02:00 PM</td>
                   </tr>
               </tbody>
           </table>
       </div>
   </div>



   