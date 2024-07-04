
<template>
    <div class="container-fluid">
        <div  v-if="$gate.isCompany() && $gate.Read()" >
        <div class="content-header" >
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark " >{{ $t('companyR') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <router-link to="/dashboard">Home</router-link>
                        </li>
                        <li class="breadcrumb-item active">Company</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card border-0 rounded-0">
                    <div class="card-header">
                        <h3 class="card-title">Companies table</h3>
                            
                        <div class="card-tools">
                            <button class="btn btn-success" v-if="$gate.Create()" v-shortkey="['ctrl', 'alt', 'n']" @shortkey="addModel" @click="addModel" >{{ $t('addnew') }}
                                <i class="fas fa-plus "></i> </button>
                            <!-- <router-link class="btn btn-success" to="/print/user" >Print <i class="fa fa-print fa fw" ></i> </router-link> -->
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Create By</th>
                                    <th>Contract</th>
                                    <th>Modify</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(company,i) in companies.data" :key="company.id" class="tr">
                                    <td>{{(current_page*20)-20 + i+1}}</td>
                                    <td>{{company.code}}</td>
                                    <td>{{company.name}}</td>
                                    <td>{{company.email}}</td>
                                    <td><span class="tag tag-success"></span>{{company.phone}}</td>
                                    <td>{{company.address}}</td>
                                    <td>{{company.username}}</td>
                                    <td><a :href="'images/company/'+company.contract" >{{company.contract}}</a></td>
                                    <td>
                                        <a href="#" @click.prevent="editModel(company)" v-if="$gate.Update()"><i class="fa fa-edit"></i></a>|
                                        <a href="#" @click.prevent="DeleteCompany(company.id)" v-if="$gate.Delete()"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <pagination :data="companies" :limit="5" @pagination-change-page="getResults"></pagination>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="AddCompany" tabindex="-1" role="dialog" aria-labelledby="AddCompanyLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="AddCompanyLabel">Add Company</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="editMode ? updateCompany() :addCompany()" autocomplete="off">

                    <div class="modal-body">
                        <div class="form-group">
                            <input v-model="form.name" type="text" name="name" placeholder="Enter company name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                            <has-error :form="form" field="name"></has-error>
                        </div>
                        <div class="form-group">
                            <input v-model="form.email" type="text" name="email" placeholder="Enter company email"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                            <has-error :form="form" field="email"></has-error>
                        </div>
                        <div class="form-group">
                            <input v-model="form.phone" type="text" name="phone" placeholder="Enter company phone number"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('phone') }">
                            <has-error :form="form" field="phone"></has-error>
                        </div>
                        <div class="form-group">
                            <input v-model="form.address" type="text" name="address" placeholder="Enter company address"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('address') }">
                            <has-error :form="form" field="address"></has-error>
                        </div>
                        <div class="form-group">
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" @change="uploadPhoto"  id="photo" name="photo">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="">Upload</span>
                                    </div>
                            </div>
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

        <div  v-if="!$gate.isCompany()">
            <not-found></not-found>
            </div>



    </div>
</template>

<script>
    export default {

        data(){
            return {
                rtl: null,
                current_page:1,
                editMode:true,
                companies:{},
                roles:{},
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    phone: '',
                    address: '',
                    contract: '',
                }),
                lang: lang
            }
        },
        methods:{
            getResults(page = 1) {
			axios.get('api/company?page=' + page)
				.then(response => {
                    this.companies = response.data;
                    this.current_page = response.data.current_page;
				});
		    },
            loadCompany(){
                axios.get('api/company').then(({data})=>(this.companies = data));
            },
            addCompany(){
                this.$Progress.start()
                this.form.post('api/company').then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#AddCompany').modal('hide');
                    toast.fire({
                        icon: 'success',
                        title: 'Company created in successfully'
                        });
                    
                    this.$Progress.finish()
                }).catch(()=>{
                    this.$Progress.fail()
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
            addModel(){
                this.form.reset();
                $('#AddCompany').modal('show');
                this.editMode = false;
            },


            editModel(company){
            this.form.reset();
            $('#AddCompany').modal('show');
            this.form.fill(company);
            this.editMode = true;
            },


            updateCompany(){
                this.$Progress.start();
            this.form.put('api/company/'+this.form.id)
            .then(()=>{
                  Fire.$emit('AfterCreate');
                  $('#AddCompany').modal('hide');
                  toast.fire({
                      icon: 'success',
                      title: 'User Update in successfully'
                    })
                  this.$Progress.finish();
            })
            .catch(()=>{
                this.$Progress.fail();
            })
            },


            DeleteCompany(id){
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
                    this.form.delete('api/company/'+id).then(()=>{
                      Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
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
        created() {

            
            this.$Progress.start();
            this.loadCompany();

            axios.get('api/roles').then(({data})=>(this.roles = data))
                .catch(()=>{
                    this.$Progress.fail()
                })


            this.$Progress.finish();


            Fire.$on('AfterCreate',()=>{
              this.loadCompany();
            });
        }
    }

</script>

<style scoped >
    .tr:hover
    {
        cursor: pointer;
    }
</style>