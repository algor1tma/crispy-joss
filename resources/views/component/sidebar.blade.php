  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

      <ul class="sidebar-nav" id="sidebar-nav">

          @if (auth()->user()->roles === 'admin')
              <li class="nav-item">
                  <a class="nav-link {{ Request::is('dashboard*') ? '' : 'collapsed' }}"
                      href="{{ route('indexDashboard') }}">
                      <i class="bi bi-grid"></i>
                      <span>Dashboard</span>
                  </a>
              </li><!-- End Dashboard Nav -->

              <li class="nav-item">
                  {{-- <a class="nav-link {{ Request::is('pengguna*') || Request::is('karyawan*') || Request::is('pelanggan*') ? '' : 'collapsed' }}"
                      data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                      <i class="bi bi-database"></i><span>Manajemen</span><i class="bi bi-chevron-down ms-auto"></i>
                  </a> --}}
                  {{-- <ul id="components-nav"
                      class="nav-content collapse {{ Request::is('pengguna*') || Request::is('karyawan*') || Request::is('pelanggan*') ? 'show' : '' }}"
                      data-bs-parent="#sidebar-nav">
                      <li>
                          <a href="{{ route('indexKaryawan') }}"
                              class="{{ Request::is('karyawan*') ? 'text-primary' : '' }}">
                              <i class="bi bi-person" style="font-size: 1.2rem;">
                                </i><span>karyawan</span>
                          </a>
                      </li>
                  </ul> --}}
              </li><!-- End Components Nav -->
              <li class="nav-item">
                <a class="nav-link {{ Request::is('Karyawan*') ? '' : 'collapsed' }}"
                    href="{{ route('indexKaryawan') }}">
                    <i class="bi bi-person"></i>
                    <span>karyawan</span>
                </a>
            </li>


              <li class="nav-item">
                  <a class="nav-link {{ Request::is('raw-materials*') || Request::is('low-stock-materials*') ? '' : 'collapsed' }}"
                      data-bs-target="#raw-materials-nav" data-bs-toggle="collapse" href="#">
                      <i class="bi bi-boxes"></i><span>Bahan Baku</span><i class="bi bi-chevron-down ms-auto"></i>
                  </a>
                  <ul id="raw-materials-nav"
                      class="nav-content collapse {{ Request::is('raw-materials*') || Request::is('low-stock-materials*') ? 'show' : '' }}"
                      data-bs-parent="#sidebar-nav">
                      <li>
                          <a href="{{ route('raw-materials.index') }}"
                              class="{{ Request::is('raw-materials') ? 'text-primary' : '' }}">
                              <i class=""></i><span>Daftar Bahan</span>
                          </a>
                      </li>
                      <li>
                        <a href="{{ route('raw-materials.purchase') }}"
                            class="{{ Request::is('raw-materials/purchase') ? 'text-primary' : '' }}">
                            <i class=""></i><span>Pembelian</span>
                        </a>
                    </li>
                    @if(auth()->user()->roles === 'admin')
                      <li>
                          <a href="{{ route('raw-materials.low-stock') }}"
                              class="{{ Request::is('low-stock-materials') ? 'text-primary' : '' }}">
                              <i class=""></i><span>Stok Menipis</span>
                          </a>
                      </li>
                      
                      <li>
                          <a href="{{ route('raw-materials.report') }}"
                              class="{{ Request::is('raw-materials-report') ? 'text-primary' : '' }}">
                              <i class=""></i><span>Laporan</span>
                          </a>
                      </li>
                      @endif
                  </ul>
              </li>

              {{-- <li class="nav-item">
                  <a class="nav-link {{ Request::is('transaksi*') ? '' : 'collapsed' }}"
                      href="{{ route('transaksi.index') }}">
                      <i class="bi bi-coin"></i>
                      <span>Transaksi</span>
                  </a>
              </li> --}}

              <li class="nav-item">
                <a class="nav-link {{ Request::is('produk*') ? '' : 'collapsed' }}"
                    href="{{ route('produk.index') }}">
                    <i class="bi bi-box"></i>
                    <span>Produk</span>
                </a>
            </li>


              <li class="nav-item">
                  <a class="nav-link {{ Request::is('pos*') ? '' : 'collapsed' }}"
                      href="{{ route('pos.index') }}">
                      <i class="bi bi-cart"></i>
                      <span>Point of Sale</span>
                  </a>
              </li>

              <li class="nav-item">
                  <a class="nav-link {{ Request::is('laporan-penjualan*') ? '' : 'collapsed' }}"
                      href="{{ route('laporanpenjualan.index') }}">
                      <i class="bi bi-file-earmark-text"></i>
                      <span>Laporan Penjualan</span>
                  </a>
              </li>
              
              <!-- End Dashboard Nav -->

          @elseif (auth()->user()->roles === 'karyawan')
              <li class="nav-item">
                  <a class="nav-link {{ Request::is('dashboard*') ? '' : 'collapsed' }}"
                      href="{{ route('indexDashboard') }}">
                      <i class="bi bi-grid"></i>
                      <span>Dashboard</span>
                  </a>
              </li>

              <li class="nav-item">
                  <a class="nav-link {{ Request::is('produk*') ? '' : 'collapsed' }}"
                      href="{{ route('produk.index') }}">
                      <i class="bi bi-box"></i>
                      <span>Produk</span>
                  </a>
              </li>

              <li class="nav-item">
                  <a class="nav-link {{ Request::is('raw-materials*') || Request::is('low-stock-materials*') ? '' : 'collapsed' }}"
                      data-bs-target="#raw-materials-nav" data-bs-toggle="collapse" href="#">
                      <i class="bi bi-boxes"></i><span>Bahan Baku</span><i class="bi bi-chevron-down ms-auto"></i>
                  </a>
                  <ul id="raw-materials-nav"
                      class="nav-content collapse {{ Request::is('raw-materials*') || Request::is('low-stock-materials*') ? 'show' : '' }}"
                      data-bs-parent="#sidebar-nav">
                      <li>
                          <a href="{{ route('raw-materials.index') }}"
                              class="{{ Request::is('raw-materials') ? 'text-primary' : '' }}">
                              <i class="bi bi-list-ul"></i><span>Daftar Bahan</span>
                          </a>
                      </li>
                      <li>
                          <a href="{{ route('raw-materials.low-stock') }}"
                              class="{{ Request::is('low-stock-materials') ? 'text-primary' : '' }}">
                              <i class="bi bi-exclamation-triangle"></i><span>Stok Menipis</span>
                          </a>
                      </li>
                      <li>
                          <a href="{{ route('raw-materials.purchase') }}"
                              class="{{ Request::is('raw-materials/purchase') ? 'text-primary' : '' }}">
                              <i class="bi bi-cart-plus"></i><span>Pembelian</span>
                          </a>
                      </li>
                  </ul>
              </li>

              <li class="nav-item">
                  <a class="nav-link {{ Request::is('pos*') ? '' : 'collapsed' }}"
                      href="{{ route('pos.index') }}">
                      <i class="bi bi-cart"></i>
                      <span>Point of Sale</span>
                  </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ Request::is('laporan-penjualan*') ? '' : 'collapsed' }}"
                    href="{{ route('laporanpenjualan.index') }}">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan Penjualan</span>
                </a>
            </li>

          @endif
      </ul>

  </aside><!-- End Sidebar-->
