@extends('components.sections.adminSection')
@section('userContent')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="panel-heading m-0 font-weight-bold text-primary container">{{$title or 'Materias'}}</div>
            
            <div class="card-body">
                @if (Session::has('status_message'))
                    <p class="alert alert-success">{{Session::get('status_message')}}</p>                           
                @endif

                <div class="">
                    <div class="col-sm-12 table-responsive">
                        <table class="table text-center dataTable table-striped table-secondary" id="dataTable" width="100%" cellspacing="0" role="grid" aria-describedby="dataTable_info" style="width: 100%;">
                            <thead>
                                <tr role="row" class="bg-dark">
                                    <th class="sorting mgx-1" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" style="width: 260px;"><font style="vertical-align: inherit;"><font style="color: white; vertical-align: inherit;">Nombre</font></font></th>

                                    <th class="sorting mgx-1" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 200px;"><font style="vertical-align: inherit;"><font style="color: white; vertical-align: inherit;">Fecha de creación</font></font></th>
                                    
                                    <th class="sorting mgx-1" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="Age: activate to sort column ascending" style="width: 200px;"><font style="vertical-align: inherit;"><font style="color: white; vertical-align: inherit;">Fecha Actualizacion</font></font></th>

                                    <th class="text-center" data-orderable="false" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="Start date: activate to sort column ascending" style="width: 88px;"><font style="vertical-align: inherit;"><font style="color: white; vertical-align: inherit;">Acciones</font></font></th>
                                </tr>
                            </thead>
                                {{-- <tfoot>
                                    <tr><th rowspan="1" colspan="1"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Nombre</font></font></th><th rowspan="1" colspan="1"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Fecha de Creación</font></font></th><th rowspan="1" colspan="1"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Fecha de Actualización</font></font></th><th rowspan="1" colspan="1"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Ver</font></font></th><th rowspan="1" colspan="1"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Editar</font></font></th><th rowspan="1" colspan="1"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Eliminar</font></font></th></tr>
                                </tfoot> --}}
                            <tbody> 
                                @foreach ($subjectMatters as $item)
                                    <tr role="row" class="odd">
                                        <td class="mgx-1"><font style="vertical-align: inherit;"><font style="vertical-align: inherit; color:">{{$item->name}}</font></font></td>
                                        <td class="mgx-1"><font style="vertical-align: inherit;"><font style="vertical-align: inherit; color:">{{$item->created_at}}</font></font></td>
                                        <td class="mgx-1"><font style="vertical-align: inherit;"><font style="vertical-align: inherit; color:">{{$item->updated_at}}</font></font></td>
                                        <td class="p-2" style="text-align: center; display: inline-flex;">
                                            <a href="/admin/subjectmatter/{{$item->id}}/edit" class="btn btn-warning btn-circle btn-sm mx-1" data-toggle="tooltip" title="Editar"> <i class="fas fa-edit"></i> </a>

                                            <button type="button" class="btn btn-danger btn-circle btn-sm mx-1" data-toggle="modal" data-toggle-2="tooltip" title="Eliminar" data-target="#eliminar{{ $item->id }}"> <i class="fas fa-trash"></i> </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="eliminar{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel"> Eliminar Materia </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        </div>
                                                        <div class="modal-body text-left">
                                                            ¿Esta seguro que desea eliminar la materia {{ $item->name }}?
                                                        </div>
                                                        <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                        <input type="hidden" id="id_auxiliar">
                                                            <form action="{{route('subjectmatters.destroy', [$item->id])}}" method="POST">
                                                                {{csrf_field()}}
                                                                {{method_field('DELETE')}}
                                                                <button type="submit" class="btn btn-danger">Eliminar</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
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
@endsection