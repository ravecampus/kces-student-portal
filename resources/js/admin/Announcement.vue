<template>
    <div class="container">
        <h4>Setup Announcement</h4>
        <div class="row border-top mt-1">
            <div class="col-md-7 mt-3 border-right">
                <div class="row">
                    <div class="form-group col-md-7">
                        <label>Title</label>
                        <input type="text" v-model="post.title" class="form-control">
                        <span class="errors-material" v-if="errors.title">{{errors.title[0]}}</span>
                    </div>
                    <div class="form-group col-md-5 pl-0">
                        <label>Expiry Date:</label>
                        <Datepicker v-model="post.expiry_date" :format="format" />
                        <span class="errors-material" v-if="errors.expiry_date">{{errors.expiry_date[0]}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea v-model="post.description" class="form-control"></textarea>
                    <span class="errors-material" v-if="errors.description">{{errors.description[0]}}</span>
                </div>
                <div class="row">
                    <div class="btn-group col-md-12 mt-3">
                        <button type="button" @click="saveAnnounce()" class="btn btn-primary">{{ btn_cap}}</button>
                        <button type="button" @click="clear()" class="btn btn-default">Clear</button>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="mb-3 mt-3">
                   <input type="text" v-model="tableData.search"  placeholder="Search ..." @input="listAnnounce()" class="form-control">
               </div>
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr v-for="(list, index) in announces" :key="index">
                                <td>
                                    <div class="card">
                                        <div class="card-body">
                                           
                                            <strong class="border-bottom">{{ list.title }}</strong>
                                            
                                            <div>
                                                <i>{{ list.description }}</i>
                                            </div>
                                             <small class="text-muted">until on: {{ list.expiry_date}}</small>
                                            
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" @click="editAnnounce(list)" class="btn btn-warning btn-sm">edit</button>
                                        <button type="button" class="btn btn-danger btn-sm">remove</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
                btn_cap:'Post',
                announces:[],
                tableData:{
                    search:""
                }
            }
        },
        methods:{
           saveAnnounce(){
                this.$axios.get('sanction/csrf-cookie').then(response=>{
                    this.btn_cap = "Posting...";
                    if(this.post.id > 0){
                        this.$axios.put('api/announce/'+this.post.id, this.post).then(res=>{
                            this.btn_cap = "Post";
                            this.post = {};
                            this.listAnnounce();
                        }).catch(err=>{
                            this.btn_cap = "Post";
                            this.error = '';
                            this.errors = err.response.data.errors
                        });
                    }else{            
                        this.$axios.post('api/announce', this.post).then(res=>{
                            this.btn_cap = "Post";
                            this.post = {};
        
                            this.listAnnounce();
                        }).catch(err=>{
                            this.error = '';
                            this.btn_cap = "Post";
                            this.errors = err.response.data.errors
                        });
                    }

                });
            },
            listAnnounce(url='api/announce'){
                this.$axios.get('sanctum/csrf-cookie').then(response => {
                    this.$axios.get(url,{params:this.tableData}).then(res=>{
                        this.announces =  res.data
                        console.log(res.data);
                    }).catch(err=>{
                    
                    });
                });
            },
            editAnnounce(data){
                this.post = data;
            },
            clear(){
                this.post = {};
            }
        },
        mounted(){
            this.listAnnounce();
        }
    };
</script>