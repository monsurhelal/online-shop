@extends('admin.layouts.app')

@section('content')

				<!-- Content Header (Page header) -->
				<section class="content-header">					
					<div class="container-fluid my-2">
						<div class="row mb-2">
							<div class="col-sm-6">
								<h1>Create Sub Category</h1>
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
                        @include('admin.message')
                        <form action="" name="shippingForm" id="shippingForm">
                            <div class="card">
                                <div class="card-body">								
                                    <div class="row">
                                         <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="category">country</label>
                                                <select name="country" id="country" class="form-control">
                                                    <option value="">Select a country</option>
                                                    @if ($countries->isNotEmpty())

                                                        @foreach ($countries as $country)
                                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                        @endforeach
                                                        
                                                    @endif
                                                    <option value="rest_of_wrold">rest of the world</option>
                                                </select>
                                                <p></p>
                                            </div>
                                        </div>

                                     	<div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="amount">Slug</label>
                                                <input type="text" name="amount" id="amount" class="form-control" placeholder="Amount">	
                                                <p></p>
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
                        <div class="card">
                            <div class="card-body">								
                                <div class="row">
                                     <div class="col-md-12">
                                        <div class="mb-3">
                                            <table  class="table table-hover">
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Name</th>
                                                    <th>Amount</th>
                                                    <th>Action</th>
                                                </tr>
                                                @foreach ($shippingCharges as $shippingCharge)
                                                <tr>
                                                    <td>{{ $shippingCharge->id }}</td>
                                                    <td>{{ ($shippingCharge->country_id == "rest_of_wrold") ? "rest of the world" : $shippingCharge->name }}</td>
                                                    <td>{{ $shippingCharge->amount }}</td>
                                                    <td>
                                                        <a href="{{ route('shipping.edit',$shippingCharge->id) }}"class="btn btn-primary" href="">Edit</a>
                                                        <a href="javascript:void(0)" onclick=" deleteShipping({{$shippingCharge->id}}) " class="btn btn-danger" href="">delete</a>
                                                    </td>
                                                    
                                                </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                     </div>
                                </div>
                            </div>
					</div>
					<!-- /.card -->
				</section>
				<!-- /.content -->
    
@endsection

@section('customeJs')
<script>
    $('#shippingForm').submit(function(e){

e.preventDefault();

const element = $('#shippingForm');

$.ajax({
    url : '{{ route("shipping.store") }}',
    type : 'post',
    data : element.serializeArray(),
    datatype : 'json',
    success : function(response){


        if (response['status'] == true) {

            window.location.href= "{{ route('shipping.create') }}";

            $('#country').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
             $('#amount').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
        }else{
            var error = response['errors'];

            if(error['country']){

                $('#country').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(error['country']);
            }
            if(error['amount']){

                $('#amount').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(error['amount']);
            }
        }

        

    },
    error:function(jqXHR,exception){

        console.log('something went wrong');

    }
})

});
function deleteShipping(id){

let url = '{{ route("shipping.delete","Id") }}';
let newUrl = url.replace("Id",id);

if(confirm('do you want to delete?')){
    $.ajax({
        url : newUrl,
        type : "delete",
        dataType : 'json',
        data : {},
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success : function(response){

            if (response['status']) {
                window.location.href = "{{ route('shipping.create') }}";
            }

        }
    })
}}
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