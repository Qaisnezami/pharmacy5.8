<template>
    <div class="container-fluid">
        <div v-if="$gate.isBarcode() && $gate.Read()">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $t('barcode')}}</h3>
                            <div class="card-tools">


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

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                all: false,
                value: [],
                options: []
            }
        },
        methods: {
            Show() {

                if (this.value.length == 0) {

                    this.$router.push('/barcode');
                } else {
                    this.$router.push('/barcode/' + this.value);

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
