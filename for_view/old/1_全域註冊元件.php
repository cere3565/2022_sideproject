<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
  <script src="../for_js/vue.js"></script>
  <script src="../for_js/vue.min.js"></script>
  <script src="../for_js/axios.min.js"></script> 
  <!-- <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>-->
  <script src="../for_js/vuetify.min.js"></script>
</head>
<body>
  <div id="app">
    <sidebar-component v-for="(item,index) in alarmclock" :clocklist="item"></Sidebar-component>
  </div>
</body>

<template id="sidc"> 
  <pre>{{clocklist}}</pre>
</template>

<script>
  Vue.component('sidebar-component',{
    template: "#sidc",
    props: ['clocklist'],
    watch: { 
      // clocklist: function(newVal, oldVal) { // watch it
      //   console.log('全域Prop changed: ', newVal, ' | was: ', oldVal)
      // }
      clocklist: {
          handler (newVal, oldVal) {
          console.log('全域Prop changed: ', newVal, ' | was: ', oldVal)
        },
        immediate: true, //立即监听
        deep: true // 深度监听
      }
    }
  })

  var app = new Vue({
    el: "#app",
    data: {
      alarmclock:[],
    },

    mounted:function(){
      var that=this;
      //顯示資料
      this.getData();
      //顯示clock資料
      axios.get("../for_system/clocklist.php").then(function(res){
          that.alarmclock = res.data;
          console.log(that.alarmclock);
      }).catch(function(error){
          console.log(error);
      });
    },

    methods: {
      getData(){
        var that=this;
        axios.get("../for_system/clocklist.php").then(function(res){
          that.alarmclock=res.data;
        }).catch(function(error){
          console.log(error);
        });
      },
    },
    
  })
</script>
  
</html>