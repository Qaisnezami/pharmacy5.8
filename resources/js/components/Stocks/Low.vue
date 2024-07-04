<template>
    <div class="container-fluid">
        <div v-if="$gate.isLowStock() && $gate.Read()">
            <div class="content-header">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark ">Low Stocks</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <router-link to="/dashboard">Home</router-link>
                            </li>
                            <li class="breadcrumb-item active">Low Stocks</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card border-0 rounded-0">
                        <div class="card-header">
                            <h3 class="card-title">Low Stocks Table</h3>
                            <div class="card-tools">

                                <select class="form-control" v-model="selected">
                                    <option value="all">All</option>
                                    <option value="low">Low Stock</option>
                                    <option value="high">High Stock</option>
                                </select>


                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ $t('Barcode')}}</th>
                                        <th>{{ $t('Name')}}</th>
                                        <th>{{ $t('group')}}</th>
                                        <th>{{ $t('manufacturer')}}</th>
                                        <th>{{ $t('classification')}}</th>
                                        <th>{{ $t('doctor')}}</th>
                                        <th>{{ $t('quantity')}}</th>
                                        <th>{{$t('Modify')}}</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(stock,i) in stocks.data" :key="stock.id"
                                        v-if="selection(stock.quantity)">
                                        <td @click="LowDetails(stock.id)">{{(current_page*20)-20 + i+1}}</td>
                                        <td @click="LowDetails(stock.id)">{{stock.barcode}}</td>
                                        <td @click="LowDetails(stock.id)">{{stock.name}}</td>
                                        <td @click="LowDetails(stock.id)">{{stock.groupName}}</td>
                                        <td @click="LowDetails(stock.id)">{{stock.manufacturerName}}</td>
                                        <td @click="LowDetails(stock.id)">{{stock.classificationName}}</td>
                                        <td @click="LowDetails(stock.id)">{{stock.doctorName}}</td>
                                        <td @click="LowDetails(stock.id)" v-if="stock.quantity < 5" class="bg-danger">
                                            {{stock.quantity}}</td>
                                        <td @click="LowDetails(stock.id)" v-else>{{stock.quantity}}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle" type="button" data-toggle="dropdown"
                                                    style="padding: 0px;" aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                </button>
                                                <div class="dropdown-menu" x-placement="bottom-start"
                                                    style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">

                                                    <a class="dropdown-item" @click.prevent="addToExcite(stock.id)">Add
                                                        To Exciting Orders</a>
                                                    <a class="dropdown-item" @click.prevent="addToNew(stock.id)">Create
                                                        New Order</a>
                                                </div>
                                            </div>
                                        </td>
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
        <div v-if="!$gate.isLowStock()">
            <not-found></not-found>
        </div>

        <div class="modal fade" id="AddVendor" tabindex="-1" role="dialog" aria-labelledby="AddVendorLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="AddVendorLabel">Add Cash</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="form.Mode ? addNew() :AddExcite()">

                        <div class="modal-body">

                            <div class="form-group">
                                <label for="">Quantity</label>
                                <input v-model="form.quantity" type="number" name="quantity"
                                    placeholder="Enter item quantity" class="form-control"
                                    :class="form.errors.has('quantity') ? 'is-invalid' : '' || form.errors.has('billNo') ? 'is-invalid' : ''"
                                    
                                    >
                                <has-error :form="form" field="quantity"></has-error>
                                <has-error :form="form" field="billNo"></has-error>
                            </div>

                            <div class="form-group" v-if="form.Mode">
                                <label for="">Supplier</label><br>
                                <v-select v-model="form.supplier" :options="suppliers"
                                    class="Nextrow" :reduce="name => name.code" label="name"
                                    id="suppliers"
                                    :class="{ 'is-invalid': form.errors.has('supplier') }">
                                </v-select>
                                <has-error :form="form" field="supplier"></has-error>
                            </div>
                             
                            <div class="custom-control custom-radio" v-for="(order,i) in orders" :key="i" v-else>
                                <input class="custom-control-input" type="radio" v-model="form.billNo"
                                    :id="'customRadio'+i" :value="order.id" name="customRadio">
                                <label :for="'customRadio'+i" :class="{ 'is-invalid': form.errors.has('billNo') }" class="custom-control-label" >{{order.BillNo}} --
                                    {{order.supplier}}</label>
                            </div>
                           

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Save</button>
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
                
                selected: 'all',
                current_page: 1,
                search: '',
                orders: {},
                stocks: {},
                suppliers: [],
                form: new Form({
                    id: null,
                    quantity: null,
                    billNo: null,
                    supplier:null,
                    Mode: false,
                })
            }
        },
        methods: {
            getResults(page = 1) {
                axios.get('/api/stocks/low', {
                        params: {
                            search: this.search,
                            page: page,
                        },
                    })
                    .then(response => {
                        this.stocks = response.data;
                        this.current_page = response.data.current_page;
                    });
            },
            addToExcite(id) {
                this.form.Mode = false;
                this.form.billNo = null
                axios.get('/api/stocks/padding/orders').then(({
                    data
                }) => (this.orders = data));


                this.form.id = id;
                $('#AddVendor').modal('show');
                $('#AddVendorLabel').text('Add To Exciting Orders');
            },
            AddExcite() {
                this.$Progress.start()
                this.form.post('/api/stocks/order/save-to-excite').then(() => {
                    Fire.$emit('AfterCreate');
                    $('#AddVendor').modal('hide');
                    toast.fire({
                        icon: 'success',
                        title: 'item add to order successfully'
                    });

                    this.$Progress.finish()
                }).catch((data) => {
                    
                    this.$Progress.fail();
                })
            },
            addToNew(id)
            {
                this.form.Mode = true;
                this.form.billNo = null;
                this.form.id = id;
                $('#AddVendor').modal('show');
                $('#AddVendorLabel').text('Add To New Order');

            },
            addNew()
            {
                this.$Progress.start()
                this.form.post('/api/stocks/order/save-to-excite').then(() => {
                    Fire.$emit('AfterCreate');
                    $('#AddVendor').modal('hide');
                    toast.fire({
                        icon: 'success',
                        title: 'item add to order successfully'
                    });

                    this.$Progress.finish()
                }).catch((data) => {
                    
                    this.$Progress.fail();
                });
            },
            selection(q) {
                if (this.selected === 'all') {
                    return true
                }
                if (this.selected === 'low') {
                    return q < 5
                }
                if (this.selected === 'high') {
                    return q > 5
                }

            },
            load() {
                axios.get('/api/stocks/low').then(({
                    data
                }) => (this.stocks = data));
                axios.get('/api/supplier/supplier').then(({
                data
            }) => (this.suppliers = data.data));
            },
            LowDetails(id) {
                this.$router.push('/stock/low/details/' + id);
            }


        },
        created() {
            this.$Progress.start();
            this.load();
            this.$Progress.finish();

            this.$parent.searchData = '';
            Fire.$off('search')
            Fire.$on('search', (e) => {
                this.search = e;
                axios.get('/api/stocks/low', {
                    params: {
                        search: e
                    },
                }).then(({
                    data
                }) => (this.stocks = data));
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
