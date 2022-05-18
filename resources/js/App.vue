<template>
     <router-view></router-view>
</template>

<script>
export default {
    mounted(){
        if(window.Laravel.isLoggedin){
            let user = window.Laravel.user;
            this.auth = true;
            if(user.role == 3){
                this.$router.push({name:'adminteacher'})
            }else if(user.role == 2){
                this.$router.push({name:'tstudent'})
            }else if(user.role == 1){
                this.$router.push({name:'sannouncement'})
            }
        }
    }
}
</script>

<style>

</style>
