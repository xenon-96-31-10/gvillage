@if (session('danger'))
<div class="container my-2">
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <p><strong>{{ session('danger') }}</strong></p>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
</div>
@endif
