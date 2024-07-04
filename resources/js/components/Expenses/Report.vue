<template>
    <div class="container-fluid" id="report">
        <div v-if="$gate.isPurchaseReport()">
            <div class="content-header">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark ">{{$t('Income')}} {{$t('Report')}}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right" :dir="rtl">
                            <li class="breadcrumb-item">
                                <router-link to="/dashboard">{{$t('dashboard')}}</router-link>
                            </li>
                            <li class="breadcrumb-item active">{{$t('Report')}}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card border-0 rounded-0">
                        <div class="card-header">
                        <h3 class="card-title">{{$t('Report')}}</h3>
                        <div class="card-tools">
                            <button class="btn btn-success" @click="print">
                                <i class="fa fa-print "></i> </button>
                           
                        </div>
                    </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <form @submit.prevent="loadData" autocomplete="off">

                                <div class="row">
                                    
                                    
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="Date">{{$t('From')}}</label>
                                            <input v-model="form.from" id="from" type="text" name="from" 
                                                placeholder="YYYY-MM-DD" autocomplete="off"
                                                class="form-control datepicker"
                                                >

                                            <has-error :form="form" field="from"></has-error>
                                            
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
                            <h1 id="counterSaleTitle"  class="text-center d-none">{{$t('Income')}} {{$t('Report')}}</h1>  
                            <div class="mt-3" style="overflow-x:auto !important;">
                            <table class="table table-hover table-bordered table-sm" :dir="rtl">
                            <thead>
                                <tr>
                                    <th>{{$t('Sales')}}</th>
                                    <th>{{$t('Sales')}} {{$t('Due')}}</th>
                                    <th>{{$t('purchase')}}</th>
                                    <th>{{$t('purchase')}} {{$t('Due')}}</th>
                                    <th>{{$t('Expenses')}}</th>
                                    <th>{{$t('NetIncome')}}</th>
                                    </tr>
                                </thead>
                            <tbody>
                                <tr v-if="list != null">
                                    <td>{{list.sales}}</td>
                                    <td>{{list.salesR}}</td>
                                    <td>{{list.purchases}}</td>
                                    <td>{{list.purchasesR}}</td>
                                    <td>{{list.expenses}}</td>
                                    <td>{{list.income}}</td>
                                </tr>
                            </tbody>
                            
                            
                            </table>
                            </div>
                            
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
                list: null,
                form: new Form({
                    type : 'sale',
                    from:'',
                    to:null
                })
            }
        },
        methods:{
            
            loadData()
            {
                this.$Progress.start()

                this.form.post('/api/report/expenses').then((data)=>{
                    
                    this.list = data.data
                    
                    this.$Progress.finish()
                }).catch(()=>{
                    this.$Progress.fail()
                });
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

<style scoped >
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

        .invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 80%;
    color: #e3342f;
}
</style>
