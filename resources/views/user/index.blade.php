<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

</head>
<body>  
 

  <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-10">
            <div class="d-flex justify-content-end">
                <a href="{{ route("user.create") }}"  class="btn btn-primary">Add New</a>
            </div>
            @if (Session("msg"))
                <div class="alert alert-primary">
                    <p text-success>{{ Session("msg") }}</p>
                </div>
            @endif
            <table class="table table-stripped mt-5">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Image</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                    @forelse ($users as $key => $user)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <img src="{{ "images/".$user->image }}" width="50px" alt="">
                            </td>
                            <td>
                                <td><a href="{{ route("user.edit",$user->id) }}" class="btn btn-primary">Edit</a></td>
                                <td><a href="javascript:void(0)" onclick="delete_fun({{ $user->id }})" class="btn btn-danger">Delete</a></td>
                            </td>
                         </tr>
                    @empty
                        <tr>
                            <td>Not Found</td>
                        </tr>
                    @endforelse
                </tbody>
              </table>
        </div>
    </div>
  </div>
  

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

      <script>
        function delete_fun(id){
            Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url:'{{ route("user.delete") }}',
                    method:'POST',
                    dataType:'json',
                    data:{id:id},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(response){
                        if (response.status) {
                              Swal.fire({
                            title: "Deleted!",
                            text: response.msg,
                            icon: "success"
                            });
                            window.location.href="{{ route('users') }}";
                        }
                    },
                });
            }
        });
        }
      </script>
</body>
</html>