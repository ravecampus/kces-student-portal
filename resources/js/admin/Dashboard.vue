<template>
    <div class="wrapper d-flex align-items-stretch">
        <nav id="sidebar" >
            <div class="p-4 pt-5">
            <a href="#" class="img logo rounded-circle mb-5" style="background-image: url(/../img/kces.png);"></a>
	        <ul class="list-unstyled components mb-5">
	          <li class="active">
	            <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Dashboard</a>
	            <ul class="collapse list-unstyled" id="homeSubmenu">
                <li>
                    <router-link :to="{name:'adminteacher'}" >Teachers</router-link>
                </li>
                <li>
                    <router-link :to="{name:'advisory'}" >Advisory</router-link>
                </li>
	            </ul>
	          </li>
	          <li>
              <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Setup</a>
              <ul class="collapse list-unstyled" id="pageSubmenu">
                <li>
                    <router-link :to="{name:'adminsubject'}">Subjects</router-link>
                </li>
                <li>
                    <router-link :to="{name:'section'}" >Level and Sections</router-link>
                </li>
                <li>
                     <router-link :to="{name:'schoolyear'}" >School Year</router-link>
                </li>
              </ul>
	          </li>
	          <li>
              <router-link  :to="{name:'adminannounce'}">Announcement</router-link>
	          </li>
              <li>
                <router-link  :to="{name:'adminfiles'}">Files</router-link>
	          </li>
	          <!-- <li>
              <a href="#">Contact</a>
	          </li> -->
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
                <!-- <li class="nav-item active">
                    <a class="nav-link" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li> -->
                <li class="nav-item">
                    <router-link :to="{name:'adminprofile'}" class="nav-link" >Profile</router-link>
                </li>
                <li class="nav-item">
                    <a class="nav-link" @click="logout()" href="#">Logout</a>
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
                        console.error(error);
                    });
            })
        },
    },
    // beforeRouteEnter(to, from, next) {
    //     if (window.Laravel.isLoggedin) {
    //         return next('/');
    //     }
    //     next();
    // },
}

</script>

<style>

</style>
