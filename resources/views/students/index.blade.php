@extends('layouts.app')
@section('content')

{{-- AddStudentModal  --}}
<div class="modal fade" id="AddStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul id="savefrom_errList"></ul>
                <div class="form-group mb-3">
                    <label for="">Student Name</label>
                    <input type="text" class="name form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="">Email</label>
                    <input type="email" class="email form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="">Phone</label>
                    <input type="tel" class="phone form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="">Course</label>
                    <input type="text" class="course form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary add_student">Save</button>
            </div>
        </div>
    </div>
</div>
{{-- AddStudentModal  --}}

{{-- EditstudentModal  --}}
<div class="modal fade" id="EditStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul id="updateform_errList"></ul>

                <input type="text" id="edit_stud_id">

                <div class="form-group mb-3">
                    <label for="">Student Name</label>
                    <input type="text" id="edit_name" class="name form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="">Email</label>
                    <input type="email" id="edit_email" class="email form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="">Phone</label>
                    <input type="tel" id="edit_phone" class="phone form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="">Course</label>
                    <input type="text" id="edit_course" class="course form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary update_student">Update</button>
            </div>
        </div>
    </div>
</div>
{{-- EditstudentModal  --}}

{{-- DeletestudentModal  --}}
<div class="modal fade" id="DeleteStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            {{-- <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>  --}}
            <div class="modal-body">
                <input type="text" id="delete_stud_id">
                <h4>Do you wnat to delete the data?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger delete_student">Delete</button>
            </div>
        </div>
    </div>
</div>
{{-- DeletestudentModal  --}}

<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div id="success_message"></div>
            <div class="card">
                <div class="card-header">
                    <h4>Students Data
                        <a href="#" data-bs-toggle="modal" data-bs-target="#AddStudentModal"
                            class="btn btn-primary float-end btn-sm">Add Student</a>
                    </h4>
                </div>
                <div class="card-body">
                    <table class="table table-border table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Course</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script>
    $( document ).ready( function () {
        fetchStudent();

        function fetchStudent() {
            $.ajax( {
                type: "GET",
                url: "/fetch-students",
                dataType: "json",
                success: function ( response ) {
                    $( 'tbody' ).html( "" );
                    $.each( response.students, function ( key, item ) {
                        $( 'tbody' ).append(
                            '<tr>\
                                <td>' + item.id + '</td>\
                                <td>' + item.name + '</td>\
                                <td>' + item.email + '</td>\
                                <td>' + item.phone + '</td>\
                                <td>' + item.course + '</td>\
                                <td><button type="button" value="' + item.id + '" class="btn btn-primary editbtn btn-sm">Edit</button></td>\
                                <td><button type="button" value="' + item.id + '" class="btn btn-danger deletebtn btn-sm">Delete</button></td>\
                            \</tr>'
                        );
                    } );
                }
            } );
        }

        $( document ).on( 'click', '.add_student', function ( e ) {
            e.preventDefault();
            var data = {
                'name': $( '.name' ).val(),
                'email': $( '.email' ).val(),
                'phone': $( '.phone' ).val(),
                'course': $( '.course' ).val()
            }

            $.ajaxSetup( {
                headers: {
                    'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                }
            } );

            $.ajax( {
                type: "POST",
                url: "/students",
                data: data,
                dataType: "json",
                success: function ( response ) {
                    if ( response.status == 400 ) {
                        $( '#savefrom_errList' ).html( "" );
                        $( '#savefrom_errList' ).addClass( 'alert alert-danger' );
                        $.each( response.errors, function ( key, err_values ) {
                            $( '#savefrom_errList' ).append( '<li>' + err_values +
                                '</li>' )
                        } )
                    } else {
                        $( '#savefrom_errList' ).html( "" );
                        $( '#success_message' ).addClass( 'alert alert-success' );
                        $( '#success_message' ).text( response.message );
                        $( '#AddStudentModal' ).modal( 'hide' );
                        $( '#AddStudentModal' ).find( 'input' ).val( "" );
                        fetchStudent();
                    }
                }
            } );
        } )

        $( document ).on( 'click', '.editbtn', function ( e ) {
            e.preventDefault();
            var stuId = $( this ).val();
            $( '#EditStudentModal' ).modal( 'show' );
            $( '#edit_stud_id' ).hide();
            $.ajax( {
                type: "GET",
                url: "/edit-student/" + stuId,
                success: function ( response ) {
                    if ( response.status == 404 ) {
                        $( '#success_message' ).html( "" );
                        $( '#success_message' ).addClass( "alert alert-danger" );
                        $( '#success_message' ).text( response.message );
                    } else {
                        $( '#edit_name' ).val( response.student.name );
                        $( '#edit_email' ).val( response.student.email );
                        $( '#edit_phone' ).val( response.student.phone );
                        $( '#edit_course' ).val( response.student.course );
                        $( '#edit_stud_id' ).val( stuId );
                    }
                }
            } );
        } );

        $( document ).on( 'click', '.update_student', function ( e ) {
            e.preventDefault();
            $( this ).text( "updating" );
            var stuId = $( '#edit_stud_id' ).val();
            var data = {
                'name': $( '#edit_name' ).val(),
                'email': $( '#edit_email' ).val(),
                'phone': $( '#edit_phone' ).val(),
                'course': $( '#edit_course' ).val(),
            }

            $.ajaxSetup( {
                headers: {
                    'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                }
            } );

            $.ajax( {
                type: "PUT",
                url: "/update-student/" + stuId,
                data: data,
                dataType: "json",
                success: function ( response ) {
                    if ( response.status == 400 ) {
                        $( '#updateform_errList' ).html( "" );
                        $( '#updateform_errList' ).addClass( 'alert alert-danger' );
                        $.each( response.errors, function ( key, err_values ) {
                            $( '#updateform_errList' ).append( '<li>' + err_values +
                                '</li>' )
                        } )
                        $( '.update_student' ).text( "Update" );
                    } else if ( response.status == 404 ) {
                        $( '#updateform_errList' ).html( "" );
                        $( '#success_message' ).addClass( 'alert alert-success' );
                        $( '#success_message' ).text( response.message );
                        $( '.update_student' ).text( "Update" );
                    } else {
                        $( '#updateform_errList' ).html( "" );
                        $( '#success_message' ).html( "" );
                        $( '#success_message' ).addClass( 'alert alert-success' );
                        $( '#success_message' ).text( response.message );
                        $( '#EditStudentModal' ).modal( 'hide' );
                        $( '.update_student' ).text( "Update" );
                        fetchStudent();
                    }
                }
            } );
        } );

        $( document ).on( 'click', '.deletebtn', function ( e ) {
            e.preventDefault();
            var stuId = $( this ).val();
            $( '#delete_stud_id' ).val( stuId );
            $( '#delete_stud_id' ).hide();
            $( '#DeleteStudentModal' ).modal( 'show' );
        } );

        $( document ).on( 'click', '.delete_student', function ( e ) {
            e.preventDefault();
            $( this ).text( "Deleting" );
            var stuId = $( '#delete_stud_id' ).val();

            $.ajaxSetup( {
                headers: {
                    'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                }
            } );

            $.ajax( {
                type: "DELETE",
                url: "/delete-student/" + stuId,
                success: function ( response ) {
                    $( '#success_message' ).addClass( 'alert alert-success' );
                    $( '#success_message' ).text( response.message );
                    $( '#DeleteStudentModal' ).modal( 'hide' );
                    $( '.delete_student' ).text( "Delete" );
                    fetchStudent();
                }
            } );
        } );
    } );

</script>

@endsection
