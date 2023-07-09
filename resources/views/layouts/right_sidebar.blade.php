<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="user-pro" data-turbolinks="false">
                    <a class="has-arrow waves-effect waves-dark" data-turbolinks="true" href="javascript:void(0)" aria-expanded="false"><img src="../assets/images/users/1.jpg" alt="user-img" class="img-circle mx-1"><span class="hide-menu">{{ \Illuminate\Support\Facades\Auth::user()->name }}</span></a>
                    <ul aria-expanded="false" class="collapse">
{{--                        <li><a href="javascript:void(0)"><i class="ti-user"></i> بروفايلي</a></li>--}}
                        <li><a href="/user_profile" data-turbolinks="true"><i class="ti-settings" data-turbolinks="true"></i> الاعدادات</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}" data-turbolinks="true" onclick="event.preventDefault();this.closest('form').submit(); ">
                                    <i class="fa fa-power-off"></i> تسجيل خروج
                                </a>
                            </form>
                        </li>
                    </ul>
                </li>


                <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="ti-layout-grid2"></i><span class="hide-menu">اليومية</span></a>
                    <ul aria-expanded="false" class="collapse" data-turbolinks="false">

                        <li><a href="/financial_activities" data-turbolinks="true">كشف حساب</a></li>
                        <li><a href="/financial_activities/create" data-turbolinks="true">إضافة جديد</a></li>

                    </ul>
                </li>
                <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="ti-layout-grid2"></i><span class="hide-menu">بيانات المخزون</span></a>
                    <ul aria-expanded="false" class="collapse" data-turbolinks="false">

                        <li><a href="/products" data-turbolinks="true">المتاح</a></li>
                        <li><a href="/products/create" data-turbolinks="true">إضافة </a></li>
                        <li><a href="/products_archive" data-turbolinks="true">ارشيف </a></li>
                        <li><a href="/few_products" data-turbolinks="true">نواقص </a></li>
                        <li> <a href="/products_logs/{{ date("Y-m") }}" data-turbolinks="true">سجل التعديلات</a></li>
                    </ul>
                </li>
                <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="ti-layout-grid2"></i><span class="hide-menu">بيانات الصيانة</span></a>
                    <ul aria-expanded="false" class="collapse" data-turbolinks="false">

                        <li><a href="/service_repairs" data-turbolinks="true">لم يتم التسليم</a></li>
                        <li><a href="/service_repairs/create" data-turbolinks="true">إضافة</a></li>

                        <li><a href="/done_repair/{{ date("Y-m") }}" data-turbolinks="true">تم</a></li>

                    </ul>
                </li>

                @can("isAdmin")
                    <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="ti-layout-grid2"></i><span class="hide-menu">تقارير شهرية</span></a>
                        <ul aria-expanded="false" class="collapse" data-turbolinks="false">

                            <li><a href="/sells_report/{{ date("Y-m") }}" data-turbolinks="true">مبيعات</a></li>
                            <li><a href="/service_report/{{ date("Y-m") }}" data-turbolinks="true">صيانة</a></li>

                        </ul>
                    </li>
                @endcan

                <li> <a class="waves-effect waves-dark" href="/clients" aria-expanded="false"><i class="fas fa-users"></i><span class="hide-menu">عملائي</span></a></li>

                <li> <a class="waves-effect waves-dark" href="/losses/{{ date("Y-m") }}" aria-expanded="false"><i class="fas fa-dollar-sign"></i><span class="hide-menu">الخسائر</span></a></li>

            @can("isAdmin")

                    <li> <a class="waves-effect waves-dark" href="/brands" aria-expanded="false"><i class="ti-layout-grid2"></i><span class="hide-menu">الماركات</span></a></li>
                    <li> <a class="waves-effect waves-dark" href="/categories" aria-expanded="false"><i class="ti-layout-grid2"></i><span class="hide-menu">الأقسام</span></a></li>
                    <li> <a class="waves-effect waves-dark" href="/vendors" aria-expanded="false"><i class="fas fa-handshake"></i><span class="hide-menu">التجار</span></a></li>
                    <li> <a class="waves-effect waves-dark" href="/accounts_types" aria-expanded="false"><i class="fas fa-address-book"></i><span class="hide-menu">الحسابات</span></a></li>
                    <li> <a class="waves-effect waves-dark" href="/users" aria-expanded="false"><i class="fas fa-users"></i><span class="hide-menu">المستخدمون</span></a></li>
                @endcan


                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="waves-effect waves-dark"  aria-expanded="false" href="{{ route('logout') }}" onclick="event.preventDefault();this.closest('form').submit(); " >
                        <i class="far fa-circle text-success"></i>
                            <span class="hide-menu">تسجيل خروج</span>
                        </a>
                    </form>
                </li>
{{--                <li> <a class="waves-effect waves-dark" href="#!" aria-expanded="false"><i class="far fa-circle text-info"></i><span class="hide-menu">FAQs</span></a></li>--}}
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
