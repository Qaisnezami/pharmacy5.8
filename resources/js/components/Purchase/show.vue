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
                            <li class="breadcrumb-item">
                                <router-link to="/purchase">{{ $t('purchase')}}</router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('Show')}}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $t('Show')}}</h3>
                            <div class="card-tools">
                                <button class="btn btn-success" @click="print">
                                    <i class="fa fa-print "></i> </button>

                            </div>
                        </div>

                        <div class="card-body">
                            <div id="printme" :dir="rtl">



                                <p class="d-inline mt-5 font-weight-bold" style="margin-right: 10%;">{{ $t('BillNo') }}: {{purchases.BillNo}} </p>
                                <p class="d-inline font-weight-bold" style="margin-right: 10%;">{{ $t('suppliers') }}: {{purchases.supplier}}</p>

                                <p class="d-inline font-weight-bold" style="margin-right: 10%;">{{ $t('SupplierRef') }}: {{purchases.supplier_ref}}
                                </p>
                                <p class="d-inline  font-weight-bold" style="margin-right: 10%;">{{ $t('ReceiveDate') }}: {{purchases.date}}</p>

                                <br><br>
                                <table class="table table-striped">
                                    <thead>
                                        <th style="width:5%">#</th>
                                        <th style="width:10%" class="barcode">{{ $t('Barcode') }}</th>
                                        <th style="width:10%">{{ $t('Name') }}</th>
                                            <th style="width:10%">{{ $t('BatchNo') }}</th>
                                            <th style="width:15%">{{ $t('Expiry') }}</th>
                                            <th style="width:5%">{{ $t('quantity') }}</th>
                                            <th style="width:10%">{{ $t('FreeQuantity') }}</th>
                                            <th style="width:10%">{{ $t('Rate') }}</th>
                                        <th style="width:10%" class="barcode">{{ $t('MRP') }}</th>
                                        <th style="width:10%">{{ $t('Amount') }}</th>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(purchases_detail,i) in purchases_details"
                                            :key="purchases_detail.id">
                                            <td>{{i+1}}</td>
                                            <td class="barcode">{{purchases_detail.barcode}}</td>
                                            <td>{{purchases_detail.medicines}} , {{purchases_detail.group}}</td>
                                            <td>{{purchases_detail.batch_no}}</td>
                                            <td>{{purchases_detail.expiry_date}}</td>
                                            <td>{{purchases_detail.quantity}}</td>
                                            <td>{{purchases_detail.free_quantity}}</td>
                                            <td>{{purchases_detail.rate}}</td>
                                            <td class="barcode">{{purchases_detail.MRP}}</td>
                                            <td>{{purchases_detail.amount}} AF</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <tfoot>
                                    <tr>
                                        <td style="width:10%">{{$t('Total')}} :
                                    {{purchases.total}}</td>
                                <td style="width:10%">{{$t('Discount')}} :
                                    {{purchases.discount}}</td>
                                <td style="width:10%">{{$t('Paid')}} :
                                    {{purchases.paid}}</td>
                                <td style="width:10%"> {{$t('Due')}} :
                                    {{purchases.remind}}</td>
                                    </tr>
                                </tfoot>
                                

                            </div>
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

                id: this.$route.params.id,
                purchases: {},
                purchases_details: {}
            }
        },
        methods: {
            print() {
                $('#printme').printThis({
                    importCSS: true,
                    importStyle: false,
                    loadCSS: "../css/style.css",
                    header: '<h1 class="text-center mb-1">' + pharmacy.full_name + '</h1><h4 class="text-center"><strong>'+ this.$t("Address") +' : </strong>'+pharmacy.address+'</h4><h4 class="text-center mb-3"><strong>'+ this.$t("Phone") +' : </strong>'+pharmacy.phone+'</h4>',
                });
                
            }
        },
        mounted() {
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
        created() {
            this.$Progress.start();

            axios.get('/api/purchases/' + this.id).then(({
                data
            }) => {
                this.purchases = data.purchase;
                this.purchases_details = data.purchase_detail;


            });
            this.$Progress.finish();


            
        }
    }

</script>
