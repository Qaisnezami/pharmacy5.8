<template>
    <div class="container-fluid">
        <div v-if="$gate.isSupplier() && $gate.Read()">
            <div class="content-header">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark ">Supplier Payments</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <router-link to="/dashboard">Home</router-link>
                            </li>
                            <li class="breadcrumb-item">
                                <router-link to="/supplier">Supplier</router-link>
                            </li>
                            <li class="breadcrumb-item active">Supplier Payments</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h1 class=" d-inline text-center">{{due}}Af</h1>
                            <div class="card-tools">
                                <button class="btn btn-success" v-if="$gate.Create()" v-shortkey="['ctrl', 'alt', 'n']" @shortkey="addModel" @click="addModel">{{ $t('addnew') }}
                                    <i class="fas fa-plus "></i> </button>
                            </div>
                        </div>

                        <div class="card-body">

                            <table class="table">
                                <thead>
                                    <th>#</th>
                                    <th>Cash</th>
                                    <th style="width:40%">Description</th>
                                    <th>Create By</th>
                                    <th>Modify</th>
                                </thead>
                                <tbody>
                                    <tr v-for="(payment,i) in payments.data" :key="payment.id">
                                        <td>{{(current_page*20)-20 + i+1}}</td>
                                        <td>{{payment.cash}}</td>
                                        <td>{{payment.description}}</td>
                                        <td>{{payment.username}}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle" type="button" data-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                </button>
                                                <div class="dropdown-menu" x-placement="bottom-start"
                                                    style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
                                                    <a class="dropdown-item" @click.prevent="editModel(payment)"
                                                        v-if="$gate.Update()" >Edit</a>
                                                    <a class="dropdown-item"
                                                        @click.prevent="Delete(payment.id)" v-if="$gate.Delete()">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <pagination :data="payments" :limit="5" @pagination-change-page="getResults"></pagination>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="AddVendor" tabindex="-1" role="dialog" aria-labelledby="AddVendorLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="AddVendorLabel"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form @submit.prevent="editMode ? update() :add()" autocomplete="off">

                            <div class="modal-body">
                                <div class="form-group">
                                    <input v-model="form.cash" type="number" name="cash" placeholder="Enter cash"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('cash') }">
                                    <has-error :form="form" field="cash"></has-error>
                                </div>
                                <div class="form-group">
                                    <textarea v-model="form.description" rows="10" type="text" name="description"
                                        placeholder="Enter  description" class="form-control"
                                        :class="{ 'is-invalid': form.errors.has('description') }"></textarea>
                                    <has-error :form="form" field="description"></has-error>
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
        <div v-if="!$gate.isSupplier()">
            <not-found></not-found>
        </div>

    </div>
</template>

<script>
    export default {
        data() {

            return {
                current_page:1,
                payments: {},
                due:'',
                supplier_id: this.$route.params.id,
                form: new Form({
                    id: '',
                    cash: '',
                    description: '',
                    supplier_id: this.$route.params.id,
                })
            }
        },
        methods: {
            getResults(page = 1) {
                axios.get('/api/payment/' ,{
                    params: {
                        id: this.form.supplier_id,
                        page:page
                    },
                })
                    .then(response => {this.payments = response.data;this.current_page = response.data.current_page;});
            },
            load() {
                axios.get('/api/payment' ,{
                    params: {
                        id: this.form.supplier_id,
                    },
                }).then(({data}) => (this.payments = data));
        
                axios.get('/api/payment/'+this.form.supplier_id).then((data)=>{
                        this.due = data.data
                });
            },
            addModel() {
                this.form.reset();
                $('.modal-title').text('Add Payment');
                $('#AddVendor').modal('show');
                this.editMode = false;
            },
            add() {
                this.$Progress.start()
                this.form.post('/api/payment').then((data) => {
                    Fire.$emit('AfterCreate');
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
                    }

                    this.$Progress.finish()
                }).catch(() => {
                    this.$Progress.fail()
                });
            },
            editModel(category) {
                this.form.reset();
                $('.modal-title').text('Edit Payment');
                $('#AddVendor').modal('show');
                this.form.fill(category);
                this.editMode = true;
            },
            update() {
                this.$Progress.start();
                this.form.put('/api/payment/' + this.form.id)
                    .then((data) => {
                        Fire.$emit('AfterCreate');
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
                    }
                        this.$Progress.finish();
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    })
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
                        this.form.delete('/api/payment/' + id).then(() => {
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                            Fire.$emit('AfterCreate');
                        }).catch(() => {
                            Swal.fire(
                                'Failed!',
                                'There was something wronge.',
                                'warning'
                            )
                        })
                    }
                })
            }

        },
        created() {
            this.$Progress.start();
            this.load();

            this.$Progress.finish();

            Fire.$on('AfterCreate', () => {
                this.load();
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
