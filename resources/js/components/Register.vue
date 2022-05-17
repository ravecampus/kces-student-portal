<template>
    <div class="row mt-5">
         <div class="modal fade register" ref="register">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Admin Registration</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" v-model="post.username" class="form-control">
                                    <span class="errors-material" v-if="errors.username">{{errors.username[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" v-model="post.email" class="form-control">
                                    <span class="errors-material" v-if="errors.email">{{errors.email[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" v-model="post.password" class="form-control">
                                    <span class="errors-material" v-if="errors.password">{{errors.password[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label>Confirmed</label>
                                    <input type="password" v-model="post.password_confirmation" class="form-control">
                                    <span class="errors-material" v-if="errors.password_confirmation">{{errors.password_confirmation[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label>Secret Code</label>
                                    <input type="password" v-model="post.secret_code" @input="checkCode(post)" class="form-control">
                                    <span class="errors-material" v-if="errors.secret">{{errors.secret_code[0]}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button type="button" :disabled="btn_save"  @click="registerUser(post)"  class="btn btn-primary">Save</button>
                            <button type="button" @click="cancelReg()"  class="btn btn-default">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            errors:[],
            post:{},
            btn_save: true
        }
    },
    methods:{
        showModal(){
            $(this.$refs.register).modal({
                backdrop: 'static',
                keyboard: false
            })
        },
        registerUser(data){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.post('api/admin-windev', data).then(res=>{
                    $(this.$refs.register).modal('hide');
                    this.$router.push({name:'login'});
                }).catch(err=>{
                    this.errors = err.response.data.errors
                });
            })
            
        },
        cancelReg(){
            $(this.$refs.register).modal('hide');
            this.$router.push({name:'login'});
        },
        checkCode(data){
            if(data.secret_code == 'winsdev'){
                this.btn_save = false;
            }else{
                this.btn_save = true;
            }
        }
    },
    mounted(){
        this.showModal();
    },
    created(){
      
    }

}
</script>

<style>

</style>
