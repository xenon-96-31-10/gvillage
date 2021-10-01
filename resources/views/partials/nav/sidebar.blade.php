<div class="container my-2">
  <div class="row row-cols-2 row-cols-sm-3 g-4">
    @if(auth()->user()->hasPermissionTo($permissions->where('slug', 'control-profile')->first()))
      @include('partials.menuItems.controlProfile')
    @endif
    @if(auth()->user()->hasPermissionTo($permissions->where('slug', 'control-profiles')->first()))
      @include('partials.menuItems.controlProfiles')
    @endif
    @if(auth()->user()->hasPermissionTo($permissions->where('slug', 'view-profiles')->first()))
      @include('partials.menuItems.viewProfiles')
    @endif
    @if(auth()->user()->hasPermissionTo($permissions->where('slug', 'control-pass-rate-plan')->first()))
      @include('partials.menuItems.controlPassRatePlan')
    @endif
    @if(auth()->user()->hasPermissionTo($permissions->where('slug', 'control-project-groups')->first()))
      @include('partials.menuItems.controlProjectGroups')
    @endif
    @if(auth()->user()->hasPermissionTo($permissions->where('slug', 'control-projects')->first()))
      @include('partials.menuItems.controlProjects')
    @endif
    @if(auth()->user()->hasPermissionTo($permissions->where('slug', 'control-guardposts')->first()))
      @include('partials.menuItems.controlGuardPosts')
    @endif
    @if(auth()->user()->hasPermissionTo($permissions->where('slug', 'view-guardposts')->first()))
      @include('partials.menuItems.viewGuardPosts')
    @endif
    @if(auth()->user()->hasPermissionTo($permissions->where('slug', 'control-passes')->first()))
      @include('partials.menuItems.controlPasses')
    @endif
    @if(auth()->user()->hasPermissionTo($permissions->where('slug', 'view-passes')->first()))
      @include('partials.menuItems.viewPasses')
    @endif
    @if(auth()->user()->hasPermissionTo($permissions->where('slug', 'create-temporary-pass')->first()))
      @include('partials.menuItems.createTemporaryPass')
    @endif
    @if(auth()->user()->hasPermissionTo($permissions->where('slug', 'create-permanent-pass')->first()))
      @include('partials.menuItems.createPermanentPass')
    @endif
    @if(auth()->user()->hasPermissionTo($permissions->where('slug', 'control-cars')->first()))
      @include('partials.menuItems.controlCars')
    @endif
    @if(auth()->user()->hasPermissionTo($permissions->where('slug', 'control-mechanizms')->first()))
      @include('partials.menuItems.controlMechanizms')
    @endif

  </div>
</div>
