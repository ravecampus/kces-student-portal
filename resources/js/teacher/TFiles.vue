<template>
    <div class="container mt-5">
        <h4>Files</h4>
        <div class="mb-3 mt-3">
            <input type="text" v-model="tableData.search"  placeholder="Search ..." @input="listFile()" class="form-control">
        </div>
        <div class="row">
            <div class="col-md-4" v-for="(list, index) in files" :key="index">
                <div class="card p-3">
                    <div class="d-flex flex-row mb-3"><img src="/../img/kces.png" width="70">
                        <div class="d-flex flex-column ml-2"><span>{{ list.title }}</span>
                        <span class="text-black-50">Documents</span>
                        <span class="ratings">
                            
                            <i class="fa fa-file-excel-o"></i>&nbsp;
                            <i class="fa fa-file-pdf-o"></i>&nbsp;
                            <i class="fa fa-file-word-o"></i>&nbsp;
                            <i class="fa fa-file-powerpoint-o"></i>
                            </span>
                        </div>
                    </div>
                    <h6>{{ list.link }}</h6>
                    <div class="d-flex justify-content-between install mt-3"><span>Posted:  <timeago :datetime="list.created_at"/> </span>
                    <a class="text-primary" v-bind:href="list.link" target="_blank">View&nbsp;
                        <i class="fa fa-angle-right"></i>
                    </a>
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
            files:[],
            tableData:{
                 search:""
            },
        }
    },
    methods:{
        listFile(url='api/file'){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.$axios.get(url,{params:this.tableData}).then(res=>{
                    this.files =  res.data
                }).catch(err=>{
                
                });
            });
        },
    },
    mounted(){
        this.listFile();
    }
}
</script>

<style>
body {
  background: #eee;
  
}

.ratings i {
  color: green;
}

.install span {
  font-size: 12px;
}

.col-md-4 {
  margin-top: 27px;
}
</style>
