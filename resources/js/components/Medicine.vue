<template>
    <div class="container-fluid" id="Medicine">
        <div v-if="$gate.isMedicines() && $gate.Read()">
            <div class="content-header">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark ">{{ $t('Medicines') }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right" :dir="rtl">
                            <li class="breadcrumb-item">
                                <router-link to="/dashboard">{{ $t('dashboard') }}</router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('Medicines') }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
            <div class="row justify-content-center">
                <div class="col-sm-12">
                    <div class="card border-0 rounded-0">
                        <div class="card-header">
                            <h3 class="card-title" v-if="rtl == 'rtl'"> {{ $t('Table')}} {{ $t('Medicines') }} </h3>
                            <h3 class="card-title" v-else> {{ $t('Medicines') }} {{ $t('Table')}} </h3>

                            <div class="card-tools">
                                <button class="btn btn-success" v-if="$gate.Create()" v-shortkey="['ctrl', 'alt', 'n']"
                                    @shortkey="addModel" @click="addModel">{{ $t('addnew') }}
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
                                        <th>{{ $t('Barcode')}}</th>
                                        <th>{{ $t('Name')}}</th>
                                        <th>{{ $t('group')}}</th>
                                        <th>{{ $t('manufacturer')}}</th>
                                        <th>{{ $t('classification')}}</th>
                                        <th>{{ $t('doctor')}}</th>
                                        <th>{{ $t('CreateBy')}}</th>
                                        <th v-if="$gate.Activation()">{{ $t('Activation')}}</th>
                                        <th>{{$t('Approval')}}</th>
                                        <th>{{$t('Modify')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(medicine,i) in medicines.data" :key="medicine.id">
                                        <td>{{(current_page*20)-20 + i+1}}</td>
                                        <td>{{medicine.barcode}}</td>
                                        <td class="text-left">{{medicine.name}}</td>
                                        <td>{{medicine.groupName}}</td>
                                        <td>{{medicine.manufacturerName}}</td>
                                        <td>{{medicine.classificationName}}</td>
                                        <td>{{medicine.doctorName}}</td>
                                        <td>{{medicine.username}}</td>
                                        <td class="text-center" v-if="$gate.Activation()">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" @change="activation"
                                                    v-if="medicine.isactive" checked :value="medicine.id"
                                                    :id="'check'+medicine.id">
                                                <input type="checkbox" class="custom-control-input" v-else
                                                    @change="activation" :value="medicine.id" :id="'check'+medicine.id">
                                                <label class="custom-control-label" :for="'check'+medicine.id"></label>
                                            </div>

                                        </td>
                                        <td v-if="medicine.approve==1" class="text-center">
                                            <span class="bg-success px-1">{{ $t('Approved')}}</span>
                                        </td>
                                        <td class="text-center" :id="medicine.id"
                                            v-else-if="!medicine.approve && $gate.Approval()">
                                            <a href="#" @click.prevent="approved"><i class="fas fa-check"></i></a>|
                                            <a href="#" @click.prevent="Notapproved"><i class="fas fa-times"></i></a>
                                        </td>
                                        <td class="text-center" v-else-if="medicine.approve==2">
                                            <span class="bg-danger px-1">{{ $t('NotApproved')}} </span>
                                        </td>
                                        <td class="text-center" v-else>
                                            <span class="bg-danger px-1">{{ $t('padding')}}</span>
                                        </td>
                                        <td>
                                            <a href="#" @click.prevent="editModel(medicine)" v-if="$gate.Update()"><i
                                                    class="fa fa-edit"></i></a>|
                                            <a href="#"  @click.prevent="Delete(medicine.id)" :id="'deleted'+medicine.id" v-if="$gate.Delete() && medicine.approve!=1"><i
                                                    class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer" :dir="rtl">
                            <pagination :data="medicines" :limit="5" @pagination-change-page="getResults"></pagination>
                        </div>
                    </div>
                </div>
            </div>
            <div id="model" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
                aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="AddVendorLabel"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form @submit.prevent="editMode ? update() :add()" autocomplete="off">

                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">{{ $t('Barcode')}}*</label>
                                            <input v-model="form.barcode" type="text"
                                                style="display: inline; width: 82%;" name="barcode" placeholder=""
                                                class="w-60" :class="{ 'is-invalid': form.errors.has('barcode') }">
                                            <a @click="generator"
                                                class="btn btn-secondary text-white float-right d-inline"
                                                style="    padding: 2px 8px;"><i class="fa fa-spinner fa-1x"></i> </a>
                                            <has-error :form="form" field="barcode"></has-error>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">{{ $t('Name')}}*</label><br>
                                            <input v-model="form.name" list="browsers1" type="text"
                                                @keyup="loadMedicine" name="name" class="w-100"
                                                :class="{ 'is-invalid': form.errors.has('name') }">

                                            <has-error :form="form" field="name"></has-error>
                                            <datalist id="browsers1">

                                            </datalist>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">{{ $t('group')}} </label><br>

                                            <v-select v-model="form.group" :options="groups" :reduce="name => name.code"
                                                label="name"></v-select>
                                            <has-error :form="form" field="group"></has-error>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">{{ $t('classification')}}</label><br>
                                            <v-select v-model="form.classification" :options="classifications"
                                                :reduce="name => name.code" label="name"></v-select>
                                            <has-error :form="form" field="classification"></has-error>
                                        </div>
                                    </div>
                                    <div class="col-md-4">

                                        <div class="form-group">
                                            <label for="">{{ $t('manufacturer')}}</label><br>
                                            <v-select v-model="form.manufacturer" :options="manufacturers"
                                                :reduce="name => name.code" label="name"></v-select>
                                            <has-error :form="form" field="manufacturer"></has-error>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">{{ $t('doctor')}}</label><br>
                                            <v-select v-model="form.doctor" :options="doctors"
                                                :reduce="name => name.code" label="name"></v-select>
                                            <has-error :form="form" field="doctor"></has-error>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">{{ $t('Image')}}</label><br>
                                            <input type="file" name="image" id="image" @change="uploadPhoto"
                                                class="w-100 dropify"
                                                :class="{ 'is-invalid': form.errors.has('image') }">
                                            <has-error :form="form" field="image"></has-error>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="">{{ $t('Description')}}</label><br>
                                            <textarea v-model="form.description" type="text" name="description"
                                                style="height: 200px;" class="w-100"
                                                :class="{ 'is-invalid': form.errors.has('description') }"></textarea>
                                            <has-error :form="form" field="description"></has-error>
                                        </div>
                                    </div>
                                    <div class="col-md-4" id="isactive" v-if="$gate.Activation()">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" value="1"
                                                v-model="form.isactive" id="customSwitch1">
                                            <label class="custom-control-label"
                                                for="customSwitch1">{{ $t('Activation')}}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4" id="isactive" v-if="$gate.Approval()">
                                        <div class="custom-control custom-switch2">
                                            <input type="checkbox" class="custom-control-input2" value="1"
                                                v-model="form.approve" id="approve">
                                            <label class="custom-control-label2"
                                                for="customSwitch2">{{ $t('Approval')}}</label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger"
                                    data-dismiss="modal">{{ $t('Close')}}</button>
                                <button type="submit" class="btn btn-success">{{ $t('Save')}}</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
        <div v-if="!$gate.isMedicines()">
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
                editMode: true,
                search: '',
                medicines: {},
                manufacturers: [],
                classifications: [],
                doctors: [],
                groups: [],
                lang: lang,
                form: new Form({
                    id: '',
                    name: '',
                    barcode: '',
                    classification: '',
                    manufacturer: '',
                    group: '',
                    doctor: '',
                    image: '',
                    isactive: '',
                    description: '',
                    approve: 0,

                }),
            }
        },
        methods: {
            getResults(page = 1) {
                axios.get('api/medicine', {
                        params: {
                            page: page,
                            search: this.search

                        },
                    })
                    .then(response => {
                        this.medicines = response.data;
                        this.current_page = response.data.current_page;

                    });
            },
            uploadPhoto(e) {
                let file = e.target.files[0];
                if (file['size'] < 2111775) {
                    let reader = new FileReader();
                    reader.onloadend = (file) => {
                        // console.log('RESULT', reader.result)
                        this.form.image = reader.result;
                    }
                    reader.readAsDataURL(file);
                } else {
                    Swal.fire(
                        'Oops...',
                        'You Upload large file more then 2MB',
                        'error'
                    )
                }
            },
            load() {
                axios.get('api/medicine').then(({
                    data
                }) => (this.medicines = data));

            },
            addModel() {
                this.form.reset();
                if (this.lang == 'en') {
                    $('.modal-title').text('Add Medicine');
                } else {
                    $('.modal-title').text('ایجاد ادویه');
                }

                $('.group').val('').trigger('change');
                $('.classification').val('').trigger('change');
                $('.manufacturer').val('').trigger('change');
                $('.doctor').val('').trigger('change');
                $('#customSwitch1').attr('checked', false);
                $(".dropify-preview").css("display", "");
                $(".dropify-render").html("");
                $("#isactive").css('display', '');
                this.form.barcode = Math.floor((Math.random() * 1000000) + 1);
                $('#model').modal('show');
                this.editMode = false;
            },
            generator() {
                this.form.barcode = Math.floor((Math.random() * 1000000) + 1);

            },
            add() {
                this.$Progress.start()
                this.form.post('api/medicine').then(() => {
                    Fire.$emit('AfterCreate');
                    var title = null;
                    if (i18n.locale == 'fa') {
                        title = 'دوا موفقانه اضافه شد'
                    } else {
                        title = 'Medicine add successfully'
                    }
                    $('#model').modal('hide');
                    toast.fire({
                        icon: 'success',
                        title: title
                    });

                    this.$Progress.finish()
                }).catch((e) => {
                    console.log(e.message);
                    this.$Progress.fail()
                });
            },
            editModel(medicine) {
                this.form.reset();
                if (this.lang == 'en') {
                    $('.modal-title').text('Edit Medicine');
                } else {
                    $('.modal-title').text('تغیر ادویه');
                }


                $('#model').modal('show');
                this.form.fill(medicine);
                if (medicine.approve == 2) {
                    $("#approve").prop("checked", false);
                    this.form.approve = false;
                }
                $(".dropify-preview").css("display", "block");
                $(".dropify-render").html("<img src='" + medicine.image + "'>")

                this.editMode = true;
            },
            update() {
                this.$Progress.start();
                this.form.put('api/medicine/' + this.form.id)
                    .then(() => {
                        Fire.$emit('AfterCreate');
                        $('#AddVendor').modal('hide');
                        var title = null;
                        if (i18n.locale == 'fa') {
                            title = 'دوا موفقانه اپدید شد'
                        } else {
                            title = 'Medicine Update successfully'
                        }

                        toast.fire({
                            icon: 'success',

                            title: title
                        });

                        this.$Progress.finish();
                        $('#model').modal('hide');
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    })
            },
            loadMedicine() {
                var value = this.form.name
                if (value != '') {
                    axios.get('/api/medicine/' + value + "?medicineView=1").then((data) => {
                        var html = '';
                        for (var i = 0; i < data.data.length; i++) {
                            html += "<option class='list-group-item medName' data-barcode='" + data
                                .data[i]
                                .barcode + "' data-id=" + data.data[i].id +
                                " value='" + data.data[i].name +
                                "' ></option>";
                        };
                        $('#browsers1').css('display', '');
                        $('#browsers1').html(html);
                    });

                } else {
                    $('#browsers1').css('display', 'none');
                }
            },
            activation(e) {
                this.$Progress.start();
                axios.get('api/medicine/activation/' + e.target.value).then(() => {
                        this.$Progress.finish();

                    })
                    .catch(() => {
                        this.$Progress.fail();
                    });

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
                        this.form.delete('api/medicine/' + id).then(() => {
                                Swal.fire(
                                    dele,
                        deleted,
                                    'success'
                                )
                                Fire.$emit('AfterCreate');
                            })
                            .catch(() => {
                                Swal.fire(
                                    'Failed!',
                                    'There was something wronge.',
                                    'warning'
                                )
                            })
                    }
                })
            },

            approved(e) {

                this.$Progress.start();
                axios.get('api/medicine/approved/1/' + e.target.parentElement.parentElement.id).then(() => {
                        var id = e.target.parentElement.parentElement.id;
                        if (i18n.locale == 'fa') {
                            $('#' + id).html("<span class='bg-success px-1'>تصویب</span>");
                        } else {
                            $('#' + id).html("<span class='bg-success px-1'>Approved</span>");
                        }

                        $('#deleted' + id).css('display', 'none');

                        this.$Progress.finish();

                    })
                    .catch(() => {
                        this.$Progress.fail();
                    });
            },

            Notapproved(e) {
                this.$Progress.start();
                axios.get('api/medicine/approved/2/' + e.target.parentElement.parentElement.id).then(() => {
                        var id = e.target.parentElement.parentElement.id;
                        if (i18n.locale == 'fa') {
                            $('#' + id).html("<span class='bg-danger px-1'>لغو</span>");
                        } else {
                            $('#' + id).html("<span class='bg-danger px-1'>Not Approved</span>");
                        }

                        this.$Progress.finish();

                    })
                    .catch(() => {
                        this.$Progress.fail();
                    });

            }

        },
        mounted() {
            $('.dropify').dropify();

            axios.get('api/medicine/classification').then((data) => {
                    this.classifications = (data.data.data);
                });
                axios.get('api/medicine/manufacturer').then((data) => {
                    this.manufacturers = (data.data.data);
                });
                axios.get('api/medicine/group').then((data) => {
                    this.groups = (data.data.data);
                });
                axios.get('api/medicine/doctor').then((data) => {
                    this.doctors = (data.data.data);
                });


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
                axios.get('api/medicine', {
                    params: {
                        search: e
                    },
                }).then(({
                    data
                }) => (this.medicines = data));
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








        },


    }

</script>


<style scoped>
    input {
        border: 1px solid #afafaf;
        border-radius: 0px !important;
    }

</style>
