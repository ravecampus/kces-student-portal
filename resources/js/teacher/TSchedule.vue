<template>
    <div class="container">
        <div class="row">
            <div class="col-md-6 p-3 m-2">
                <div class="form-group border-right">
                    <label>ID Number: </label>
                    <input type="text" v-model="post.id_number" class="form-control">
                    <span class="errors-material" v-if="errors.id_number">{{errors.id_number[0]}}</span>
                </div>
            </div>
            <div class="col-md-6">

            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return{
            post:{},
            errors:[]
        }
    }
}
</script>