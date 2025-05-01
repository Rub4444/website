@extends('auth.layouts.master')

@section('title', 'Купоны')

@section('content')
<div class="col-md-12">

   <!-- Add Coupon Button -->
   <div class="mt-4">
        <a class="btn btn-success" href="{{ route('coupons.create') }}">
            <i class="fas fa-plus text-white"></i> Ավելացնել կուպոն
        </a>
    </div>
   <!-- Coupons Table -->
   <div class="card shadow-lg">
       <div class="card-body">
           <table class="table table-striped table-hover">
               <thead>
                   <tr>
                       <th>#</th>
                       <th>Կոդ</th>
                       <th>Նկարագրություն</th>
                       <th>Գործողություններ</th>
                   </tr>
               </thead>
               <tbody>
                   @foreach($coupons as $coupon)
                   <tr>
                       <td>{{ $coupon->id }}</td>
                       <td>{{ $coupon->code }}</td>
                       <td>{{ $coupon->description }}</td>
                       <td>
                           <div class="btn-group" role="group">
                                <!-- Open Button -->
                                <a class="btn btn-success" href="{{ route('coupons.show', $coupon) }}" data-toggle="tooltip" title="Բացել">
                                    <i class="fas fa-eye text-white"></i> Բացել
                                </a>

                                <!-- Edit Button -->
                                <a class="btn btn-warning" href="{{ route('coupons.edit', $coupon) }}" data-toggle="tooltip" title="Փոփոխել">
                                    <i class="fas fa-edit text-white"></i> Փոփոխել
                                </a>

                                <!-- Delete Button -->
                                <form action="{{ route('coupons.destroy', $coupon) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input class="btn btn-danger" type="submit" value="Հեռացնել" data-toggle="tooltip" title="Հեռացնել">
                                </form>
                           </div>
                       </td>
                   </tr>
                   @endforeach
               </tbody>
           </table>
       </div>

       <!-- Custom Pagination -->
       <div class="card-footer text-center">
           <nav aria-label="Page navigation example">
               <ul class="pagination justify-content-center">
                   <!-- Previous Button -->
                   <li class="page-item {{ $coupons->onFirstPage() ? 'disabled' : '' }}">
                       <a class="page-link" href="{{ $coupons->previousPageUrl() }}" aria-label="Previous">
                           <span aria-hidden="true">&laquo;</span>
                       </a>
                   </li>

                   <!-- Page Numbers -->
                   @foreach ($coupons->getUrlRange(1, $coupons->lastPage()) as $page => $url)
                       <li class="page-item {{ $page == $coupons->currentPage() ? 'active' : '' }}">
                           <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                       </li>
                   @endforeach

                   <!-- Next Button -->
                   <li class="page-item {{ $coupons->hasMorePages() ? '' : 'disabled' }}">
                       <a class="page-link" href="{{ $coupons->nextPageUrl() }}" aria-label="Next">
                           <span aria-hidden="true">&raquo;</span>
                       </a>
                   </li>
               </ul>
           </nav>
       </div>
   </div>
</div>
@endsection
