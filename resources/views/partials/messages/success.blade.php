@if (session('success'))
<div class="container my-2">
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <p><strong>{{ session('success') }}</strong></p>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
</div>
@endif
