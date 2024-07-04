<template>
    <div class="container-fluid">
        <div v-if="$gate.isManufacturer() && $gate.Read()" >
        <div class="content-header" >
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark " >{{$t('manufacturers')}}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right" :dir="rtl">
                        <li class="breadcrumb-item">
                            <router-link to="/dashboard">{{ $t('dashboard') }}</router-link>
                        </li>
                        <li class="breadcrumb-item active">{{$t('manufacturers')}}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card border-0 rounded-0">
                    <div class="card-header">
                        <h3 class="card-title" v-if="rtl == 'rtl'"> {{ $t('Table')}} {{$t('manufacturers')}} </h3>
                            <h3 class="card-title" v-else> {{$t('manufacturers')}} {{ $t('Table')}} </h3>
                       
                            
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
                                    <th>{{ $t('CreateBy')}}</th>
                                    <th>{{$t('Modify')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(manufacturer,i) in manufacturers.data" :key="manufacturer.id">
                                    <td>{{(current_page*20)-20 + i+1}}</td>
                                    <td>{{manufacturer.code}}</td>
                                    <td>{{manufacturer.name}}</td>
                                    
                                    <td>{{manufacturer.username}}</td>
                                    <td>
                                        <a href="#" @click.prevent="editModel(manufacturer)" v-if="$gate.Update()"><i class="fa fa-edit"></i></a>|
                                        <a href="#" @click.prevent="Delete(manufacturer.id)" v-if="$gate.Delete()"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                                <pagination :data="manufacturers" :limit="5" @pagination-change-page="getResults"></pagination>
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
                            <input v-model="form.name" type="text" name="name" :dir="rtl" :placeholder="rtl == 'rtl' ? 'کشور تولید کننده را داخل کنید' : 'Enter manufacturer name' " 
                                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                            <has-error :form="form" field="name"></has-error>
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
        <div v-if="!$gate.isManufacturer()">
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
                    manufacturers:{},
                    form: new Form({
                    id: '',
                    name: '',
                }),
            }
        },
        methods:{
            getResults(page = 1) {
			axios.get('api/manufacturer',{
                    params: {
                        search:this.search,
                        page:page
                    },
                })
				.then(response => {
                    this.manufacturers = response.data;
                    this.current_page = response.data.current_page;
				});
		    },
            load(){
                axios.get('api/manufacturer').then(({data})=>(this.manufacturers = data));
            },
            addModel(){
                this.form.reset();
                if (i18n.locale == 'fa') {
                $('.modal-title').text('ایجاد تولید کننده');
            } else {
                $('.modal-title').text('Add Manufacturer');
            }
               
                $('#AddVendor').modal('show');
                this.editMode = false;
            },
            add(){
                this.$Progress.start()
                this.form.post('api/manufacturer').then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#AddVendor').modal('hide');
                    var title = null;
                    if (i18n.locale == 'fa') {
                        title = ' تولید کننده موفقانه اضافه شد'
                    } else {
                        title ='Manufacturer created  successfully'
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
            editModel(manufacturer){
            this.form.reset();
            if (i18n.locale == 'fa') {
                $('.modal-title').text('تغیر تولید کننده');
            } else {
                $('.modal-title').text('Edit Manufacturer');
            }
            
            $('#AddVendor').modal('show');
            this.form.fill(manufacturer);
            this.editMode = true;
            },
            update(){
            this.$Progress.start();
            this.form.put('api/manufacturer/'+this.form.id)
            .then(()=>{
                  Fire.$emit('AfterCreate');
                  $('#AddVendor').modal('hide');
                  var title = null;
                    if (i18n.locale == 'fa') {
                        title = ' تولید کننده موفقانه اپدید شد'
                    } else {
                        title ='Manufacturer Update  successfully'
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
                    this.form.delete('api/manufacturer/'+id).then(()=>{
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
            this.load();

            this.$Progress.finish();

            Fire.$on('AfterCreate',()=>{
              this.load();
            });

            this.$parent.searchData = '';
            Fire.$off('search')
            Fire.$on('search', (e) => {
                this.search = e;
                axios.get('api/manufacturer',{
                    params: {
                        search:e  
                    },
                }).then(({data}) => (this.manufacturers = data));
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
