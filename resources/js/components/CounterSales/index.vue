<template>
    <div class="container-fluid">
        <div v-if="$gate.isCounterSales() && $gate.Read()">
            <div class="content-header">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark ">{{ $t('CounterSales')}}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right" :dir="rtl">
                            <li class="breadcrumb-item">
                                <router-link to="/dashboard">{{ $t('dashboard') }}</router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('CounterSales')}}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card border-0 rounded-0">
                        <div class="card-header">
                            <h3 class="card-title" v-if="rtl == 'rtl'">{{ $t('Table')}} {{ $t('CounterSales')}}</h3>
                            <h3 class="card-title" v-else> {{ $t('CounterSales')}} {{ $t('Table')}}</h3>

                            <div class="card-tools">
                                <b class="btn btn-success" v-if="$gate.Create()" v-shortkey="['ctrl', 'alt', 'n']" @shortkey="theAction" data-toggle="tooltip"
                                    data-placement="top" data-original-title="Ctrl+Alt+N"
                                    @click="theAction">{{ $t('addnew') }}
                                    <i class="fas fa-plus "></i> </b>
                                <!-- <router-link class="btn btn-success" to="/print/user" >Print <i class="fa fa-print fa fw" ></i> </router-link> -->
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <div style="display:none" >
                            <div id="printme">

                                <p id="bill" class="d-inline mt-5" style="margin-right: 10%;">{{$t('BillNo')}}: {{sale.BillNo}} </p>
                                <p id="patient" class="d-inline" style="margin-right: 10%;">{{$t('PatientName')}}: {{sale.patient_name}}</p>
                                <p id="doctor" class="d-inline" style="margin-right: 10%;">{{ $t('Doctor')}}: {{sale.doctor}}</p>
                                <p id="date" class="d-inline" style="margin-right: 10%;">{{ $t('SaleDate')}}: {{sale.date}}</p>

                                <table class="table table-striped">
                                    <thead>
                                        <th style="width:5%">#</th>
                                        <th class="barcode" style="width:10%">{{$t('Barcode')}}</th>
                                        <th style="width:20%">{{$t('Name')}}</th>
                                        <th class="Batch" style="width:10%">{{$t('BatchNo')}}</th>
                                        <th class="Expiry" style="width:10%">{{$t('Expiry')}}</th>
                                        <th style="width:10%">{{$t('quantity')}}</th>
                                        <th style="width:10%">{{$t('MRP')}}</th>
                                        <th style="width:10%">{{$t('Amount')}}</th>
                                    </thead>

                                    <tbody id="tbody">

                                    </tbody>




                                    <tr>
                                        <td></td>
                                        <td id="total" style="width:10%"> <strong>{{$t('Total')}}: {{sale.total}} Af</strong> </td>
                                        <td id="discount"> <strong>{{$t('Discount')}}: {{sale.discount}} Af</strong> </td>
                                        <td id="paid"> <strong>{{$t('Paid')}}: {{sale.paid}} Af</strong> </td>
                                        <td id="remaind"> <strong>{{$t('Due')}}: {{sale.remaind}} Af</strong></td>
                                    </tr>

                                </table>



                            </div>
                            </div>
                            <table class="table table-hover table-bordered text-center" :dir="rtl">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{$t('BillNo')}}</th>
                                        <th>{{$t('PatientName')}}</th>
                                        <th>{{$t('Doctor')}}</th>
                                        <th>{{$t('SaleDate')}}</th>
                                        <th>{{$t('Total')}}</th>
                                        <th>{{$t('Discount')}}</th>
                                        <th>{{$t('Due')}}</th>
                                        <th>{{$t('Paid')}}</th>
                                        <th>{{$t('CreateBy')}}</th>
                                        <th>{{$t('Modify')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(sale,i) in sales.data" :key="sale.id">
                                        <td @click="show(sale.id)">{{(current_page*20)-20 + i+1}}</td>
                                        <td @click="show(sale.id)">{{sale.BillNo}}</td>
                                        <td @click="show(sale.id)">{{sale.patient_name}}</td>
                                        <td @click="show(sale.id)">{{sale.doctor}}</td>
                                        <td @click="show(sale.id)">{{sale.date}}</td>
                                        <td @click="show(sale.id)">{{sale.total}}</td>
                                        <td @click="show(sale.id)">{{sale.discount}}</td>
                                        <td @click="show(sale.id)">{{sale.remaind}}</td>
                                        <td @click="show(sale.id)">{{sale.paid}}</td>
                                        <td @click="show(sale.id)">{{sale.user}}</td>


                                        <td>
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle" type="button" data-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                </button>
                                                <div class="dropdown-menu" x-placement="bottom-start"
                                                    style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
                                                    <router-link class="dropdown-item"
                                                        :to="'counter-sale/show/'+sale.id">{{$t('Show')}}</router-link>
                                                    <router-link v-if="false" class="dropdown-item"
                                                        :to="'counter-sale/edit/'+sale.id">{{$t('Edit')}}</router-link>
                                                    <router-link v-if="false" class="dropdown-item"
                                                        :to="'counter-sale/add/'+sale.id">Add Product</router-link>
                                                    <router-link class="dropdown-item"
                                                        :to="'counter-sale/return/'+sale.id">{{$t('ReturnProduct')}}
                                                    </router-link>
                                                    <a class="dropdown-item" v-if="sale.remaind"
                                                        @click.prevent="AddMoney(sale.id)">{{$t('AddMoney')}}</a>
                                                    <a class="dropdown-item" @click.prevent="Delete(sale.id)">{{$t('Delete')}}</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <pagination :data="sales" :limit="5" @pagination-change-page="getResults"></pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="!$gate.isCounterSales()">
            <not-found></not-found>
        </div>

        <div class="modal fade" id="AddVendor" tabindex="-1" role="dialog" aria-labelledby="AddVendorLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="AddVendorLabel">{{$t('AddMoney')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="save()">

                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="form.cash" type="number" name="cash" v-if="rtl == 'rtl'" placeholder="مقدار پول را نوشته کنید"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('cash') }">
                                <input v-model="form.cash" type="number" name="cash" v-else placeholder="Enter your Cash"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('cash') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">{{$t('Close')}}</button>
                            <button type="submit" class="btn btn-success">{{$t('Save')}}</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>




    </div>
</template>


<script>
    export default {
        data() {
            return {
                rtl: null,
                 i: 0,
                module: null,
                AllSale: null,
                loops: null,
                Company: pharmacy.name,
                sale: {},
                current_page:1,
                search: '',
                sales: {},
                form: new Form({
                    cash: '',
                    id: '',
                }),
            }
        },

        methods: {
            show(id) {
                this.$router.push('/counter-sale/show/' + id);
            },
            theAction() {
                this.$router.push('/counter-sale/create')
            },
            AddMoney(id) {
                $('#AddVendor').modal('show');
                this.form.cash = '';
                this.form.id = id;
            },
            getResults(page = 1) {
                axios.get('api/counter-sale', {
                        params: {
                            search: this.search,
                            page: page
                        },
                    })
                    .then(response => {
                        this.sales = response.data;
                        this.current_page = response.data.current_page;
                    });
            },
            save() {
                this.$Progress.start()
                this.form.post('/api/counter-sale/cash').then((data) => {
                    if (data.data.error) {
                        toast.fire({
                            icon: 'error',
                            title: data.data.error
                        });
                        this.$Progress.fail()
                    }

                    if (data.data.success) {
                        toast.fire({
                            icon: 'success',
                            title: data.data.success
                        });
                        $('#AddVendor').modal('hide');
                        Fire.$emit('AfterCreate');
                    }

                    this.$Progress.finish()
                }).catch(() => {
                    this.$Progress.fail()
                });
            },
            load() {
                axios.get('api/counter-sale').then(({
                    data
                }) => (this.sales = data));
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
                        axios.delete('api/purchases/' + id).then(() => {
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
        },
        mounted() {
            Fire.$on('success', (e) => {

                toast.fire({
                    icon: 'success',
                    title: 'Data Add Successfully'
                });

            });

            
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
                axios.get('api/counter-sale', {
                    params: {
                        search: e
                    },
                }).then(({
                    data
                }) => (this.sales = data));
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
            Fire.$on('SaleId', (id) => {

                setTimeout(() => {

                axios.get('/api/counter-sale/' + id).then(({
                data
            }) => {
                this.sale = data.sale;
                
                $('#bill').text('Bill No: '+data.sale.BillNo);
                $('#patient').text('Patient Name: '+data.sale.patient_name);
                $('#doctor').text('Doctors: '+data.sale.doctor);
                $('#date').text('Sale Date: '+data.sale.date);
                $('#total').text('Total: '+data.sale.total +' Af');
                $('#discount').text('Discount: '+data.sale.discount +' Af');
                $('#paid').text('Paid: '+data.sale.paid +' Af');
                $('#remaind').text('Due: '+data.sale.remaind +' Af');

                if (data.sale_detail.length >= 10) {
                    this.module = data.sale_detail.length % 10;
                    var AllSale = data.sale_detail.length - this.module;
                } else {

                    var demo = 9 - data.sale_detail.length
                    var AllSale = demo + data.sale_detail.length;
                }


                if (data.sale_detail.length <= 9) {
                    this.loops = 1
                } else {

                    this.loops = parseInt(AllSale / 10 + 1);
                }
                var html = '';
                var z = 0
                for (var i = 0; i < this.loops; i++) {

                    z = i * 10;

                    for (z; z < AllSale; z++) {
                        if (data.sale_detail[z] === undefined) {
                            html += "<tr class='RemoveRow' ><td>" + (z + 1) +
                                "</td><td colspan='4' ></td><td class='barcode'></td><td class='Batch'></td><td class='Expiry'></td></tr>";


                        } else {
                            html += "<tr><td>" + (z + 1) + "</td><td class='barcode'>" + data.sale_detail[z]
                                .barcode +
                                "</td><td>" + data.sale_detail[z].medicines + "</td><td class='Batch'>" + data
                                .sale_detail[z]
                                .batch_no + "</td><td class='Expiry'>" + data.sale_detail[z].expiry_date +
                                "</td><td>" + data
                                .sale_detail[z].quantity + "</td><td>" + data.sale_detail[z].MRP + "</td><td>" +
                                data.sale_detail[z].amount + "</td></tr>";
                        }

                    }


                    AllSale = AllSale + 11

                }

                $('#tbody').html(html);

                $('#printme').printThis({
                    importCSS: true,
                    importStyle: false,
                    loadCSS: "../css/style.css",
                    header: '<h1 class="text-center mb-5">' + this.Company + '</h1>',
                });

            });
            },0);

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



    a:hover {
        cursor: pointer;
    }

</style>
