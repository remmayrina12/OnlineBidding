<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Custom fonts for this template-->
    <link href="{{ url('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ url('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <link rel="icon" href="/path-to-your-favicon/favicon.ico">

    <link href="https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.css" rel="stylesheet" />

    <script src="https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.js"></script>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

<style>
    #alertsDropdown {
        position: relative;
    }

    /* Max height and scroll for dropdown */
    .dropdown-menu {
        max-height: 300px;
        overflow-y: auto;
        width: 350px; /* Adjust the width to fit the message */
    }

    /* Optionally, adjust the arrow or positioning */
    .dropdown-menu-end {
        left: auto !important;

    }
    /* Notification Icon Circle */
    .icon-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Badge for unread notifications */
    .badge {
        position: absolute;
        top: 0px;
        right: -5px;
        font-size: 0.75rem;
    }

    /* Dividers between notification items */
    .dropdown-divider {
        border-color: #ddd;
    }

    /* Centered "No new notifications" text */
    .text-muted {
        font-size: 14px;
    }

    /* Mobile responsive adjustments */
    @media (max-width: 768px) {
        .dropdown-menu {
            width: 100%;
        }
    }
    #custom-alert {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 10000;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        background-color: #fff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    #custom-alert h3 {
        margin: 0 0 10px;
    }

    #custom-alert p {
        margin: 0 0 20px;
    }

    #custom-alert-ok {
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    #custom-alert-ok:hover {
        background-color: #0056b3;
    }
</style>
</head>

<body>
    <div id="app">
        <body id="page-top">

            <!-- Page Wrapper -->
            <div id="wrapper">

                <!-- Sidebar -->
                <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

                    <!-- Sidebar - Brand -->
                    <a class="sidebar-brand d-flex align-items-center justify-content-center" style="display: flex; align-items: center; justify-content: center;">
                        <div class="sidebar-brand-icon rotate-n-15" style="margin-right: 1px; transform: rotate(0deg);">
                            <img src="{{ url('assets/panibagong logo eyyy.png')}}" style="width: 50px; height: 50px;">
                        </div>
                        @if (Auth::check() && Auth::user()->role == 'admin')
                            <div class="sidebar-brand-text mx-3">Admin</div>
                        @elseif (Auth::check() && Auth::user()->role == 'admin2')
                            <div class="sidebar-brand-text mx-3">Admin2</div>
                        @elseif (Auth::check() && Auth::user()->role == 'auctioneer')
                            <div class="sidebar-brand-text mx-3">Auctioneer</div>
                        @else
                            <div class="sidebar-brand-text mx-3">Bidder</div>
                        @endif
                    </a>

                    <!-- Divider -->
                    <hr class="sidebar-divider my-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.show', Auth::user()->id) }}">
                            <i class="fas fa-fw fa-tachometer-alt"></i>
                            <span>View Profile</span></a>
                    </li>

                    <!-- Auctioneer -->
                    @if (Auth::check() && Auth::user()->role == 'auctioneer')

                        <hr class="sidebar-divider">

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home.show') }}">
                                <i class="fas fa-fw fa-tachometer-alt"></i>
                                <span>Home</span></a>
                        </li>

                        <!-- Divider -->
                        <hr class="sidebar-divider">

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('auctioneer.create') }}">
                                <i class="fas fa-fw fa-tachometer-alt"></i>
                                <span>Create Product</span></a>
                        </li>

                        <!-- Divider -->
                        <hr class="sidebar-divider">

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('auctioneer.index') }}">
                                <i class="fas fa-fw fa-tachometer-alt"></i>
                                <span>Index</span></a>
                        </li>

                        <!-- Divider -->
                        <hr class="sidebar-divider">

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('auctioneer.archived') }}">
                                <i class="fas fa-fw fa-tachometer-alt"></i>
                                <span>Archived</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('notifications.index') }}">
                                <i class="fas fa-fw fa-tachometer-alt"></i>
                                <span>Your Notifications</span></a>
                        </li>
                    @endif

                    <!-- Bidder -->
                    @if (Auth::check() && Auth::user()->role == 'bidder')

                        <hr class="sidebar-divider">

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home.show') }}">
                                <i class="fas fa-fw fa-tachometer-alt"></i>
                                <span>Home</span></a>
                        </li>

                        <!-- Divider -->
                        <hr class="sidebar-divider">

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('bidder.show') }}">
                                <i class="fas fa-fw fa-tachometer-alt"></i>
                                <span>Show auction bid</span></a>
                        </li>

                        <!-- Divider -->
                        <hr class="sidebar-divider">

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('bidder.showAuctionWin') }}">
                                <i class="fas fa-fw fa-tachometer-alt"></i>
                                <span>Auction Won</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('notifications.index') }}">
                                <i class="fas fa-fw fa-tachometer-alt"></i>
                                <span>Your Notifications</span></a>
                        </li>
                    @endif

                    <!-- Admin -->
                    @if (Auth::check() && Auth::user()->role == 'admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home.show') }}">
                                <i class="fas fa-fw fa-tachometer-alt"></i>
                                <span>Home</span></a>
                        </li>

                        <!-- Divider -->
                        <hr class="sidebar-divider">

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.manageProduct') }}">
                                <i class="fas fa-fw fa-tachometer-alt"></i>
                                <span>Manage Product</span></a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseManageUsers"
                                aria-expanded="true" aria-controls="collapseManageUsers">
                                <i class="fas fa-fw fa-cog"></i>
                                <span>Manage Users</span>
                            </a>
                            <div id="collapseManageUsers" class="collapse" aria-labelledby="headingManageUsers" data-parent="#accordionSidebar">
                                <div class="bg-white py-2 collapse-inner rounded">
                                    <h6 class="collapse-header">User:</h6>
                                    <a class="collapse-item" href="{{ route('admin.auctioneerIndex') }}">Auctioneer</a>
                                    <a class="collapse-item" href="{{ route('admin.bidderIndex') }}">Bidder</a>
                                </div>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReports"
                                aria-expanded="true" aria-controls="collapseReports">
                                <i class="fas fa-fw fa-cog"></i>
                                <span>Reports</span>
                            </a>
                            <div id="collapseReports" class="collapse" aria-labelledby="headingReports" data-parent="#accordionSidebar">
                                <div class="bg-white py-2 collapse-inner rounded">
                                    <h6 class="collapse-header">Reports:</h6>
                                    <a class="collapse-item" href="{{ route('reportIndex.index') }}">Manage Reports</a>
                                    <a class="collapse-item" href="{{ route('reportForListOfWinningBid.getTopRanks') }}">List Of Winning Bid</a>
                                    <a class="collapse-item" href="{{ route('reportForTopBidder.getTopBidders') }}">Top Bidder</a>
                                    <a class="collapse-item" href="{{ route('reportForTopSeller.getTopSellers') }}">Top Seller</a>
                                </div>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('notifications.index') }}">
                                <i class="fas fa-fw fa-tachometer-alt"></i>
                                <span>Your Notifications</span></a>
                        </li>
                    @endif

                    {{-- Admin2 --}}
                    @if (Auth::check() && Auth::user()->role == 'admin2')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home.show') }}">
                                <i class="fas fa-fw fa-tachometer-alt"></i>
                                <span>Home</span></a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReports"
                                aria-expanded="true" aria-controls="collapseReports">
                                <i class="fas fa-fw fa-cog"></i>
                                <span>Reports</span>
                            </a>
                            <div id="collapseReports" class="collapse" aria-labelledby="headingReports" data-parent="#accordionSidebar">
                                <div class="bg-white py-2 collapse-inner rounded">
                                    <h6 class="collapse-header">Reports:</h6>
                                    <a class="collapse-item" href="{{ route('reportForListOfWinningBid.getTopRanks') }}">List Of Winning Bid</a>
                                    <a class="collapse-item" href="{{ route('reportForTopBidder.getTopBidders') }}">Top Bidder</a>
                                    <a class="collapse-item" href="{{ route('reportForTopSeller.getTopSellers') }}">Top Seller</a>
                                </div>
                            </div>
                        </li>
                    @endif

                    <!-- Divider -->
                    <hr class="sidebar-divider d-none d-md-block">

                    <!-- Sidebar Toggler (Sidebar) -->
                    <div class="text-center d-none d-md-inline">
                        <button class="rounded-circle border-0" id="sidebarToggle"></button>
                    </div>

                </ul>
                <!-- End of Sidebar -->

                <!-- Content Wrapper -->
                <div id="content-wrapper" class="d-flex flex-column">

                    <!-- Main Content -->
                    <div id="content">

                        <!-- Topbar -->
                        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                            <!-- Sidebar Toggle (Topbar) -->
                            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                                <i class="fa fa-bars"></i>
                            </button>

                            <!-- Topbar Navbar -->
                            <ul class="navbar-nav ml-auto">

                                <!-- Nav Item - Notification alerts -->
                                <li class="nav-item dropdown no-arrow mx-1">
                                    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-bell fa-fw"></i>
                                        <!-- Counter - Alerts -->
                                        <span class="badge bg-danger">{{ Auth::user()->unreadNotifications->count() }}</span>
                                    </a>

                                    <ul class="dropdown-menu dropdown-menu-end shadow animated--grow-in"
                                        aria-labelledby="alertsDropdown">
                                        @if(Auth::user()->unreadNotifications->isEmpty())
                                            <li class="text-center p-2">
                                                <span class="text-muted">No new notifications</span>
                                            </li>
                                        @else
                                            @foreach(Auth::user()->unreadNotifications as $notification)
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center"
                                                    href="{{ route('notifications.markAsRead', $notification->id) }}">
                                                        <div class="me-3">
                                                            <div class="icon-circle bg-primary">
                                                                <i class="fas fa-info text-white"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <span class="font-weight-bold">{{ $notification->data['message'] }}</span>
                                                            <div class="small text-gray-500">{{ $notification->created_at->diffForHumans() }}</div>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </li>

                                <div class="topbar-divider d-none d-sm-block"></div>

                                <!-- Nav Item - User Information -->
                                    <li class="nav-item dropdown no-arrow">
                                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>

                                                @if(optional(Auth::user()->info)->profile_picture)
                                                    <img src="{{ asset('storage/' . Auth::user()->info->profile_picture) }}" class="img-profile rounded-circle" alt="Profile Picture">
                                                @else
                                                    <img src="{{ asset('assets/—Pngtree—vector add user icon_4101348.png') }}" class="img-profile rounded-circle" alt="Default Picture">
                                                @endif

                                    <!-- Dropdown - User Information -->
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                        aria-labelledby="userDropdown">
                                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                            Profile
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                            Logout
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            </ul>

                        </nav>
                        <!-- End of Topbar -->

                        <!-- Begin Page Content -->
                        <div class="container-fluid">

                            <main class="py-4">
                                @yield('content')
                            </main>

                        </div>
                        <!-- /.container-fluid -->

                    </div>
                    <!-- End of Main Content -->


                </div>
                <!-- End of Content Wrapper -->

            </div>
            <!-- End of Page Wrapper -->

            <!-- Scroll to Top Button-->
            <a class="scroll-to-top rounded" href="#page-top">
                <i class="fas fa-angle-up"></i>
            </a>

            <!-- Logout Modal-->
            <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                            <a class="btn btn-primary" href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ url('assets/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{ url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ url('assets/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ url('assets/js/sb-admin-2.min.js') }}"></script>

    {{-- countdown script --}}
    <script src="{{ asset('js/countdown.js') }}"></script>
    <!-- Make sure you include jQuery and Bootstrap's JavaScript (for Bootstrap 4/5) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Add this script for dropdown toggle on mobile -->
    <script>
            $(document).ready(function() {
        // Toggle dropdown visibility when clicked
        $('#alertsDropdown').on('click', function(e) {
            e.stopPropagation();  // Prevents event from propagating to other elements
            $(this).next('.dropdown-menu').toggleClass('show'); // Toggle visibility
        });

        // Close the dropdown if the user clicks outside of it
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#alertsDropdown').length) {
                $('#alertsDropdown').next('.dropdown-menu').removeClass('show'); // Close dropdown
            }
        });
    });
    </script>

    <!-- Custom Alert Container -->
    <div id="custom-alert" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10000; padding: 20px; border-radius: 10px; text-align: center; background-color: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h3 id="custom-alert-title" style="margin: 0 0 10px;"></h3>
        <p id="custom-alert-message" style="margin: 0 0 20px;"></p>
        <button id="custom-alert-ok" style="padding: 10px 20px; background-color: #007bff; color: #fff; border: none; border-radius: 5px; cursor: pointer;">OK</button>
    </div>

    <!-- JavaScript for Custom Alert -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @if(session()->has('alert'))
                const alertData = @json(session('alert'));

                // Get alert elements
                const alertBox = document.getElementById('custom-alert');
                const alertTitle = document.getElementById('custom-alert-title');
                const alertMessage = document.getElementById('custom-alert-message');
                const alertOkButton = document.getElementById('custom-alert-ok');

                // Set alert content
                alertTitle.textContent = alertData.type === 'success' ? 'Success!' : (alertData.type === 'error' ? 'Error!' : 'Warning!');
                alertMessage.textContent = alertData.message;

                // Set alert styles based on type
                alertBox.style.display = 'block';
                alertBox.style.border = `2px solid ${alertData.type === 'success' ? '#28a745' : (alertData.type === 'error' ? '#dc3545' : '#ffc107')}`;

                // Close alert on clicking "OK"
                alertOkButton.addEventListener('click', () => {
                    alertBox.style.display = 'none';
                });
            @endif
        });
    </script>

    </body>
</html>
