<template>
    <div class="container-fluid">
        <div v-if="$gate.isPurchase() && $gate.Read()">
            <div class="content-header">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark ">{{ $t('purchase')}}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right" :dir="rtl">
                            <li class="breadcrumb-item">
                                <router-link to="/dashboard">{{ $t('dashboard') }}</router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('purchase')}}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card border-0 rounded-0">
                        <div class="card-header">
                            <h3 class="card-title" v-if="rtl == 'rtl'">{{ $t('Table')}} {{ $t('purchase')}}</h3>
                            <h3 class="card-title" v-else> {{ $t('purchase')}} {{ $t('Table')}}</h3>

                            <div class="card-tools">
                                <router-link class="btn btn-success" to="purchase/create" data-toggle="tooltip"
                                    data-placement="top" data-original-title="Ctrl+Alt+N"
                                    v-shortkey="['ctrl', 'alt', 'n']" v-if="$gate.Create()" @shortkey.native="theAction"
                                    @click="theAction">{{ $t('addnew') }}
                                    <i class="fas fa-plus "></i> </router-link>
                                <!-- <router-link class="btn btn-success" to="/print/user" >Print <i class="fa fa-print fa fw" ></i> </router-link> -->
                            </div>
                        </div>



                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <div style="display:none">
                                <div id="printme">
                                    <p id="bill" class="d-inline mt-5" style="margin-right: 10%;">{{ $t('BillNo') }}: </p>
                                    <p id="suppiler" class="d-inline" style="margin-right: 10%;">{{ $t('suppliers') }}: </p>

                                    <p id="suppilerRef" class="d-inline" style="margin-right: 10%;">{{ $t('SupplierRef') }}:
                                    </p>
                                    <p id="receiveDate" class="d-inline " style="margin-right: 10%;">{{ $t('ReceiveDate') }}: </p>

                                    <br><br>
                                    <table class="table table-striped" :dir="rtl"  >
                                        <thead>
                                            <th style="width:5%">#</th>
                                        
                                            <th style="width:20%">{{ $t('Name') }}</th>
                                            <th style="width:10%">{{ $t('BatchNo') }}</th>
                                            <th style="width:15%">{{ $t('Expiry') }}</th>
                                            <th style="width:5%">{{ $t('quantity') }}</th>
                                            <th style="width:5%">{{ $t('FreeQuantity') }}</th>
                                            <th style="width:10%">{{ $t('Rate') }}</th>
                                           
                                            <th style="width:10%">{{ $t('Amount') }}</th>
                                        </thead>
                                        <tbody id="tbody">

                                        </tbody>
                                    </table>


                                    <p id="total" style="margin-right: 10%; float: right;display:block;clear: both;">
                                        {{$t('Total')}} :
                                    </p>
                                    <p id="discount" style="margin-right: 10%; float: right;display:block;clear: both;">
                                        {{$t('Discount')}} :
                                    </p>
                                    <p id="paid" style="margin-right: 10%; float: right;display:block;clear: both;">{{$t('Paid')}}
                                        :
                                    </p>
                                    <p id="remaind" style="margin-right: 10%; float: right;display:block;clear: both;">
                                        {{$t('Due')}} :
                                    </p>

                                </div>
                            </div>


                            <table class="table table-hover table-bordered text-center" :dir="rtl">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ $t('BillNo') }}</th>
                                        <th>{{ $t('suppliers') }}</th>
                                        <th>{{ $t('SupplierRef') }}</th>
                                        <th>{{ $t('Date') }}</th>
                                        <th>{{ $t('Total') }}</th>
                                        <th>{{ $t('Discount') }}</th>
                                        <th>{{ $t('Paid') }}</th>
                                        <th>{{ $t('Due') }}</th>
                                        <th>{{ $t('CreateBy') }}</th>
                                        <th>{{ $t('Image') }}</th>
                                        <th>{{ $t('Approval') }}</th>
                                        <th>{{ $t('Modify') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(purchase,i) in purchases.data" :key="purchase.id">
                                        <td @click="show(purchase.id)">{{(current_page*20)-20 + i+1}}</td>
                                        <td @click="show(purchase.id)">{{purchase.BillNo}}</td>
                                        <td @click="show(purchase.id)">{{purchase.supplier}}</td>
                                        <td @click="show(purchase.id)">{{purchase.supplier_ref}}</td>
                                        <td @click="show(purchase.id)">{{purchase.date}}</td>
                                        <td @click="show(purchase.id)">{{purchase.total}}</td>
                                        <td @click="show(purchase.id)">{{purchase.discount}}</td>
                                        <td @click="show(purchase.id)">{{purchase.paid}}</td>
                                        <td @click="show(purchase.id)">{{purchase.remind}}</td>
                                        <td @click="show(purchase.id)">{{purchase.user}}</td>
                                        <td v-if="purchase.image"><a
                                                :href="'images/purchases/'+purchase.image">Image</a></td>
                                        <td v-else></td>
                                        <td v-if="purchase.approve==1" class="text-center">
                                            <span class="bg-success px-1">{{ $t('Approved')}}</span>
                                        </td>
                                        <td class="text-center" :id="purchase.id"
                                            v-else-if="!purchase.approve && $gate.isUser()">
                                            <a href="#" @click.prevent="approved"><i class="fas fa-check"></i></a>|
                                            <a href="#" @click.prevent="Notapproved"><i class="fas fa-times"></i></a>
                                        </td>
                                        <td class="text-center" v-else-if="purchase.approve==2">
                                            <span class="bg-danger px-1">{{ $t('NotApproved')}}</span>
                                        </td>
                                        <td class="text-center" v-else>
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
                                                    <router-link class="dropdown-item" :id="'edit'+purchase.id"
                                                        v-if="$gate.isUser() && purchase.approve !=1"
                                                        :to="'purchase/edit/'+purchase.id">{{$t('Edit')}}</router-link>
                                                    <a class="dropdown-item" @click.prevent="show(purchase.id)">{{$t('Show')}}</a>
                                                    <a class="dropdown-item" :id="'deleted'+purchase.id"
                                                        v-if="$gate.isUser() && purchase.approve !=1"
                                                        @click.prevent="Delete(purchase.id)">{{$t('Delete')}}</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <pagination :data="purchases" :limit="5" @pagination-change-page="getResults"></pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="!$gate.isPurchase()">
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
                current_page: 1,
                search: '',
                purchases: {}
            }
        },

        methods: {
            doThis() {
                axios.get('/api/purchases/' + this.id).then(({
                    data
                }) => {
                    this.purchase = data.purchase;
                    this.purchases_details = data.purchase_detail;
                });
            },
            theAction() {
                this.$router.push('/purchase/create')
            },
            show(id) {
                this.$router.push('/purchase/show/' + id);
            },
            getResults(page = 1) {
                axios.get('api/purchases', {
                        params: {
                            search: this.search,
                            page: page
                        },
                    })
                    .then(response => {
                        this.purchases = response.data;
                        this.current_page = response.data.current_page;
                    });
            },
            load() {
                axios.get('api/purchases').then(({
                    data
                }) => (this.purchases = data));
            },
            Delete(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    // Send Request to the server

                    if (result.value) {
                        axios.delete('api/purchases/' + id).then(() => {
                                Swal.fire(
                                    'Deleted!',
                                    'Your file has been deleted.',
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
                axios.get('api/purchases/approved/1/' + e.target.parentElement.parentElement.id).then(() => {
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
                axios.get('api/purchases/approved/2/' + e.target.parentElement.parentElement.id).then(() => {
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
                axios.get('api/purchases', {
                    params: {
                        search: e
                    },
                }).then(({
                    data
                }) => (this.purchases = data));
            });

            Fire.$on('success', (e) => {
                toast.fire({
                    icon: 'success',
                    title: 'Data Add Successfully'
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
            Fire.$on('purchasesId', (id) => {
                setTimeout(() => {
                    axios.get('/api/purchases/' + id).then(({
                    data
                }) => {
                    // this.purchases = data.purchase;
                    // this.purchases_details = data.purchase_detail;

                    $('#bill').text('Bill No: ' + data.purchase.BillNo);
                    $('#suppiler').text('Suppiler: ' + data.purchase.supplier);
                    $('#suppilerRef').text('Supplier Ref: ' + data.purchase.supplier_ref);
                    $('#receiveDate').text('Receive Date:' + data.purchase.date);
                    $('#total').text('Total: ' + data.purchase.total);
                    $('#discount').text('Discount: ' + data.purchase.discount);
                    $('#paid').text('Paid: ' + data.purchase.paid);
                    $('#remaind').text('Due: ' + data.purchase.remind);

                    var html = '';
                    for (var i = 0; i < data.purchase_detail.length; i++) {
                        html += "<tr> <td>" + (i + 1) + "</td> <td>" + data.purchase_detail[i].medicines + " , " + data
                            .purchase_detail[i].group + "</td> <td>" + data
                            .purchase_detail[i].batch_no + "</td> <td>" + data
                            .purchase_detail[i].expiry_date + "</td> <td>" + data
                            .purchase_detail[i].quantity + "</td> <td>" + data
                            .purchase_detail[i].free_quantity + "</td> <td>" + data
                            .purchase_detail[i].rate + "</td>  <td>" + data
                            .purchase_detail[i].amount + " af </td> </tr>";
                    }
                    $('#tbody').html(html);


                    $('#printme').printThis({
                        importCSS: true,
                        importStyle: false,
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

</style>
