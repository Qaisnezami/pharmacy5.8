<template>
    <div class="container-fluid" id="Counter">
        <div v-if="$gate.isCounterSales()">
            <div class="content-header">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark ">{{ $t('CounterSales')}}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right" :dir="rtl">
                            <li class="breadcrumb-item">
                                <router-link to="/dashboard">{{ $t('dashboard')}}</router-link>
                            </li>
                            <li class="breadcrumb-item">
                                <router-link to="/counter-sale">{{ $t('CounterSales')}}</router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('addnew')}}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">{{ $t('addnew')}}</div>

                        <div class="card-body">

                            <div class="box">
                                <!-- /.box-header -->
                                <form role="form" @keydown.enter.prevent class="form-horizontal" autocomplete="off">
                                    <div class="box-body">

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">{{ $t('BillNo')}}</label><br>
                                                    <input v-model="form.BillNo" disabled type="text" name="BillNo"
                                                        class="form-control"
                                                        :class="{ 'is-invalid': form.errors.has('BillNo') }">
                                                    <has-error :form="form" field="BillNo"></has-error>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">{{ $t('PatientName')}}</label><br>
                                                    <input v-model="form.patient_name" type="text" name="patient_name"
                                                        id="patient_name" class="form-control Nextrow"
                                                        :class="{ 'is-invalid': form.errors.has('patient_name') }">
                                                    <has-error :form="form" field="patient_name"></has-error>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">{{ $t('Doctor')}}</label><br>
                                                    <v-select v-model="form.doctor" :options="doctors"
                                                        :reduce="name => name.code" label="name" class="Nextrow"
                                                        :class="{ 'is-invalid': form.errors.has('doctors') }">
                                                    </v-select>
                                                    <has-error :form="form" field="doctors"></has-error>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">{{ $t('SaleDate')}}</label><br>
                                                    <input v-model.lazy="form.date" id="date" type="text" name="date"
                                                        autocomplete="off" class="form-control Nextrow datepicker"
                                                        :class="{ 'is-invalid': form.errors.has('date') }">

                                                    <has-error :form="form" field="date"></has-error>
                                                </div>
                                            </div>
                                        </div>

                                        <div style="display:none">

                                            Stock:<input type="text" disabled class="mb-1 col-md-2" id="stock">
                                        </div>

                                        <table class="table table-bordered" id="product_info_table">
                                            <thead>
                                                <tr>
                                                    <th style="width:10%">{{ $t('Barcode')}}</th>
                                                    <th style="width:10%">{{ $t('Name')}}</th>
                                                    <th style="width:10%">{{ $t('BatchNo') }}</th>
                                                    <th style="width:10%">{{ $t('Expiry') }}</th>
                                                    <th style="width:10%">{{ $t('quantity') }}</th>
                                                    <th style="width:10%">{{ $t('MRP') }}</th>
                                                    <th style="width:10%">{{ $t('Amount') }}</th>
                                                    <th style="width:2%"><button type="button" @click="addrow"
                                                            id="add_row" class="btn btn-default add_row"><i
                                                                class="fa fa-plus"></i></button></th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr class="row_1" style="display:none">
                                                    <td>
                                                        <input type="text" class="form-control barcode">
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="medID[]">
                                                        <input type="text" list="browsers1"
                                                            class="loadMedicines form-control">
                                                        <datalist id="browsers1">

                                                        </datalist>
                                                    </td>

                                                    <td>
                                                        <select name="Batch[]" style="height: 32px;"
                                                            class="form-control Batch">
                                                            <option value=""></option>
                                                        </select>
                                                    </td>


                                                    <td><input type="text" disabled readonly="readonly" name="Expiry[]"
                                                            class=" form-control"></td>


                                                    <td><input type="number" name="Quantity[]"
                                                            class="addrow multiplication form-control"></td>

                                                    <td><input type="number" disabled name="mrp[]"
                                                            class="addrow multiplication form-control"></td>
                                                    <td>
                                                        <input type="number" disabled name="amount[]" value="0"
                                                            class="addrow amount form-control" autocomplete="off">
                                                    </td>
                                                    <td><button type="button" class="remove btn btn-danger"><i
                                                                class="fa fa-times"></i></button></td>
                                                </tr>



                                                <tr class="row_1">
                                                    <td>
                                                        <input type="text" class="form-control barcode">
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="medID[]">
                                                        <input type="text" list="browsers"
                                                            class="loadMedicines form-control">
                                                        <datalist id="browsers">

                                                        </datalist>

                                                    </td>

                                                    <td>
                                                        <select name="Batch[]" class="form-control Batch"
                                                            style="    height: 32px;">
                                                            <option value=""></option>
                                                        </select>
                                                    </td>


                                                    <td><input type="text" disabled readonly="readonly" name="Expiry[]"
                                                            class="datepicker form-control"></td>


                                                    <td><input type="number" name="Quantity[]"
                                                            class="addrow multiplication form-control"></td>
                                                    <td><input type="number" name="mrp[]" disabled
                                                            class="addrow multiplication form-control"></td>
                                                    <td>
                                                        <input type="number" name="amount[]" disabled value="0"
                                                            class="addrow amount form-control" autocomplete="off">
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>




                                    </div>
                                    <!-- /.box-body -->
                                    <div class="row">

                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label for="">{{ $t('Description') }}</label><br>
                                                <textarea v-model="form.description" type="text" name="description"
                                                    style="height: 270px;" class="w-100"
                                                    :class="{ 'is-invalid': form.errors.has('description') }"></textarea>
                                                <has-error :form="form" field="description"></has-error>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{ $t('Total') }}</label>
                                                <input v-model="form.total" disabled type="text" @keyup="sumTotal"
                                                    id="total" name="total" class="form-control"
                                                    :class="{ 'is-invalid': form.errors.has('total') }">
                                                <has-error :form="form" field="total"></has-error>

                                            </div>
                                            <div class="form-group">
                                                <label for="">{{ $t('Discount') }}</label>
                                                <input v-model="form.discount" @keyup="sumTotal" id="discount"
                                                    type="text" name="discount" class="form-control"
                                                    :class="{ 'is-invalid': form.errors.has('discount') }">
                                                <has-error :form="form" field="discount"></has-error>

                                            </div>

                                            <div class="form-group">
                                                <label for="">{{ $t('Paid') }}</label>
                                                <input v-model="form.Paid" id="net_amount" @keyup="RemaindTotal"
                                                    type="text" name="Paid" class="form-control"
                                                    :class="{ 'is-invalid': form.errors.has('Paid') }">
                                                <has-error :form="form" field="Paid"></has-error>
                                            </div>

                                            <div class="form-group">
                                                <label for="">{{ $t('Due') }}</label>
                                                <input v-model="form.Remaind" @keyup="RemaindTotal" type="text"
                                                    id="Remaind" name="Remaind" class="form-control"
                                                    :class="{ 'is-invalid': form.errors.has('Remaind') }">
                                                <has-error :form="form" field="Remaind"></has-error>

                                            </div>


                                        </div>



                                    </div>
                                    <button class="btn btn-success" type="submit" v-shortkey="['ctrl', 'alt', 's']" data-toggle="tooltip" data-placement="top"  data-original-title="Ctrl+Alt+S"
                                        @shortkey="saveData" @click.prevent="saveData"> {{ $t('Save') }}
                                    </button>
                                    <button class="btn btn-primary" type="submit" v-shortkey="['ctrl', 'alt', 'p']" data-toggle="tooltip" data-placement="top"  data-original-title="Ctrl+Alt+P"
                                        @shortkey="saveAndPrint" @click.prevent="saveAndPrint">  {{ $t('SavePrint') }}
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
                rtl : null,
                i: 2,
                doctors: [],
                form: new Form({
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
                    Remaind: 0,
                    Paid: 0,
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
                $('.row_1:last-child td:nth-child(2) input:nth-child(2)').attr('list', 'brow' + this.i);
                $('.row_1:last-child td:nth-child(2) datalist').attr('id', 'brow' + this.i);

                this.i++
            },

            sumTotal() {
                this.form.Paid = Number(this.form.total) - Number(this.form.discount);
                this.form.Remaind = Number(this.form.total) - Number(this.form.discount) - Number(this.form.Paid);
            },
            RemaindTotal() {
                this.form.Remaind = Number(this.form.total) - Number(this.form.discount) - Number(this.form.Paid);
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
                $('tbody tr').each(function () {

                    if ($(this).css('display') != 'none') {
                        $(this).addClass('StoreData');
                        id[i] = $('.StoreData td:nth-child(2) input:first-child').val();
                        BatchNo[i] = $('.StoreData td:nth-child(3) select option:selected').val();
                        expiry[i] = $('.StoreData td:nth-child(4) input:first-child').val();
                        quantity[i] = $('.StoreData td:nth-child(5) input:first-child').val();
                        mrp[i] = $('.StoreData td:nth-child(6) input:first-child').val();
                        amount[i] = $('.StoreData td:nth-child(7) input:first-child').val();

                        $(this).removeClass('StoreData');
                        i++
                    }

                });
                that.form.medicineId = id;
                that.form.stock_id = BatchNo;
                that.form.Expiry = expiry;
                that.form.Quantity = quantity;
                that.form.Mrp = mrp;
                that.form.Amount = amount;
                this.form.post('/api/counter-sale').then((data) => {

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
                $('tbody tr').each(function () {

                    if ($(this).css('display') != 'none') {
                        $(this).addClass('StoreData');
                        id[i] = $('.StoreData td:nth-child(2) input:first-child').val();
                        BatchNo[i] = $('.StoreData td:nth-child(3) select option:selected').val();
                        expiry[i] = $('.StoreData td:nth-child(4) input:first-child').val();
                        quantity[i] = $('.StoreData td:nth-child(5) input:first-child').val();
                        mrp[i] = $('.StoreData td:nth-child(6) input:first-child').val();
                        amount[i] = $('.StoreData td:nth-child(7) input:first-child').val();

                        $(this).removeClass('StoreData');
                        i++
                    }

                });
                that.form.medicineId = id;
                that.form.stock_id = BatchNo;
                that.form.Expiry = expiry;
                that.form.Quantity = quantity;
                that.form.Mrp = mrp;
                that.form.Amount = amount;
                this.form.post('/api/counter-sale').then((data) => {

                    if (data.data.error) {
                        toast.fire({
                            icon: 'error',
                            title: data.data.error
                        });
                        this.$Progress.fail()
                    }

                    if (data.data.success) {
                        Fire.$emit('SaleId', data.data.id);
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

            function delay(callback, ms) {
                var timer = 0;
                return function () {
                    var context = this,
                        args = arguments;
                    clearTimeout(timer);
                    timer = setTimeout(function () {
                        callback.apply(context, args);
                    }, ms || 0);
                };
            }

            let that = this;
            // Remove Row
            $('.remove').on('click', function () {
                $(this).parent().parent().remove();
            });

            // Show Names of Medicines
            $('#Counter').on('input', '.loadMedicines', function (e) {

                var value = $(this).val();
                if (value != '') {
                    axios.get('/api/counter-sale/search/' + value).then((data) => {
                        var html = '';
                        for (var i = 0; i < data.data.length; i++) {
                            html += "<option class='list-group-item medName' data-barcode='" + data
                                .data[i]
                                .barcode + "' data-id=" + data.data[i].id +
                                " value='" + data.data[i].name + "," + data.data[i].groups +
                                "' ></option>";
                        };
                        $(this).next().css('display', '');
                        $(this).next().html(html);
                    });

                } else {
                    $(this).next().css('display', 'none');
                }
            });
            $('#Counter').on('change', '.loadMedicines', function () {
                var parent = $(this).parent().parent();
                parent.addClass('barcode_1');
                var value = $(this).val();
                if (value.length > 0) {
                    var option = $(".barcode_1 datalist").find("[value='" + value + "']");
                    if (option.length > 0) {
                        var id = option.data("id");
                        var barcode = option.data('barcode');
                    }
                }
                axios.get('/api/counter-sale/loadBatch/' + id).then((data) => {
                    var html = '';
                    $('.barcode_1 td:nth-child(3) select').html('');
                    $('.barcode_1 td:nth-child(4) input').val('');
                    
                    $('#stock').val('0');
                    $('#stock').parent().css('display', 'none');
                    $('.barcode_1 td:nth-child(6) input').val('');
                    if(data.data.length > 0 )
                    {
                        for (var i = 0; i < data.data.length; i++) {
                        html += "<option value='" + data.data[i].id + "' data-expiry='" + data.data[i]
                            .expiry_date + "'  data-quantity='" + data.data[i].quantity +
                            "' data-MRP='" + data.data[i].MRP + "' >" + data.data[i].batch_no +
                            "</option>";
                    }
                    $('.barcode_1 td:nth-child(3) select').html(html);
                    $('.barcode_1 td:nth-child(4) input').val(data.data[0].expiry_date);
                    $('.barcode_1 td:nth-child(5) input').val('');
                    $('.barcode_1 td:nth-child(5) input').focus();
                    $('#stock').val(data.data[0].quantity);
                    $('#stock').parent().css('display', '');
                    $('.barcode_1 td:nth-child(6) input').val(data.data[0].MRP);
                    }else
                    {
                        $('.barcode_1 td:nth-child(3) select').html('');
                        $('.barcode_1 td:nth-child(4) input').val('');
                        $('#stock').val(0);
                        $('.barcode_1 td:nth-child(5) input').val('');
                    }
                    
                    $('.barcode_1 td:nth-child(7) input').val(0);
                    parent.removeClass('barcode_1');
                    

                   
                });
                $(this).parent().prev().find('input').val(barcode);
                $(this).prev().val(id);
                $(this).next().css('display', 'none');

                // 
                




            });

            $('#Counter').on('keyup', '.addrow', function (e) {
                if (e.which == 13) {

                    $('.row_1:nth-child(1)').clone(true, true).appendTo('tbody');
                    $('.row_1:last-child').css({
                        display: 'table-row'
                    });
                    $('.row_1:last-child td:nth-child(4) input:first-child').datepicker({
                        format: 'yyyy-mm-dd'
                    });
                    $('.row_1:last-child td:nth-child(2) input:nth-child(2)').attr('list', 'brow' + that.i);
                    $('.row_1:last-child td:nth-child(2) datalist').attr('id', 'brow' + that.i);
                    $('.row_1:last-child td:nth-child(1) input').focus();
                    that.i++

                }
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
                        $('.barcode td:nth-child(5) input').focus();
                        parent.removeClass('barcode');
                    });

                }
            });

            // multiplication Of Quantity and Price
            $('#Counter').on('keyup  change', '.multiplication', _.debounce(function (e) {
                var parent = $(this).parent().parent();
                parent.addClass('selected_price');
                var quantity = $('.selected_price td:nth-child(5) input').val();
                var id = $('.selected_price td:nth-child(3) select').children("option:selected").val();
                axios.get('/api/counter-sale/findBatch/' + id).then((data) => {
                    if (data.data.quantity < quantity) {
                        if(that.rtl = 'rtl')
                            {
                                Swal.fire(
                            '...اشتباه',
                            ' شما دارید ' + data.data.quantity + 'قلم دوا',
                            'error'
                        )
                            }
                            else{
                        Swal.fire(
                            'Oops...',
                            'You have ' + data.data.quantity + ' items in this batch ',
                            'error'
                        )}
                        $('.selected_price td:nth-child(5) input').val(data.data.quantity);
                    }
                    quantity = $('.selected_price td:nth-child(5) input').val();
                    var price = $('.selected_price td:nth-child(6) input').val();
                    var Total_price = quantity * price;
                    $('.selected_price td:nth-child(7) input').val(Total_price);
                    parent.removeClass('selected_price');


                    let totalSum = 0;
                    $('.amount').each(function () {
                        let inputVal = $(this).val();
                        if ($.isNumeric(inputVal)) {
                            totalSum += parseFloat(inputVal);
                        }
                    });

                    that.form.total = totalSum;
                   
                    that.form.Paid = totalSum;
                   that.sumTotal();
                });



            },100));

            // Changing of batch number , fill all column 
            $('#Counter').on('change', '.Batch', function () {
                var expiry = $(this).children("option:selected").attr('data-expiry');
                var quantity = $(this).children("option:selected").attr('data-quantity');
                var mrp = $(this).children("option:selected").attr('data-MRP');
                console.log(expiry);
                var parent = $(this).parent().parent();
                parent.addClass('selected_Batch');
                $('.selected_Batch td:nth-child(4) input').val(expiry);
                $('#stock').val(quantity);
                $('#stock').parent().css('display', '');
                $('.selected_Batch td:nth-child(6) input').val(mrp);
                if($('.selected_Batch td:nth-child(5) input').val() != '')
                {
                    quantity = $('.selected_Batch td:nth-child(5) input').val();
                    var price = $('.selected_Batch td:nth-child(6) input').val();
                    var Total_price = quantity * price;
                    $('.selected_Batch td:nth-child(7) input').val(Total_price);
                }
                
                parent.removeClass('selected_Batch');
                let totalSum = 0;
                    $('.amount').each(function () {
                        let inputVal = $(this).val();
                        if ($.isNumeric(inputVal)) {
                            totalSum += parseFloat(inputVal);
                        }
                    });

                    that.form.total = totalSum;
                   
                    that.form.Paid = totalSum;
                   that.sumTotal();
                

            });


            // Fill the date
            $('#Counter').on('keyup  change', '#date', function () {

                that.form.date = $(this).val();
            });


            // For Date
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                date: new Date()
            });
            $('#patient_name').focus();

            $('#Counter').on('keyup', '.Nextrow', function (e) {
                if (e.which == 13) {
                    $('.row_1:last-child td:nth-child(1) input').focus();
                }
            });

        },
        created() {
            this.$Progress.start();
            axios.get('/api/doctor/doctor').then(({
                data
            }) => (this.doctors = data.data));
            axios.get('/api/counter-sale/bill-no').then((data) => (this.form.BillNo = data.data));

            this.form.date = DateFormat();

            this.$Progress.finish();

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
