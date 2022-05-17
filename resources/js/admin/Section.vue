<template>
    <div class="container">
        <button type="button" @click="openModalSection()" class="btn btn-primary">
            <span class="fa fa-plus"></span>
            Add Level and Section</button>
        <div class="row border-top border-bottom mt-2">
            <div class="col-md-12">
                 <div class="card-body">
                    <div class="col-md-12 mt-2">
                        <div class="row search-head">

                            <div class="col-md-10 mb-2">
                                <input type="text" class="form-control" v-model="tableData.search"  placeholder="Search ..." @keyup.enter="listOfTeacher()">
                            </div>
                            <!-- <div class="col-md-3">
                                <div class="col-md-12 pull-right">
                                    <select class="form-control" aria-placeholder="sort" v-model="tableData.archive" @change="listOfTeacher()">
                                        <option value="0">Active</option>
                                        <option value="1">Inactive</option>
                
                                    </select>
                                </div>
                            </div> -->
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
                            <tr v-for = "(list, index) in sections" :key="index" class="linkTable">
                                
                                <td><strong>{{ extractSection(list.level_of) }}</strong></td>
                                <td><strong>{{ list.section_name }}</strong></td>
                                <td>
                                    <div class="btn-group pull-right">
                                        <button type="button" @click="showeditSection(list)" class="btn btn-warning btn-sm">Edit</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" v-show="!noData(sections)">
                                    No Result Found!
                                </td>
                            </tr>
                        </tbody>
                        </data-table>
                        <div class="table-footer pull-right">
                            <pagination :pagination="pagination"
                                @prev="listOfSection(pagination.prevPageUrl)"
                                @next="listOfSection(pagination.nextPageUrl)"
                                v-show="noData(sections)">
                            </pagination>
                        </div>
                </div>
            </div>
        </div>
         <div class="modal fade section">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Level and Section Information</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Grade Level </label>
                                <select v-model="post.level_of" class="form-control">
                                    <option value="1">Grade 1</option>
                                    <option value="2">Grade 2</option>
                                    <option value="3">Grade 3</option>
                                    <option value="4">Grade 4</option>
                                    <option value="5">Grade 5</option>
                                    <option value="6">Grade 6</option>
                                </select>
                                <span class="errors-material" v-if="errors.level_of">{{errors.level_of[0]}}</span>
                            </div>
                            <div class="col-md-12 form-group">
                                <label>Section Name</label>
                                <input type="text" v-model="post.section_name" class="form-control">
                                <span class="errors-material" v-if="errors.section_name">{{errors.section_name[0]}}</span>
                            </div>
                        </div>
                      
                        <div class="modal-footer">
                            <div class="btn-group">
                                <button type="button"  @click="saveSection"  class="btn btn-primary btn-lg">{{btn_cap}}</button>
                                <button type="button" data-dismiss="modal"  class="btn btn-default btn-lg">Cancel</button>
                            </div>
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
            {label:'Level', name:'level_of'},
            {label:'Section', name:'section_name'},
            {label:'', name:null},
            ];
        
        columns.forEach(column=>{
            sortOrders[column.name] = -1;
        });
        return{
            btn_cap:"Save",
            sections:[],
            post:{
                
            },
            errors:[],
            columns:columns,
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
        openModalSection(){
            this.errors = [];
            this.post = {};
            $('.section').modal('show');
        },
        saveSection(){
            this.$axios.get('sanction/csrf-cookie').then(response=>{
                if(this.post.id > 0){
                    this.$axios.put('api/section/'+this.post.id, this.post).then(res=>{
                        this.post = {};
                        $('.section').modal('hide');
                        this.listOfSection();
                    }).catch(err=>{
                        this.error = '';
                        this.errors = err.response.data.errors
                    });
                }else{                  
                    this.$axios.post('api/section', this.post).then(res=>{
                        this.post = {};
                        $('.section').modal('hide');
                        this.listOfSection();
                    }).catch(err=>{
                        this.error = '';
                        this.errors = err.response.data.errors
                    });
                }

            });
        },
        listOfSection(url='api/section'){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.tableData.draw ++;
                this.$axios.get(url,{params:this.tableData}).then(res=>{
                let data = res.data;
                    if(this.tableData.draw == data.draw){
                        this.sections = data.data.data;
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
                this.listOfSection();
            }
        },
        getIndex(array, key, value){
            return array.findIndex(i=>i[key] == value)
        },
        noData(data){
            return data == undefined ? true : (data.length > 0) ? true : false;
        },
        showeditSection(data){
            this.post = data;
            $('.section').modal('show');
        },
        extractSection(data){
            return 'Grade '+data;
        },
    
        
    },
    mounted(){
        this.listOfSection();
    }

}
</script>

<style>

</style>
