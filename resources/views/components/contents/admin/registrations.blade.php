@extends('components.sections.adminSection')
@section('userContent')

<div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="panel-heading m-0 font-weight-bold text-primary container">Periodo de inscripciones</div>
                
                <div class="card-body">
                    @if (Session::has('status_message'))
                        <p class="alert alert-success">{{Session::get('status_message')}}</p>                           
                    @endif
                    <div class="">
                        <div class="col-sm-12 table-responsive">
                            <table class="table dataTable text-center table-striped table-secondary" id="dataTable" width="100%" cellspacing="0" role="grid" aria-describedby="dataTable_info" style="width: 100%;">
                                    <thead>
                                        <tr role="row" class="bg-dark">
                                            <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" style="width: 125px;"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Gestion</font></font></th>

                                            <th class="sorting_desc" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 125px;" aria-sort="descending"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Semestre</font></font></th>

                                            <th class="sorting mgx-1" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 225px;"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Fecha Inicio</font></font></th>
                                            <th class="sorting mgx-1" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="Age: activate to sort column ascending" style="width: 225px;"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Fecha Fin</font></font></th>

                                            <th class="text-center" data-orderable="false" rowspan="1" colspan="1" aria-label="Start date: activate to sort column ascending" style="width: 88px;"><font style="vertical-align: inherit;"><font style="color: white; vertical-align: inherit;">Deshabilitar / Habilitar</font></font></th>
                                        </tr>
                                    </thead>
                                        <tbody> 
                                            @foreach ($managements as $item)
                                                <tr role="row" class="odd">
                                                    <td class="mgx-1"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">{{$item->managements}}</font></font></td>
                                                    <td class="mgx-1"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">{{$item->semester}}</font></font></td>
                                                    <td class="mgx-1"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">{{$item->start_management}}</font></font></td>
                                                    <td class="mgx-1"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">{{$item->end_management}}</font></font></td>
                                                    
                                                    <td class="text-center">
                                                        <label class="switch">
                                                            <input id="checkbox-{{$item->id}}" data-toggle="modal" data-target="#modal-inscription" type="checkbox" onclick="registrationMessage({{$item}})">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </td>
                                                    @if($item->enable_inscription == 1)
                                                    <script>
                                                        $("#checkbox-{!!json_encode($item->id)!!}")[0].checked = 1;
                                                    </script>
                                                    @else
                                                    <script>
                                                        $("#checkbox-{!!json_encode($item->id)!!}")[0].checked = 0;
                                                    </script>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                </table>
                        </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-inscription" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Inscripciones</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div id="enable-content" style="display: none;"></div>
        <div id="disable-content" style="display: none;">
            <strong> Se deshabilitarán las inscripciones para esta gestión. </strong>
        </div>
    </div>
    <div class="modal-footer">
        <button id="btn-accept" type="button" class="btn btn-primary" data-dismiss="modal" onclick="enableOrDisableCheck()">Aceptar</button>
    </div>
    </div>
</div>
</div>

<style>
    .switch {
    position: relative;
    display: inline-block;
    width: 55px;
    height: 25px;
    }
    
    /* Hide default HTML checkbox */
    .switch input {
    opacity: 0;
    width: 0;
    height: 0;
    }
    
    /* The slider */
    .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
    }
    
    .slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
    }
    
    input:checked + .slider {
    background-color: #2196F3;
    }
    
    input:focus + .slider {
    box-shadow: 0 0 1px #2196F3;
    }
    
    input:checked + .slider:before {
    -webkit-transform: translateX(28px);
    -ms-transform: translateX(28px);
    transform: translateX(28px);
    }
    
    /* Rounded sliders */
    .slider.round {
    border-radius: 34px;
    }
    
    .slider.round:before {
    border-radius: 50%;
    }
</style>

@endsection

@push('scripts')
    <script src="/js/registration-control.js"></script>
@endpush