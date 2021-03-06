@extends('components.sections.adminSection')
@section('userContent')

<script src="/js/generatekey.js"></script>
<div class="loader"></div>
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-10 col-md-9">
            <div class="card o-hidden border-0 my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="px-5">
                                <div class="text-center">
                                    <br>
                                    <h1 class="h4 text-gray-900 mb-4">Editar Estudiante</h1>
                                </div>
                                @if (count($errors)>0)
                                    <div class="alert alert-danger">
                                        <b>Ha ocurrido un error!</b>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                            <li>{{$error}}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form class="user" role="form" method="POST"
                                      action="{{ Route('student.update',[$student->id]) }}">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                            <label for="">Nombres</label>
                                            <input type="text" class="form-control" name="names" value="{{ old('names', $user->names)}}" required autofocus>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                                <div class="">
                                                    <label for="">Apellido Paterno</label>  
                                                    <input type="text" class="form-control" name="first_name" value="{{ old('first_name', $user->first_name)}}" required autofocus>
                                                </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                                <div class="">
                                                    <label for="">Apellido Materno</label>  
                                                    <input type="text" class="form-control" name="second_name" value="{{ old('second_name',$user->second_name)}}"required autofocus> 
                                                </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                            <label for="">Correo Electrónico</label>
                                            <input type="email" class="form-control" name="email" value="{{ old('email',$user->email)}}" required>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <div class="">
                                                <label for="">Código SIS</label>  
                                                <input type="number" class="form-control" name="code_sis" value="{{ old('code_sis',$user->code_sis)}}" required autofocus min="0">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <div class="">
                                                <label for="">Cédula de Identidad</label>  
                                                <input type="number" class="form-control" name="ci" value="{{ old('ci',$student->ci)}}" required autofocus min="0"> 
                                            </div>
                                        </div>
                                    </div>
                                    <label for="">Contraseña</label>
                                    <div class="form-group ">
                                            <input type="text" id="password" onCopy="return false" class="form-control col-md-12" name="password" required>
                                    </div>
                                    <br>
                                    <div class="form-group row"> 
                                            <div class="form-group col-md-6">
                                                <button type="submit" class="btn btn-primary btn-block">Modificar</button>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <a class="btn btn-danger btn-block" href="{{ url('/admin/students') }}">Cancelar</a>        
                                            </div>                    
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection