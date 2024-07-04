<style scoped>
    .tr:hover {
        cursor: pointer;
    }

</style>
<template>
    <div class="container-fluid">

        <div v-if="$gate.isUser() && $gate.Read()">
            <div class="content-header">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark ">{{$t('users')}}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right" :dir="rtl">
                            <li class="breadcrumb-item">
                                <router-link to="/dashboard">{{$t('dashboard')}}</router-link>
                            </li>
                            <li class="breadcrumb-item active">{{$t('users')}}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card border-0 rounded-0">
                        <div class="card-header">
                            <h3 class="card-title" v-if="rtl=='rtl'">{{$t('Table')}} {{$t('users')}} </h3>
                            <h3 class="card-title" v-else>{{$t('Table')}} {{$t('users')}} </h3>

                            <div class="card-tools">
                                <button v-if="$gate.Create()" class="btn btn-success" @click="addModel">{{$t('addnew')}}
                                    <i class="fas fa-user-plus fa fw"></i> </button>
                                <!-- <router-link class="btn btn-success" to="/print/user" >Print <i class="fa fa-print fa fw" ></i> </router-link> -->
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{$t('Name')}} </th>
                                        <th>{{$t('Email')}}</th>
                                        <th>{{$t('CreatedAt')}}</th>
                                        <th>{{$t('Modify')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr" v-for="(user,i) in users.data" :key="user.id">
                                        <td>{{(current_page*20)-20 + i+1}}</td>
                                        <td>{{user.name | upText}}</td>
                                        <td>{{user.email}}</td>
                                        <td><span class="tag tag-success">{{user.created_at | myDate }}</span></td>
                                        <td>

                                            <a href="#" @click.prevent="editModel(user)" v-if="$gate.Update()"><i
                                                    class="fa fa-edit"></i></a>|
                                            <a href="#" @click.prevent="editRole(user)"><i class="fa fa-lock"></i></a>|
                                            <a href="#" @click.prevent="DeleteUser(user.id)" v-if="$gate.Delete()"><i
                                                    class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <pagination :data="users" :limit="5" @pagination-change-page="getResults"></pagination>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="AddUser" tabindex="-1" role="dialog" aria-labelledby="AddUserLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="AddUserLabel">{{$t('User')}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form @submit.prevent="editMode ? updateUser() :createUser()" autocomplete="off">

                            <div class="modal-body">
                                <div class="form-group">
                                    <input v-model="form.name" type="text" name="name" :dir="rtl"
                                        :placeholder="rtl == 'rtl' ? 'اسم خود را داخل کنید' : 'Enter your name' "
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                    <has-error :form="form" field="name"></has-error>
                                </div>
                                <div class="form-group">
                                    <input v-model="form.email" type="text" name="email" :dir="rtl"
                                        :placeholder="rtl == 'rtl' ? 'ایمیل خود را داخل کنید' : 'Enter your email' "
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                    <has-error :form="form" field="email"></has-error>
                                </div>
                                <div class="form-group">
                                    <input v-model="form.password" type="password" name="password" :dir="rtl"
                                        :placeholder="rtl == 'rtl' ? 'پاسورد خود را داخل کنید' : 'Enter your password' "
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                    <has-error :form="form" field="password"></has-error>
                                </div>
                                <div class="form-group">
                                    <input v-model="form.confirm_password" type="password" name="confirm_password"
                                        :dir="rtl"
                                        :placeholder="rtl == 'rtl' ? 'پاسورد را دوباره داخل کنید' : 'Confirm Password' "
                                        class="form-control"
                                        :class="{ 'is-invalid': form.errors.has('confirm_password') }">
                                    <has-error :form="form" field="confirm_password"></has-error>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger"
                                    data-dismiss="modal">{{$t('Close')}}</button>
                                <button type="submit" class="btn btn-success">{{$t('Save')}}</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
            <div class="modal fade" id="AddUser2" tabindex="-1" role="dialog" aria-labelledby="AddUserLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="AddUserLabel">User Permission</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form @submit.prevent="updateRole()">

                            <div class="modal-body">

                                <table class="table table-bordered">
                                    <tr>
                                        <th>Roles</th>
                                        <th>Permission</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group d-inline">
                                                <input type="checkbox" @change="selectAll" name="select all"
                                                    class="d-inline selectAll">
                                                <label for="" class="d-inline">Select All</label> <br>

                                                <has-error :form="form" field="roles"></has-error>
                                            </div>
                                            <div class="form-group d-inline" v-for="role in roles" :key="role.id">

                                                <input v-model="form.roles" type="checkbox" :value="role.id"
                                                    name="roles[]" class="d-inline roles"
                                                    :class="{ 'is-invalid': form.errors.has('roles') }">
                                                <label for="" class="d-inline">{{role.name}}</label> <br>

                                                <has-error :form="form" field="roles"></has-error>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group d-inline">
                                                <input type="checkbox" @change="selectAll2" name="select all"
                                                    class="d-inline selectAll2">
                                                <label for="" class="d-inline">Select All</label> <br>

                                                <has-error :form="form" field="permissions"></has-error>
                                            </div>
                                            <div class="form-group d-inline" v-for="permission in permissions"
                                                :key="permission.id">

                                                <input v-model="form.permissions" type="checkbox" :value="permission.id"
                                                    name="permissions[]" class="d-inline permissions"
                                                    :class="{ 'is-invalid': form.errors.has('permissions') }">
                                                <label for="" class="d-inline">{{permission.name}}</label> <br>

                                                <has-error :form="form" field="permissions"></has-error>
                                            </div>
                                        </td>
                                    </tr>
                                </table>


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

        <div v-if="!$gate.isUser()">
            <not-found></not-found>
        </div>



    </div>
</template>

<script>
    export default {

        data() {
            return {
                rtl: null,
                current_page: 1,
                search: '',
                editMode: true,
                users: {},
                roles: {},
                permissions: {},
                allSelected: false,
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    password: '',
                    confirm_password: '',
                    roles: [],
                    permissions: []
                }),
            }
        },
        methods: {
            editRole(user) {
                this.form.fill(user);
                axios.get('api/role/' + user.id).then((data) => {
                    this.form.roles = data.data;

                    if (this.roles.length == data.data.length) {
                        $('.selectAll').prop('checked', true);
                    } else {
                        $('.selectAll').prop('checked', false);
                    }

                });
                axios.get('api/permission/' + user.id).then((data) => {
                    this.form.permissions = data.data;

                    if (this.permissions.length == data.data.length) {
                        $('.selectAll').prop('checked', true);
                    } else {
                        $('.selectAll').prop('checked', false);
                    }

                });
                $('#AddUser2').modal('show');

            },
            updateRole() {
                this.$Progress.start();
                this.form.put('api/assign-role/' + this.form.id)
                    .then(() => {
                        Fire.$emit('AfterCreate');
                        $('#AddUser2').modal('hide');
                        toast.fire({
                            icon: 'success',
                            title: 'User Update in successfully'
                        });
                        this.$Progress.finish();
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    })
            },
            selectAll() {
                var selected = []
                if (this.roles.length == this.form.roles.length) {
                    this.form.roles = selected;
                } else {
                    this.roles.forEach(function (role) {
                        selected.push(role.id);
                    });
                    this.form.roles = selected;
                }
                var checkBoxes = $('.roles');
                checkBoxes.prop("checked", !checkBoxes.prop("checked"));
            },
            selectAll2() {

                var selected = []
                if (this.permissions.length == this.form.permissions.length) {
                    this.form.permissions = selected;
                } else {
                    this.permissions.forEach(function (permission) {
                        selected.push(permission.id);
                    });
                    this.form.permissions = selected;
                }
                var checkBoxes = $('.permissions');
                checkBoxes.prop("checked", !checkBoxes.prop("checked"));
            },
            getResults(page = 1) {
                axios.get('api/user', {
                        params: {
                            search: this.search,
                            page: page
                        },
                    })
                    .then(response => {
                        this.users = response.data;
                        this.current_page = response.data.current_page;
                    });
            },
            createUser() {
                this.$Progress.start()
                this.form.post('api/user').then(() => {
                    Fire.$emit('AfterCreate');
                    $('#AddUser').modal('hide');
                    var title = null;
                    if (i18n.locale == 'fa') {
                        title = '  کاربر موفقانه ایجاد شد'
                    } else {
                        title = 'User created in successfully'
                    }
                    toast.fire({
                        icon: 'success',
                        title: title
                    });

                    this.$Progress.finish()
                }).catch(() => {
                    this.$Progress.fail()
                });
            },

            updateUser() {
                this.$Progress.start();
                this.form.put('api/user/' + this.form.id)
                    .then(() => {
                        Fire.$emit('AfterCreate');
                        $('#AddUser').modal('hide');
                        var title = null;
                        if (i18n.locale == 'fa') {
                            title = '  کاربر موفقانه تغیر شد'
                        } else {
                            title = 'User Update in successfully'
                        }
                        toast.fire({
                            icon: 'success',
                            title: title
                        });
                        this.$Progress.finish();
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    })
            },


            addModel() {
                this.form.reset();
                $('#AddUser').modal('show');
                this.editMode = false;
            },



            editModel(user) {
                this.form.reset();
                $('#AddUser').modal('show');
                this.form.fill(user);
                axios.get('api/role/' + user.id).then(({
                    data
                }) => (this.form.roles = data))
                this.editMode = true;
            },




            loadusers() {
                axios.get('api/user').then(({
                    data
                }) => (this.users = data));
            },




            DeleteUser(id) {
                var title = null;
                var confirmButtonText = null;
                var cancelButtonText = null;
                var deleted = null;
                var dele = null;
                if (i18n.locale == 'fa') {
                    title = 'آیا مطمین هستین؟';
                    confirmButtonText = '!بلی';
                    cancelButtonText = 'نخیر';
                    deleted = '.فایل موفقانه حذف شد'
                    dele = '!حذف'
                } else {
                    title = 'Are you sure?';
                    confirmButtonText = 'Yes, delete it!';
                    cancelButtonText = 'Cancel';
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
                        this.form.delete('api/user/' + id).then(() => {
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
        mounted() {
            console.log('Component mounted.')
        },
        created() {


            this.$Progress.start();
            this.loadusers();

            axios.get('api/roles').then(({
                    data
                }) => (this.roles = data))
                .catch(() => {
                    this.$Progress.fail()
                });
            axios.get('api/permissions').then(({
                    data
                }) => (this.permissions = data))
                .catch(() => {
                    this.$Progress.fail()
                });


            this.$Progress.finish();


            Fire.$on('AfterCreate', () => {
                this.loadusers();
            });

            this.$parent.searchData = '';
            Fire.$off('search')
            Fire.$on('search', (e) => {
                this.search = e;
                axios.get('api/user', {
                    params: {
                        search: e
                    },
                }).then(({
                    data
                }) => (this.users = data));
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
