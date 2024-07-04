<template>
    <div class="container-fluid" >
        <div v-if="$gate.isDashboard()&& $gate.Read()">
        <div class="content-header">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{{$t('dashboard')}}</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-primary rounded-0">
                            <div class="inner">
                                <h3 v-if="sale">{{sale}} $</h3>
                                <h3 v-else>0 $</h3>
                                <p>Today Sales</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <router-link to="/counter-sale" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></router-link>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger rounded-0">
                            <div class="inner">
                                <h3 v-if="expenses">{{expenses}} $</h3>
                                <h3 v-else>0 $</h3>

                                <p>Today Expenses</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-chart"></i>
                            </div>
                            <router-link to="/income" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></router-link>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success rounded-0">
                            <div class="inner">
                                <h3 v-if="stock">{{stock}}<sup style="font-size: 20px"></sup></h3>
                                <h3 v-else>0<sup style="font-size: 20px"></sup></h3>

                                <p>Low Stock</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-coins"></i>
                            </div>
                            <router-link to="/stock/low" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></router-link>
                        </div>
                    </div>

                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning rounded-0">
                            <div class="inner">
                                <h3 v-if="expiry">{{expiry}}</h3>
                                <h3 v-else>0</h3>

                                <p>Near to Expiry</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-briefcase"></i>
                            </div>
                            <router-link to="/stock/expiry" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></router-link>
                        </div>
                    </div>
                    <!-- ./col -->

                    <!-- ./col -->
                </div>
            </div>
        </div>
        <!-- <div class="row">
            <div class="col-md-6">
                    <h2>Sales</h2>
        <column-chart  :data="sales"  :colors="['#FFCE56', '#666']"></column-chart>
            </div>
            <div class="col-md-6">
                    <h2>Purchases</h2>
        <column-chart  :data="purchases"  :colors="['#FF6384', '#666']"></column-chart>
            </div>
        </div> -->

        </div>
        <div v-if="!$gate.isDashboard()">
            <not-found></not-found>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                sale : 0,
                stock:0,
                expiry:0,
                medicine:0,
                sales:[
                    ['Jan', 0],
                     ['Feb', 0],
                     ['Mar', 0],
                      ['Apr', 0],
                      ['May', 0],
                      ['Jun', 0],
                      ['Jul', 0],
                      ['Aug', 0],
                      ['Sep', 0],
                      ['Oct', 0],
                      ['Nov', 0],
                      ['Dec', 0],

                    ],
                purchases:[
                    ['Jan', 0],
                     ['Feb', 0],
                     ['Mar', 0],
                      ['Apr', 0],
                      ['May', 0],
                      ['Jun', 0],
                      ['Jul', 0],
                      ['Aug', 0],
                      ['Sep', 0],
                      ['Oct', 0],
                      ['Nov', 0],
                      ['Dec', 0],
                    ],

            }
        },
        mounted() {
            this.$Progress.start()
            axios.get('/api/dashboard').then((data)=>{
                this.sale = data.data.sale
                this.stock = data.data.stock
                this.expiry = data.data.expiry
                this.medicine = data.data.medicine
                this.sales = data.data.sales
                this.purchases = data.data.purchases
            })
            .catch(()=>{

            })
            this.$Progress.finish()
        },
        created(){
            this.$parent.searchData = '';
            Fire.$off('search')


        }
    }

</script>
