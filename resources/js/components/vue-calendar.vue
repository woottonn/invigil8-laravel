<template>
    <div class="parentdiv">

        <div
            v-if='selectedDay'
            class='selected-day'>
            <h4 class="mb-4">{{ selectedDay.date.toDateString() }}
                <i class="fa fa-times text-danger" id="day_close" v-on:click="dayClose()"></i>
            </h4>

            <ul>
                <li
                    v-for='attr in selectedDay.attributes'
                    :key='attr.key'>
                    <a v-bind:href="'/exams/'+attr.customData.id">{{ attr.popover.label }}</a>
                </li>
            </ul>
        </div>

        <v-calendar  :columns="this.screenscol"
                     :rows="this.screensrow"
                     :is-expanded="$screens({ default: true, lg: false })"
                     :attributes='exams'
                     :max-date="new Date(this.theend[0],this.theend[1]-1,this.theend[2])"
                     :min-date="new Date(this.thestart[0],this.thestart[1]-1,this.thestart[2])"
                     @dayclick='dayClicked'
                     style="width:100%"
        ></v-calendar>

    </div>
</template>

<script>

    export default {

        mounted() {
        },

        data() {
            return {
                selectedDay: null, // Add state to store selected day
            }
        },
        props:{
            exams: {
                type: Array,
                required: false
            },
            screenscol: {},
            screensrow: {},
            daybox: {},
            theend: {
                type: Array,
                required: false
            },
            thestart: {
                type: Array,
                required: false
            },
        },
        methods: {
            dayClicked(day) {
                if(this.daybox===true) {
                    if(day.popovers){
                        this.selectedDay = day;
                        $('.vc-container').css('opacity', '0.2');
                    }
                }else{
                    if(day.popovers) {
                        window.location.href = '/exams/?date=' + encodeURI(day.date.toISOString().slice(0, 19).replace('T', ' ').slice(0, -9));
                    }
                }
            },
            dayClose(day) {
                this.selectedDay = false;
                $('.vc-container').css('opacity', '1.0');
            },
        },
    };



</script>

<style scoped>
    .selected-day{
        position: absolute;
        display: inline-block;
        z-index: 99999;
        left: 0;
        border: 1px solid #ccc;
        border-radius: 5px;
        background: #fff;
        min-height: 40%;
        height: 40%;
        max-height: 40%;
        padding: 2.5%;
        margin: 20%;
        width: 60%;
        overflow-y: auto;
        overflow-x: hidden;
    }
    ul{
        text-align:left;
    }
    #day_close{
        position: absolute;
        right: 0px;
        top: 0px;
        font-size: 20px;
        padding: 10px;
        cursor: pointer;
    }
</style>
