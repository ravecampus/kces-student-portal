<template>
    <div class="container">
      <div class="row">
          <div class="col-md-12">
                <div class="card" v-if="students.length <= 0">
                    <div class="card-body" >
                       No Class 
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
                        <p class="p-0 m-0 fsize">Year 2021-2022</p>
                    </div>
                    <div></div>
                </div>
                <div class="col-md-12 d-print-none">
                    <div class="btn-group pull-right mb-1">
                        <button type="button" v-if="students.length > 0"  @click="PrintWeb()" class="btn btn-success btn-sm">
                            <span class="fa fa-print"></span>
                        </button>
                    </div>
                </div>
                <div class="co-md-8">
                <div class="table-responsive" >
                    <table class="table table-bordered table-dark table-sm">
                        <thead>
                            <tr>
                                <th colspan="4" class="text-center">Class List</th>
                            </tr>
                            <tr class="text-center">
                                <th>LRN</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Sex</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center" v-for="(list, index) in students " :key="index">
                                <td>
                                   {{ list.lrn}}
                                </td>
                                <td>
                                    {{ list.last_name }},  {{ list.first_name }} {{ list.middle }} {{ list.suffix }}.
                                </td>
                                <td>
                                   {{ list.address}}
                                </td>
                                <td>
                                    {{ extractSex(list.sex) }}
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
            student:{},
            students:[],
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
        listOfStudentA(){
            let id = this.$route.params.id;
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.$axios.get('api/tadvisory/'+id).then(res=>{
                    console.log(res.data)
                    this.students = res.data;
                })
            });
        },
        
        PrintWeb(){
            const print = window;
            print.focus();
            print.print();
            print.close();
        },
        extractSex(data){
            return data == 1 ? 'Male' : 'Female';
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
        this.listLevelSY();
        this.listOfStudentA();
      
    }
}
</script>

<style>

</style>
