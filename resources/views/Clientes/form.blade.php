{{-- DIV del formulario modal para editar el registro --}}
<div class="modal animated zoomInUp custo-zoomInUp" id="form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 1500px" role="document">
{{--        <meta name="csrf-token" content="{{ csrf_token() }}">--}}
        <form autocomplete="off" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header bg-sgls">
                    <h5 class="modal-title text-white" id="exampleModalLabel">
                        <span id="titulo"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-black">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs mb-3 mt-3" id="icon-tab" role="tablist">
                        <li class="nav-item">
                           <a class="nav-link active" id="icon-datos-tab" data-toggle="tab" href="#icon-datos" role="tab" aria-controls="icon-datos" aria-selected="true">
                               @include('layouts.svg.datos')
                                Datos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="icon-contactos-tab" data-toggle="tab" href="#icon-contactos" role="tab" aria-controls="icon-contactos" aria-selected="false">
                                @include('layouts.svg.contactos')
                                Contactos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="icon-acciones-tab" data-toggle="tab" href="#icon-acciones" role="tab" aria-controls="icon-acciones" aria-selected="false">
                                @include('layouts.svg.acciones')
                                Acciones
                            </a>
                        </li>
                    </ul>
                    {{-- TABS DEL FORM --}}
                    <div class="tab-content" id="icon-tabContent">
                        {{-- div de los datos del cliente --}}
                        @include('Clientes.DatosCliente.form')
                        {{-- div de los contactos del cliente --}}
                        @include('Clientes.ContactosCliente.component')
                        {{-- div de las acciones del cliente --}}
                        @include('Clientes.AccionesCliente.component')
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
