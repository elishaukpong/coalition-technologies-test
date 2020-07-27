@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        #sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            $( "#sortable" ).sortable({
                update:function(event, ui){
                    $('#sortable tr').removeClass('block');
                    ui.item.addClass('block');

                    let position = ui.item.index() + 1;
                    let id = $($('tr.block')[0]).data('id');

                    $.post('{{ route('task.reorder') }}', {
                        "_token": "{{ csrf_token() }}",
                        "position": position,
                        "id": id
                    }).done(function(response){
                        console.dir(response);
                    }).fail(function (response) {
                            alert('Error occured while sending reorder request');
                        });
                }
            });

            $( "#sortable" ).disableSelection();


            $('.delete').click(function(e){
                e.preventDefault();

                let id = $(this).data('delete-id');

                $.post(`${location.origin}/tasks/${id}`, {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'DELETE',
                }).done(function(response){
                    location.reload();
                }).fail(function (response) {
                    alert('Error occured while sending deleting task');
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
@section('content')

    <div class="row mt-5">

        @if($tasks->count())
            <div class="col-12">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#exampleModal">
                    Create Task
                </button>
            </div>

            <div class="col-12 mt-5">

                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody id="sortable">

                        @foreach($tasks as $key => $task)

                            <tr data-id="{{$task->id}}">

                                <td id="name-{{$task->id}}">{{$task->name}}</td>
                                <td id="desc-{{$task->id}}">{{$task->description}}</td>
                                <td>
                                    <a href="#" class="btn btn-warning btn-small edit-button" data-toggle="modal" data-key="{{$task->id}}" data-target="#exampleModalTwo">Edit</a>

                                    <a href="#" class="btn btn-danger delete btn-small" data-delete-id="{{$task->id}}">Delete</a>
                                </td>

                            </tr>

                        @endforeach

                    </tbody>
                </table>
            </div>
        @else
            <div class="col-12 text-center">
                <h1 class="mb-3 mt-5 pt-5">No task for this project yet!</h1>
                <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#exampleModal">
                    Create Task
                </button>
            </div>

        @endif
    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('tasks.store')}}" method="POST">
                    @csrf

                        <input type="hidden" name="project_id" value="{{$project->id}}">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="taskname">Task Name</label>
                            <input type="text" class="form-control mb-3" name="name"  id="taskname">
                        </div>

                        <div class="form-group">
                            <label for="taskdesc">Task Desc</label>

                            <textarea name="description" id="" cols="10" rows="10" class="form-control" id="taskdesc"></textarea>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Task</button>
                    </div>
                </form>

            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModalTwo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelTwo" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST"  id="update-form">
                    @method('PATCH')
                    @csrf

                    <input type="hidden" name="project_id" value="{{$project->id}}">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="taskname">Task Name</label>
                            <input type="text" class="form-control mb-3" name="name"  id="edit-taskname">
                        </div>

                        <div class="form-group">
                            <label for="taskdesc">Task Desc</label>

                            <textarea name="description" cols="10" rows="10" class="form-control" id="edit-taskdesc"></textarea>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Task</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
