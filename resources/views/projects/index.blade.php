@extends('layouts.app')

@section('content')

    <div class="row">
        @forelse($projects as $project)
            <div class="col-3 mt-3">
                <div class="card" >
                    <div class="card-body">
                        <h5 class="card-title">{{$project->name}}</h5>
                        <a href="{{route('projects.show', $project->id)}}" class="btn btn-dark">Show Tasks</a>
                        <a href="{{route('projects.show', $project->id)}}" data-delete-id="{{$project->id}}" class="btn btn-danger delete" >Delete</a>
                    </div>
                </div>
            </div>

        @empty
            <div class="col-12 text-center">
                <h1 class="mb-3 mt-5 pt-5">No Project yet!</h1>
                <a href="{{route('projects.create')}}" class="btn btn-dark">Create New Project</a>
            </div>
        @endforelse
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {

            $('.delete').click(function(e){
                e.preventDefault();

                let id = $(this).data('delete-id');

                $.post(`${location.origin}/projects/${id}`, {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'DELETE',
                }).done(function(response){
                    location.reload();
                }).fail(function (response) {
                    alert('Error occured while deleting project');
                });
            });

            $('.edit-button').click(function(e){

                e.preventDefault();
                let key = $(this).data('key');
                $('#update-form').attr('action', `${location.origin}/tasks/${key}`);

                $('#edit-taskname').val($(`#name-${key}`).html());
                $('#edit-taskdesc').html($(`#desc-${key}`).html());

                $("#exampleModalTwo").modal('show');

            });

        });

    </script>
@endpush

