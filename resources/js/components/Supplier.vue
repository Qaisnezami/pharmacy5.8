<template>
    <div class="container-fluid">
        <div v-if="$gate.isSupplier() && $gate.Read()" >
        <div class="content-header" >
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark " >{{ $t('suppliers')}}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right" :dir="rtl">
                        <li class="breadcrumb-item">
                            <router-link to="/dashboard">{{ $t('dashboard') }}</router-link>
                        </li>
                        <li class="breadcrumb-item active">{{ $t('suppliers')}}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card border-0 rounded-0">
                    <div class="card-header">
                        <h3 class="card-title">{{ $t('Table')}} {{$t('suppliers')}}</h3>
                            
                        <div class="card-tools">
                            <button class="btn btn-success" v-if="$gate.Create()" v-shortkey="['ctrl', 'alt', 'n']" @shortkey="addModel" @click="addModel" >{{ $t('addnew') }}
                                <i class="fas fa-plus "></i> </button>
                            <!-- <router-link class="btn btn-success" to="/print/user" >Print <i class="fa fa-print fa fw" ></i> </router-link> -->
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-bordered text-center" :dir="rtl">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ $t('Code')}}</th>
                                    <th>{{ $t('Name')}}</th>
                                    <th>{{ $t('Email')}}</th>
                                    <th>{{ $t('Phone')}}</th>
                                    <th>{{ $t('Address')}}</th>
                                    <th>{{ $t('CreateBy')}}</th>
                                    <th>{{ $t('Contract')}}</th>
                                    <th v-if="false">Company</th>
                                    
                                    <th>{{$t('Modify')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(supplier,i) in suppliers.data" :key="supplier.id" >
                                    <td @click="Payments(supplier.id)">{{(current_page*20)-20 + i+1}}</td>
                                    <td @click="Payments(supplier.id)">{{supplier.code}}</td>
                                    <td @click="Payments(supplier.id)">{{supplier.name}}</td>
                                    <td @click="Payments(supplier.id)" v-if="supplier.email" >{{supplier.email}}</td>
                                    <td @click="Payments(supplier.id)" v-else  >NaN</td>
                                    <td @click="Payments(supplier.id)">{{supplier.phone}}</td>
                                    <td @click="Payments(supplier.id)">{{supplier.address}}</td>
                                    <td v-if="false" @click="Payments(supplier.id)">{{supplier.company_name}}</td>
                                    <td @click="Payments(supplier.id)">{{supplier.username}}</td>
                                    <td><a :href="'images/suppliers/'+supplier.contract" >{{supplier.contract}}</a></td>
                                    <td>
                                        <a href="#" @click.prevent="editModel(supplier)" v-if="$gate.Update()"><i class="fa fa-edit"></i></a>|
                                        <a href="#" @click.prevent="Delete(supplier.id)" v-if="$gate.Delete()"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                                <pagination :limit="5" :data="suppliers" @pagination-change-page="getResults"></pagination>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="AddVendor" tabindex="-1" role="dialog" aria-labelledby="AddVendorLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="AddVendorLabel">{{ $t('addnew') }} {{ $t('suppliers') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="editMode ? update() :add()" autocomplete="off">

                    <div class="modal-body">
                        <div class="form-group">
                            <input v-model="form.name" type="text" name="name" :dir="rtl" :placeholder="rtl == 'rtl' ? 'اسم فروشنده را نوشته کنید' : 'Enter supplier name' "
                                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                            <has-error :form="form" field="name"></has-error>
                        </div>
                        <div class="form-group">
                            <input v-model="form.email" type="text" name="email"  :dir="rtl" :placeholder="rtl == 'rtl' ? 'ایمیل فروشنده را نوشته کنید' : 'Enter supplier email' "
                                class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                            <has-error :form="form" field="email"></has-error>
                        </div>
                        <div class="form-group">
                            <input v-model="form.phone" type="text" name="phone" :dir="rtl" :placeholder="rtl == 'rtl' ? 'شماره فروشنده را نوشته کنید' : 'Enter supplier phone number' " 
                                class="form-control" :class="{ 'is-invalid': form.errors.has('phone') }">
                            <has-error :form="form" field="phone"></has-error>
                        </div>
                        <div class="form-group">
                            <select v-model="form.company" v-if="false"  name="company" class="form-control " :class="{ 'is-invalid': form.errors.has('company') }">
                                <option value="">Select Company</option>
                                <option v-for="company in companies" v-bind:value="company.id" :key="company.id">
                                    {{ company.name }}
                                </option>
                            </select>
                            <has-error :form="form" field="company"></has-error>
                        </div>
                        <div class="form-group">
                            <input v-model="form.address" type="text" name="address" :dir="rtl" :placeholder="rtl == 'rtl' ? 'آدرس کمپنی را داخل کنید' : 'Enter company address' " 
                                class="form-control" :class="{ 'is-invalid': form.errors.has('address') }">
                            <has-error :form="form" field="address"></has-error>
                        </div>
                        <div class="form-group">
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" @change="uploadPhoto"  id="photo" name="photo">
                                        <label class="custom-file-label" for="exampleInputFile">{{$t('Choose file')}}</label>
                                    </div>
                                    <div class="input-group-append d-none">
                                        <span class="input-group-text" id="">Upload</span>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">{{ $t('Close')}}</button>
                        <button type="submit" class="btn btn-success">{{ $t('Save')}}</button>
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
        data(){
            return{
                rtl: null,
                current_page:1,
                search:'',
                    editMode:true,
                    suppliers:{},
                    companies:{},
                    form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    phone: '',
                    company: null,
                    address: '',
                    contract: '',
                }),
            }
        },
        methods:{
            Payments(id)
            {
               this.$router.push('/supplier/'+id);
            },
            getResults(page = 1) {
			axios.get('api/supplier',{
                    params: {
                        search:this.search,
                        page:page
                    },
                })
				.then(response => {
                    this.suppliers = response.data;
                    this.current_page = response.data.current_page;
				});
            },
            uploadPhoto(e){
                let file = e.target.files[0];
                if(file['size'] < 2111775)
                {
                  let reader = new FileReader();
                  reader.onloadend = (file) => {
                    // console.log('RESULT', reader.result)
                    this.form.contract = reader.result;
                  }
                  reader.readAsDataURL(file);
                }else{
                  Swal.fire(
                        'Oops...',
                        'You Upload large file more then 2MB',
                        'error'
                      )
                }
            },
            load(){
                axios.get('api/supplier').then(({data})=>(this.suppliers = data));
            },
            addModel(){
                this.form.reset();
                $('#AddVendor').modal('show');
                if (i18n.locale == 'fa') {
                $('.modal-title').text('ایجاد فروشنده');
            } else {
                $('.modal-title').text('Add Supplier');
            }
                this.editMode = false;
            },
            add(){
                this.$Progress.start()
                this.form.post('api/supplier').then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#AddVendor').modal('hide');
                    var title = null;
                    if (i18n.locale == 'fa') {
                        title = '  فروشنده موفقانه اضافه شد'
                    } else {
                        title ='Supplier created  successfully'
                    }
                    toast.fire({
                        icon: 'success',
                        title: title
                        });
                    
                    this.$Progress.finish()
                }).catch(()=>{
                    this.$Progress.fail()
                });
            },
            editModel(supplier){
            this.form.reset();
            $('#AddVendor').modal('show');
            if (i18n.locale == 'fa') {
                $('.modal-title').text('تغیر فروشنده');
            } else {
                $('.modal-title').text('Edit Supplier');
            }
            this.form.fill(supplier);
            this.editMode = true;
            },
            update(){
            this.$Progress.start();
            this.form.put('api/supplier/'+this.form.id)
            .then(()=>{
                  Fire.$emit('AfterCreate');
                  $('#AddVendor').modal('hide');
                  var title = null;
                    if (i18n.locale == 'fa') {
                        title = '  فروشنده موفقانه تغیر شد'
                    } else {
                        title ='Supplier Update  successfully'
                    }
                  toast.fire({
                      icon: 'success',
                      title: title
                    })
                  this.$Progress.finish();
            })
            .catch(()=>{
                this.$Progress.fail();
            })
            },

            Delete(id){
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
                    this.form.delete('api/supplier/'+id).then(()=>{
                      Swal.fire(
                         dele,
                        deleted,
                        'success'
                      )
                      Fire.$emit('AfterCreate');
                    }).catch(()=>{
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
        created () {
            this.$Progress.start();
            axios.get('api/supplier/company').then(({data})=>(this.companies = data))
                .catch(()=>{
                    this.$Progress.fail()
                });
            this.load();

            this.$Progress.finish();

            Fire.$on('AfterCreate',()=>{
              this.load();
            });

            this.$parent.searchData = '';
            Fire.$off('search')
            Fire.$on('search', (e) => {
                this.search = e;
                axios.get('api/supplier',{
                    params: {
                        search:e  
                    },
                }).then(({data}) => (this.suppliers = data));
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
