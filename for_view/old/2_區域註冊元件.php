<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
  <script src="../for_js/vue.js"></script>
  <link rel="stylesheet" type="text/css" href="../for_css/gridstyle.css">
  <link rel="stylesheet" type="text/css" href="../for_css/style.css">
  <link rel="stylesheet" type="text/css" href="../for_css/1101/style.css">
  <link rel="stylesheet" type="text/css" href="../for_css/v-minusplusfield.css">

  <script src="../for_js/knockout-3.5.1.debug.js"></script>
  <script src="../for_js/vue.min.js"></script>
  <script src="../for_js/axios.min.js"></script>
</head>
<body>
  <div id="app">
    <clockitem v-for="(item,index) in alarmclock" :clocklist="item"></clockitem>
  </div>

  <template id="clockitem"> 
    <pre>{{clocklist}}</pre>
  </template>
  
</body>
<script type="text/javascript">

  var clockitemvalue = {
    // 和全局组件一样,props中的属性值是html的v-bind中绑定的
    props: ['clocklist'],
    template:"#clockitem",
    watch: { 
      clocklist: {
        handler (newVal, oldVal) {
          console.log('區域Prop changed: ', newVal, ' | was: ', oldVal)
        },
        immediate: true, //立即监听
        deep: true // 深度监听
      }
    }
  } 

  var app = new Vue({
    el: "#app",
    data: {
      alarmclock:[],
    },

    components:{
      'clockitem':clockitemvalue,
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
    }    
  })
</script>
</html>