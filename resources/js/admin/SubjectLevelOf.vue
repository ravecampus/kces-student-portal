<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4>Subjects / Level</h4>
                <div class="card">
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a v-for="(list, index) in grades"  :key="index" @click="showGradeModal(list)" href="#" class="list-group-item list-group-item-action" >
                               {{ list.lbl }}
                                </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade grade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>ADD SUBJECTS</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                              
                                    <div class="input-group">
                                        <select v-model="post.subject" class="custom-select" id="inputGroupSelect04">
                                            <!-- <option selected>Subjects...</option> -->
                                            <option v-for="(list, indx) in subjects" :key="indx"
                                            v-bind:value="list.id" >{{ list.subject_name }}</option>
                                        
                                        </select>
                                        <div class="input-group-append">
                                            <button @click="saveSubjectLevel()" class="btn btn-outline-secondary" type="button">{{ btn_cap }}</button>
                                        </div>
                                    </div>
                                    <span class="errors-material" v-if="errors.subject">{{errors.subject[0]}}</span>
                                    <div class="card mt-2">
                                        <!-- <div class="card-header"> -->
                                           
                                        <!-- </div> -->
                                        <div class="card-body">
                                             <h6>Subjects</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <tbody>
                                                        <tr v-for="(list,index) in subjectlevels" :key="index" >
                                                            <td><strong>{{list.subject_name }}</strong></td>
                                                            <td>
                                                                <button type="button" @click="deleteSubjectLevel(list.id)" class="btn btn-danger btn-sm pull-right">Delete</button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                               
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- <div class="btn-group">
                            <button type="button"  @click="saveSubject(post)"  class="btn btn-primary">Save</button>
                            <button type="button" data-dismiss="modal"  class="btn btn-default btn-sm">Cancel</button>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
        data(){
            return {
                grades:[
                    {'lbl':'Grade 1', 'val':1},
                    {'lbl':'Grade 2', 'val':2},
                    {'lbl':'Grade 3', 'val':3},
                    {'lbl':'Grade 4', 'val':4},
                    {'lbl':'Grade 5', 'val':5},
                    {'lbl':'Grade 6', 'val':6},
                ],
                post:{},
                subjects:[],
                errors:[],
                subjectlevels:[],
                btn_cap:"Save",
            }
        },
        methods:{
            showGradeModal(data){
                this.errors = [];
                this.post.grade = data.val;
                this.listSubjectLevel(data.val)
                $('.grade').modal('show');
            },
            subjectData(){
                this.$axios.get('sanctum/csrf-cookie').then(res=>{
                    this.$axios.get('api/subject/list/').then(res=>{
                        this.subjects = res.data;
                    });
                });
            },
            saveSubjectLevel(){
                this.btn_cap = "Saving...";
                this.$axios.get('sanctum/crf-cookie').then(res=>{
                    this.$axios.post('api/subject-level', this.post).then(res=>{
                        this.btn_cap = "Save";
                        this.listSubjectLevel(this.post.grade);
                        // this.post= {};
                        this.errors = [];
                    }).catch(err=>{
                        this.btn_cap = "Save";
                        this.errors = err.response.data.errors
                    });
                });
            },
            listSubjectLevel(id){
                this.$axios.get('sanctum/crf-cookie').then(res=>{
                    this.$axios.get('api/subject-level/'+id).then(res=>{
                        this.subjectlevels = res.data;
                    });
                });
            },
            deleteSubjectLevel(id){
                this.$axios.get('sanctum/crf-cookie').then(res=>{
                    this.$axios.delete('api/subject-level/'+ id).then(res=>{
                        this.listSubjectLevel(this.post.grade);
                        this.errors = [];
                    });
                });
            }
        },
        mounted(){
            this.subjectData();
            // this.listSubjectLevel();
        }
}
</script>

<style>

</style>
