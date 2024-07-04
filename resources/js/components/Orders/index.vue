<template>
    <div class="container-fluid">
        <div v-if="$gate.isOrder() && $gate.Read()">
            <div class="content-header">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">{{ $t('Order')}}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right" :dir="rtl">
                            <li class="breadcrumb-item">
                                <router-link to="/dashboard">{{ $t('dashboard') }}</router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('Order') }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card border-0 rounded-0">
                        <div class="card-header">
                            <h3 class="card-title">{{ $t('Table')}} {{ $t('Order') }}</h3>

                            <div class="card-tools">
                                <router-link class="btn btn-success" to="/order/create" data-toggle="tooltip"
                                    data-placement="top" data-original-title="Ctrl+Alt+N"
                                    v-shortkey="['ctrl', 'alt', 'n']" v-if="$gate.Create()" @shortkey.native="theAction" @click="theAction">
                                    {{ $t('addnew') }}
                                    <i class="fas fa-plus "></i> </router-link>
                                <!-- <router-link class="btn btn-success" to="/print/user" >Print <i class="fa fa-print fa fw" ></i> </router-link> -->
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <div style="display: none">
                                <div id="printme">
                                    <h1 class="text-center">{{Company}}</h1>
                                    <p id="bill" class="d-inline col-md-12 d-40" style="margin-right: 18%;">{{ $t('BillNo') }}:
                                        {{order.BillNo}} </p>

                                    <p id="suppiler" class="d-inline col-md-12" style="margin-right: 18%;">{{ $t('suppliers') }}:
                                        {{order.supplier}}</p>

                                    <p id="date" class="d-inline col-md-12" style="margin-right: 18%;">{{ $t('OrderDate') }}:
                                        {{order.date}}</p>
                                    <table class="table table-hover mt-2">
                                        <thead class="thead">
                                            <th style="width:5%">#</th>
                                            <th style="width:20%">{{ $t('Name') }}</th>
                                            <th style="width:20%">{{ $t('group') }}</th>
                                            <th style="width:20%">{{ $t('quantity') }}</th>
                                        </thead>
                                        <tbody id="tbody">
                                        </tbody>
                                    </table>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <p>{{order.description}}</p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <table class="table table-hover table-bordered text-center" :dir="rtl">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ $t('BillNo') }}</th>
                                        <th>{{ $t('suppliers') }}</th>
                                        <th>{{ $t('OrderDate') }}</th>
                                        <th>{{ $t('CreateBy') }}</th>
                                        <th>{{ $t('FromStock') }}</th>
                                        <th>{{ $t('Approval') }}</th>
                                        <th>{{ $t('Modify') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(order,i) in orders.data" :key="order.id">
                                        <td @click="show(order.id)">{{(current_page*20)-20 + i+1}}</td>
                                        <td @click="show(order.id)">{{order.BillNo}}</td>
                                        <td @click="show(order.id)">{{order.supplier}}</td>
                                        <td @click="show(order.id)">{{order.date}}</td>
                                        <td @click="show(order.id)">{{order.user}}</td>
                                        <td @click="show(order.id)" v-if="order.is_stock">Yes</td>
                                        <td @click="show(order.id)" v-else>No</td>
                                        <td @click="show(order.id)" v-if="order.approve==1" class="text-center">
                                            <span class="bg-success px-1">{{ $t('Approved')}}</span>
                                        </td>
                                        <td class="text-center" :id="order.id"
                                            v-else-if="!order.approve && $gate.isUser()">
                                            <a href="#" @click.prevent="approved"><i class="fas fa-check"></i></a>|
                                            <a href="#" @click.prevent="Notapproved"><i class="fas fa-times"></i></a>
                                        </td>
                                        <td @click="show(order.id)" class="text-center" v-else-if="order.approve==2">
                                            <span class="bg-danger px-1">{{ $t('NotApproved')}}</span>
                                        </td>
                                        <td @click="show(order.id)" class="text-center" v-else>
                                            <span class="bg-danger px-1">{{ $t('padding')}}</span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle" type="button" data-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                </button>
                                                <div class="dropdown-menu" x-placement="bottom-start"
                                                    style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
                                                    <router-link class="dropdown-item" :id="'edit'+order.id"
                                                        v-if="order.approve !=1" :to="'order/edit/'+order.id">{{$t('Edit')}}
                                                    </router-link>
                                                    <a class="dropdown-item" @click.prevent="show(order.id)">{{$t('Show')}}</a>
                                                    <a class="dropdown-item" :id="'deleted'+order.id"
                                                        v-if="order.approve !=1"
                                                        @click.prevent="Delete(order.id)">{{$t('Delete')}}</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <pagination :data="orders" :limit="5" @pagination-change-page="getResults"></pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>




        <div v-if="!$gate.isOrder()">
            <not-found></not-found>
        </div>
    </div>
</template>


<script>
    export default {
        data() {
            return {
                rtl: null,
                Company: pharmacy.name,
                id: '',
                order: {},
                orders_details: {},
                current_page: 1,
                search: '',
                orders: {}
            }
        },

        methods: {

            theAction() {
                this.$router.push('/order/create')
            },
            show(id) {
                this.$router.push('/order/show/' + id);
            },
            getResults(page = 1) {
                axios.get('api/order', {
                        params: {
                            search: this.search,
                            page: page
                        },
                    })
                    .then(response => {
                        this.orders = response.data;
                        this.current_page = response.data.current_page;
                    });
            },
            load() {
                axios.get('api/order').then(({
                    data
                }) => (this.orders = data));
            },
            Delete(id) {
                var title = null;
                var confirmButtonText = null;
                var cancelButtonText = null;
                var deleted = null;
                var dele = null;
                    if (i18n.locale == 'fa') {
                        title = 'آیا مطمین هستین؟';
                        confirmButtonText= '!بلی';
                        cancelButtonText= 'نخیر';
                        deleted = '.فایل موفقانه حذف شد'
                        dele = '!حذف'
                    } else {
                        title ='Are you sure?';
                        confirmButtonText= 'Yes, delete it!';
                        cancelButtonText= 'Cancel';
                        deleted = 'Your file has been deleted.';
                        dele = 'Deleted!';
                    }
              Swal.fire({
                title: title,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: confirmButtonText,
                cancelButtonText: cancelButtonText
                }).then((result) => {
                    // Send Request to the server

                    if (result.value) {
                        axios.delete('/api/order/' + id).then(() => {
                                Swal.fire(
                                    dele,
                                    deleted,
                                    'success'
                                )
                                Fire.$emit('AfterCreate');
                            })
                            .catch(() => {
                                Swal.fire(
                                    'Failed!',
                                    'There was something wronge.',
                                    'warning'
                                )
                            })
                    }
                })
            },



            approved(e) {

                this.$Progress.start();
                axios.get('api/order/approved/1/' + e.target.parentElement.parentElement.id).then(() => {
                        var id = e.target.parentElement.parentElement.id;
                        $('#' + id).html("<span class='bg-success px-1'>Approved</span>");
                        $('#edit' + id).css('display', 'none');
                        $('#deleted' + id).css('display', 'none');
                        this.$Progress.finish();

                    })
                    .catch(() => {
                        this.$Progress.fail();
                    });
            },

            Notapproved(e) {
                this.$Progress.start();
                axios.get('api/order/approved/2/' + e.target.parentElement.parentElement.id).then(() => {
                        var id = e.target.parentElement.parentElement.id;
                        $('#' + id).html("<span class='bg-danger px-1'>Not Approved</span>");
                        this.$Progress.finish();

                    })
                    .catch(() => {
                        this.$Progress.fail();
                    });

            }
        },
        mounted() {
            $('[data-toggle="tooltip"]').tooltip({
                trigger: 'hover'
            });
            $('[data-toggle="tooltip"]').on('click', function () {
                $(this).tooltip('hide')
            });
        },
        created() {

            this.$Progress.start();


            this.load();

            this.$Progress.finish();
            Fire.$on('AfterCreate', () => {
                this.load();
            });

            this.$parent.searchData = '';
            Fire.$off('search')
            Fire.$on('search', (e) => {
                this.search = e;
                axios.get('api/order', {
                    params: {
                        search: e
                    },
                }).then(({
                    data
                }) => (this.orders = data));
            });
            Fire.$on('orderSuccess', (e) => {
                toast.fire({
                    icon: 'success',
                    title: 'Order Add Successfully'
                });
            });


            


            Fire.$on('langChange', (e) => {
                if (e == 'en') {
                    this.rtl = 'ltr'
                } else {
                    this.rtl = 'rtl'
                }
            });

            if (i18n.locale == 'fa') {
                this.rtl = 'rtl'
            } else {
                this.rtl = 'ltr'
            }




        },
        updated()
        {
            Fire.$on('orderId', (id) => {

                setTimeout(() => {
                axios.get('/api/order/' + id).then(({
                    data
                }) => {
                    // this.order = data.order;
                    // this.orders_details = data.order_details;
                    $('#bill').text('Bill No: ' + data.order.BillNo);
                    $('#suppiler').text('Suppiler: ' + data.order.supplier);
                    $('#date').text('Order Date: ' + data.order.date);
                    var html = '';
                    for (var i = 0; i < data.order_details.length; i++) {
                        html += "<tr> <td>" + (i + 1) + "</td><td>" + data.order_details[i].medicines +
                            "</td> <td>" + data.order_details[i].group + "</td> <td>" + data
                            .order_details[i].quantity + "</td> </tr>";
                    }
                    $('#tbody').html(html);

                    $('#printme').printThis({
                        importCSS: true,
                        importStyle: false,
                    });


                });
                },500);
            });
        }

    }

</script>


<style scoped>
    .dropdown-toggle::after {
        display: none;
        width: 0;
        height: 0;
        margin-left: none;
        vertical-align: none;
        content: "";
        border-top: none;
        border-right: none;
        border-bottom: 0;
        border-left: none;
        border-top: 0px solid;
        border-right: 0px solid
    }

</style>
