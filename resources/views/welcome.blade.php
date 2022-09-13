<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ajax</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"/>

</head>
<body>
    
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">All Student</h5>
                        <hr>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Serial</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Hometwon</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                            </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    
                        <div class="card-body">
                            <h5 class="card-title">Add Student</h5>
                            <hr>
                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Your Name">
                                <span class="text-danger" id="nameError"></span>
                            </div>
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Your Email">
                                <span class="text-danger" id="emailError"></span>
                            </div>
                            <div class="mb-3">
                                <label>Hometown</label>
                                <input type="text" class="form-control" id="hometown" name="hometown" placeholder="Your HomeTown">
                                <span class="text-danger" id="townError"></span>
                                <input type="hidden" value="" id="id" name="id">
                            </div>
                            <div class="mb-3 text-center">
                                <div class="btn btn-outline-info btn-sm" onClick="insertData()" id="insert">Insert</div>
                                <div class="btn btn-outline-info btn-sm" onClick="updateData()" id="update">Update</div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script type="text/javascript">
       $.ajaxSetup({
           headers:{
               'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
           }
       })

       $('#update').hide();

    //    get data
    function allStudent(){
        $.ajax({
            type: 'GET',
            url: '/student/get',
            dataType: 'json',
            success:function(response){
                console.log(response)
                var allStudent = "";
                var i = 1;
                $.each(response.data, function(key, value){
                    allStudent += `<tr>
                                    <td>${ i++ }</td>
                                    <td>${value.name}</td>
                                    <td>${value.email}</td>
                                    <td>${value.hometown}</td>
                                    <td>
                                        <button class="btn btn-outline-info btn-sm" id="${ value.id }" onclick="editData(this.id)" href="#">Edit</button>
                                        <button class="btn btn-outline-danger btn-sm" id="${ value.id }" onClick="deleteData(this.id)">Delete</button>
                                    </td>
                                </tr>`
                });
                $('tbody').html(allStudent);
            }
        })
    }
    allStudent();

    function clearData(){
        $('#id').val('');
        $('#name').val('');
        $('#email').val('');
        $('#hometown').val('');
        $('#nameError').text('');
        $('#emailError').text('');
        $('#townError').text('');
    }

    function insertData(){

        var name = $('#name').val();
        var email = $('#email').val();
        var hometown = $('#hometown').val();

        $.ajax({
            type: 'post',
            dataType: 'json',
            data: {
                name:name,email:email,hometown:hometown
            },
            url: '/student/store',
            success:function(data){
                allStudent();
                clearData();
                // Start Message 
                // Start Message 
                const Toast = Swal.mixin({
                      toast: true,
                      position: 'top-end',
                      
                      showConfirmButton: false,
                      timer: 3000
                    })
                if ($.isEmptyObject(data.error)) {
                    Toast.fire({
                        type: 'success',
                        icon: 'success',
                        title: 'Data Inserted Successfully'
                    })
                }else{
                    Toast.fire({
                        type: 'error',
                        icon: 'error',
                        title: 'Data did not inserted'
                    })
                }

                // End Message 
                
                console.log('data inserted successfully');
            },
            error:function(error){
                $('#nameError').text(error.responseJSON.errors.name);
                $('#emailError').text(error.responseJSON.errors.email);
                $('#townError').text(error.responseJSON.errors.hometown);
            }
        });
    }

    function editData(id){
        $.ajax({
            type: 'get',
            dataType: 'json',
            url: '/student/edit/' +id,
            success:function(data){
                $('#insert').hide();
                $('#update').show();
                $('#id').val(data.id);
                $('#name').val(data.name);
                $('#email').val(data.email);
                $('#hometown').val(data.hometown);
            }
        })
    }

    function updateData(){

        var id = $('#id').val();
        var name = $('#name').val();
        var email = $('#email').val();
        var hometown = $('#hometown').val();

        $.ajax({
            type: 'post',
            dataType: 'json',
            data: {
                name:name,email:email,hometown:hometown
            },
            url: '/student/update/' +id,
            success:function(data){
                allStudent();
                clearData();
                $('#insert').show();
                $('#update').hide();
                // Start Message 
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    
                    showConfirmButton: false,
                    timer: 3000
                    })
                if ($.isEmptyObject(data.error)) {
                    Toast.fire({
                        type: 'success',
                        icon: 'success',
                        title: 'Data Updated Successfully'
                    })
                }else{
                    Toast.fire({
                        type: 'error',
                        icon: 'error',
                        title: 'Data did not Updated'
                    })
                }

                // End Message 
                console.log('data Updated successfully');
            },
            error:function(error){
                $('#nameError').text(error.responseJSON.errors.name);
                $('#emailError').text(error.responseJSON.errors.email);
                $('#townError').text(error.responseJSON.errors.hometown);
                console.log(error);
            }
        });
    }

    function deleteData(id){
        Swal.fire({
        title: 'Do you want to Delete the Item?',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        denyButtonText: `Don't Delete`,
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            $.ajax({
                type: 'get',
                dataType: 'json',
                url: '/student/delete/' +id,
                success:function(data){
                    allStudent();
                    clearData();
                    // Start Message 
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        
                        showConfirmButton: false,
                        timer: 3000
                        })
                    if ($.isEmptyObject(data.error)) {
                        Toast.fire({
                            type: 'success',
                            icon: 'success',
                            title: 'Data Deleted Successfully'
                        })
                    }else{
                        Toast.fire({
                            type: 'error',
                            icon: 'error',
                            title: 'Data did not Deleted'
                        })
                    }

                    // End Message 
                }
            });
            Swal.fire('Deleted!', '', 'success')
        } else if (result.isDenied) {
            
        }
        })
    }


   // Start Product View with Modal 
//    function productView(id){
//        // alert(id)
//        $.ajax({
//            type: 'GET',
//            url: '/product/view/modal/'+id,
//            dataType:'json',
//            success:function(data){
//                $('#pname').text(data.product.product_name_en);
//                $('#price').text(data.product.selling_price);
//                $('#pcode').text(data.product.product_code);
//                $('#pcategory').text(data.product.category1.category_name_en);
//                $('#pbrand').text(data.product.brand1.brand_name_en);
//                $('#pimage').attr('src','/'+data.product.product_thambnail);

//                $('#product_id').val(id);
//                $('#qty').val('1');

//                // Product Price 
//                if (data.product.discount_price == null) {
//                    $('#pprice').text('');
//                    $('#oldprice').text('');
//                    $('#pprice').text(data.product.selling_price);
//                }else{
//                    $('#pprice').text(data.product.discount_price);
//                    $('#oldprice').text(data.product.selling_price);
//                } // end prodcut price 
//                // Start Stock opiton
//                if (data.product.product_qty > 0) {
//                    $('#aviable').text('');
//                    $('#stockout').text('');
//                    $('#aviable').text('aviable');
//                }else{
//                    $('#aviable').text('');
//                    $('#stockout').text('');
//                    $('#stockout').text('stockout');
//                } // end Stock Option 

//                // Color
//                 $('select[name="color"]').empty();        
//                 $.each(data.color,function(key,value){
//                     $('select[name="color"]').append('<option value=" '+value+' ">'+value+' </option>')
//                     if (data.color == "") {
//                         $('#colorArea').hide();
//                     }else{
//                         $('#colorArea').show();
//                     }
//                 }) // end color
//                  // Size
//                 $('select[name="size"]').empty();        
//                 $.each(data.size,function(key,value){
//                     $('select[name="size"]').append('<option value=" '+value+' ">'+value+' </option>')
//                     if (data.size == "") {
//                         $('#sizeArea').hide();
//                     }else{
//                         $('#sizeArea').show();
//                     }
//                 }) // end size


//            }
//        })
    
//    }


//    function addToCart(){
//          var product_name = $('#pname').text();
//          var id = $('#product_id').val();
//          var color = $('#color option:selected').text();
//          var size = $('#size option:selected').text();
//          var qty  = $('#qty').val();

//          $.ajax({
//             type: "POST",
//             dataType: 'json',
//             data:{
//                color:color, size:size, qty:qty, product_name:product_name
//             },
//             url: "/product/addcart/"+id,
//             success:function(data){
//                miniCart()
//                $('#closeModel').click();
//                                console.log(data)
//                 // console.log(data)
//                 // Start Message 
//                 const Toast = Swal.mixin({
//                       toast: true,
//                       position: 'top-end',
//                       icon: 'success',
//                       showConfirmButton: false,
//                       timer: 4000
//                     })
//                 if ($.isEmptyObject(data.error)) {
//                     Toast.fire({
//                         type: 'success',
//                         title: data.success
//                     })
//                 }else{
//                     Toast.fire({
//                         type: 'error',
//                         title: data.error
//                     })
//                 }
//                console.log(data)
//             }

//          })

//       }


   </script>
</body>
</html>