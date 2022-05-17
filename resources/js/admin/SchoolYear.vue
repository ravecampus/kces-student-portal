<template>
    <div class="container">
        <button type="button" @click="openModalSchoolYear()" class="btn btn-primary">
            <span class="fa fa-plus"></span>
            Add School Year</button>
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
                            <tr v-for = "(list, index) in schoolyears" :key="index" class="linkTable">
                                
                                <td><strong>{{ list.sy_name }} - {{ parseInt(list.sy_name) + 1 }}</strong></td>
                                <td>
                                    <div class="btn-group pull-right">
                                        <button type="button" @click="showeditSchoolYear(list)" class="btn btn-warning btn-sm">Edit</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" v-show="!noData(schoolyears)">
                                    No Result Found!
                                </td>
                            </tr>
                        </tbody>
                        </data-table>
                        <div class="table-footer pull-right">
                            <pagination :pagination="pagination"
                                @prev="listOfSchoolYear(pagination.prevPageUrl)"
                                @next="listOfSchoolYear(pagination.nextPageUrl)"
                                v-show="noData(schoolyears)">
                            </pagination>
                        </div>
                </div>
            </div>
        </div>
         <div class="modal fade schoolyear">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>School Year Information</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Section Name</label>
                                <input type="text" v-model="post.sy_name" class="form-control">
                                <span class="errors-material" v-if="errors.sy_name">{{errors.sy_name[0]}}</span>
                            </div>
                        </div>
                      
                        <div class="modal-footer">
                            <div class="btn-group">
                                <button type="button"  @click="saveSchoolYear"  class="btn btn-primary btn-lg">{{btn_cap}}</button>
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
            {label:'School Year', name:'sy_name'},
            {label:'', name:null},
            ];
        
        columns.forEach(column=>{
            sortOrders[column.name] = -1;
        });
        return{
            btn_cap:"Save",
            schoolyears:[],
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
        openModalSchoolYear(){
            this.errors = [];
            this.post = {};
            $('.schoolyear').modal('show');
        },
        saveSchoolYear(){
            this.$axios.get('sanction/csrf-cookie').then(response=>{
                if(this.post.id > 0){
                    this.$axios.put('api/syear/'+this.post.id, this.post).then(res=>{
                        this.post = {};
                        $('.schoolyear').modal('hide');
                        this.listOfSchoolYear();
                    }).catch(err=>{
                        this.error = '';
                        this.errors = err.response.data.errors
                    });
                }else{                  
                    this.$axios.post('api/syear', this.post).then(res=>{
                        this.post = {};
                        $('.schoolyear').modal('hide');
                        this.listOfSchoolYear();
                    }).catch(err=>{
                        this.error = '';
                        this.errors = err.response.data.errors
                    });
                }

            });
        },

        listOfSchoolYear(url='api/syear'){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.tableData.draw ++;
                this.$axios.get(url,{params:this.tableData}).then(res=>{
                let data = res.data;
                    if(this.tableData.draw == data.draw){
                        this.schoolyears = data.data.data;
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
                this.listOfSchoolYear();
            }
        },
        getIndex(array, key, value){
            return array.findIndex(i=>i[key] == value)
        },
        noData(data){
            return data == undefined ? true : (data.length > 0) ? true : false;
        },
        showeditSchoolYear(data){
            this.post = data;
            $('.schoolyear').modal('show');
        },
        extractSection(data){
            return 'Grade '+data;
        }
        
    },
    mounted(){
        this.listOfSchoolYear();
    }

}
</script>

<style>

</style>
