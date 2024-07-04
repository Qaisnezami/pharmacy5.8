<template>
    <div class="container-fluid" id="report">
        <div v-if="$gate.isPurchaseReport() && $gate.Read()">
            <div class="content-header">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark" v-if="rtl == 'rtl'">{{ $t('Report') }} {{ $t('purchase') }} </h1>
                        <h1 class="m-0 text-dark" v-else>{{ $t('purchase') }} {{ $t('Report') }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <router-link to="/dashboard">{{ $t('dashboard') }}</router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('Report') }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card border-0 rounded-0">
                        <div class="card-header">
                        <h3 class="card-title">{{ $t('Report') }}</h3>
                        <div class="card-tools">
                            <button class="btn btn-success" @click="print">
                                <i class="fa fa-print "></i> </button>
                           
                        </div>
                    </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <form @submit.prevent="loadData" autocomplete="off">

                                <div class="row">
                                    <div class="col-md-3" v-if="false">
                                        <div class="form-group">
                                            <label for="Medicine">Medicines</label>
                                            <v-select v-model="form.medicine" :options="medicines"
                                                :reduce="name => name.code" label="name"></v-select>
                                            <has-error :form="form" field="medicine"></has-error>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="Doctor">{{ $t('suppliers') }}</label>
                                            <v-select v-model="form.supplier" :options="suppliers"
                                                :reduce="name => name.code" label="name"></v-select>
                                            <has-error :form="form" field="supplier"></has-error>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="User">{{$t('User')}}</label>
                                            <v-select v-model="form.user" :options="users" :reduce="name => name.code"
                                                label="name"></v-select>
                                            <has-error :form="form" field="user"></has-error>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="Date">{{$t('From')}}</label>
                                            <input v-model="form.from" id="from" type="text" name="from" 
                                                placeholder="YYYY-MM-DD" autocomplete="off"
                                                class="form-control datepicker"
                                                >

                                            <has-error :form="form" field="from"></has-error>
                                            <p id="fromText" class="text-danger d-none">This field is required.</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="Date">{{$t('To')}}</label>
                                            <input v-model="form.to" id="to" type="text" name="to"
                                                placeholder="YYYY-MM-DD" autocomplete="off"
                                                class="form-control datepicker"
                                                :class="{ 'is-invalid': form.errors.has('to') }">

                                            <has-error :form="form" field="to"></has-error>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12"><button type="submit" id="submit"
                                            class="btn btn-primary btn-block">{{$t('Load')}}</button></div>
                                </div>
                            </form>
                            <h1 id="counterSaleTitle"  class="text-center d-none" v-if="rtl == 'rtl'">{{ $t('Report') }} {{ $t('purchase') }}</h1>  
                            <h1 id="counterSaleTitle"  class="text-center d-none" v-else> {{ $t('purchase') }} {{ $t('Report') }}</h1>  
                            <div class="mt-3" style="overflow-x:auto !important;">
                            <table class="table table-hover table-bordered table-sm" :dir="rtl">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ $t('BillNo') }}</th>
                                        <th style="width:10%">{{ $t('suppliers') }}</th>
                                        <th style="width:10%">{{ $t('SupplierRef') }}</th>
                                        <th style="width:10%">{{ $t('Date') }}</th>
                                        <th style="width:10%">{{ $t('Total') }}</th>
                                        <th style="width:10%">{{ $t('Discount') }}</th>
                                        <th style="width:10%">{{ $t('Paid') }}</th>
                                        <th style="width:10%">{{ $t('Due') }}</th>
                                        <th style="width:10%">{{ $t('CreateBy') }}</th>
                                        <th style="width:10%" class="td">{{ $t('Image') }}</th>
                                        <th style="width:10%" class="td">{{ $t('Approval') }}</th>
                                    <th class="modeify">{{ $t('Modify') }}</th>
                                    </tr>
                                </thead>
                            <tbody>
                                <tr v-for="(purchase,i) in list" :key="i"  >
                                    <td>{{i+1}}</td>
                                    <td>{{purchase.BillNo}}</td>
                                    <td>{{purchase.supplier}}</td>
                                    <td>{{purchase.supplier_ref}}</td>
                                    <td>{{purchase.date}}</td>
                                    <td class="total">{{purchase.total}}</td>
                                    <td class="discount">{{purchase.discount}}</td>
                                    <td class="remaind">{{purchase.remind}}</td>
                                    <td class="paid">{{purchase.paid}}</td>
                                    <td>{{purchase.user}}</td>
                                    <td class="td">{{purchase.image}}</td>
                                    <td v-if="purchase.approve==1" class="text-center td " >
                                            <span class="bg-success px-1">{{ $t('Approved')}}</span>
                                    </td>
                                    <td class="text-center td" v-else-if="purchase.approve==2" >
                                            <span class="bg-danger px-1">{{ $t('NotApproved')}}</span>
                                    </td>
                                    <td class="text-center td" v-else>
                                            <span class="bg-danger px-1">{{ $t('padding')}}</span>
                                        </td>
                                    <td class="modeify">
                                        <router-link class="btn" :to="'/purchase/show/'+purchase.id"><i class="fa fa-print"></i></router-link>
                                    </td>
                                </tr>
                            </tbody>
                            
                            <tfoot class="d-none">
                                <tr><td colspan="11"></td></tr>
                                <tr>
                                    <th colspan="5"></th>
                                    <th id="total">0</th>
                                    <th id="discount">0</th>
                                    <th id="remaind">0</th>
                                    <th id="paid">0</th>
                                    <th colspan="3"></th>
                                </tr>
                            </tfoot>
                            </table>
                            </div>
                            <infinite-loading @distance="1" spinner="spiral" :identifier="infiniteId" @infinite="infinteHandler">
                                <div slot="no-more">No more Data</div>
                                <div slot="no-results">No results</div>
                                <div slot="error" slot-scope="{ trigger }">
                                    Error message, click <a href="javascript:;" @click="trigger">here</a> to retry
                                </div>
                            </infinite-loading>
                        </div>

                    </div>
                    
                </div>
            </div>
        </div>

        <div v-if="!$gate.isPurchaseReport()">
            <not-found></not-found>
        </div>



    </div>
</template>


<script>
import InfiniteLoading from 'vue-infinite-loading';
    export default {
        data() {
            return {
                rtl: null,
                list: [],
                page: 1,
                infiniteId: +new Date(),
                load : false,
                medicines: [],
                suppliers: [],
                users: [],
                form: new Form({
                    medicine : null,
                    user : null,
                    supplier : null,
                    type : 'sale',
                    from:'',
                    to:null
                })
            }
        },
        components: {
            InfiniteLoading,
        },
        methods:{
            infinteHandler($state) {
                
                if(this.load)
                {
                    
                axios.get('/api/report/purchases', {
                    params: {
                        page: this.page,
                        user:this.form.user,
                        supplier:this.form.supplier,
                        from:this.form.from,
                        to:this.form.to
                    },
                }).then(({
                    data
                }) => {
                    if (data.data.length) {
                        this.page += 1;
                        this.list.push(...data.data);
                        $state.loaded();
                    } else {
                        let totalSum = 0;
                        let discountSum = 0;
                        let remaindSum = 0;
                        let paidSum = 0;
                        $('.total').each(function () {
                            let inputVal = $(this).html();
                            totalSum += parseFloat(inputVal);
                        });
                        $('.discount').each(function () {
                            let inputVal = $(this).html();
                            discountSum += parseFloat(inputVal);
                        });
                        $('.remaind').each(function () {
                            let inputVal = $(this).html();
                            remaindSum += parseFloat(inputVal);
                        });
                        $('.paid').each(function () {
                            let inputVal = $(this).html();
                            paidSum += parseFloat(inputVal);
                        });

                        $('#total').html(totalSum);
                        $('#discount').html(discountSum);
                        $('#remaind').html(remaindSum);
                        $('#paid').html(paidSum);
                        
                        $state.complete();
                    }
                });
                }else
                {
                    $state.complete();
                }
            },
            loadData()
            {
                $('tfoot').removeClass('d-none');
                if($.trim(this.form.from) == '')
                {
                    $('#from').addClass('is-invalid');
                    $('#fromText').removeClass('d-none');
                }else
                {
                    $('#from').removeClass('is-invalid');
                    $('#fromText').addClass('d-none');
                    this.load = true;
                this.page = 1;
                this.list = [];
                this.infiniteId += 1;
                }
                
            },
            print() {
                window.print();
            }
            
        },
        mounted() {
           let that = this;
            $('#report').on('keyup  change', '#from', function () {

                that.form.from = $(this).val();
            });
            $('#report').on('keyup  change', '#to', function () {

                that.form.to = $(this).val();
            });
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd'
            });
        },
        created() {
            this.$Progress.start();

            axios.get('/api/counter-sale/report/users').then((data) => {
                this.users = (data.data.data);
            });

            axios.get('/api/supplier/supplier').then(({
                data
            }) => (this.suppliers = data.data));
            this.$Progress.finish();

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



        }

    }

</script>

<style >
   @media print {
            body{
                background-color: white;
            }
            .card-header{

                display: none;
            }
            form{

                display: none;
            }
            .modeify{

                display: none;
            }
            .td{

                display: none;
            }
            #counterSaleTitle{

                display: block !important;
            }
            .infinite-status-prompt{

                display: none;
            }
            footer{

                display: none;
            }
            .card{
                margin-left: -87px;
    margin-top: -2px;
    width: 30cm;
            }

        }
</style>
