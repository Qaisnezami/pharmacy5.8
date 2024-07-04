<template>
    <div class="container-fluid" id="Purchase">
        <div v-if="$gate.isPurchase() && $gate.Create()">
            <div class="content-header">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark ">{{ $t('purchase')}}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right" :dir="rtl">
                            <li class="breadcrumb-item">
                                <router-link to="/dashboard">{{ $t('dashboard') }}</router-link>
                            </li>
                            <li class="breadcrumb-item">
                                <router-link to="/purchase">{{ $t('purchase')}}</router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('addnew') }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">{{ $t('addnew') }}</div>

                        <div class="card-body">

                            <div class="box">
                                <!-- /.box-header -->
                                <form role="form" @keydown.enter.prevent class="form-horizontal" autocomplete="off">
                                    <div class="box-body">

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">{{ $t('BillNo') }}</label><br>
                                                    <input v-model="form.BillNo" type="text" name="BillNo"
                                                        class="form-control"
                                                        :class="{ 'is-invalid': form.errors.has('BillNo') }">
                                                    <has-error :form="form" field="BillNo"></has-error>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">{{ $t('suppliers') }}</label><br>
                                                    <v-select v-model="form.supplier" :options="suppliers" class="Nextrow"
                                                        :reduce="name => name.code" label="name" id="suppliers"
                                                        :class="{ 'is-invalid': form.errors.has('supplier') }">
                                                    </v-select>
                                                    <has-error :form="form" field="supplier"></has-error>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">{{ $t('SupplierRef') }}#</label><br>
                                                    <input v-model="form.supplierRef" type="text" name="supplierRef"
                                                        class="form-control Nextrow"
                                                        :class="{ 'is-invalid': form.errors.has('supplierRef') }">
                                                    <has-error :form="form" field="supplierRef"></has-error>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">{{ $t('ReceiveDate') }}</label><br>
                                                    <input v-model="form.date" id="date" type="text" name="date"
                                                        autocomplete="off" class="form-control Nextrow datepicker"
                                                        :class="{ 'is-invalid': form.errors.has('date') }">

                                                    <has-error :form="form" field="date"></has-error>
                                                </div>
                                            </div>
                                        </div>





                                        <table class="table table-bordered" id="product_info_table">
                                            <thead>
                                                <tr>
                                                    <th style="width:10%">{{ $t('Barcode') }}</th>
                                                    <th style="width:10%">{{ $t('Name') }}</th>
                                                    <th style="width:10%">{{ $t('BatchNo') }}</th>
                                                    <th style="width:12%">{{ $t('Expiry') }}</th>
                                                    <th style="width:10%">{{ $t('quantity') }}</th>
                                                    <th style="width:10%">{{ $t('FreeQuantity') }}</th>
                                                    <th style="width:10%">{{ $t('Rate') }}</th>
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
                                                        <input type="text" list="browsers"
                                                            class="loadMedicines form-control">
                                                        <datalist id="browsers">

                                                        </datalist>
                                                    </td>

                                                    <td><input type="text" name="Batch[]" class="form-control"></td>


                                                    <td><input type="text" placeholder="yyyy-mm-dd"  name="Expiry[]"
                                                            class=" form-control"></td>


                                                    <td><input type="number" name="Quantity[]"
                                                            class="multi form-control"></td>
                                                    <td><input type="number" name="free[]" class="form-control"></td>
                                                    <td>
                                                        <input type="number" name="rate[]" class="multi form-control"
                                                            autocomplete="off">
                                                    </td>
                                                    <td><input  type="number" name="mrp[]" class="addrow form-control"></td>
                                                    <td>
                                                        <input type="number" name="amount[]" value="0"
                                                            class=" amount form-control" disabled autocomplete="off">
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
                                                        <input type="text" list="browsers1"
                                                            class="loadMedicines form-control">
                                                        <datalist id="browsers1">

                                                        </datalist>
                                                    </td>

                                                    <td><input type="text" name="Batch[]" class="form-control"></td>


                                                    <td ><input type="text" placeholder="yyyy-mm-dd"  name="Expiry[]"
                                                            class=" form-control datepicker"></td>


                                                    <td><input type="number" name="Quantity[]"
                                                            class="multi form-control"></td>
                                                    <td><input type="number" name="free[]" class="form-control"></td>

                                                    <td>
                                                        <input type="number" name="rate[]" class="multi form-control"
                                                            autocomplete="off">
                                                    </td>
                                                    <td><input type="number" name="mrp[]" class="addrow form-control"></td>
                                                    <td>
                                                        <input type="number" name="amount[]" disabled value="0"
                                                            class="amount form-control" autocomplete="off">
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>




                                    </div>
                                    <!-- /.box-body -->
                                    <div class="row">

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{$t('Image')}}</label><br>
                                                <input type="file" name="image" id="image" @change="uploadPhoto"
                                                    data-height="255px" class="w-100 dropify"
                                                    :class="{ 'is-invalid': form.errors.has('image') }">
                                                <has-error :form="form" field="image"></has-error>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{$t('Description')}}</label><br>
                                                <textarea v-model="form.description" type="text" name="description"
                                                    style="height: 270px;" class="w-100"
                                                    :class="{ 'is-invalid': form.errors.has('description') }"></textarea>
                                                <has-error :form="form" field="description"></has-error>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">{{$t('Total')}}</label>
                                                <input v-model="form.total" disabled type="text" @keyup="sumTotal" id="total"
                                                    name="total" class="form-control"
                                                    :class="{ 'is-invalid': form.errors.has('total') }">
                                                <has-error :form="form" field="total"></has-error>

                                            </div>
                                            <div class="form-group">
                                                <label for="">{{$t('Discount')}}</label>
                                                <input v-model="form.discount" @keyup="RemaindTotal" id="discount"
                                                    type="text" name="discount" class="form-control"
                                                    :class="{ 'is-invalid': form.errors.has('discount') }">
                                                <has-error :form="form" field="discount"></has-error>

                                            </div>
                                            <div class="form-group">
                                                <label for="">{{$t('Paid')}}</label>
                                                <input v-model="form.paid" @keyup="RemaindTotal"  type="text"
                                                    name="paid" class="form-control"
                                                    :class="{ 'is-invalid': form.errors.has('paid') }">
                                                <has-error :form="form" field="paid"></has-error>
                                            </div>
                                            <div class="form-group">
                                                <label for="">{{$t('Due')}}</label>
                                                <input v-model="form.remind" @keyup="RemaindTotal" type="text"
                                                    name="remind" class="form-control"
                                                    :class="{ 'is-invalid': form.errors.has('remind') }">
                                                <has-error :form="form" field="remind"></has-error>
                                            </div>


                                        </div>



                                    </div>
                                    <button class="btn btn-success" type="submit" v-shortkey="['ctrl', 'alt', 's']" data-toggle="tooltip" data-placement="top"  data-original-title="Ctrl+Alt+S" @shortkey="saveData" @click.prevent="saveData"> {{$t('Save')}}
                                    </button>
                                    <button class="btn btn-primary" type="submit" v-shortkey="['ctrl', 'alt', 'p']" data-toggle="tooltip" data-placement="top"  data-original-title="Ctrl+Alt+P"
                                        @shortkey="saveAndPrint" @click.prevent="saveAndPrint"> {{$t('SavePrint')}}
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
        <div v-if="!$gate.isPurchase() || !$gate.Create()">
            <not-found></not-found>
        </div>
    </div>

</template>

<script>
    export default {
        data() {
            return {
                rtl: null,
                i: 2,
                suppliers: [],
                form: new Form({
                    date: '',
                    medicineId: [],
                    BillNo: '',
                    image: '',
                    supplierRef: '',
                    supplier: null,
                    Batch_No: [],
                    Expiry: [],
                    Quantity: [],
                    Free_Quantity: [],
                    Rate: [],
                    Mrp: [],
                    Amount: [],
                    paid:0,
                    remind:0,
                    total: 0,
                    description: '',
                    discount: 0,

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
                $('.row_1:last-child td:nth-child(1) input').focus();
                this.i++
            },

            sumTotal() {
                this.form.paid = Number(this.form.total) - Number(this.form.discount);
                this.form.remind = Number(this.form.total) - Number(this.form.discount) - Number(this.form.paid);
            },
            RemaindTotal() {
                this.form.remind = Number(this.form.total) - Number(this.form.discount) - Number(this.form.paid);
            },
            uploadPhoto(e) {
                let file = e.target.files[0];
                if (file['size'] < 7111775) {
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

            saveData() {
                this.$Progress.start()
                var i = 0;
                let that = this;
                let id = [];
                let BatchNo = [];
                let expiry = [];
                let quantity = [];
                let free_Quantity = [];
                let rate = [];
                let mrp = [];
                let amount = [];
                $('tbody tr').each(function () {


                    if ($(this).css('display') != 'none') {
                        $(this).addClass('StoreData');
                        id[i] = $('.StoreData td:nth-child(2) input:first-child').val();
                        BatchNo[i] = $('.StoreData td:nth-child(3) input:first-child').val();
                        expiry[i] = $('.StoreData td:nth-child(4) input:first-child').val();
                        quantity[i] = $('.StoreData td:nth-child(5) input:first-child').val();
                        free_Quantity[i] = $('.StoreData td:nth-child(6) input:first-child').val();
                        rate[i] = $('.StoreData td:nth-child(7) input:first-child').val();
                        mrp[i] = $('.StoreData td:nth-child(8) input:first-child').val();
                        amount[i] = $('.StoreData td:nth-child(9) input:first-child').val();

                        $(this).removeClass('StoreData');
                        i++
                    }

                });

                that.form.medicineId = id;
                that.form.Batch_No = BatchNo;
                that.form.Expiry = expiry;
                that.form.Quantity = quantity;
                that.form.Free_Quantity = free_Quantity;
                that.form.Rate = rate;
                that.form.Mrp = mrp;
                that.form.Amount = amount;

                that.form.date = $('#date').val();

                this.form.post('/api/purchases').then((data) => {

                    if (data.data.error) {
                        toast.fire({
                            icon: 'error',
                            title: data.data.error
                        });
                        this.$Progress.fail()
                    }

                    if (data.data.success) {
                        Fire.$emit('success', data.data.success);
                        this.$router.push('/purchase')
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
                let free_Quantity = [];
                let rate = [];
                let mrp = [];
                let amount = [];
                $('tbody tr').each(function () {


                    if ($(this).css('display') != 'none') {
                        $(this).addClass('StoreData');
                        id[i] = $('.StoreData td:nth-child(2) input:first-child').val();
                        BatchNo[i] = $('.StoreData td:nth-child(3) input:first-child').val();
                        expiry[i] = $('.StoreData td:nth-child(4) input:first-child').val();
                        quantity[i] = $('.StoreData td:nth-child(5) input:first-child').val();
                        free_Quantity[i] = $('.StoreData td:nth-child(6) input:first-child').val();
                        rate[i] = $('.StoreData td:nth-child(7) input:first-child').val();
                        mrp[i] = $('.StoreData td:nth-child(8) input:first-child').val();
                        amount[i] = $('.StoreData td:nth-child(9) input:first-child').val();

                        $(this).removeClass('StoreData');
                        i++
                    }

                });

                that.form.medicineId = id;
                that.form.Batch_No = BatchNo;
                that.form.Expiry = expiry;
                that.form.Quantity = quantity;
                that.form.Free_Quantity = free_Quantity;
                that.form.Rate = rate;
                that.form.Mrp = mrp;
                that.form.Amount = amount;

                that.form.date = $('#date').val();

                this.form.post('/api/purchases').then((data) => {

                    if (data.data.error) {
                        toast.fire({
                            icon: 'error',
                            title: data.data.error
                        });
                        this.$Progress.fail()
                    }

                    if (data.data.success) {
                        Fire.$emit('purchasesId', data.data.id);
                        Fire.$emit('success', data.data.success);
                        this.$router.push('/purchase')
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
            $('#Purchase').on('keyup', '.addrow', function (e) {
                if (e.which == 13) {
                    that.addrow();
                }
            });
            // Remove Row
            $('.remove').on('click', function () {
                $(this).parent().parent().remove();
            });

             // Show Names of Medicines
            $('.loadMedicines').on('keyup', function () {
                var value = $(this).val();
                if (value != '') {
                    axios.get('/api/medicine/' + value).then((data) => {
                        var html = '';
                        for (var i = 0; i < data.data.length; i++) {
                            html += "<option class='list-group-item medName' data-barcode='" + data
                                .data[i]
                                .barcode + "' data-id=" + data.data[i].id +
                                " value='" + data.data[i].name +","+ data.data[i].groups +"' ></option>";
                        };
                        $(this).next().css('display', '');
                        $(this).next().html(html);
                    });

                } else {
                    $(this).next().css('display', 'none');
                }
            });

            // Fill The date 
            $('#Purchase').on('keyup  change', '#date', function () {

                that.form.date = $(this).val();
            });

            // Select Medicines , show barcode
            $('#Purchase').on('change', '.loadMedicines', function () {
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
                $('.barcode_1 td:nth-child(3) input').focus();
                $(this).parent().prev().find('input').val(barcode);
                $(this).prev().val(id);
                parent.removeClass('barcode_1');
            });

            // Enter Barcode show Medicine

            $('#Purchase').on('keyup', '.barcode', function (e) {
                if (e.which == 13) {
                    var barcode = $(this).val();

                    var parent = $(this).parent().parent();
                    parent.addClass('barcode');
                    axios.get('/api/medicine/barcode/' + barcode).then((data) => {
                        $('.barcode td:nth-child(2) input:first-child').val(data.data.id);
                        $('.barcode td:nth-child(2) input:nth-child(2)').val(data.data.name);
                        parent.removeClass('barcode');
                    });

                }
            });

            // multiplication Of Quantity and Price
            $('#Purchase').on('keyup  change', '.multi', function () {
                var parent = $(this).parent().parent();
                parent.addClass('selected_price');
                var quantity = $('.selected_price td:nth-child(5) input').val();
                var price = $('.selected_price td:nth-child(7) input').val();
                var Total_price = quantity * price;
                $('.selected_price td:nth-child(9) input').val(Total_price);
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
                that.sumTotal();

            });


            // Image Upload Design
            $('.dropify').dropify();

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd'
            });

            


        },
        created() {
            this.$Progress.start();
            axios.get('/api/supplier/supplier').then(({
                data
            }) => (this.suppliers = data.data));
            axios.get('/api/purchases/bill-no').then((data) => (this.form.BillNo = data.data));
            this.form.date =DateFormat();
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
