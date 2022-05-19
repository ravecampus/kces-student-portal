<template>
     <router-view></router-view>
</template>

<script>
export default {
    mounted(){
        // if(window.Laravel.isLoggedin){
        //     let user = window.Laravel.user;
        //     this.auth = true;
        //     if(user.role == 3){
        //         window.location.href="/admin"
        //     }else if(user.role == 2){
        //         window.location.href="/teacher"
        //     }else if(user.role == 1){
        //         window.location.href="/student"
        //     }
        // }
    }
}
</script>

<style>

</style>
