@extends('admin.layouts.app')

@section('content')

				<!-- Content Header (Page header) -->
				<section class="content-header">					
					<div class="container-fluid my-2">
						<div class="row mb-2">
							<div class="col-sm-6">
								<h1>Create Brands</h1>
							</div>
							<div class="col-sm-6 text-right">
								<a href="subcategory.html" class="btn btn-primary">Back</a>
							</div>
						</div>
					</div>
					<!-- /.container-fluid -->
				</section>
				<!-- Main content -->
				<section class="content">
					<!-- Default box -->
					<div class="container-fluid">
                        <form action="" name="brandsForm" id="brandsForm">
                            <div class="card">
                                <div class="card-body">								
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name">Name</label>
                                                <input type="text" name="name" id="name" class="form-control" placeholder="Name">	
                                                <p></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="slug">Slug</label>
                                                <input type="text" name="slug" id="slug" class="form-control" placeholder="Slug">	
                                                <p></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="status">Status</label>
                                                <select name="status" id="status" class="form-control">
                                                    <option value="1">Active</option>    
                                                    <option value="0">Block</option>    
                                                </select>	
                                            </div>
                                        </div>									
                                    </div>
                                </div>							
                            </div>
                            <div class="pb-5 pt-3">
                                <button type="submit" class="btn btn-primary">Create</button>
                                <a href="subcategory.html" class="btn btn-outline-dark ml-3">Cancel</a>
                            </div>
                        </form>
					</div>
					<!-- /.card -->
				</section>
				<!-- /.content -->
    
@endsection

@section('customeJs')
<script>
    $('#brandsForm').submit(function(e){

e.preventDefault();

const element = $('#brandsForm');

$.ajax({
    url : '{{ route("brands.store") }}',
    type : 'post',
    data : element.serializeArray(),
    datatype : 'json',
    success : function(response){


        if (response['status'] == true) {

            window.location.href= "{{ route('brands.index') }}";

            $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
             $('#slug').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
        }else{
            var error = response['errors'];

            if(error['name']){

                $('#name').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(error['name']);
            }
            if(error['slug']){

                $('#slug').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(error['slug']);
            }
        }

        

    },
    error:function(jqXHR,exception){

        console.log('something went wrong');

    }
})

});
$('#name').change(function(){

var element = $(this);
$.ajax({
    url : '{{ route("getSlug") }}',
    type : 'get',
    data : {title:element.val()},
    datatype : 'json',
    success : function(response){

        if(response['status'] == true){
            $('#slug').val(response['slug']);
        }
    }
});
});
</script>
@endsection