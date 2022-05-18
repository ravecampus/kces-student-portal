import {createWebHistory, createRouter} from "vue-router";

import Login from '../components/Login';
import Register from '../components/Register';
import Notfound from '../components/Notfound';
import Admin from '../admin/Dashboard';
import AdminTeacher from '../admin/Teacher';
import Advisory from '../admin/Advisory';
import AdminSubject from '../admin/Subject';
import Section from '../admin/Section';
import SchoolYear from '../admin/SchoolYear';
import AdminProfile from '../admin/AdminProfile';
import AdminAnnounce from '../admin/Announcement';


import TStudent from '../teacher/Tstudent';
import Teacher from '../teacher/BaseTeacher';
import SearchStudentInfo from '../teacher/SearchStudentInfo';
import TProfile from '../teacher/TProfile';
import TGrade from '../teacher/TGrade';
import TGradeDownload from '../teacher/TGradeDownload';
import TSchedule from '../teacher/TSchedule';
import TAnnounce from '../teacher/TAnnouncement';

import Student from '../student/BaseStudent';
import SAnnouncement from '../student/SAnnouncement';
import SProfile from '../student/SProfile';
import SGrade from '../student/SGrade';
import SSchedule from '../student/SSchedule';


export const routes = [
    {
        name: 'login',
        path: '/',
        component: Login
    },
    {
        name: 'register',
        path: '/iger-admin/20windevs19',
        component: Register
    },
    {
        name: 'admin',
        path: '/admin/',
        component: Admin,
        children:[
            {
                name:'adminteacher',
                path:'',
                component: AdminTeacher
            },
            {
                name:'advisory',
                path:'/admin/advisory',
                component: Advisory,
            },
            {
                name:'adminsubject',
                path:'/admin/subjects',
                component: AdminSubject,
            },
            {
                name:'section',
                path:'/admin/section',
                component: Section,
            },
            {
                name:'schoolyear',
                path:'/admin/school-year',
                component: SchoolYear,
            },
            {
                name:'adminprofile',
                path:'/admin/profile',
                component: AdminProfile,
            },
            {
                name:'adminannounce',
                path:'/admin/announcement',
                component: AdminAnnounce,
            },
        ]
    },
    {
        name: 'student',
        path: '/student',
        component: Student,
        children:[
            {
                name:'sannouncement',
                path:'',
                component: SAnnouncement
            },
            {
                name:'sprofile',
                path:'/student/profile',
                component: SProfile
            },
            {
                name:'sgrade',
                path:'/student/mygrade',
                component: SGrade
            },
            {
                name:'sschedule',
                path:'/student/schedule',
                component: SSchedule
            },
        ]
    },
    {
        name: 'teacher',
        path: '/teacher',
        component: Teacher,
        children:[
            {
                name:'tstudent',
                path:'',
                component: TStudent
            },
            {
                name:'tstudentinfo',
                path:'/teacher/setup/:advisory_id',
                component: SearchStudentInfo
            },
            {
                name:'tprofile',
                path:'/teacher/profile/',
                component: TProfile
            },
            {
                name:'tgrade',
                path:'/teacher/grade/',
                component: TGrade
            },
            {
                name:'tgradedownload',
                path:'/teacher/grade/download/:student_id',
                component: TGradeDownload
            },
            {
                name:'tschedule',
                path:'/teacher/schedule/',
                component: TSchedule
            },
            {
                name:'tannounce',
                path:'/teacher/announcement/',
                component: TAnnounce
            },
        ]
    },
    {
        path: '/:pathMatch(.*)*',
        name:'/notfound',
        component: Notfound
    },

];


const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

const openRoutes = [
    'register', 
    'login',
    'notfound',
];

router.beforeEach((to, from, next)=>{
    if(openRoutes.includes(to.name) || window.Laravel.isLoggedin){
        // if(window.Laravel.user.role == 3){
        //     next();
        // }
        next();
    }
});

export default router;