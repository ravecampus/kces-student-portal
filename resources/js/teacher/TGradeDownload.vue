<template>
    <div class="container">
      <div class="row">
          <div class="col-md-12">
                <div class="d-flex justify-content-center">
                    <div></div>
                    <div class="text-center mb-5 d-none d-print-block">
                        <p class="p-0 m-0 fsize">Republic of the Philippines</p>
                        <p class="p-0 m-0"><strong>DEPARTMENT OF EDUCATION</strong></p>
                        <p class="p-0 m-0 fsize">Region XII</p>
                        <p class="p-0 m-0 fsize">Schools Division Office of Cotabato</p>
                        <p class="p-0 m-0 fsize">Kabacan North District</p>
                        <p class="p-0 m-0"><strong>KATIDTUAN CENTER ELEMENTARY SCHOOL</strong></p>
                        <p class="p-0 m-0"><strong>LEARNER'S PROGRESS REPORT CARD</strong></p>
                        <p class="p-0 m-0 fsize">{{ syDisplay }}</p>
                    </div>
                    <div></div>
                </div>
                <div class="co-md-12">
                     <ul class="list-inline m-0 p-0 pl-3">
                        <li class="list-inline-item w-5">
                            <div class="w-5 mr-4">
                                <label><strong>Name:</strong> {{ student.last_name }}, {{ student.first_name }} {{ student.middle_name }} {{ student.suffix }}.</label>
                            </div>
                        </li>
                        <li class="list-inline-item w-5">
                            <div class="w-5 mr-4">
                                <label><strong>Age:</strong> {{ student.age }}</label>
                            </div> 
                        </li>
                         <li class="list-inline-item w-5">
                            <div class="w-5 mr-4">
                                <label><strong>Sex:</strong> {{ extractSex(student.sex) }}</label>
                            </div> 
                        </li>
                    </ul>
                    <ul class="list-inline m-0 p-0 pl-3 d-none d-print-block">
                        <li class="list-inline-item w-5">
                            <div class="w-5 mr-4">
                                <label><strong>Grade:</strong> {{ gradeDisplay }}</label>
                            </div>
                        </li>
                        <li class="list-inline-item w-5">
                            <div class="w-5 mr-4">
                                <label><strong>Section:</strong> {{ sectionDisplay }}</label>
                            </div> 
                        </li>
                         <li class="list-inline-item w-5">
                            <div class="w-5 mr-4">
                                <label><strong>LRN:</strong> {{student.lrn}}</label>
                            </div> 
                        </li>
                    </ul>
                </div>
                <div class="col-md-12 d-print-none">
                    <div class="btn-group pull-right mb-1">
                        <button type="button" v-if="gradeStatus.grade != null" @click="PrintWeb()" class="btn btn-success btn-sm">
                            <span class="fa fa-print"></span>
                        </button>
                    </div>
                </div>
                <div class="table-responsive mt-2">
                    <table class="table table-bordered table-dark table-sm">
                        <thead>
                            <tr>
                                <th rowspan="2" class="text-center align-middle">Learning Areas</th>
                                <th colspan="4" class="text-center">Quarter</th>
                                
                                <th class="text-center align-middle" rowspan="2">Final Rating</th>
                                <th class="text-center align-middle" rowspan="2">Remarks</th>
                            </tr>
                              <tr>
                               
                                <th>1st Quarter</th>
                                <th>2nd Quarter</th>
                                <th>3rd Quarter</th>
                                <th>4th Quarter</th>
                                
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

                            </tr>
                            <tr>
                                <td colspan="5">
                                    <strong class="pull-right"> General Average</strong>
                                </td>
                                <td>{{ getAverage(gradeStatus.grade)}}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 d-none d-print-block">
                    Print date: {{ format(new Date()) }}
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
            {label:'Credential', name:null},
            {label:'', name:null},
            ];
        
        columns.forEach(column=>{
            sortOrders[column.name] = -1;
        });
        return{
            btn_cap:"Save",
            students:[],
            student:{},
            gradeStatus:{},
            sectionDisplay:null,
            gradeDisplay:null,
            syDisplay:null,
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
                        let adv = this.findActiveAdvisory(data);
                        this.levelSection(adv);
                        this.levelSY(adv);
                        this.advisory_id = data.advisory_id;
                        this.post.id = data.advisory_id;
                        this.post.student_id = this.$route.params.student_id;
                        // this.tableData.advisory_id = data.advise.id;
                        this.getStudent();
                  }
                //   this.userAdvise(res.data.id)
                });
            });
        },
        findActiveAdvisory(data){
            let ret = {};
             $.each(data.advise, function(key, value) {
               if(value.id == data.advisory_id){
                  ret = value;
               }
            });
            return ret;
        },
        getStudent(){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.$axios.post('api/teacher-advisory/ind',this.post).then(res=>{
                    console.log(res.data);
                    let data = res.data;
                    this.student = data;
                    this.listOfGrade(data.advisory_id)
                }).catch(err=>{
                
                });
            });
        },
        listOfGrade(id){
            this.$axios.get('sanctum/csrf-cookie').then(res=>{
                this.$axios.get('api/grade/'+id).then(res=>{
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
        listLevelSection(){
            this.$axios.get('sanctum/csrf-cookie').then(res=>{
                this.$axios.get('api/section/list').then(res=>{
                    this.sections = res.data;
                });
            });
        },
        PrintWeb(){
            const print = window;
            print.focus();
            print.print();
            // print.close();
        },
        levelSection(data){
            let ret = "";
            $.each(this.sections, function(key, value) {
               if(value.id == data.section_id){
                   ret = value;
               }
            });
           this.sectionDisplay  = ret.section_name;   
           this.gradeDisplay  = ret.level_of;   
        },

        listLevelSY(){
            this.$axios.get('sanctum/csrf-cookie').then(res=>{
                this.$axios.get('api/syear/list').then(res=>{
                    this.syears = res.data;
                });
            });
        },
        levelSY(data){
            let ret = "";
            $.each(this.syears, function(key, value) {
               if(value.id == data.school_year_id){
                   ret ="S.Y. "+ value.sy_name +" - "+(parseInt(value.sy_name) + 1);
               }
            });
            this.syDisplay = ret;         
        },

        
    },
    mounted(){
        this.subjectData();
        this.listLevelSection();
        this.listLevelSY();
        // this.studentwithAdviser();
        this.userExtract(window.Laravel.user.id);
        
    }

}
</script>

<style>
    .fsize{
        font-size: 11px;
    }
</style>
