<template>
    <div class="container-fluid">
        <div v-if="$gate.isBarcode() && $gate.Read()">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $t('barcode')}}</h3>
                            <div class="card-tools">
                                <button class="btn btn-success" @click="print">
                                    <i class="fa fa-print "></i> </button>
                                <!-- <router-link class="btn btn-success" to="/print/user" >Print <i class="fa fa-print fa fw" ></i> </router-link> -->
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-11">
                                    <multiselect v-model="value" :multiple="true" :close-on-select="false"
                                        :clear-on-select="false" :preserve-search="true" placeholder="Select  Medicine"
                                        label="name" track-by="id" :options="options"></multiselect>
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-success" @click="Show">Show</button>
                                </div>
                            </div>
                            
                            <div id="app">
                                <div id="printme">

                                    <div v-for="medicine in list" :key="medicine.barcode" class="barcode" style="display:inline;height:17mm;width:54mm">

                                        <barcode :value="medicine.barcode" height="20px" width="2%" :text="medicine.name"
                                            font-size="10px" style="display:inline;height:17mm;width:54mm"
    >
                                        </barcode>


                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="!$gate.isBarcode()">
            <not-found></not-found>
        </div>
    </div>
</template>

<script>
    import InfiniteLoading from 'vue-infinite-loading';
    import {
        Printd
    } from 'printd'

    const cssText = `
    
@page {
  size: 54mm 17mm ;
  margin:0;
}


`


    export default {
        data() {
            return {
                list: [],
                page: 1,
                value: [],
                options: [],
                barcode: []

            }
        },
        methods: {
            print() {
                const d = new Printd();
                d.print(document.getElementById('printme'), [cssText])
            },
            Show() {
                this.$Progress.start();
                if (this.value.length == 0) {

                    axios.get('api/barcode/print').then((data) => {
                        this.list = (data.data);
                        this.$Progress.finish();
                    });
                } else {
                    this.barcode = [];
                    for (var i = 0; i < this.value.length; i++) {
                        this.barcode.push(this.value[i].id)
                    }
                    axios({
                        url: 'api/barcode/print',
                        method: 'post',
                        data: this.barcode
                    }).then((data) => {
                        this.list = (data.data);
                        this.$Progress.finish();
                    });

                }
            }
        },
        mounted() {
            $('.dropify').dropify();

            axios.get('api/SearchBarcode').then((data) => {
                this.options = (data.data.data);
            });
        },

    }

</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
