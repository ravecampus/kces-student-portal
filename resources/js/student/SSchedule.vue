<template>
    <div class="container">
      <div class="row">
          <div class="col-md-12">
                <div class="card">
                    <div class="card-body" v-if="schedules == null">
                        No posted of Schedule
                    </div>
                </div>
                <div class="table-responsive" v-if="schedules != null">
                    <table class="table table-bordered table-dark">
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
        }
    },
    methods:{
        userExtract(id){
            this.$axios.get('sanctum/csrf-cookie').then(res=>{
                this.$axios.get('api/student/'+id).then(res=>{
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
       
    },
    mounted(){
        this.userExtract(window.Laravel.user.id);
    }
}
</script>

<style>

</style>
