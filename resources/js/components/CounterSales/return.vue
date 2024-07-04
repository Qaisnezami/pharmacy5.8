<template>
    <div class="container-fluid" id="Counter">
        <div v-if="$gate.isCounterSales()">
            <div class="content-header">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark ">{{ $t('CounterSales')}}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <router-link to="/dashboard">{{ $t('dashboard')}}</router-link>
                            </li>
                            <li class="breadcrumb-item">
                                <router-link to="/counter-sale">{{ $t('CounterSales')}}</router-link>
                            </li>
                            <li class="breadcrumb-item active">{{$t('ReturnProduct')}}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">{{$t('ReturnProduct')}} </div>

                        <div class="card-body">

                            <div class="box">
                                <!-- /.box-header -->
                                <form role="form" @keydown.enter.prevent class="form-horizontal" autocomplete="off">
                                    <div class="box-body">

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for=""> {{$t('BillNo')}}</label><br>
                                                    <input v-model="form.BillNo" disabled type="text" name="BillNo"
                                                        class="form-control"
                                                        :class="{ 'is-invalid': form.errors.has('BillNo') }">
                                                    <has-error :form="form" field="BillNo"></has-error>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">{{$t('PatientName')}}</label><br>
                                                    <input v-model="form.patient_name" disabled type="text" name="patient_name"
                                                        class="form-control"
                                                        :class="{ 'is-invalid': form.errors.has('patient_name') }">
                                                    <has-error :form="form" field="patient_name"></has-error>
                                                </div>
                                            </div>
                                        
                                        </div>

                                        <div style="display:none">

                                            Stock:<input type="text" disabled class="mb-1 col-md-2" id="stock">
                                        </div>

                                        <table class="table table-bordered" id="product_info_table">
                                            <thead>
                                                <tr>
                                                    <th style="width:1%">{{$t('Return')}}</th>
                                                    <th style="width:10%">{{$t('Barcode')}}</th>
                                                    <th style="width:10%">{{$t('Name')}}</th>
                                                    <th style="width:10%">{{$t('BatchNo')}}</th>
                                                    <th style="width:10%">{{$t('Expiry')}}</th>
                                                    <th style="width:10%">{{$t('quantity')}}</th>
                                                    <th style="width:10%">{{$t('MRP')}}</th>
                                                    <th style="width:10%">{{$t('Amount')}}</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr class="row_1" v-for="(detail) in details" :key="detail.id">
                                                    <td class="text-center"><input type="checkbox" :value="detail.id" name="returnId[]" ></td>
                                                    <td>
                                                        <input type="text" v-once :value="detail.barcode"
                                                            class="form-control barcode">
                                                    </td>
                                                    <td>
                                                        <input type="hidden" v-once :value="detail.medicine_id"
                                                            name="medID[]">
                                                        <input type="text" v-once :value="detail.medicines"
                                                            class="loadMedicines form-control">
                                                        <ul class="list-group" style="display:none">

                                                        </ul>
                                                    </td>

                                                    <td>
                                                        <select v-once name="Batch[]" class="form-control Batch"
                                                            style="    height: 32px;" id="">
                                                            <option :value="detail.stock_id">{{detail.batch_no}}
                                                            </option>
                                                        </select>
                                                    </td>


                                                    <td><input v-once type="text" :value="detail.expiry_date"
                                                            readonly="readonly" name="Expiry[]"
                                                            class="datepicker form-control"></td>


                                                    <td><input v-once type="number" :value="detail.quantity" name="Quantity[]"
                                                            class="multiplication form-control"></td>
                                                    <td><input v-once type="number" :value="detail.MRP" name="mrp[]"
                                                            class="multiplication form-control"></td>
                                                    <td>
                                                        <input v-once type="number" :value="detail.amount" name="amount[]"
                                                            class="amount form-control" autocomplete="off">
                                                    </td>
                                                    
                                                </tr>
                                            </tbody>
                                        </table>




                                    </div>
                                    <!-- /.box-body -->
                                    <div class="row">

                                        <div class="col-md-9">
                                            
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{$t('Total')}}</label>
                                                <input   type="text"  id="Alltotal"
                                                    name="total" class="form-control"
                                                    :class="{ 'is-invalid': form.errors.has('total') }">
                                                <has-error :form="form" field="total"></has-error>

                                            </div>
                                            <div class="form-group">
                                                <label for="">{{$t('TotalAfterReturn')}}</label>
                                                <input v-model="form.total" type="text" @keyup="sumTotal" id="total"
                                                    name="total" class="form-control"
                                                    :class="{ 'is-invalid': form.errors.has('total') }">
                                                <has-error :form="form" field="total"></has-error>

                                            </div>
                                            <div class="form-group">
                                                <label for="">{{$t('Discount')}}</label>
                                                <input v-model="form.discount" @keyup="sumTotal" id="discount"
                                                    type="text" name="discount" class="form-control"
                                                    :class="{ 'is-invalid': form.errors.has('discount') }">
                                                <has-error :form="form" field="discount"></has-error>

                                            </div>

                                            <div class="form-group">
                                                <label for="">{{$t('Paid')}}</label>
                                                <input v-model="form.paid" id="net_amount" @keyup="RemaindTotal"
                                                    type="text" name="Paid" class="form-control"
                                                    :class="{ 'is-invalid': form.errors.has('Paid') }">
                                                <has-error :form="form" field="Paid"></has-error>
                                            </div>

                                            <div class="form-group">
                                                <label for="">{{$t('Due')}}</label>
                                                <input v-model="form.remaind" @keyup="RemaindTotal" type="text"
                                                    id="Remaind" name="Remaind" class="form-control"
                                                    :class="{ 'is-invalid': form.errors.has('Remaind') }">
                                                <has-error :form="form" field="Remaind"></has-error>

                                            </div>


                                        </div>



                                    </div>
                                    <button class="btn btn-success" type="submit" v-shortkey="['ctrl', 'alt', 's']" data-toggle="tooltip" data-placement="top"  data-original-title="Ctrl+Alt+S"
                                        @shortkey="saveData" @click.prevent="saveData"> {{$t('Save')}} 
                                    </button>
                                    <button class="btn btn-primary" type="submit" v-shortkey="['ctrl', 'alt', 'p']" data-toggle="tooltip" data-placement="top"  data-original-title="Ctrl+Alt+P"
                                        @shortkey="saveAndPrint" @click.prevent="saveAndPrint">{{$t('SavePrint')}}
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
                details: {},
                doctors: [],
                form: new Form({
                    returnId:[],
                    id: '',
                    medicineId: [],
                    BillNo: '',
                    patient_name: null,
                    doctor: null,
                    stock_id: [],
                    Expiry: [],
                    Quantity: [],
                    Mrp: [],
                    Amount: [],
                    total: 0,
                    remaind: 0,
                    paid: 0,
                    description: '',
                    discount: 0,
                    date: '',
                }),
            }
        },
        methods: {
            addrow() {
                $('.row_1:nth-child(1)').clone(true, true).appendTo('tbody');
                $('.row_1:last-child').css({
                    display: 'table-row'
                });
                $('.row_1:last-child td:nth-child(4) input:first-child').datepicker({
                    format: 'yyyy-mm-dd'
                });
            },

            sumTotal() {
                
                this.form.remaind = Number(this.form.total) - Number(this.form.discount) - Number(this.form.paid);
            },
            RemaindTotal() {
                this.form.remaind = Number(this.form.total) - Number(this.form.discount) - Number(this.form.paid);
            },
            saveData() {
                this.$Progress.start()
                var i = 0;
                let that = this;
                let id = [];
                let BatchNo = [];
                let expiry = [];
                let quantity = [];
                let mrp = [];
                let amount = [];
                let returnID = [];
                $('tbody tr').each(function () {

                    if ($(this).css('display') != 'none') {
                        $(this).addClass('StoreData');
                        returnID[i] = $('.StoreData td:nth-child(1) input:first-child:checked').val();
                        id[i] = $('.StoreData td:nth-child(3) input:first-child').val();
                        BatchNo[i] = $('.StoreData td:nth-child(4) select option:selected').val();
                        expiry[i] = $('.StoreData td:nth-child(5) input:first-child').val();
                        quantity[i] = $('.StoreData td:nth-child(6) input:first-child').val();
                        mrp[i] = $('.StoreData td:nth-child(7) input:first-child').val();
                        amount[i] = $('.StoreData td:nth-child(8) input:first-child').val();

                        $(this).removeClass('StoreData');
                        i++
                    }

                });
                that.form.returnId = returnID;
                that.form.medicineId = id;
                that.form.stock_id = BatchNo;
                that.form.Expiry = expiry;
                that.form.Quantity = quantity;
                that.form.Mrp = mrp;
                that.form.Amount = amount;
                this.form.put('/api/counter-sale/return/'+this.form.id).then((data) => {

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

            },
            saveAndPrint() {
                this.$Progress.start()
                var i = 0;
                let that = this;
                let id = [];
                let BatchNo = [];
                let expiry = [];
                let quantity = [];
                let mrp = [];
                let amount = [];
                let returnID = [];
                $('tbody tr').each(function () {

                    if ($(this).css('display') != 'none') {
                        $(this).addClass('StoreData');
                        returnID[i] = $('.StoreData td:nth-child(1) input:first-child:checked').val();
                        id[i] = $('.StoreData td:nth-child(3) input:first-child').val();
                        BatchNo[i] = $('.StoreData td:nth-child(4) select option:selected').val();
                        expiry[i] = $('.StoreData td:nth-child(5) input:first-child').val();
                        quantity[i] = $('.StoreData td:nth-child(6) input:first-child').val();
                        mrp[i] = $('.StoreData td:nth-child(7) input:first-child').val();
                        amount[i] = $('.StoreData td:nth-child(8) input:first-child').val();

                        $(this).removeClass('StoreData');
                        i++
                    }

                });
                that.form.returnId = returnID;
                that.form.medicineId = id;
                that.form.stock_id = BatchNo;
                that.form.Expiry = expiry;
                that.form.Quantity = quantity;
                that.form.Mrp = mrp;
                that.form.Amount = amount;
                this.form.put('/api/counter-sale/return/'+this.form.id).then((data) => {

                    if (data.data.error) {
                        toast.fire({
                            icon: 'error',
                            title: data.data.error
                        });
                        this.$Progress.fail()
                    }

                    if (data.data.success) {
                        Fire.$emit('SaleId', this.form.id);
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

            $('[data-toggle="tooltip"]').tooltip({
                trigger: 'hover'
            });
            $('[data-toggle="tooltip"]').on('click', function () {
                $(this).tooltip('hide')
            });


            let that = this;
            // Remove Row
            $('#Counter').on('click', '.remove', function () {
                $(this).parent().parent().remove();
                let totalSum = 0;
                $('.amount').each(function () {
                    let inputVal = $(this).val();
                    if ($.isNumeric(inputVal)) {
                        totalSum += parseFloat(inputVal);
                    }
                });
                that.form.total = totalSum;
                that.form.paid = totalSum;
                that.form.remaind = Number(that.form.total) - Number(that.form.discount) - Number(that.form.paid);

            });

            // Show Names of Medicines
            $('#Counter').on('keyup', '.loadMedicines', function () {
                var value = $(this).val();
                if (value != '') {
                    axios.get('/api/counter-sale/search/' + value).then((data) => {
                        var html = '';
                        for (var i = 0; i < data.data.length; i++) {
                            html += "<li class='list-group-item medName' data-barcode='" + data.data[i]
                                .barcode + "' id=" + data.data[i].id +
                                " >" + data.data[i].name + "</li>";
                        };
                        $(this).next().css('display', '');
                        $(this).next().html(html);
                    });

                } else {
                    $(this).next().css('display', 'none');
                }
            });

            // Select Medicines , Show barcode ,Fill all Cloumn 
            $('#Counter').on('click', '.medName', function () {
                var text = $(this).text();
                var id = $(this).attr('id');
                var barcode = $(this).attr('data-barcode');
                var parent = $(this).parent().parent().parent();
                parent.addClass('barcode_1');
                axios.get('/api/counter-sale/loadBatch/' + id).then((data) => {
                    var html = '';
                    for (var i = 0; i < data.data.length; i++) {
                        html += "<option value='" + data.data[i].id + "' data-expiry='" + data.data[i]
                            .expiry_date + "'  data-quantity='" + data.data[i].quantity +
                            "' data-MRP='" + data.data[i].MRP + "' >" + data.data[i].batch_no +
                            "</option>";
                    }
                    $('.barcode_1 td:nth-child(3) select').html(html);
                    $('.barcode_1 td:nth-child(4) input').val(data.data[0].expiry_date);
                    $('#stock').val(data.data[0].quantity);
                    $('#stock').parent().css('display', '');
                    $('.barcode_1 td:nth-child(6) input').val(data.data[0].MRP);
                    parent.removeClass('barcode_1');
                });
                $(this).parent().parent().prev().find('input').val(barcode);
                $(this).parent().prev().val(text);
                $(this).parent().prev().prev().val(id);
                $(this).parent().css('display', 'none');
            });

            // Barcode Enter , Fill All column 
            $('#Counter').on('keyup', '.barcode', function (e) {
                if (e.which == 13) {
                    var barcode = $(this).val();

                    var parent = $(this).parent().parent();
                    parent.addClass('barcode');
                    axios.get('/api/medicine/barcode/' + barcode).then((data) => {
                        $('.barcode td:nth-child(2) input:first-child').val(data.data.id);
                        $('.barcode td:nth-child(2) input:nth-child(2)').val(data.data.name);

                    });

                    axios.get('/api/counter-sale/barcodeLoadBatch/' + barcode).then((data) => {
                        var html = '';
                        for (var i = 0; i < data.data.length; i++) {
                            html += "<option value='" + data.data[i].id + "' data-expiry='" + data.data[
                                    i].expiry_date + "'  data-quantity='" + data.data[i].quantity +
                                "' data-MRP='" + data.data[i].MRP + "' >" + data.data[i].batch_no +
                                "</option>";
                        }
                        $('.barcode td:nth-child(3) select').html(html);
                        $('.barcode td:nth-child(4) input').val(data.data[0].expiry_date);
                        $('#stock').val(data.data[0].quantity);
                        $('#stock').parent().css('display', '');
                        $('.barcode td:nth-child(6) input').val(data.data[0].MRP);
                        parent.removeClass('barcode');
                    });

                }
            });

            // multiplication Of Quantity and Price
            $('#Counter').on('keyup  change', '.multiplication', function () {
                var parent = $(this).parent().parent();
                parent.addClass('selected_price');
                var quantity = $('.selected_price td:nth-child(6) input').val();
                var price = $('.selected_price td:nth-child(7) input').val();
                var Total_price = Number(quantity) * Number(price);
                $('.selected_price td:nth-child(8) input').val(Total_price);
                parent.removeClass('selected_price');

                let totalSum = 0;
                $('.amount').each(function () {
                    let inputVal = $(this).val();
                    if ($.isNumeric(inputVal)) {
                        totalSum += parseFloat(inputVal);
                    }
                });

                that.form.total = totalSum;
                that.form.paid = totalSum;
                that.form.remaind = Number(that.form.total) - Number(that.form.discount) - Number(that.form.paid);

            });

            // Changing of batch number , fill all column 
            $('#Counter').on('change', '.Batch', function () {
                var expiry = $(this).children("option:selected").attr('data-expiry');
                var quantity = $(this).children("option:selected").attr('data-quantity');
                var mrp = $(this).children("option:selected").attr('data-MRP');
                var parent = $(this).parent().parent();
                parent.addClass('selected_Batch');
                $('.selected_Batch td:nth-child(4) input').val(expiry);
                $('#stock').val(quantity);
                $('#stock').parent().css('display', '');
                $('.selected_Batch td:nth-child(6) input').val(mrp);
                parent.removeClass('selected_Batch');
            });


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
                $('#Alltotal').val(data.data.counter_sales.total);
                this.details = data.data.counter_sale_details;

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
