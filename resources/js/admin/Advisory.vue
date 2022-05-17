<template>
    <div class="container">
        <!-- <button type="button" @click="openModalTeacher()" class="btn btn-primary">
            <span class="fa fa-plus"></span>
            Add Teacher</button> -->
            <h4>Teacher Advisory</h4>
        <div class="row border-top border-bottom mt-2">
            <div class="col-md-12">
                 <div class="card-body">
                    <div class="col-md-12 mt-2">
                        <div class="row search-head">

                            <div class="col-md-7 mb-2">
                                <input type="text" class="form-control" v-model="tableData.search"  placeholder="Search ..." @keyup.enter="listOfTeacher()">
                            </div>
                            <div class="col-md-3">
                                <div class="col-md-12 pull-right">
                                    <select class="form-control" aria-placeholder="sort" v-model="tableData.archive" @change="listOfTeacher()">
                                        <option value="0">Active</option>
                                        <option value="1">Inactive</option>
                
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="col-md-12 pull-right">
                                    <select class="form-control" aria-placeholder="sort" v-model="tableData.length" @change="listOfTeacher()">
                                        <option value="5" selected="selected">5</option>
                                        <option value="10">10</option>
                                        <option value="20">20</option>
                                        <option value="30">30</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <data-table :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
                        <tbody>
                            <tr v-for = "(list, index) in teachers" :key="index" class="linkTable">
                                
                                <td><strong>{{ list.id_number }}</strong></td>
                                <td>{{ list.last_name }}, {{list.first_name}} {{list.middle_name}}</td>
                                <td>{{ extractAdvisory(list.advise) }}</td>
                                <td>
                                    <div class="btn-group pull-right">
                                        <button type="button" @click="setupAdvisory(list)" class="btn btn-success btn-sm">Advisory</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" v-show="!noData(teachers)">
                                    No Result Found!
                                </td>
                            </tr>
                        </tbody>
                        </data-table>
                        <div class="table-footer pull-right">
                            <pagination :pagination="pagination"
                                @prev="listOfTeacher(pagination.prevPageUrl)"
                                @next="listOfTeacher(pagination.nextPageUrl)"
                                v-show="noData(teachers)">
                            </pagination>
                        </div>
                </div>
            </div>
        </div>
         <div class="modal fade advisory">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Advisory Information</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Level and Section</label>
                                <select class="form-control" v-model="post.section_id">
                                    <option v-for="(list, index) in sections" :key="index" :value="list.id">
                                       Grade {{ list.level_of }}, ({{ list.section_name }})
                                    </option>
                                </select>
                                <span class="errors-material" v-if="errors.section_id">{{errors.section_id[0]}}</span>
                            </div>
                            <div class="col-md-12 form-group">
                                <label>School Year</label>
                                <select class="form-control" v-model="post.school_year_id">
                                    <option v-for="(list, index) in syears" :key="index" :value="list.id">
                                       S.Y. {{ list.sy_name }} - {{ parseInt(list.sy_name) + 1}}
                                    </option>
                                </select>
                                <span class="errors-material" v-if="errors.school_year_id">{{errors.school_year_id[0]}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                            <div class="btn-group">
                            <button type="button"  @click="saveAdvisory()"  class="btn btn-primary btn-lg">{{btn_cap}}</button>
                            <button type="button" data-dismiss="modal"  class="btn btn-default btn-lg">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <div class="modal fade delete-teacher">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <!-- <div class="modal-header">
                            <h4>ORGANIZATION CATEGORY</h4>
                        </div> -->
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    Do you want to Delete <strong>{{ post.id_number }} </strong>?
                                    
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                                <div class="btn-group">
                                <button type="button"  @click="deleteTeacher(post)"  class="btn btn-danger btn-sm">Yes</button>
                                <button type="button" data-dismiss="modal"  class="btn btn-default btn-sm">No</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             <div class="modal fade credential">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>TEACHER CREDENTIAL</h4>
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
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                                <div class="btn-group">
                                <button type="button"  @click="saveCredential(post)"  class="btn btn-danger">Save</button>
                                <button type="button" data-dismiss="modal"  class="btn btn-default btn-sm">Cancel</button>
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

export default {

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
        let sortOrders = {};
        let columns =[
            {label:'ID #', name:'id_num'},
            {label:'Name', name:'last_name'},
            {label:'Adviser of', name:null},
        
            {label:'', name:null},
            ];
        
        columns.forEach(column=>{
            sortOrders[column.name] = -1;
        });
        return{
            btn_cap:"Save",
            teachers:[],
            post:{
                
            },
            advise:null,
            syears:[],
            errors:[],
            columns:columns,
            sections:[],
            sortOrders:sortOrders,
            sortKey:'created_at',
            tableData:{
                draw:0,
                length:10,
                search:'',
                column:0,
                archive:0,
                dir:'desc',
            },
            pagination:{
                lastPage:'',
                currentPage:'',
                total:'',
                lastPageUrl:'',
                nextPageurl:'',
                prevPageUrl:'',
                from:'',
                to:''
            },
        }
    },
    methods:{
        openModalTeacher(){
            this.post = {};
            $('.teacher').modal('show');
        },
        saveAdvisory(){
            this.$axios.get('sanction/csrf-cookie').then(response=>{
                if(this.advise != null){
                    this.$axios.put('api/advisory/'+this.post.advisory_id, this.post).then(res=>{
                        this.post = {};
                        $('.advisory').modal('hide');
                        this.listOfTeacher();
                    }).catch(err=>{
                        this.error = '';
                        this.errors = err.response.data.errors
                    });
                }else{                  
                    this.$axios.post('api/advisory', this.post).then(res=>{
                        this.post = {};
                        $('.advisory').modal('hide');
                        this.listOfTeacher();
                    }).catch(err=>{
                        this.error = '';
                        this.errors = err.response.data.errors
                    });
                }

            });
        },
        listOfTeacher(url='api/teacher'){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.tableData.draw ++;
                this.$axios.get(url,{params:this.tableData}).then(res=>{
                let data = res.data;
                    if(this.tableData.draw == data.draw){
                        this.teachers = data.data.data;
                        this.configPagination(data.data);
                    }else{
                        this.not_found = true;
                    }
                
                }).catch(err=>{
                
                });
            });
        },
        configPagination(data){
            this.pagination.lastPage = data.last_page;
            this.pagination.currentPage = data.current_page;
            this.pagination.total = data.total;
            this.pagination.lastPageUrl = data.last_page_url;
            this.pagination.nextPageUrl = data.next_page_url;
            this.pagination.prevPageUrl = data.prev_page_url;
            this.pagination.from = data.from;
            this.pagination.to = data.to;
        },
        sortBy(key){
            if(key != null){
                this.sortKey = key;
                this.sortOrders[key] = this.sortOrders[key] * -1;
                this.tableData.column = this.getIndex(this.columns, 'name', key);
                this.tableData.dir = this.sortOrders[key] === 1 ? 'asc' : 'desc';
                this.listOfTeacher();
            }
        },
        getIndex(array, key, value){
            return array.findIndex(i=>i[key] == value)
        },
        noData(data){
            return data == undefined ? true : (data.length > 0) ? true : false;
        },

        setupAdvisory(data){
            this.post = {};
            this.advise = data.advise;
            if(data.advise != null){
                 this.post['advisory_id'] = data.advise.id;
                this.post.section_id = data.advise.section_id;
                this.post.school_year_id = data.advise.school_year_id;
            }else{
                this.post = data;
            }
           
            $('.advisory').modal('show');
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
        }


        
    },
    mounted(){
        this.listOfTeacher();
        this.listLevelSection();
        this.listLevelSY();
    }

}
</script>

<style>

</style>
