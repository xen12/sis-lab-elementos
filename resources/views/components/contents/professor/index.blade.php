@extends('components.sections.'.$view)
@section('userContent')
    
<script src="/js/generatekey.js"></script>
<div class="container-fluid">
      <div class="card shadow mb-4">
          <div class="card-header py-3">
              <div class="panel-heading m-0 font-weight-bold text-primary container">{{$title}}</div>

              <div class="card-body">
                  @if (Session::has('status_message'))
                      <p class="alert alert-success"> <strong> {{Session::get('status_message')}} </strong> </p>
                  @endif
                  <div class="">
                      <div class="row">
                          <div class="col-sm-12 table-responsive ">
                                  <table class="table dataTable text-center table-striped table-secondary" id="dataTable" width="100%" cellspacing="0" role="grid" aria-describedby="dataTable_info" style="width: 100%;">
                                      <thead>
                                          <tr role="row" class="bg-dark">
                                              <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" style="width: 230px;"><font style="vertical-align: inherit;"><font style="color: white; vertical-align: inherit;">Código SIS</font></font></th>

                                              <th class="sorting_desc" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 380px;" aria-sort="descending"><font style="vertical-align: inherit;"><font style="color: white; vertical-align: inherit;">Apellidos</font></font></th>

                                              <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 380px;"><font style="vertical-align: inherit;"><font style="color: white; vertical-align: inherit;">Nombres</font></font></th>

                                              <th class="text-center" data-orderable="false" rowspan="1" colspan="1" style="width: 39px;"><font style="vertical-align: inherit;"><font style="color: white; vertical-align: inherit;">Acciones</font></font></th>
                                          </tr>
                                      </thead>
                                          <tbody>

                                                @foreach ($professors as $item)
                                                  <tr role="row" class="odd">
                                                      <td class="mgx-1"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">{{ $item->code_sis }}</font></font></td>
                                                      <td class="mgx-1 sorting_1"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">{{ $item->first_name }} {{ $item->second_name }}</font></font></td>
                                                      <td class="mgx-1"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">{{ $item->names }}</font></font></td>
                                                      {{-- <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">{{ $item->email }}</font></font></td> --}}

                                                      <td class="p-2" style="text-align: center; display: inline-flex;">
                                                          <a href="#" class="btn btn-info btn-circle btn-sm mx-1" data-toggle-2="tooltip" title="Ver Perfil" data-toggle="modal" data-target="#professorProfile" onclick="loadProfile({{ $item }})"><i class="fas fa-eye"></i></a>

                                                          <a href="/admin/professors/{{$item->id}}/edit" class="btn btn-warning btn-circle btn-sm mx-1" data-toggle="tooltip" title="Editar"><i class="fas fa-edit"></i></a>

                                                          <button type="button" class="btn btn-danger btn-circle btn-sm mx-1" data-toggle="modal" data-toggle-2="tooltip" title="Eliminar" data-target="#eliminar{{ $item->id }}"> <i class="fas fa-trash"></i> </button>

                                                          <!-- Modal -->
                                                          <div class="modal fade" id="eliminar{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                              <div class="modal-dialog" role="document">
                                                              <div class="modal-content">
                                                                  <div class="modal-header">
                                                                  <h5 class="modal-title" id="exampleModalLabel"> Eliminar Docente </h5>
                                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                      <span aria-hidden="true">&times;</span>
                                                                  </button>
                                                                  </div>
                                                                  <div class="modal-body text-left">
                                                                      ¿Esta seguro que desea eliminar al docente {{ $item->names }} {{ $item->first_name }}?
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                                  <input type="hidden" id="id_auxiliar">
                                                                      <form action="{{route('professor.destroy', [$item->id])}}" method="POST">
                                                                          {{csrf_field()}}
                                                                          {{method_field('DELETE')}}
                                                                      <button type="submit" class="btn btn-danger">Eliminar</button>
                                                                      </form>
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
  </div>

  <div class="modal fade" id="professorProfile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document" style="pointer-events: inherit;">
          <div class="card card-profile o-hidden border-0 my-3 rounded">
              <div style="background-image: url(/img/lab.jpg);" class="card-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: black; margin-top: -5px;">
                  <span aria-hidden="true"><strong>&times;</strong></span>
                </button>
              </div>
              <div class="card-body text-center"><img src="/users/demo.png" class="card-profile-img">
                  <h3 class="mb-3" id="namesProfile"></h3>
                  <div class=""><strong> Tipo de Usuario: </strong> <p> Docente </p></div>
                  <div><strong> Código Sis: </strong> <p id="codeSisProfile" ></p></div>
                  <div><strong> Correo Electrónico: </strong> <p id="emailProfile"></p></div>
              </div>
          </div>
        </div>
      </div>

<script>
    function loadProfile(item){
        document.getElementById('namesProfile').innerHTML=item.names+' '+item.first_name+' '+item.second_name;
        document.getElementById('codeSisProfile').innerHTML=item.code_sis;
        document.getElementById('emailProfile').innerHTML=item.email;
    }
</script>

@endsection