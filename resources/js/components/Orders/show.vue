<template>
    <div class="container-fluid">
        <div v-if="$gate.isOrder() && $gate.Read()">
            <div class="content-header">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark ">{{ $t('Order')}}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right" :dir="rtl">
                            <li class="breadcrumb-item">
                                <router-link to="/dashboard">{{ $t('dashboard') }}</router-link>
                            </li>
                            <li class="breadcrumb-item">
                                <router-link to="/order">{{ $t('Order') }}</router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('Show') }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{$t('Show')}}</h3>
                            <div class="card-tools">
                                <button class="btn btn-success" @click="print">
                                    <i class="fa fa-print "></i> </button>

                            </div>
                        </div>

                        <div class="card-body" >
                            <div id="printme" :dir="rtl">
                             
                                <p id="bill"  class="d-inline col-md-12 d-40 font-weight-bold" style="margin-right: 18%;">{{ $t('BillNo') }}: {{order.BillNo}} </p>
                                   
                                        <p id="suppiler" class="d-inline col-md-12 font-weight-bold" style="margin-right: 18%;">{{ $t('suppliers') }}: {{order.supplier}}</p>
                                  
                                   

                                        <p id="date" class="d-inline col-md-12 font-weight-bold" style="margin-right: 18%;">{{ $t('OrderDate') }}: {{order.date}}</p>

                                <table class="table table-bordered mt-3 text-center" :dir="rtl">
                                    <thead class="thead">
                                        <th style="width:5%">#</th>
                                        <th style="width:20%">{{ $t('Name') }}</th>
                                            <th style="width:20%">{{ $t('group') }}</th>
                                            <th style="width:20%">{{ $t('quantity') }}</th>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(orders_detail,i) in orders_details" :key="orders_detail.id">
                                            <td class="text-center">{{i+1}}</td>
                                            <td>{{orders_detail.medicines}}</td>
                                            <td>{{orders_detail.group}}</td>
                                            <td>{{orders_detail.quantity}}</td>

                                        </tr>
                                    </tbody>
                                </table>

                                <div class="row">
                                    <div class="col-md-12">
                                        <p>{{order.description}}</p>
                                    </div>

                                </div>
                            </div>
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
                Company:pharmacy.name,
                id: this.$route.params.id,
                order: {},
                orders_details: {}
            }
        },
        methods: {
            print() {
                $('#printme').printThis({
                    importCSS: true,            
                        importStyle: false,  
                        header: ' <h1 class="text-center">'+this.Company+'</h1>',
                });
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        created() {
            this.$Progress.start();

            axios.get('/api/order/' + this.id).then(({
                data
            }) => {
                this.order = data.order;
                this.orders_details = data.order_details;
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
