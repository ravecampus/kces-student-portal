<template>
    <div class="container">
        <h4>Setup Files</h4>
        <div class="row border-top mt-1">
            <div class="col-md-4 mt-3 border-right">
               
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" v-model="post.title" class="form-control">
                    <span class="errors-material" v-if="errors.title">{{errors.title[0]}}</span>
                </div>
                <div class="form-group">
                    <label>Link</label>
                    <textarea v-model="post.link" class="form-control"></textarea>
                    <span class="errors-material" v-if="errors.link">{{errors.link[0]}}</span>
                </div>
                <div class="row">
                    <div class="btn-group col-md-12 mt-3">
                        <button type="button" @click="saveFile()" class="btn btn-primary">{{ btn_cap}}</button>
                        <button type="button" @click="clear()" class="btn btn-success">Clear</button>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="mb-3 mt-3">
                   <input type="text" v-model="tableData.search"  placeholder="Search ..." @input="listFile()" class="form-control">
               </div>
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr v-for="(list, index) in files" :key="index">
                                <td>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <strong class="border-bottom">{{ list.title }}</strong>
                                                    <div>
                                                        <i>{{ list.link }}</i>
                                                    </div>
                                                    <a class="btn btn-primary btn-sm" v-bind:href="list.link" target="_blank">
                                                        <span class="fa fa-link"></span>
                                                    </a>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="btn-group pull-right">
                                                        <button type="button" @click="editFile(list)" class="btn btn-warning btn-sm">edit</button>
                                                        <button type="button" @click="showRemoveModal(list)" class="btn btn-danger btn-sm">delete</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade remove-file">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <!-- <div class="modal-header">
                        <h4>ORGANIZATION CATEGORY</h4>
                    </div> -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                Do you want to Remove <strong>{{ deleteData.title }} </strong>?
                                
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                            <div class="btn-group">
                            <button type="button"  @click="deleteFile(deleteData)"  class="btn btn-danger btn-sm">Yes</button>
                            <button type="button" data-dismiss="modal"  class="btn btn-default btn-sm">No</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script>
import DataTable from '../table/DataTable'
import PaginationVue from '../table/Pagination';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';


    export default{
        components:{
            Datepicker,
            dataTable:DataTable,
            pagination:PaginationVue
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
        data(){
            return{
                post:{},
                errors:[],
                btn_cap:'Save',
                files:[],
                tableData:{
                    search:""
                },
                deleteData:{}
            }
        },
        methods:{
           saveFile(){
                this.$axios.get('sanction/csrf-cookie').then(response=>{
                    this.btn_cap = "Saving...";
                    if(this.post.id > 0){
                        this.$axios.put('api/file/'+this.post.id, this.post).then(res=>{
                            this.btn_cap = "Save";
                            this.post = {};
                            this.errors = [];
                            this.listFile();
                        }).catch(err=>{
                            this.btn_cap = "Save";
                            this.error = '';
                            this.errors = err.response.data.errors
                        });
                    }else{            
                        this.$axios.post('api/file', this.post).then(res=>{
                            this.btn_cap = "Save";
                            this.post = {};
                            this.errors = [];
                            this.listFile();
                        }).catch(err=>{
                            this.error = '';
                            this.btn_cap = "Save";
                            this.errors = err.response.data.errors
                        });
                    }

                });
            },
            listFile(url='api/file'){
                this.$axios.get('sanctum/csrf-cookie').then(response => {
                    this.$axios.get(url,{params:this.tableData}).then(res=>{
                        this.files =  res.data
                    }).catch(err=>{
                    
                    });
                });
            },
            editFile(data){
                this.post = data;
            },
            showRemoveModal(data){
                this.deleteData = data;
                $('.remove-file').modal('show');
            },
            deleteFile(data){
                 this.$axios.get('sanctum/csrf-cookie').then(response => {
                    this.$axios.delete('api/file/'+data.id).then(res=>{
                        this.listFile();
                        $('.remove-file').modal('hide');
                    }).catch(err=>{
                    
                    });
                });
            },
            clear(){
                this.post = {};
            }
        },
        mounted(){
            this.listFile();
        }
    };
</script>