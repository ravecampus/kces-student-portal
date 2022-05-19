<template>
    <div class="container">
      <div class="row">
          <div class="col-md-12">
                <div class="card" v-if="schedules.length <= 0">
                    <div class="card-body" >
                        No posted of Schedule
                    </div>
                </div>
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
                 <div class="co-md-12" v-if="schedules.length > 0">
                     <ul class="list-inline m-0 p-0 pl-3">
                        <li class="list-inline-item w-5">
                            <div class="w-5 mr-4">
                                <label><strong>Name:</strong> {{ student.last_name }}, {{ student.first_name }} {{ student.middle_name }} {{ student.suffix }}.</label>
                            </div>
                        </li>
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
                        <button type="button" v-if="schedules.length > 0"  @click="PrintWeb()" class="btn btn-success btn-sm">
                            <span class="fa fa-print"></span>
                        </button>
                    </div>
                </div>
                <div class="co-md-8">
                <div class="table-responsive" v-if="schedules.length > 0">
                    <table class="table table-bordered table-dark table-sm">
                        <thead>
                            <tr>
                                <th colspan="4" class="text-center">Schedule</th>
                            </tr>
                            <tr>
                                <th>Subjects</th>
                                <th>Day</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(list, index) in schedules" :key="index">
                                <td>
                                    {{ list.subject_name }}
                                </td>
                                <td>
                                    {{ extractDay(list.sday) }}
                                </td>
                                <td>
                                        {{ conver12Hour(list.stime_from) }} - 
                                        {{ conver12Hour(list.stime_to) }}
                                </td>

                            </tr>
                           
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-12 d-none d-print-block">
                Print date: {{ format(new Date()) }}
            </div>
          </div>
      </div>
  
    </div>
</template>

<script>
export default {
    data(){
        return{
            schedules:[],
             days:[
                {'lbl':'Monday', 'val':1},
                {'lbl':'Tuesday', 'val':2},
                {'lbl':'Wednesday', 'val':3},
                {'lbl':'Thursday', 'val':4},
                {'lbl':'Friday', 'val':5},
                ],
            student:{},
            gradeDisplay:null,
            sectionDisplay:null,
            syDisplay:null,
        }
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
    methods:{
        userExtract(id){
            this.$axios.get('sanctum/csrf-cookie').then(res=>{
                this.$axios.get('api/student/'+id).then(res=>{
                    this.student = res.data;
                    this.levelSection(res.data);
                    this.levelSY(res.data);
                    this.listSchedule(res.data.teacher_advisory_id);
                });
            });
        },
        listSchedule(id){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.$axios.get('api/schedule/ind/'+id).then(res=>{
                    this.schedules =  res.data
                }).catch(err=>{
                
                });
            });
        },
        extractDay(day){
            let ret = "";
            $.each(this.days, (key, value)=>{
                if(value.val == day){
                    ret = value.lbl;
                }
            });
            return ret;
        },
        conver12Hour(time){
            time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];
            if (time.length > 1) { // If time format correct
                time = time.slice (1);  // Remove full string match value
                time[5] = +time[0] < 12 ? ' AM' : ' PM'; // Set AM/PM
                time[0] = +time[0] % 12 || 12; // Adjust hours
            }
            return time.join ('')
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
            print.close();
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
        this.listLevelSection()
        this.listLevelSY();
        this.userExtract(window.Laravel.user.id);
      
    }
}
</script>

<style>

</style>
