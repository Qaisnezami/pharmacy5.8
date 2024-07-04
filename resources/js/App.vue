<template>
    <div class="locale-changer">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light" >
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- SEARCH FORM -->
            <form class="form-inline ml-3">
                <div class="input-group input-group-sm">
                    <input v-model="searchData" class="form-control form-control-navbar" @keyup="search" type="search" placeholder="Search"
                        aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-navbar" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
            <ul class="navbar-nav ml-auto mr-3">
                <li class="nav-item mt-1"  @click="FullScreen">
                    <i class="fa fa-expand" style="margin: 9px 9px 0px 0px;font-size: 19px;color: #7b7c7d;"></i>
                </li>
                <li class="nav-item dropdown mt-1">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell" style="font-size: 19px;"></i>
                        <span v-if="unreadNotificationsCount != 0" class="badge badge-danger navbar-badge" style="margin: -9px -5px;">{{unreadNotificationsCount}}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="width: 500px;height: 400px;overflow-y:scroll;">
                        <h1 class="dropdown-header">{{unreadNotificationsCount}} Notifications</h1>
                        <div v-for="(unreadNotification) in unreadNotifications" :key="unreadNotification.id">
                        <div class="dropdown-divider"></div>
                        <router-link to="/stock/expiry" class="dropdown-item bg-new"  style="background-color:#75d2ff8f;padding: 1.5rem 1rem;width: 298px" >
                             <p>{{unreadNotification.data['data']}} " {{unreadNotification.data['batch']}} " {{unreadNotification.data['message']}}</p>
                            <span class="float-right text-muted text-sm">{{unreadNotification.created_at | myDate}}</span>
                        </router-link>
                        </div>
                        <div v-for="(readNotification) in readNotifications" :key="readNotification.id">
                        <div class="dropdown-divider"></div>
                        <router-link to="/stock/expiry" class="dropdown-item text-dark" style="background-color:white;padding: 1.5rem 1rem;width: 298px" >
                             <p class="text-dark">{{readNotification.data['data']}} " {{readNotification.data['batch']}} " {{readNotification.data['message']}}</p>
                            <span class="float-right text-muted text-sm">{{readNotification.created_at | myDate}}</span>
                        </router-link>
                        </div>
                        <div class="dropdown-divider"></div>
                        <router-link to="/stock/expiry" class="dropdown-item dropdown-footer text-center text-dark">See All Notifications</router-link>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a id="dropdownSubMenu1" href="#" style="width: 41px;" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false" class="nav-link dropdown-toggle"><img :src="langIcon" alt="language"></a>
                    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                        <li><a @click.prevent="english" class="dropdown-item"><img :src="asset+'/logo/uk.svg'"
                                    width="20%"> </a></li>
                        <li><a @click.prevent="persion" class="dropdown-item"><img :src="asset+'/logo/afghanistan.svg'"
                                    width="20%"></a></li>

                    </ul>
                </li>
            </ul>



        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="/dashboard" class="brand-link">
                <img :src="pharmacyIcon" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">{{CompanyName}}</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img :src="photo" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">{{name}}</a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

                        <li class="nav-item" v-if="$gate.isDashboard() && $gate.Read()">
                            <router-link to="/dashboard" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    {{ $t('dashboard') }}
                                </p>
                            </router-link>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa fa-edit"></i>
                                <p>
                                    {{ $t('master')}}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item" v-if="$gate.isMedicines() && $gate.Read()">
                                    <router-link to="/medicine" class="nav-link" v-shortkey="['ctrl', '5']" @shortkey.native="loadMedicine">
                                        <i class="nav-icon 	fas fa-capsules"></i>
                                        <p>
                                            {{$t('add-medicine')}}
                                        

                                            <span class="right">Ctrl+5</span>
                                        </p>
                                    </router-link>
                                </li>
                                <li class="nav-item" v-if="$gate.isMedicineGroup() && $gate.Read()">
                                    <router-link to="/groups" class="nav-link">
                                        <i class="nav-icon fa fa-th-list"></i>
                                        <p>
                                            {{$t('group')}}
                                        </p>
                                    </router-link>
                                </li>
                                <li class="nav-item" v-if="$gate.isManufacturer() && $gate.Read()">
                                    <router-link to="/manufacturer" class="nav-link">
                                        <i class="nav-icon fa fa-industry"></i>
                                        <p>
                                            {{$t('manufacturer')}}
                                        </p>
                                    </router-link>
                                </li>
                                <li class="nav-item" v-if="$gate.isClassification() && $gate.Read()">
                                    <router-link to="/classification" class="nav-link">
                                        <i class="nav-icon fa fa-list-alt"></i>
                                        <p>
                                           
                                           {{$t('classification')}}
                                        </p>
                                    </router-link>
                                </li>
                                <li class="nav-item" v-if="$gate.isDoctor() && $gate.Read()">
                                    <router-link to="/doctor" class="nav-link">
                                        <i class="nav-icon fa fa-user-md"></i>
                                        <p>
                                            {{$t('doctors')}}
                                        </p>
                                    </router-link>
                                </li>
                                <li class="nav-item" v-if="$gate.isCompany() && false" >
                                    <router-link to="/company" class="nav-link">
                                        <i class="fa fa-briefcase nav-icon"></i>
                                        <p>{{$t('company')}}</p>
                                    </router-link>
                                </li>
                                <li class="nav-item" v-if="$gate.isSupplier() && $gate.Read()" >
                                    <router-link to="/supplier" class="nav-link" v-shortkey="['ctrl', '4']" @shortkey.native="loadSupplier">
                                        <i class="fa fa-male nav-icon"></i>
                                        <p>
                                            
                                            <span class="mr-4">{{ $t('suppliers')}}</span>

                                            <span style="margin-left: 48px;">Ctrl+4</span>
                                            
                                        </p>
                                    </router-link>
                                </li>
                                <li class="nav-item" v-if="$gate.isBarcode() && $gate.Read()">
                                    <router-link to="/barcode" class="nav-link">
                                        <i class="nav-icon fa fa-barcode"></i>
                                        <p>
                                            {{ $t('barcode')}}
                                        </p>
                                    </router-link>
                                </li>

                            </ul>
                        </li>
                        <li class="nav-item has-treeview" v-if="$gate.isOrder() || $gate.isOrderReport() && $gate.Read()">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa fa-shopping-basket"></i>
                                <p>
                                    {{ $t('Order')}}
                                    
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item" v-if="$gate.isOrder() && $gate.Read()">
                                    <router-link to="/order" class="nav-link" v-shortkey="['ctrl', '1']" @shortkey.native="loadOrder">
                                        <i class="nav-icon fa fa-cart-plus "></i>
                                        <p>
                                            <span class="mr-4">{{ $t('Order')}}</span>

                                            <span class="ml-5">Ctrl+1</span>
                                            
                                        </p>
                                    </router-link>
                                </li>
                                <li class="nav-item" v-if="$gate.isOrderReport() && $gate.Read()">
                                    <router-link to="/order/report" class="nav-link" v-shortkey="['ctrl', '[']" @shortkey.native="loadOrderReport">
                                        <i class="nav-icon fa fa-cart-arrow-down"></i>
                                        <p>
                                            {{ $t('Report')}}
                                            <span class="right">Ctrl+[</span>
                                            
                                        </p>
                                    </router-link>
                                </li>
                                
                            </ul>
                        </li>
                        <li class="nav-item has-treeview" v-if="$gate.isPurchase() || $gate.isPurchaseReport() && $gate.Read()">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa fa-shopping-basket"></i>
                                <p>
                                    {{ $t('purchase')}}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item" v-if="$gate.isPurchase() && $gate.Read()">
                                    <router-link to="/purchase" class="nav-link" v-shortkey="['ctrl', '2']" @shortkey.native="loadPurchase">
                                        <i class="nav-icon fa fa-cart-plus "></i>
                                        <p>
                                            <span class="mr-4">{{ $t('Purchase2')}}</span>

                                            <span class="ml-5">Ctrl+2</span>
                                            
                                        </p>
                                    </router-link>
                                </li>
                                <li class="nav-item" v-if="$gate.isPurchaseReport() && $gate.Read()">
                                    <router-link to="/purchase/report" class="nav-link" v-shortkey="['ctrl', '0']" @shortkey.native="loadPurchaseReport">
                                        <i class="nav-icon fa fa-cart-arrow-down"></i>
                                        <p>
                                            {{ $t('Report')}}
                                            <span class="ml-5">Ctrl+0</span>
                                            
                                        </p>
                                    </router-link>
                                </li>
                                
                            </ul>
                        </li>
                        
                        <li class="nav-item has-treeview" v-if="$gate.isCounterSales() || $gate.isCounterSalesReport() && $gate.Read()">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa fa-shopping-cart"></i>
                                <p>
                                    {{ $t('Sales')}}
                                    
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item" v-if="$gate.isCounterSales() && $gate.Read()" >
                                    <router-link to="/counter-sale" class="nav-link" v-shortkey="['ctrl','3']" @shortkey.native="loadCounter">
                                        <i class="nav-icon fa fa-cart-plus "></i>
                                        <p>
                                            {{ $t('CounterSales')}}
                                                 
                                            <span class="right">Ctrl+3</span>
                                        </p>
                                    </router-link>
                                </li>
                                <li class="nav-item" v-if="$gate.isCounterSalesReport() && $gate.Read()" >
                                    <router-link to="/counter-sale/report" class="nav-link" v-shortkey="['ctrl', '9']" @shortkey.native="loadCounterSalesReport">
                                        <i class="nav-icon fa fa-cart-arrow-down"></i>
                                        <p>
                                            {{ $t('Report')}}
                                             <span class="right">Ctrl+9</span>
                                        </p>
                                    </router-link>
                                </li>
                                <li v-if="false" class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fa fa-user-secret"></i>
                                        <p>
                                            Counter Sales Register
                                        </p>
                                    </a>
                                </li>
                                <li v-if="false" class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fa fa-heartbeat"></i>
                                        <p>
                                            Receipt
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview" v-if="$gate.isLowStock() || $gate.isExpiry() || $gate.isInventoryReport() && $gate.Read()">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa fa-truck"></i>
                                <p>
                                    {{ $t('Inventory')}}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item" v-if="$gate.isLowStock() && $gate.Read()" >
                                    <router-link to="/stock/low" class="nav-link" v-shortkey="['ctrl', '6']" @shortkey.native="loadLowStock">
                                        <i class="nav-icon fas fa-th"></i>
                                        <p>
                                            
                                            <span class="mr-3">Low Stock</span>
                                            <span class="ml-5">Ctrl+6</span>
                                        </p>
                                    </router-link>
                                </li>
                                <li class="nav-item" v-if="$gate.isExpiry() && $gate.Read()">
                                    <router-link to="/stock/expiry" class="nav-link" v-shortkey="['ctrl', '7']" @shortkey.native="loadExpiry">
                                        <i class="nav-icon fas fa-th"></i>
                                        <p>
                                            {{ $t('ItemExpiryDate')}}
                                            <span v-if="unreadNotificationsCount != 0" class="right badge badge-danger">New</span>
                                            <span class="right">Ctrl+7</span>
                                        </p>
                                    </router-link>
                                </li>
                                <li class="nav-item" v-if="$gate.isInventoryReport() && $gate.Read()">
                                    <router-link to="/stock/report" class="nav-link" v-shortkey="['ctrl', ']']" @shortkey.native="loadInventoryReport">
                                        <i class="nav-icon fas fa-th"></i>
                                        <p>
                                            {{ $t('Report')}}
                                            <span class="right">Ctrl+]</span>
                                        </p>
                                    </router-link>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview" v-if="$gate.isExpenseCategory() || $gate.isExpense() && $gate.Read()">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa fa-credit-card"></i>
                                <p>
                                    {{ $t('Expenses')}}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item" v-if="$gate.isExpenseCategory() && $gate.Read()" >
                                    <router-link to="/expenses-category" class="nav-link">
                                        <i class="nav-icon fa fa-list-ul"></i>
                                        <p>
                                            {{ $t('Category')}}
                                            
                                        </p>
                                    </router-link>
                                </li>
                                <li class="nav-item" v-if="$gate.isExpense() && $gate.Read()">
                                    <router-link to="/income" class="nav-link" v-shortkey="['ctrl', '8']" @shortkey.native="loadExpenses">
                                        <i class="nav-icon fa fa-suitcase"></i>
                                        <p>
                                            {{ $t('Expenses')}}
                                            <span class="right">Ctrl+8</span>
                                        </p>
                                    </router-link>
                                </li>
                               <li  class="nav-item">
                                    <router-link to="/income/report" class="nav-link">
                                        <i class="nav-icon fas fa-th"></i>
                                        <p>
                                             {{ $t('Report')}}

                                        </p>
                                    </router-link>
                                </li>
                            </ul>
                        </li>



                        <li class="nav-item" v-if="$gate.isUser() && $gate.Read()">
                            <router-link to="/users" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>{{$t('users')}}</p>
                            </router-link>
                        </li>
                        <li class="nav-item" v-if="$gate.isProfile() && $gate.Read() ">
                            <router-link to="/profile" class="nav-link">
                                <i class="nav-icon fa fa-user"></i>
                                <p>
                                    {{$t('profile')}}
                                </p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/setting" v-if="$gate.isSetting() && $gate.Read()" class="nav-link">
                                <i class="nav-icon fa fa-cog"></i>
                                <p>
                                    {{ $t('setting')}}
                                    
                                </p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <a href="/logout" class="nav-link">
                                <i class="nav-icon fas fa-power-off"></i>
                                <p>{{$t('logout')}}</p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <!-- Main content -->
            <div class="content">
                <router-view></router-view>
                <vue-progress-bar></vue-progress-bar>
                <!-- /.row -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->


        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <footer class="main-footer text-center">
            <!-- To the right -->
            
            <!-- Default to the left -->
            <strong >Copyright &copy; {{year()}} <a href="http://netsoft.af">Netsoft </a>.</strong> All rights
            reserved.
        </footer>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                
                CompanyName:'',
                name: username,
                photo: '/images/users/' + userphoto,
                langIcon: asset + '/logo/subject2.svg',
                pharmacyIcon: '',
                asset: asset,
                unreadNotifications:{},
                unreadNotificationsCount:0,
                readNotifications:{},
                searchData:''

            }
        },
        methods: {
            loadPurchase()
            {
                this.$router.push('/purchase')
            },
            loadOrder()
            {
                this.$router.push('/order')
            },
            loadCounter()
            {
                this.$router.push('/counter-sale')
            },
            loadSupplier()
            {
                this.$router.push('/supplier')
            },
            loadMedicine()
            {
                this.$router.push('/medicine')
            },
            loadLowStock()
            {
                this.$router.push('/stock/low')
            },
            loadExpiry()
            {
                this.$router.push('/stock/expiry')
            },
            loadExpenses()
            {
                this.$router.push('/income')
            },
            loadPurchaseReport()
            {
                this.$router.push('/purchase/report')
            },
            loadCounterSalesReport()
            {
                this.$router.push('/counter-sale/report')
            },
            loadInventoryReport()
            {
                this.$router.push('/stock/report')
            },
            loadOrderReport()
            {
                this.$router.push('/order/report')
            },
            search()
            {   
                
                    Fire.$emit('search',this.searchData);
                
                
            },
            english() {
                this.$Progress.start()
                i18n.locale = 'en';
                Fire.$emit('langChange','en');
                axios.get('locale/en').then(() => (
                    this.$Progress.finish()
                )).catch(() => {
                    this.$Progress.fail()
                });

            },
            year()
            {
                var d = new Date();
                return d.getFullYear();
            },
            getFullscreenElement()
            {
                return document.fullscreenElement
            },
            FullScreen()
            {
                if(this.getFullscreenElement()){
                    document.exitFullscreen();
                }else
                {
                    document.documentElement.requestFullscreen().catch((e) => {
                    console.log(e);})
                }
            },
            persion() {
                this.$Progress.start()
                i18n.locale = 'fa';
                Fire.$emit('langChange','fa');
                axios.get('locale/fa').then(() => (
                    this.$Progress.finish()
                )).catch(() => {
                    this.$Progress.fail()
                });
            },

        },
        
        created() {
            Fire.$on('changeName', (e) => {
                this.name = e
            });

            Fire.$on('changePhoto', (e) => {
                this.photo = e
            });
            Fire.$on('changeCompanyName', (e) => {
                this.CompanyName = e
            });
            Fire.$on('changelogo', (e) => {
                this.pharmacyIcon = e
            });
            Fire.$on('Notify', () => {
                this.unreadNotifications = [];
                this.unreadNotificationsCount = 0;
                
                axios.post('/api/notification/read').then((data)=>{
                    this.readNotifications = data.data.readNotifications;
                });
            });

           axios.get('/api/notification/create').then((data)=>{
               this.unreadNotifications = data.data.unreadNotifications;
               this.unreadNotificationsCount = data.data.unreadNotificationsCount;
               this.readNotifications = data.data.readNotifications;
               });

               axios.get('/api/setting').then((data)=>{
                    this.CompanyName = data.data.name;
                    this.pharmacyIcon = asset + '/logo/'+ data.data.logo;
                });


        },
    }

</script>


<style  scoped>
.bg-new:hover{
            background-color: rgba(51, 168, 224, 0.56) !important;
        }


</style>