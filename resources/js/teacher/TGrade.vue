<template>
    <div class="container">
        <!-- <button type="button" @click="addToClass()" class="btn btn-primary">
            <span class="fa fa-plus"></span>
            Add to your Class</button> -->
        <h4>Grade Setup</h4>
        <div class="row border-top border-bottom mt-2">
            <div class="col-md-12">
                 <div class="card-body">
                    <div class="col-md-12 mt-2">
                        <div class="row search-head">

                            <div class="col-md-7 mb-2 pl-0">
                                <input type="text" class="form-control" v-model="tableData.search"  placeholder="Search ..." @keyup.enter="listOfStudentA()">
                            </div>
                            <!-- <div class="col-md-3">
                                <div class="col-md-12 pull-right">
                                    <select class="form-control" aria-placeholder="sort" v-model="tableData.archive" @change="listOfStudentA()">
                                        <option value="0">Active</option>
                                        <option value="1">Inactive</option>
                
                                    </select>
                                </div>
                            </div> -->
                            <div class="col-md-2">
                                <div class="col-md-12 pull-right">
                                    <select class="form-control" aria-placeholder="sort" v-model="tableData.length" @change="listOfStudentA()">
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
                                        <button type="button" @click="viewSummaryGrade(list)" class="btn btn-warning btn-sm">View</button>
                                        <button type="button" @click="showGrade(list)" class="btn btn-success btn-sm">Edit</button>
                                        <!-- <button type="button" @click="editInfo(list)" class="btn btn-warning btn-sm">Edit Info</button> -->
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5" v-show="!noData(students)">
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
        </div>
         <div class="modal fade grade">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Student Evaluation Card</h4>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive" v-if="gradeStatus.grade != null">
                            <table class="table table-bordered table-dark">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>1st Quarter</th>
                                        <th>2nd Quarter</th>
                                        <th>3rd Quarter</th>
                                        <th>4th Quarter</th>
                                        <th>Final Rating</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(list, index) in gradeStatus.grade" :key="index">
                                        <td>{{ extractSubject(list.subject_id) }}
                                        </td>
                                        <td>
                                            {{ list.first}}
                                        </td>
                                        <td>
                                             {{ list.second}}
                                        </td>
                                        <td>
                                             {{ list.third}}
                                        </td>
                                        <td>
                                             {{ list.fourth }}
                                        </td>
                                        <td>
                                            {{list.average }}
                                        </td>
                                        <td>
                                            {{ list.remarks }}
                                        </td>
                                        <td v-if="gradeStatus.status != 1">
                                            <button type="button" @click="showSetup(list)" class="btn btn-warning btn-sm">Set up</button>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td colspan="5">
                                           <h5 class="pull-right text-white"> General Average</h5>
                                        </td>
                                        <td>{{ getAverage(gradeStatus.grade)}}</td>
                                        <td colspan="2">
                                            <button type="button" @click="finalGrade(gradeStatus)" class="btn btn-primary">
                                                {{ btn_final }}
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center" v-if="gradeStatus.grade == null">
                            <button type="button"  @click="generateSubject()" class="btn btn-warning btn-lg">Generate Subjects</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <!-- <button type="button"  @click="saveStudent"  class="btn btn-primary btn-lg">{{btn_cap}}</button> -->
                            <button type="button" data-dismiss="modal"  class="btn btn-default">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade grade-setup mt-5">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <h5>Grade per Quarter</h5>
                        <div class="row">
                            <div class="col-md-12">
                            <div class="row">
                                
                               <div class="col-md-6 form-group">
                                   <label>First Quarter</label>
                                   <input type="number" v-model="grade.first" class="form-control input-sm">
                               </div>
                            
                               <div class="col-md-6 form-group">
                                   <label>Second Quarter</label>
                                   <input type="number" v-model="grade.second" class="form-control input-sm">
                               </div>
                            </div>
                            <div class="row">
                               <div class="col-md-6 form-group">
                                   <label>Third Quarter</label>
                                   <input type="number" v-model="grade.third" class="form-control input-sm">
                               </div>
                               <div class="col-md-6 form-group">
                                    <label>Fourth Quarter</label>
                                   <input type="number" v-model="grade.fourth" class="form-control input-sm">
                               </div>
                            </div>   
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button type="button" @click="saveGrade()"  class="btn btn-warning">Save</button>
                            <button type="button" @click="cancelGradeModal()" class="btn btn-defaul">No</button>
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
            btn_final:"Final Review",
            students:[],
            gradeStatus:{},
            grade:{
                // first:[],
                // second:[],
                // third:[],
                // fourth:[],
                // average:[],
                // remarks:[],
            },
            user:{},
            post:{  
            },
            subjects:[],
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
                advisory_id:0,
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
        addToClass(){
            this.$router.push({name:'tstudentinfo', params:{'advisory_id':this.advisory_id}});
        },
        showSetup(data){
            this.grade = data;
            $('.grade').modal('hide');
            $('.grade-setup').modal('show');
        },
        showGrade(data){
            this.post = data;
            this.listOfGrade();
            $('.grade').modal('show');
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
                        this.listOfStudentA();
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
                        this.listOfStudentA();
                    }).catch(err=>{
                        this.error = '';
                        this.btn_cap = "Save";
                        this.errors = err.response.data.errors
                    });
                }

            });
        },

        listOfStudentA(url='api/teacher-advisory'){
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
                this.listOfStudentA();
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
        showCredential(data){
            if(data.account != null){
                this.post['username'] = data.account.username;
                this.post['email'] = data.account.email;
            }else{
                this.post = data;
            }
          
            $('.credential').modal('show');
        },
        userExtract(id){
            this.$axios.get('sanctum/csrf-cookie').then(res=>{
                this.$axios.get('api/teacher/'+id).then(res=>{
                  this.user = res.data;
                  let data = res.data;
                  if(data.advise != null){
                        this.advisory_id = data.advise.id;
                        this.tableData.advisory_id = data.advise.id;
                        this.listOfStudentA();
                  }
                });
            });
        },
        generateSubject(){
            this.$axios.get('sanctum/csrf-cookie').then(res=>{
                this.$axios.post('api/grade/', this.post).then(res=>{
                  this. listOfStudentA();
                  this.listOfGrade();
                });
            });
        },
        listOfGrade(){
            this.$axios.get('sanctum/csrf-cookie').then(res=>{
                this.$axios.get('api/grade/'+this.post.id).then(res=>{
                   this.gradeStatus = res.data;
                });
            });
        },
        subjectData(){
            this.$axios.get('sanctum/csrf-cookie').then(res=>{
                this.$axios.get('api/subject/list/').then(res=>{
                 this.subjects = res.data;
                });
            });
        },
        extractSubject(id){
            let ret = "";
            $.each(this.subjects, function(key, value) {
                if(value.id == id){
                    ret = value.subject_name
                }
                
            });
            return ret;
        },
        saveGrade(){
            this.computeGrade();
            this.$axios.get('sanctum/csrf-cookie').then(res=>{
                this.$axios.put('api/grade/'+this.grade.id, this.grade).then(res=>{
                    this.listOfGrade();
                    this.cancelGradeModal();
                }).catch(err=>{

                });
            });
        },
        cancelGradeModal(){
            $('.grade-setup').modal('hide'); 
            $('.grade').modal('show');
        },
        computeGrade(){
            if(this.grade.first > 0 && this.grade.second >0 && this.grade.third > 0 && this.grade.fourth >0){
                let ret = (parseInt(this.grade.first) + parseInt(this.grade.second) + parseInt(this.grade.third) + parseInt(this.grade.fourth));
                this.grade.average = ret /4;
                if(this.grade.average > 75){
                    this.grade.remarks = "Passed"
                }else{
                    this.grade.remarks = "Failed";
                }
            }else{
                this.grade.average = "";
                this.grade.remarks = ""
            }
        },
        getAverage(data){
            let count = 0;
            let ret = 0;
            $.each(data,(key, value)=>{
                if(value.average != null){
                    count++;
                    ret += value.average;
                   
                }
            });
            
            let rr = (ret / count);
            return isNaN(rr) ? 0 : (ret / count); 
        },
        viewSummaryGrade(data){
            this.$router.push({name:'tgradedownload',params:{'student_id':data.student_id}});
        },
        checkSubject(data){
            // this.post = data;
            // this.listOfGrade();
            // if(this.gradeStatus.grade != null){
            //     return  true;
            // }

            // return false;
        },
        finalGrade(data){
             this.btn_final = "Reviewing ...";
             this.$axios.get('sanctum/csrf-cookie').then(res=>{
                this.$axios.get('api/grade/final/'+data.id).then(res=>{
                    this.btn_final = "Final Review";
                    this.listOfGrade();
                }).catch(err=>{

                });
            });
        }
    },
    mounted(){
        this.subjectData();
        this.listOfGrade();
        this.userExtract(window.Laravel.user.id);
        
    }

}
</script>

<style>

</style>
