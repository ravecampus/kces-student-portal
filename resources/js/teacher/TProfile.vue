<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4>Profile</h4>
                <div class="d-flex justify-content-start">
                 
                    <img class="profile-image" src="/../img/kces.png" />
                    
                    <div class="pt-2 pl-3">
                        <p>NAME: <strong>{{post.first_name}} {{post.last_name}}</strong></p>
                        <!-- <p class="m-1 bg-white p-1">AGE: <strong>Lavwin Campollo</strong></p> -->
                        <p class="m-1 bg-white p-1">SEX: <strong>{{extractSex(post.sex) }}</strong></p>
                        <p class="m-1 bg-white p-1 mb-3">ID Number: <strong>{{ post.id_number }}</strong></p>
                        <button type="button" @click="showChangePassword()" class="btn btn-success btn-sm">Changes Password</button>
                    </div>
                    <div class="ml-auto p-2">
                        <button type="button" @click="saveProfile()" class="btn btn-success">{{ btn_cap }}</button>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <!-- <div class="card-header">
                    </div> -->
                    <div class="card-body">
                        <h4>Profile Information</h4>
                        <div class="row">
                            <div class="col-md-6 border-right">
                                <div class="form-group">
                                    <label>ID Number: </label>
                                    <input type="text" v-model="post.id_number" class="form-control">
                                    <span class="errors-material" v-if="errors.id_number">{{errors.id_number[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label>First Name: </label>
                                    <input type="text" v-model="post.first_name" class="form-control">
                                     <span class="errors-material" v-if="errors.first_name">{{errors.first_name[0]}}</span>
                                </div>
                                 <div class="form-group">
                                    <label>Middle Name: </label>
                                    <input type="text" v-model="post.middle_name" class="form-control">
                                    <span class="errors-material" v-if="errors.middle_name">{{errors.middle_name[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label>Last Name: </label>
                                    <input type="text" v-model="post.last_name" class="form-control">
                                    <span class="errors-material" v-if="errors.last_name">{{errors.last_name[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label>Suffix: </label>
                                    <input type="text" v-model="post.suffix" class="form-control">
                                    <span class="errors-material" v-if="errors.suffix">{{errors.suffix[0]}}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Birth Date: </label>
                                    <Datepicker v-model="post.birthdate" :format="format"/>
                                    <span class="errors-material" v-if="errors.birthdate">{{errors.birthdate[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label>Birth Place: </label>
                                    <input type="text" v-model="post.birthpalce" class="form-control">
                                     <span class="errors-material" v-if="errors.birthpale">{{errors.birthplace[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label>Sex</label>
                                    <select v-model="post.sex" class="form-control">
                                        <option value="1">Male</option>
                                        <option value="2">Female</option>
                                    </select>
                                    <span class="errors-material" v-if="errors.sex">{{errors.sex[0]}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade changes-password">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Change Password</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Username: </label>
                                        <input type="text" v-model="pass.username" class="form-control">
                                        <span class="errors-material" v-if="errors.username">{{errors.username[0]}}</span>
                                    </div>
                                    <div class="form-group">
                                        <label>New</label>
                                        <input type="password" v-model="pass.password" class="form-control">
                                        <span class="errors-material" v-if="errors.password">{{errors.password[0]}}</span>
                                    </div>
                                    <div class="form-group">
                                        <label>Confirmed</label>
                                        <input type="password" v-model="pass.password_confirmation" class="form-control">
                                        <span class="errors-material" v-if="errors.password_confirmation">{{errors.password_confirmation[0]}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                                <div class="btn-group">
                                <button type="button"  @click="savePassword()"  class="btn btn-success">{{ btn_cap_ }}</button>
                                <button type="button" data-dismiss="modal"  class="btn btn-default btn-sm">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
export default {
    components:{
        Datepicker
    },
    data(){
        return{
            errors:[],
            post:{},
            btn_cap:'Save',
            btn_cap_:'Save',
            pass:{}
        }
    },
    setup() {
        const format = (d) => {
            const day =("0" + d.getDate()).slice(-2);
            const month = ("0"+(d.getMonth()+1)).slice(-2);
            const year =  d.getFullYear();

            return  month+ "-" + day  + "-" + year;
        }
        
        return {
            format,
        }
    },
    methods:{
        userExtract(id){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get('api/teacher/'+id).then(res=>{
                  let data = res.data;
                  this.post = data;
                });
            });
        },
        showChangePassword(){
            this.pass = this.post.account;
            $('.changes-password').modal('show');
        },
        saveProfile(){
            this.btn_cap = "Saving...";
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.put('api/teacher/'+this.post.id, this.post).then(res=>{
                    this.btn_cap = "Save";
                }).catch(err=>{
                    this.btn_cap = "Save";
                    this.error = '';
                    this.errors = err.response.data.errors
                });
            });
        },
        savePassword(){
            this.btn_cap_ = "Saving...";
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.post('api/teacher/password', this.pass).then(res=>{
                    this.btn_cap_ = "Save";
                    $('.changes-password').modal('hide');
                }).catch(err=>{
                    this.btn_cap_ = "Save";
                    this.error = '';
                    this.errors = err.response.data.errors
                });
            });
        },
        extractSex(data){
            if(data != undefined){
                return data == 1 ? 'Male' : 'Female';
            }
           
        },
    },
    mounted(){
        this.userExtract(window.Laravel.user.id);
    }
}
</script>

<style>
    .profile-image{
        height: 180px;
    }
</style>
