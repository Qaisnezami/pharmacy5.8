<template>
    <div class="container-fluid">
        <div v-if="$gate.isExpense() && $gate.Read()">
            <div class="content-header">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark ">{{$t('Expenses')}}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right" :dir="rtl">
                            <li class="breadcrumb-item">
                                <router-link to="/dashboard">{{$t('dashboard')}}</router-link>
                            </li>
                            
                            <li class="breadcrumb-item active">{{$t('Expenses')}}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h1 class=" d-inline text-center">{{income}}Af</h1>
                            <div class="card-tools">
                                <button class="btn btn-success" v-if="$gate.Create()" v-shortkey="['ctrl', 'alt', 'n']"  @shortkey="addModel" @click="addModel">{{ $t('addnew') }}
                                    <i class="fas fa-plus "></i> </button>
                            </div>
                        </div>

                        <div class="card-body">

                            <table class="table table-hover table-bordered text-center">
                                <thead>
                                    <th>#</th>
                                    <th>{{$t('Cash')}}</th>
                                    <th>{{$t('Category')}}</th>
                                    <th style="width:40%">{{$t('Description')}}</th>
                                    <th>{{$t('CreateBy')}}</th>
                                    <th>{{$t('Modify')}}</th>
                                </thead>
                                <tbody>
                                    <tr v-for="(expense,i) in expenses.data" :key="expense.id">
                                        <td>{{(current_page*20)-20 + i+1}}</td>
                                        <td>{{expense.cash}}</td>
                                        <td>{{expense.category_name}}</td>
                                        <td>{{expense.description}}</td>
                                        <td>{{expense.username}}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle" type="button" data-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                </button>
                                                <div class="dropdown-menu" x-placement="bottom-start"
                                                    style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
                                                    <a class="dropdown-item" @click.prevent="editModel(expense)"
                                                        v-if="$gate.isUser()">{{$t('Edit')}}</a>
                                                    <a class="dropdown-item"
                                                        @click.prevent="Delete(expense.id)">{{$t('Delete')}}</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <pagination :data="expenses" :limit="5" @pagination-change-page="getResults"></pagination>
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
                                    <input v-model="form.cash" type="number" name="cash" :placeholder="rtl == 'rtl' ? 'مقدار پول را نوشته کنید' : 'Enter cash' "
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('cash') }">
                                    <has-error :form="form" field="cash"></has-error>
                                </div>
                                <div class="form-group">
                                    <textarea v-model="form.description" rows="10" type="text" name="description"
                                        :placeholder="rtl == 'rtl' ? 'توضیحات را نوشته کنید' : 'Enter  description' " class="form-control"
                                        :class="{ 'is-invalid': form.errors.has('description') }"></textarea>
                                    <has-error :form="form" field="description"></has-error>
                                </div>

                                <div class="form-group">
                                    <label for="">{{$t('Category')}}</label><br>
                                    <v-select v-model="form.category" :options="categories" :reduce="name => name.code"
                                        label="name" :class="{ 'is-invalid': form.errors.has('category') }">
                                    </v-select>
                                    <has-error :form="form" field="category"></has-error>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">{{$t('Close')}}</button>
                                <button type="submit" class="btn btn-success">{{$t('Save')}}</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
        <div v-if="!$gate.isExpense()">
            <not-found></not-found>
        </div>

    </div>
</template>

<script>
    export default {
        data() {

            return {
                rtl: null,
                current_page:1,
                search:'',
                categories : [],
                expenses: {},
                income:'',
                income_id: this.$route.params.id,
                form: new Form({
                    id: '',
                    cash: '',
                    description: '',
                    category:'',
                    income_id: this.$route.params.id,
                })
            }
        },
        methods: {
            getResults(page = 1) {
                axios.get('/api/incomes/' + this.form.income_id,{
                    params: {
                        search:this.search,
                        page:page
                    },
                })
                    .then(response => {
                        this.expenses = response.data;
                        this.current_page = response.data.current_page;
                    });
            },
            load() {
                axios.get('/api/incomes/' + this.form.income_id).then(({
                    data
                }) => (this.expenses = data));
                axios.get('/api/expenses/category').then(({
                data
                }) => (this.categories = data.data));

                axios.get('/api/expenses/'+this.form.income_id).then((data)=>{
                        this.income = data.data
                });
            },
            addModel() {
                this.form.reset();
                 if (i18n.locale == 'fa') {
                        $('.modal-title').text('ایجاد مصارف');
                    } else {
                        $('.modal-title').text('Add Expense');
                    }
                
                $('#AddVendor').modal('show');
                this.editMode = false;
            },
            add() {
                this.$Progress.start()
                this.form.post('/api/expenses').then(() => {
                    Fire.$emit('AfterCreate');
                    $('#AddVendor').modal('hide');
                    if (i18n.locale == 'fa') {
                        title = 'مصارف ایجاد شد'
                    } else {
                        title ='Expenses created in successfully'
                    }
                    toast.fire({
                        icon: 'success',
                        title: 'Expense created in successfully'
                    });

                    this.$Progress.finish()
                }).catch(() => {
                    this.$Progress.fail()
                });
            },
            editModel(category) {
                this.form.reset();
                 if (i18n.locale == 'fa') {
                        $('.modal-title').text('تغیر مصارف');
                    } else {
                        $('.modal-title').text('Edit Expense');
                    }
                
                $('#AddVendor').modal('show');
                this.form.fill(category);
                this.editMode = true;
            },
            update() {
                this.$Progress.start();
                this.form.put('/api/expenses/' + this.form.id)
                    .then(() => {
                        Fire.$emit('AfterCreate');
                        $('#AddVendor').modal('hide');
                        var title = null;
                    if (i18n.locale == 'fa') {
                        title = ' مصارف تغیر شد'
                    } else {
                        title ='Expenses Update in successfully'
                    }
                        toast.fire({
                            icon: 'success',
                            title: title
                        })
                        this.$Progress.finish();
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    })
            },

            Delete(id) {
                var title = null;
                var confirmButtonText = null;
                var cancelButtonText = null;
                var deleted = null;
                var dele = null;
                    if (i18n.locale == 'fa') {
                        title = 'آیا مطمین هستین؟';
                        confirmButtonText= '!بلی';
                        cancelButtonText= 'نخیر';
                        deleted = '.فایل موفقانه حذف شد'
                        dele = '!حذف'
                    } else {
                        title ='Are you sure?';
                        confirmButtonText= 'Yes, delete it!';
                        cancelButtonText= 'Cancel';
                        deleted = 'Your file has been deleted.';
                        dele = 'Deleted!';
                    }
              Swal.fire({
                title: title,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: confirmButtonText,
                cancelButtonText: cancelButtonText
              }).then((result) => {
                    // Send Request to the server
                    if (result.value) {
                        this.form.delete('/api/expenses/' + id).then(() => {
                            Swal.fire(
                                 dele,
                        deleted,
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

            this.$parent.searchData = '';
            Fire.$off('search')
            Fire.$on('search', (e) => {
                this.search = e;
                axios.get('/api/incomes/'+ this.form.income_id,{
                    params: {
                        search:e  
                    },
                }).then(({data}) => (this.expenses = data));
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
