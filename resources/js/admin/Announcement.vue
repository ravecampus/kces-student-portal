<template>
    <div class="container">
        <h4>Setup Announcement</h4>
        <div class="row border-top mt-1">
            <div class="col-md-6 mt-3 border-right">
                <div class="row">
                    <div class="form-group col-md-7">
                        <label>Title</label>
                        <input type="text" v-model="post.title" class="form-control">
                        <span class="errors-material" v-if="errors.title">{{errors.title[0]}}</span>
                    </div>
                    <div class="form-group col-md-5 pl-0">
                        <label>Expiry Date:</label>
                        <Datepicker v-model="post.expiry_date" :format="format" />
                        <span class="errors-material" v-if="errors.expiry_date">{{errors.expiry_date[0]}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label>Deacription</label>
                    <textarea v-model="post.description" class="form-control"></textarea>
                    <span class="errors-material" v-if="errors.description">{{errors.description[0]}}</span>
                </div>
                <div class="row">
                    <div class="btn-group col-md-12 mt-3">
                        <button type="button" @click="saveAnnounce()" class="btn btn-primary">{{ btn_cap}}</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">

            </div>
        </div>
    </div>
</template>
<script>
import DataTable from '../table/DataTable'
import PaginationVue from '../table/Pagination';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';


    export default{
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
            return{
                post:{},
                errors:[],
                btn_cap:'Save',
            }
        }
    };
</script>