<template>
    <div class="container">
        <h4>Set up Schedule</h4>
        <div class="row">
            <div class="col-md-6 p-3 border-right">
                <div class="form-group">
                    <label>Subject</label>
                    <select v-model="post.subject" class="form-control">
                        <option v-for="(list, index) in subjects" :key="index" :value="list.id" >{{ list.subject_name }}</option>
                        
                    <span class="errors-material" v-if="errors.subject">{{errors.subject[0]}}</span>
                    </select>
                </div>
                <div class="form-group">
                    <label>Day</label>
                    <select v-model="post.day" class="form-control">
                        <option v-for="(list, index) in days" :key="index" :value="list.val">{{ list.lbl }}</option>
                       
                    </select>
                    <span class="errors-material" v-if="errors.subject">{{errors.subject[0]}}</span>
                </div>
                <div class="row mb-3">
                    <div class="form-group col-md-6">
                        <label>Time From:</label>
                        <Datepicker v-model="post.time_from" timePicker :is24="false" />
                        <span class="errors-material" v-if="errors.time_from">{{errors.time_from[0]}}</span>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Time To:</label>
                        <Datepicker v-model="post.time_to" timePicker :is24="false" />
                        <span class="errors-material" v-if="errors.time_to">{{errors.time_to[0]}}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="btn-group col-md-12">
                        <button type="button" @click="saveSchedule()" class="btn btn-primary">{{ btn_cap}}</button>
                    </div>
                </div>
               
            </div>
            <div class="col-md-6">
               <div class="mb-3">
                   <input type="text" v-model="tableData.search"  placeholder="Search ..." @keyup.enter="listSchedule()" class="form-control">
               </div>
               <div class="table-responsive">
                   <table class="table table-striped table-sm">
                       <thead>
                           <tr>
                               <th>Subject</th>
                               <th>Day</th>
                               <th>Time</th>
                               <th></th>
                           </tr>
                       </thead>
                       <tbody>
                           <tr v-for="(list, index) in schedules" :key="index">
                               <td>{{list.subject_name}}</td>
                               <td>{{ extractDay(list.sday) }}</td>
                               <td>{{ conver12Hour(list.stime_from) }} - {{ conver12Hour(list.stime_to) }}</td>
                               <td>
                                   <div class="btn-group">
                                       <button type="button" @click="editSchedule(list)" class="btn btn-warning btn-sm">Edit</button>
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
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { ref } from 'vue';

export default {
    components:{
        Datepicker
    },
    setup() {
        const time = ref({ 
            hours: new Date().getHours(),
            minutes: new Date().getMinutes()
        });

        const conver12Hour = (time)=>{
            time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];
            if (time.length > 1) { // If time format correct
                time = time.slice (1);  // Remove full string match value
                time[5] = +time[0] < 12 ? 'AM' : 'PM'; // Set AM/PM
                time[0] = +time[0] % 12 || 12; // Adjust hours
            }
            return time.join ('')
        }
        
        return {
            time,
        }
    },
    data(){
        return{
            post:{
            },
            errors:[],
            btn_cap:'Save',
            subjects:[],
            days:[
                {'lbl':'Monday', 'val':1},
                {'lbl':'Tuesday', 'val':2},
                {'lbl':'Wednesday', 'val':3},
                {'lbl':'Thursday', 'val':4},
                {'lbl':'Friday', 'val':5},
                ],
            schedule:{},
            schedules:[],
            tableData:{
                search:""
            }
        }
    },
    methods:{
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
        saveSchedule(){
            if(this.post.id > 0 ){
                this.btn_cap = "Saving...";
                this.$axios.get('sanctum/csrf-cookie').then(res=>{
                    this.$axios.put('api/schedule/'+this.post.id,this.post).then(res=>{
                        this.btn_cap = "Save";
                        this.listSchedule();
                        this.post = {};
                        this.errors = [];
                    }).catch(err=>{
                        this.btn_cap = "Save";
                        this.errors = err.response.data.errors
                    });
                });
            }else{
                this.btn_cap = "Saving...";
                this.$axios.get('sanctum/csrf-cookie').then(res=>{
                    this.$axios.post('api/schedule',this.post).then(res=>{
                        this.btn_cap = "Save";
                        this.listSchedule();
                        this.post = {};
                        this.errors = [];
                    }).catch(err=>{
                        this.btn_cap = "Save";
                        this.errors = err.response.data.errors
                    });
                });
            }
          
        },
        listSchedule(url='api/schedule'){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.$axios.get(url,{params:this.tableData}).then(res=>{
                    this.schedules =  res.data
                }).catch(err=>{
                
                });
            });
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
        extractDay(day){
            let ret = "";
            $.each(this.days, (key, value)=>{
                if(value.val == day){
                    ret = value.lbl;
                }
            });
            return ret;
        },
        editSchedule(data){
            this.errors = [];
            this.post.id = data.id;
            this.post.subject = data.subject_id;
            this.post.day = data.sday;
            // this.post.time_to = new Date().getTime(data.stime_to);
            // this.post.time_from = new Date(data.stime_from);
            this.btn_cap = "Save Changes"
        }
    },
    mounted(){
        this.listSchedule();
        this.subjectData();
    }
    
}
</script>