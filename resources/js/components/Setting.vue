<template>
    <div class="container-fluid">
        <div v-if="$gate.isSetting() && $gate.Read()" >
        <div class="content-header" >
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark " >{{$t('setting')}}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right" :dir="rtl">
                        <li class="breadcrumb-item">
                            <router-link to="/dashboard">{{$t('dashboard')}}</router-link>
                        </li>
                        <li class="breadcrumb-item active">{{$t('setting')}}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-body">
                        <div class="text-center">
                            <div class="loader d-none"></div>
                            <button class="btn btn-primary float-right database" data-toggle="tooltip" data-placement="top"  data-original-title="Take Database Backup"  @click="backup"><i class="fa fa-database"></i> </button>
                        </div>
                        
                        <div class="box box-primary d-flex justify-content-center">
                            <div class="box-body box-profile">
                                <img class="profile-user-img img-responsive img-circle" :src="getPhoto()"
                                    alt="User profile picture">
                            </div>
                            
                        </div>
                        

                        <div>
                            <form class="form-horizontal">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="inputName" class=" control-label" v-if="rtl == 'rtl'" >{{$t('Name')}} {{ $t('short')}} {{$t('Pharmacy')}} </label>
                                            <label for="inputName" class=" control-label" v-else>{{$t('Pharmacy')}} {{ $t('short')}} {{$t('Name')}}</label>
                                           
                                            <input type="name" v-model="form.name" @keyup="changeName" class="form-control" id="inputName"
                                                placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="inputName" class=" control-label" v-if="rtl == 'rtl'" >{{$t('Name')}} {{$t('full')}} {{$t('Pharmacy')}} </label>
                                            <label for="inputName" class=" control-label" v-else>{{$t('Pharmacy')}} {{$t('full')}} {{$t('Name')}}</label>
                                           
                                            <input type="name" v-model="form.full_name"  class="form-control" id="inputName"
                                                placeholder="Full Name">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                           
                                            <label for="inputName" class=" control-label" >{{$t('Address')}}</label>
                                           
                                            <input type="name" v-model="form.address"  class="form-control" id="inputName"
                                                placeholder="Address">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                           
                                            <label for="inputName" class=" control-label" >{{$t('Phone')}}</label>
                                           
                                            <input type="name" v-model="form.phone"  class="form-control" id="inputName"
                                                placeholder="Phone">
                                        </div>
                                    </div>
                                    
                                        <div class="col-md-12">
                                    <div class="form-group">
                                            <label for="exampleInputFile" class=" col-form-label">{{$t('Logo')}}</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input"
                                                        @change="updateProfilePhoto" id="photo" name="photo">
                                                    <label class="custom-file-label" for="exampleInputFile">Choose
                                                        file</label>
                                                </div>
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="">Upload</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="col-md-12">
                                    <div class="form-group">
                                            <label for="exampleInputFile" class=" col-form-label">{{$t('Background')}}</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input"
                                                        @change="updateProfilePhoto2" id="photo" name="photo">
                                                    <label class="custom-file-label" for="exampleInputFile">Choose
                                                        file</label>
                                                </div>
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="">Upload</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 ">
                                            <button type="submit" @click.prevent="upateSettingInfo"
                                                class="btn btn-success">Update</button>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <div v-if="!$gate.isSetting()">
            <not-found></not-found>
        </div>
    </div>
</template>

<script>

    export default {

        data() {
            return {
                rtl: null,
                form: new Form({
                name: '',
                   logo:'',
                   background:'',
                   full_name:'',
                   address:'',
                   phone:''

                })
            }

        },

        mounted() {
            
        },

        //firing the profile event here
        methods: {
            backup()
            {
                $('.loader').removeClass('d-none');
                $('.database').addClass('d-none');
                 this.$Progress.start();
                axios.post('/api/backup').then((data)=>{
                   
                    
                    this.$Progress.finish();
                        toast.fire({
                        icon: 'success',
                        title: 'Backup Take successfully'
                        });
                        $('.loader').addClass('d-none');
                        $('.database').removeClass('d-none');
                })
                .catch(() => {
                    toast.fire({
                        icon: 'error',
                        title: 'Backup Taking Filed'
                        });
                        this.$Progress.fail();

                    });

            },
            getPhoto(){
              let prefix = (this.form.logo.match(/\//) ? '' : '/images/logo/');
              Fire.$emit('changelogo', prefix + this.form.logo);
              return prefix + this.form.logo;
            },
            upateSettingInfo() {
                this.$Progress.start();
                this.form.put('/api/setting')
                    .then(() => {
                        this.$Progress.finish();
                        toast.fire({
                        icon: 'success',
                        title: 'Setting update in successfully'
                        });
                    })
                    .catch(() => {
                        this.$Progress.fail();

                    });
            },

            changeName()
            {
                Fire.$emit('changeCompanyName', this.form.name);
            },

            updateProfilePhoto(e) {
                 let file = e.target.files[0];
                if(file['size'] < 2111775)
                {
                  let reader = new FileReader();
                  reader.onloadend = (file) => {
                    // console.log('RESULT', reader.result)
                    this.form.logo = reader.result;
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
            updateProfilePhoto2(e) {
                 let file = e.target.files[0];
                if(file['size'] < 2111775)
                {
                  let reader = new FileReader();
                  reader.onloadend = (file) => {
                    // console.log('RESULT', reader.result)
                    this.form.background = reader.result;
                  }
                  reader.readAsDataURL(file);

                }else{
                  Swal.fire(
                        'Oops...',
                        'You Upload large file more then 2MB',
                        'error'
                      )
                }
            }
        },
        

        created() {
            axios.get("/api/setting")
                .then(({
                    data
                }) => (this.form.fill(data)));

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


<style  scoped>
.loader {
  border: 4px solid #f3f3f3; /* Light grey */
  border-top: 4px solid #3498db; /* Blue */
  border-radius: 50%;
  width: 30px;
  height: 30px;
  animation: spin 2s linear infinite;
  float: right;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>