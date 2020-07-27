@extends('layouts.app')

@section('content')

    <div class="row">
       <div class="col-5 mx-auto mt-5">

           <form action="{{route('projects.store')}}" method="POST">
               @csrf

               <div class="form-group">
                   <label for="name">Project Name</label>
                   <input type="text" name="name" id="name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{old('name')}}">

                   @if ($errors->has('name'))
                       <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                   @endif

               </div>
               <button type="submit" class="btn btn-dark">Create Project</button>
           </form>
       </div>
    </div>
@endsection
