<template>
       <div class="wrapper d-flex align-items-stretch">
        <nav id="sidebar" >
            <div class="p-4 pt-5">
            <a href="#" class="img logo rounded-circle mb-5" style="background-image: url(/../img/kces.png);"></a>
	        <div class="d-flex justify-content-center">
                <strong class="align-center ">{{ user.first_name }} {{ user.last_name }} </strong>
            </div>
            <div class="d-flex justify-content-center mb-4">
                ({{ extractAdvisory(user) }} )
            </div>
            
            <ul class="list-unstyled components mb-5">
	          <li class="active">
	            <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Student Dashboard</a>
	            <ul class="collapse list-unstyled" id="homeSubmenu">
                <li>
                    <router-link :to="{name:'sgrade'}" >Grade</router-link>
                </li>
                <li>
                    <router-link :to="{name:'sschedule'}">Schedule</router-link>
                </li>
                <!-- <li>
                    <a href="#">Home 3</a>
                </li> -->
	            </ul>
	          </li>
	        </ul>

	        <div class="footer">
	        	
	        </div>

	      </div>
    	</nav>

        <!-- Page Content  -->
      <div id="content" class="p-4 p-md-5">

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
          <div class="container-fluid">

            <button type="button" id="sidebarCollapse" class="btn btn-primary">
              <i class="fa fa-bars"></i>
              <span class="sr-only">Toggle Menu</span>
            </button>
            <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-bars"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="nav navbar-nav ml-auto">
                <li class="nav-item">
                     <router-link class="nav-link" :to="{name:'sannouncement'}">Announcement <span class="badge badge-success" v-if="announces.length > 0">{{ announces.length }}</span></router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="{name:'sprofile'}">Profile</router-link>
                </li>
                <li class="nav-item">
                    <a class="nav-link" @click="logout"  href="#">Logout</a>
                </li>
              </ul>
            </div>
          </div>
        </nav>
         <router-view></router-view>
      </div>

    </div>
</template>

<script>
export default {
    data(){
        return{
            user:{},
            sections:[],
            syears:[],
            announces:[]
        }
    },
    methods:{
        logout(){
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('/api/logout')
                    .then(response => {
                        if (response.data.success) {
                            window.location.href = "/"
                        } 
                    })
                    .catch(function (error) {
                       
                    });
            })
        },
        userExtract(id){
            this.$axios.get('sanctum/csrf-cookie').then(res=>{
                this.$axios.get('api/student/'+id).then(res=>{
                  this.user = res.data;
                });
            });
        },
        listLevelSection(){
            this.$axios.get('sanctum/csrf-cookie').then(res=>{
                this.$axios.get('api/section/list').then(res=>{
                    this.sections = res.data;
                });
            });
        },
        listLevelSY(){
            this.$axios.get('sanctum/csrf-cookie').then(res=>{
                this.$axios.get('api/syear/list').then(res=>{
                    this.syears = res.data;
                });
            });
        },
        extractAdvisory(data){
            if(data != null){
                return this.levelSection(data) + ", "+this.levelSY(data);
            }
        },
        levelSection(data){
            let ret = "";
            $.each(this.sections, function(key, value) {
               if(value.id == data.section_id){
                   ret ="Grade "+ value.level_of +" "+value.section_name;
               }
            });
            return ret;         
        },
        levelSY(data){
            let ret = "";
            $.each(this.syears, function(key, value) {
               if(value.id == data.school_year_id){
                   ret ="S.Y. "+ value.sy_name +" - "+(parseInt(value.sy_name) + 1);
               }
            });
            return ret;         
        },
        listAnnounce(url='api/announce'){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.$axios.get(url,{params:this.tableData}).then(res=>{
                    this.announces =  res.data
                }).catch(err=>{
                
                });
            });
        }
    },
    mounted(){
        this.listLevelSY();
        this.listLevelSection();
        this.userExtract(window.Laravel.user.id);
        this.listAnnounce();
    }
}
</script>

<style>

</style>
