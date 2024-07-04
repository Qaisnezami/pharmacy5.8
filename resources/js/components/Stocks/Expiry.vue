<template>
    <div class="container-fluid">
        <div v-if="$gate.isExpiry() && $gate.Read()" >
        <div class="content-header" >
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark" >{{ $t('ItemExpiryDate')}}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <router-link to="/dashboard">{{ $t('dashboard')}}</router-link>
                        </li>
                        <li class="breadcrumb-item active">{{ $t('ItemExpiryDate')}}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card border-0 rounded-0">
                    <div class="card-header">
                        <h3 class="card-title">{{ $t('ItemExpiryDate')}} {{ $t('Table')}}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ $t('Barcode')}}</th>
                                    <th>{{ $t('Name') }}</th>
                                    <th>{{ $t('BatchNo') }}</th>
                                    <th>{{ $t('Expiry') }}</th>
                                    <th>{{ $t('quantity') }}</th>
                                    <th>{{ $t('MRP') }}</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(stock,i) in stocks.data" :key="stock.id">
                                    <td>{{(current_page*20)-20 + i+1}}</td>
                                    <td>{{stock.barcode}}</td>
                                    <td>{{stock.name}}</td>
                                    <td>{{stock.batch_no}}</td>
                                    <td v-if="date > stock.expiry_date" class="bg-danger" >{{stock.expiry_date}}</td>
                                    <td v-else>{{stock.expiry_date}}</td>
                                    <td>{{stock.quantity}}</td>
                                    <td>{{stock.MRP}}</td>
                                    
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <pagination :data="stocks" :limit="5" @pagination-change-page="getResults"></pagination>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <div v-if="!$gate.isExpiry()">
            <not-found></not-found>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return{
                current_page:1,
                date : '',
                search:'',
                    stocks:{},     
            }
        },
        methods:{
            getResults(page = 1) {
			axios.get('/api/stocks/expiry',{
                    params: {
                        search:this.search,
                        page:page
                    },
                })
				.then(response => {
                    this.stocks = response.data;
                    this.current_page = response.data.current_page;
				});
		    },
            load(){
                axios.get('/api/stocks/expiry').then(({data})=>(this.stocks = data));
            },

        },
        created () {
            this.$Progress.start();
            this.load();

            Fire.$emit('Notify');

            this.$Progress.finish();

            this.$parent.searchData = '';
            Fire.$off('search')
            Fire.$on('search', (e) => {
                this.search = e;
                axios.get('/api/stocks/expiry',{
                    params: {
                        search:e  
                    },
                }).then(({data}) => (this.stocks = data));
            });
            this.date =DateFormat();



        }
    }
</script>
