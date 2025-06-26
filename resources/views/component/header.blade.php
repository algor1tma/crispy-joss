  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

      <div class="d-flex align-items-center justify-content-between">
          <i class="bi bi-list toggle-sidebar-btn"></i>
          {{-- <a href="{{ route('indexDashboard') }}" class="logo d-flex align-items-center  mx-3">
              <img src="{{ asset('img/DataLogo/' . $sistemData->logo) }}" alt="Logo" style="max-width: 200px;">
              <span class="d-none d-lg-block">{{ $sistemData->nama }}</span>
          </a> --}}
      </div><!-- End Logo -->

      <nav class="header-nav ms-auto">
          <ul class="d-flex align-items-center">

              <li class="nav-item dropdown pe-3">

                  <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                      @if (auth()->user()->roles === 'karyawan')
                          @if (auth()->user()->karyawan && auth()->user()->karyawan->foto)
                              <img src="{{ asset('img/DataKaryawan/' . auth()->user()->karyawan->foto) }}"
                                  alt="Profile" class="profile-photo">
                          @else
                              <img src="{{ asset('img\default_image.jpg') }}" alt="Profile" class="profile-photo" >
                          @endif
                      @else
                          {{-- <img src="{{ asset('img/default-profile.png') }}" alt="Profile" class="profile-photo"> --}}
                      @endif
                      <span class="d-none d-md-block dropdown-toggle ps-2">
                          @if (auth()->user()->roles === 'super')
                              {{ auth()->user()->super?->nama ?? 'Super Admin' }}
                          @elseif (auth()->user()->roles === 'admin')
                              {{ auth()->user()->admin?->nama ?? 'Admin' }}
                          @elseif (auth()->user()->roles === 'karyawan')
                              {{ auth()->user()->karyawan?->nama ?? 'Karyawan' }}
                          @endif
                      </span>
                  </a>

                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                      <li class="dropdown-header">
                          @if (auth()->user()->roles === 'super')
                              <h6>{{ auth()->user()->super?->nama ?? 'Super Admin' }}</h6>
                              <span>{{ ucfirst(auth()->user()->roles) }}</span>
                          @elseif (auth()->user()->roles === 'admin')
                              <h6>{{ auth()->user()->admin?->nama ?? 'Admin' }}</h6>
                              <span>{{ ucfirst(auth()->user()->roles) }}</span>
                          @elseif (auth()->user()->roles === 'karyawan')
                              <h6>{{ auth()->user()->karyawan?->nama ?? 'Karyawan' }}</h6>
                              <span>{{ ucfirst(auth()->user()->roles) }}</span>
                          @endif
                      </li>
                      <li>
                          <hr class="dropdown-divider">
                      </li>

                      <li>
                          <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.edit') }}">
                              <i class="bi bi-person"></i>
                              <span>Edit Profile</span>
                          </a>
                      </li>

                      <li>
                          <hr class="dropdown-divider">
                      </li>

                      <li>
                          <form action="{{ route('outAuth') }}" method="post">
                              @csrf
                              <button type="submit" class="dropdown-item d-flex align-items-center"> <i
                                      class="bi bi-box-arrow-right"></i>
                                  <span>Logout</span></button>
                          </form>
                      </li>

                  </ul><!-- End Profile Dropdown Items -->
              </li><!-- End Profile Nav -->

          </ul>
      </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->
