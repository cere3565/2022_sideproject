<!DOCTYPE html>
<html lang="zh">
<head>
  <meta charset="UTF-8">
  <title>選擇適用鬧鐘</title>
  <link rel="stylesheet" type="text/css" href="../for_css/normalize.css">
  <link rel="stylesheet" type="text/css" href="../for_css/font.css">
  <link rel="stylesheet" type="text/css" href="../for_css/project_style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css">  
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Material+Icons">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vuetify@2.1.6/dist/vuetify.min.css">
  
  <script src="../for_js/src/polyfill.min.js"></script>
  <script src="../for_js/src/vue.min.js"></script>
  <script src="../for_js/src/axios.min.js"></script>
  <script src="../for_js/src/vuetify.min.js"></script>
</head>
<body>
  <div id="app">
    <v-app id="inspire" class="body">
      <v-data-table v-model="selected" :headers="headers" :items="clockMenu" :single-select="singleSelect" 
                    item-key="ID" show-select class="my-table">
        <template v-slot:top>
          <v-toolbar flat>
            <v-toolbar-title>選擇鬧鐘</v-toolbar-title>
            <v-divider class="mx-4" inset vertical ></v-divider>
            <v-spacer></v-spacer>
            <v-btn color="primary" dark class="mb-2" @click = "saveclock">確定</v-btn>
          </v-toolbar>
          <v-divider></v-divider>
        </template>        
        <template v-slot:no-data>
          <v-subheader>無符合的鬧鐘</v-subheader>
        </template>
      </v-data-table>
    </v-app> 
    
  </div>
  
</body>
<script src="../for_js/clockmenu.js"></script>
</html>