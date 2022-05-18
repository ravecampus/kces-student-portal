<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-3 border-bottom">
                 <h5>Announcement</h5>
            </div>
           
            <div class="col-md-12">
                <div class="list-group">
                    <a v-for="(list, index) in announces" :key="index" href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">{{ list.title }}</h5>
                        <small>
                            <timeago :datetime="list.created_at"/>
                        </small>
                       
                        </div>
                        <p class="mb-1">{{ list.description }}</p>
                        <small>Until on : {{ list.expiry_date }}</small>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

export default{
    data(){
        return{
            announces:[],
        }
    },
    methods:{
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
        this.listAnnounce();
    }
}

</script>