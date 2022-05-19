<template>
    <div class="container">
        <button type="button" @click="openModalStudent()" class="btn btn-primary">
            <span class="fa fa-plus"></span>
            Add Student</button>
        <div class="row border-top border-bottom mt-2">
            <div class="col-md-8">
                <h5>KATIDTUAN STUDENTS</h5>
                 <div class="card-body">
                    <div class="col-md-12 mt-2">
                        <div class="row search-head">

                            <div class="col-md-9 mb-2 pl-0 pr-0">
                                <input type="text" class="form-control" v-model="tableData.search"  placeholder="Search ..." @keyup.enter="listOfStudent()">
                            </div>
                            <!-- <div class="col-md-3 pl-0 pr-0">
                                <div class="col-md-12 pull-right">
                                    <select class="form-control" aria-placeholder="sort" v-model="tableData.archive" @change="listOfStudent()">
                                        <option value="0">Active</option>
                                        <option value="1">Inactive</option>
                
                                    </select>
                                </div>
                            </div> -->
                            <div class="col-md-3 pl-0 pr-0">
                                <div class="col-md-12 pull-right">
                                    <select class="form-control" aria-placeholder="sort" v-model="tableData.length" @change="listOfStudent()">
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
                            <tr v-for = "(list, index) in students" :key="index" class="linkTable">
                                
                                <td><strong>{{ list.lrn }}</strong></td>
                                <td><strong>{{ list.last_name }}</strong>, {{list.first_name}} {{list.middle_name}}</td>
                                <td>{{ extractSex(list.sex) }}</td>
                                <td>
                                    <div class="btn-group pull-right">
                                        <button type="button" @click="showeditStudent(list)" class="btn btn-warning btn-sm">Edit</button>
                                        <button type="button" @click="setasClass(list)" class="btn btn-success btn-sm">Set as</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" v-show="!noData(students)">
                                    No Result Found!
                                </td>
                            </tr>
                        </tbody>
                        </data-table>
                        <div class="table-footer pull-right">
                            <pagination :pagination="pagination"
                                @prev="listOfStudent(pagination.prevPageUrl)"
                                @next="listOfStudent(pagination.nextPageUrl)"
                                v-show="noData(students)">
                            </pagination>
                        </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mt-2" v-if="listasClass.length > 0">
                    <div class="card card-body">
                        <h5>My students</h5>
                        <table class="table table-sm">
                            <tbody>
                                <tr v-for="(list, index) in listasClass" :key="index">
                                     <td>{{ list.lrn }}</td>
                                    <td>{{ list.last_name }}, {{ list.first_name }} {{ list.middle_name }}</td>
                                   
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" @click="removeStudent(index)">
                                            <span class="fa fa-trash"></span>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="btn-group" v-if="listasClass.length > 0">
                            <button type="button" @click="saveAsStudent()" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <div class="modal fade student">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Student Information</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>LRN</label>
                                <input type="text" v-model="post.lrn" class="form-control">
                                <span class="errors-material" v-if="errors.lrn">{{errors.lrn[0]}}</span>
                            </div>
                            <div class="col-md-6 form-group">
                               <label>Birthdate</label>
                               <Datepicker v-model="post.birthdate" :format="format"/>
                               <span class="errors-material" v-if="errors.birthdate">{{errors.birthdate[0]}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>First Name</label>
                                <input type="text" v-model="post.first_name" class="form-control">
                                <span class="errors-material" v-if="errors.first_name">{{errors.first_name[0]}}</span>
                            </div>
                            <div class="col-md-6 form-group">
                               <label>Middle Name</label>
                               <input type="text" v-model="post.middle_name" class="form-control">
                               <span class="errors-material" v-if="errors.middle_name">{{errors.middle_name[0]}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Last Name</label>
                                <input type="text" v-model="post.last_name" class="form-control">
                                <span class="errors-material" v-if="errors.last_name">{{errors.last_name[0]}}</span>
                            </div>
                            <div class="col-md-6 form-group">
                               <label>Suffix (Optional)</label>
                               <input type="text" v-model="post.suffix" class="form-control">
                               <span class="errors-material" v-if="errors.suffix">{{errors.suffix[0]}}</span>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Address</label>
                                <input type="text" v-model="post.address" class="form-control">
                                <span class="errors-material" v-if="errors.address">{{errors.address[0]}}</span>
                            </div>
                            <div class="col-md-6 form-group">
                               <label>Age</label>
                              <input type="number" v-model="post.age" class="form-control">
                               <span class="errors-material" v-if="errors.age">{{errors.age[0]}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Birth Place</label>
                                <input type="text" v-model="post.birthplace" class="form-control">
                                <span class="errors-material" v-if="errors.birthplace">{{errors.birthplace[0]}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Sex</label>
                                <select v-model="post.sex" class="form-control">
                                    <option value="1">Male</option>
                                    <option value="2">Female</option>
                                </select>
                                <span class="errors-material" v-if="errors.sex">{{errors.sex[0]}}</span>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Civil Status</label>
                                <select v-model="post.civil_status" class="form-control">
                                    <option value="1">Single</option>
                                    <option value="2">Married</option>
                                </select>
                                <span class="errors-material" v-if="errors.sex">{{errors.sex[0]}}</span>
                            </div>    
                        </div>

                         <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Nationality</label>
                                <input type="text" v-model="post.nationality" class="form-control">
                                <span class="errors-material" v-if="errors.nationality">{{errors.nationality[0]}}</span>
                            </div>
                            <div class="col-md-6 form-group">
                               <label>Religion</label>
                                <input type="text" v-model="post.religion" class="form-control">
                               <span class="errors-material" v-if="errors.religion">{{errors.religion[0]}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                            <div class="btn-group">
                            <button type="button"  @click="saveStudent"  class="btn btn-primary btn-lg">{{btn_cap}}</button>
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
            {label:'LRN', name:'lrn'},
            {label:'Name', name:'last_name'},
            {label:'Sex', name:'sex'},
            {label:'', name:null},
            ];
        
        columns.forEach(column=>{
            sortOrders[column.name] = -1;
        });
        return{
            btn_cap:"Save",
            students:[],
            user:{},
            ls:[],
            post:{
            },
            listasClass:[],
            listStud:[],
            errors:[],
            columns:columns,
            advisory_id:null,
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
        openModalStudent(){
            this.post = {};
            $('.student').modal('show');
        },
        saveStudent(){
            this.post['advisory_id'] = this.advisory_id;
            this.$axios.get('sanction/csrf-cookie').then(response=>{
                this.btn_cap = "Saving...";
                if(this.post.id > 0){
                    this.$axios.put('api/student/'+this.post.id, this.post).then(res=>{
                        this.btn_cap = "Save";
                        this.post = {};
                        $('.student').modal('hide');
                        this.listOfStudent();
                    }).catch(err=>{
                        this.btn_cap = "Save";
                        this.error = '';
                        this.errors = err.response.data.errors
                    });
                }else{            
                    this.$axios.post('api/student', this.post).then(res=>{
                        this.btn_cap = "Save";
                        this.post = {};
                        $('.student').modal('hide');
                        this.listOfStudent();
                    }).catch(err=>{
                        this.error = '';
                        this.btn_cap = "Save";
                        this.errors = err.response.data.errors
                    });
                }

            });
        },

        listOfStudent(url='api/student'){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.tableData.draw ++;
                this.$axios.get(url,{params:this.tableData}).then(res=>{
                let data = res.data;
                    if(this.tableData.draw == data.draw){
                        this.students = data.data.data;
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
                this.listOfStudent();
            }
        },
        getIndex(array, key, value){
            return array.findIndex(i=>i[key] == value)
        },
        noData(data){
            return data == undefined ? true : (data.length > 0) ? true : false;
        },
        extractSex(data){
            return data == 1 ? 'Male' : 'Female';
        },
        showeditStudent(data){
            this.post = data;
            $('.student').modal('show');
        },
        showdeleteTeacher(data){
              this.post = data;
            $('.delete-teacher').modal('show');
        },
        deleteTeacher(data){
            this.$axios.get('sanctum/csrf-cookie').then(res=>{
                this.$axios.delete('api/teacher/'+data.id).then(res=>{
                    this.post = {};
                    this.listOfTeacher();
                    $('.delete-teacher').modal('hide');
                });
            });
        },
        restoreTeacher(data){
            this.$axios.get('sanctum/csrf-cookie').then(res=>{
                this.$axios.get('api/teacher/restore/'+data.id).then(res=>{
                    this.post = {};
                    this.listOfTeacher();
                    $('.delete-teacher').modal('hide');
                });
            });
        },
        showCredential(data){
            if(data.account != null){
                this.post['id'] = data.account.id;
                this.post['username'] = data.account.username;
                this.post['email'] = data.account.email;
            }else{
                this.post = data;
            }
          
            $('.credential').modal('show');
        },
        saveCredential(data){
            this.$axios.get('sanctum/csrf-cookie').then(res=>{
                this.$axios.post('api/teacher/credential/',data).then(res=>{
                    this.post = {};
                    this.listOfTeacher();
                    $('.credential').modal('hide');
                }).catch(err=>{
                    this.error = '';
                    this.errors = err.response.data.errors
                });
            });
        }
        ,userExtract(id){
            this.$axios.get('sanctum/csrf-cookie').then(res=>{
                this.$axios.get('api/teacher/'+id).then(res=>{
                  this.user = res.data;
                  let data = res.data;
                  if(data.advise != null){
                        this.advisory_id = data.advise.id;
                  }
                //   this.userAdvise(res.data.id)
                });
            });
        },
        setasClass(data){
            this.listasClass.push(data);
            this.listasClass = this.useUnique(this.listasClass,(a, b) => a.id == b.id);
        },
        useUnique(vl,fn){
           let arr =  vl.reduce((acc, v) => {
                if (!acc.some(x => fn(v, x))) acc.push(v);
                return acc;
            },[])
            return arr; 
        },
        removeStudent(index){
             this.listasClass.splice(index, 1);
        },
        saveAsStudent(){
            this.post['adviser'] = this.$route.params.advisory_id;
            this.post['students'] = this.listasClass;
            this.$axios.get('sanctum/csrf-cookie').then(res=>{
                this.$axios.post('api/teacher-advisory/', this.post).then(res=>{
                   this.$router.push({name:'tstudent'});
                });
            });
        },
        
        
    },
    mounted(){
        this.listOfStudent();
        // this.userExtract(window.Laravel.user.id);
    }

}
</script>

<style>

</style>
