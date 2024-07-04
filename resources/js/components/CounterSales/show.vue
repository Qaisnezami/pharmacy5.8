<template>
    <div class="container-fluid">
        <div v-if="$gate.isPurchase() && $gate.Read()">
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
                            <li class="breadcrumb-item">
                                <router-link to="/counter-sale">{{ $t('CounterSales')}}</router-link>
                            </li>
                            <li class="breadcrumb-item active">{{$t('Show')}}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $t('CounterSales')}} </h3>
                            <div class="card-tools">
                                <button class="btn btn-success" @click="print">
                                    <i class="fa fa-print "></i> </button>

                            </div>
                        </div>

                        <div class="card-body">
                            <div id="printme" :dir="rtl">

                                <p class="d-inline mt-5" style="margin-right: 10%;">{{ $t('BillNo')}}: {{sales.BillNo}} </p>
                                <p class="d-inline" style="margin-right: 10%;">{{ $t('PatientName')}}: {{sales.patient_name}}</p>
                                <p class="d-inline" style="margin-right: 10%;">{{ $t('Doctor')}}: {{sales.doctor}}</p>
                                <p class="d-inline" style="margin-right: 10%;">{{ $t('SaleDate')}}: {{sales.date}}</p>

                                <table class="table table-striped">
                                    <thead>
                                       
                                        <th style="width:5%">#</th>
                                        <th class="barcode" style="width:5%">{{$t('Barcode')}}</th>
                                        <th style="width:10%">{{$t('Name')}}</th>
                                        <th class="Batch" style="width:10%">{{$t('BatchNo')}}</th>
                                        <th class="Expiry" style="width:10%">{{$t('Expiry')}}</th>
                                        <th style="width:10%">{{$t('quantity')}}</th>
                                        <th style="width:10%">{{$t('MRP')}}</th>
                                        <th style="width:10%">{{$t('Amount')}}</th>
                                    </thead>

                                    <tbody class="tbody">

                                    </tbody>




                                    <tr>
                                        <td></td>
                                        <td style="width:10%"> <strong>{{$t('Total')}}: {{sales.total}} Af</strong> </td>
                                        <td> <strong>{{$t('Discount')}}: {{sales.discount}} Af</strong> </td>
                                        <td> <strong> {{$t('Paid')}}: {{sales.paid}} Af</strong> </td>
                                        <td> <strong> {{$t('Due')}}: {{sales.remaind}} Af</strong></td>
                                    </tr>

                                </table>


                                



                            </div>
                            <div v-if="returnItems.length != 0 ">
                                <h2> Retrun Items</h2>
<table class="table table-striped" >
                                    <thead>
                                       
                                        <th style="width:5%">#</th>
                                        <th class="barcode" style="width:5%">{{$t('Barcode')}}</th>
                                        <th style="width:10%">{{$t('Name')}}</th>
                                        <th class="Batch" style="width:10%">{{$t('BatchNo')}}</th>
                                        <th class="Expiry" style="width:10%">{{$t('Expiry')}}</th>
                                        <th style="width:10%">{{$t('quantity')}}</th>
                                        <th style="width:10%">{{$t('MRP')}}</th>
                                        <th style="width:10%">{{$t('Amount')}}</th>
                                    </thead>

                                    <tbody>
                                            <tr v-for="(item,i) in returnItems"  :key="i" >
                                                <td>
                                                    {{i++}}
                                                </td>
                                                <td>
                                                    {{item.barcode}}
                                                </td>
                                                <td>
                                                    {{item.medicines}}
                                                </td>
                                                <td>
                                                    {{item.batch_no}}
                                                </td>
                                                <td>
                                                    {{item.expiry_date}}
                                                </td>
                                                <td>
                                                    {{item.quantity}}
                                                </td>
                                                <td>
                                                    {{item.MRP}}
                                                </td>
                                                <td>
                                                    {{item.amount}}
                                                </td>
                                            </tr>
                                    </tbody>
                            </table>
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
                i: 0,
                module: null,
                AllSale: null,
                loops: null,
                returnItems : [],
                Company: pharmacy.name,
                id: this.$route.params.id,
                sales: {},
            }
        },
        methods: {
            print() {
                // $('.Expiry').addClass('d-none');
                $('#printme').printThis({
                    importCSS: true,
                    importStyle: false,
                    loadCSS: "../css/style.css",
                    header: '<h1 class="text-center mb-5">' + this.Company + '</h1>',
                });
                // $('.Expiry').removeClass('d-none');
            }
        },
        mounted() {
            
        },
        created() {
            
            this.$Progress.start();
            axios.get('/api/counter-sale/' + this.id).then(({
                data
            }) => {
                this.sales = data.sale;
                this.returnItems = data.return;
                
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

                $('.tbody').html(html);

            });

            
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
