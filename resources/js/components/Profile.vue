<template>
    <div class="container-fluid">
        <div v-if="$gate.isProfile() && $gate.Read()" >
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-body">

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
                                            <label for="inputName" class=" control-label">{{$t('Name')}}</label>
                                            <input type="name" v-model="form.name" @keyup="changeName"
                                                class="form-control" id="inputName" placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="inputEmail" class=" control-label">{{$t('Email')}}</label>


                                            <input type="email" v-model="form.email" class="form-control"
                                                id="inputEmail" placeholder="Email">

                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="exampleInputFile" class=" col-form-label">{{$t('ProfilePicture')}}</label>
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
                                            <label for="inputSkills" class=" control-label">{{$t('password')}}</label>
                                            <input type="password" v-model="form.password" class="form-control"
                                                id="inputSkills" placeholder="password">

                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 ">
                                                <button type="submit" @click.prevent="upateProfileInfo"
                                                    class="btn btn-success">{{$t('Save')}}</button>
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
        <div v-if="!$gate.isProfile()">
            <not-found></not-found>
        </div>
    </div>
</template>

<script>
    export default {

        data() {
            return {
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    password: '',
                    photo: '',

                })
            }

        },

        mounted() {

        },

        //firing the profile event here
        methods: {
            getPhoto() {
                let prefix = (this.form.photo.match(/\//) ? '' : '/images/users/');
                Fire.$emit('changePhoto', prefix + this.form.photo);
                return prefix + this.form.photo;
            },
            upateProfileInfo() {
                this.$Progress.start();
                this.form.put('api/profile')
                    .then(() => {
                        this.$Progress.finish();
                        toast.fire({
                            icon: 'success',
                            title: 'Profile update in successfully'
                        });
                    })
                    .catch(() => {
                        this.$Progress.fail();

                    });
            },

            changeName() {
                Fire.$emit('changeName', this.form.name);
            },

            updateProfilePhoto(e) {
                let file = e.target.files[0];
                if (file['size'] < 2111775) {
                    let reader = new FileReader();
                    reader.onloadend = (file) => {
                        // console.log('RESULT', reader.result)
                        this.form.photo = reader.result;
                    }
                    reader.readAsDataURL(file);

                } else {
                    Swal.fire(
                        'Oops...',
                        'You Upload large file more then 2MB',
                        'error'
                    )
                }
            }
        },

        created() {
            axios.get("api/profile")
                .then(({
                    data
                }) => (this.form.fill(data)));
        }

    }

</script>
