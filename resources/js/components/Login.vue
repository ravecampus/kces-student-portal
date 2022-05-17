<template>
    <div class="d-flex flex-column align-items-center row mt-3" v-if="!auth">
        <div class="col-md-4">
            <div class="card mb-5 mt-3">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center border-bottom p-3 mb-3">
                        <img class="img-responsive logo-kces" src="img/kces.png"/>
                        <h4>KATIDTUAN CES</h4>
                    </div>
                    <div class="alert alert-danger p-0" v-if="errors.main">
                        <div class="alert errors-material m-0">
                            <div v-if="errors.main"><strong>*</strong>{{errors.main[0]}}</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" v-model="post.username"  @keyup.enter="userLogin()" class="form-control">
                          <span class="errors-material" v-if="errors.username">{{errors.username[0]}}</span>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" @keyup.enter="userLogin()" v-model="post.password" class="form-control">
                        <span class="errors-material" v-if="errors.password">{{errors.password[0]}}</span>
                    </div>
                   
                    <!-- <div class="card card-body"> -->
                        <button type="button" :disabled="btn_dis" @click="userLogin()" class="btn btn-primary btn-block btn-lg">{{ btn_log }}</button>
                        <!-- <button type="button" class="btn btn-default pull-right">Cancel</button> -->
                    <!-- </div> -->
                </div>
            </div>
            
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            auth:false,
            errors:[],
            post:{},
            btn_log:"Sign in",
            btn_dis:false
        }
    },
    methods:{
        userLogin(){
            this.btn_log ='Accessing....'
            this.btn_dis = true;
            this.$axios.get('sanctum/csrf-cookie').then(res=>{
                this.$axios.post('api/login', this.post).then(res=>{
                    window.location.href="/";
                    this.btn_log = "Sign in";
                     this.btn_dis = false;
                }).catch(err=>{
                    this.btn_log = "Sign in";
                    this.btn_dis = false;
                    this.error = '';
                    this.errors = err.response.data.errors
                });
            });
        }
    },
    // beforeRouteEnter(to, from, next) {
    //     if (window.Laravel.isLoggedin) {
    //         let user = window.Laravel.user;
    //         return next('/');
    //     }
    //     next();
    // },
    mounted(){
      
        if(window.Laravel.isLoggedin){
            let user = window.Laravel.user;
            this.auth = true;
            if(user.role == 3){
                this.$router.push({name:'adminteacher'})
            }else if(user.role == 2){
                this.$router.push({name:'tstudent'})
            }else if(user.role == 1){
                this.$router.push({name:'student'})
            }
        }
    }
}
</script>

<style>
    .logo-kces{
        width: 40%;
    }
</style>
