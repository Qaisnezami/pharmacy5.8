<template>
    <div class="container-fluid" id="Counter">
        <div v-if="$gate.isCounterSales()">
            <div class="content-header">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark ">Counter Sales</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <router-link to="/dashboard">Home</router-link>
                            </li>
                            <li class="breadcrumb-item">
                                <router-link to="/counter-sale">Counter Sales</router-link>
                            </li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Edit </div>

                        <div class="card-body">

                            <div class="box">
                                <!-- /.box-header -->
                                <form role="form" @keydown.enter.prevent class="form-horizontal" autocomplete="off">
                                    <div class="box-body">

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Bill No</label><br>
                                                    <input v-model="form.BillNo" type="text" name="BillNo"
                                                        class="form-control"
                                                        :class="{ 'is-invalid': form.errors.has('BillNo') }">
                                                    <has-error :form="form" field="BillNo"></has-error>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Patient Name</label><br>
                                                    <input v-model="form.patient_name" type="text" name="patient_name"
                                                        class="form-control"
                                                        :class="{ 'is-invalid': form.errors.has('patient_name') }">
                                                    <has-error :form="form" field="patient_name"></has-error>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Doctors</label><br>
                                                    <v-select v-model="form.doctor" :options="doctors"
                                                        :reduce="name => name.code" label="name"
                                                        :class="{ 'is-invalid': form.errors.has('doctors') }">
                                                    </v-select>
                                                    <has-error :form="form" field="doctors"></has-error>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Sales Date</label><br>
                                                    <input v-model.lazy="form.date" id="date" type="text" name="date"
                                                        autocomplete="off" class="form-control datepicker"
                                                        :class="{ 'is-invalid': form.errors.has('date') }">

                                                    <has-error :form="form" field="date"></has-error>
                                                </div>
                                            </div>
                                        </div>

                                        <div style="display:none">

                                            Stock:<input type="text" disabled class="mb-1 col-md-2" id="stock">
                                        </div>





                                    </div>
                                    <!-- /.box-body -->
                                    <div class="row">

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Description*</label><br>
                                                <textarea v-model="form.description" type="text" name="description"
                                                    style="height: 270px;" class="w-100"
                                                    :class="{ 'is-invalid': form.errors.has('description') }"></textarea>
                                                <has-error :form="form" field="description"></has-error>
                                            </div>
                                        </div>
                                       



                                    </div>
                                    <button class="btn btn-success" type="submit" @click.prevent="saveData"> Save
                                    </button>
                                </form>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>
                        <!-- col-md-12 -->
                    </div>
                    <!-- /.row -->
                    <!-- /.content -->
                </div>
            </div>
        </div>
        <div v-if="!$gate.isCounterSales()">
            <not-found></not-found>
        </div>
    </div>

</template>

<script>
    export default {
        data() {
            return {
                id: this.$route.params.id,
                doctors: [],
                form: new Form({
                    BillNo: '',
                    patient_name: null,
                    doctor: null,
                    
                    description: '',
                    date: '',
                }),
            }
        },
        methods: {
            
            saveData() {
                this.$Progress.start()
                this.form.put('/api/counter-sale/'+this.id).then((data) => {

                    if (data.data.error) {
                        toast.fire({
                            icon: 'error',
                            title: data.data.error
                        });
                        this.$Progress.fail()
                    }

                    if (data.data.success) {
                        Fire.$emit('success', data.data.success);
                        this.$router.push('/counter-sale')
                    }
                    this.$Progress.finish()
                }).catch(() => {
                    this.$Progress.fail()
                });

            }
        },
        mounted() {

            let that = this;
            // Remove Ro

            // Fill the date
            $('#Counter').on('keyup  change', '#date', function () {

                that.form.date = $(this).val();
            });


            // For Date
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd'
            });

        },
        created() {
            this.$Progress.start();
            axios.get('/api/doctor/doctor').then(({
                data
            }) => (this.doctors = data.data));
            axios.get('/api/counter-sale/edit/' + this.id).then((data) => {
                this.form.fill(data.data.counter_sales);
            });
            this.$Progress.finish();
        }

    }

</script>



<style scoped>
    th,
    i {
        font-size: 13px;
    }

    ul {
        position: absolute;
        z-index: 10;
    }

    li:hover {
        cursor: pointer;
    }

    .form-control {
        display: block;
        width: 100%;
        height: calc(1.3em + 0.75rem + 2px);
    }

</style>
